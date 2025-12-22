<?php

namespace App\Livewire\Product;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Group;
use App\Models\Product;
use App\Models\ProductFilter;
use App\Models\Tax;
use App\Services\ActivityLogger;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use RealRashid\SweetAlert\Facades\Alert;

class AddProduct extends Component
{
    use WithFileUploads;

     // add product
     public $category;
     public $brand_id;
     public $product_name;
     public $product_code;
     public $product_price;
     public $quantity;
     public $product_discount;
     public $product_tax;
     public $product_weight;
     public $product_color;
     public $priceType;
     public $description;
     public $is_returnable;
     public $returnable_time;
     public $available_for_delivery;
    // file uploads
    public $product_image;
    public $product_video;
    public $selectedFilters = [];


     public function store_product()
    {
        $this->validate([
            'category' => 'required',
            'brand_id' => 'required',
            'product_name' => 'required|string|max:255',
            'product_code' => 'required|string|max:255|unique:products',
            'product_discount' => 'nullable|numeric|min:0|max:100',
            // 'product_price' => 'required|numeric',
            'product_image' => 'image|max:1024', // 1MB Max
            // 'product_video' => 'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4|max:20480', // 20MB Max
        ]);

        $product = new Product;

        $product->product_price = $this->product_price;
        $product->quantity = $this->quantity;

        $categoryDetails = Category::find($this->category);
        $product->group_id = $categoryDetails->group_id;
        $product->category_id = $this->category;
        $product->brand_id = $this->brand_id;

        $adminType = Auth::guard('admin')->user()->type;
        $vendor_id = Auth::guard('admin')->user()->vendor_id;
        $admin_id = Auth::guard('admin')->user()->id;
        $product->admin_type = $adminType;
        $product->admin_id = $admin_id;

        // $productFilters = ProductFilter::productFilters();
        // foreach ($productFilters as $filter) {
        //     $filterAvailable = ProductFilter::filterAvailable($filter['id'], $this->category);
        //     if ($filterAvailable == "Yes") {
        //         dump($this->selectedFilters);
        //         if (isset($filter['filter_column']) && $this->selectedFilters[$filter['filter_column']]) {
        //             $product->{$filter['filter_column']} = $this->selectedFilters[$filter['filter_column']];
        //         }
        //     }
        // }

        if ($adminType === "vendor" || $adminType==="Ecommerce Manager") {
            $product->vendor_id = $vendor_id;
        } else {
            $product->vendor_id = 1;
        }
        if (empty($this->product_discount)) {
            $this->product_discount = 0;
        }
        if (empty($this->product_weight)) {
            $this->product_weight = 0;
        }
        $productcode = Product::where('product_code', $this->product_code)->count();
        if ($productcode > 0) {
            Alert::toast('Product code is unavailable, please enter another product code', 'error');
            return redirect()->back();
        }
        $product->product_name = $this->product_name;
        $product->product_code = $this->product_code;
        $product->product_color = $this->product_color;
        $product->product_discount = $this->product_discount;
        $product->product_tax = $this->product_tax;
        $product->product_weight = $this->product_weight;
        $product->description = $this->description;
        if ($this->product_image) {
            $imagePath = $this->product_image->store('images', 'public');
            $product->product_image = asset('storage/' . $imagePath); // store full URL
        }

        if ($this->product_video) {
            $videoPath = $this->product_video->store('videos', 'public');
            $product->product_video = asset('storage/' . $videoPath); // store full URL
        }

        $product->status = 1;
        $product->is_featured = 'Yes';
        $product->returnable_time = $this->returnable_time;
        $product->available_for_delivery = $this->available_for_delivery;
        $product->is_returnable = $this->is_returnable;
        $product->save();

         $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Add Ecommerce Product', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");


        session()->flash('message', 'Product Saved Successfully');

        return redirect()->route('products');
    }


    public function render()
    {
        return view('livewire.product.add-product',[
            'categories' => Group::with('categories')->get()->toArray(),
            'brands' => Brand::where('status', 1)->get()->toArray(),
            'data' => Category::get(["name", "id"]),
            'color' => Color::all()->where('status', 1),
            'taxs' =>Tax::where('status',1)->get()
        ]);
    }
}
