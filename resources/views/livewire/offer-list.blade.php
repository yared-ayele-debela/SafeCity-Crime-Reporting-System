<div>
    @php
    $user = Auth::guard('admin')->user();
    @endphp
    <div class="pagetitle bg-light shadow-sm">
        <nav>
            <ol class="breadcrumb p-3">
                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">All offer product requests</li>
            </ol>
        </nav>
    </div>
    <div class="col-lg-12 mt-3">
        <div class="card shadow-sm">
            <div class="card-header">
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
                <h4>Offers for Product ID: <strong> #{{ $productId }}</strong></h4>
            </div>
            <div class="card-body m-2">
                <table id="example" class="table mt-2">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2">#</th>
                            <th class="px-4 py-2">User Name</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Mobile</th>
                            <th class="px-4 py-2">Quantity</th>
                            <th class="px-4 py-2">Description</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($offers as $offer)
                        <tr>
                            <td class="px-4 py-2">{{ $offer->id }}</td>
                            <td class="px-4 py-2">{{ $offer->user->name }}</td>
                            <td class="px-4 py-2">{{ $offer->user->email }}</td>
                            <td class="px-4 py-2">{{ $offer->user->mobile }}</td>
                            <td class="px-4 py-2">{{ $offer->quantity }}</td>
                            <td class="px-4 py-2">{{ $offer->description }}</td>
                            <td class="px-4 py-2">
                                @if($offer->status=="approved")
                                 <button class="btn btn-success btn-sm">Approved</button>
                                @endif
                                @if($offer->status=="pending")
                                 <button class="btn btn-warning btn-sm">Pending</button>
                                @endif
                                @if($offer->status==="declined")
                                 <button class="btn btn-danger btn-sm">Declined</button>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                <i class="cont">
                                {{-- @if ($user && $user->hasPermissionByRole('edit_subscription')) --}}
                                <a id="popoverButton" wire:navigate href="{{ route('offer.detail', ['offerId' => $offer->id]) }}" class="btn btn-primary btn-sm">
                                    <i class="ri-eye-fill"></i>
                                </a>
                                <div id="popover" class="popover">
                                    <div class="popover-content">
                                        <p>View order details</p>
                                    </div>
                                </div>
                                </i>
                                {{-- @endif --}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

