@extends('layouts.app')

@section('title', 'Edit Clinic')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Edit Clinic</h4>
                            <div class="page-title-right">
                                <a href="{{ route('clinic.index') }}"
                                    class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="live-preview">
                                    <form method="POST" action="{{ route('clinic.update') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('post')
                                        <div class="row gy-4">
                                            <input type="hidden" name="clinic_id" value="{{ $Clinic->clinic_id }}">
                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Name <span style="color:red;">*</span>
                                                    <input type="text" class="form-control" maxlength="30" name="name"
                                                        value="{{ old('name') ? old('name') : $Clinic->name }}" required>
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Doctor <span style="color:red;">*</span>
                                                    <input type="text" class="form-control" maxlength="30" name="doctor"
                                                        value="{{ old('doctor') ? old('doctor') : $Clinic->doctor }}"
                                                        required>
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Phone <span style="color:red;">*</span>
                                                    <input type="text" class="form-control"
                                                        onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"
                                                        name="mobile_no" minlength="10" maxlength="10"
                                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                        value="{{ old('mobile_no') ? old('mobile_no') : $Clinic->mobile_no }}"
                                                        required>
                                                     @error('mobile_no')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            
                                             <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Email
                                                    <input type="email" class="form-control" autocomplete="off"
                                                        name="email" value="{{ $Clinic->email }}" minlength="10"
                                                        maxlength="30" placeholder="Enter Email">
                                                    @error('email')
                                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Address
                                                    <textarea name="address" id="address" class="form-control" rows="3">{{ $Clinic->address }}</textarea>
                                                </div>
                                            </div>
                                            <!--end col-->
                                           
                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    State <span style="color:red;">*</span>
                                                    <input type="text" maxlength="255" class="form-control"
                                                        name="state"
                                                        value="{{ old('state') ? old('state') : $Clinic->state }}" required>
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    City <span style="color:red;">*</span>
                                                    <input type="text" maxlength="255" class="form-control"
                                                        name="city"
                                                        value="{{ old('city') ? old('city') : $Clinic->city }}" required>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-lg-4 col-md-6">
                                                <div>

                                                    Logo

                                                    <input type="file" id="editmain_img" name="editmain_img"
                                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                                        autocomplete="off">
                                                </div>

                                                  <div id="viewimg">
                                                        @if ($Clinic->logo)
                                                            <img src="{{ asset('upload/logo/') . '/' . $Clinic->logo }}"
                                                                height="70" width="70" alt="">
                                                        @else
                                                            <img src="{{ asset('assets/images/noimage.png') }}" height="70"
                                                                width="70" alt="">
                                                        @endif
    
                                                    </div>
                                            </div>
                                            <input type="hidden" name="hiddenPhoto" class="form-control"
                                                value="{{ old('editmain_logo') ? old('editmain_logo') : $Clinic->logo }}"
                                                id="hiddenPhoto">
                                        </div>
                                        {{-- <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Status
                                                    <select class="form-control" name="status" required>
                                                        <option selected disabled value="">Select Status</option>
                                                        <option value="1"
                                                            {{ old('role_id') ? (old('role_id') == 1 ? 'selected' : '') : ($Clinic->status == 1 ? 'selected' : '') }}>
                                                            Active</option>
                                                        <option value="0"
                                                            {{ old('role_id') ? (old('role_id') == 0 ? 'selected' : '') : ($Clinic->status == 0 ? 'selected' : '') }}>
                                                            Inactive</option>
                                                    </select>
                                                </div>
                                            </div> --}}
                                        <!--end col-->
                                </div>
                                <div class="card-footer mt-5" style="float : right;">
                                    <button type="submit" class="btn btn-primary btn-user float-right mb-3">Update</button>
                                    <a class="btn btn-primary float-right mr-3 mb-3"
                                        href="{{ route('clinic.index') }}">Cancel</a>
                                </div>
                                <!--end row-->
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->

        </div>
    </div>
    </div>


@endsection


@section('scripts')


@endsection
