@extends('admindashboard.maindashboard')
@section('dashboard')
@php
$user = Auth::guard('admin')->user();
@endphp
<div class="pagetitle bg-light">
   <nav>
      <ol class="breadcrumb p-3">
         <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
         <li class="breadcrumb-item">FAQ</li>
         <li class="breadcrumb-item active">All FAQ</li>
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
                    <a class="nav-link active" href="{{ url('admin/allfaq') }}"><i class="fa fa-list mr-2"></i>All FAQ</a>
                    </li>
                 @if ($user && $user->hasPermissionByRole('add_faq'))
                  <li class="nav-item border-none">
                   <a class="nav-link bg-light" href="{{ url('admin/faq/add') }}"><i class=" fas fa-plus"></i>Add FAQ</a>
                  </li>
                 @endif
               </ul>
               <table id="example"  class="table datatable">
                  <thead>
                     <tr>
                        <th scope="col">ID</th>
                        <td scope="col">FAQ Question</td>
                        <th scope="col">Answer</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                    @foreach ($allfaq as $k => $faq)

                     <tr>
                        <td>{{ $faq['id'] }}</td>
                        <td>{{ $faq['question'] }}</td>
                        <td>{{ $faq['answer'] }}</td>
                        <td>
                            @if ($user && $user->hasPermissionByRole('edit_faq'))
                           @if($faq['status']==1)
                                 <a href="{{ url('admin/faq/inactive/'.$faq['id']) }}"><span style="border-radius: 0.2rem;padding-left:3px;padding-right:3px"  class="btn btn-outline-success btn-sm">Active</span></a>
                           @elseif($faq['status']==0)
                                 <a href="{{ url('admin/faq/active/'.$faq['id']) }}"><span style="border-radius: 0.2rem;padding-left:3px;padding-right:3px"  class="btn btn-outline-danger btn-sm">Inactive</span></a>
                            @endif
                            @endif
                           </td>
                        <td>
                        @if ($user && $user->hasPermissionByRole('edit_faq'))
                         <a href="{{ url('admin/faq/edit/'.$faq['id']) }}" style="background-color: rgb(242, 248, 248)" class=" btn btn-sm"><i class="ri-ball-pen-fill"></i></a>
                         @endif
                         @if ($user && $user->hasPermissionByRole('delete_faq'))
                         <a href="{{ url('admin/faq/delete/'.$faq['id']) }}" style="background-color: rgb(204, 199, 199); border:none" onclick="return confirm('Are you sure,you want to delete this FAQ ?? ') " class="btn btn-sm" ><i class="ri-delete-bin-6-fill"></i></a>
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

