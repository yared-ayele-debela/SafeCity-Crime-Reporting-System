<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\CustomOrder;
use App\Models\CustomOrderProduct;
use App\Models\DeliveryMan;
use App\Models\EmailTemplate;
use App\Models\FastOrders;
use App\Services\ActivityLogger;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class CustomOrderController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('view custom order')) {
                return view('admin.errors.unauthorized');
            }
            $appsettings = AppSetting::all()->toArray();
            $custom_order = CustomOrder::with('custom_order_product')->get();

            return view('admin.custom_order.index', compact('custom_order', 'appsettings'));
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function create(){
        $appsettings = AppSetting::all()->toArray();

        return view('admin.custom_order.add_custom_order.add_custom_order',compact('appsettings'));
    }
    public function store_custom_order(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'customer_name' => 'required|string',
                'phone_number' => 'required|numeric',
                'productname.*' => 'required|string',
                'quantity.*' => 'required|string',
                'description.*' => 'required|string',
                'delivery_address.*' => 'required|string',
            ]);

            $rules = array(
                'customer_name' => 'required|string',
                'phone_number' => 'required|numeric',
                'productname.*' => 'required|string',
                'quantity.*' => 'required|string',
                'description.*' => 'required|string',
                'delivery_address.*' => 'required|string',
            );

            $error = Validator::make($request->all(), $rules);
            if ($error->fails()) {
                return response()->json([
                    'error'  => $error->errors()->all()
                ]);
            }

            $order_number = str_pad(mt_rand(1, 9999), 8, '0', STR_PAD_LEFT);
            $user_code = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $vendor_code = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            // dd($user_code);
            $order = CustomOrder::create([
                'order_number'=>$order_number,
                'user_code' => $user_code,
                'customer_name' => $validatedData['customer_name'],
                'phone_number' => $validatedData['phone_number'],

            ]);
            // dd($order);

            // Iterate through the product details and insert into order_product table
            foreach ($validatedData['productname'] as $key => $productName) {
                CustomOrderProduct::create([
                    'vendor_code' => $vendor_code,
                    'order_id' => $order->id,
                    'product_name' => $productName,
                    'quantity' => $validatedData['quantity'][$key],
                    'description' => $validatedData['description'][$key],
                    'delivery_address' => $validatedData['delivery_address'][$key],
                ]);
            }

                 $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Create Custom order', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

            Alert::toast("Custom Order placed successfully!", 'success');
            return redirect()->route('custom_orders');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel's built-in validation exception
            return redirect()->back()->withErrors($e->validator->errors())->withInput();

        } catch (\Exception $e) {
            // Log or handle the exception as needed
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }

    }

    public function invoice($id)
    {

        $custom_order = CustomOrder::with('custom_order_product')->where('id', $id)->first();
        $appsettings = AppSetting::all()->toArray();
        $html = view('admin.custom_order.invoice', compact('custom_order', 'appsettings'))->render();

        // Create a new Dompdf instance
        $dompdf = new Dompdf();

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        return $dompdf->stream('custom_order.pdf');
    }

    public function viewinvoice($id)
    {

        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('view custom order invoice')) {
                return view('admin.errors.unauthorized');
            }
            $custom_order = CustomOrder::with('custom_order_product')->where('id', $id)->first();

            // dd($custom_order);
            $appsettings = AppSetting::all()->toArray();

            return view('admin.custom_order.view_invoice', compact('custom_order', 'appsettings', 'id'));
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }


    public function detail($id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('view custom order detail')) {
                return view('admin.errors.unauthorized');
            }
            $appsettings = AppSetting::all()->toArray();
            $alldelivery_boys = DeliveryMan::all();
            $custom_order = CustomOrder::with('custom_order_product')->where('id', $id)->get();
            //  dd($alldelivery_boys);
            return view('admin.custom_order.detail', compact('appsettings', 'custom_order', 'alldelivery_boys'));
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit custom order')) {
                return view('admin.errors.unauthorized');
            }
            if (!$request->isMethod('post')) {
                // Handle the error - Method not allowed
                Alert::toast('Method not allowed', 'error');
                return redirect()->back();
            }

            $custom_order = CustomOrder::find($request->input('order_id'));
            // dd($update);
            if ($custom_order) {
                // dd($custom_order);
                $custom_order->status = $request->input('order_status');
                $custom_order->save();

                // $orderDetails=FastOrders::with('user')->where('id',$request->input('order_id'))->first()->toArray();

                // $email=$orderDetails['user']['email'];
                // // dd($email);
                // $messageData=[
                // 'email'=>$email,
                // 'name'=>$orderDetails['user']['name'],
                // 'order_id'=>$request->input('order_id'),
                // 'orderDetails'=>$orderDetails,
                // 'status'=>$request->input('order_status'),
                // ];

                // Mail::send('emails.fast_order_status',$messageData,function($message)use ($email){
                //     $message->to($email)->subject('Fast Order Item Status Updated - Byt Developers.in');
                // });

                 $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Update Custom order Status', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

                Alert::toast('Custom order status updated successfully!', 'success');
                return back();
            } else {
                Alert::toast('Something Went wrong!', 'error');
                return back();
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel's built-in validation exception
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function assign_to_delivery_boy(Request $request)
    {

        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('assign custom order to deliveryman')) {
                return view('admin.errors.unauthorized');
            }
            if (!$request->isMethod('post')) {
                // Handle the error - Method not allowed
                Alert::toast('Method not allowed', 'error');
                return redirect()->back();
            }
            // dd($request->all());
            $custom_order = CustomOrder::find($request->input('order_id'));
            // dd($custom_order);
            if (($custom_order->delivery_boy_id) == ($request->input('delivery_boy_id'))) {
                Alert::toast('This custom_order is already assigned to this delivery boy','error');
                return back();
            }

            $custom_order->delivery_boy_id = $request->input('delivery_boy_id');
            $custom_order->save();


            $deliveryDetails = DeliveryMan::select('first_name', 'last_name', 'email')->where('id', $request->input('delivery_boy_id'))->first()->toArray();

            $delvieryboy=DeliveryMan::where('id',$request->input('delivery_boy_id'))->first();
            // dd($delvieryboy);
            $delvieryboy->status="delivering";
            $delvieryboy->update();

            $orderDetails = CustomOrder::with('custom_order_product')->where('id', $request->input('order_id'))->first()->toArray();
            $email_template = EmailTemplate::first();

            // dd($orderDetails);
            //Send Order Status Update Email
            // $email = $deliveryDetails['email'];
            // $messageData = [
            //     'email_template' => $email_template,
            //     'email' => $email,
            //     'first_name' => $deliveryDetails['first_name'],
            //     'last_name' => $deliveryDetails['last_name'],
            //     'order_id' => $request->input('order_id'),
            //     'orderDetails' => $orderDetails,
            //     'status' => $request->input('order_status'),
            // ];

            // Mail::send('emails.assing_custom_order_to_delivery_boy', $messageData, function ($message) use ($email) {
            //     $message->to($email)->subject('New custom order has been assigned to you for delivery - Byt Developers.in');
            // });


                 $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log(  'Assign custom order to delivery man', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");


            Alert::toast('Order has been assigned to delivery boy', 'success');
            return redirect()->back();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel's built-in validation exception
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('delete custom order')) {
                return view('admin.errors.unauthorized');
            }
            // dd($id);
            $custom_order = CustomOrder::find($id);
            $custom_order->delete();


                 $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Delete Custom order', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");


            Alert::toast('Custom Order has been deleted!', 'error');
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }
}
