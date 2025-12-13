@extends('admindashboard.maindashboard')
@section('dashboard')
 <livewire:admin.admin-activities :adminId="$admin->id" />
@endsection
