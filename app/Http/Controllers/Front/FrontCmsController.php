<?php

namespace App\Http\Controllers\Front;

use App\Models\CmsPage;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class FrontCmsController extends Controller
{
    //
    public function pages($url){

        $cmsPageDetails = CmsPage::where('url', $url)->firstOrFail();
        $cms_pages = CmsPage::all()->toArray();
        $appsettings=AppSetting::all()->toArray();

        return view('NewFrontEndPage.pages.cms_page', compact('cms_pages', 'cmsPageDetails', 'appsettings'));

    }
    public function cmsPage()
    {
        // try {
            $cms_pages = CmsPage::get()->toArray();

            $currentRoute = url()->current();
            $currentRoute = str_replace("http://127.0.0.1:8000/", "", $currentRoute);
            $cmsRoutes = CmsPage::where('status', 1)->get()->pluck('url')->toArray();

            // Trim leading/trailing slashes for accurate comparison
            $currentRoute = trim($currentRoute, '/');
            $cmsRoutes = array_map('trim', $cmsRoutes);

            if (in_array($currentRoute, $cmsRoutes)) {
                $cmsPageDetails = CmsPage::where('url', $currentRoute)->first()->toArray();
                return view('NewFrontEndPage.pages.cms_page', compact('cms_pages', 'cmsPageDetails', 'appsettings'));
            } else {
                abort(404);
            }
        // } catch (\Exception $e) {
        //     // Log or handle the exception as needed
        //     Alert::toast('something is wrong!!', 'error');
        //     return redirect()->back();
        // }
    }
    public function contact(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $data = $request->all();
                $email_template = EmailTemplate::first();

                $email = $email_template->email;
                $messageData = [
                    'email_template' => $email_template,
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'comment' => $data['message']
                ];
                Mail::send('emails.enquiry', $messageData, function ($message) use ($email) {
                    $message->to($email)->subject('Enquiry');
                });

                return redirect()->back()->with('success','Thanks for your enquiry.We will get back to you soon.');
            }
            $cms_pages = CmsPage::get()->toArray();
            $appsettings = AppSetting::all()->toArray();
            return view('NewFrontEndPage.pages.contact', compact('cms_pages', 'appsettings'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('success', 'something is wrong');
        }
    }
}
