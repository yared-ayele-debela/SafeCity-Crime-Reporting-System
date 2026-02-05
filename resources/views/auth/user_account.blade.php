@extends('all_frontend_layouts.layouts')
@section('content')

<!-- Page Introduction Wrapper /- -->
<!-- Lost-password-Page -->
<div class="page-lost-password u-s-p-t-80">
    <div class="container items-center">
        <div class="row">
            <div class="col-lg-6">
                    <div class="login-wrapper rounded-sm p-4 shadow-sm">
                        <h1 class="account-h2 u-s-m-b-20">Update your password</h1>
                        <br>
                        <form method="POST" action="{{ url('user/password/update') }}">
                            @csrf
                            @method('PUT')
                            <div class="u-s-m-b-30">
                                <label for="oldpassword">Current_password
                                    <span class="astk">*</span>
                                </label>
                                <input  type="password" name="oldpassword"  id="oldpassword"  class="text-field" required="" placeholder="Old password">
                                @error('oldpassword')
                                <small class=" text-danger">{{ $message }}</small>
                               @enderror                            </div>
                            <div class="u-s-m-b-30">
                                <label for="new_password">New Password
                                    <span class="astk">*</span>
                                </label>
                                <input type="password" class="text-field" placeholder="Password" name="new_password"  id="new_password">
                                @error('new_password')
                                <small class=" text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="u-s-m-b-30">
                                <label for="new_confirm_password">New Password
                                    <span class="astk">*</span>
                                </label>
                                <input type="password" class="text-field" placeholder="Confirm password" name="new_confirm_password"  id="new_confirm_password">
                                @error('new_confirm_password')
                                <small class=" text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="m-b-45">
                                <button type="submit" class="button button-primary w-100">Update Password</button>
                            </div>
                            <p>Forget <a class="link-danger" href="{{ url('user/forgot-password') }}">your password?</a></p>
                        </form>
                    </div>
                </div>

            <div class="col-lg-6">
                    <div class="reg-wrapper rounded-sm p-4 shadow-sm">
                        <h6 class="account-h3 u-s-m-b-30">Update your personal details</h6>
                        <form  action="{{ url('user/account') }}" method="POST" enctype="multipart/form-data" >
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="user_id"  value="{{ Auth::user()->id }}">
                            <div class="u-s-m-b-30">
                                <label for="user-name">Name
                                    <span class="astk">*</span>
                                </label>
                                <input type="text" name="name" class="text-field" value="{{ Auth::user()->name }}"  placeholder="Name">
                                @error('name')
                                <small class=" text-danger">{{ $message }}</small>
                               @enderror
                            </div>
                            <div class="u-s-m-b-30">
                                <label for="email">Email
                                    <span class="astk">*</span>
                                </label>
                                <input type="email" name="email" id="email" class="text-field" value="{{ Auth::user()->email }}" disabled placeholder="Email">
                                @error('email')
                                <small class=" text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="u-s-m-b-30">
                                <label for="country">Country
                                    <span class="astk">*</span>
                                </label>
                                <select name="country" id="user-country" class=" text-field">
                                    <option selected disabled>Select Country</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country['country_name'] }}" @if($country['country_name']==Auth::user()->country) selected @endif>{{ $country['country_name'] }}</option>
                                @endforeach
                                </select>
                                @error('country')
                                <small class=" text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="u-s-m-b-30">
                                <label for="mobile">phone
                                    <span class="astk">*</span>
                                </label>
                                <input type="number" name="mobile" id="user-mobile" class="text-field" value="{{ Auth::user()->mobile }}" placeholder="Phone ">
                                @error('mobile')
                                <small class=" text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="u-s-m-b-30">
                                <label for="address">address
                                    <span class="astk">*</span>
                                </label>
                                <input type="text" name="address" id="user-address" class="text-field" value="{{ Auth::user()->address }}" placeholder="address ">
                                @error('address')
                                <small class=" text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="u-s-m-b-30">
                                <div class="form-group">
                                    <label for="state">State</label>
                                    <select class="text-field" name="state" id="user-state">
                                    <option disabled value="" selected>select one</option>
                                    @foreach ($state as $state )
                                    <option @if(Auth::user()->state==$state->name) selected @endif value="{{ $state->name }}">{{ $state->name }}</option>
                                    @endforeach
                                  </select>
                                  @error('state')
                                  <small class=" text-danger">{{ $message }}</small>
                                  @enderror
                                </div>
                            </div>
                            <div class="u-s-m-b-30">
                                <div class="form-group">
                                    <label for="city">city
                                        <span class="astk">*</span>
                                    </label>
                                    <select name="city" id="user-city" class="text-field">
                                    <option disabled value="" selected>select one</option>
                                    @foreach ($city as $city )
                                    <option @if(Auth::user()->city==$city->name) selected @endif value="{{ $city->name }}">{{ $city->name }}</option>
                                    @endforeach
                                  </select>
                                  @error('city')
                                  <small class=" text-danger">{{ $message }}</small>
                                  @enderror
                                </div>
                            </div>

                            <div class="u-s-m-b-30">
                                <label for="pincode">pincode
                                    <span class="astk">*</span>
                                </label>
                                <input type="text" name="pincode" id="user-pincode" class="text-field" value="{{ Auth::user()->pincode }}" placeholder="pincode ">
                                @error('pincode')
                                <small class=" text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- <div class="u-s-m-b-30">
                                <input type="checkbox" class="check-box" id="accept">
                                <label class="label-text no-color" for="accept">I’ve read and accept the
                                    <a href="terms-and-conditions.html" class="u-c-brand">terms &amp; conditions</a>
                                </label>
                                 @error('terms')
                                <small class=" text-danger">{{ $message }}</small>
                                @enderror
                            </div> --}}
                            <div class="u-s-m-b-45">
                                <button type="submit" class="button button-primary w-100">Update Account Details</button>
                            </div>
                        </form>
                    </div>
                </div>
        </div>
    </div>
</div>

@endsection

