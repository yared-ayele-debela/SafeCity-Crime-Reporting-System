@include('admindashboard.css.css_file')
<body>
    @include('sweetalert::alert')
<div class="container vh-100 d-flex justify-content-center align-items-center bg-light px-3">
    <div class="col-lg-4 col-md-6">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">

                {{-- Logo --}}
                <div class="text-center mb-3">
                    <img src="{{ $appsettings[0]['logo'] }}" class="img-fluid"  style="max-height: 100px;"alt="Logo">
                </div>

                <h4 class="fw-bold text-center mb-1">Admin Login</h4>
                <p class="text-muted text-center mb-4">Enter your credentials to access your account</p>

                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Login Form --}}
                <form method="POST" action="{{ url('admin/login') }}" class="needs-validation" novalidate>
                    @csrf

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" required placeholder="Enter your email">
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-4">
                        <label for="yourPassword" class="form-label">Password</label>
                        <input type="password" id="yourPassword" name="password" class="form-control @error('password') is-invalid @enderror" pattern=".{8,}" title="At least 8 characters required" required placeholder="Enter your password">
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </div>

                    {{-- Forgot Password --}}
                    <div class="text-center">
                        <a href="{{ url('admin/forgot-password') }}" class="small text-decoration-none text-primary">Forgot your password?</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ session('error') }}'
        });
    </script>
@endif
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Bootstrap validation
    (function () {
        'use strict';
        window.addEventListener('load', function () {
            var forms = document.getElementsByClassName('needs-validation');
            Array.prototype.filter.call(forms, function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>
  @include('admindashboard.js.js_file')
