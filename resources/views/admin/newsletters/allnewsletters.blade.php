@extends('admindashboard.maindashboard')
@section('dashboard')
@php
$user = Auth::guard('admin')->user();
@endphp
    <div class="pagetitle bg-light">
        <nav>
            <ol class="breadcrumb p-3">
                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Newsletters Subscribers</li>
                <li class="breadcrumb-item active">All Newsletters Subscribers</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">

                    <div class="card-body">

                        <ul class="nav nav-tabs pb-4 align-items-end card-header-tabs w-100">
                            <li class="nav-item">
                                <a class="nav-link active" href="javascript:void(0);"><i class="fa fa-list mr-2"></i>All Newsletters Subscribers</a>
                            </li>
                        </ul>
                        <table id="example"  class="table datatable">
                            <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Email</th>
                                <th scope="col">Status</th>
                                <th scope="col">Subscried on</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($allnewsletter as $k => $all)

                                <tr>

                                    <td>{{ $all['id'] }}</td>
                                    <td>{{ $all['email']}}</td>

                                    <td>
                                        @if ($user && $user->hasPermissionByRole('edit_newsletter'))
                                            @if($all['status']==1)
                                                <a href="{{ url('admin/newslettersubscribers/inactive/'.$all['id']) }}"><span style="border-radius: 0.2rem;padding-left:3px;padding-right:3px"  class="btn btn-outline-success btn-sm">Active</span></a>
                                            @elseif($all['status']==0)
                                                <a href="{{ url('admin/newslettersubscribers/active/'.$all['id']) }}"><span style="border-radius: 0.2rem;padding-left:3px;padding-right:3px"  class="btn btn-outline-danger btn-sm">InActive</span></a>
                                            @endif
                                        @endif
                                    </td>

                                    <td>
                                        {{date('d-m-Y h:i:s',strtotime($all['created_at']))}}
                                    </td>

                                    <td>
                                        @if ($user && $user->hasPermissionByRole('delete_newsletter'))
                                        <a href="{{ url('admin/newslettersubscribers/delete/'.$all['id']) }}" style="background-color:rgb(239, 239, 239) " onclick="return confirm('Are you sure,you want to delete this NewsLetters ?? ') " class="btn  btn-sm" ><i class=" ri-delete-bin-6-fill"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        <div class=" pagination-sm">
                            {{-- {{ $categories->links() }} --}}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
