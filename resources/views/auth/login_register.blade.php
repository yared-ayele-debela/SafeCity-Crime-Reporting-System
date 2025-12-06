@extends('all_frontend_layouts.layouts')

@section('content')
<div class="container d-flex align-items-center justify-content-center">
    <div class="row rounded py-4 overflow-hidden">
        <div class="col-md-6 d-md-block text-center">
            <img src="{{ asset('restaurant_frontend/assets/img/Illustration.png') }}" alt="Login Illustration" class="img-fluid " style="object-fit: cover; max-height:500px;max-weight:500px;">
        </div>
        <div class="col-md-5 border rounded p-4">
            <h4 class="text-center mb-4 text-primary">Easy eCommerce, Hotel Booking, and Food Delivery</h4>
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if(session('info'))
            <div class="alert alert-warning">{{ session('info') }}</div>
            @endif
            <ul class="nav nav-pills mb-3 d-flex justify-content-center align-items-center" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Login</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link " id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Create Account</a>
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade " id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                    <form action="{{ route('auth.register') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Full Name</label>
                                    <input type="text" name="name" class="form-control" id="full_name" placeholder="Enter your full name" required>
                                    @error('name')
                                    <small class=" text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Phone Number -->
                                <div class="mb-3">
                                    <label for="phoneNumber" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" id="phoneNumber" name="phone" placeholder="Enter your phone number" required>
                                    @error('phone')
                                    <small class=" text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="emails" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="emails" name="emails" placeholder="Enter your email" required>
                                    @error('emails')
                                    <small class=" text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                                    @error('password')
                                    <small class=" text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="confirmation_password" class="form-label">Confirmation Password</label>
                                    <input type="password" class="form-control" id="confirmation_password" name="password_confirmation" placeholder="Confirm your password" required>
                                    @error('password_confirmation')
                                    <small class=" text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="checkbox" class="check-box" id="accept">
                                    <label class="label-text no-color" for="accept">I’ve read and accept the
                                        <a href="javascript:void();" class="text-primary">terms &amp; conditions</a>
                                    </label>
                                    @error('terms')
                                    <small class=" text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn bg-primary w-100 text-white py-2">Create Account</button>
                    </form>
                </div>
                <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                    <form method="POST" action="{{ url('auth/login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="mobile" class="form-label">Mobile Number</label>
                            <input type="mobile" class="form-control" id="mobile" name="mobile" placeholder="Enter your mobile" required>
                            @error('mobile')
                            <small class=" text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="passwords" placeholder="Enter your password" required>
                            @error('passwords')
                            <small class=" text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <label class="mb-2">
                            <input type="checkbox" name="remember" checked> Remember Me
                        </label>
                        <button type="submit" class="btn bg-primary w-100 text-white mb-2">Login</button>
                        <a href="{{ url('delivery-boy/login') }}" type="submit" class="btn btn-outline-secondary w-100">Login as Driver</a>

                        <a href="{{ route('google.login') }}" class="btn border border-1 w-100 text-dark mt-2"><img src="{{ asset('restaurant_frontend/Logo-google-icon-PNG.png') }}" width="25"></img> Sign in with Google</a>
                        <div class="py-2">
                            <a href="{{ url('auth/forgot-password') }}" class="text-dark">Forget Password</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

