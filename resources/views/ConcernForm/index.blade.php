@extends('layouts.app')

@section('title', 'ConcerForm List')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')



                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="card-title mb-0">Add Consent Form</h5>
                            </div>

                            <div class="card-body">
                                <form action="{{ route('Consentform.store') }}" method="POST" id="createLabForm">
                                    @csrf

                                    <div class="mb-3">
                                        <label>Title<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="title" maxlength="50"
                                            placeholder="Enter Title" required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Text<span class="text-danger">*</span></label>
                                        <textarea name="text" class="form-control" name="contact_person" placeholder="Enter Text" required></textarea>

                                    </div>

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                        <button type="reset" class="btn btn-primary">Clear</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Consent Form List</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Consent Form Title</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($Concerforms as $key => $Concerform)
                                            <tr>
                                                <td>{{ ($Concerforms->currentPage() - 1) * $Concerforms->perPage() + $key + 1 }}
                                                </td>
                                                <td>{{ $Concerform->strConcernFormTitle }}</td>

                                                <td>

                                                    <a class="btn btn-sm btn-primary edit-concernform" title="Edit"
                                                        href="#" data-bs-toggle="modal" data-bs-target="#EditModal"
                                                        onclick="getEditData(<?= $Concerform->iConcernFormId ?>)">
                                                        Edit
                                                    </a>

                                                    <a class="btn btn-sm btn-primary" title="Delete" href="#"
                                                        onclick="confirmSingleDelete({{ $Concerform->iConcernFormId }})">
                                                        Delete
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $Concerforms->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Edit Lab Modal -->
    <div class="modal fade" id="EditModal" tabindex="-1" aria-labelledby="editLabModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Consent Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('Consentform.update') }}" id="editLabForm" method="POST">
                        @csrf


                        <input type="hidden" name="concernid" id="editconcernid" value="">

                        <div class="mb-3">
                            <label>Title<span class="text-danger">*</span></label>
                            <input type="text" name="edittitle" id="edittitle" class="form-control" value=""
                                maxlength="50" placeholder="Enter Title" required>
                        </div>

                        <div class="mb-3">
                            <label>Text<span class="text-danger">*</span></label>
                            <textarea class="form-control" id="edit_contact_person" name="text" placeholder="Enter Contact Person" required></textarea>


                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>




@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function getEditData(id) {

            var url = "{{ route('Consentform.edit', ':id') }}";
            url = url.replace(":id", id);
            if (id) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        id
                    },
                    success: function(data) {
                        var obj = JSON.parse(data);

                        $("#edittitle").val(obj.strConcernFormTitle);
                        $("#edit_contact_person").val(obj.strConcernFormText);
                        $("#editconcernid").val(obj.iConcernFormId);
                    },
                    error: function(xhr) {
                        alert('Failed to load data');
                    }
                });
            }
        }

        function confirmSingleDelete(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel",
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: true,
                showCloseButton: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    fetch(`{{ route('Consentform.delete', ['id' => '__id__']) }}`.replace('__id__', id), {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show deleted message and then redirect
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Record deleted successfully.',
                                    icon: 'success',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'btn btn-success'
                                    },
                                    buttonsStyling: true
                                }).then(() => {
                                    window.location.href = '{{ route('Consentform.index') }}';
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'There was an issue deleting the record.',
                                    icon: 'error',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'btn btn-danger'
                                    },
                                    buttonsStyling: true
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Delete error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: 'Something went wrong.',
                                icon: 'error',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                },
                                buttonsStyling: true
                            });
                        });
                }
            });
        }
    </script>
@endsection
