<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\BlogCategory;
use App\Models\Blogs;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class BlogsController extends Controller
{
    //
    public function index()
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('view blog')) {
                return view('admin.errors.unauthorized');
            }
            $blogs = Blogs::paginate(10);
            $appsettings = AppSetting::all()->toArray();

            return view('admin.blog.blogs.index', compact('blogs', 'appsettings'));
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
            if (!$user || !$user->hasPermissionByRole('add blog')) {
                return view('admin.errors.unauthorized');
            }
            $blog_category=BlogCategory::where('status',1)->get();
            $appsettings = AppSetting::all()->toArray();
            return view('admin.blog.blogs.create', compact('appsettings','blog_category'));
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
            if (!$user || !$user->hasPermissionByRole('add blog')) {
                return view('admin.errors.unauthorized');
            }
            if (!$request->isMethod('post')) {
                // Handle the error - Method not allowed
                Alert::toast('Method not allowed', 'error');
                return redirect()->route('cities'); // You can redirect to an appropriate location
            }

            $request->validate([
                'title' => 'required|unique:blogs',
                'message'=>'required',
                'category_id'=>'required'
            ]);
            $admin_id=Auth::guard('admin')->user()->name;
            // dd($admin_id);
            // dd($request->all());
            $blog = new Blogs();

            if ($request->hasFile('image')) {
                $fileNameWithExt = $request->file('image')->getClientOriginalName();
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('image')->getClientOriginalExtension();
                $fileNameToStore = $fileName . '_' . time() . '.' . $extension;

                // Store the file in the public disk
                $path = $request->file('image')->storeAs('blog', $fileNameToStore, 'public');

                // Save the full URL to the image
                $blog->image = asset('storage/' . $path);
            }

            $blog->title       = $request->input('title');
            $blog->category_id = $request->input('category_id');
            $blog->description = $request->input('message');
            $blog->status      = 1;
            $blog->added_by    = $admin_id;
            $blog->save();
 $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Add Blog', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

            Alert::toast('Blog has been saved successfully!', 'success');
            return redirect()->route('blogs');
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
            if (!$user || !$user->hasPermissionByRole('edit blog')) {
                return view('admin.errors.unauthorized');
            }
            $blog_category=BlogCategory::where('status',1)->get();
            $blogs = Blogs::find($id);
            $appsettings = AppSetting::all()->toArray();

            return view('admin.blog.blogs.edit', compact('blogs', 'appsettings','blog_category'));
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
            if (!$user || !$user->hasPermissionByRole('edit blog')) {
                return view('admin.errors.unauthorized');
            }
            if (!$request->isMethod('put')) {
                // Handle the error - Method not allowed
                Alert::toast('Method not allowed', 'error');
                return redirect()->route('cities'); // You can redirect to an appropriate location
            }
            $request->validate([
                'title' => 'required',
                'message'=>'required',
                'category_id'=>'required'
            ]);

            $blog = Blogs::find($request->input('id'));

            if ($request->hasFile('image')) {
                // Check if a previous image exists and delete it
                if ($blog->image) {
                    // Extract the relative path from the full URL
                    $imagePath = str_replace(asset('storage') . '/', '', $blog->image);

                    // Delete the image using the relative path
                    Storage::disk('public')->delete($imagePath);
                }


                // Get file name with extension
                $fileNameWithExt = $request->file('image')->getClientOriginalName();
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('image')->getClientOriginalExtension();
                $fileNameToStore = $fileName . '_' . time() . '.' . $extension;

                // Store image and get the path
                $path = $request->file('image')->storeAs('blog', $fileNameToStore, 'public');

                // Save the full URL to the image in the database
                $blog->image = asset('storage/' . $path);
            }

            $blog->title = $request->input('title');
            $blog->category_id = $request->input('category_id');
            $blog->description = $request->input('message');
            $blog->update();

 $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log('Update Blog', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

            Alert::toast('Blog has been updated successfully!', 'success');
            return redirect()->route('blogs');
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

        // Check if the user has permission to delete blog
        if (!$user || !$user->hasPermissionByRole('delete blog')) {
            return view('admin.errors.unauthorized');
        }

        $blog = Blogs::find($id);

        // Check if the blog has an image and delete it
        if ($blog->image) {
            // Extract the relative path from the full URL
            $imagePath = str_replace(asset('storage') . '/', '', $blog->image);

            // Delete the image using the relative path
            Storage::disk('public')->delete($imagePath);
        }

        // Delete the blog
        $blog->delete();
 $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log(  'Delete Blog', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

        Alert::toast('Blog has been deleted successfully!', 'error');
        return redirect()->route('blogs');
    } catch (\Exception $e) {
        // Handle exceptions or errors
        Alert::toast('Something went wrong!!', 'error');
        return redirect()->back();
    }
}


    public function active($id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit blog')) {
                return view('admin.errors.unauthorized');
            }
            $blog = Blogs::find($id);
            $blog->status = 1;
            $blog->save();

            Alert::toast('Blog has been activated!', 'success');
            return redirect()->route('blogs');
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
            if (!$user || !$user->hasPermissionByRole('edit blog')) {
                return view('admin.errors.unauthorized');
            }
            $blog = Blogs::find($id);
            $blog->status = 0;
            $blog->save();

            Alert::toast('Blog has been inactivated successfully!', 'error');
            return redirect()->route('blogs');
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }
}