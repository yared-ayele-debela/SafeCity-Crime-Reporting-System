<?php

namespace App\Http\Controllers\Front;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\Order;
use App\Models\Cart;
use App\Models\CmsPage;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Omnipay\Omnipay;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\ProductAttribute;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;

class PaypalController extends Controller
{
    private $gateway;

    public function __construct()
    {
        $this->gateway= Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId(env('PAYPAL_CLIENT_ID'));
        $this->gateway->setSecret(env('PAYPAL_CLIENT_SECRET'));
        $this->gateway->setTestMode(true);
    }

    public function paypal($value=''){
        $appsettings=AppSetting::all()->toArray();
        $cms_pages=CmsPage::get()->toArray();

        if(Session::has('order_id')){
            return view('NewFrontEndPage.paypal.paypal',compact('cms_pages','appsettings'));
        }else{
            return redirect('cart',compact('appsettings','cms_pages'));
        }
    }
    public function pay(Request $request){
        try{
            $paypal_amount=Helper::final_amount_currency_converter(Session::get('grand_total'));
            // $paypal_amount=round(Session::get('grand_total')/80,2);
            $response=$this->gateway->purchase(array(
                'amount'=>$paypal_amount,
                'currency'=>env('PAYPAL_CURRENCY'),
                'returnUrl'=>url('success'),
                'cancelUrl'=>url('error')
            ))->send();
            if($response->isRedirect()){
                $response->redirect();
            }
            else
            {
                return $response->getMessage();
            }
        }
        catch (\Throwable $th)
        {
            return $th->getMessage();
        }
    }

    public function success(Request $request){

        $appsettings=AppSetting::all()->toArray();
        $cms_pages=CmsPage::get()->toArray();

        if(!Session::has('order_id')){
            return redirect('cart');
        }

        if($request->input('paymentId') && $request->input('PayerID')){
            $transaction=$this->gateway->completePurchase(array(
                'payer_id'=>$request->input('PayerID'),
                'transactionReference'=>$request->input('paymentId'),
            ));
            $response=$transaction->send();

            if($response->isSuccessful()){
                $arr=$response->getData();
                $payment=new Payment;

                $payment->order_id=Session::get('order_id');
                $payment->user_id=Auth::user()->id;
                $payment->payment_id=$arr['id'];
                $payment->payer_id=$arr['payer']['payer_info']['payer_id'];
                $payment->payer_email=$arr['payer']['payer_info']['email'];
                $payment->amount=$arr['transactions'][0]['amount']['total'];
                $payment->currency=env('PAYPAL_CURRENCY');
                $payment->payment_status=$arr['state'];
                $payment->save();

                $payment_id=$arr['id'];
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
                        $message->to($email)->subject('Order Placed ');
                    });

                    foreach($orderDetails['orders_products'] as $key=>$order){
                        //Reduce Stock Script Starts
                        $getProductStock=ProductAttribute::isStokAvailable($order['product_id'],$order['product_size']);
                        $newStock= $getProductStock - $order['product_qty'];
                        ProductAttribute::where(['product_id'=>$order['product_id'],'size'=>$order['product_size']])->update(['stock'=>$newStock]);
                    }

                    //Empty the cart
                   Cart::where('user_id',Auth::user()->id)->delete();

                   Alert::toast('YOUR PAYMENT HAS BEEN CONFIRMED !!','success');

                   return redirect()->route('successfully.ordered',$payment_id);
                //    return view('NewFrontEndPage.paypal.success',compact('cms_pages','appsettings'));
            }else{
                return $response->getMessage();
            }
        }else{
            return "Payment Declined!";
        }
    }
    public function error(){
        $appsettings=AppSetting::all()->toArray();
        $cms_pages=CmsPage::get()->toArray();

      Alert::toast('YOUR PAYMENT HAS BEEN FAILD!!','error');
      return view('NewFrontEndPage.paypal.fail',compact('appsettings','cms_pages'));
    }

    public function payment_successfully(){

        $appsettings=AppSetting::all()->toArray();
        $cms_pages=CmsPage::get()->toArray();

        // Alert::toast('Your payment has been placed successfully!!','success');
        return view('NewFrontEndPage.paypal.success',compact('appsettings','cms_pages'));
    }



}
