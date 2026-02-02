<?php

namespace App\Http\Controllers\SafeCity;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\SafeCity\Contact;
use App\Models\SafeCity\OfferDepartment;
use App\Models\SafeCity\Report;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    //
    public function index()
    {
        $officers=OfferDepartment::orderBy("created_at","desc")->get();
            $reports = Report::where('status','resolved')->select('id', 'title', 'description', 'latitude', 'longitude')->get();
            // $reports = Report::select('id', 'title', 'description', 'latitude', 'longitude')->get();

        // This method can be used to return the main page of the SafeCity frontend
        return view(view: 'frontend.pages.index',data: compact('officers','reports'));
    }
    public function about()
    {
        // This method can be used to return the about page of the SafeCity frontend
        return view('frontend.pages.about');
    }
    public function contact()
    {
        // This method can be used to return the contact page of the SafeCity frontend
        return view(view: 'frontend.pages.contact');
    }

     public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        Contact::create($request->only('name', 'email', 'subject', 'message'));

        return back()->with('success', 'Your message has been sent successfully!');
    }
    public function faq()


    {
        $allfaq=Faq::where('status',1)->latest()->get();
        // This method can be used to return the FAQ page of the SafeCity frontend
        return view('frontend.pages.faq',compact('allfaq'));
    }

}
