<?php

namespace App\Http\Controllers\SafeCity;

use App\Http\Controllers\Controller;
use App\Models\SafeCity\OfferDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OfferDepartmentController extends Controller
{

     public function __construct()
    {
        $this->middleware('admin.permission:view_officer')->only('index');
        $this->middleware('admin.permission:add_officer')->only('store');
        $this->middleware('admin.permission:edit_officer')->only(methods: 'update');
        $this->middleware('admin.permission:delete_officer')->only('destroy');
    }

    public function index()
    {
        $offers = OfferDepartment::latest()->get();
        return view('admin.offers.index', compact('offers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'division' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('offers', 'public');
        }

        OfferDepartment::create($validated);

        return redirect()->back()->with('success', 'Officer added successfully.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'division' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png'
        ]);

            $officer=OfferDepartment::find($id);


        if ($request->hasFile('image')) {
            // dd($validated);
            if ($officer->image) {
                Storage::disk('public')->delete($officer->image);
            }
            $validated['image'] = $request->file('image')->store('offers', 'public');
        }

        $officer->update($validated);

        return redirect()->back()->with('success', 'Officer updated successfully.');
    }
   public function destroy($id)
{
    $officer=OfferDepartment::find($id);
    if ($officer->image) {
        Storage::disk('public')->delete($officer->image);
    }

    $officer->delete();

    return redirect()->back()->with('success', 'Officer deleted successfully.');
}
}
