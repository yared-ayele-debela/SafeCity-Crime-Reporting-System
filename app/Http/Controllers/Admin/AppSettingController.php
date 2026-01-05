<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Services\ActivityLogger;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException as ValidationValidationException;
use Intervention\Image\ImageManagerStatic as Image;
use RealRashid\SweetAlert\Facades\Alert;

class AppSettingController extends Controller
{
    //
    public function create()
    {
        try {
            $user = Auth::guard('admin')->user();

            if (!$user || !$user->hasPermissionByRole('edit_appsetting')) {
                return view('admin.errors.unauthorized');
            }

            $appsettings = AppSetting::where('id', 1)->get();
            $appsetting = AppSetting::where('id', 1)->first();

            return view('admin.app_setting.app_settings', compact('appsetting', 'appsettings'));
        } catch (\Exception $e) {
            // Handle exceptions
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        try {
            $this->validate($request, [
                'title' => 'required|string',
                'address' => 'required',
                'email' => 'email',
                'phone_no' => 'required',
                'footer_text' => 'required',
            ]);

            $appsettings = AppSetting::find(1);

            if (!$appsettings) {
                throw new \Exception('Application settings not found.');
            }

            $appsettings->application_title = $request->input('title');
            $appsettings->address = $request->input('address');
            $appsettings->email_address = $request->input('email');

            $appsettings->panel_footer_text = $request->input('plane_footer_text');

            $fields = [
                'favicon'       => ['width' => 32,  'height' => 32],
                'footer_image'  => ['width' => 500, 'height' => 86],
                'panel_icon'    => ['width' => 500, 'height' => 86],
                'logo'          => ['width' => 500, 'height' => 86],
            ];

            foreach ($fields as $field => $resize) {
                if ($request->hasFile($field)) {
                    // Delete old image if it exists and not 'noimage.jpg'
                    if (!empty($appsettings->$field) && $appsettings->$field !== 'noimage.jpg') {
                        $oldFileName = basename($appsettings->$field); // Handle if stored as full URL
                        Storage::delete('public/appsettings/' . $oldFileName);
                    }

                    // Store new file
                    $file = $request->file($field);
                    $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
                    $file->storeAs('public/appsettings', $fileNameToStore);

                    // Optional resizing (uncomment if using Intervention Image)
                    // $image = \Image::make(public_path("storage/appsettings/{$fileNameToStore}"))
                    //     ->resize($resize['width'], $resize['height'])
                    //     ->save();

                    // Save full URL
                    $appsettings->$field = asset('storage/appsettings/' . $fileNameToStore);
                }
            }


            $appsettings->footer_text = $request->input('footer_text');
            $appsettings->update();

              $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->toDateTimeString(); // 'Y-m-d H:i:s'
        ActivityLogger::log( 'Update Website Settings', Auth::guard('admin')->user()->name . " at {$formattedDateTime}");

            Alert::toast('Website Settings has been updated!', 'success');
            return redirect()->back();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel's built-in validation exception
            return redirect()->back()->withErrors($e->validator->errors())->withInput();

        } catch (\Exception $e) {
            // Handle exceptions
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }
}
