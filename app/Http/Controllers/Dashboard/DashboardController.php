<?php

namespace App\Http\Controllers\Dashboard;

use App\Charts\MonthlyOrderChart;
use App\Charts\MonthlyUserRegisterChart;
use App\Charts\MonthlyUsersChart;
use App\Charts\Order_Payment_StatusChart;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AppSetting;

use App\Models\CustomOrderProduct;
use App\Models\DeliveryMan;
use App\Models\Offer;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Permissions;
use App\Models\Product;
use App\Models\Roles;
use App\Models\SafeCity\OfferDepartment;
use App\Models\SafeCity\Report;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\FuncCall;
use RealRashid\SweetAlert\Facades\Alert;

class DashboardController extends Controller
{
    //


    public function dashboard()
   {

    return view('admindashboard.dashboard', [
            'totalUsers' => User::count(),
            'totalReports' => Report::count(),
            'openReports' => Report::where('status', 'open')->count(),
            'resolvedReports' => Report::where('status', 'resolved')->count(),
            'totalOfficers' => Admin::where( 'type', 'officer')->count(),
            'totalAdmins' => Admin::where( 'type', 'admin')->count(),
            'total_officer_departments'=>OfferDepartment::count(),
            'total_role'=>Roles::count(),
            'total_permission'=>Permissions::count(),
        ]);
   }


    public function index(){

        return view('admindashboard.maindashboard')->withComponent(MainDashboard::class);;
    }

    public function order_reports(){

        $appsettings=AppSetting::all()->toArray();
        $current_month_order=Order::whereYear('created_at',Carbon::now()->year)->whereMonth('created_at',Carbon::now()->month)->count();
        $before_1_month_order=Order::whereYear('created_at',Carbon::now()->year)->whereMonth('created_at',Carbon::now()->subMonth(1))->count();
        $before_2_month_order=Order::whereYear('created_at',Carbon::now()->year)->whereMonth('created_at',Carbon::now()->subMonth(2))->count();
        $before_3_month_order=Order::whereYear('created_at',Carbon::now()->year)->whereMonth('created_at',Carbon::now()->subMonth(3))->count();

        $ordersCount=array($current_month_order,$before_1_month_order,$before_2_month_order,$before_3_month_order);
//        dd($ordersCount);die;
        return view('layouts.orders_reports',compact('appsettings','ordersCount'));
    }
    public function userscity(){

        $appsettings=AppSetting::all()->toArray();
        $getUserCity=User::select('city',DB::raw('count(city) as count'))->groupBy('city')->get();
//        dd($getUserCity);die;
       return view('layouts.city_reports',compact('getUserCity','appsettings'));
    }


    public function filterOrders(Request $request,MonthlyUsersChart $chart,MonthlyOrderChart $c,MonthlyUserRegisterChart $user,Order_Payment_StatusChart $payment)
    {
        try {

            if(!$request->method('post')) {
                Alert::toast('something is wrong!!', 'error');
                return redirect()->back();
            }

            $appsettings = AppSetting::all()->toArray();


            // Retrieve filter parameters
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            // Filter by Start Date and End Date
            if ($startDate && $endDate) {
                $orders = Order::with(['orders_products','orders_products.product'])
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->paginate(30);
            }

        $order_profile=new Order();

        $profits=$order_profile->calculateProfits();
        // dd($profits);

       $total_profit=$order_profile->calculateProfit();
       $totalPayment = $order_profile->calculateTotalPayment();
       $totalTaxPayments = $order_profile->calculateTotalTaxPayments();

        // dd($total_profit," ",$totalPayment," ",$totalTaxPayments);

        $current_month_users=User::whereYear('created_at',Carbon::now()->year)->whereMonth('created_at',Carbon::now()->month)->count();
        $before_1_month_users=User::whereYear('created_at',Carbon::now()->year)->whereMonth('created_at',Carbon::now()->subMonth(1))->count();
        $before_2_month_users=User::whereYear('created_at',Carbon::now()->year)->whereMonth('created_at',Carbon::now()->subMonth(2))->count();
        $before_3_month_users=User::whereYear('created_at',Carbon::now()->year)->whereMonth('created_at',Carbon::now()->subMonth(3))->count();
        $userCount=array($current_month_users,$before_1_month_users,$before_2_month_users,$before_3_month_users);
        $orderproducts=OrderProduct::all()->where('vendor_id',Auth::guard ('admin')->user()->vendor_id);
        $vendorproducts=Product::all()->where('vendor_id',Auth::guard('admin')->user()->vendor_id)->count();

        // dd($appsettings);
        $allproducts=Product::all()->where('status',1)->count();
        $allusers=User::all()->where('status',1)->count();
        $allorders=Order::all()->count();
        $pendingorders=Order::where('order_status','pending')->count();
        $deliverdorders=Order::where('order_status','delivered')->count();
        $paidorders=Order::where('order_status','paid')->count();

        $allvendors=Vendor::all()->where('status',1)->count();
        $latestOrders = Order::orderBy('created_at', 'desc')->take(5)->get();
        $latestproducts = Product::orderBy('created_at', 'desc')->take(5)->get();
        $alldeliveryboys= DeliveryMan::all()->count();
        $latestcustomorder = CustomOrderProduct::orderBy('created_at','desc')->take(5)->get();

        return view('layouts.payments.all_payments',compact('orders','profits','total_profit','totalPayment','totalTaxPayments','latestcustomorder','alldeliveryboys','pendingorders','deliverdorders','paidorders','latestproducts','latestOrders','orderproducts','vendorproducts','appsettings','allproducts','allusers','allorders','allvendors'),['c'=>$c->build(),'chart'=>$chart->build(),'user'=>$user->build(),'payment'=>$payment->build()]);
            } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function payments_report(Request $request, MonthlyUsersChart $chart,MonthlyOrderChart $c,MonthlyUserRegisterChart $user,Order_Payment_StatusChart $payment){

        try {
            //generate report for a users
            $orders = Order::with(['orders_products','orders_products.product'])->paginate(30);
            // dd($orders);
            $appsettings=AppSetting::all()->toArray();

            $order_profile=new Order();

            $profits=$order_profile->calculateProfits();
            // dd($profits);

            $total_profit=$order_profile->calculateProfit();
            $totalPayment = $order_profile->calculateTotalPayment();
            $totalTaxPayments = $order_profile->calculateTotalTaxPayments();

            // dd($total_profit," ",$totalPayment," ",$totalTaxPayments);

            $current_month_users=User::whereYear('created_at',Carbon::now()->year)->whereMonth('created_at',Carbon::now()->month)->count();
            $before_1_month_users=User::whereYear('created_at',Carbon::now()->year)->whereMonth('created_at',Carbon::now()->subMonth(1))->count();
            $before_2_month_users=User::whereYear('created_at',Carbon::now()->year)->whereMonth('created_at',Carbon::now()->subMonth(2))->count();
            $before_3_month_users=User::whereYear('created_at',Carbon::now()->year)->whereMonth('created_at',Carbon::now()->subMonth(3))->count();
            $userCount=array($current_month_users,$before_1_month_users,$before_2_month_users,$before_3_month_users);
            $orderproducts=OrderProduct::all()->where('vendor_id',Auth::guard ('admin')->user()->vendor_id);
            $vendorproducts=Product::all()->where('vendor_id',Auth::guard('admin')->user()->vendor_id)->count();

            // dd($appsettings);
            $allproducts=Product::all()->where('status',1)->count();
            $allusers=User::all()->where('status',1)->count();
            $allorders=Order::all()->count();
            $pendingorders=Order::where('order_status','pending')->count();
            $deliverdorders=Order::where('order_status','delivered')->count();
            $paidorders=Order::where('order_status','paid')->count();

            $allvendors=Vendor::all()->where('status',1)->count();
            $latestOrders = Order::orderBy('created_at', 'desc')->take(5)->get();
            $latestproducts = Product::orderBy('created_at', 'desc')->take(5)->get();
            $alldeliveryboys= DeliveryMan::all()->count();
            $latestcustomorder = CustomOrderProduct::orderBy('created_at','desc')->take(5)->get();

        return view('layouts.payments.all_payments',compact('orders','profits','total_profit','totalPayment','totalTaxPayments','latestcustomorder','alldeliveryboys','pendingorders','deliverdorders','paidorders','latestproducts','latestOrders','orderproducts','vendorproducts','appsettings','allproducts','allusers','allorders','allvendors'),['c'=>$c->build(),'chart'=>$chart->build(),'user'=>$user->build(),'payment'=>$payment->build()]);

        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }
}
