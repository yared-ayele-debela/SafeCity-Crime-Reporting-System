<?php

use App\Http\Controllers\Admin\AdminActivityLogController;
use App\Http\Controllers\Admin\AdminController as AdminAdminController;
use App\Http\Controllers\Admin\AdminResetController;
use App\Http\Controllers\Admin\AdminRolesController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\AdminController;

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Dashboard\DashboardController;

use App\Http\Controllers\Admin\AppSettingController;

use App\Http\Controllers\Admin\Blog\BlogsCategoryController;
use App\Http\Controllers\Admin\Blog\BlogsCommmentController;
use App\Http\Controllers\Admin\Blog\BlogsController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\CountryController;

use App\Http\Controllers\Admin\FaqController;

use App\Http\Controllers\Admin\NewletterSubscriberController;

use App\Http\Controllers\Admin\PermissionCategoriesController;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RolePermissions;
use App\Http\Controllers\Admin\RolesController;

use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\StreetController;
use App\Http\Controllers\Admin\SubCityController;
use App\Http\Controllers\CustomOrderController;
use App\Http\Controllers\Front\FrontCmsController;
use App\Http\Controllers\Front\UserController;
use App\Http\Controllers\SafeCity\AuthController;
use App\Http\Controllers\SafeCity\BlogsController as SafeCityBlogsController;
use App\Http\Controllers\SafeCity\FrontendController;
use App\Http\Controllers\SafeCity\NewslettersController;
use App\Http\Controllers\SafeCity\OfferDepartmentController;
use App\Http\Controllers\SafeCity\ProfileController;
use App\Http\Controllers\SafeCity\ReportController as SafeCityReportController;
use App\Http\Controllers\SafeCity\UserDashboardController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Auth::routes();
//Clear Cache facade value:
Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function () {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/route-cache', function () {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function () {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function () {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function () {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});


Route::get('/foo', function () {
    Artisan::call('storage:link');
});


Route::prefix('admin')->group(function () {
    Route::get('forgot-password', [AdminResetController::class, 'ForgetPassword'])->name('admin.forgot.password');
    Route::post('forgot-password', [AdminResetController::class, 'ForgetPasswordStore'])->name('admin.forgot.password.store');
    Route::get('reset-password/{token}', [AdminResetController::class, 'ResetPassword'])->name('admin.reset.password');
    Route::post('reset-password', [AdminResetController::class, 'ResetPasswordStore'])->name('admin.reset.password.store');
});

// Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('admin')->group(function () {
    Route::get('login', [AdminController::class, 'loginpage'])->name('admin_login');
    Route::post('login', [AdminController::class, 'loginvalidate'])->name('login_admin');
    Route::group(['middleware' => ['admin']], function () {
        Route::get('update_admin_password', [AdminController::class, 'updateadminpassword'])->name('update_admin_password');
        Route::post('updateadminpassword', [AdminController::class, 'update_admin_password'])->name('updateadminpassword');

        Route::get('updateadmindetails', [AdminController::class, 'updateadmindetails'])->name('updateadmindetails');
        Route::put('update_admin_details', [AdminController::class, 'update_admin_details'])->name('update_admin_details');

});

Route::group(['middleware' => ['admin']], function () {
Route::get('adminlogout', [AdminController::class, 'logout'])->name('adminlogout');
});
Route::group(['middleware' => ['admin']], function () {


        Route::get('cities', [CityController::class, 'index'])->name('cities');
        Route::get('city/add', [CityController::class, 'create'])->name('add-city');
        Route::post('city/store', [CityController::class, 'store'])->name('store-city');
        Route::get('city/edit/{id}', [CityController::class, 'edit'])->name('edit-city');
        Route::put('city/update', [CityController::class, 'update'])->name('update-city');
        Route::get('city/delete/{id}', [CityController::class, 'delete'])->name('delete-city');
        Route::get('city/active/{id}', [CityController::class, 'active'])->name('active-city');
        Route::get('city/inactive/{id}', [CityController::class, 'inactive'])->name('inactive-city');

        Route::resource('sub-cities', SubCityController::class);
        Route::resource('streets', StreetController::class);


        Route::get('states', [StateController::class, 'index'])->name('states');
        Route::get('state/add', [StateController::class, 'create'])->name('add-state');
        Route::post('state/store', [StateController::class, 'store'])->name('store-state');
        Route::get('state/edit/{id}', [StateController::class, 'edit'])->name('edit-state');
        Route::put('state/update', [StateController::class, 'update'])->name('update-state');
        Route::get('state/delete/{id}', [StateController::class, 'delete'])->name('delete-state');
        Route::get('state/active/{id}', [StateController::class, 'active'])->name('active-state');
        Route::get('state/inactive/{id}', [StateController::class, 'inactive'])->name('inactive-state');

        Route::get('countries', [CountryController::class, 'index'])->name('countries');
        Route::get('country/add', [CountryController::class, 'create'])->name('add-country');
        Route::post('country/store', [CountryController::class, 'store'])->name('store-country');
        Route::get('country/edit/{id}', [CountryController::class, 'edit'])->name('edit-country');
        Route::put('country/update', [CountryController::class, 'update'])->name('update-country');
        Route::get('country/delete/{id}', [CountryController::class, 'delete'])->name('delete-country');
        Route::get('country/active/{id}', [CountryController::class, 'active'])->name('active-country');
        Route::get('country/inactive/{id}', [CountryController::class, 'inactive'])->name('inactive-country');

        //permission-categories
        //routing for permission categories
        Route::get('permissions-categories', [PermissionCategoriesController::class, 'index'])->name('permissions-categories');
        Route::get('permissions/category/add', [PermissionCategoriesController::class, 'create'])->name('add-permission-category');
        Route::post('permissions/category/store', [PermissionCategoriesController::class, 'store'])->name('store-permission-category');
        Route::get('permissions/category/edit/{id}', [PermissionCategoriesController::class, 'edit'])->name('edit-permission-category');
        Route::put('permissions/category/update', [PermissionCategoriesController::class, 'update'])->name('update-permission-category');
        Route::get('permissions/category/delete/{id}', [PermissionCategoriesController::class, 'delete'])->name('delete-permission-category');
        Route::get('permissions/category/active/{id}', [PermissionCategoriesController::class, 'active'])->name('active-permission-category');
        Route::get('permissions/category/inactive/{id}', [PermissionCategoriesController::class, 'inactive'])->name('inactive-permission-category');


        //routing for blog category
        Route::get('blog-categories', [BlogsCategoryController::class, 'index'])->name('blog-categories');
        Route::get('blog/category/add', [BlogsCategoryController::class, 'create'])->name('add-blog-category');
        Route::post('blog/category/store', [BlogsCategoryController::class, 'store'])->name('store-blog-category');
        Route::get('blog/category/edit/{id}', [BlogsCategoryController::class, 'edit'])->name('edit-blog-category');
        Route::put('blog/category/update', [BlogsCategoryController::class, 'update'])->name('update-blog-category');
        Route::get('blog/category/delete/{id}', [BlogsCategoryController::class, 'delete'])->name('delete-blog-category');
        Route::get('blog/category/active/{id}', [BlogsCategoryController::class, 'active'])->name('active-blog-category');
        Route::get('blog/category/inactive/{id}', [BlogsCategoryController::class, 'inactive'])->name('inactive-blog-category');


        //routing for blogs
        //routing for blog category
        Route::get('blogs', [BlogsController::class, 'index'])->name('blogs');
        Route::get('blogs/add', [BlogsController::class, 'create'])->name('add-blog');
        Route::post('blogs/store', [BlogsController::class, 'store'])->name('store-blog');
        Route::get('blogs/edit/{id}', [BlogsController::class, 'edit'])->name('edit-blog');
        Route::put('blogs/update', [BlogsController::class, 'update'])->name('update-blog');
        Route::get('blogs/delete/{id}', [BlogsController::class, 'delete'])->name('delete-blog');
        Route::get('blogs/active/{id}', [BlogsController::class, 'active'])->name('active-blog');
        Route::get('blogs/inactive/{id}', [BlogsController::class, 'inactive'])->name('inactive-blog');


        //for blog-comment
        Route::get('blog-comments', [BlogsCommmentController::class, 'index'])->name('blog-comments');
        Route::get('blog-comment/delete/{id}', [BlogsCommmentController::class, 'delete'])->name('delete-blog-comment');
        Route::get('blog-comment/active/{id}', [BlogsCommmentController::class, 'active'])->name('active-blog-comment');
        Route::get('blog-comment/inactive/{id}', [BlogsCommmentController::class, 'inactive'])->name('inactive-blog-comment');


        //for role and permission
        Route::resource('roles', RolesController::class);
        Route::resource('permissions', PermissionsController::class);

        Route::get('role/{id}/permission', [RolePermissions::class, 'edit'])->name('role_permissions_edit');
        Route::put('roles/{role}/permissions', [RolePermissions::class, 'update'])->name('role_permissions.update');

        Route::get('user/{id}/role', [RolePermissions::class, 'edit'])->name('user_role_edit');
        Route::put('users/{user}/roles', [AdminRolesController::class, 'update'])->name('user_roles.update');

        Route::get('all-admins', [AdminAdminController::class, 'index'])->name('all-admins.index');
        Route::get('users/{user}/assign-role', [AdminAdminController::class, 'assignRole'])->name('users.assign_role');
        Route::post('users/{user}/update-role', [AdminAdminController::class, 'updateRole'])->name('users.update_role');

        Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('maindashboard');

        // Route::get('/activities', [DashboardController::class, 'getFilteredActivities'])->name('admin.activities');
        Route::get('/admin/activities', action: [DashboardController::class, 'fetch']);

        //for update vendor
        Route::get('update-vendor-details', [AdminController::class, 'updatevendordetails'])->name('updatevendordetails');
        Route::get('update-vendor-bank-details', [AdminController::class, 'updatevendorbankdetails'])->name('updatevendorbankdetails');
        Route::get('update-vendor-business-details', [AdminController::class, 'updatevendorbusinessdetails'])->name('updatevendorbusinessdetails');
  Route::get('newslettersubscribers', [NewletterSubscriberController::class, 'lists'])->name('newslettersubscribers');
        Route::get('newslettersubscribers/inactive/{id}', [NewletterSubscriberController::class, 'inactive'])->name('inactive_newslettersubscribers');
        Route::get('newslettersubscribers/active/{id}', [NewletterSubscriberController::class, 'active'])->name('active_newslettersubscribers');
        Route::get('newslettersubscribers/delete/{id}', [NewletterSubscriberController::class, 'delete'])->name('delete_newslettersubscribers');


 Route::get('send-email-to-all', [NewletterSubscriberController::class, 'create'])->name('send-email-to-all');
        Route::post('send-email-to-all-users', [NewletterSubscriberController::class, 'send'])->name('send-email-to-all-users');
        //routing for vendor status
        Route::get('admin/active/{id}', [AdminController::class, 'active_user'])->name('active_admin');
        Route::get('admin/inactive/{id}', [AdminController::class, 'inactive_user'])->name('inactive_admin');

        // display all users on admin table
        Route::get('all/admins', action: [AdminController::class, 'displayall'])->name('alladmins');
        Route::get('activity/{id}', [AdminActivityLogController::class, 'activity_log'])->name('admin_activity_log');
         //Routing for banners

        Route::match(['get', 'post'], 'banners', [BannerController::class, 'banners'])->name('banners');
        //routing for active and inactive product images
        Route::get('banners/active/{id}', [BannerController::class, 'active_banner'])->name('active_banner');
        Route::get('banners/inactive/{id}', [BannerController::class, 'inactive_banner'])->name('inactive_banner');

        Route::get('banners/delete/{banner_id}', [BannerController::class, 'delete'])->name('delete_banners');
        Route::get('banners/create', [BannerController::class, 'create'])->name('create_banners');
        //    Route::post('banners/store',[BannerController::class,'store'])->name('store_banners');

        Route::get('banners/edit/{id}', [BannerController::class, 'edit'])->name('edit_banner');
        Route::put('banner/update', [BannerController::class, 'update'])->name('update_banner');

        Route::get('users', [AdminUserController::class, 'users'])->name('users');
        Route::put('/users/{id}/update-password', [AdminUserController::class, 'updatePassword'])->name('admin.users.updatePassword');

                Route::put('/admin/{id}/update-password', action: [AdminController::class, 'updatePassword'])->name('admin.updatePassword');

        Route::get('users/active/{user_id}', [AdminUserController::class, 'active'])->name('active_users');
        Route::get('users/inactive/{user_id}', [AdminUserController::class, 'inactive'])->name('inactive_users');
        Route::get('users/{user_id}/delete', [AdminUserController::class, 'destory'])->name('delete_user');


        Route::get('admins-subadmins', [AdminController::class, 'adminsubadmins'])->name('admin_subadmin');
        Route::get('admin_subadmin_active/{id}', [AdminController::class, 'active_admin_and_subadmin'])->name('active_admin_subadmin');
        Route::get('admin_subadmin_inactive/{id}', [AdminController::class, 'inactive_admin_and_subadmin'])->name('inactive_admin_subadmin');
        Route::get('admin-subadmin/{id}', [AdminController::class, 'delete_admin_and_subadmin'])->name('delete_admin_or_subadmin');

        Route::get('add_admin', [AdminController::class, 'add_admin_or_subadmin'])->name('add_admin_or_subadmin');
        Route::post('store_admin_or_subadmin', [AdminController::class, 'store_admin_or_subadmin'])->name('store_admin_or_subadmin');
        Route::get('edit_admin/{id}', [AdminController::class, 'edit_admin_or_subadmin'])->name('edit_admin_or_subadmin');
        Route::put('update_admin_or_subadmin', [AdminController::class, 'update_admin_or_subadmin'])->name('update_admin_or_subadmin');

        Route::get('add_user', [AdminUserController::class, 'adduser'])->name('add_user');
        Route::post('store_user', [AdminUserController::class, 'store_user'])->name('store_user');
        Route::get('edit_user/{id}', [AdminUserController::class, 'edit_user'])->name('edit_user');
        Route::put('udpate_user', [AdminUserController::class, 'update_user'])->name('update_user');


        // Route::get('cms-pages', [CmsController::class, 'cmspages']);
        // Route::get('cms/active/{cms_id}', [CmsController::class, 'active'])->name('active_cms');
        // Route::get('cms/inactive/{cms_id}', [CmsController::class, 'inactive'])->name('inactive_cms');
        // Route::get('cms/delete/{cms_id}', [CmsController::class, 'delete'])->name('delete_cms');

        // Route::get('add_cms_page', [CmsController::class, 'create'])->name('add_cms_page');
        // Route::get('edit_cms_page/{id}', [CmsController::class, 'edit'])->name('edit_cms_page');
        // Route::post('store_cms_page', [CmsController::class, 'store'])->name('store_cms_page');
        // Route::put('update_cms_page', [CmsController::class, 'update'])->name('update_cms_page');


        //routing for app settings
        Route::get('appsettings', [AppSettingController::class, 'create'])->name('appsettings');
        Route::put('appsettings/update', [AppSettingController::class, 'update'])->name('update_appsettings');

        Route::get('allfaq', [FaqController::class, 'allfaq'])->name('allfaq');
        Route::get('faq/add', [FaqController::class, 'create'])->name('add_faq');
        Route::post('store_faq', [FaqController::class, 'store'])->name('store_faq');
        Route::put('update_faq', [FaqController::class, 'update'])->name('update_faq');

        Route::get('faq/edit/{id}', [FaqController::class, 'edit'])->name('edit_faq');
        Route::get('faq/delete/{id}', [FaqController::class, 'delete'])->name('delete_faq');
        Route::get('faq/inactive/{id}', [FaqController::class, 'inactive'])->name('inactive_faq');
        Route::get('faq/active/{id}', [FaqController::class, 'active'])->name('active_faq');

    });
        Route::group(['middleware' => 'admin'], function () {
        Route::put('update_vendor_details', [AdminController::class, 'update_vendor_details'])->name('update_vendor_details')->middleware('admin');
        Route::get('updateadmindetails', [AdminController::class, 'updateadmindetails'])->name('updateadmindetails');

        Route::put('update_admin_details', [AdminController::class, 'update_admin_details'])->name('update_admin_details');

        Route::resource(name: 'officer', controller: OfferDepartmentController::class)->only(['index', 'store', 'update','destroy']);


          Route::get('/reports', action: [ReportController::class, 'index'])->name('admin.reports');
    Route::get('/reports/{id}', action: [ReportController::class, 'show'])->name('admin.reports.show');
    Route::post('/assignments', [ReportController::class, 'store'])->name('assignments.store');

    });Route::patch( '/reports/{report}/status', [ReportController::class, 'updateStatus'])
    ->name('admin.reports.updateStatus');

});

// Route::get('page/{url}', [FrontCmsController::class, 'pages'])->name('pages');
Route::post('store-custom-orders', [CustomOrderController::class, 'store_fast_order'])->name('store_custom_order');

// /Route::match(['GET', 'POST'], '/add-rating', [RatingController::class, 'addRating'])->name('add_rating');
Route::post('newslettersubscriber', [NewletterSubscriberController::class, 'store'])->name('newslettersubscriber');
//User Logout
Route::get('user/logout', [UserController::class, 'userLogout']);
//Confirm User Account
Route::get('faq', [FaqController::class, 'index'])->name('faq');

Route::get('blogs', action: [SafeCityBlogsController::class, 'index'])->name('display-blogs');
Route::get('blogs/details/{id}', [SafeCityBlogsController::class, 'details'])->name('blogs-details');
Route::post('store-blogs', [SafeCityBlogsController::class, 'store'])->name('store-blogs');

Route::get('/',[FrontendController::class,'index'])->name('home-page');
Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::get('/faqs', [FrontendController::class, 'faq'])->name('faqs');
Route::post('/subscribe-newsletter', action: [NewslettersController::class, 'store']);


Route::group(['middleware' => 'auth'], function () {
    Route::get('user/dashboard', [UserDashboardController::class, 'dashboard'])->name('user.dashboard');
    Route::get('user/profile', action: [UserDashboardController::class, 'profile'])->name('user.profile');
    Route::put('user/profile/update', [UserDashboardController::class, 'updateProfile'])->name('user.profile.update');
    Route::put('user/password/update', [UserDashboardController::class, 'updatePassword'])->name('user.password.update');

    Route::get('user/reports', [UserDashboardController::class, 'reports'])->name('user.reports');

    Route::get('/report/{id}', action: [UserDashboardController::class, 'show'])->name('user.report.show')->middleware('auth');

      Route::get(uri: '/profile', action: [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::post('logout', [AuthController::class, 'logout'])->name(name: 'logout');

     Route::get('/reports/create', [SafeCityReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [SafeCityReportController::class, 'store'])->name('reports.store');

    Route::get('/report-success/{tracking_code}', action: [SafeCityReportController::class, 'success'])->name('report.success');


});

   Route::get('/track-report', action: [SafeCityReportController::class, 'trackForm'])->name('report.track.form');
Route::post('/track-report', action: [SafeCityReportController::class, 'track'])->name('report.track');


Route::group(['middleware'=>'guest'], function(){


Route::get('register', action: [AuthController::class, 'showRegister'])->name('register.form');
Route::post('register', [AuthController::class, 'register'])->name('register');

Route::get('login', [AuthController::class, 'showLogin'])->name('login.form');
Route::post('login', [AuthController::class, 'login'])->name('login');

Route::get('forgot-password', [AuthController::class, 'showForgot'])->name('password.request');
Route::post('forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('reset-password/{token}', [AuthController::class, 'showReset'])->name('password.reset');
Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/contact', [FrontendController::class, 'store'])->name('contact.store');