@extends('admindashboard.maindashboard')
@section('dashboard')
@php
$user = Auth::guard('admin')->user();
@endphp
<div class="pagetitle bg-light">
    <nav>
       <ol class="breadcrumb p-3 ">
          <li class="breadcrumb-item font-weight-bold"><a href="{{ url('admin/dashboard') }}">Home</a></li>
          <li class="breadcrumb-item">Update FAQ</li>
       </ol>
    </nav>
 </div>
 <section class="section col-md-12" >
   <div class="card" >
      <div class="card-body pt-3">
                     <h5 class="card-title">Edit FAQ</h5>
                     <ul class="nav nav-tabs pb-4 align-items-end card-header-tabs w-100">
                        <li class="nav-item border-none">
                           <a class="nav-link active bg-light" href="javascript:void(0);"><i class=" fas fa-plus"></i>Update FAQ</a>
                         </li>
                         @if ($user && $user->hasPermissionByRole('view_faq'))
                            <li class="nav-item">
                            <a class="nav-link " href="{{ url('admin/allfaq')}}"><i class="fa fa-list mr-2"></i>All FAQs</a>
                            </li>
                         @endif
                       </ul>
                     <form class=" g-3" action="{{ route('update_faq') }}" method="POST" enctype="multipart/form-data" >
                        @csrf
                        @method('PUT')
                        <div class="col-md-12">
                            <input type="hidden" name="faq_id" value="{{ $faq->id }}">
                            <label for="question" class="form-label">FAQ Question</label>
                             <input type="text" style="width: 100%" class="form-control" value="{{ $faq->question }}" name="question">
                             @error('question')
                             <small class=" text-danger">{{ $message }}</small>
                             @enderror
                         </div>
                        <div class="col-md-12">
                           <label for="answer" class="form-label">FAQ Answer</label>
                           <textarea name="answer" class="form-control" id="" cols="30" rows="10">{{ $faq->answer }}</textarea>
                            @error('answer')
                            <small class=" text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                     <div class="form-group pt-3 ">
                     <input type="submit" class=" btn btn-primary pt-2 pb-2 shadow" value="Update FAQ">
                     </div>
          </form>
         </div>
        </div>
      </div>
      </div>
 </section>
@endsection
