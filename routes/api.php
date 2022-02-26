<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BalanceController;
use App\Http\Controllers\Api\BankController;
use App\Http\Controllers\Api\BillController;
use App\Http\Controllers\Api\CreditNoteController;
use App\Http\Controllers\Api\DebitNoteController;
use App\Http\Controllers\Api\ParchaseController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RequisitionController;
use App\Http\Controllers\Api\RevenueController;
use App\Http\Controllers\Api\RolesController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WingController;
use App\Http\Controllers\Api\CharOfAccountController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\LabReportTemplateController;
use App\Http\Controllers\Api\PatientController;

use App\Http\Controllers\SetUpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::middleware('auth:api')->get('/v1.0/user', function (Request $request) {
    $user = $request->user();
    $data["user"] = $user;
    $data["permissions"]  = $user->getAllPermissions()->pluck('name');
    $data["roles"] = $user->roles->pluck('name');

    return response()->json(
        $data,
        200
    );
});
Route::post('/v1.0/login', [AuthController::class, "login"]);
Route::post('/v1.0/register', [AuthController::class, "register"]);
// protected routes
Route::middleware(['auth:api'])->group(function () {
    // Route::get('/v1.0/setup', [SetUpController::class, "setUp"]);
    Route::post('/v1.0/logout', [AuthController::class, "logout"]);



// Doctor
Route::post('/v1.0/report-templates', [LabReportTemplateController::class, "createTemplate"]);
Route::delete('/v1.0/report-templates/{id}', [LabReportTemplateController::class, "deleteTemplate"]);
Route::put('/v1.0/report-templates', [LabReportTemplateController::class, "updateTemplate"]);
Route::get('/v1.0/report-templates', [LabReportTemplateController::class, "getTemplates"]);
Route::get('/v1.0/report-templates/all', [LabReportTemplateController::class, "getAllTemplates"]);



    // Appointment
Route::post('/v1.0/appointments', [AppointmentController::class, "createAppointment"]);
Route::delete('/v1.0/appointments/{id}', [AppointmentController::class, "deleteAppointment"]);
Route::put('/v1.0/appointments', [AppointmentController::class, "updateAppointment"]);
Route::get('/v1.0/appointments', [AppointmentController::class, "getAppointments"]);



// Doctor
Route::post('/v1.0/doctors', [DoctorController::class, "createDoctor"]);
Route::delete('/v1.0/doctors/{id}', [DoctorController::class, "deleteDoctor"]);
Route::put('/v1.0/doctors', [DoctorController::class, "updateDoctor"]);
Route::get('/v1.0/doctors', [DoctorController::class, "getDoctors"]);
Route::get('/v1.0/doctors/all', [DoctorController::class, "getAllDoctors"]);

    // Patient
    Route::post('/v1.0/patients', [PatientController::class, "createPatient"]);
    Route::delete('/v1.0/patients/{id}', [PatientController::class, "deletePatient"]);
    Route::put('/v1.0/patients', [PatientController::class, "updatePatient"]);
    Route::get('/v1.0/patients', [PatientController::class, "getPatients"]);
    Route::get('/v1.0/patients/all', [PatientController::class, "getAllPatients"]);

    // products
    Route::post('/v1.0/products', [ProductController::class, "createProduct"]);
    Route::delete('/v1.0/products/{id}', [ProductController::class, "deleteProduct"]);
    Route::put('/v1.0/products', [ProductController::class, "updateProduct"]);
    Route::get('/v1.0/products', [ProductController::class, "getProducts"]);
    Route::get('/v1.0/products/search/{search}', [ProductController::class, "searchProductByName"]);
    Route::get('/v1.0/products/{id}', [ProductController::class, "getProductById"]);
    // requisitions
    Route::post('/v1.0/requisitions', [RequisitionController::class, "createRequisition"]);
    Route::get('/v1.0/requisitions', [RequisitionController::class, "getRequisitions"]);
    Route::get('/v1.0/requisitions/return', [RequisitionController::class, "getRequisitionsReturn"]);
    Route::get('/v1.0/requisitions/report/thismonth', [RequisitionController::class, "getRequisitionsThisMonthReport"]);
    Route::delete('/v1.0/requisitions/{id}', [RequisitionController::class, "deleteRequisition"]);
    Route::put('/v1.0/requisitions', [RequisitionController::class, "updateRequisition"]);
    Route::put('/v1.0/requisitions/approve', [RequisitionController::class, "approveRequisition"]);

    // parchases
    Route::get('/v1.0/parchases', [ParchaseController::class, "getParchases"]);
    Route::get('/v1.0/parchases/return', [ParchaseController::class, "getPurchasesReturn"]);
    Route::get('/v1.0/purchase/report/thismonth', [ParchaseController::class, "getPurchaseThisMonthReport"]);
    Route::post('/v1.0/parchases', [ParchaseController::class, "createParchase"]);
    Route::put('/v1.0/parchases', [ParchaseController::class, "updatePurchase"]);
    Route::delete('/v1.0/parchases/{id}', [ParchaseController::class, "deletePurchase"]);
    Route::put('/v1.0/parchases/approve', [ParchaseController::class, "approvePurchase"]);
    Route::put('/v1.0/requisitionToParchase', [RequisitionController::class, "requisitionToParchase"]);

    // income @@@@@@@@@@@@@@@@
    Route::get('/v1.0/income/report/thismonth', [RevenueController::class, "getIncomeThisMonthReport"]);
    // revenue
    Route::post('/v1.0/revenues', [RevenueController::class, "createRevenue"]);
    Route::get('/v1.0/revenues', [RevenueController::class, "getRevenues"]);
    Route::put('/v1.0/revenues/approve', [RevenueController::class, "approveRevenue"]);
    Route::delete('/v1.0/revenues/{id}', [RevenueController::class, "deleteRevenue"]);
    Route::put('/v1.0/revenues', [RevenueController::class, "updateRevenue"]);

    // credit notes
    Route::post('/v1.0/credit-notes', [CreditNoteController::class, "createCreditNote"]);
    Route::get('/v1.0/credit-notes', [CreditNoteController::class, "getCreditNotes"]);
    Route::put('/v1.0/credit-notes/approve', [CreditNoteController::class, "approveCreditNote"]);
    Route::delete('/v1.0/credit-notes/{id}', [CreditNoteController::class, "deleteCreditNote"]);
    Route::put('/v1.0/credit-notes', [CreditNoteController::class, "updateCreditNote"]);


    // income @@@@@@@@@@@@@@@@
    Route::get('/v1.0/expense/report/thismonth', [PaymentController::class, "getExpenseThisMonthReport"]);
    // Bills
    Route::post('/v1.0/bills', [BillController::class, "createBill"]);
    Route::get('/v1.0/bills', [BillController::class, "getBills"]);
    Route::get('/v1.0/bills/{wingId}', [BillController::class, "getBillsByWing"]);

    // Payment
    Route::post('/v1.0/payments', [PaymentController::class, "createPayment"]);
    Route::get('/v1.0/payments', [PaymentController::class, "getPayment"]);
    Route::put('/v1.0/payments/approve', [PaymentController::class, "approvePayment"]);
    Route::delete('/v1.0/payments/{id}', [PaymentController::class, "deletePayment"]);
    Route::put('/v1.0/payments', [PaymentController::class, "updatePayment"]);
    // debit note
    Route::post('/v1.0/debit-notes', [DebitNoteController::class, "createDebitNote"]);
    Route::get('/v1.0/debit-notes', [DebitNoteController::class, "getDebitNotes"]);
    Route::put('/v1.0/debit-notes/approve', [DebitNoteController::class, "approveDebitNote"]);
    // wing
    Route::post('/v1.0/wings', [WingController::class, "createWing"]);
    Route::get('/v1.0/wings', [WingController::class, "getWings"]);
    Route::delete('/v1.0/wings/{id}', [WingController::class, "deleteWing"]);
    Route::put('/v1.0/wings', [WingController::class, "updateWing"]);
    Route::get('/v1.0/wings/all', [WingController::class, "getAllWings"]);
    //  bank
    Route::post('/v1.0/banks', [BankController::class, "createBank"]);
    Route::delete('/v1.0/banks/{id}', [BankController::class, "deleteBank"]);
    Route::put('/v1.0/banks', [BankController::class, "updateBank"]);
    Route::get('/v1.0/banks', [BankController::class, "getBanks"]);
    Route::get('/v1.0/banks/wing/{wing_id}', [BankController::class, "getBanksByWing"]);
    // balance
    Route::get('/v1.0/balance/total', [BalanceController::class, "getTotalBalance"]);
    Route::get('/v1.0/balance/wing-bank/{wing_id}/{bank_id}', [BalanceController::class, "getBalanceByWingAndBank"]);
    Route::get('/v1.0/balance/wing/{wing_id}', [BalanceController::class, "getBalanceByWing"]);
    Route::patch('/v1.0/balance/transfer', [BalanceController::class, "transferBalance"]);
    Route::get('/v1.0/transfers', [BalanceController::class, "getTransfers"]);
    Route::get('/v1.0/transfers/{account_number}', [BalanceController::class, "getTransfersByAccountNumber"]);

    // user
    Route::post('/v1.0/users', [UserController::class, "createUser"]);
    Route::get('/v1.0/users', [UserController::class, "getUsers"]);
    Route::delete('/v1.0/users/{id}', [UserController::class, "deleteUser"]);
    // roles
    Route::post('/v1.0/roles', [RolesController::class, "createRole"]);
    Route::get('/v1.0/roles', [RolesController::class, "getRoles"]);
    Route::get('/v1.0/roles/all', [RolesController::class, "getRolesAll"]);
    // chart of account

    Route::get('/v1.0/accounts', [CharOfAccountController::class, "getAccounts"]);
    Route::post('/v1.0/chart-of-account', [CharOfAccountController::class, "createCharOfAccount"]);
    Route::get('/v1.0/chart-of-account', [CharOfAccountController::class, "getChartOfAccounts"]);
});
