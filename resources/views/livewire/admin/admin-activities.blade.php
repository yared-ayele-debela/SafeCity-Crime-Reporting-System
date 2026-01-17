<div>
    @include('livewire.modal.admin-activitie-modal')
    @php
    $user = Auth::guard('admin')->user();
    @endphp
    <div class="pagetitle bg-light shadow-sm">
       <nav>
          <ol class="breadcrumb p-3">
             <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
             <li class="breadcrumb-item active">all activities</li>
          </ol>
       </nav>
     </div>
     <section class="section">
       <div class="row">
          <div class="col-lg-12">
              <div class="card">
                <img class="card-img-top" src="holder.js/100x180/" alt="">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-md-4">
                            <form wire:submit.prevent="updateFilter" class="">
                                <div class="form-group">
                                    <label for="filter">Filter by</label>
                                    <select class="form-control" id="filter" wire:model="filter">
                                        <option value="all">All</option>
                                        <option value="today">Today</option>
                                        <option value="yesterday">Yesterday</option>
                                        <option value="this_week">This Week</option>
                                        <option value="this_month">This Month</option>
                                        <option value="custom">Custom</option>
                                    </select>
                                </div> &nbsp;
                                <div class="form-group" id="custom-date-range" style="display: {{ $filter == 'custom' ? 'block' : 'none' }};">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control" name="start_date" id="start_date" wire:model="start_date">
                                    <label for="end_date">End Date</label>
                                    <input type="date" class="form-control" name="end_date" id="end_date" wire:model="end_date">
                                </div><br>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </form>
                        </div>
                    </div>
                 </div>
              </div>
             <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <a class="btn btn-primary" href=""><i class="fa fa-list "></i>All Activities</a>
                    </div>
                </div>
                <div class="card-body p-3">
                   <table id="example"  class="table mt-2">
                      <thead>
                         <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Activity</th>
                            <th scope="col">Description</th>
                            <th scope="col">Date</th>
                            <th scope="col">Actions</th>
                         </tr>
                      </thead>
                      <tbody>
                        @foreach ($userActivity ?? [] as $k => $activity)
                         <tr>
                            <td>{{ $activity['id'] }}</td>
                            <td>{{ $activity['activity']}}</td>
                            <td>{{ $activity['description']}}</td>
                            <td>{{ $activity['created_at']}}</td>
                            <td>
                                @if ($user && $user->hasPermissionByRole('delete activity_log'))
                                <button type="button" data-bs-toggle="modal" data-bs-target="#deleteSubscriptionModal" wire:click="deleteActivityLog({{ $activity['id'] }})" style="background-color:rgb(239, 239, 239)" class="btn btn-sm" ><i class=" ri-delete-bin-6-fill"></i></button>

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
</div>
