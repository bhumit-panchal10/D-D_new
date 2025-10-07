@extends('layouts.app')
@section('title', 'Patient Treatments')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="d-flex justify-content-between align-items-center m-3">
                    <h5 class="mb-0">Name: {{ $patient->name }} | Mobile No: {{ $patient->mobile1 }}</h5>
                    <a href="{{ route('patient.index') }}" class="btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
                    </a>
                </div>

                @include('common.alert')
                @include('patient.show', ['id' => $patient->id])

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Patient Treatments</h5>
                        <a href="{{ route('patient_treatments.create', $patient->id) }}" class="btn btn-sm btn-primary">+
                            Add Treatment</a>
                    </div>

                    <div class="tab-content text-muted">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Sr no.</th>
                                                <th>Treatment</th>
                                                {{-- <th>Doctor</th> --}}
                                                <th>Tooth Selected</th>
                                                <th>Qty</th>
                                                <th>Rate</th>
                                                <th>Amount</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($patientTreatments as $key => $treatment)
                                                <tr>
                                                    <td>{{ $patientTreatments->firstItem() + $key }}</td>
                                                    <td>{{ $treatment->treatment->treatment_name }}</td>
                                                    {{-- <td>{{ $treatment->doctor->doctor_name ?? '' }}</td> --}}
                                                    <td>{{ $treatment->tooth_selection }}</td>
                                                    <td>{{ $treatment->qty }}</td>
                                                    <td>{{ $treatment->rate }}</td>
                                                    <td>{{ $treatment->amount }}</td>
                                                    <td>
                                                        <a href="{{ route('document.multidocview', [$treatment->id]) }}"
                                                            class="btn btn-sm btn-primary" title="View Document">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <button class="btn btn-sm btn-primary upload-document-btn"
                                                            data-bs-toggle="modal" title="upload"
                                                            data-bs-target="#uploadDocumentModal"
                                                            onclick="getdatas(<?= $treatment->patient_id ?>,<?= $treatment->treatment_id ?>,<?= $treatment->id ?>)"
                                                            ;>
                                                            <i class="fas fa-upload"></i>
                                                        </button>

                                                        <button class="btn btn-sm btn-primary labwork-btn"
                                                            data-bs-toggle="modal" title="add"
                                                            data-bs-target="#labworkModal"
                                                            onclick="getid(<?= $treatment->patient_id ?>,<?= $treatment->treatment_id ?>,<?= $treatment->id ?>)"
                                                            ;>
                                                            Add Labwork
                                                        </button>

                                                        <button class="btn btn-sm btn-primary delete-treatment"
                                                            data-id="{{ $treatment->id }}" data-bs-toggle="modal"
                                                            data-bs-target="#deleteRecordModal">
                                                            Delete
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-center mt-3">
                                        {{ $patientTreatments->links('pagination::bootstrap-4') }}
                                    </div>
                                </div>
                            </div>
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
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this patient treatment?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn btn-primary" id="confirmDelete">Yes, Delete It!</button>
                        <button type="button" class="btn w-sm btn-primary" data-bs-dismiss="modal">Close</button>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="treatment_id" id="deleteid" value="">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Modal End -->



    <!-- Document Upload Modal -->
    <div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="documentUploadForm" action="{{ route('document.multipleDocstore', $patient->id ?? 0) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="patient_id" id="modal_patient_id">
                        <input type="hidden" name="treatment_id" id="modal_treatment_id">
                        <input type="hidden" name="patient_treatment_id" id="modal_patient_treatment_id">

                        <div class="mb-3">
                            <label>Document <span class="text-danger">*</span></label>
                            <input type="file" name="document[]" class="form-control"
                                accept="image/jpeg, image/png, application/pdf" multiple required>
                        </div>

                        <div class="mb-3">
                            <label>Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Comment</label>
                            <textarea name="comment" class="form-control"></textarea>
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

    <!-- Labwork Modal -->
    <div class="modal fade" id="labworkModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Labwork</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="labworkForm" action="{{ route('labworks.store') }}" method="POST">
                        @csrf

                        <input type="hidden" name="patient_id" id="work_patient_id">
                        <input type="hidden" name="treatment_id" id="work_treatment_id">
                        <input type="hidden" name="patient_treatment_id" id="work_patient_treatment_id">

                        <div class="mb-3">
                            <label>Lab <span class="text-danger">*</span></label>
                            <select name="lab_id" class="form-control" required>
                                @foreach ($labs->sortBy('lab_name') as $lab)
                                    <option value="{{ $lab->id }}">{{ $lab->lab_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Entry Date <span class="text-danger">*</span></label>
                            <input type="date" name="entry_date" id="entry_date" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Comment</label>
                            <textarea name="comment" class="form-control"></textarea>
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


@section('scripts')
    <script>
        $(document).ready(function() {
            // Delete Lab Modal
            $(".delete-treatment").on("click", function() {
                let id = $(this).data("id");
                $("#deleteid").val(id); // Set lab ID in hidden input
            });

            // Confirm Delete Button Click
            $("#confirmDelete").on("click", function() {
                let id = $("#deleteid").val();
                let actionUrl = "{{ route('patient_treatments.destroy', ':id') }}".replace(':id', id);
                $("#deleteForm").attr("action", actionUrl);
                $("#deleteForm").submit();
            });
        });
    </script>


    <script>
        function getdatas(patient_id, treatment_id, id) {
            $("#modal_patient_id").val(patient_id);
            $("#modal_treatment_id").val(treatment_id);
            $("#modal_patient_treatment_id").val(id);
        }
    </script>

    <script>
        function getid(patient_id, treatment_id, id) {
            $("#work_patient_id").val(patient_id);
            $("#work_treatment_id").val(treatment_id);
            $("#work_patient_treatment_id").val(id);
        }
    </script>

    <script>
        document.getElementById("entry_date").addEventListener("input", function() {
            let input = this.value;
            let parts = input.split("-");
            if (parts[0] && parts[0].length > 4) {
                parts[0] = parts[0].slice(0, 4); // Limit year to 4 digits
                this.value = parts.join("-");
            }
        });
    </script>
@endsection
