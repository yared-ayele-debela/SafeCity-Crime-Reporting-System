
@php
    $newReports = \App\Models\SafeCity\Report::where('status', 'open')->count();
@endphp
<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
         <a href="{{ route('maindashboard') }}" class="logo d-flex align-items-center"> <img src="{{ $appsettings[0]['logo'] }}" alt=""> </a> <i class="bi bi-list toggle-sidebar-btn"></i></div>

    <!-- layouts/dashboard.blade.php -->
    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
           
                <li class="nav-item dropdown pe-3">
                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown"> <img src="{{ Auth::guard('admin')->user()->image }}" alt="Profile" class="rounded-circle"> <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::guard('admin')->user()->name }}</span> </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6>{{ Auth::guard('admin')->user()->name }}</h6>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('updateadmindetails') }}"> <i class="bi bi-person"></i> <span>My Profile</span> </a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li> <a class="dropdown-item d-flex align-items-center" href="{{ route('update_admin_password') }}"> <i class="bi bi-lock"></i> <span>Change Password</span> </a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <hr class="dropdown-divider">
            </li>
            <li> <a class="dropdown-item d-flex align-items-center" href="{{ route('adminlogout') }}"> <i class="bi bi-box-arrow-right"></i> <span>Sign Out</span> </a></li>
        </ul>

    </nav>
    @include('sweetalert::alert')

</header>
