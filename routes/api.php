<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BalanceController;
use App\Http\Controllers\Api\BankController;
use App\Http\Controllers\Api\BillController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ChairmanController;
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
use App\Http\Controllers\Api\CitizenController;
use App\Http\Controllers\Api\CitizenTaxController;
use App\Http\Controllers\Api\ComplainController;
use App\Http\Controllers\Api\DistrictController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\LabReportController;
use App\Http\Controllers\Api\LabReportTemplateController;
use App\Http\Controllers\Api\MethodController;
use App\Http\Controllers\Api\NonCitizenTaxController;
use App\Http\Controllers\Api\NonCitizenTaxPaymentController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\PostOfficeController;
use App\Http\Controllers\Api\UnionController;
use App\Http\Controllers\Api\UpazilaController;
use App\Http\Controllers\Api\VillageController;
use App\Http\Controllers\Api\WardController;
use App\Http\Controllers\Api\NonHoldingCitizenController;
use App\Http\Controllers\Api\TaxPaymentsController;
use App\Http\Controllers\Api\TradeLicenseController;
use App\Http\Controllers\Api\VariationTemplateController;
use App\Http\Controllers\SetUpController;
use App\Http\Requests\ImageRequest;
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



// Category
Route::post('/v1.0/categories', [CategoryController::class, "createCategory"]);
Route::put('/v1.0/categories', [CategoryController::class, "updateCategory"]);
Route::get('/v1.0/categories', [CategoryController::class, "getCategory"]);
Route::get('/v1.0/categories/all', [CategoryController::class, "getAllCategory"]);
Route::get('/v1.0/categories/{id}', [CategoryController::class, "getCategoryById"]);
Route::get('/v1.0/categories/search/{term}', [CategoryController::class, "searchCategory"]);
Route::delete('/v1.0/categories/{id}', [CategoryController::class, "deleteCategory"]);


Route::post('/v1.0/image/upload/single/{location}',function(ImageRequest $request,$location){

                     $new_file_name = time() . '_' . $request->image->getClientOriginalName();

                     $request->image->move($location, $new_file_name);
                     $imageName = $location . "/" . $new_file_name;
                     return response()->json(["image" => $imageName],201);

});

// Variation Templates
Route::post('/v1.0/variation-templates', [VariationTemplateController::class, "createVariationTemplate"]);
Route::put('/v1.0/variation-templates', [VariationTemplateController::class, "updateVariationTemplate"]);
Route::get('/v1.0/variation-templates', [VariationTemplateController::class, "getVariationTemplate"]);
Route::get('/v1.0/variation-templates/all', [VariationTemplateController::class, "getAllVariationTemplate"]);
Route::get('/v1.0/variation-templates/{id}', [
    VariationTemplateController::class, "getVariationTemplateById"
]);
Route::get('/v1.0/variation-templates/search/{term}', [VariationTemplateController::class, "searchVariationTemplate"]);
Route::delete('/v1.0/variation-templates/{id}', [VariationTemplateController::class, "deleteVariationTemplate"]);


// product
Route::post('/v1.0/products', [ProductController::class, "createProduct"]);
Route::put('/v1.0/products', [ProductController::class, "updateProduct"]);
Route::get('/v1.0/products', [ProductController::class, "getProduct"]);
Route::get('/v1.0/products/pagination/{perPage}', [ProductController::class, "getProductPagination"]);
Route::get('/v1.0/products/{id}', [ProductController::class, "getProductById"]);
Route::get('/v1.0/products/search/{term}', [ProductController::class, "searchProduct"]);
Route::delete('/v1.0/products/{id}', [ProductController::class, "deleteProduct"]);




// Unions
Route::post('/v1.0/unions', [UnionController::class, "createUnion"]);
Route::put('/v1.0/unions', [UnionController::class, "updateUnion"]);
Route::get('/v1.0/unions', [UnionController::class, "getUnion"]);
Route::get('/v1.0/unions/all', [UnionController::class, "getAllUnion"]);
Route::get('/v1.0/unions/{id}', [UnionController::class, "getUnionById"]);
Route::get('/v1.0/unions/search/{term}', [UnionController::class, "searchUnion"]);
Route::delete('/v1.0/unions/{id}', [UnionController::class, "deleteUnion"]);


// Wards
Route::post('/v1.0/wards', [WardController::class, "createWard"]);
Route::put('/v1.0/wards', [WardController::class, "updateWard"]);
Route::get('/v1.0/wards', [WardController::class, "getWard"]);
Route::get('/v1.0/wards/unions/{unionId}', [WardController::class, "getWardByUnion"]);
Route::get('/v1.0/wards/{id}', [WardController::class, "getWardById"]);
Route::get('/v1.0/wards/search/{term}', [WardController::class, "searchWard"]);
Route::delete('/v1.0/wards/{id}', [WardController::class, "deleteWard"]);


// Village
Route::post('/v1.0/villages', [VillageController::class, "createVillage"]);
Route::put('/v1.0/villages', [VillageController::class, "updateVillage"]);
Route::get('/v1.0/villages', [VillageController::class, "getVillage"]);
Route::get('/v1.0/villages/{id}', [VillageController::class, "getVillageById"]);
Route::get('/v1.0/villages/search/{term}', [VillageController::class, "searchVillage"]);
Route::delete('/v1.0/villages/{id}', [VillageController::class, "deleteVillage"]);

// Post Office
Route::post('/v1.0/post-office', [PostOfficeController::class, "createPostOffice"]);
Route::put('/v1.0/post-office', [PostOfficeController::class, "updatePostOffice"]);
Route::get('/v1.0/post-office', [PostOfficeController::class, "getPostOffice"]);
Route::get('/v1.0/post-office/{id}', [PostOfficeController::class, "getPostOfficeById"]);
Route::get('/v1.0/post-office/search/{term}', [PostOfficeController::class, "searchPostOffice"]);
Route::delete('/v1.0/post-office/{id}', [PostOfficeController::class, "deletePostOffice"]);

// Citizen
Route::post('/v1.0/citizens', [CitizenController::class, "createCitizen"]);
Route::put('/v1.0/citizens', [CitizenController::class, "updateCitizen"]);
Route::get('/v1.0/citizens', [CitizenController::class, "getCitizen"]);
Route::get('/v1.0/citizens/{id}', [CitizenController::class, "getCitizenById"]);
Route::get('/v1.0/citizens/search/{term}', [CitizenController::class, "searchCitizen"]);
Route::delete('/v1.0/citizens/{id}', [CitizenController::class, "deleteCitizen"]);



// non holding Citizen
Route::post('/v1.0/nonholding-citizens', [NonHoldingCitizenController::class, "createCitizen"]);
Route::put('/v1.0/nonholding-citizens', [NonHoldingCitizenController::class, "updateCitizen"]);
Route::get('/v1.0/nonholding-citizens', [NonHoldingCitizenController::class, "getCitizen"]);
Route::get('/v1.0/nonholding-citizens/{id}', [NonHoldingCitizenController::class, "getCitizenById"]);
Route::get('/v1.0/nonholding-citizens/search/{term}', [NonHoldingCitizenController::class, "searchCitizen"]);
Route::delete('/v1.0/nonholding-citizens/{id}', [NonHoldingCitizenController::class, "deleteCitizen"]);

// Chairman
Route::post('/v1.0/chairmans', [ChairmanController::class, "createChairman"]);
Route::put('/v1.0/chairmans', [ChairmanController::class, "updateChairman"]);
Route::get('/v1.0/chairmans', [ChairmanController::class, "getChairman"]);
Route::get('/v1.0/chairmans/{id}', [ChairmanController::class, "getChairmanById"]);
Route::get('/v1.0/chairmans/search/{term}', [ChairmanController::class, "searchChairman"]);
Route::delete('/v1.0/chairmans/{id}', [ChairmanController::class, "deleteChairman"]);

// Complain
Route::post('/v1.0/complains', [ComplainController::class, "createComplain"]);
Route::put('/v1.0/complains', [ComplainController::class, "updateComplain"]);
Route::get('/v1.0/complains', [ComplainController::class, "getComplain"]);
Route::get('/v1.0/complains/{id}', [ComplainController::class, "getComplainById"]);
Route::get('/v1.0/complains/search/{term}', [ComplainController::class, "searchComplain"]);
Route::delete('/v1.0/complains/{id}', [ComplainController::class, "deleteComplain"]);
// trade license
Route::post('/v1.0/trade-license', [TradeLicenseController::class, "createTradeLicense"]);
Route::put('/v1.0/trade-license', [TradeLicenseController::class, "updateTradeLicense"]);
Route::get('/v1.0/trade-license', [TradeLicenseController::class, "getTradeLicense"]);
Route::get('/v1.0/trade-license/{id}', [TradeLicenseController::class, "getTradeLicenseById"]);
Route::get('/v1.0/trade-license/search/{term}', [TradeLicenseController::class, "searchTradeLicense"]);
Route::delete('/v1.0/trade-license/{id}', [TradeLicenseController::class, "deleteTradeLicense"]);

// tax payment
Route::post('/v1.0/tax-payments', [TaxPaymentsController::class, "createTaxPayment"]);
Route::put('/v1.0/tax-payments', [TaxPaymentsController::class, "updateTaxPayment"]);
Route::get('/v1.0/tax-payments', [TaxPaymentsController::class, "getTaxPayment"]);
Route::get('/v1.0/tax-payments/{id}', [TaxPaymentsController::class, "getTaxPaymentById"]);
Route::get('/v1.0/tax-payments/search/{term}', [TaxPaymentsController::class, "searchTaxPayment"]);
Route::delete('/v1.0/tax-payments/{id}', [TaxPaymentsController::class, "deleteTaxPayment"]);

// non citizen tax payment
Route::post('/v1.0/non-citizen-tax-payments', [NonCitizenTaxPaymentController::class, "createNonCitizenTaxPayment"]);
Route::put('/v1.0/non-citizen-tax-payments', [NonCitizenTaxPaymentController::class, "updateNonCitizenTaxPayment"]);
Route::get('/v1.0/non-citizen-tax-payments', [NonCitizenTaxPaymentController::class, "getNonCitizenTaxPayment"]);
Route::get('/v1.0/non-citizen-tax-payments/{id}', [NonCitizenTaxPaymentController::class, "getNonCitizenTaxPaymentById"]);
Route::get('/v1.0/non-citizen-tax-payments/search/{term}', [NonCitizenTaxPaymentController::class, "searchNonCitizenTaxPayment"]);
Route::delete('/v1.0/non-citizen-tax-payments/{id}', [NonCitizenTaxPaymentController::class, "deleteNonCitizenTaxPayment"]);

// citizen taxes
Route::post('/v1.0/cizen-taxes', [CitizenTaxController::class, "createCitizenTax"]);
Route::put('/v1.0/cizen-taxes', [CitizenTaxController::class, "updateCitizenTax"]);
Route::get('/v1.0/cizen-taxes', [CitizenTaxController::class, "getCitizenTax"]);
Route::get('/v1.0/cizen-taxes/{id}', [CitizenTaxController::class, "getCitizenTaxById"]);
Route::get('/v1.0/cizen-taxes/search/{term}', [CitizenTaxController::class, "searchCitizenTax"]);
Route::delete('/v1.0/cizen-taxes/{id}', [CitizenTaxController::class, "deleteCitizenTax"]);

// non citizen taxes
Route::post('/v1.0/non-cizen-taxes', [NonCitizenTaxController::class, "createNonCitizenTax"]);
Route::put('/v1.0/non-cizen-taxes', [NonCitizenTaxController::class, "updateNonCitizenTax"]);
Route::get('/v1.0/non-cizen-taxes', [NonCitizenTaxController::class, "getNonCitizenTax"]);
Route::get('/v1.0/non-cizen-taxes/{id}', [NonCitizenTaxController::class, "getNonCitizenTaxById"]);
Route::get('/v1.0/non-cizen-taxes/search/{term}', [NonCitizenTaxController::class, "searchNonCitizenTax"]);
Route::delete('/v1.0/non-cizen-taxes/{id}', [NonCitizenTaxController::class, "deleteNonCitizenTax"]);




// Upazila
Route::post('/v1.0/upazilas', [UpazilaController::class, "createUpazila"]);
Route::put('/v1.0/upazilas', [UpazilaController::class, "updateUpazila"]);
Route::get('/v1.0/upazilas', [UpazilaController::class, "getUpazila"]);
Route::get('/v1.0/upazilas/{id}', [UpazilaController::class, "getUpazilaById"]);
Route::get('/v1.0/upazilas/search/{term}', [UpazilaController::class, "searchUpazila"]);
Route::delete('/v1.0/upazilas/{id}', [UpazilaController::class, "deleteUpazila"]);


// District
Route::post('/v1.0/districts', [DistrictController::class, "createDistrict"]);
Route::put('/v1.0/districts', [DistrictController::class, "updateDistrict"]);
Route::get('/v1.0/districts', [DistrictController::class, "getDistrict"]);
Route::get('/v1.0/districts/{id}', [DistrictController::class, "getDistrictById"]);
Route::get('/v1.0/districts/search/{term}', [DistrictController::class, "searchDistrict"]);
Route::delete('/v1.0/districts/{id}', [DistrictController::class, "deleteDistrict"]);

// Methods
Route::post('/v1.0/methods', [MethodController::class, "createMethod"]);
Route::put('/v1.0/methods', [MethodController::class, "updateMethod"]);
Route::get('/v1.0/methods', [MethodController::class, "getMethod"]);
Route::get('/v1.0/methods/{id}', [MethodController::class, "getMethodById"]);
Route::get('/v1.0/methods/search/{term}', [MethodController::class, "searchMethod"]);
Route::delete('/v1.0/methods/{id}', [MethodController::class, "deleteMethod"]);



// Lab Report
Route::post('/v1.0/lab-reports', [LabReportController::class, "createLabReport"]);
Route::delete('/v1.0/lab-reports/{id}', [LabReportController::class, "deleteLabReport"]);
Route::put('/v1.0/lab-reports', [LabReportController::class, "updateLabReport"]);
Route::get('/v1.0/lab-reports', [LabReportController::class, "getLabReports"]);



// Report Template
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

    // Route::post('/v1.0/products', [ProductController::class, "createProduct"]);
    // Route::delete('/v1.0/products/{id}', [ProductController::class, "deleteProduct"]);
    // Route::put('/v1.0/products', [ProductController::class, "updateProduct"]);
    // Route::get('/v1.0/products', [ProductController::class, "getProducts"]);
    // Route::get('/v1.0/products/search/{search}', [ProductController::class, "searchProductByName"]);
    // Route::get('/v1.0/products/{id}', [ProductController::class, "getProductById"]);
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
