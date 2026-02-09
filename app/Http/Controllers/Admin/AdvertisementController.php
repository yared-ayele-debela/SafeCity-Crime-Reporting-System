<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use App\Models\Advertisement;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Services\ActivityLogger;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AdvertisementController extends Controller
{
    //
    public function index()
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('view_advertisment')) {
                return view('admin.errors.unauthorized');
            }
            $adver = Advertisement::all();
            $appsettings = AppSetting::all()->toArray();
            return view('admin.advertisement.alladvertisement', compact('adver', 'appsettings'));
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }
    public function create()
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('create_advertisment')) {
                return view('admin.errors.unauthorized');
            }

            $appsettings = AppSetting::all()->toArray();

            return view('admin.advertisement.add_advertisement', compact('appsettings'));
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        // try {
            $all_ad=Advertisement::where('type',$request->type)->count();
            if($all_ad >=3){
                Alert::toast('Maximum amount you can add is 3 advertisement banners','error');
                return redirect()->back();
            }
            $check=Advertisement::where(column: 'position',operator: $request->position)->first();
            if($check){
                 Alert::toast('You already link this position with another advertisement, please select another position','error');
                return redirect()->back();
            }
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('create_advertisment')) {
                return view('admin.errors.unauthorized');
            }

            $request->validate([
                'title' => 'required|string|max:255',
                'position' =>'required',
                'description' => 'required|string',
                'image' => 'required|image',
                'adver_links' => 'required|string',
                'type' => 'required|in:restaurant,hotel,ecommerce',
            ]);

            $adver = new Advertisement();
            $adver->title = $request->input('title');
            $adver->position = $request->input('position');
            $adver->type = $request->type;
            $adver->description = $request->input('description');
            $adver->adv_links = $request->input('adver_links');
            $adver->is_approved = 1;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileNameWithExt = $file->getClientOriginalName();
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $fileNameToStore = $fileName . '_' . time() . '.' . $extension;

                $file->storeAs('public/adver', $fileNameToStore);
                $adver->image = asset('storage/adver/' . $fileNameToStore); // Store full URL
            }

            $adver->save();


             $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Add Advertisement', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

            $appsettings = AppSetting::all()->toArray();

            Alert::toast('Advertisement has been added successfully!', 'success');
            return redirect('admin/adverstisements');
        // } catch (\Illuminate\Validation\ValidationException $e) {
        //     // Laravel's built-in validation exception
        //     return redirect()->back()->withErrors($e->validator->errors())->withInput();
        // } catch (\Exception $e) {
        //     // Handle other exceptions
        //     return redirect()->back()->withErrors([$e->getMessage()])->withInput();
        // }
    }


    public function edit($id)
    {
        try {
            $user = Auth::guard('admin')->user();

            if (!$user || !$user->hasPermissionByRole('edit_advertisment')) {
                return view('admin.errors.unauthorized');
            }

            $adver = Advertisement::find($id);

            if (!$adver) {
                throw new \Exception('Advertisement not found.');
            }

            $appsettings = AppSetting::all()->toArray();

            return view('admin.advertisement.edit_advertisement', compact('adver', 'appsettings'));
        } catch (\Exception $e) {
            // Handle exceptions
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'adver_links' => 'required|string',
            ]);
            $user = Auth::guard('admin')->user();
             $check=Advertisement::where(column: 'position',operator: $request->position)->first();
            if($check){
                 Alert::toast('You already link this position with another advertisement, please select another position','error');
                return redirect()->back();
            }
            if (!$user || !$user->hasPermissionByRole('edit_advertisment')) {
                return view('admin.errors.unauthorized');
            }
            $adver = Advertisement::find($request->input('adver_id'));
            $adver->title = $request->input('title');
            $adver->position = $request->input('position')?? $adver->position;
            $adver->type = $request->type??$adver->type;
            $adver->description = $request->input('description');
            $adver->adv_links = $request->input('adver_links');

            if ($request->hasFile('image')) {
                $file = $request->file('image');

                // Check if the file is valid
                if (!$file->isValid()) {
                    throw new \Exception('Invalid file upload.');
                }

                // If there's an existing image, delete it
                if ($adver->image) {
                    // Extract the file name from the URL
                    $oldImagePath = str_replace(asset('storage'), 'public', $adver->image);

                    // Delete the old image from storage
                    Storage::delete($oldImagePath);
                }

                // Generate a new file name
                $fileNameWithExt = $file->getClientOriginalName();
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $fileNameToStore = $fileName . '_' . time() . '.' . $extension;

                // Store the new file
                $file->storeAs('public/adver', $fileNameToStore);

                // Save the full URL of the new image
                $adver->image = asset('storage/adver/' . $fileNameToStore);
            }


            $adver->update();

             $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log('Update Advertisement', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

            $appsettings = AppSetting::all()->toArray();

            Alert::toast('Advertisement has been updated successfully!', 'success');
            return redirect('admin/adverstisements');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel's built-in validation exception
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        } catch (\Exception $e) {
            // Handle other exceptions
            return redirect()->back()->withErrors([$e->getMessage()])->withInput();
        }
    }
    public function active($id)
    {
        try {
            $user = Auth::guard('admin')->user();

            if (!$user || !$user->hasPermissionByRole('edit_advertisment')) {
                return view('admin.errors.unauthorized');
            }

            $adver = Advertisement::find($id);
            $adver->is_approved = 1;
            $adver->update();

            Alert::toast('advertisement status has been actived!', 'success');
            return redirect('admin/adverstisements');
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }


    public function inactive($id)
    {
        try {
            $user = Auth::guard('admin')->user();

            if (!$user || !$user->hasPermissionByRole('edit_advertisment')) {
                return view('admin.errors.unauthorized');
            }
            $adver = Advertisement::find($id);
            $adver->is_approved = 0;
            $adver->update();

            Alert::toast('advertisement status has been inactive!', 'error');
            return redirect('admin/adverstisements');
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('delete_advertisment')) {
                return view('admin.errors.unauthorized');
            }

            $adver = Advertisement::find($id);
            if ($adver->image) {
                Storage::delete('public/category/' . $adver->image);
            }
            $adver->delete();

             $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log(  'Delete Advertisement', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");


            Alert::toast('advertisement has been deleted successfully!', 'error');
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }
}
