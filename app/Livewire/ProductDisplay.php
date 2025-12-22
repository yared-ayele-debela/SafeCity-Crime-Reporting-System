<?php

namespace App\Livewire;

use App\Models\AppSetting;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Discount;
use App\Models\Group;
use App\Models\Month;
use App\Models\Product;
use App\Models\ProductFilter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use RealRashid\SweetAlert\Facades\Alert;

class ProductDisplay extends Component
{
    use WithFileUploads;

    public $products;
    public $months;
    public $appsettings;
    public $user;
    public $productIdToDelete;
    public $deleteId;
    public $isModalOpen = false;

    // for discount package
    public $discount_name;
    public $amount;
    public $max_product;
    public $min_product;
    public $discount_type;
    public $selectedDiscount;
    public $product_id;

    // to delete product
    public $productId;

    // file uploads
    public $product_image;
    public $product_video;

    public $status_comment;
    public $productIdBeingUpdated;


    public function mount()
    {
        $user = Auth::guard('admin')->user();
        if (!$user || !$user->hasPermissionByRole('view_product')) {
            return redirect('admin.errors.unauthorized');
        }
        $appsettings = AppSetting::all()->toArray();
        Session::put('page', 'products');
        $adminType = $user->type;
        $vendor_id = $user->vendor_id;

        if ($adminType == "vendor") {
            $vendorStatus = $user->status;
            if ($vendorStatus == 0) {
                Alert::toast('Your Vendor Account is not approved yet. Please make sure to fill your valid personal, business, and bank details', 'Inactive Vendor Account!', 'success');
                return redirect('admin/updatevendordetails');
            }
        }
     $productsQuery = Product::with([
    'attributes',
    'group' => function ($query) {
        $query->select('id', 'name');
    },
    'category' => function ($query) {
        $query->select('id', 'name');
    },
    'months'
]);


        if ($adminType == 'vendor') {
            $productsQuery = $productsQuery->where('vendor_id', $vendor_id);
        }

        $this->products = $productsQuery->get()->toArray();
        $this->months = collect(Month::all());
        $this->appsettings = $appsettings;
        $this->user = $user;

        $this->resetForm();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function resetStatus(){
        $this->status_comment='';
        $this->productIdBeingUpdated='';
    }

    public function toggleStatus($productId)
    {
        $user = Auth::guard('admin')->user();
        if (!$user || !$user->hasPermissionByRole('update product status')) {
            session()->flash('error', 'You don`t have permission to update this status');
            return;
        }
        $this->productIdBeingUpdated = $productId;
        $this->status_comment = '';
        $this->isModalOpen = true;
    }


    public function updateStatus()
    {
        $product = Product::find($this->productIdBeingUpdated);
        $product->status = $product->status == 1 ? 0 : 1;
        $product->status_comment = $this->status_comment;
        $product->save();
        $this->resetStatus();
        $this->closeModal();
        $this->mount();
        session()->flash('message', 'Product status updated successfully!');

    }


    public function is_seasonal($id)
    {
        $product = Product::findOrFail($id);
        if ($product) {
            $is_seasonal = $product->is_seasonal;
            $product->is_seasonal = !$is_seasonal;
            $product->update();
            Alert::toast('Is seasonal updated', 'success');
            $this->mount();
        }
    }

    // check if product is featured or not
    public function is_featured($productId)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit_product')) {
                return view('admin.errors.unauthorized');
            }
            $product = Product::find($productId);
            $product->is_featured = $product->is_featured == "Yes" ? "No" : "Yes";
            $product->update();

            // Alert::toast('Product has been featured!', 'success');
            $this->mount(); // Refresh the products list

        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    // for discount package
    public function resetForm()
    {
        $this->discount_name = '';
        $this->discount_type = "";
        $this->amount = "";
        $this->min_product = "";
        $this->max_product = '';
        $this->selectedDiscount = null;
        $this->productId = null;
        $this->product_image = null;
        $this->product_video = null;
    }

    public function create_discount_package($id)
    {
        $this->product_id = $id;
    }

    public function save_discount_package()
    {
        $this->validate([
            'discount_name' => 'required|string',
            'min_product' => 'required|numeric|min:0|max:100',
            'max_product' => 'required|numeric|min:0',
            'discount_type' => 'required',
            'amount' => 'required'
        ]);

        $discount = new Discount();
        $discount->product_id = $this->product_id;
        $discount->name = $this->discount_name;
        $discount->min_product = $this->min_product;
        $discount->max_product = $this->max_product;
        $discount->discount_type = $this->discount_type;
        $discount->amount = $this->amount;
        $discount->save();
        session()->flash('message', 'Discount Saved Successfully');
        $this->resetForm();
        $this->mount();
    }

    public function edit_discount_package($id)
    {
        $this->selectedDiscount = Discount::findOrFail($id);
        $this->discount_name = $this->selectedDiscount->name;
        $this->min_product = $this->selectedDiscount->min_product;
        $this->max_product = $this->selectedDiscount->max_product;
        $this->discount_type = $this->selectedDiscount->discount_type;
        $this->amount = $this->selectedDiscount->amount;
    }

    public function update_discount_package()
    {
        $this->validate([
            'discount_name' => 'required|string',
            'min_product' => 'required|numeric|min:0|max:100',
            'max_product' => 'required|numeric|min:0',
            'discount_type' => 'required',
            'amount' => 'required'
        ]);

        try {
            $discount = $this->selectedDiscount;
            // Update the discount fields
            $discount->name = $this->discount_name;
            $discount->min_product = $this->min_product;
            $discount->max_product = $this->max_product;
            $discount->discount_type = $this->discount_type;
            $discount->amount = $this->amount;
            // Save the updated discount
            $discount->save();
            session()->flash('message', 'Discount Updated Successfully');
            // Reset form and reload data
            $this->resetForm();
            $this->mount();
        } catch (\Exception $e) {
            // Handle potential errors gracefully
            session()->flash('error', 'Failed to update discount: something is wrong');
        }
    }

    public function add_new_discount_package()
    {
        $this->selectedDiscount = '';
    }

    public function update_discount_package_status($id)
    {
        $discount = Discount::find($id);
        $discount->status = $discount->status == 1 ? 0 : 1;
        $discount->update();
        $this->mount();
    }

    public function destroy_discount_package($id)
    {
        $product = Product::findOrFail($id);

        if (!empty($this->productImage) && Storage::disk('public/images')->exists($product->product_image)) {
            Storage::disk('public')->delete($product->product_image);
        }
        if (!empty($this->productVideo) && Storage::disk('public/videos')->exists($product->product_video)) {
            Storage::disk('public')->delete($product->product_video);
        }

        session()->flash('error', 'Discount Package Deleted Successfully');
        $this->dispatch('close-modal');
        $this->mount();
    }



    public function deleteProduct(int $id)
    {
        $this->productId = $id;
    }

    public function destroyProduct()
    {
        $product = Product::find($this->productId);

        if ($product->image) {
            Storage::disk('public')->delete($product->product_image);
        }
        $product->delete();

        session()->flash('message', 'Product Deleted Successfully');

        $this->dispatch('close-modal');
        $this->mount();

    }

    public function render()
    {
        return view('livewire.product-display', [
            'products' => $this->products,
            'months' => $this->months,
            'appsettings' => $this->appsettings,
            'user' => $this->user,
            'discounts' => Discount::where('product_id', $this->product_id)->get(),
            'categories' => Group::with('categories')->get()->toArray(),
            'brands' => Brand::where('status', 1)->get()->toArray(),
            'data' => Category::get(["name", "id"]),
            'color' => Color::all()->where('status', 1)
        ]);
    }
}
