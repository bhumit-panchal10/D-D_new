@extends('layouts.app')
@section('title', 'Edit Expense')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                @include('common.alert')

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Edit Expense</h5>
                        <a href="{{ route('Expense.index') }}" class="btn btn-sm btn-primary">Back</a>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('Expense.update', $Expenses->expense_id) }}" method="post">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="mb-3 col-md-4">
                                    <label>Item Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter Item Name"
                                        name="item_name" value="{{ $Expenses->item_name }}" maxlength="50" required>
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label>Amount <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter Amount" name="amount"
                                        value="{{ $Expenses->amount }}" maxlength="10" required>
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label>Enter By <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter By" name="enter_by"
                                        value="{{ $Expenses->enter_by }}" maxlength="50" required>
                                </div>

                            </div>

                            <div class="row">
                                <div class="mb-3 col-md-4">
                                    <label>Mode <span class="text-danger">*</span></label>
                                    <select name="mode" id="mode" class="form-control">
                                        <option value="1" {{ $Expenses->mode == 1 ? 'selected' : '' }}>online</option>
                                        <option value="2" {{ $Expenses->mode == 2 ? 'selected' : '' }}>cash</option>

                                    </select>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{ route('Expense.index') }}" class="btn btn-primary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $(".numeric-only").on("input", function() {
                this.value = this.value.replace(/[^0-9.]/g, ''); // Allow only numbers and dots
            });
            $("form").on("submit", function(e) {
                let quotationAmount = parseFloat($("input[name='quotation_amount']").val()) || 0;
                let paymentPaidAmount = parseFloat($("input[name='payment_paid_amount']").val()) || 0;

                if (paymentPaidAmount > quotationAmount) {
                    alert("Payment Paid Amount cannot be greater than Quotation Amount.");
                    e.preventDefault(); // Prevent form submission
                }
            });
        });
    </script>
@endsection
