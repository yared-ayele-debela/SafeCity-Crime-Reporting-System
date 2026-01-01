<div>
    @php
    $user = Auth::guard('admin')->user();
    @endphp
    <!-- Update Student Modal -->
    <div wire:ignore.self class="modal fade" id="addDiscountModal" tabindex="-1" aria-labelledby="addDiscountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" style="width: 1250px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDiscountModalLabel">Add Discount Package</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"  aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
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
                            @if(!$selectedDiscount)
                            <form wire:submit.prevent="save_discount_package">
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control" wire:model="discount_name" id="name" placeholder="Discount Name">
                                            @error('discount_name') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="min_product" class="form-label">Min Product</label>
                                            <input type="number" class="form-control" wire:model="min_product" min="0" id="min_product" placeholder="min product">
                                            @error('min_product') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="max_product" class="form-label">Max Product</label>
                                            <input type="number" class="form-control" wire:model="max_product" min="0" id="max_product" placeholder="max product">
                                            @error('max_product') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="discount_type" class="pt-1 mt-1">Discount Type</label>
                                            <select class="form-control" wire:model="discount_type" id="discount_type">
                                                <option value="Percentage">Percentage (%)</option>
                                                <option value="Discounted Price">Discounted Price</option>
                                            </select>
                                            @error('discount_type')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="amount" class="form-label">Amount</label>
                                            <input type="number" class="form-control" wire:model="amount" id="amount" placeholder="Amount">
                                            @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mb-3">Create</button>
                            </form>
                            @endif

                            @if($selectedDiscount)
                            <form wire:submit.prevent="update_discount_package">
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control" wire:model="discount_name" id="name" placeholder="Discount Name">
                                            @error('discount_name') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="min_product" class="form-label">Min Product</label>
                                            <input type="number" class="form-control" wire:model="min_product" id="min_product" placeholder="min product">
                                            @error('min_product') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="max_product" class="form-label">Max Product</label>
                                            <input type="number" class="form-control" wire:model="max_product" id="max_product" placeholder="max product">
                                            @error('max_product') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                              <label for="discount_type" class="pt-1 mt-1">Discount Type</label>
                                              <select class="form-control" wire:model="discount_type" id="discount_type">
                                                <option value="Percentage">Percentage</option>
                                                <option value="Discounted Price">Discounted Price</option>
                                              </select>
                                              @error('discount_type')
                                              <span class="text-danger">{{ $message }}</span>
                                              @enderror
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="amount" class="form-label">Amount</label>
                                            <input type="number" class="form-control" wire:model="amount" id="amount" placeholder="Amount">
                                            @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                                    <button type="submit" class="btn btn-primary mb-3">Update</button>
                                    <button  wire:click='add_new_discount_package' class="btn btn-primary mb-3">Add New</button>
                            </form>
                            @endif
                        </div>

                        <div class="col-lg-12 mt-3">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h5>
                                        List of discount packages
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <table id="example" class="table datatable">
                                        <thead>
                                            <tr>
                                                <th scope="col">ID</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Min</th>
                                                <th scope="col">Max</th>
                                                <th scope="col">Discount Type</th>
                                                <th scope="col">Amount</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($discounts as $discount)
                                            <tr>
                                                <td>{{ $discount['id'] }}</td>
                                                <td>{{ $discount['name'] }}</td>
                                                <td>{{ $discount['min_product'] }}</td>
                                                <td>{{ $discount['max_product'] }}</td>
                                                <td>{{ $discount['discount_type'] }}</td>
                                                <td>{{ $discount['amount'] }}</td>
                                                <td>
                                                    @if ($user && $user->hasPermissionByRole('edit_banners'))
                                                    <button wire:click="update_discount_package_status({{ $discount['id'] }})" class="btn btn-sm {{ $discount['status'] ? 'btn-success' : 'btn-danger' }}">
                                                        {{ $discount['status'] ? 'Active' : 'Inactive' }}
                                                    </button>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($user && $user->hasPermissionByRole('edit_banners'))
                                                    <button type="button"  wire:click="edit_discount_package({{$discount['id']}})" class="btn btn-primary btn-sm">
                                                        <i class="ri-ball-pen-fill"></i>
                                                    </button>
                                                    @endif
                                                    @if ($user && $user->hasPermissionByRole('delete_banners'))
                                                    <button type="button" wire:click="destroy_discount_package({{$discount['id']}})" class="btn btn-danger btn-sm">
                                                        <i class="ri-delete-bin-6-fill"></i>
                                                    </button>
                                                    @endif
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
                </div>
            </div>
        </div>
    </div>
</div>

