@extends('layouts.app')
@section('title', 'Create Invoice')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center m-3">
            <h5 class="mb-0">Name: {{ $patient->name }} | Mobile No: {{ $patient->mobile }}</h5>
            <a href="{{ route('patient.index') }}" class="btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
                </a>
            </div>

            @include('common.alert')
            @include('patient.show', ['id' => $patient->id])

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">Create Invoice</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                        <!-- First Row: Invoice No, Date, Patient Name -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label>Invoice No</label>
                                <input type="text" name="invoice_no" class="form-control" value="{{ str_pad($invoice_no, 4, '0', STR_PAD_LEFT) }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label>Date</label>
                                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label>Patient Name</label>
                                <input type="text" class="form-control" value="{{ $patient->name }}" readonly>
                                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                            </div>
                        </div>

                        <!-- Second Row: Treatment Selection -->
                        <div class="row mb-3 align-items-end">
                            <div class="col-md-5">
                                <label>Select Treatment & Tooth</label>
                                <select id="patient_treatment_select" class="form-control select2" multiple>
                                    @foreach($patientTreatments as $treatment)
                                    @if($treatment->is_billed == 0)  {{-- Only show treatments that are NOT billed --}}
                                        <option value="{{ $treatment->id }}"
                                            data-treatment-name="{{ $treatment->treatment->treatment_name }}"
                                            data-tooth-selection="{{ $treatment->tooth_selection }}"
                                            data-rate="{{ $treatment->rate }}"
                                            data-qty="{{ $treatment->qty }}"
                                            data-amount="{{ $treatment->amount }}">
                                            {{ $treatment->treatment->treatment_name }} - (Tooth: {{ $treatment->tooth_selection }})
                                        </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-2 text-end">
                                <button type="button" id="addToInvoice" class="btn btn-primary w-100">Add to Invoice</button>
                            </div>
                        </div>

                        <!-- Treatment List View (Dynamic Table) -->
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered" id="invoiceTable">
                                <thead>
                                    <tr>
                                        <th>Treatment Name</th>
                                        <th>Tooth</th>
                                        <th>Rate</th>
                                        <th>Qty</th>
                                        <th>Amount</th>
                                        <th>Discount</th>
                                        <th>Net Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Dynamic Data Will Be Added Here -->
                                </tbody>
                                <tfoot class="table-light">
                                <tr>
                                    <th colspan="4" class="text-end">Total:</th>
                                    <th id="totalAmount">0.00</th>
                                    <th id="totalDiscount">0.00</th>
                                    <th id="totalNetAmount">0.00</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                            </table>
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ route('orders.index', $patient->id) }}" class="btn btn-primary">Cancel</a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    if (window.jQuery) {
        console.log("✅ jQuery is loaded! Version:", jQuery.fn.jquery);

        if (jQuery.fn.select2) {
            jQuery("#patient_treatment_select").select2({
                placeholder: "Select Treatment & Tooth",
                allowClear: true
            });
            console.log("✅ Select2 initialized successfully!");
        } else {
            console.error("❌ Select2 is not loaded!");
        }
    } else {
        console.error("❌ jQuery is not loaded!");
    }

    // Add to Invoice Button Functionality
    document.getElementById('addToInvoice').addEventListener('click', function() {
        let selectedOptions = document.querySelectorAll('#patient_treatment_select option:checked');
        let invoiceTable = document.querySelector('#invoiceTable tbody');

        selectedOptions.forEach(option => {
            let treatmentId = option.value;
            let treatmentName = option.dataset.treatmentName;
            let toothSelection = option.dataset.toothSelection;
            let rate = parseFloat(option.dataset.rate);
            let qty = parseInt(option.dataset.qty);
            let amount = parseFloat(option.dataset.amount);
            let discount = 0;
            let netAmount = amount - discount;

            // Check if the treatment is already added
            if (document.getElementById('row-' + treatmentId)) {
                alert("This treatment is already added to the invoice.");
                return;
            }

            // Append new row to table
            let row = `
                <tr id="row-${treatmentId}">
                    <td>
                        <input type="hidden" name="patient_treatment_id[]" value="${treatmentId}">
                        ${treatmentName}
                    </td>
                    <td>${toothSelection}</td>
                    <td><input type="text" name="rate[]" value="${rate}" class="form-control" readonly></td>
                    <td><input type="text" name="qty[]" value="${qty}" class="form-control" readonly></td>
                    <td><input type="text" name="amount[]" value="${amount}" class="form-control amount" readonly></td>
                    <td><input type="text" name="discount[]" value="${discount}" class="form-control discount-input" oninput="this.value = this.value.replace(/[^0-9.]/g, '')"></td>
                    <td><input type="text" name="net_amount[]" value="${netAmount}" class="form-control net-amount" readonly></td>
                    <td><button type="button" class="btn btn-primary btn-sm remove-row">X</button></td>
                </tr>
            `;

            invoiceTable.insertAdjacentHTML('beforeend', row);
        });

        // Reset Dropdown Selection
        jQuery("#patient_treatment_select").val(null).trigger("change");

        // Update Totals
        updateTotals();
    });

    // Submit Validation
    document.querySelector("form").addEventListener("submit", function(event) {
        let tableRows = document.querySelectorAll("#invoiceTable tbody tr");
        
        if (tableRows.length === 0) {
            event.preventDefault();
            alert("Please add at least one treatment to the invoice before submitting.");
        }
    });

    // Update Net Amount on Discount Change
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('discount-input')) {
            let row = e.target.closest('tr');
            let amount = parseFloat(row.querySelector('.amount').value) || 0;
            let discount = parseFloat(e.target.value) || 0;

            if (discount > amount) {
                alert("Discount cannot be greater than the amount!");
                e.target.value = amount;
                discount = amount;
            }

            let netAmountField = row.querySelector('.net-amount');
            netAmountField.value = (amount - discount).toFixed(2);

            updateTotals();
        }
    });

    // Remove Row from Table (Using Event Delegation)
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
            updateTotals();
        }
    });

    // Function to Calculate Totals
    function updateTotals() {
        let totalAmount = 0;
        let totalDiscount = 0;
        let totalNetAmount = 0;

        document.querySelectorAll('#invoiceTable tbody tr').forEach(row => {
            let amount = parseFloat(row.querySelector('.amount').value) || 0;
            let discount = parseFloat(row.querySelector('.discount-input').value) || 0;
            let netAmount = parseFloat(row.querySelector('.net-amount').value) || 0;

            totalAmount += amount;
            totalDiscount += discount;
            totalNetAmount += netAmount;
        });

        document.getElementById('totalAmount').innerText = totalAmount.toFixed(2);
        document.getElementById('totalDiscount').innerText = totalDiscount.toFixed(2);
        document.getElementById('totalNetAmount').innerText = totalNetAmount.toFixed(2);
    }
});
</script>

@endsection
