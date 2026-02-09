<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use App\Models\CmsPage;
use App\Services\ActivityLogger;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class CmsController extends Controller
{
    //
    public function cmspages()
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('view_cmspage')) {
                return view('admin.errors.unauthorized');
            }

            $cms_pages = CmsPage::get()->toArray();
            $appsettings = AppSetting::all()->toArray();

            return view('admin.pages.cms_pages')->with(compact('cms_pages', 'appsettings'));
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
            if (!$user || !$user->hasPermissionByRole('create_cmspage')) {
                return view('admin.errors.unauthorized');
            }

            $appsettings = AppSetting::all()->toArray();
            return view('admin.pages.addcmspage', compact('appsettings'));
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
                return redirect()->route('cities'); // You can redirect to an appropriate location
            }
            $this->validate($request, [
                'title' => 'required|string',
                'description' => 'required|string',
                'url' => 'required|string|unique:cms_pages'
            ]);

            $cms = new CmsPage();
            $cms->title = $request->input('title');
            $cms->description = $request->input('description');
            $cms->url = $request->input('url');
            $cms->meta_title = $request->input('meta_title');
            $cms->meta_description = $request->input('meta_description');
            $cms->meta_keywords = $request->input('meta_keywords');
            $cms->status = 1;

            $cms->save();

             $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Add Pages', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");


            Alert::toast('Cms page has been saved', 'success');
            return redirect('admin/cms-pages');
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
            if (!$user || !$user->hasPermissionByRole('edit_cmspage')) {
                return view('admin.errors.unauthorized');
            }

            $cms = CmsPage::find($id);
            $appsettings = AppSetting::all()->toArray();

            return view('admin.pages.editcmspage', compact('cms', 'appsettings'));
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
                return redirect()->route('cities'); // You can redirect to an appropriate location
            }
            $this->validate($request, [
                'title' => 'required|string',
                'description' => 'required|string',
                'url' => 'required|string'
            ]);

            $cms = CmsPage::find($request->input('cms_id'));
            $cms->title = $request->input('title');
            $cms->description = $request->input('description');
            $cms->url = $request->input('url');
            $cms->meta_title = $request->input('meta_title');
            $cms->meta_description = $request->input('meta_description');
            $cms->meta_keywords = $request->input('meta_keywords');
            $cms->update();


             $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log('Update Pages', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");


            Alert::toast('Cms page has been updated', 'success');
            return redirect('admin/cms-pages');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel's built-in validation exception
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function active($cms_id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit_cmspage')) {
                return view('admin.errors.unauthorized');
            }
            $cms = CmsPage::find($cms_id);
            $cms->status = 1;
            $cms->update();

            Alert::toast('Cms page has been activated', 'success');
            return redirect('admin/cms-pages');
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function inactive($cms_id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit_cmspage')) {
                return view('admin.errors.unauthorized');
            }
            $cms = CmsPage::find($cms_id);
            $cms->status = 0;
            $cms->update();

            Alert::toast('Cms page has been inactivated', 'error');
            return redirect('admin/cms-pages');
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function delete($cms_id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('delete_cmspage')) {
                return view('admin.errors.unauthorized');
            }

            $cms = CmsPage::find($cms_id);
            $cms->delete();


             $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Delete Pages', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

            Alert::toast('Cms page has been deleted', 'error');
            return redirect('admin/cms-pages');
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }
}
