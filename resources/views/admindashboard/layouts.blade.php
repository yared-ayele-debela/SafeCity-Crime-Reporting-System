<?php

use App\Models\AppSetting;
use App\Models\CmsPage;

$cms_pages = CmsPage::get()->toArray();
$appsettings = AppSetting::all()->toArray();
?>
@include('admindashboard.css.css_file')
@include('admindashboard.header')
@include('admindashboard.sidebar')
<main id="main" class="main">
    @yield('dashboard')
</main>
@include('admindashboard.footer')
@include('admindashboard.js.js_file')
