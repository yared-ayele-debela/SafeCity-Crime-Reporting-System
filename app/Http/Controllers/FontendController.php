<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Product;
use App\Models\Admin;
use App\Models\Advertisement;
use App\Models\AppSetting;
use App\Models\Brand;
use App\Models\Category;
use App\Models\CmsPage;
use App\Models\FastOrders;
use App\Models\FlashDeal;
use App\Models\FlashDealProduct;
use App\Models\Group;
use App\Models\Rating;
use App\Models\TransferRequest;
use App\Models\Vendor;
use App\Models\VendorBussinessDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class FontendController extends Controller
{
    //
    public function index()
    {

        try {

            //display all vendor on main page
            $allvendor = Vendor::with('vendorbusinessdetails', 'adminvendor')->where('status', '1')->get();
            $allvendor = Vendor::where('id', '<>', '0')->get();

            $allvendor =  $allvendor->loadCount('products');

            $vendorRatingsCount = [];
            foreach ($allvendor as $vendor) {
                $id = $vendor->id; // Get the vendor ID within the loop

                $ratingCount = Rating::whereHas('product', function ($query) use ($id) {
                        $query->where('vendor_id', $id);
                    })
                    ->count();

                // Store the count for each vendor
                $vendorRatingsCount[$id] = $ratingCount;
            }

            $allbrands = Brand::all()->where('status', 1)->toArray();
            //display all product in main page
            $allproduct = Product::where('status',1)->paginate(20);
            $products = Product::latest()->take(7)->where('status', 1)->get()->toArray();
            //display all banner in main page
            $banners = Banner::where('type', 'Slider')->where('status', 1)->get()->toArray();
            $fixbanners = Banner::where('type', 'Fix')->where('status', 1)->get()->toArray();
            //  dd($allvendor);
            //display featured product in main page
            $isfeatured = Product::where('is_Featured', 'Yes')->where('status', 1)->limit(6)->inRandomOrder()->get()->toArray();
            //display discountedproduct
            $discountedproduct = Product::where('product_discount', '>', 0)->where('status', 1)->limit(6)->inRandomOrder()->get()->toArray();
            //display vendor shop
            $getVendorShop = Admin::with('vendorBusiness')->where('type', 'vendor')->get();

            $getCategory = Category::all()->where('parent_id', 0)->where('status', 1)->toArray();
            // dd($getCategory);
            $appsettings = AppSetting::where('id', 1)->get();

            $cms_pages = CmsPage::get()->toArray();
            $new_products = Product::orderBy('id', 'Desc')->where('status', 1)->limit(6)->get()->toArray();
            //  dd($new_products);
            $alladvertisement = Advertisement::all()->where('is_approved', '1')->toArray();

            $flash_deal_query = FlashDeal::query();
            $featured_flash_deal = $flash_deal_query
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->where('status', 1)
                ->get();
            // dd($featured_flash_deal);
            $group = Group::with('categories')->get();

            // dd($featured_flash_deal);
            $groups = Group::whereHas('categories', function ($query) {
                $query->havingRaw('count(*) = 4')->groupBy('group_id');
            })->with('categories')->get();

            // to display category banner
            $categoriesWithHighDiscount = Category::orderBy('discount', 'desc')
            ->take(6) // Fetch only the top 6 categories
            ->get();

            if (!empty($featured_flash_deal)) {
                $flash_deal_ids = $featured_flash_deal->pluck('id');
                // dd($flash_deal_ids);
                $flash_deal_product_query = FlashDealProduct::query();
                $flash_deal_product_query->whereIn('flash_deal_id', $flash_deal_ids);
                $flash_deal_products = $flash_deal_product_query->with('product')->limit(10)->get();


                $activated_flash_deal_query = FlashDeal::query();
                $activated_flash_deal_query = $activated_flash_deal_query->where("status", 1);
                return view('fontend.layout.lndex', compact('group','categoriesWithHighDiscount','vendorRatingsCount','groups','productbooks','productsmartphones','flash_deal_products', 'featured_flash_deal', 'new_products', 'allbrands', 'cms_pages', 'producthome', 'productbaby', 'productcoffee', 'productsnacks', 'alladvertisement', 'getCategory', 'appsettings', 'getVendorShop', 'allproduct', 'allvendor', 'banners', 'fixbanners', 'products', 'isfeatured', 'discountedproduct', 'menfashion'));
            } else {
                if (Auth::user()) {
                    $fast_orders = FastOrders::where('user_id', Auth::user()->id);
                    return view('fontend.layout.lndex', compact('group','categoriesWithHighDiscount','vendorRatingsCount','groups','fast_orders', 'flash_deal_products', 'featured_flash_deal', 'new_products', 'allbrands', 'cms_pages', 'producthome', 'productbaby', 'productcoffee', 'productsnacks', 'alladvertisement', 'getCategory', 'appsettings', 'getVendorShop', 'allproduct', 'allvendor', 'banners', 'fixbanners', 'products', 'isfeatured', 'discountedproduct', 'menfashion'));
                }
                return view('fontend.layout.lndex', compact('group','categoriesWithHighDiscount','vendorRatingsCount','groups','productbooks','productsmartphones','new_products', 'allbrands', 'cms_pages', 'producthome', 'productbaby', 'productcoffee', 'productsnacks', 'alladvertisement', 'getCategory', 'appsettings', 'getVendorShop', 'allproduct', 'allvendor', 'banners', 'fixbanners', 'products', 'isfeatured', 'discountedproduct', 'menfashion'));
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel's built-in validation exception
            return redirect()->back()->withErrors($e->validator->errors())->withInput();

        } catch (\Exception $e) {
            // Log or handle the exception as needed
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }



    public function category($id)
    {
        try {
            $getCategory = Category::all()->where('status', 1)->where('id', $id)->toArray();
            $allproduct = Product::with(['group', 'category', 'brand', 'images', 'vendor', 'attributes' => function ($query) {
                $query->where('stock', '>', 0)->where('status', 1);
            }])->where('category_id', $id)->paginate(20);
            $appsettings = AppSetting::all()->toArray();
            $cms_pages = CmsPage::get()->toArray();
            // dd($getCategory);
            return view('NewFrontEndPage.product_by_category.product_list', compact('cms_pages', 'appsettings', 'allproduct', 'getCategory'));
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function vendorListing($vendorid)
    {
        try {
            $cms_pages = CmsPage::get()->toArray();
            $appsettings = AppSetting::all()->toArray();
            $getVendorShop = Vendor::getVendorShop($vendorid);
            $gtvendor = VendorBussinessDetails::all()->where('vendor_id', $vendorid)->toArray();
            $vendorProducts = Product::with('brand')->where('vendor_id', $vendorid)->where('status', 1);
            $vendorProducts = $vendorProducts->paginate(30);
            // dd($vendorProducts);
            return view('FontendPages.vendor_listing', compact('cms_pages', 'gtvendor', 'appsettings', 'getVendorShop', 'vendorProducts'));
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function allproduct()
    {
        try {
            $appsettings = AppSetting::all()->toArray();
            $cms_pages = CmsPage::get()->toArray();
            $allproduct = Product::with(['group', 'category', 'brand', 'images', 'vendor', 'attributes' => function ($query) {
                $query->where('stock', '>', 0)->where('status', 1);
            }])->paginate(20);
            $countproducts=Product::count();
            // dd($countproducts);

            return view('NewFrontEndPage.product_by_category.allproduct_lists', compact('countproducts','cms_pages', 'allproduct', 'appsettings'));
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function fetchProductData(Request $request)
    {
        if ($request->ajax()) {
            $allproduct =  $allproduct = Product::with(['group', 'category', 'brand', 'images', 'vendor', 'attributes'])->where('status', 1)->paginate(20);
            return view('fontend.layout.autoloadproduct.data', compact('allproduct'));
        }
    }

    public function fetchData(Request $request)
    {
        if ($request->ajax()) {

            $allproduct =  $allproduct = Product::with(['group', 'category', 'brand', 'images', 'vendor', 'attributes'])->where('status', 1)->paginate(20);
            return view('NewFrontEndPage.product_by_category.data', compact('allproduct'));
        }

        // $allproduct =  $allproduct = Product::with(['group', 'category', 'brand', 'images', 'vendor', 'attributes'])->where('status', 1)->paginate(20);
        // if ($request->ajax()) {
        //     $view = view('NewFrontEndPage.product_by_category.data', compact('allproduct'))->render();
        //     return response()->json(['html' => $view]);
        // }
        // return view('/', compact('allproduct'));
    }


    public function aboutus()
    {
        try {
            $appsettings = AppSetting::all()->toArray();

            $cms_pages = CmsPage::get()->toArray();
            return view('FontendPages.about', compact('appsettings', 'cms_pages'));
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }
    public function shop()
    {
        try {
            $cms_pages = CmsPage::get()->toArray();
            $appsettings = AppSetting::all()->toArray();
            return view('FontendPages.shop', compact('appsettings', 'cms_pages'));
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }
    public function contact()
    {
        try {
            $cms_pages = CmsPage::get()->toArray();
            $appsettings = AppSetting::all()->toArray();
            return view('FontendPages.contact_us', compact('appsettings', 'cms_pages'));
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }
    public  function currency()
    {
        try {
            $cms_pages = CmsPage::get()->toArray();
            $appsettings = AppSetting::all()->toArray();
            return view("FontendPages.currency_converter.index", compact('appsettings', 'cms_pages'));
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function faq()
    {
        try {
            $cms_pages = CmsPage::get()->toArray();
            $appsettings = AppSetting::all()->toArray();
            return view('fontend.faq', compact('appsettings', 'cms_pages'));
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }
}
