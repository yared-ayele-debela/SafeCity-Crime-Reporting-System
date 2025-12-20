<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Brand;
use App\Services\ActivityLogger;
use Illuminate\Support\Carbon;
use Intervention\Image\ImageManagerStatic as Image;

use Illuminate\Support\Facades\Auth;
use Psy\CodeCleaner\ReturnTypePass;
use RealRashid\SweetAlert\Facades\Alert;

class BrandController extends Controller
{
    //
    public function index()
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('view_brand')) {
                return view('admin.errors.unauthorized');
            }

            $appsettings = AppSetting::all()->toArray();
            $brands = Brand::all();

            return view('admin.brand.index', compact('brands', 'appsettings'));
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }


    public function create()
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('create_brand')) {
                return view('admin.errors.unauthorized');
            }
            $appsettings = AppSetting::all()->toArray();

            return view('admin.brand.addbrand', compact('appsettings'));
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        try {
            if (!$request->isMethod('post')) {
                // Handle the error - Method not allowed
                Alert::toast('Method not allowed', 'error');
                return redirect('admin/brands');
            }

            $this->validate($request, [
                'name' => 'required|string',
            ]);

            $brand = new Brand();

            if ($request->hasFile('image')) {
                $fileNameWithExt = $request->file('image')->getClientOriginalName();
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('image')->getClientOriginalExtension();
                $fileNameToStore = $fileName . '_' . time() . '.' . $extension;

                $request->file('image')->storeAs('public/brand', $fileNameToStore);

                // Save full URL
                $brand->image = asset('storage/brand/' . $fileNameToStore);
            }

            $brand->name = $request->input('name');
            $brand->save();

                     $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Add Ecommerce Brand', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");


            Alert::toast('Brand has been saved', 'success');
            return redirect('admin/brands');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel's built-in validation exception
            return redirect()->back()->withErrors($e->validator->errors())->withInput();

        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function edit($brand_id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit_brand')) {
                return view('admin.errors.unauthorized');
            }

            $appsettings = AppSetting::all()->toArray();

            $brand = Brand::find($brand_id);

            if (!$brand) {
                // Handle the case where the brand is not found
                Alert::toast('Brand not found', 'error');
                return redirect('admin/brands');
            }

            return view('admin.brand.editbrand', compact('brand', 'appsettings'));
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit_brand')) {
                return view('admin.errors.unauthorized');
            }
            if (!$request->isMethod('put')) {
                // Handle the error - Method not allowed
                Alert::toast('Method not allowed', 'error');
                return redirect('admin/brands');
            }

            $this->validate($request, [
                'name' => 'required|string',
            ]);

            $brand = Brand::find($request->input('id'));

            if (!$brand) {
                // Handle the case where the brand is not found
                Alert::toast('Brand not found', 'error');
                return redirect('admin/brands');
            }

            $brand->name = $request->input('name');

            if ($request->hasFile('image')) {
                // Delete old image if it exists
                if ($brand->image) {
                    $oldPath = str_replace(asset('storage') . '/', '', $brand->image); // convert URL to relative path
                    Storage::disk('public')->delete($oldPath);
                }

                // Prepare new image
                $fileNameWithExt = $request->file('image')->getClientOriginalName();
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('image')->getClientOriginalExtension();
                $fileNameToStore = $fileName . '_' . time() . '.' . $extension;

                $request->file('image')->storeAs('public/brand', $fileNameToStore);

                // Store full URL
                $brand->image = asset('storage/brand/' . $fileNameToStore);
            }


            $brand->update();

                   $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log(  'Update Ecommerce Brand', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

            Alert::toast('Brand has been updated', 'success');

            return redirect('admin/brands');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel's built-in validation exception
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }
    public function destory($brand_id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('delete_brand')) {
                return view('admin.errors.unauthorized');
            }

            $brand = Brand::find($brand_id);

            if ($brand->image) {
                $oldPath = str_replace(asset('storage') . '/', '', $brand->image); // convert URL to relative path
                Storage::disk('public')->delete($oldPath);
            }

            $brand->delete();

                   $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Delete Ecommerce Brand', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");


            Alert::toast('Brand has been deleted', 'error');
            return redirect('admin/brands');
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function active($brand_id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit_brand')) {
                return view('admin.errors.unauthorized');
            }
            $brand = Brand::find($brand_id);

            if (!$brand) {
                // Handle the case where the brand is not found
                Alert::toast('Brand not found', 'error');
                return redirect('admin/brands');
            }

            $brand->status = 1;
            $brand->update();

            Alert::toast('Brand has been activated', 'success');
            return redirect('admin/brands');
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function inactive($brand_id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit_brand')) {
                return view('admin.errors.unauthorized');
            }
            $brand = Brand::find($brand_id);

            if (!$brand) {
                // Handle the case where the brand is not found
                Alert::toast('Brand not found', 'error');
                return redirect('admin/brands');
            }

            $brand->status = 0;
            $brand->update();

            Alert::toast('Brand has been inactivated', 'info');
            return redirect('admin/brands');
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }
}
