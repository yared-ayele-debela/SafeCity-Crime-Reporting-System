@extends('admindashboard.maindashboard')
@section('dashboard')
@php
$user = Auth::guard('admin')->user();
@endphp
    <div class="pagetitle bg-light">
        <nav>
            <ol class="breadcrumb p-3 ">
                <li class="breadcrumb-item font-weight-bold"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item">Add Cms Page</li>
            </ol>
        </nav>
    </div>
    <section class="section col-md-12" >
        <div class="card" >
            <div class="card-body pt-3">
                <h5 class="card-title">Add Cms_Page</h5>
                <ul class="nav nav-tabs pb-4 align-items-end card-header-tabs w-100">
                    <li class="nav-item border-none">
                        <a class="nav-link active bg-light" href=""><i class=" fas fa-plus"></i>Add Cms_Page</a>
                    </li>
                    @if ($user && $user->hasPermissionByRole('view_cmspage'))
                    <li class="nav-item">
                        <a class="nav-link " href="{{ url('admin/cms-pages')}}"><i class="fa fa-list mr-2"></i>All Cms_Pages</a>
                    </li>
                    @endif
                </ul>
                <form class="g-3" action="{{ route('store_cms_page') }}" method="POST" enctype="multipart/form-data" >
                    @csrf
                    <div class="col-md-12">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" name="title">
                        @error('title')
                        <small class=" text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                          <textarea class="form-control" name="description" id="description" rows="5"></textarea>
                        </div>
                        @error('description')
                        <small class=" text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                     <div class="col-md-12">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control" name="meta_description" id="description" rows="5"></textarea>
                        @error('meta_description')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-12">
                      <p class=" text-danger"> write url like this (about-us) or aboutus don't use space between words</p>
                        <label for="url" class="form-label">Url</label>

                        <input type="text" class="form-control"  name="url" pattern="^[^\s]+$" title="Please enter text without spaces or use (-) between text" required>
                        @error('url')
                        <small class=" text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <label for="meta_title" class="form-label">Meta Title</label>
                        <input type="text" class="form-control" name="meta_title">
                        @error('meta_title')
                        <small class=" text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label for="meta_keywords" class="form-label">Meta keywords</label>
                        <input type="text" class="form-control" name="meta_keywords">
                        @error('meta_keywords')
                        <small class=" text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group pt-3 ">
                        <input type="submit" class=" btn btn-primary pt-2 pb-2 shadow" value="Submit">
                    </div>
                </form>
            </div>
        </div>
        </div>
        </div>
    </section>
@endsection
