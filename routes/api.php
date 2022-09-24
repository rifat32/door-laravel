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
use App\Http\Controllers\Api\ColorController;
use App\Http\Controllers\Api\ComplainController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\CouponController;
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
use App\Http\Controllers\Api\OptionController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ShippingController;
use App\Http\Controllers\Api\StateController;
use App\Http\Controllers\Api\StyleController;
use App\Http\Controllers\Api\TaxPaymentsController;
use App\Http\Controllers\Api\ThicknessController;
use App\Http\Controllers\Api\TradeLicenseController;
use App\Http\Controllers\Api\VariationTemplateController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SetUpController;
use App\Http\Requests\AccountDetailsRequest;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ImageRequest;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\ProductVariation;
use App\Models\User;
use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
////////////////////////////////////
use App\Mail\orderconfirmationmail;
use App\Mail\orderdeliveredmail;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;

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
Route::post('/v1.0/register2', [AuthController::class, "register2"]);

//emil api
//Routes for  for Welcome Mail
Route::post("/v1.0/email", function (Request $request) {
    $email = $request->email;
    $mail = Mail::to($email)->send(new WelcomeMail());
    return json_encode(["type" => "success", "message" => "Your mail send successfully from post method to this $email"]);
    /*     return new WelcomeMail(); */
});
//Routes for order confirmation mail
Route::post("/v1.0/orderconfirmition", function (Request $request) {
    /*    $order = Order::where("email", $request->email)->orderByDesc("id")->first();
    $mail = $request->email;
    $order_id = $order->id;
    Mail::to($mail)->send(new orderconfirmationmail($order_id)); */
    return json_encode(["type" => "null"]);
    /*  return new orderconfirmationmail($order_id); */
});

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

    // Category
    Route::post('/v1.0/shippings', [ShippingController::class, "createShipping"]);
    Route::put('/v1.0/shippings', [ShippingController::class, "updateShipping"]);
    Route::get('/v1.0/shippings', [ShippingController::class, "getShipping"]);
    Route::get('/v1.0/shippings/all', [ShippingController::class, "getAllShipping"]);
    Route::get('/v1.0/shippings/{id}', [ShippingController::class, "getShippingById"]);
    Route::get('/v1.0/shippings/search/{term}', [ShippingController::class, "searchShipping"]);
    Route::delete('/v1.0/shippings/{id}', [ShippingController::class, "deleteShipping"]);


    // City
    Route::post('/v1.0/countries', [CountryController::class, "createCountry"]);
    Route::put('/v1.0/countries', [CountryController::class, "updateCountry"]);
    Route::get('/v1.0/countries', [CountryController::class, "getCountry"]);

    Route::get('/v1.0/countries/by-id/{id}', [CountryController::class, "getCountryById"]);
    Route::get('/v1.0/countries/search/{term}', [CountryController::class, "searchCountry"]);
    Route::delete('/v1.0/countries/{id}', [CountryController::class, "deleteCountry"]);

    // State
    //   Route::post('/v1.0/states', [CountryController::class, "createCountry"]);
    //   Route::put('/v1.0/states', [CountryController::class, "updateCountry"]);
    //   Route::get('/v1.0/states', [CountryController::class, "getCountry"]);
    //   Route::get('/v1.0/states/all', [CountryController::class, "getAllCountry"]);
    //   Route::get('/v1.0/states/get/country-id/{countryId}', [CountryController::class, "getAllCountry"]);

    //   Route::get('/v1.0/states/{id}', [CountryController::class, "getCountryById"]);
    //   Route::get('/v1.0/states/search/{term}', [CountryController::class, "searchCountry"]);

    //   Route::delete('/v1.0/states/{id}', [CountryController::class, "deleteCountry"]);

    // thickness
    Route::post('/v1.0/thicknesses', [ThicknessController::class, "createThickness"]);
    Route::put('/v1.0/thicknesses', [ThicknessController::class, "updateThickness"]);
    Route::get('/v1.0/thicknesses', [ThicknessController::class, "getThickness"]);
    Route::get('/v1.0/thicknesses/all', [ThicknessController::class, "getAllThickness"]);
    Route::get('/v1.0/thicknesses/{id}', [ThicknessController::class, "getThicknessById"]);
    Route::get('/v1.0/thicknesses/search/{term}', [ThicknessController::class, "searchThickness"]);
    Route::delete('/v1.0/thicknesses/{id}', [ThicknessController::class, "deleteThickness"]);

    // Style
    Route::post('/v1.0/styles', [StyleController::class, "createStyle"]);
    Route::put('/v1.0/styles', [StyleController::class, "updateStyle"]);
    Route::get('/v1.0/styles', [StyleController::class, "getStyle"]);
    Route::get('/v1.0/styles/all', [StyleController::class, "getAllStyle"]);
    Route::get('/v1.0/styles/{id}', [StyleController::class, "getStyleById"]);
    Route::get('/v1.0/styles/search/{term}', [StyleController::class, "searchStyle"]);
    Route::delete('/v1.0/styles/{id}', [StyleController::class, "deleteStyle"]);

    Route::post('/v1.0/image/upload/single/{location}', function (ImageRequest $request, $location) {

        $new_file_name = time() . '_' . $request->image->getClientOriginalName();

        $request->image->move(public_path($location), $new_file_name);
        $imageName = $location . "/" . $new_file_name;
        return response()->json(["image" => $imageName], 201);
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

    // Option Templates
    Route::post('/v1.0/menus', [MenuController::class, "createMenu"]);
    Route::put('/v1.0/menus', [MenuController::class, "updateMenu"]);
    Route::get('/v1.0/menus', [MenuController::class, "getMenu"]);
    Route::get('/v1.0/menus/all', [MenuController::class, "getAllMenu"]);
    Route::get('/v1.0/menus/{id}', [
        MenuController::class, "getMenuById"
    ]);
    Route::get('/v1.0/menus/search/{term}', [MenuController::class, "searchMenu"]);
    Route::delete('/v1.0/menus/{id}', [MenuController::class, "deleteMenu"]);

    // Option Templates
    Route::post('/v1.0/options', [OptionController::class, "createOptionTemplate"]);
    Route::put('/v1.0/options', [OptionController::class, "updateOptionTemplate"]);
    Route::get('/v1.0/options', [OptionController::class, "getOptionTemplate"]);
    Route::get('/v1.0/options/all', [OptionController::class, "getAllOptionTemplate"]);
    Route::get('/v1.0/options/{id}', [
        OptionController::class, "getOptionTemplateById"
    ]);
    Route::get('/v1.0/options/search/{term}', [OptionController::class, "searchOptionTemplate"]);
    Route::delete('/v1.0/options/{id}', [OptionController::class, "deleteOptionTemplate"]);

    // Color Templates
    Route::post('/v1.0/colors', [ColorController::class, "createColor"]);
    Route::put('/v1.0/colors', [ColorController::class, "updateColor"]);
    Route::get('/v1.0/colors', [ColorController::class, "getColor"]);
    Route::get('/v1.0/colors/all', [ColorController::class, "getAllColor"]);
    Route::get('/v1.0/colors/{id}', [
        ColorController::class, "getColorById"
    ]);
    Route::delete('/v1.0/colors/{id}', [
        ColorController::class, "deleteColor"
    ]);
    Route::get('/v1.0/colors/search/{term}', [ColorController::class, "searchColor"]);

    // Product
    Route::post('/v1.0/products', [ProductController::class, "createProduct"]);
    Route::put('/v1.0/products', [ProductController::class, "updateProduct"]);
    Route::put('/v1.0/products/bulkedit/price', [ProductController::class, "updateBulkPrice"]);
    Route::put('/v1.0/products/bulkedit/delete', [ProductController::class, "bulkDelete"]);
    Route::get('/v1.0/products', [ProductController::class, "getProduct"]);
    Route::get('/v1.0/products/pagination/{perPage}', [ProductController::class, "getProductPagination"]);
    Route::get('/v1.0/products/{id}', [ProductController::class, "getProductById"]);
    Route::get('/v1.0/products/search/{term}', [ProductController::class, "searchProduct"]);
    Route::delete('/v1.0/products/{id}', [ProductController::class, "deleteProduct"]);
    Route::delete('/v1.0/products/variation/{id}', [ProductController::class, "deleteVariation"]);
    Route::delete('/v1.0/products/product-variation/{id}', [ProductController::class, "deleteProductVariation"]);

    // Category
    Route::post('/v1.0/coupons', [CouponController::class, "createCoupon"]);
    Route::put('/v1.0/coupons', [CouponController::class, "updateCoupon"]);
    Route::get('/v1.0/coupons', [CouponController::class, "getCoupon"]);
    Route::get('/v1.0/coupons/all', [CouponController::class, "getAllCoupon"]);
    Route::get('/v1.0/coupons/{id}', [CouponController::class, "getCouponById"]);
    Route::get('/v1.0/coupons/search/{term}', [CouponController::class, "searchCoupon"]);
    Route::delete('/v1.0/coupons/{id}', [CouponController::class, "deleteCoupon"]);
    // user
    Route::post('/v1.0/users', [UserController::class, "createUser"]);
    Route::put('/v1.0/users', [UserController::class, "updateUser"]);
    Route::get('/v1.0/users', [UserController::class, "getUsers"]);
    Route::delete('/v1.0/users/{id}', [UserController::class, "deleteUser"]);
    Route::get('/v1.0/users/search/{term}', [UserController::class, "searchUser"]);
    // roles
    Route::post('/v1.0/roles', [RolesController::class, "createRole"]);
    Route::get('/v1.0/roles', [RolesController::class, "getRoles"]);
    Route::get('/v1.0/roles/all', [RolesController::class, "getRolesAll"]);
    // chart of account

    Route::get('/v1.0/accounts', [CharOfAccountController::class, "getAccounts"]);

    Route::get('/v1.0/orders', function (Request $request) {

        $data["data"] = Order::latest()->paginate(10);
        return response()->json($data, 200);
    });

    Route::get('/v1.0/orders/search/{term}', function ($term, Request $request) {

        $data["data"] = Order::where(function ($query) use ($term) {
            $query->where("fname", "like", "%" . $term . "%");
            $query->orWhere("lname", "like", "%" . $term . "%");
            $query->orWhere("cname", "like", "%" . $term . "%");
            $query->orWhere("billing_address", "like", "%" . $term . "%");
            $query->orWhere("billing_address2", "like", "%" . $term . "%");
            $query->orWhere("city", "like", "%" . $term . "%");
            $query->orWhere("zipcode", "like", "%" . $term . "%");
            $query->orWhere("phone", "like", "%" . $term . "%");
            $query->orWhere("email", "like", "%" . $term . "%");
            $query->orWhere("additional_info", "like", "%" . $term . "%");
        })
            ->latest()
            ->paginate(10);
        return response()->json($data, 200);
    });

    Route::get('/v1.0/orders/client/customers', function (Request $request) {
        $customerId =  Customer::where(["email" => $request->user()->email])->first()->id;
        if ($customerId) {
            $data["data"] = Order::where([
                "customer_id" => Customer::where(["email" => $request->user()->email])->first()->id
            ])->orderByDesc("id")->paginate(10);
            return response()->json($data, 200);
        } else {
            return response()->json([
                "message" => "No Orders"
            ], 404);
        }
    });
    Route::get('/v1.0/orders/customers/{customerId}', function ($customerId, Request $request) {

        $data["data"] = Order::where([
            "customer_id" => $customerId
        ])->paginate(10);
        return response()->json($data, 200);
    });

    Route::get('/v1.0/orders/{id}', [OrderController::class, "showOrder"]);

    Route::get('/v1.0/customers/{id}', [OrderController::class, "showCustomer"]);

    Route::post('/v1.0/orders/status/{id}', [OrderController::class, "changeStatus"]);
    Route::post('/v1.0/orders/status/cancel/{id}', [OrderController::class, "cancelOrder"]);
    Route::get('/v1.0/customers', [OrderController::class, "getCustomers"]);
    Route::get('/v1.0/customers/search/{term}', function ($term, Request $request) {

        $data["data"] = Customer::where(function ($query) use ($term) {
            $query->where("fname", "like", "%" . $term . "%");
            $query->orWhere("lname", "like", "%" . $term . "%");
            $query->orWhere("cname", "like", "%" . $term . "%");
            $query->orWhere("billing_address", "like", "%" . $term . "%");
            $query->orWhere("billing_address2", "like", "%" . $term . "%");
            $query->orWhere("city", "like", "%" . $term . "%");
            $query->orWhere("zipcode", "like", "%" . $term . "%");
            $query->orWhere("phone", "like", "%" . $term . "%");
            $query->orWhere("email", "like", "%" . $term . "%");
            $query->orWhere("type", "like", "%" . $term . "%");
        })
            ->latest()
            ->paginate(10);
        return response()->json($data, 200);
    });

    Route::get('/v1.0/client/customer/info', function (Request $request) {

        $customer = Customer::where([
            "email" => $request->user()->email,
        ])
            ->first();

        return response()->json([
            "customer" => $customer
        ], 200);
    });


    Route::post('/v1.0/client/orders/loggedin', [OrderController::class, "create2"]);

    Route::post('/v1.0/client/addresses', function (AddressRequest $request) {

        $insertableData = $request->validated();
        $insertableData["user_id"] = $request->user()->id;

        if (
            Address::where([
                "user_id" => $request->user()->id
            ])
            ->count() == 0
        ) {
            $insertableData["is_default"] = 1;
        }
        $inserted_address = Address::create($insertableData);
        if ($inserted_address->is_default) {
            Address::where(
                "id",
                "!=",
                $inserted_address->id
            )
                ->update([
                    "is_default" => 0
                ]);
        }

        $data['data'] = $inserted_address;

        return response()->json($data, 201);
    });
    Route::delete('/v1.0/client/addresses/{id}', function (Request $request, $id) {
        Address::where([
            "user_id" => $request->user()->id,
            "id" => $id
        ])
            ->delete();


        return response()->json(["ok" => true], 200);
    });
    Route::put('/v1.0/client/addresses/{id}', function (AddressRequest $request, $id) {
        $updatableData = $request->validated();
        Address::where([
            "user_id" => $request->user()->id,
            "id" => $id
        ])
            ->update(
                collect($updatableData)->only([
                    "billing_address",
                    "billing_address2",
                    "city",
                    "zipcode",
                    "user_id",
                    "fname",
                    "lname",
                    "cname",
                    "country_id",
                    "state_id",
                    "phone",
                    "is_default",
                ])
                    ->toArray()

            );

        if ($updatableData["is_default"]) {
            Address::where(
                "id",
                "!=",
                $id
            )
                ->update([
                    "is_default" => 0
                ]);
        }
        return response()->json(["ok" => true], 200);
    });

    Route::get('/v1.0/client/addresses', function (Request $request) {

        $data['data'] = Address::with("country","state")->where([
            "user_id" => $request->user()->id
        ])
            ->get();


        return response()->json($data, 201);
    });
    Route::post('/v1.0/client/account-details', function (AccountDetailsRequest $request) {

        $request_user = $request->user();
        //    $user = User::where([
        //     "email" => $request_user->email
        //    ])
        //    ->first();
        $insertableData = $request->validated();

        if (!Hash::check($insertableData["current_password"], $request_user->password)) {
            return response()->json(["message" => "current password not matching"], 403);
        }
        $request->user()->update([
            "password" => Hash::make($insertableData['password'])
        ]);

        return response()->json(["ok" => $request_user->password], 201);
    });
});





































































































// end of protected route
Route::get('/v1.0/shipping/calculate/price/{country_id}/{state_id}', [ShippingController::class, "calculateShipping"]);
Route::get('/v1.0/countries/all', [CountryController::class, "getAllCountry"]);
Route::get('/v1.0/states/get/country-id/{countryId}', [StateController::class, "getStateById"]);
Route::post('/v1.0/client/orders', [OrderController::class, "create"]);





Route::get('/v1.0/client/categories/all', [CategoryController::class, "getAllCategory"]);
Route::get('/v1.0/client/styles/all', [StyleController::class, "getAllStyle"]);
Route::get('/v1.0/client/colors/all', [ColorController::class, "getAllColor"]);
Route::get('/v1.0/client/products/{slug}', [ProductController::class, "getProductByIdClient"]);
Route::get('/v1.0/client/categories/search/exact/{term}', [CategoryController::class, "searchExactCategory"]);

Route::get('/v1.0/client/menus/all', [MenuController::class, "getAllMenu"]);

Route::get('/v1.0/client/products/pagination/{perPage}', [ProductController::class, "getProductPaginationClient"]);
Route::get('/v1.0/client/products/relatedproduct/get', [ProductController::class, "getRelatedProductClient"]);


Route::get('/v1.0/client/products/featured/all', [ProductController::class, "getFeatutedProductClient"]);


Route::get('/v1.0/client/check-height', function (Request $request) {
    $product =  ProductVariation::where(
        "name",
        ">=",
        $request->height
    )
        ->where(
            "product_id",
            "=",
            $request->product_id,

        )

        ->where(
            "color_id",
            "=",
            $request->color_id,
        )

        ->orderBy("name")
        ->first();
    return response()->json([
        "product" => $product
    ], 200);
});

Route::get('/v1.0/client/check-width', function (Request $request) {
    $product =  Variation::where(
        "name",
        ">=",
        $request->width
    )
        ->where(
            "product_variation_id",
            "=",
            $request->product_variation_id
        )
        ->orderBy("name")
        ->first();

    return response()->json([
        "product" => $product
    ], 200);
});

Route::get('/v1.0/client/check-coupon', function (Request $request) {

    $coupon = Coupon::with("cproducts")
        ->where([
            "code" => $request->coupon,
        ])


        ->first();

    return response()->json([
        "coupon" => $coupon
    ], 200);
});
