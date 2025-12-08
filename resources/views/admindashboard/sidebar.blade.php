<aside id="sidebar" class=" colored sidebar ">
    @php
        $user = Auth::guard('admin')->user();
    @endphp
    <ul class="sidebar-nav" id="sidebar-nav">

        <li class=" {{ request()->is('admin/dashboard') ? 'nav-item active' : '' }}">
            <a href="{{ url('admin/dashboard') }}" class="nav-link {{ request()->is('admin/dashboard') }}"> <i
                    class="bi bi-menu-button-fill"></i><span>Dashboard</span> </a>
        </li>


        @if (
            $user->hasPermissionByRole('view_permission') ||
                $user->hasPermissionByRole('view_role') ||
                $user->hasPermissionByRole('view_admin'))
            <li
                class="{{ request()->is('admin/permissions') ? 'nav-item active' : '' }} {{ request()->is('admin/permissions*') ? 'nav-item active' : '' }} {{ request()->is('admin/all-delivery-boys') ? 'nav-item active' : '' }} {{ request()->is('admin/delivery-boy/*') ? 'nav-item active' : '' }} {{ request()->is('admin/permissions/create') ? 'nav-item active' : '' }} {{ request()->is('admin/roles') ? 'nav-item active' : '' }} {{ request()->is('admin/roles/*') ? 'nav-item active' : '' }}  {{ request()->is('admin/all-admins') ? 'nav-item active' : '' }}">
                <a class="nav-link " data-bs-target="#manage-nav" data-bs-toggle="collapse" href="javascripit:void(0);">
                    <i class="bi bi-people-fill "></i><span>Role and Permissions</span><i
                        class="bi bi-chevron-down ms-auto"></i> </a>
                <ul id="manage-nav"
                    class=" nav-content collapse {{ request()->is('admin/permissions') ? 'show' : '' }} {{ request()->is('admin/permissions*') ? 'show' : '' }}  {{ request()->is('admin/all-delivery-boys') ? 'show' : '' }}  {{ request()->is('admin/delivery-boy/*') ? 'show' : '' }} {{ request()->is('admin/role*') ? 'show' : '' }} {{ request()->is('admin/roles*') ? 'show' : '' }} {{ request()->is('admin/permissions/*') ? 'show' : '' }} {{ request()->is('admin/roles') ? 'show' : '' }} {{ request()->is('admin/all-admins') ? 'show' : '' }}"
                    data-bs-parent="#sidebar-nav">
                    @if ($user && $user->hasPermissionByRole('view_permission'))
                        <li> <a href="{{ route('permissions.index') }}"
                                class="{{ request()->is('admin/permissions') ? 'nav-link active' : '' }} {{ request()->is('admin/permissions*') ? 'nav-link active' : '' }} {{ request()->is('admin/permissions/*') ? 'nav-link active' : '' }}">
                                <i class=" bi bi-circle active "></i><span>All Permissions </span> </a></li>
                    @endif
                    @if ($user && $user->hasPermissionByRole('view_role'))
                        <li> <a href="{{ route('roles.index') }}"
                                class="{{ request()->is('admin/roles') ? 'nav-link active' : '' }} {{ request()->is('admin/role*') ? 'nav-link active' : '' }}  {{ request()->is('admin/roles/*') ? 'nav-link active' : '' }}">
                                <i class="bi bi-circle"></i><span>All Roles</span></a></li>
                    @endif
                    @if ($user && $user->hasPermissionByRole('view_admin'))
                        <li> <a href="{{ route('all-admins.index') }}"
                                class="{{ request()->is('admin/all-admins') ? 'nav-link active' : '' }}"> <i
                                    class=" bi bi-circle active "></i><span>All admins</span></a></li>
                    @endif

                </ul>
            </li>
        @endif

        @if ($user->hasPermissionByRole('view_admin') || $user->hasPermissionByRole('create_admin'))
            <li
                class="{{ request()->is('admin/add_admin') ? 'nav-item active' : '' }} {{ request()->is('admin/edit_admin/*') ? 'nav-item active' : '' }} ">
                <a class="nav-link " data-bs-target="#manageadmin-nav" data-bs-toggle="collapse"
                    href="javascripit:void(0);"> <i class="bi bi-people-fill "></i><span>Admin Managements</span><i
                        class="bi bi-chevron-down ms-auto"></i> </a>
                <ul id="manageadmin-nav"
                    class=" nav-content collapse {{ request()->is('admin/all/admins') ? 'show' : '' }} {{ request()->is('admin/edit_admin/*') ? 'show' : '' }}  {{ request()->is('admin/add_admin') ? 'show' : '' }}"
                    data-bs-parent="#sidebar-nav">
                    @if ($user && $user->hasPermissionByRole('view_admin'))
                        <li> <a href="{{ route('alladmins') }}"
                                class="{{ request()->is('admin/all/admins*') ? 'nav-link active' : '' }}"> <i
                                    class="bi bi-circle"></i><span>List Admin</span></a></li>
                    @endif
                    @if ($user && $user->hasPermissionByRole('create_admin'))
                        <li> <a href="{{ url('admin/add_admin') }}"
                                class="{{ request()->is('admin/add_admin*') ? 'nav-link active' : '' }}"> <i
                                    class=" bi bi-circle active "></i><span>Add Admins</span></a></li>
                    @endif
                </ul>
            </li>
        @endif


        @if ($user && $user->hasPermissionByRole('view_users'))
            <li
                class=" {{ request()->is('admin/users') ? 'nav-item active' : '' }} {{ request()->is('admin/edit_user*') ? 'nav-item active' : '' }}  ">
                <a class="nav-link" data-bs-target="#users-nav" data-bs-toggle="collapse" href="javascripit:void(0);">
                    <i class=" ri-group-2-fill"></i><span>Citizens</span><i class="bi bi-chevron-down ms-auto"></i> </a>
                <ul id="users-nav"
                    class="nav-content collapse   {{ request()->is('admin/edit_user*') ? 'show' : '' }}   {{ request()->is('admin/users') ? 'show' : '' }}  "
                    data-bs-parent="#sidebar-nav">
                    <li> <a href="{{ route('users') }}"
                            class="{{ request()->is('admin/users*') ? 'nav-link active' : '' }} {{ request()->is('admin/edit_user*') ? 'nav-link active' : '' }}">
                            <i class=" bi bi-circle active "></i><span>List of Citizens</span></a></li>
                </ul>
            </li>
        @endif
       @if ($user && $user->hasPermissionByRole('view_officer'))
            <li class=" {{ request()->is('admin/officer') ? 'nav-item active' : '' }}">
                <a class="nav-link" data-bs-target="#officer-nav" data-bs-toggle="collapse" href="javascripit:void(0);">
                    <i class=" ri-group-2-fill"></i><span>Officers</span><i class="bi bi-chevron-down ms-auto"></i> </a>
                <ul id="officer-nav" class="nav-content collapse {{ request()->is('admin/officer') ? 'show' : '' }}  "
                    data-bs-parent="#sidebar-nav">
                    <li> <a href="{{ route('officer.index') }}"
                            class="{{ request()->is('admin/officer*') ? 'nav-link active' : '' }} "> <i
                                class=" bi bi-circle active "></i><span>List of Officers</span></a></li>

                            </ul>
                        </li>
                    @endif

                    @if ($user && $user->hasPermissionByRole('view_report'))
                        <li class="{{ request()->is('admin/reports') ? 'nav-item active' : '' }}">
                            <a class="nav-link" data-bs-target="#reports-nav" data-bs-toggle="collapse"
                                href="javascripit:void(0);"> <i class="bi bi-book-fill"></i><span>Reports</span><i
                                    class="bi bi-chevron-down ms-auto"></i> </a>
                            <ul id="reports-nav"
                                class="nav-content collapse {{ request()->is('admin/reports') ? 'show' : '' }}  "
                                data-bs-parent="#sidebar-nav">
                                <li> <a href="{{ url('admin/reports') }}"
                                        class="{{ request()->is('admin/reports*') ? 'nav-link active' : '' }} "> <i
                                            class=" bi bi-circle active "></i><span>List of Reports</span></a></li>
                            </ul>
                        </li>
                    @endif


                    @if (
                        ($user && $user->hasPermissionByRole('view blog')) ||
                            $user->hasPermissionByRole('view blog category') ||
                            $user->hasPermissionByRole('view blog comment'))
                        <li
                            class=" {{ request()->is('admin/blog-categories') ? 'nav-item active' : '' }} {{ request()->is('admin/blog-comments') ? 'nav-item active' : '' }} {{ request()->is('admin/blog/category*') ? 'nav-item active' : '' }} {{ request()->is('admin/blogs*') ? 'nav-item active' : '' }} {{ request()->is('admin/blogs') ? 'nav-item active' : '' }}">
                            <a class="nav-link" data-bs-target="#blog-nav" data-bs-toggle="collapse"
                                href="javascripit:void(0);"> <i class=" ri-newspaper-fill"></i><span>News /
                                    Blogs</span><i class="bi bi-chevron-down ms-auto"></i> </a>
                            <ul id="blog-nav"
                                class="nav-content collapse {{ request()->is('admin/blog-categories') ? 'show' : '' }} {{ request()->is('admin/blog-comments') ? 'show' : '' }} {{ request()->is('admin/blog/category*') ? 'show' : '' }} {{ request()->is('admin/blogs*') ? 'show' : '' }} {{ request()->is('admin/blogs') ? 'show' : '' }}"
                                data-bs-parent="#sidebar-nav">
                                @if ($user->hasPermissionByRole('view blog'))
                                    <li> <a href="{{ route('blogs') }}"
                                            class="{{ request()->is('admin/blogs') ? 'nav-link active' : '' }} {{ request()->is('admin/blogs*') ? 'nav-link active' : '' }}">
                                            <i class=" bi bi-circle active "></i><span>List Blogs</span></a></li>
                                @endif
                                @if ($user->hasPermissionByRole('view blog category'))
                                    <li> <a href="{{ route('blog-categories') }}"
                                            class="{{ request()->is('admin/blog-categories') ? 'nav-link active' : '' }} {{ request()->is('admin/blog/category*') ? 'nav-link active' : '' }}">
                                            <i class=" bi bi-circle active "></i><span> Blog Categories</span></a></li>
                                @endif
                                @if ($user->hasPermissionByRole('view blog comment'))
                                    <li> <a href="{{ route('blog-comments') }}"
                                            class="{{ request()->is('admin/blog-comments') ? 'nav-link active' : '' }} {{ request()->is('admin/blog/category*') ? 'nav-link active' : '' }}">
                                            <i class=" bi bi-circle active "></i><span> Blog Comments</span></a></li>
                                @endif
                            </ul>
                        </li>
                    @endif




                    @if ($user && $user->hasPermissionByRole('view_banners'))
                        <li class=" {{ request()->is('admin/banners') ? 'nav-item active' : '' }}  ">
                            <a class="nav-link" data-bs-target="#banner-nav" data-bs-toggle="collapse"
                                href="javascripit:void(0);"> <i class=" ri-layout-top-2-line"></i><span>Banner
                                    Managements</span><i class="bi bi-chevron-down ms-auto"></i> </a>
                            <ul id="banner-nav"
                                class="nav-content collapse  {{ request()->is('admin/banners') ? 'show' : '' }}  "
                                data-bs-parent="#sidebar-nav">
                                <li> <a href="{{ route('banners') }}"
                                        class="{{ request()->is('admin/banners*') ? 'nav-link active' : '' }}"> <i
                                            class=" bi bi-circle active "></i><span>Slider Banners</span> </a></li>
                            </ul>
                        </li>
                    @endif

                    @if (($user && $user->hasPermissionByRole('send_newsletters')) || $user->hasPermissionByRole('view_newsletters'))
                        <li
                            class=" {{ request()->is('admin/newslettersubscribers') ? 'nav-item active' : '' }} {{ request()->is('admin/send-email-to-all') ? 'nav-item active' : '' }} ">
                            <a class="nav-link" data-bs-target="#news-nav" data-bs-toggle="collapse"
                                href="javascripit:void(0);"> <i class="bx bxs-file"></i><span>Newsletter
                                    Subscribers</span><i class="bi bi-chevron-down ms-auto"></i> </a>
                            <ul id="news-nav"
                                class="nav-content collapse  {{ request()->is('admin/newslettersubscribers') ? 'show' : '' }}  {{ request()->is('admin/send-email-to-all') ? 'show' : '' }}  "
                                data-bs-parent="#sidebar-nav">
                                @if ($user->hasPermissionByRole('view_newsletters'))
                                    <li> <a href="{{ route('newslettersubscribers') }}"
                                            class="{{ request()->is('admin/newslettersubscribers*') ? 'nav-link active' : '' }}">
                                            <i class=" bi bi-circle active "></i><span>All Newsletter
                                                Subscribers</span> </a></li>
                                @endif
                                @if ($user->hasPermissionByRole('send_newsletters'))
                                    <li> <a href="{{ route('send-email-to-all') }}"
                                            class="{{ request()->is('admin/send-email-to-all') ? 'nav-link active' : '' }}">
                                            <i class=" bi bi-circle active "></i><span>Sent Email To All</span> </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if (($user && $user->hasPermissionByRole('view_faq')) || $user->hasPermissionByRole('add_faq'))
                        <li class=" {{ request()->is('admin/faq') ? 'nav-item active' : '' }}  ">
                            <a class="nav-link" data-bs-target="#faq-nav" data-bs-toggle="collapse"
                                href="javascripit:void(0);"> <i class="bx bxs-file"></i><span>FAQ</span><i
                                    class="bi bi-chevron-down ms-auto"></i> </a>
                            <ul id="faq-nav"
                                class="nav-content collapse  {{ request()->is('admin/faq') ? 'show' : '' }}  {{ request()->is('admin/faq/add') ? 'show' : '' }}  {{ request()->is('admin/allfaq') ? 'show' : '' }}   "
                                data-bs-parent="#sidebar-nav">
                                @if ($user->hasPermissionByRole('view_faq'))
                                    <li> <a href="{{ route('allfaq') }}"
                                            class="{{ request()->is('admin/allfaq*') ? 'nav-link active' : '' }}"> <i
                                                class=" bi bi-circle active "></i><span> All FAQ</span> </a></li>
                                @endif
                                @if ($user->hasPermissionByRole('add_faq'))
                                    <li> <a href="{{ route('add_faq') }}"
                                            class="{{ request()->is('admin/faq/add*') ? 'nav-link active' : '' }}"> <i
                                                class=" bi bi-circle active "></i><span>Add FAQs</span> </a></li>
                                @endif
                            </ul>
                        </li>
                    @endif



                    <li class="nav-heading">Location Settings</li>


                    @if (
                        ($user && $user->hasPermissionByRole('view country')) ||
                            $user->hasPermissionByRole('view city') ||
                            $user->hasPermissionByRole('view state'))
                        <li
                            class="{{ request()->is('admin/sub-cities') ? 'nav-item active' : '' }}{{ request()->is('admin/streets') ? 'nav-item active' : '' }}{{ request()->is('admin/states') ? 'nav-item active' : '' }} {{ request()->is('admin/countries') ? 'nav-item active' : '' }} {{ request()->is('admin/country*') ? 'nav-item active' : '' }} {{ request()->is('admin/cities') ? 'nav-item active' : '' }} {{ request()->is('admin/city*') ? 'nav-item active' : '' }} {{ request()->is('admin/state*') ? 'nav-item active' : '' }}">
                            <a class="nav-link" data-bs-target="#location-nav" data-bs-toggle="collapse"
                                href="javascripit:void(0);"> <i class="ri ri-map-2-fill"></i><span>Location</span><i
                                    class="bi bi-chevron-down ms-auto"></i> </a>
                            <ul id="location-nav"
                                class="nav-content collapse {{ request()->is('admin/countries') ? 'show' : '' }}  {{ request()->is('admin/country*') ? 'show' : '' }} {{ request()->is('admin/state*') ? 'show' : '' }} {{ request()->is('admin/city*') ? 'show' : '' }} {{ request()->is('admin/cities') ? 'show' : '' }} {{ request()->is('admin/states') ? 'show' : '' }} {{ request()->is('admin/streets') ? 'show' : '' }} {{ request()->is('admin/sub-cities') ? 'show' : '' }} "
                                data-bs-parent="#sidebar-nav">
                                @if ($user->hasPermissionByRole('view country'))
                                    <li> <a href="{{ url('admin/countries') }}"
                                            class="{{ request()->is('admin/country*') ? 'nav-link active' : '' }} {{ request()->is('admin/countries') ? 'nav-link active' : '' }}">
                                            <i class=" bi bi-circle active "></i><span>Countries</span></a></li>
                                @endif
                                @if ($user->hasPermissionByRole('view state'))
                                    <li> <a href="{{ url('admin/states') }}"
                                            class="{{ request()->is('admin/state*') ? 'nav-link active' : '' }} {{ request()->is('admin/states') ? 'nav-link active' : '' }}">
                                            <i class=" bi bi-circle active "></i><span>States</span></a></li>
                                @endif
                                @if ($user->hasPermissionByRole('view city'))
                                    <li> <a href="{{ url('admin/cities') }}"
                                            class="{{ request()->is('admin/cities') ? 'nav-link active' : '' }} {{ request()->is('admin/city*') ? 'nav-link active' : '' }}">
                                            <i class=" bi bi-circle active "></i><span>Cities</span></a></li>
                                @endif
                                @if ($user->hasPermissionByRole('view city'))
                                    <li> <a href="{{ url('admin/sub-cities') }}"
                                            class="{{ request()->is('admin/sub-cities') ? 'nav-link active' : '' }} {{ request()->is('admin/sub-cities*') ? 'nav-link active' : '' }}">
                                            <i class=" bi bi-circle active "></i><span>Sub Cities</span></a></li>
                                @endif
                                @if ($user->hasPermissionByRole('view city'))
                                    <li> <a href="{{ url('admin/streets') }}"
                                            class="{{ request()->is('admin/streets') ? 'nav-link active' : '' }} {{ request()->is('admin/streets*') ? 'nav-link active' : '' }}">
                                            <i class=" bi bi-circle active "></i><span>Streets</span></a></li>
                                @endif

                            </ul>
                        </li>
                    @endif

                    @if ($user && $user->hasPermissionByRole('manage_appsetting'))
                        <li
                            class="{{ request()->is('admin/withdraw-settings') ? 'nav-item active' : '' }} {{ request()->is('admin/banks') ? 'nav-item active' : '' }} {{ request()->is('admin/tips') ? 'nav-item active' : '' }} {{ request()->is('admin/appsettings') ? 'nav-item active' : '' }} {{ request()->is('admin/tax-settings') ? 'nav-item active' : '' }} {{ request()->is('admin/currency*') ? 'nav-item active' : '' }}  {{ request()->is('admin/currencies') ? 'nav-item active' : '' }}  {{ request()->is('admin/invoice-setting*') ? 'nav-item active' : '' }} {{ request()->is('admin/email-template*') ? 'nav-item active' : '' }}  ">
                            <a class="nav-link" data-bs-target="#app-nav" data-bs-toggle="collapse"
                                href="javascripit:void(0);"> <i class=" ri-group-2-fill"></i><span>Website
                                    Settings</span><i class="bi bi-chevron-down ms-auto"></i> </a>
                            <ul id="app-nav"
                                class="nav-content collapse {{ request()->is('admin/withdraw-settings') ? 'show' : '' }} {{ request()->is('admin/banks') ? 'show' : '' }} {{ request()->is('admin/tips') ? 'show' : '' }} {{ request()->is('admin/currency*') ? 'show' : '' }} {{ request()->is('admin/tax-settings') ? 'show' : '' }} {{ request()->is('admin/currencies') ? 'show' : '' }}    {{ request()->is('admin/appsettings') ? 'show' : '' }} {{ request()->is('admin/invoice-setting*') ? 'show' : '' }}  {{ request()->is('admin/email-template*') ? 'show' : '' }}  "
                                data-bs-parent="#sidebar-nav">
                                @if ($user->hasPermissionByRole('manage_appsetting'))
                                    <li> <a href="{{ url('admin/appsettings') }}"
                                            class="{{ request()->is('admin/appsettings*') ? 'nav-link active' : '' }}">
                                            <i class=" bi bi-circle active "></i><span>General Settings</span></a></li>
                                @endif

                            </ul>
                        </li>
                    @endif

                    <li class="nav-heading">My Settings</li>
                    <li
                        class=" {{ request()->is('admin/update_admin_password') ? 'nav-item active' : '' }} {{ request()->is('admin/updateadmindetails') ? 'nav-item active' : '' }} ">
                        <a class="nav-link" data-bs-target="#settings-nav" data-bs-toggle="collapse"
                            href="javascripit:void(0);"> <i class="bi bi-person-bounding-box  "></i><span>My
                                Profile</span><i class="bi bi-chevron-down ms-auto"></i> </a>
                        <ul id="settings-nav"
                            class="nav-content collapse  {{ request()->is('admin/update_admin_password') ? 'show' : '' }}  {{ request()->is('admin/updateadmindetails') ? 'show' : '' }}"
                            data-bs-parent="#sidebar-nav">
                            <li> <a href="{{ route('update_admin_password') }}"
                                    class="{{ request()->is('admin/update_admin_password*') ? 'nav-link active' : '' }}">
                                    <i class=" bi bi-circle active "></i><span>Update Password</span> </a></li>
                            <li> <a href="{{ route('updateadmindetails') }}"
                                    class="{{ request()->is('admin/updateadmindetails*') ? 'nav-link active' : '' }}"> <i
                                        class="bi bi-circle"></i><span>Update Details</span></a></li>
                        </ul>
                    </li>
                </ul>
</aside>
