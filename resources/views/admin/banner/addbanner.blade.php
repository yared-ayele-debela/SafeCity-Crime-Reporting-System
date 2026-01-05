@extends('admindashboard.maindashboard')
@section('dashboard')
@php
$user = Auth::guard('admin')->user();
@endphp
<div class="pagetitle bg-light">
    <nav>
       <ol class="breadcrumb p-3 ">
          <li class="breadcrumb-item font-weight-bold"><a href="{{ url('admin/dashboard') }}">Home</a></li>
          <li class="breadcrumb-item">create banner</li>
       </ol>
    </nav>
 </div>
 <section class="section col-md-12" >
   <div class="card" >
      <div class="card-body pt-3">
                     <ul class="nav nav-tabs pb-4 align-items-end card-header-tabs w-100">
                        @if ($user && $user->hasPermissionByRole('view_banners'))
                          <a class="btn btn-primary " href="{{ url('admin/banners')}}"><i class="fa fa-list mr-2"></i>All Banners</a>
                        @endif
                     </ul>
                     <form class="row g-1" action="{{ route('banners') }}" method="POST" enctype="multipart/form-data" >
                        @csrf
                        <div class="col-md-6">
                           <label for="type">Banner Type</label>
                           <select class="form-control " name="type" id="type">
                              <option value="">Select</option>
                              <option @if(!empty($banner['type'])&&$banner['type']=="Slider")
                                 selected="" @endif value="Slider">Slider</option>
                              <option  @if(!empty($banner['type'])&&$banner['type']=="Fix")
                              selected="" @endif  value="Fix">Fix</option>
                           </select>
                           @error('type')
                              <small class=" text-danger">{{ $message }}</small>
                           @enderror
                       </div>
                        <div class="col-md-6">
                           <label for="link" class="form-label">Banner Link</label>
                            <input type="text" class="form-control" name="link">
                            @error('link')
                            <small class=" text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="title" class="form-label">Banner Title</label>
                             <input type="text" class="form-control" name="title">
                             @error('title')
                             <small class=" text-danger">{{ $message }}</small>
                             @enderror
                         </div>

                         <div class="col-md-6">
                            <label for="alt" class="form-label">Banner Alternate Text</label>
                             <input type="alt" class="form-control" name="alt">
                             @error('alt')
                             <small class=" text-danger">{{ $message }}</small>
                             @enderror
                         </div>
                         <div class="col-md-6">

                            <label for="image" class="form-label">Banner Image</label>
                             <input type="file" class="form-control" name="image">
                             <small class="text-sm text-danger ">Recomended size for silder (683px x 407px)</small><br>
                             <small class="text-sm text-danger">Recomended size for fixed(1280px x 250px)</small>
                        
                             @error('image')
                             <small class=" text-danger">{{ $message }}</small>
                             @enderror
                         </div>
                     <div class="form-group pt-3 ">
                     <input type="submit" class=" btn btn-primary pt-2 pb-2 shadow" value="Save Banner">
                     </div>
          </form>
         </div>
        </div>
      </div>
      </div>
 </section>
@endsection
