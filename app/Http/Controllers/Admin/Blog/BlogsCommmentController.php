<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\BlogComment;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class BlogsCommmentController extends Controller
{
     public function index(){
        $user = Auth::guard('admin')->user();
        if (!$user || !$user->hasPermissionByRole('view blog comment')) {
            return view('admin.errors.unauthorized');
        }
        $all_blog_comments=BlogComment::paginate(10);
        $appsettings = AppSetting::all()->toArray();

        return view('admin.blog.comment.index',compact('all_blog_comments','appsettings'));
     }
     public function delete($id)
     {
         try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('delete blog comment')) {
                return view('admin.errors.unauthorized');
            }
             $blog = BlogComment::find($id);
             $blog->delete();

              $currentDateTime = Carbon::now();
                $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
                ActivityLogger::log( 'Delete Blog Comment', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");


             Alert::toast('Blog commment has been deleted successfully!', 'error');
             return redirect()->route('blog-comments');
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
            if (!$user || !$user->hasPermissionByRole('edit blog comment')) {
                return view('admin.errors.unauthorized');
            }
             $blog = BlogComment::find($id);
             $blog->status = 1;
             $blog->save();

             Alert::toast('Blog comment has been activated!', 'success');
             return redirect()->route('blog-comments');
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
            if (!$user || !$user->hasPermissionByRole('edit blog comment')) {
                return view('admin.errors.unauthorized');
            }
             $blog = BlogComment::find($id);
             $blog->status = 0;
             $blog->save();

             Alert::toast('Blog comment has been inactivated successfully!', 'error');
             return redirect()->route('blog-comments');
         } catch (\Exception $e) {
             // Handle exceptions or errors
             Alert::toast('something is wrong!!', 'error');
             return redirect()->back();
         }
     }
}