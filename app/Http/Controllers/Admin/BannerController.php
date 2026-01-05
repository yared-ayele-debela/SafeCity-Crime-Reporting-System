<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\Banner;
use App\Services\ActivityLogger;
use Intervention\Image\ImageManager as Image;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

use function PHPUnit\Framework\returnSelf;

class BannerController extends Controller
{
    //
    public function banners(Request $request)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('view_banners')) {
                return view('admin.errors.unauthorized');
            }

            $banners = Banner::get()->toArray();

            if ($request->isMethod('post')) {
                $this->validate($request, [
                    'type' => 'required',
                    'title' => 'required|string',
                    'image' => 'required|image',
                ]);

                // Validation passed, proceed with creating the banner

                $banner = new Banner();

                // Image handling based on type (Slider or Fix)
                if ($request->input('type') == "Slider") {
                    if ($request->hasFile('image')) {
                        // Delete old image if exists
                        if ($banner->image) {
                            Storage::delete('public/banner/' . $banner->image);
                        }

                        // Get file name and extension
                        $file = $request->file('image');
                        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $extension = $file->getClientOriginalExtension();
                        $fileNameToStore = $fileName . '_' . time() . '.' . $extension;

                        // Upload image
                        $path = $file->storeAs('public/banner', $fileNameToStore);

                        // Optionally resize based on type
                        // $image = Image::make(public_path('storage/banner/' . $fileNameToStore));
                        // if ($request->input('type') == 'Slider') {
                        //     $image->resize(1200, 400)->save();
                        // } elseif ($request->input('type') == 'Fix') {
                        //     $image->resize(1110, 192)->save();
                        // }

                        // Save full URL to DB
                        $banner->image = asset('storage/banner/' . $fileNameToStore);
                    }
                }
                if ($request->input('type') == "Fix") {
                    if ($request->hasFile('image')) {
                        // Delete old image if exists
                        if ($banner->image) {
                            Storage::delete('public/banner/' . $banner->image);
                        }

                        // Get file name and extension
                        $file = $request->file('image');
                        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $extension = $file->getClientOriginalExtension();
                        $fileNameToStore = $fileName . '_' . time() . '.' . $extension;

                        // Upload image
                        $path = $file->storeAs('public/banner', $fileNameToStore);

                        // Optionally resize based on type
                        // $image = Image::make(public_path('storage/banner/' . $fileNameToStore));
                        // if ($request->input('type') == 'Slider') {
                        //     $image->resize(1200, 400)->save();
                        // } elseif ($request->input('type') == 'Fix') {
                        //     $image->resize(1110, 192)->save();
                        // }

                        // Save full URL to DB
                        $banner->image = asset('storage/banner/' . $fileNameToStore);
                    }
                }

                // Assign other banner properties
                $banner->link = $request->input('link');
                $banner->type = $request->input('type');
                $banner->title = $request->input('title');
                $banner->alt = $request->input('alt');
                $banner->status = 0;

                $banner->save();

                   $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Add Ecommerce Banner', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

                Alert::toast('Banner has been saved', 'success');
                return redirect('admin/banners');
            }

            $appsettings = AppSetting::all()->toArray();
            return view('admin.banner.allbanner', compact('banners', 'appsettings'));

            // ... (the rest of your code)
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel's built-in validation exception
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        } catch (\Exception $e) {
            // Handle other exceptions or errors
            Alert::toast('Something went wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function create()
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('create_banners')) {
                return view('admin.errors.unauthorized');
            }
            $appsettings = AppSetting::all()->toArray();

            return view('admin.banner.addbanner', compact('appsettings'));
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    // public function store(Request $request){


    // }

    public function edit($id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit_banners')) {
                return view('admin.errors.unauthorized');
            }

            $appsettings = AppSetting::all()->toArray();

            $banner = Banner::find($id);

            if (!$banner) {
                // Handle the case where the banner is not found
                Alert::toast('Banner not found', 'error');
                return redirect('admin/banners');
            }

            return view('admin.banner.editbanner', compact('banner', 'appsettings'));
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('Error editing banner: ' . $e->getMessage(), 'error');
            return redirect('admin/banners');
        }
    }

    public function update(Request $request)
    {
        // try {
        if (!$request->method('put')) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
        $this->validate($request, [
            'link' => 'required|string',
            'title' => 'required|string',
            'alt' => 'required|string'
        ]);

        $banner = Banner::find($request->input('id'));

        if (!$banner) {
            // Handle the case where the banner is not found
            Alert::toast('Banner not found', 'error');
            return redirect('admin/banners');
        }

        $banner->link = $request->input('link');
        $banner->type = $request->input('type');
        $banner->title = $request->input('title');
        $banner->alt = $request->input('alt');

        if ($request->input('type') == "Slider") {
            if ($request->hasFile('image')) {
                if ($banner->image) {
                    // Extract file name if stored as full URL
                    $oldFileName = basename($banner->image);
                    Storage::delete('public/banner/' . $oldFileName);
                }
                $file = $request->file('image');
                $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $fileNameToStore = $fileName . '_' . time() . '.' . $extension;

                // Store file
                $file->storeAs('public/banner', $fileNameToStore);

                // Optional image resizing using Intervention Image
                // $image = \Image::make(public_path('storage/banner/' . $fileNameToStore));
                // if ($request->input('type') === 'Slider') {
                //     $image->resize(1200, 700)->save();
                // } elseif ($request->input('type') === 'Fix') {
                //     $image->resize(1110, 192)->save();
                // }

                // Store full URL in DB
                $banner->image = asset('storage/banner/' . $fileNameToStore);
            }
        }
        if ($request->input('type') == "Fix") {
            if ($request->hasFile('image')) {
                if ($banner->image) {
                    // Extract file name if stored as full URL
                    $oldFileName = basename($banner->image);
                    Storage::delete('public/banner/' . $oldFileName);
                }
                $file = $request->file('image');
                $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $fileNameToStore = $fileName . '_' . time() . '.' . $extension;

                // Store file
                $file->storeAs('public/banner', $fileNameToStore);

                // Optional image resizing using Intervention Image
                // $image = \Image::make(public_path('storage/banner/' . $fileNameToStore));
                // if ($request->input('type') === 'Slider') {
                //     $image->resize(1200, 700)->save();
                // } elseif ($request->input('type') === 'Fix') {
                //     $image->resize(1110, 192)->save();
                // }

                // Store full URL in DB
                $banner->image = asset('storage/banner/' . $fileNameToStore);
            }
        }


        $banner->update();

                 $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Update Ecommerce Banner', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

        Alert::toast('Banner has been updated', 'success');
        return redirect('admin/banners');
        // } catch (\Illuminate\Validation\ValidationException $e) {
        //     // Laravel's built-in validation exception
        //     return redirect()->back()->withErrors($e->validator->errors())->withInput();
        // } catch (\Exception $e) {
        //     // Handle exceptions or errors
        //     Alert::toast('something is wrong!!', 'error');
        //     return redirect()->back();
        // }
    }

    // for active and inactive admin type

    public function active_banner($banner_id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit_banners')) {
                return view('admin.errors.unauthorized');
            }

            $bannercount = Banner::where('status', 1)->count();
            if ($bannercount >= 6) {
                Alert::toast('You have already actived 5 banners. Please inactive any one to active more banner', 'info');
                return redirect()->back();
            } else {
                $banner = Banner::find($banner_id);
                $banner->status = 1;
                $banner->update();
            }

            Alert::toast('Banner Status Acitve', 'success');
            return redirect('admin/banners');
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }


    public function inactive_banner($banner_id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit_banners')) {
                return view('admin.errors.unauthorized');
            }

            $banner = Banner::find($banner_id);
            $banner->status = 0;
            $banner->update();

            Alert::toast('Banner Status Inactive', 'error');
            return redirect('admin/banners');
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function delete($banner_id)
    {
        try {

            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('delete_banners')) {
                return view('admin.errors.unauthorized');
            }

            $banner = Banner::find($banner_id);

            if (!$banner) {
                // Handle the case where the banner is not found
                Alert::toast('Banner not found', 'error');
                return redirect('admin/banners');
            }

            if ($banner->image) {
                // Extract file name if stored as full URL
                $oldFileName = basename($banner->image);
                Storage::delete('public/banner/' . $oldFileName);
            }

            $banner->delete();

                     $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Delete Ecommerce Banner', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");


            Alert::toast('Banner has been deleted', 'success');
            return redirect('admin/banners');
        } catch (\Exception $e) {
            // Handle exceptions or errors
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }
}
