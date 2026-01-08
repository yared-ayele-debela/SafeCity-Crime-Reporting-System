<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class BlogsCategoryController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('view blog category')) {
                return view('admin.errors.unauthorized');
            }
            $blog_categories = BlogCategory::paginate(10);
            $appsettings = AppSetting::all()->toArray();

            return view('admin.blog.category.index', compact('blog_categories', 'appsettings'));
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
            if (!$user || !$user->hasPermissionByRole('add blog category')) {
                return view('admin.errors.unauthorized');
            }
            $appsettings = AppSetting::all()->toArray();
            return view('admin.blog.category.create', compact('appsettings'));
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
            if (!$user || !$user->hasPermissionByRole('add blog category')) {
                return view('admin.errors.unauthorized');
            }
            if (!$request->isMethod('post')) {
                // Handle the error - Method not allowed
                Alert::toast('Method not allowed', 'error');
                return redirect()->route('cities'); // You can redirect to an appropriate location
            }

            $request->validate([
                'name' => 'required|unique:blog_categories'
            ]);

            $category = new BlogCategory();
            $category->name = $request->input('name');
            $category->status = 1;
            $category->save();

             $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Add Blog', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

            Alert::toast('Blog category has been saved successfully!', 'success');
            return redirect()->route('blog-categories');
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
            if (!$user || !$user->hasPermissionByRole('edit blog category')) {
                return view('admin.errors.unauthorized');
            }
            $blog_categories = BlogCategory::find($id);
            $appsettings = AppSetting::all()->toArray();

            return view('admin.blog.category.edit', compact('blog_categories', 'appsettings'));
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
            if (!$user || !$user->hasPermissionByRole('edit blog category')) {
                return view('admin.errors.unauthorized');
            }
            if (!$request->isMethod('put')) {
                // Handle the error - Method not allowed
                Alert::toast('Method not allowed', 'error');
                return redirect()->route('cities'); // You can redirect to an appropriate location
            }
            $request->validate([
                'name' => 'required'
            ]);

            $category = BlogCategory::find($request->input('id'));
            $category->name = $request->input('name');
            $category->save();

              $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Update Blog Category', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

            Alert::toast('Blog category has been updated successfully!', 'success');
            return redirect()->route('blog-categories');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel's built-in validation exception
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('delete blog category')) {
                return view('admin.errors.unauthorized');
            }
            $category = BlogCategory::find($id);
            $category->delete();

              $currentDateTime = Carbon::now();
            $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
            ActivityLogger::log( 'Delete Blog Category', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

            Alert::toast('Blog category has been deleted successfully!', 'error');
            return redirect()->route('blog-categories');
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function active($id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit blog category')) {
                return view('admin.errors.unauthorized');
            }
            $category = BlogCategory::find($id);
            $category->status = 1;
            $category->save();

            Alert::toast('Blog category has been activated!', 'success');
            return redirect()->route('blog-categories');
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function inactive($id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit blog category')) {
                return view('admin.errors.unauthorized');
            }
            $category = BlogCategory::find($id);
            $category->status = 0;
            $category->save();

            Alert::toast('Blog category has been inactivated successfully!', 'error');
            return redirect()->route('blog-categories');
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }
}