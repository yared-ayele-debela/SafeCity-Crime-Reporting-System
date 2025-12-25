<div>
    @php
    $user = Auth::guard('admin')->user();
    @endphp
    @include('livewire.modal.shipping-zone')
    <div class="pagetitle bg-light shadow-sm">
        <nav>
            <ol class="breadcrumb p-3">
                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">All Shipping Zones</li>
            </ol>
        </nav>
    </div>
    <div class="col-lg-12 mt-3">
        <div class="card shadow-sm">
            <div class="card-header mt-3">
                @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
                @endif
                @if (session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <button type="button" class="btn btn-primary float-start" data-bs-toggle="modal" data-bs-target="#SubscriptionModal">
                    Add Shipping Zone
                </button>
            </div>
            <div class="card-body">
                <table id="example" class="table datatable">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Region</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ShippingZones as $ShippingZone)
                        <tr>
                            <td class="px-4 py-2">{{ $ShippingZone->name }}</td>
                            <td class="px-4 py-2">{{ $ShippingZone->states->name }}</td>
                            <td class="px-4 py-2">
                                {{-- @if ($user && $user->hasPermissionByRole('edit_subscription')) --}}
                                <button wire:click="update_status({{ $ShippingZone['id'] }})" class="btn btn-sm {{ $ShippingZone['status'] ? 'btn-success' : 'btn-danger' }}">
                                    {{ $ShippingZone['status'] ? 'Active' : 'Inactive' }}
                                </button>
                                {{-- @endif --}}
                            </td>
                            <td class="px-4 py-2">
                                {{-- @if ($user && $user->hasPermissionByRole('edit_subscription')) --}}
                                <button type="button"  data-bs-toggle="modal" data-bs-target="#updateSubscriptionModal" wire:click="edit({{$ShippingZone['id']}})" class="btn btn-primary btn-sm">
                                    <i class="ri-ball-pen-fill"></i>
                                </button>
                                {{-- @endif --}}
                                {{-- @if ($user && $user->hasPermissionByRole('delete_subscription')) --}}
                                <button type="button" data-bs-toggle="modal" data-bs-target="#deleteSubscriptionModal" wire:click="deleteShippingZone({{$ShippingZone['id']}})"  class="btn btn-danger btn-sm">
                                    <i class="ri-delete-bin-6-fill"></i>
                                </button>
                                {{-- @endif --}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class=" pagination-sm">
                </div>
            </div>
        </div>
    </div>
</div>
