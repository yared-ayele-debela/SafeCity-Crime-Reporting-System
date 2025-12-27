<div>
    @php
    $user = Auth::guard('admin')->user();
    @endphp
    <div class="pagetitle bg-light shadow-sm">
        <nav>
            <ol class="breadcrumb p-3">
                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">tax settings</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        @if (session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                        @endif
                        @if ($user && $user->hasPermissionByRole('add tax'))
                        <button wire:click="create()" class="btn btn-primary">Create Tax</button>
                        @endif
                        @if($isModalOpen)
                        @include('livewire.create-tax')
                        @endif
                        @if($isDeleteModalOpen)
                            @include('livewire.delete-tax')
                        @endif
                    </div>
                    <div class="card-body mt-2">
                        <table class="table mt-2" id="example">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tax Name</th>
                                    <th>Percentage</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($taxes as $tax)
                                <tr>
                                    <td>{{ $tax->id }}</td>
                                    <td>{{ $tax->taxname }}</td>
                                    <td>{{ $tax->percentage }}</td>
                                    <td>
                                        @if ($user && $user->hasPermissionByRole('edit tax'))
                                        <button wire:click="update_status({{ $tax['id'] }})" class="btn btn-sm {{ $tax['status'] ? 'btn-success' : 'btn-danger' }}">
                                            {{ $tax['status'] ? 'Active' : 'Inactive' }}
                                        </button>
                                        @else
                                        <button class="btn btn-sm {{ $tax['status'] ? 'btn-success' : 'btn-danger' }}">
                                            {{ $tax['status'] ? 'Active' : 'Inactive' }}
                                        </button>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user && $user->hasPermissionByRole('edit tax'))
                                        <button wire:click="edit({{ $tax->id }})" class="btn btn-primary btn-sm"><i class=" ri-pencil-fill"></i></button>
                                        @endif
                                        @if ($user && $user->hasPermissionByRole('delete tax'))
                                        <button wire:click="confirmDelete({{ $tax->id }})" class="btn btn-danger btn-sm"> <i class="ri-delete-bin-6-fill"></i></button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
