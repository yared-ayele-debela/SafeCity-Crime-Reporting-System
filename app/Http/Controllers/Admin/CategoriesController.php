<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryFormRequest;
use App\Models\AdminsRole;
use App\Models\AppSetting;
use Intervention\Image\ImageManagerStatic as Image;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Group;
use App\Services\ActivityLogger;
use Illuminate\Support\Carbon;
use PhpParser\Node\Expr\FuncCall;
use RealRashid\SweetAlert\Facades\Alert;

class CategoriesController extends Controller
{
    //
    public function index()
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('view_category')) {
                return view('admin.errors.unauthorized');
            }

            $appsettings = AppSetting::all()->toArray();

            // Fetching categories with related data
            $categories = Category::with(['group', 'parentcategory'])->get()->toArray();

            return  view('admin.categories.allcategories', compact('appsettings', 'categories'));
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
            if (!$user || !$user->hasPermissionByRole('create_category')) {
                return view('admin.errors.unauthorized');
            }
            $getgroups = Group::all();
            $appsettings = AppSetting::all()->toArray();

            return  view('admin.categories.addcategory', compact('appsettings', 'getgroups'));
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('create_category')) {
                return view('admin.errors.unauthorized');
            }

            if (!$request->isMethod('post')) {
                // Handle the error - Method not allowed
                Alert::toast('Method not allowed', 'error');
                return redirect('admin/categories');
            }

            $this->validate($request, [
                'group_id' => 'required|string',
                'parent_id' => 'required',
                'name' => 'required|string',
                'discount' => 'required|integer',
                'description' => 'required|string',
                'url' => 'required|string',
                'image' => 'required|nullable'
            ]);

            $category = new Category();

            if ($request->hasFile('image')) {
                $fileNameWithExt = $request->file('image')->getClientOriginalName();
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('image')->getClientOriginalExtension();
                $fileNameToStore = $fileName . '_' . time() . '.' . $extension;

                $request->file('image')->storeAs('public/category', $fileNameToStore);

                // Store full URL
                $category->image = asset('storage/category/' . $fileNameToStore);
            }

            if ($request->hasFile('banner_image')) {
                $fileNameWithExt = $request->file('banner_image')->getClientOriginalName();
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('banner_image')->getClientOriginalExtension();
                $fileNameToStore = $fileName . '_' . time() . '.' . $extension;

                $request->file('banner_image')->storeAs('public/category', $fileNameToStore);

                // Store full URL
                $category->banner_image = asset('storage/category/' . $fileNameToStore);
            }


            $category->group_id = $request->input('group_id');
            $category->parent_id = $request->input('parent_id');
            $category->name = $request->input('name');
            $category->discount = $request->input('discount');
            $category->description = $request->input('description');
            $category->url = $request->input('url');
            $category->meta_title = $request->input('meta_title');
            $category->meta_description = $request->input('meta_description');
            $category->meta_keywords = $request->input('meta_keywords');
            $category->meta_title = $request->input('meta_title');
            $category->status = 1;

            $category->save();

                   $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Add Ecommerce Categories', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");


            Alert::toast('Category has been saved', 'success');
            return redirect('admin/categories');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel's built-in validation exception
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }


    public function update(Request $request)
    {
        try {
            if (!$request->isMethod('put')) {
                // Handle the error - Method not allowed
                Alert::toast('Method not allowed', 'error');
                return redirect('admin/categories');
            }

            $category = Category::find($request->input('id'));

            if (!$category) {
                // Handle the case where the category is not found
                Alert::toast('Category not found', 'error');
                return redirect('admin/categories');
            }

            $category->group_id = $request->input('group_id');
            $category->parent_id = $request->input('parent_id');
            $category->name = $request->input('name');
            $category->discount = $request->input('discount');
            $category->description = $request->input('description');
            $category->url = $request->input('url');
            $category->meta_title = $request->input('meta_title');
            $category->meta_description = $request->input('meta_description');
            $category->meta_keywords = $request->input('meta_keywords');
            $category->meta_title = $request->input('meta_title');

            if ($request->hasFile('image')) {
                // Delete old image if it exists (strip URL if previously saved as full URL)
                if ($category->image) {
                    $oldImagePath = str_replace(asset('storage') . '/', '', $category->image);
                    Storage::delete('public/' . $oldImagePath);
                }

                // Upload new image
                $fileNameWithExt = $request->file('image')->getClientOriginalName();
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('image')->getClientOriginalExtension();
                $fileNameToStore = $fileName . '_' . time() . '.' . $extension;

                $request->file('image')->storeAs('public/category', $fileNameToStore);

                // Store full URL
                $category->image = asset('storage/category/' . $fileNameToStore);
            }

            if ($request->hasFile('banner_image')) {
                // Delete old banner if it exists
                if ($category->banner_image) {
                    $oldBannerPath = str_replace(asset('storage') . '/', '', $category->banner_image);
                    Storage::delete('public/' . $oldBannerPath);
                }

                // Upload new banner
                $fileNameWithExt = $request->file('banner_image')->getClientOriginalName();
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('banner_image')->getClientOriginalExtension();
                $fileNameToStore = $fileName . '_' . time() . '.' . $extension;

                $request->file('banner_image')->storeAs('public/category', $fileNameToStore);

                // Store full URL
                $category->banner_image = asset('storage/category/' . $fileNameToStore);
            }


            $category->update();


                   $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log(  'Update Ecommerce Categories', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

            Alert::toast('Category has been updated', 'success');
            return redirect('admin/categories');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel's built-in validation exception
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit_category')) {
                return view('admin.errors.unauthorized');
            }

            $appsettings = AppSetting::all()->toArray();
            $categories = Category::find($id);
            $groups = Group::all();
            $getcategory = Category::with('subcategories')->where(['parent_id' => 0, 'group_id' => $categories['group_id']])->get();

            return  view('admin.categories.editcategories', compact('appsettings', 'categories', 'groups', 'getcategory'));
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function appendCategoryLevel(Request $request)
    {
        $user = Auth::guard('admin')->user();
        if (!$user || !$user->hasPermissionByRole('edit_category')) {
            return view('admin.errors.unauthorized');
        }
        if ($request->ajax()) {
            $data = $request->all();
            $getcategory = Category::with('subcategories')->where(['parent_id' => 0, 'group_id' => $data['group_id']])->get()->toArray();
            return  view('admin.categories.append_categories', compact('getcategory'));
        }
    }





    public function destory($categories_id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('delete_category')) {
                return view('admin.errors.unauthorized');
            }

            $category = Category::find($categories_id);
            if ($category->image) {
                $oldImagePath = str_replace(asset('storage') . '/', '', $category->image);
                Storage::delete('public/' . $oldImagePath);
            }

            if ($category->banner_image) {
                $oldBannerPath = str_replace(asset('storage') . '/', '', $category->banner_image);
                Storage::delete('public/' . $oldBannerPath);
            }

            $category->delete();


                   $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Delete Ecommerce Categories', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

            Alert::toast('Category has been deleted', 'error');
            return redirect('admin/categories');
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function active($categories_id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit_category')) {
                return view('admin.errors.unauthorized');
            }
            $category = Category::find($categories_id);
            $category->status = 1;
            $category->update();
            Alert::toast('Category has been activated', 'success');

            return redirect('admin/categories');
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function inactive($categories_id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit_category')) {
                return view('admin.errors.unauthorized');
            }
            $category = Category::find($categories_id);
            $category->status = 0;
            $category->update();
            Alert::toast('Category has been inactivated', 'error');

            return redirect('admin/categories');
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }
}
