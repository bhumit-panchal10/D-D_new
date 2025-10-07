@extends('layouts.app')
@section('title', 'Expense List')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                @include('common.alert')

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Expense List</h5>
                        <a href="{{ route('Expense.create') }}" class="btn btn-sm btn-primary">+ Add Expense
                        </a>
                    </div>

                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sr No.</th>
                                    <th>Item Name</th>
                                    <th>Amount</th>
                                    <th>Enter By</th>
                                    <th>Mode</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $key = 1; @endphp
                                @foreach ($Expenses as $Expense)
                                    <tr>
                                        <td>{{ $key++ }}</td>
                                        <td>{{ $Expense->item_name }}</td>
                                        <td>{{ $Expense->amount }}</td>
                                        <td>{{ $Expense->enter_by }}</td>
                                        <td>{{ $Expense->mode == 1 ? 'online' : 'cash' }}</td>


                                        <td>
                                            <a href="{{ route('Expense.edit', $Expense->expense_id) }}"
                                                class="btn btn-sm btn-primary edit-btn">Edit</a>


                                            <button class="btn btn-sm btn-primary delete-expenses"
                                                data-id="{{ $Expense->expense_id }}" data-bs-toggle="modal"
                                                data-bs-target="#deleteRecordModal">
                                                Delete
                                            </button>



                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $Expenses->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
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
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this maintenance register?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn btn-primary" id="confirmDelete">Yes, Delete It!</button>
                        <button type="button" class="btn w-sm btn-primary" data-bs-dismiss="modal">Close</button>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="expenses_id" id="deleteid" value="">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Modal End -->

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            // Numeric-only restriction
            $(".numeric-only").on("input", function() {
                this.value = this.value.replace(/[^0-9.]/g, ''); // Allow only numbers and dots
            });

            $(".delete-expenses").on("click", function() {
                let id = $(this).data("id");
                $("#deleteid").val(id);
            });

            // Confirm Delete Button Click
            $("#confirmDelete").on("click", function() {
                let id = $("#deleteid").val();
                let actionUrl = "{{ route('Expense.destroy', ':id') }}".replace(':id', id);
                $("#deleteForm").attr("action", actionUrl);
                $("#deleteForm").submit();
            });
        });
    </script>
@endsection
