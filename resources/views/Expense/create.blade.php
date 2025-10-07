@extends('layouts.app')
@section('title', 'Add Expense')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                @include('common.alert')

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Add Expense</h5>
                        <a href="{{ route('Expense.index') }}" class="btn btn-sm btn-primary">Back</a>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('Expense.store') }}" method="post">
                            @csrf

                            <div class="row">
                                <div class="mb-3 col-md-4">
                                    <label>Item Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter Item Name"
                                        name="item_name" required maxlength="30">
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label>Amount<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter Amount" name="amount"
                                        required maxlength="30">
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label>Enter By<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter By" name="enter_by"
                                        required maxlength="30">
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label>Mode<span class="text-danger">*</span></label>
                                    <select name="mode" id="mode" class="form-control">
                                        <option value="1">online</option>
                                        <option value="2">cash</option>

                                    </select>
                                </div>

                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="reset" class="btn btn-primary">Clear</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
