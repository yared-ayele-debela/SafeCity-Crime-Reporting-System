@extends('all_frontend_layouts.layouts')
@section('content')
<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="offer-card p-4 rounded shadow-sm ">
                <h2 class="account-h2 mb-3 text-dark text-center">Forgot Password?</h2>
                <h6 class="account-h6 mb-4 text-dark text-center">Enter your phone number below to link to reset your password.</h6>
                <p id="forget-error" class="text-danger"></p>
                <p id="forget-success" class="text-success"></p>
                <form class="needs-validation" method="POST" action="{{ url('forgot-password/send-otp') }}">
                    @csrf
                    <div class="mb-3">
                            <label for="identifier" class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" id="identifier" name="identifier" class="form-control" required>
                            <p id="forget-error" class="text-danger"></p>
                        </div>
                    <div class="mb-3">
                        <button type="submit" class="btn text-white bg-primary w-100">Submit</button>
                    </div>
                </form>
                <div class="page-anchor mt-3">
                    <a href="{{ url('auth/login') }}" class="text-decoration-none text-dark">
                        <i class="fas fa-arrow-left me-2"></i>Back to Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
