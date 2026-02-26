<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OutOfStockProductController extends Controller
{
    //
    public function index()
    {
          $vendor_id = Auth::guard('admin')->user()->vendor_id;

          if(Auth::guard('admin')->user()->type==="Super Admin")
          {
            // dd("hello");
          $outOfStockProducts = Product::with('category','brand','group')->where('quantity','<','1')->paginate(10);

          }else{
                $outOfStockProducts = Product::with('category','brand','group')->where('vendor_id', $vendor_id)->where('quantity','<','1')->paginate(10);
      
          }

        return view('admin.product.index', compact('outOfStockProducts'));
    }
}