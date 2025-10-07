<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClinicApiController;
use App\Http\Controllers\Api\TreatmentApiController;
use App\Http\Controllers\Api\MedicineApiController;
use App\Http\Controllers\Api\DosageApiController;
use App\Http\Controllers\Api\LabApiController;
use App\Http\Controllers\Api\ConsentFormApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\DoctorApiController;
use App\Http\Controllers\Api\PatientApiController;
use App\Http\Controllers\Api\PatientTreatmentApiController;
use App\Http\Controllers\Api\DocumentApiController;
use App\Http\Controllers\Api\LabWorkApiController;
use App\Http\Controllers\Api\PatientNotesApiController;
use App\Http\Controllers\Api\VendorApiController;
use Illuminate\Support\Facades\Artisan;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    return 'Cache is cleared';
});

Route::post('/Cliniclogin', [ClinicApiController::class, 'Cliniclogin']);
Route::post('/Treatment/Add', [TreatmentApiController::class, 'AddTreatment'])->name('Treatmentadd');
Route::post('/Treatment/list', [TreatmentApiController::class, 'Treatmentlist'])->name('Treatmentlist');
Route::post('/Treatment/Update', [TreatmentApiController::class, 'TreatmentUpdate'])->name('TreatmentUpdate');
Route::post('/Treatment/delete', [TreatmentApiController::class, 'Treatmentdelete'])->name('Treatmentdelete');

Route::post('/Medicine/Add', [MedicineApiController::class, 'AddMedicine'])->name('Medicineadd');
Route::post('/Medicine/list', [MedicineApiController::class, 'Medicinelist'])->name('Medicinelist');
Route::post('/Medicine/Update', [MedicineApiController::class, 'MedicineUpdate'])->name('MedicineUpdate');
Route::post('/Medicine/delete', [MedicineApiController::class, 'Medicinedelete'])->name('Medicinedelete');
Route::post('/dosage/list', [MedicineApiController::class, 'dosagelist'])->name('dosagelist');

Route::post('/Dosage/Add', [DosageApiController::class, 'AddDosage'])->name('Dosageadd');
Route::post('/Dosage/Update', [DosageApiController::class, 'DosageUpdate'])->name('DosageUpdate');
Route::post('/Dosage/delete', [DosageApiController::class, 'Dosagedelete'])->name('Dosagedelete');

Route::post('/Lab/Add', [LabApiController::class, 'AddLab'])->name('AddLab');
Route::post('/Lab/list', [LabApiController::class, 'Lablist'])->name('Lablist');
Route::post('/Lab/Update', [LabApiController::class, 'LabUpdate'])->name('LabUpdate');
Route::post('/Lab/delete', [LabApiController::class, 'Labdelete'])->name('Labdelete');

Route::post('/ConsetForm/Add', [ConsentFormApiController::class, 'AddConsentForm'])->name('AddConsentForm');
Route::post('/ConsetForm/list', [ConsentFormApiController::class, 'ConsentFormlist'])->name('ConsentFormlist');
Route::post('/ConsetForm/Update', [ConsentFormApiController::class, 'ConsentFormUpdate'])->name('ConsentFormUpdate');
Route::post('/ConsetForm/delete', [ConsentFormApiController::class, 'ConsentFormdelete'])->name('ConsentFormdelete');

Route::post('/User/Add', [UserApiController::class, 'AddUser'])->name('AddUser');
Route::post('/User/list', [UserApiController::class, 'Userlist'])->name('Userlist');
Route::post('/User/Update', [UserApiController::class, 'UserUpdate'])->name('UserUpdate');
Route::post('/User/delete', [UserApiController::class, 'Userdelete'])->name('Userdelete');
Route::post('/User/status', [UserApiController::class, 'status'])->name('status');
Route::post('/User/ChangePassword', [UserApiController::class, 'ChangePassword'])->name('ChangePassword');

Route::post('/Doctor/Add', [DoctorApiController::class, 'AddDoctor'])->name('AddDoctor');
Route::post('/Doctor/list', [DoctorApiController::class, 'Doctorlist'])->name('Doctorlist');
Route::post('/Doctor/Update', [DoctorApiController::class, 'DoctorUpdate'])->name('DoctorUpdate');
Route::post('/Doctor/delete', [DoctorApiController::class, 'Doctordelete'])->name('Doctordelete');

Route::post('/Patient/Add', [PatientApiController::class, 'AddPatient'])->name('AddPatient');
Route::post('/Patient/list', [PatientApiController::class, 'Patientlist'])->name('Patientlist');
Route::post('/Patient/Update', [PatientApiController::class, 'PatientUpdate'])->name('PatientUpdate');
Route::post('/Patient/delete', [PatientApiController::class, 'Patientdelete'])->name('Patientdelete');

Route::post('/Patient/Treatment/Add', [PatientTreatmentApiController::class, 'AddPatientTreatment'])->name('AddPatientTreatment');
Route::post('/Patient/Treatment/list', [PatientTreatmentApiController::class, 'PatientTreatmentlist'])->name('PatientTreatmentlist');
Route::post('/Patient/Treatment/Update', [PatientTreatmentApiController::class, 'PatientTreatmentUpdate'])->name('PatientTreatmentUpdate');
Route::post('/Patient/Treatment/delete', [PatientTreatmentApiController::class, 'PatientTreatmentdelete'])->name('PatientTreatmentdelete');

Route::post('/Document/Add', [DocumentApiController::class, 'AddDocument'])->name('AddDocument');
Route::post('/Document/list', [DocumentApiController::class, 'Documentlist'])->name('Documentlist');
Route::post('/Document/delete', [DocumentApiController::class, 'Documentdelete'])->name('Documentdelete');

Route::post('/LabWork/Add', [LabWorkApiController::class, 'AddLabWork'])->name('AddLabWork');
Route::post('/LabWork/list', [LabWorkApiController::class, 'LabWorklist'])->name('LabWorklist');
Route::post('/LabWork/delete', [LabWorkApiController::class, 'LabWorkdelete'])->name('LabWorkdelete');
Route::post('/LabWork/MarkAsCollected', [LabWorkApiController::class, 'MarkAsCollected'])->name('MarkAsCollected');
Route::post('/LabWork/MarkAsReceived', [LabWorkApiController::class, 'MarkAsReceived'])->name('MarkAsReceived');

Route::post('/Patient/Notes/Add', [PatientNotesApiController::class, 'AddPatientNotes'])->name('AddPatientNotes');
Route::post('/Patient/Notes/list', [PatientNotesApiController::class, 'PatientNoteslist'])->name('PatientNoteslist');
Route::post('/Patient/Notes/Update', [PatientNotesApiController::class, 'PatientNotesUpdate'])->name('PatientNotesUpdate');
Route::post('/Patient/Notes/delete', [PatientNotesApiController::class, 'PatientNotesdelete'])->name('PatientNotesdelete');

Route::post('/Vendor/Add', [VendorApiController::class, 'AddVendor'])->name('AddVendor');
Route::post('/Vendor/list', [VendorApiController::class, 'Vendorlist'])->name('Vendorlist');
Route::post('/Vendor/Update', [VendorApiController::class, 'VendorUpdate'])->name('VendorUpdate');
Route::post('/Vendor/delete', [VendorApiController::class, 'Vendordelete'])->name('Vendordelete');
