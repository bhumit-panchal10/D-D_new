@extends('layouts.app')

@section('title', 'Add Clinic')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')

                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Add Clinic</h4>
                            <div class="page-title-right">
                                <a href="{{ route('clinic.index') }}"
                                    class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="live-preview">
                                    <form onsubmit="return validateFile()" method="POST" action="{{ route('clinic.store') }}" id="regForm"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row gy-4">
                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Clinic Name<span style="color:red;">*</span>
                                                    <input type="text" class="form-control" maxlength="30" id=""
                                                        placeholder="Enter Name" name="name" autocomplete="off"
                                                        value="{{ old('name') }}" required>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Doctor Name<span style="color:red;">*</span>
                                                    <input type="text" class="form-control" maxlength="30" id=""
                                                        placeholder="Enter Doctor Name" name="doctor_name"
                                                        autocomplete="off" value="{{ old('doctor_name') }}" required>
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Phone<span style="color:red;">*</span>
                                                    <input type="text" class="form-control"
                                                        onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"
                                                        autocomplete="off" name="mobile_number"
                                                        value="{{ old('mobile_number') }}" minlength="10" maxlength="10"
                                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                        placeholder="Enter Phone" required>
                                                </div>
                                            </div>
                                            <!--end col-->
                                            
                                             <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Email
                                                    <input type="email" class="form-control" autocomplete="off"
                                                        name="email" value="{{ old('email') }}" minlength="10"
                                                        maxlength="30" placeholder="Enter Email">
                                                    @error('email')
                                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Address
                                                    <textarea name="address" id="address" class="form-control" rows="3"></textarea>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    State<span style="color:red;">*</span>
                                                    <input type="text" class="form-control" id=""
                                                        placeholder="Enter State" name="state" maxlength="30"
                                                        autocomplete="off" value="{{ old('state') }}" required>
                                                </div>
                                            </div>

                                            <!--end col-->
                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    City<span style="color:red;">*</span>
                                                    <input type="text" class="form-control" id=""
                                                        placeholder="Enter city" name="city" maxlength="30"
                                                        autocomplete="off" value="{{ old('city') }}" required>
                                                </div>
                                            </div>
                                          
                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Logo
                                                    <input type="file" class="form-control" id="logo" name="logo"
                                                        autocomplete="off" value="{{ old('logo') }}" id=""
                                                        placeholder="Enter Logo">
                                                </div>
                                            </div>

                                            <!--end col-->
                                        </div>
                                        <div class="card-footer mt-5" style="float : right;">
                                            <button type="submit"
                                                class="btn btn-primary btn-user float-right mb-3">Save</button>
                                            <button type="reset"
                                                class="btn btn-primary float-right mr-3 mb-3">Clear</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
    
      <script>
        function validateFile() {
            var allowedExtension = ['jpeg', 'jpg', 'png', 'webp', ''];
            var fileExtension = document.getElementById('logo').value.split('.').pop().toLowerCase();
            var isValidFile = false;

            for (var index in allowedExtension) {

                if (fileExtension === allowedExtension[index]) {
                    isValidFile = true;
                    break;
                }
            }
            if (!isValidFile) {
                alert('Allowed Extensions are : *.' + allowedExtension.join(', *.'));
            }
            return isValidFile;
        }
    </script>

@endsection
