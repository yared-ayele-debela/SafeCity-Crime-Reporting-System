<?php

namespace App\Http\Controllers\SafeCity;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewslettersController extends Controller
{
    //
     public function store(Request $request)
{
    $request->validate([
        'email' => 'required|email|unique:newsletter_subscribers,email',
    ]);

    NewsletterSubscriber::create([
        'email' => $request->email,
        'status' => 1,
    ]);

    return redirect()->back()->with('success','Subscribed successfully!');
}
}
