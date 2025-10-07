@extends('layouts.app')
@section('title', 'Payments')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="d-flex justify-content-between align-items-center m-3">
                    <h5 class="mb-0">Name: {{ $patient->name }} | Mobile No: {{ $patient->mobile1 }}</h5>
                    <a href="{{ route('patient.index') }}" class="btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
                    </a>
                </div>

                @include('common.alert')
                @include('patient.show', ['id' => $patient->id])

                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sr. No</th>
                                    <th>Invoice No</th>
                                    <th>Payment Date</th>
                                    <th>Amount</th>
                                    <th>Mode</th>
                                    <th>Comments</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $key => $payment)
                                    <tr>
                                        <td>{{ $payments->firstItem() + $key }}</td>
                                        <td>{{ $payment->order->invoice_no }}</td>
                                        <td>{{ date('d-m-Y', strtotime($payment->payment_date)) }}</td>
                                        <td>{{ number_format($payment->amount, 2) }}</td>
                                        <td>{{ $payment->mode }}</td>
                                        <td>{{ $payment->comments }}</td>
                                        <td>
                                            <a href="{{ route('payments.edit', $payment->id) }}"
                                                class="btn btn-primary btn-sm">Edit</a>
                                            <button class="btn btn-sm btn-primary delete-payment"
                                                data-id="{{ $payment->id }}" data-bs-toggle="modal"
                                                data-bs-target="#deleteRecordModal">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $payments->links('pagination::bootstrap-4') }}
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
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this payment?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn btn-primary" id="confirmDelete">Yes, Delete It!</button>
                        <button type="button" class="btn w-sm btn-primary" data-bs-dismiss="modal">Close</button>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="payment_id" id="deleteid" value="">
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
            $(".delete-payment").on("click", function() {
                let id = $(this).data("id");
                $("#deleteid").val(id);
            });

            // Confirm Delete Button Click
            $("#confirmDelete").on("click", function() {
                let id = $("#deleteid").val();
                let actionUrl = "{{ route('payments.destroy', ':id') }}".replace(':id', id);
                $("#deleteForm").attr("action", actionUrl);
                $("#deleteForm").submit();
            });
        });
    </script>
@endsection
