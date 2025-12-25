<div>
    @php
    $user = Auth::guard('admin')->user();
    @endphp
    @include('livewire.modal.shipping-method-modal')
    <div class="pagetitle bg-light shadow-sm">
        <nav>
            <ol class="breadcrumb p-3">
                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">All Shipping Methods</li>
            </ol>
        </nav>
    </div>
    <div class="col-lg-12 mt-3">
        <div class="card shadow-sm">
            <div class="card-header mt-2">
                @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show " role="alert">
                    {{ session('message') }}
                    <button type="button" class="close btn" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show " role="alert">
                    {{ session('error') }}
                    <button type="button" class="close btn" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                <button type="button" class="btn btn-primary float-start" data-bs-toggle="modal" data-bs-target="#SubscriptionModal">
                    Add Shipping Method
                </button>
            </div>
            <div class="card-body">
                <table id="example" class="table">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Descripton</th>
                            <th class="px-4 py-2">Base Cost</th>
                            <th class="px-4 py-2">Per KG rate</th>
                            {{-- <th class="px-4 py-2">Zones</th> --}}
                            <th class="px-4 py-2">Zones & Price</th>
                            <th class="px-4 py-2">Status</th>

                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ShippingMethods as $ShippingMethod)
                        <tr>
                            <td class="px-4 py-2">{{ $ShippingMethod->name }}</td>
                            <td class="px-4 py-2">{{ $ShippingMethod->description }}</td>
                            <td class="px-4 py-2">{{ $ShippingMethod->base_cost }}</td>
                            <td class="px-4 py-2">{{ $ShippingMethod->per_kg_rate }}</td>

                            <td>
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modelId{{ $ShippingMethod->id }}">
                                  View
                                </button>
                                <!-- Modal -->
                                <div class="modal fade" id="modelId{{ $ShippingMethod->id }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Shipping zone with price</h5>
                                                    <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                            </div>
                                            <div class="modal-body">

                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Zone</th>
                                                            <th>Price</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($ShippingMethod->prices as $price)
                                                            <tr>
                                                                <td>{{ $price->zone->name }}</td>
                                                                <td>${{ $price->price }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </td>
                            <td class="px-4 py-2">
                                {{-- @if ($user && $user->hasPermissionByRole('edit_subscription')) --}}
                                <button wire:click="update_status({{ $ShippingMethod['id'] }})" class="btn btn-sm {{ $ShippingMethod['status'] ? 'btn-success' : 'btn-danger' }}">
                                    {{ $ShippingMethod['status'] ? 'Active' : 'Inactive' }}
                                </button>
                                {{-- @endif --}}
                            </td>
                            <td class="px-4 py-2 d-flex">
                                {{-- @if ($user && $user->hasPermissionByRole('edit_subscription')) --}}
                                <button type="button"  data-bs-toggle="modal" data-bs-target="#updateSubscriptionModal" wire:click="edit({{$ShippingMethod['id']}})" class="btn btn-primary btn-sm">
                                    <i class="ri-ball-pen-fill"></i>
                                </button>
                                {{-- @endif --}}
                                &nbsp;
                                {{-- @if ($user && $user->hasPermissionByRole('delete_subscription')) --}}
                                <button type="button" data-bs-toggle="modal" data-bs-target="#deleteSubscriptionModal" wire:click="deleteShippingMethod({{$ShippingMethod['id']}})"  class="btn btn-danger btn-sm">
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
