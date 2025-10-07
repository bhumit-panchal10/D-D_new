@extends('layouts.app')
@section('title', 'Product Purchases')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                @include('common.alert')

                @if ($errors->any())
                    <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Product Purchases</h5>
                        <div class="d-flex align-items-center gap-2">
                            <form action="{{ route('product_purchase.index') }}" method="GET"
                                class="d-flex align-items-center gap-2">
                                <input type="text" name="search" class="form-control form-control-sm"
                                    placeholder="Search by Product or Vendor" value="{{ request()->search }}">
                                <button type="submit" class="btn btn-sm btn-primary">Search</button>

                                @if (request()->has('search'))
                                    <a href="{{ route('product_purchase.index') }}" class="btn btn-sm btn-primary">Clear</a>
                                @endif
                            </form>

                            <a href="{{ route('product_purchase.create') }}" class="btn btn-sm btn-primary">➕ Add
                                Purchase</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Product Name</th>
                                    <th>Vendor Name</th>
                                    <th>Quantity</th>
                                    <th>Rate</th>
                                    <th>Amount</th>
                                    <th>Received Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchases as $index => $purchase)
                                    <tr>
                                        <td>{{ ($purchases->currentPage() - 1) * $purchases->perPage() + $index + 1 }}</td>
                                        <td>{{ $purchase->product->product_name }}</td>
                                        <td>{{ $purchase->vendor->company_name }}</td>
                                        <td>{{ $purchase->quantity }}</td>
                                        <td>{{ $purchase->rate }}</td>
                                        <td>{{ $purchase->amount }}</td>
                                        <td>{{ date('d-m-Y', strtotime($purchase->received_date)) }}</td>
                                        <td>

                                            <button class="btn btn-sm btn-primary product_purchase_edit"
                                                data-id="{{ $purchase->id }}" data-product="{{ $purchase->product_id }}"
                                                data-vendor="{{ $purchase->vendor_id }}"
                                                data-quantity="{{ $purchase->quantity }}"
                                                data-rate="{{ $purchase->rate }}" data-amount="{{ $purchase->amount }}"
                                                data-received_date="{{ $purchase->received_date }}">Edit</button>

                                            <button class="btn btn-sm btn-primary delete-purchase"
                                                data-id="{{ $purchase->id }}" data-bs-toggle="modal"
                                                data-bs-target="#deleteRecordModal">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $purchases->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="editProductPurechaseModal" tabindex="-1" aria-labelledby="ProductPurechaseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="ProductPurechaseModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form method="post" action="{{ route('product_purchase.update') }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="productpurchase_id">

                        <div class="mb-3">
                            <label>Product Name</label>
                            <select class="form-control" name="product_id" id="product_id" required>
                                <option value="" disabled selected>Select Product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Vendor Name</label>
                            <select class="form-control" id="vendor_id" name="vendor_id" required>
                                <option value="" disabled selected>Select Vendor</option>
                                @foreach ($vendors as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->company_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Quantity</label>
                            <input type="text" class="form-control" placeholder="Enter QTY" name="quantity"
                                id="quantity" required minlength="1" maxlength="10">

                        </div>

                        <div class="mb-3">
                            <label>Rate</label>
                            <input type="text" class="form-control" placeholder="Enter Rate" name="rate"
                                id="rate" required minlength="1" maxlength="10">

                        </div>

                        <div class="mb-3">
                            <label>Amount</label>
                            <input type="text" class="form-control" placeholder="Enter Amount" id="amount"
                                name="amount" readonly>

                        </div>

                        <div class="mb-3">
                            <label>Received Date</label><span class="text-danger">*</span>
                            <input type="date" class="form-control" id="received_date" name="received_date"
                                max="{{ now()->toDateString() }}" required>

                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Delete Modal Start -->
    <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mt-2 text-center">
                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                            colors="primary:#f7b84b,secondary:#f06548" style="width : 100px; height : 100px">
                        </lord-icon>
                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                            <h4>Are you Sure?</h4>
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this product purchase?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn btn-primary" id="confirmDelete">Yes, Delete It!</button>
                        <button type="button" class="btn w-sm btn-primary" data-bs-dismiss="modal">Close</button>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="purchase_id" id="deleteid" value="">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Modal End -->

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".delete-purchase").on("click", function() {
                let id = $(this).data("id");
                $("#deleteid").val(id);

                // Capture current search query parameters
                let searchParams = new URLSearchParams(window.location.search);
                let actionUrl = "{{ route('product_purchase.destroy', ':id') }}".replace(':id', id);

                if (searchParams.toString()) {
                    actionUrl += '?' + searchParams.toString(); // Append existing search params
                }

                $("#deleteForm").attr("action", actionUrl);
            });

            // Confirm Delete Button Click
            $("#confirmDelete").on("click", function() {
                $("#deleteForm").submit();
            });

            $('#quantity').on('input', function() {
                let val = $(this).val();

                // Remove non-numeric characters
                val = val.replace(/[^0-9]/g, '');

                // Ensure the value does not exceed 10 characters
                if (val.length > 10) {
                    val = val.slice(0, 10);
                }

                $(this).val(val);
            });

            // Allow only numeric input with decimals in Rate field
            $('#rate').on('input', function() {
                let val = $(this).val();

                // Remove non-numeric characters except for decimals
                val = val.replace(/[^0-9.]/g, '');

                // Ensure only one decimal point is present
                if ((val.match(/\./g) || []).length > 1) {
                    val = val.substring(0, val.lastIndexOf('.'));
                }

                // Ensure it does not exceed 10 characters
                if (val.length > 10) {
                    val = val.slice(0, 10);
                }

                $(this).val(val);
            });

            // Calculate Amount
            $('#quantity, #rate').on('input', function() {
                const qty = parseFloat($('#quantity').val()) || 0;
                const rate = parseFloat($('#rate').val()) || 0;
                const amount = qty * rate;
                $('#amount').val(amount.toFixed(2));
            });

            $(".product_purchase_edit").on("click", function() {
                let productpurchaseid = $(this).data("id");
                let product = $(this).data("product");
                let vendor = $(this).data("vendor");
                let quantity = $(this).data("quantity");
                let rate = parseFloat($(this).data("rate")) || 0;
                let amount = parseFloat($(this).data("amount")) || 0;

                let received_date = $(this).data("received_date");


                $('#productpurchase_id').val(productpurchaseid);
                $('#product_id').val(product);
                $('#vendor_id').val(vendor);
                $('#quantity').val(quantity);
                $('#rate').val(rate);
                $('#amount').val(amount);
                $('#received_date').val(received_date);


                $('#editProductPurechaseModal').modal('show');
            });


        });
    </script>
@endsection
