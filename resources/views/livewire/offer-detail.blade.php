<div>
    @include('livewire.modal.offer-modal')
    <div class="pagetitle bg-light shadow-sm">
        <nav>
            <ol class="breadcrumb p-3">
                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Offer detail</li>
            </ol>
        </nav>
    </div>
    <section class="section ">
        <div class="row">
            <div class="col-lg-5">
                <div class="card  shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title">Product Detail</h4>
                        <p class="card-text">Product Name :<strong>{{$offer->product->product_name}}</strong></p>
                        <p class="card-text">Product Category :<strong>{{$offer->product->category->name}}</strong></p>
                        <p class="card-text">Product Brand :<strong>{{$offer->product->brand->name}}</strong></p>
                        <p class="card-text">Product Code :<strong>{{$offer->product->product_code}}</strong></p>
                        <p class="card-text">Product Color :<strong>{{$offer->product->product_color}}</strong></p>
                        <p class="card-text">Product Discount :<strong>{{$offer->product->product_discount}}</strong></p>
                        <p class="card-text">Product Price :<strong>{{$offer->product->product_price}}</strong></p>
                        <p class="card-text">Product Tax :<strong>{{$offer->product->product_tax}}</strong></p>
                        <p class="card-text">Product Weight :<strong>{{$offer->product->product_weight}}</strong></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card  shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title">User Detail</h4>
                        <p class="card-text">User Name :<strong>{{$offer->user->name}}</strong></p>
                        <p class="card-text">User Email :<strong>{{$offer->user->email}}</strong></p>
                        <p class="card-text">Address :<strong>{{$offer->user->address}}</strong></p>
                        <p class="card-text">City :<strong>{{$offer->user->city}}</strong></p>
                        <p class="card-text">State :<strong>{{$offer->user->state}}</strong></p>
                        <p class="card-text">Country :<strong>{{$offer->user->country}}</strong></p>
                        <p class="card-text">Mobile :<strong> &nbsp;<a class="btn btn-primary shadow-none">
                        <svg id='Outgoing_Call_16' width='16' height='16' viewBox='0 0 16 16' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'>
                        <rect width='16' height='16' stroke='none' fill='#000000' opacity='0' />
                        <g transform="matrix(0.67 0 0 0.67 8 8)">
                            <path style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(255, 255, 255); fill-rule: nonzero; opacity: 1;" transform=" translate(-12, -12)" d="M 4 3 C 3.562 3 3 3.328 3 4 C 3 8.539 4.8407969 12.873203 7.9667969 16.033203 C 11.126797 19.159203 15.461 21 20 21 C 20.672 21 21 20.438 21 20 L 21 16.490234 C 21 15.945234 20.568437 15.501281 20.023438 15.488281 C 19.393438 15.473281 18.600609 15.435656 18.099609 15.347656 C 17.557609 15.251656 16.904312 15.066828 16.445312 14.923828 C 16.085313 14.811828 15.694734 14.909781 15.427734 15.175781 L 13.210938 17.380859 C 11.678937 16.573859 10.451109 15.632891 9.4121094 14.587891 C 8.3671094 13.548891 7.4261406 12.321063 6.6191406 10.789062 L 8.8242188 8.5722656 C 9.0902187 8.3052656 9.1881719 7.9127344 9.0761719 7.5527344 C 8.9341719 7.0947344 8.7473437 6.4423906 8.6523438 5.9003906 C 8.5633437 5.3993906 8.5277188 4.6065625 8.5117188 3.9765625 C 8.4987188 3.4315625 8.0547656 3 7.5097656 3 L 4 3 z M 17 3 L 17 6 L 12 6 L 12 8 L 17 8 L 17 11 L 21 7 L 17 3 z" stroke-linecap="round" />
                        </g>
                        </svg> &nbsp;
                        {{$offer->user->mobile}}</a></strong></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Offer Detail</h4>
                        <p class="card-text">Quantity :<strong>{{$offer->quantity}}</strong></p>
                        <p class="card-text">Offer Price :<strong>{{$offer->offer_price}}</strong></p>
                        <p class="card-text">Description :<strong>{{$offer->description}}</strong></p>
                        <p class="card-text">Status : <button class="btn btn-outline-primary shadow-none">{{$offer->status}}</button></p>

                        <div>
                            <button type="button"  data-bs-toggle="modal" data-bs-target="#SubscriptionModal" class="btn btn-primary">                                Update Offer Status</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
