<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\DosageController;
use App\Http\Controllers\MaintenanceRegisterController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPurchaseController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientAppointmentController;
use App\Http\Controllers\PatientTreatmentController;
use App\Http\Controllers\PatientNoteController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\LabworkController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\QuatationController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\EmployeeLabworkController;
use App\Http\Controllers\EmployeePatientAppointmentController;
use App\Http\Controllers\EmployeeProductPurchaseController;
use App\Http\Controllers\EmployeeMaintenanceRegisterController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\ConcerformController;
use App\Http\Controllers\CasenoController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\AppointmentController;


use App\Http\Controllers\PatientConcernFormController;
use App\Exports\PaymentsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Exports\PatientsExport;
use App\Exports\DuePaymentsExport;
use App\Exports\LabWorkExport;
use App\Exports\PayToDrExport;
use Illuminate\Support\Facades\Artisan;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    return 'Cache is cleared';
});

Route::get('/run-scheduled-appointment', function () {
    Artisan::call('auto:AppointmentReminderToDoctor');
    return 'Scheduled Appointment Reminder To Doctor has been executed.';
});

// login
Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes(['register' => false]);

Route::get('/ClinicLogin', [ClinicController::class, 'ClinicLogin'])->name('ClinicLogin');
Route::post('/Loginstore', [ClinicController::class, 'Loginstore'])->name('Loginstore');

Route::get('/ClinicUserLogin', [ClinicController::class, 'ClinicUserLogin'])->name('ClinicUserLogin');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Profile Routes
Route::prefix('profile')->name('profile.')->middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'getProfile'])->name('detail');
    Route::get('/edit', [HomeController::class, 'EditProfile'])->name('EditProfile');
    Route::post('/update', [HomeController::class, 'updateProfile'])->name('update');
    Route::post('/change-password', [HomeController::class, 'changePassword'])->name('change-password');
});

Route::get('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('clinic/logout', [ClinicController::class, 'cliniclogout'])->name('cliniclogout');

// Roles
Route::resource('roles', App\Http\Controllers\RolesController::class);

// Permissions
Route::resource('permissions', App\Http\Controllers\PermissionsController::class);

// Users
Route::middleware('auth')->prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/store', [UserController::class, 'store'])->name('store');
    Route::get('/edit/{id?}', [UserController::class, 'edit'])->name('edit');
    Route::post('/update/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/delete/{user}', [UserController::class, 'delete'])->name('destroy');
    Route::get('/update/status/{user_id}/{status}', [UserController::class, 'updateStatus'])->name('status');
    Route::post('/password-update/{Id?}', [UserController::class, 'passwordupdate'])->name('passwordupdate');
    Route::get('/import-users', [UserController::class, 'importUsers'])->name('import');
    Route::post('/upload-users', [UserController::class, 'uploadUsers'])->name('upload');
    Route::get('export/', [UserController::class, 'export'])->name('export');
});


Route::middleware('auth')->prefix('clinic')->name('clinic.')->group(function () {
    Route::get('/', [ClinicController::class, 'index'])->name('index');
    Route::get('/create', [ClinicController::class, 'create'])->name('create');
    Route::post('/store', [ClinicController::class, 'store'])->name('store');
    Route::get('/edit/{id?}', [ClinicController::class, 'edit'])->name('edit');
    Route::post('/update', [ClinicController::class, 'update'])->name('update');
    Route::delete('/delete', [ClinicController::class, 'delete'])->name('destroy');
    Route::get('/update/status/{clinic_id}/{status}', [ClinicController::class, 'updateStatus'])->name('status');
    Route::post('/password-update/{Id?}', [ClinicController::class, 'password_update'])->name('password_update');
    Route::post('/ordercreate/{Id?}', [ClinicController::class, 'ordercreate'])->name('ordercreate');
});

// Prescription Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('prescriptions/{patient_id}', [PrescriptionController::class, 'index'])->name('prescriptions.index');
    Route::get('prescriptions/create/{patient_id}', [PrescriptionController::class, 'create'])->name('prescriptions.create');
    Route::post('prescriptions/store', [PrescriptionController::class, 'store'])->name('prescriptions.store');
    Route::delete('prescriptions/{id}', [PrescriptionController::class, 'destroy'])->name('prescriptions.destroy');
    Route::get('/prescriptions/{id}/edit', [PrescriptionController::class, 'edit'])->name('prescriptions.edit');
    Route::put('/prescriptions/{id}', [PrescriptionController::class, 'update'])->name('prescriptions.update');
    Route::get('/prescriptions/{id}/pdf', [PrescriptionController::class, 'downloadPDF'])->name('prescriptions.pdf');
    Route::get('/get/dosages/{id?}', [PrescriptionController::class, 'get_dosages'])->name('prescriptions.get_dosages');
});
Route::get('/prescriptions/pdf/{guid}', [PrescriptionController::class, 'downloadPDFByGUID'])->name('prescriptions.guid_pdf');


// Doctor Routes
Route::prefix('admin')->name('doctors.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/doctors', [DoctorController::class, 'index'])->name('index');
    Route::post('/doctors', [DoctorController::class, 'store'])->name('store');
    Route::delete('/doctors/{id}', [DoctorController::class, 'destroy'])->name('destroy');
});

// Patient Appointment CRUD Routes
Route::prefix('admin/patient_appointment')->name('patient_appointment.')->group(function () {
    Route::get('/index/{id?}', [PatientAppointmentController::class, 'index'])->name('index'); // List Appointments
    Route::get('/create/{id?}', [PatientAppointmentController::class, 'create'])->name('create'); // Show Create Form
    Route::post('/store', [PatientAppointmentController::class, 'store'])->name('store'); // Store Data
    Route::get('/{appointment}/edit', [PatientAppointmentController::class, 'edit'])->name('edit'); // Show Edit Form
    Route::put('/{appointment}', [PatientAppointmentController::class, 'update'])->name('update'); // Update Data
    Route::delete('/{appointment}', [PatientAppointmentController::class, 'destroy'])->name('destroy'); // Delete Data
    // Today's Appointments, Confirmation, and Rescheduling
    Route::get('/today', [PatientAppointmentController::class, 'todayAppointments'])->name('today'); // Today's Appointments
    Route::put('/{appointment}/confirm', [PatientAppointmentController::class, 'confirm'])->name('confirm'); // Confirm Appointment
    Route::post('/reschedule/{appointment}', [PatientAppointmentController::class, 'reschedule'])->name('reschedule'); // Reschedule Appointment
    Route::get('/appointments/get', [PatientAppointmentController::class, 'getAppointments'])->name('getAppointments');
    Route::get('/appointments/fetch', [AppointmentController::class, 'getAppointments'])
        ->name('getAppointments');

    Route::post('/appointments/store', [AppointmentController::class, 'appointmentsstore'])
        ->name('appointmentsstore');
});

Route::prefix('admin/appointment')->name('appointment.')->group(function () {
    Route::get('/index', [AppointmentController::class, 'index'])->name('index'); // List Appointments
    Route::get('/create', [AppointmentController::class, 'create'])->name('create'); // Show Create Form
    Route::post('/store', [AppointmentController::class, 'store'])->name('store'); // Store Data
    Route::get('/{appointment}/edit', [AppointmentController::class, 'edit'])->name('edit'); // Show Edit Form
    Route::put('/{appointment}', [AppointmentController::class, 'update'])->name('update'); // Update Data
    Route::delete('/{appointment}', [AppointmentController::class, 'destroy'])->name('destroy'); // Delete Data
    // Today's Appointments, Confirmation, and Rescheduling
    Route::get('/today', [AppointmentController::class, 'todayAppointments'])->name('today'); // Today's Appointments
    Route::put('/{appointment}/confirm', [AppointmentController::class, 'confirm'])->name('confirm'); // Confirm Appointment
    Route::post('/reschedule/{appointment}', [AppointmentController::class, 'reschedule'])->name('reschedule'); // Reschedule Appointment
    Route::get('/appointments/get', [AppointmentController::class, 'getAppointments'])->name('getAppointments');
    Route::get('/patients/search', [AppointmentController::class, 'search'])->name('patients.search');
});

// Employee Appointment CRUD Routes
Route::prefix('employee/patient_appointment')->name('employee.')->group(function () {
    Route::get('/index/', [EmployeePatientAppointmentController::class, 'index'])->name('patient_appointment.index'); // List Appointments

    // Today's Appointments, Confirmation, and Rescheduling
    Route::put('/{appointment}/confirm', [EmployeePatientAppointmentController::class, 'confirm'])->name('patient_appointment.confirm'); // Confirm Appointment
    Route::post('/reschedule/{appointment}', [EmployeePatientAppointmentController::class, 'reschedule'])->name('patient_appointment.reschedule'); // Reschedule Appointment
});


// Document Routes
Route::prefix('admin/document')->name('document.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/index/{patient_id}', [DocumentController::class, 'index'])->name('index');
    Route::get('/multidocview/{patient_treatment_id}', [DocumentController::class, 'multidocview'])->name('multidocview');

    Route::post('/store/{patient_id}', [DocumentController::class, 'store'])->name('store');
    Route::post('/multipleDocstore/{patient_id}', [DocumentController::class, 'multipleDocstore'])->name('multipleDocstore');

    Route::delete('/delete/{patient_id}/{id}', [DocumentController::class, 'destroy'])->name('destroy');
});

//Payment Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('payments/{patient_id}', [PaymentsController::class, 'index'])->name('payments.index');
    Route::post('payments', [PaymentsController::class, 'store'])->name('payments.store');
    Route::get('payments/{payment}/edit', [PaymentsController::class, 'edit'])->name('payments.edit');
    Route::put('payments/{payment}', [PaymentsController::class, 'update'])->name('payments.update');
    Route::delete('payments/{id}', [PaymentsController::class, 'destroy'])->name('payments.destroy');
});

// Reports Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/reports/payments', [ReportController::class, 'report'])->name('payments.report');
    Route::get('/reports/patients', [ReportController::class, 'patient_report'])->name('patients.report');
    Route::get('/reports/due_payments', [ReportController::class, 'duePaymentsReport'])->name('reports.due_payments');
    Route::any('/reports/pay_to_dr', [ReportController::class, 'pay_to_dr_Report'])->name('reports.pay_to_dr');
    Route::any('/reports/lab_work', [ReportController::class, 'lab_work_Report'])->name('reports.lab_work_Report');
});

Route::prefix('admin/Consent')->name('Consentform.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [ConcerformController::class, 'index'])->name('index');
    Route::get('/create', [ConcerformController::class, 'create'])->name('create');
    Route::post('/store', [ConcerformController::class, 'store'])->name('store');
    Route::get('/edit/{id?}', [ConcerformController::class, 'edit'])->name('edit');
    Route::post('/update', [ConcerformController::class, 'update'])->name('update');
    Route::delete('/delete/{id?}', [ConcerformController::class, 'delete'])->name('delete');
});

Route::prefix('admin/Caseno')->name('Caseno.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [CasenoController::class, 'index'])->name('index');
    Route::get('/edit/{id}', [CasenoController::class, 'edit'])->name('edit');
    Route::post('/update', [CasenoController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [CasenoController::class, 'delete'])->name('delete');
    Route::post('/generate-case-number/{id}', [CasenoController::class, 'updateCaseNumber'])->name('generate.case.number');

    // Route::post('/update-counter', [CasenoController::class, 'updateCounter'])->name('Caseno.counter.update');
});

// Route::get('/concentform/{patient_id?}/{iConcernFormId?}/{PatientsConcernFormId?}', [ConcerformController::class, 'concentform'])->name('concentform');
Route::get('/concentform/{guid?}', [ConcerformController::class, 'concentform'])->name('concentform');
Route::get('/send-whatsapp/{gu_id}', [ConcerformController::class, 'sendWhatsappMessage'])->name('send.whatsapp.message');

Route::post('/signature/upload', [ConcerformController::class, 'upload'])->name('patient.upload');
Route::get('/thankyou', [ConcerformController::class, 'thankyou'])->name('thankyou');

// Report Export To Excel Routes

Route::get('/payments/export', function (Request $request) {
    $fromDate = $request->from_date ?? date('Y-m-01');
    $toDate = $request->to_date ?? date('Y-m-d');

    return Excel::download(new PaymentsExport($fromDate, $toDate), 'payment_report.xlsx');
})->name('payments.export');

Route::get('/pay_to_dr/export', function (Request $request) {
    $fromDate = $request->from_date;
    $toDate = $request->to_date;
    $doctorId = $request->doctor_id;

    return Excel::download(new PayToDrExport($fromDate, $toDate, $doctorId), 'pay_to_dr_report.xlsx');
})->name('pay_to_dr.export');

Route::get('/lab_works/export', function (Request $request) {
    $fromDate = $request->from_date;
    $toDate = $request->to_date;
    $labId = $request->lab_id;

    return Excel::download(new LabWorkExport($fromDate, $toDate, $labId), 'lab_work_report.xlsx');
})->name('lab_work_report.export');

Route::get('/patients/export', function () {
    $fromDate = request('from_date', date('Y-m-01'));
    $toDate = request('to_date', date('Y-m-d'));

    return Excel::download(new PatientsExport($fromDate, $toDate), 'patients_report.xlsx');
})->name('patients.export');

Route::get('/export-due-payments', function () {
    return Excel::download(new DuePaymentsExport, 'due_payments.xlsx');
})->name('export.due_payments');



// Order Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/orders/{patient_id}', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create/{patient_id}', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');
    Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::get('/orders/{id}/invoice', [OrderController::class, 'generateInvoice'])->name('orders.invoice');
});

// quotation Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/quotation/{patient_id}', [QuatationController::class, 'index'])->name('quotation.index');
    Route::get('/quotation/create/{patient_id}', [QuatationController::class, 'create'])->name('quotation.create');
    Route::post('/quotation/store', [QuatationController::class, 'store'])->name('quotation.store');
    Route::delete('/quotation/{id}', [QuatationController::class, 'destroy'])->name('quotation.destroy');
    Route::get('/quotation/{id}/qutationPdf', [QuatationController::class, 'generateInvoice'])->name('quotation.invoice');
});

// patient concerform Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/patientconcernform/{patient_id}', [PatientConcernFormController::class, 'index'])->name('patientconcernform.index');
    Route::get('/patientconcernform/create/{patient_id}', [PatientConcernFormController::class, 'create'])->name('patientconcernform.create');
    Route::post('/patientconcernform/store', [PatientConcernFormController::class, 'store'])->name('patientconcernform.store');
    Route::delete('/patientconcernform/{id}', [PatientConcernFormController::class, 'destroy'])->name('patientconcernform.destroy');
    Route::get('/patientconcernform/{id}/invoice', [PatientConcernFormController::class, 'generateInvoice'])->name('patientconcernform.invoice');
});


// Labwork Routes
Route::prefix('admin/labworks')->name('labworks.')->group(function () {
    Route::get('/index/{patient_id}', [LabworkController::class, 'index'])->name('index');
    Route::post('/store', [LabworkController::class, 'store'])->name('store');
    Route::delete('/delete/{id}', [LabworkController::class, 'destroy'])->name('destroy');
    Route::post('/collected/{id}', [LabworkController::class, 'markCollected'])->name('collected');
    Route::post('/received/{id}', [LabworkController::class, 'markReceived'])->name('received');
    Route::get('/full-list', [LabworkController::class, 'fullList'])->name('full_list'); // ✅ Added Route
});

// Employee Labwork Routes
Route::prefix('employee/labworks')->name('employee.')->group(function () {
    Route::get('/index', [EmployeeLabworkController::class, 'index'])->name('labworks.index');
    Route::post('/collected/{id}', [EmployeeLabworkController::class, 'markCollected'])->name('labworks.collected');
    Route::post('/received/{id}', [EmployeeLabworkController::class, 'markReceived'])->name('labworks.received');
});

// Patient Master Routes
Route::prefix('admin')->name('patient.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/patient', [PatientController::class, 'index'])->name('index'); // Correct
    Route::get('/patient/create/{id?}', [PatientController::class, 'create'])->name('create');
    Route::post('/patient', [PatientController::class, 'store'])->name('store');
    Route::get('/patient/{patient}/edit', [PatientController::class, 'edit'])->name('edit');
    Route::put('/patient/{patient}', [PatientController::class, 'update'])->name('update');
    Route::delete('/patient/{patient}', [PatientController::class, 'destroy'])->name('destroy');
    Route::get('/patient/{id}/show', [PatientController::class, 'show'])->name('show');
    Route::post('/patient/fetch-by-mobile', [PatientController::class, 'fetchByMobile'])->name('fetchByMobile');
    Route::get('/patient/autocomplete', [PatientController::class, 'autocomplete'])->name('autocomplete');
});

// Maintenance Register Routes
Route::prefix('admin/maintenance')->name('maintenance.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/index', [MaintenanceRegisterController::class, 'index'])->name('index');
    Route::get('/create', [MaintenanceRegisterController::class, 'create'])->name('create');
    Route::post('/store', [MaintenanceRegisterController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [MaintenanceRegisterController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [MaintenanceRegisterController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [MaintenanceRegisterController::class, 'destroy'])->name('destroy');
    Route::post('/mark-as-received', [MaintenanceRegisterController::class, 'markAsReceived'])->name('markAsReceived');
});

// Expense Routes
Route::prefix('admin/Expense')->name('Expense.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/index', [ExpenseController::class, 'index'])->name('index');
    Route::get('/create', [ExpenseController::class, 'create'])->name('create');
    Route::post('/store', [ExpenseController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [ExpenseController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [ExpenseController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [ExpenseController::class, 'destroy'])->name('destroy');
});

// Employee Maintenance Register Routes
Route::prefix('employee/maintenance')->name('employee.')->group(function () {
    Route::get('/index', [EmployeeMaintenanceRegisterController::class, 'index'])->name('maintenance.index');
    Route::get('/create', [EmployeeMaintenanceRegisterController::class, 'create'])->name('maintenance.create');
    Route::post('/store', [EmployeeMaintenanceRegisterController::class, 'store'])->name('maintenance.store');
    Route::get('/edit/{id}', [EmployeeMaintenanceRegisterController::class, 'edit'])->name('maintenance.edit');
    Route::put('/update/{id}', [EmployeeMaintenanceRegisterController::class, 'update'])->name('maintenance.update');
    Route::delete('/delete/{id}', [EmployeeMaintenanceRegisterController::class, 'destroy'])->name('maintenance.destroy');
    Route::post('/mark-as-received', [EmployeeMaintenanceRegisterController::class, 'markAsReceived'])->name('maintenance.markAsReceived');
});

// Patient Treatment Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/patient_treatments/{id}', [PatientTreatmentController::class, 'index'])->name('patient_treatments.index');
    Route::get('/patient_treatments/{id}/create', [PatientTreatmentController::class, 'create'])->name('patient_treatments.create');
    Route::post('/patient_treatments/{id}', [PatientTreatmentController::class, 'store'])->name('patient_treatments.store');
    Route::delete('/patient_treatments/{id}', [PatientTreatmentController::class, 'destroy'])->name('patient_treatments.destroy');
});

// vendor Routes
Route::prefix('admin/vendor')->name('vendor.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/index', [VendorController::class, 'index'])->name('index');
    Route::get('/create', [VendorController::class, 'create'])->name('create');
    Route::post('/store', [VendorController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [VendorController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [VendorController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [VendorController::class, 'destroy'])->name('destroy');
});

// Product Routes
Route::prefix('admin/product')->name('product.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/index', [ProductController::class, 'index'])->name('index');
    Route::post('/store', [ProductController::class, 'store'])->name('store');
    Route::put('/{id}/update', [ProductController::class, 'update'])->name('update');
    Route::delete('/{id}/delete', [ProductController::class, 'destroy'])->name('destroy');
});

// Product Purchase Routes
Route::prefix('admin/product-purchase')->name('product_purchase.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/index', [ProductPurchaseController::class, 'index'])->name('index');
    Route::get('/create', [ProductPurchaseController::class, 'create'])->name('create');
    Route::post('/store', [ProductPurchaseController::class, 'store'])->name('store');
    Route::put('/update', [ProductPurchaseController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [ProductPurchaseController::class, 'destroy'])->name('destroy');
    Route::get('/get-last-purchases/{productId}', [ProductPurchaseController::class, 'getLastPurchases'])->name('getLastPurchases');
});

// Employee Product Purchase Routes
Route::prefix('employee/product_purchase')->name('employee.')->group(function () {
    Route::get('/index', [EmployeeProductPurchaseController::class, 'index'])->name('product_purchase.index');
    Route::get('/create', [EmployeeProductPurchaseController::class, 'create'])->name('product_purchase.create');
    Route::post('/store', [EmployeeProductPurchaseController::class, 'store'])->name('product_purchase.store');
    Route::delete('/delete/{id}', [EmployeeProductPurchaseController::class, 'destroy'])->name('product_purchase.destroy');
    Route::get('/get-last-purchases/{productId?}', [EmployeeProductPurchaseController::class, 'getLastPurchases'])->name('product_purchase.getLastPurchases');
});

// Patient Notes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/patient_notes/{id}', [PatientNoteController::class, 'index'])->name('patient_notes.index');
    Route::post('/patient_notes/{id}', [PatientNoteController::class, 'store'])->name('patient_notes.store');
    Route::patch('/patient_notes/{patient_id}/{id}', [PatientNoteController::class, 'update'])->name('patient_notes.update');
    Route::delete('/patient_notes/{patient_id}/{id}', [PatientNoteController::class, 'destroy'])->name('patient_notes.destroy');
});

// Lab routes
Route::prefix('admin/lab')->name('lab.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/index', [LabController::class, 'index'])->name('index'); // List all labs
    Route::post('/store', [LabController::class, 'store'])->name('store'); // Store new lab
    Route::put('/update/{id}', [LabController::class, 'update'])->name('update'); // Update lab
    Route::delete('/delete/{id}', [LabController::class, 'destroy'])->name('destroy'); // Delete lab
});

// Treatment Master Routes
Route::prefix('admin/treatment')->name('treatment.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/index', [TreatmentController::class, 'index'])->name('index'); // Show list & edit form
    Route::post('/store', [TreatmentController::class, 'store'])->name('store'); // Store new treatment
    Route::put('/update/{id}', [TreatmentController::class, 'update'])->name('update'); // Update treatment
    Route::delete('/delete/{id}', [TreatmentController::class, 'destroy'])->name('destroy'); // Delete treatment
});

// Medicine routes
Route::prefix('admin/medicine')->name('medicine.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/index', [MedicineController::class, 'index'])->name('index'); // List all medicines
    Route::post('/store', [MedicineController::class, 'store'])->name('store'); // Store new medicine
    Route::put('/update/{id}', [MedicineController::class, 'update'])->name('update'); // Update medicine
    Route::delete('/delete/{id}', [MedicineController::class, 'destroy'])->name('destroy'); // Delete medicine
});

// Dosage Routes
Route::prefix('admin/dosage')->name('dosage.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/index', [DosageController::class, 'index'])->name('index'); // Shows list & edit form
    Route::post('/store', [DosageController::class, 'store'])->name('store'); // Save new dosage
    Route::put('/update/{id}', [DosageController::class, 'update'])->name('update'); // Update dosage
    Route::delete('/delete/{id}', [DosageController::class, 'destroy'])->name('destroy'); // Delete dosage
});
