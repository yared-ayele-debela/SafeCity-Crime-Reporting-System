<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\Cart;
use App\Models\CmsPage;
use App\Models\EmailTemplate;
use App\Models\Order;
use App\Models\ProductAttribute;
use Chapa\Chapa\Facades\Chapa as Chapa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;

class ChapaController extends Controller
{
    //
    /**
     * Initialize Rave payment process
     * @return void
     */
    protected $reference;

    public function __construct(){
        $this->reference = Chapa::generateReference();

    }
    public function chapa($value=''){
        $appsettings=AppSetting::all()->toArray();
        $cms_pages=CmsPage::get()->toArray();

        if(Session::has('order_id')){
            return view('NewFrontEndPage.chapa.chapa',compact('cms_pages','appsettings'));
        }else{
            return redirect('cart',compact('appsettings'));
        }
    }

    public function initialize()
    {
        //This generates a payment reference
        $reference = $this->reference;
        $paypal_amount=round(Session::get('grand_total'),2);
        $email=Auth::user()->email;

        // Enter the details of the payment
        $data = [

            'amount' => $paypal_amount,
            'email' => $email,
            'tx_ref' => $reference,
            'currency' => "ETB",
            'callback_url' => route('callback',[$reference]),
            'name' => Auth::user()->name,
            'city'=>Auth::user()->city,
            'mobile'=>Auth::user()->mobile,
            "customization" => [
                "title" => "Order Payment",
                "description" => "Pay for Prodcuts"
            ]
        ];


        $payment = Chapa::initializePayment($data);
        // dd($payment);

        if ($payment['status'] !== 'success') {
            // notify something went wrong
            return;
        }

        return redirect($payment['data']['checkout_url']);
    }

    /**
     * Obtain Rave callback information
     * @return void
     */
    public function callback($reference)
    {

        $data = Chapa::verifyTransaction($reference);
        dd($data);
        //if payment is successful
        if ($data['status'] =='success') {

            $order_id=Session::get('order_id');
            Order::where('id',$order_id)->update(['order_status'=>'Paid']);
            //Send by Email
             $orderDetails=Order::with('orders_products')->where('id',$order_id)->first()->toArray();
                //Send Order Email
                $email=Auth::user()->email;
                $email_template=EmailTemplate::first();
                $messageData=[
                    'email_template'=>$email_template,
                    'email'=>$email,
                    'name'=>Auth::user()->name,
                    'order_id'=>$order_id,
                    'orderDetails'=>$orderDetails
                ];
                Mail::send('emails.order',$messageData,function($message)use($email){
                    $message->to($email)->subject('Order Placed');
                });

                foreach($orderDetails['order_products'] as $key=>$order){
                    //Reduce Stock Script Starts
                    $getProductStock=ProductAttribute::isStokAvailable($order['product_id'],$order['product_size']);
                    $newStock= $getProductStock - $order['product_qty'];
                    ProductAttribute::where(['product_id'=>$order['product_id'],'size'=>$order['product_size']])->update(['stock'=>$newStock]);

                }

                //Empty the cart
               Cart::where('user_id',Auth::user()->id)->delete();
               $cms_pages=CmsPage::get()->toArray();
               $appsettings=AppSetting::all()->toArray();

               Alert::toast('YOUR PAYMENT HAS BEEN CONFIRMED !!','success');
               return view('NewFrontEndPage.chapa.success',compact('appsettings','cms_pages'));
        }
        else{
            //oopsie something ain't right.
        }
    }

    public function error(){
        $appsettings=AppSetting::all()->toArray();
        $cms_pages=CmsPage::get()->toArray();
       Alert::toast('YOUR PAYMENT HAS BEEN FAILD!!','error');
        return view('NewFrontEndPage.chapa.fail',compact('appsettings','cms_pages'));
      }


}
