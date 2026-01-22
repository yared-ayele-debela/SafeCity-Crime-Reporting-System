<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\City;
use App\Models\Country;
use App\Models\Delivery_Man_Type;
use App\Models\Delivery_Zone;
use App\Models\DeliveryMan;
use App\Models\DeliveryManTip;
use App\Models\DeliveryManWithdrawRequest;
use App\Models\State;
use App\Models\Vehicle_Type;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class DeliveryManController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('view_delivery_boy')) {
                return view('admin.errors.unauthorized');
            }

            $appsettings = AppSetting::all()->toArray();
            $deliverymen = Deliveryman::all();

            return view('admin.deliverymen.index', compact('deliverymen', 'appsettings'));
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function create()
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('create_delivery_boy')) {
                return view('admin.errors.unauthorized');
            }

            $delivery_man_type = Delivery_Man_Type::where('status', 1)->get();
            $delivery_zone = Delivery_Zone::where('status', 1)->get();
            $vehicle_type = Vehicle_Type::where('status', 1)->get();
            $city = City::where('status', 1)->get();
            $state = State::where('status', 1)->get();
            $country = Country::where('status', 1)->get();

            $appsettings = AppSetting::all()->toArray();

            return view('admin.deliverymen.create', compact('appsettings', 'city', 'state', 'country', 'vehicle_type', 'delivery_zone', 'delivery_man_type'));
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('create_delivery_boy')) {
                return view('admin.errors.unauthorized');
            }

            if (!$request->isMethod('post')) {
                // Handle the error - Method not allowed
                Alert::toast('Method not allowed', 'error');
                return redirect()->back();
            }
            //    dd($request->all());
            $validatedData = $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'delivery_man_image' => 'required|image',
                'address' => 'required',
                'country' => 'required',
                'state' => 'required',
                'city' => 'required',
                'identity_type' => 'required',
                'identity_number' => 'required',
                'identity_image' => 'required|image',
                'phone' => 'required',
                'salary' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:6',
                'confirm_password' => 'required|same:password',
                'delivery_man_type' => 'required',
                'delivery_zone' => 'required',
                'vehicle_type' => 'required',
                'vehicle_capacity' => 'required',
                'driving_license_number' => 'required',
            ]);

            // Create a new Deliveryman instance
            $deliveryBoy = new DeliveryMan();
            $deliveryBoy->first_name = $validatedData['first_name'];
            $deliveryBoy->last_name = $validatedData['last_name'];
            $deliveryBoy->address = $validatedData['address'];
            $deliveryBoy->country = $validatedData['country'];
            $deliveryBoy->state = $validatedData['state'];
            $deliveryBoy->city = $validatedData['city'];
            $deliveryBoy->identity_type = $validatedData['identity_type'];
            $deliveryBoy->identity_number = $validatedData['identity_number'];
            $deliveryBoy->phone = $validatedData['phone'];
            $deliveryBoy->salary = $validatedData['salary'];
            $deliveryBoy->email = $validatedData['email'];
            $deliveryBoy->password = bcrypt($validatedData['password']);
            $deliveryBoy->delivery_man_type = $validatedData['delivery_man_type'];
            $deliveryBoy->delivery_zone = $validatedData['delivery_zone'];
            $deliveryBoy->vehicle_type = $validatedData['vehicle_type'];
            $deliveryBoy->vehicle_capacity = $validatedData['vehicle_capacity'];
            $deliveryBoy->driving_license_number = $validatedData['driving_license_number'];


            if ($request->hasFile('delivery_man_image')) {
                $file = $request->file('delivery_man_image');
                $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
                $path = $file->storeAs('public/delivery_man', $fileNameToStore);
                $deliveryBoy->delivery_man_image = asset('storage/delivery_man/' . $fileNameToStore);
            }

            if ($request->hasFile('identity_image')) {
                $file = $request->file('identity_image');
                $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
                $path = $file->storeAs('public/delivery_man', $fileNameToStore);
                $deliveryBoy->identity_image = asset('storage/delivery_man/' . $fileNameToStore);
            }

            if ($request->hasFile('driving_license_image')) {
                $file = $request->file('driving_license_image');
                $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
                $path = $file->storeAs('public/delivery_man', $fileNameToStore);
                $deliveryBoy->driving_license_image = asset('storage/delivery_man/' . $fileNameToStore);
            }


            // Save the deliveryman to the database
            $deliveryBoy->save();

            Alert::toast('DeliveryMan has been saved!', 'success');

            return redirect()->route('delivery_boy.index')->with('success', 'Deliveryman created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel's built-in validation exception
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit_delivery_boy')) {
                return view('admin.errors.unauthorized');
            }

            $delivery_man_type = Delivery_Man_Type::all()->where('status', 1);
            $delivery_zone = Delivery_Zone::all()->where('status', 1);
            $vehicle_type = Vehicle_Type::all()->where('status', 1);
            $city = City::all()->where('status', 1);
            $state = State::all()->where('status', 1);
            $country = Country::all()->where('status', 1);
            $appsettings = AppSetting::all()->toArray();
            $deliveryman = Deliveryman::findOrFail($id);
            return view('admin.deliverymen.edit', compact('deliveryman', 'appsettings', 'city', 'state', 'country', 'vehicle_type', 'delivery_zone', 'delivery_man_type'));
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        // Validate the request data here

        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('edit_delivery_boy')) {
                return view('admin.errors.unauthorized');
            }
            if (!$request->isMethod('put')) {
                // Handle the error - Method not allowed
                Alert::toast('Method not allowed', 'error');
                return redirect()->back();
            }

            $validatedData = $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'phone' => 'required',
                'identity_type' => 'required',
                'identity_number' => 'required',
                'address' => 'required',
                'email' => 'required|email',
            ]);
            // dd($validatedData);

            // Create a new Deliveryman instance
            $deliveryBoy = Deliveryman::findOrFail($id);

            $deliveryBoy->first_name = $request->input('first_name');
            $deliveryBoy->last_name = $request->input('last_name');
            $deliveryBoy->address = $request->input('address');
            $deliveryBoy->country = $request->input('country');
            $deliveryBoy->state = $request->input('state');
            $deliveryBoy->city = $request->input('city');
            $deliveryBoy->identity_type = $request->input('identity_type');
            $deliveryBoy->identity_number = $request->input('identity_number');
            $deliveryBoy->phone = $request->input('phone');
            $deliveryBoy->salary = $request['salary'];
            $deliveryBoy->email = $request->input('email');
            $deliveryBoy->password = bcrypt($request->input('password'));
            $deliveryBoy->delivery_man_type = $request->input('delivery_man_type');
            $deliveryBoy->delivery_zone = $request->input('delivery_zone');
            $deliveryBoy->vehicle_type = $request->input('vehicle_type');
            $deliveryBoy->vehicle_capacity = $request->input('vehicle_capacity');
            $deliveryBoy->driving_license_number = $request->input('driving_license_number');
            $deliveryBoy->status=$request->input('status');


            if ($request->hasFile('delivery_man_image')) {
                if ($deliveryBoy->delivery_man_image) {
                    $oldFileName = basename($deliveryBoy->delivery_man_image);
                    Storage::delete('public/delivery_man/' . $oldFileName);
                }

                $file = $request->file('delivery_man_image');
                $fileNameToStore = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/delivery_man', $fileNameToStore);
                $deliveryBoy->delivery_man_image = asset('storage/delivery_man/' . $fileNameToStore);
            }

            if ($request->hasFile('identity_image')) {
                if ($deliveryBoy->identity_image) {
                    $oldFileName = basename($deliveryBoy->identity_image);
                    Storage::delete('public/delivery_man/' . $oldFileName);
                }

                $file = $request->file('identity_image');
                $fileNameToStore = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/delivery_man', $fileNameToStore);
                $deliveryBoy->identity_image = asset('storage/delivery_man/' . $fileNameToStore);
            }

            if ($request->hasFile('driving_license_image')) {
                if ($deliveryBoy->driving_license_image) {
                    $oldFileName = basename($deliveryBoy->driving_license_image);
                    Storage::delete('public/delivery_man/' . $oldFileName);
                }

                $file = $request->file('driving_license_image');
                $fileNameToStore = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/delivery_man', $fileNameToStore);
                $deliveryBoy->driving_license_image = asset('storage/delivery_man/' . $fileNameToStore);
            }

            // Save the deliveryman to the database
            $deliveryBoy->save();

            Alert::toast('DeliveryMan has been updated!', 'success');

            return redirect()->route('delivery_boy.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel's built-in validation exception
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->hasPermissionByRole('delete_delivery_boy')) {
                return view('admin.errors.unauthorized');
            }
            $deliveryman = DeliveryMan::findOrFail($id);

            if ($deliveryman->delivery_man_image) {
                $oldFileName = basename($deliveryman->delivery_man_image);
                Storage::delete('public/delivery_man/' . $oldFileName);
            }

            if ($deliveryman->identity_image) {
                $oldFileName = basename($deliveryman->identity_image);
                Storage::delete('public/delivery_man/' . $oldFileName);
            }
            if ($deliveryman->driving_license_image) {
                $oldFileName = basename($deliveryman->driving_license_image);
                Storage::delete('public/delivery_man/' . $oldFileName);
            }


            $deliveryman->delete();
            Alert::toast('DeliveryMan has beeb deleted!', 'error');
            return redirect()->route('delivery_boy.index')->with('success', 'Deliveryman deleted successfully.');
        } catch (\Exception $e) {
            Alert::toast('something is wrong!!', 'error');
            return redirect()->back();
        }
    }

    public function detail($id){
        $delivery_men=DeliveryMan::findOrFail($id);

        $men = DeliveryMan::where('id', $delivery_men->id)->first();
        $available_to_withdraw = $men->total_earn;

        $totalEarned = DeliveryManTip::where('delivery_man_id', $delivery_men->id)
            ->whereIn('status', ['earned', 'withdrawn', 'pending']) // total earned
            ->sum('tip_amount');

        // Available for withdrawal
        $withdrawn = $totalEarned - $men->total_earn;

        // Pending withdraw requests
        $pendingWithdraw = DeliveryManWithdrawRequest::where('delivery_man_id', $delivery_men->id)
            ->where('status', 'pending')
            ->sum('amount');

        // Withdraw history
        $withdrawals = DeliveryManWithdrawRequest::where('delivery_man_id', $delivery_men->id)
            ->latest()->get();

                // dd(1);
      return view('admin.deliverymen.view_detail',compact('men','totalEarned','withdrawn','pendingWithdraw','withdrawals','available_to_withdraw'));

    }

     public function updatePassword(Request $request, $id)
    {
        // dd($request->password);
        $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = DeliveryMan::findOrFail($id);
        $user->password = Hash::make($request->password);
        $user->save();

        Alert::toast('Delivery Boy password updated successfully!', 'success');
        return redirect()->back();
    }

    public function is_active($id){
        $delivery_man=DeliveryMan::findOrFail($id);
        $delivery_man->is_active=!$delivery_man->is_active;
        $delivery_man->save();

        Alert::toast('Delivery Boy account status updated!', 'success');
        return redirect()->back();
    }

}
