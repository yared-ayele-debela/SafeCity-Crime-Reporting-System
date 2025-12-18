@extends('admindashboard.maindashboard')
@section('dashboard')
@php
$user = Auth::guard('admin')->user();
@endphp
<div class="pagetitle bg-light">
    <nav>
        <ol class="breadcrumb p-3 ">
            <li class="breadcrumb-item font-weight-bold"><a href="{{ url('admin/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">Add Blog </li>
        </ol>
    </nav>
</div>
<section class="section col-md-12">
    <div class="card border-0">
        <div class="card-body pt-3">
            <ul class="nav nav-tabs pb-4 align-items-end card-header-tabs w-100">
                @if ($user && $user->hasPermissionByRole('view blog'))
                    <a class="btn btn-primary" href="{{ route('blogs') }}"><i class="fa fa-list mr-2"></i>All Blogs</a>
                @endif
            </ul>
            <form class=" g-3" action="{{ route('store-blog') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-md-12 my-2">
                    <label for="title" class="form-label">Blog Title</label>
                    <input type="text" class="form-control" name="title">
                    @error('name')
                    <small class=" text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group my-2">
                  <label for="category_id">Category</label>
                  <select class="form-control" name="category_id" id="">
                    <option selected disabled value="">select one</option>
                    @foreach ($blog_category as $category)
                    <option value="{{ $category->id}}">{{ $category->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group my-2">
                  <label for="image">Preview Image</label>
                  <input type="file" class="form-control" name="image" id="image" placeholder="">
                  <small>  recommend size(870 x 450)</small>
                   @error('image')
                    <small class=" text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-12">
                    <label for="message" class="mb-2 mt-2">Message</label><br>
                    @error('message')
                    <small class=" text-danger">{{ $message }}</small>
                    @enderror
                    <textarea name="message" class="form-control" id="description" cols="30" rows="10"></textarea>
                </div>
                <div class="form-group pt-1 mt-2">
                    <input type="submit" class=" btn btn-primary pt-2 pb-2 shadow" value="Save">
                </div>
            </form>
        </div>
    </div>
    </div>
    </div>
</section>
@endsection

