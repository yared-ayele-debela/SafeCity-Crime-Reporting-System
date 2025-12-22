<div>
    @php
    $user = Auth::guard('admin')->user();
    @endphp
    <div class="pagetitle bg-light shadow-sm">
        <nav>
            <ol class="breadcrumb p-3">
                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">All sales users</li>
            </ol>
        </nav>
    </div>
    <section class="section" >
        <div class="card">
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
                @if ($user && $user->hasPermissionByRole('add_product'))
                <a href="{{ url('admin/products/add-product') }}" class="btn btn-primary"> Add Product</a>
                @endif
            </div>
            <div class="card-body mt-2">
                <table class="table mt-2" id="example">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Product Code</th>
                            <th scope="col">Product Price</th>
                            <th scope="col">Image</th>
                            <th scope="col">Category</th>
                            <th scope="col">Group</th>
                            <th scope="col">Featured</th>
                            <th scope="col">Added by</th>
                            <th scope="col">Status</th>
                            <th scope="col">Is Seasonal</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $k => $product)
                        <tr>
                            <td>{{ $product['id'] }}</td>
                            <td>{{ $product['product_name']}}
                                   @php
                                    $hasStock = $product['attributes'];
                                    @endphp
                                    @if(!$hasStock)
                                        <span class="bg-secondary position-absolute badge bg-danger m-2" style="z-index: 1100;">Out of Stock</span>
                                    @endif
                            </td>
                            <td>{{ $product['product_code']}}</td>
                            <td>{{ $product['product_price']}} ETB | Qty: {{ $product['quantity'] }}</td>
                            <td>
                                @if (!empty($product['product_image']))
                                <img src="{{ $product['product_image'] }}" alt="Product Image" style="max-width: 50px;">
                                @else
                                <img class=" rounded-sm border-0" src="{{ asset('/storage/products/no-image.png') }}" style="width: 50px; box-shadow:1px 1px 2px 2px rgb(218, 215, 215); border-radius:3px; border:none; height:50px" alt="">
                                @endif
                            </td>
                            <td>{{ $product['category']['name']}}</td>
                            <td>{{ $product['group']['name']}}</td>
                            <td>
                                <button wire:click="is_featured({{ $product['id'] }})" class="btn btn-sm text-white {{ $product['is_featured']=="Yes" ? 'btn-success' : 'btn-warning' }}">
                                    {{ $product['is_featured']=="Yes" ? 'Yes' : 'No' }}
                                </button>
                            </td>
                            <td>
                                @if($product['admin_type']=='vendor')
                                <a href="{{ url('admin/view_vendor_details/'.$product['admin_id']) }}" class=" underline">{{ ucfirst($product['admin_type']) }}</a>
                                @else
                                {{ ucfirst($product['admin_type']) }}
                                @endif
                            </td>

                            <td>
                                <i class="cont">
                                    <button id="popoverButton" wire:click="toggleStatus({{ $product['id'] }})" class="btn btn-sm {{ $product['status'] ? 'btn-success' : 'btn-danger' }}">
                                    {{ $product['status'] ? 'Active' : 'Inactive' }}
                                </button>
                                <div id="popover" class="popover">
                                    <div class="popover-content">
                                        <p><strong>Comment</strong>: {{ $product['status_comment'] }}</p>
                                    </div>
                                </div>
                                </i>
                            </td>
                          

                            <td>
                                <div class="d-flex">
                                @if ($user && $user->hasPermissionByRole('view seasonal'))
                                 @if ($user && $user->hasPermissionByRole('add seasonal'))
                                    <button wire:click="is_seasonal({{ $product['id'] }})" class="btn btn-sm {{ $product['is_seasonal'] ? 'btn-success' : 'btn-danger' }}">
                                        {{ $product['is_seasonal'] ? 'Seasonal' : 'Not Seasonal' }}
                                    </button> &nbsp;
                                    @if($product['is_seasonal']=="1")
                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#exampleModal">+</button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Select Season</h5>
                                                    <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <h5>Select Season when product does'nt available</h5>
                                                    <form action="{{ route('add_season') }}" method="post">
                                                        @csrf
                                                        <div class="row">
                                                            <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                                                            <div class="col mb-2 gird gird-row">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        @foreach($months->chunk(ceil($months->count() / 2))[0] as $month)
                                                                        <div class="form-check">
                                                                            <input type="checkbox" name="season_end_months[]" value="{{ $month->id }}" class="form-check-input" {{ collect($product['months'])->pluck('id')->contains($month->id) ? 'checked' : '' }}>
                                                                            <label class="form-check-label">{{ $month->name }}</label>
                                                                        </div>
                                                                        @endforeach
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        @foreach($months->chunk(ceil($months->count() / 2))[1] as $month)
                                                                        <div class="form-check">
                                                                            <input type="checkbox" name="season_end_months[]" value="{{ $month->id }}" class="form-check-input" {{ collect($product['months'])->pluck('id')->contains($month->id) ? 'checked' : '' }}>
                                                                            <label class="form-check-label">{{ $month->name }}</label>
                                                                        </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @else
                                     <button class="btn btn-sm {{ $product['is_seasonal'] ? 'btn-success' : 'btn-danger' }}">
                                        {{ $product['is_seasonal'] ? 'Seasonal' : 'Not Seasonal' }}
                                    </button>
                                    @endif
                                @endif
                                </div>
                            </td>
                            <td>
                                @if ($user && $user->hasPermissionByRole('edit_product'))
                                <i class="cont">
                                    <a id="popoverButton" href="{{ url('admin/edit/product/'.$product['id']) }}" style="background-color:rgb(239, 239, 239) " class=" btn btn-sm"> <i class="ri-ball-pen-fill"></i></a>
                                    <div id="popover" class="popover">
                                        <div class="popover-content">
                                            <p>Update Produt</p>
                                        </div>
                                    </div>
                                </i>
                                @endif

                                @if ($user && $user->hasPermissionByRole('add_attribute'))
                                <i class="cont">
                                 <a id="popoverButton" href="{{ url('admin/products/add_attribute/'.$product['id']) }}" style="background-color:rgb(239, 239, 239) " class=" btn btn-sm"> <i class="bx bx-add-to-queue"></i></a>
                                <div id="popover" class="popover">
                                    <div class="popover-content">
                                        <p>Add Produt Size</p>
                                    </div>
                                </div>
                                </i>
                                @endif
                                @if ($user && $user->hasPermissionByRole('add_image_to_product'))
                                <i class="cont">
                                <a id="popoverButton" href="{{ url('admin/products/images/'.$product['id']) }}" style="background-color:rgb(239, 239, 239) " class=" btn btn-sm mt-1"> <i class="ri-camera-fill "></i></a>
                                <div id="popover" class="popover">
                                    <div class="popover-content">
                                        <p>Add Produt Image</p>
                                    </div>
                                </div>
                                </i>
                                @endif
                                @if ($user && $user->hasPermissionByRole('delete_product'))
                                <i class="cont">
                                <button id="popoverButton" wire:click="deleteProduct({{ $product['id'] }})" data-toggle="modal" data-target="#deleteProductModal" class="btn btn-sm btn-danger">
                                    <i class=" ri-delete-bin-6-fill text-white"></i>
                                </button>
                                <div id="popover" class="popover">
                                    <div class="popover-content">
                                        <p>Delete Product</p>
                                    </div>
                                </div>
                                </i>
                                @endif
                                <i class="cont">
                                <button id="popoverButton"  data-bs-toggle="modal" data-bs-target="#addDiscountModal" wire:click="create_discount_package({{ $product['id'] }})" class="btn btn-sm btn-secondary cont">
                                    <i class="ri-price-tag-line"></i>
                                </button>
                                <div id="popover" class="popover">
                                    <div class="popover-content">
                                        <p>Add discount package</p>
                                    </div>
                                </div>
                                </i>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if ($isModalOpen)
        <div class="modal fade show d-block" style="background-color: rgba(0, 0, 0, 0.5);" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Status</h5>
                        <button type="button" class="close btn" wire:click="closeModal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="updateStatus">
                            <div class="form-group mb-2">
                                <label for="comment">Comment:</label>
                                <textarea id="comment" class="form-control" wire:model="status_comment" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
    </section>

</div>
