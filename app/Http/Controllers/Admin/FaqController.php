<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\CmsPage;
use App\Models\Faq;
use App\Services\ActivityLogger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class FaqController extends Controller
{
    //
    public function index()
    {
        try {
            $cms_pages = CmsPage::get()->toArray();
            $appsettings = AppSetting::all()->toArray();
            $allfaq = Faq::all()->where('status', 1)->toArray();

            return view('fontend.layout.faq', compact('cms_pages', 'allfaq', 'appsettings'));
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }
    public function allfaq()
    {
        try {
            $cms_pages = CmsPage::get()->toArray();
            $appsettings = AppSetting::all()->toArray();
            $allfaq = Faq::all()->toArray();
            return view('admin.faq.allfaq', compact('allfaq', 'cms_pages', 'appsettings'));
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function create()
    {
        try {
            $cms_pages = CmsPage::get()->toArray();
            $appsettings = AppSetting::all()->toArray();
            return view('admin.faq.addfaq', compact('cms_pages', 'appsettings'));
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }
    public function store(Request $request)
    {
        try {
            if (!$request->method('post')) {
                Alert::toast('something is wrong!!', 'error');
                return redirect()->back();
            }
            $request->validate([
                'question' => 'required|string|max:255',
                'answer' => 'required|string',
            ]);
            $faq = new Faq();
            $faq->question = $request->input('question');
            $faq->answer = $request->input('answer');
            $faq->status = 1;
            $faq->save();

                $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Add FAQ', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

            Alert::toast('FAQ is has been created!', 'success');

            return redirect('admin/allfaq');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel's built-in validation exception
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try {
            $faq = Faq::find($id);
            $cms_pages = CmsPage::get()->toArray();
            $appsettings = AppSetting::all()->toArray();
            return view('admin.faq.editfaq', compact('faq', 'cms_pages', 'appsettings'));
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        try {
            if (!$request->method('put')) {
                Alert::toast('something is wrong!!', 'error');
                return redirect()->back();
            }
            $request->validate([
                'question' => 'required|string|max:255',
                'answer' => 'required|string',
            ]);

            $faq = Faq::find($request->input('faq_id'));
            $faq->question = $request->input('question');
            $faq->answer = $request->input('answer');
            $faq->update();

                $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Update FAQ', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

            Alert::toast('FAQ is has been updated!', 'success');
            return redirect('admin/allfaq');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel's built-in validation exception
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function active($id)
    {
        try {
            $faq = Faq::find($id);
            $faq->status = 1;
            $faq->update();

            Alert::toast('FAQ status is active!', 'success');
            return redirect('admin/allfaq');
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }


    public function inactive($id)
    {
        try {
            $faq = Faq::find($id);
            $faq->status = 0;
            $faq->update();

            Alert::toast('FAQ status inactive!', 'error');
            return redirect('admin/allfaq');
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        try {
            $faq = Faq::find($id);
            $faq->delete();

                $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Delete FAQ', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

            Alert::toast('FAQ has been deleted!', 'error');
            return redirect('admin/allfaq');
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }
}