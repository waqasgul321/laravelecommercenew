@extends('layouts.admin')

@section('content')

<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>All Products</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="admin.index">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">All Products</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <form class="form-search">
                        <fieldset class="name">
                            <input type="text" placeholder="Search here..." class="" name="name"
                                tabindex="2" value="" aria-required="true" required="">
                        </fieldset>
                        <div class="button-submit">
                            <button class="" type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>
                <a class="tf-button style-1 w208" href="{{route('admin.product.add')}}"><i
                        class="icon-plus"></i>Add new</a>
            </div>
            <div class="table-responsive">
                @if(Session::has('status'))
                <p class="alert alert-success">{{ Session::get('status') }}</p>
                @endif
                <table class="table table-striped table-bordered text-center align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Sale Price</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Featured</th>
                            <th>Stock</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td class="text-start">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('uploads/products/thumbnails/' . $product->image) }}"
                                        alt="{{ $product->name }}" class="rounded-circle" style="width: 50px; height: 50px; margin-right: 10px;">
                                    <div>
                                        <span class="fw-bold">{{ $product->name }}</span>
                                        <div class="text-muted small">{{ $product->slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>${{ number_format($product->regular_price, 2) }}</td>
                            <td>${{ number_format($product->sale_price, 2) }}</td>
                            <td>{{ $product->SKU }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>{{ $product->brand->name }}</td>
                            <td>{{ $product->featured ? 'Yes' : 'No' }}</td>
                            <td>{{ ucfirst($product->stock_status) }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <a href="#" class="btn btn-sm btn-outline-primary" title="View">
                                            <i class="icon-eye"></i>
                                        </a>
                                        
                                <a href="{{route('admin.product.edit',['id'=>$product->id])}}">
                                    <div class="item edit">
                                        <i class="icon-edit-3"></i>
                                    </div>
                                </a>
                                <form action="{{route('admin.product.delete',['id'=>$product->id])}}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="item text-danger delete">
                                        <i class="icon-trash-2"></i>
                                    </div>
                                </form>
                           </div>
                                </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>


    @endsection

    @push('scripts')
<script>
    $(function() {
        $(".delete").on('click', function(e) {
            e.preventDefault();
            var selectedForm = $(this).closest('form');
            swal({
                title: "Are you sure?",
                text: "You want to delete this record?",
                type: "warning",
                buttons: ["No!", "Yes!"],
                confirmButtonColor: '#dc3545'
            }).then(function(result) {
                if (result) {
                    selectedForm.submit();
                }
            });
        });
    });
</script>
@endpush