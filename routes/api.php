<?php
use App\Http\Controllers\Brand\ContactsController;
use App\Http\Controllers\BusinessAuthentications\AuthenticationController;
use App\Http\Controllers\BusinessOnboarding\BrandBusinessController;
use App\Http\Controllers\BusinessOnboarding\BrandKitController;
use App\Http\Controllers\BusinessOnboarding\CategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

Route::get('/test-email', function () {
    try {
        \Illuminate\Support\Facades\Mail::raw('Test email content', function ($message) {
            $message->to('jagadees.s@macincode.com')
                ->subject('Test Email');
        });
        return 'Email sent successfully';
    } catch (\Exception $e) {
        return 'Error sending email: ' . $e->getMessage();
    }
});

/* These for Brand & Crators/Professionals Registration, Login, verify Email to forgot password & Change password*/
Route::post("/brandRegistration", [AuthenticationController::class, "brandRegistration"]);
Route::post("/creatorsRegistration", [AuthenticationController::class, "creatorsRegistration"]);
Route::post("/creatorsLogin", [AuthenticationController::class, "creatorsLogin"]);
Route::post("/brandLogin", [AuthenticationController::class, "brandLogin"]);
Route::post("/forgotPassword", [AuthenticationController::class, "forgotPassword"]);

/* Creators service CRUD Operations */
Route::post('/storeCreatorService', [AuthenticationController::class, 'storeCreatorService']);
Route::get('/getCreatorServiceById/{id}', [AuthenticationController::class, 'getCreatorServiceById']);
Route::put('/updateCreatorService/{id}', [AuthenticationController::class, 'updateCreatorService']);
Route::delete('/deleteCreatorService/{id}', [AuthenticationController::class, 'deleteCreatorService']);
Route::ger('/getAllCreatorsServices', [AuthenticationController::class, 'getAllCreatorsServices']);

/* Whatsapp Settings CRUD Operations */
Route::post('/storeWhatsappReg', [AuthenticationController::class, 'storeWhatsappReg']);
Route::get('/getWhatsappRegById/{id}', [AuthenticationController::class, 'getWhatsappRegById']);
Route::put('/updateWhatsappReg/{id}', [AuthenticationController::class, 'updateWhatsappReg']);
Route::delete('/deleteWhatsappReg/{id}', [AuthenticationController::class, 'deleteWhatsappReg']);
Route::get('/getAllWhatsappReg', [AuthenticationController::class, 'getAllWhatsappReg']);

/* CRM Customers, Leads, Groups, Segments CRUD Operations */
Route::post("/addContact", [ContactsController::class, "addContact"]);
Route::post("/importBulkContacts", [ContactsController::class, "importBulkContacts"]);
Route::get('/contacts/brand/{brand_id}', [ContactsController::class, 'getContactsByBrandId']);
Route::put('/contacts/brand/update/{id}', [ContactsController::class, 'updateContact']);
Route::get('/contacts/{id}', [ContactsController::class, 'getContact']);
Route::get('/contacts', [ContactsController::class, 'getAllContacts']);

Route::post("/addGroups", [ContactsController::class, "addGroups"]);
Route::put("/editGroup/{id}", [ContactsController::class, "editGroup"]);
Route::delete("/deleteGroup/{id}", [ContactsController::class, "deleteGroup"]);
Route::get("/getGroups/{id?}", [ContactsController::class, "getGroups"]);

Route::post("/createSegment", [ContactsController::class, "createSegment"]);
Route::put("/updateSegment/{id}", [ContactsController::class, "updateSegment"]);
Route::delete("/deleteSegment/{id}", [ContactsController::class, "deleteSegment"]);
Route::get("/getSegments/{id?}", [ContactsController::class, "getSegments"]);

Route::post('/addLeads', [ContactsController::class, 'createLead']);
Route::get('/getLeads', [ContactsController::class, 'getLeads']);
Route::get('/getLeads/{id}', [ContactsController::class, 'getLeadById']);
Route::put('/updateLeads/{id}', [ContactsController::class, 'updateLead']);
Route::delete('/deleteLeads/{id}', [ContactsController::class, 'deleteLead']);
Route::get('/getLeads/brand/{id}', [ContactsController::class, 'getLeadsByBrandId']);
Route::post("/importBulkLeads", [ContactsController::class, "importBulkLeads"]);

/* Categories CRUD Operations */
Route::post('createCategory', [CategoryController::class, 'createCategory']);
Route::get('readCategory', [CategoryController::class, 'readCategory']);
Route::put('updateCategory', [CategoryController::class, 'updateCategory']);
Route::delete('deleteCategory', [CategoryController::class, 'deleteCategory']);

/* Sub Categories CRUD Operations */
Route::post('createSubCategory', [CategoryController::class, 'createSubCategory']);
Route::get('readSubCategory', [CategoryController::class, 'readSubCategory']);
Route::put('updateSubCategory', [CategoryController::class, 'updateSubCategory']);
Route::delete('deleteSubCategory', [CategoryController::class, 'deleteSubCategory']);

/* Business basic, Service, Products CRUD Operations */
Route::post('createBrandBusiness', [BrandBusinessController::class, 'createBrandBusiness']);
Route::get('readBrandBusiness', [BrandBusinessController::class, 'readBrandBusiness']);
Route::post('indexBrandBusiness', [BrandBusinessController::class, 'indexBrandBusiness']);
Route::put('updateBrandBusiness', [BrandBusinessController::class, 'updateBrandBusiness']);
Route::delete('deleteBrandBusiness', [BrandBusinessController::class, 'deleteBrandBusiness']);

Route::post('createBrandService', [BrandBusinessController::class, 'createBrandService']);
Route::get('readBrandService', [BrandBusinessController::class, 'readBrandService']);
Route::post('indexBrandService', [BrandBusinessController::class, 'indexBrandService']);
Route::put('updateBrandService', [BrandBusinessController::class, 'updateBrandService']);
Route::delete('deleteBrandService', [BrandBusinessController::class, 'deleteBrandService']);

Route::post('createBrandProducts', [BrandBusinessController::class, 'createBrandProducts']);
Route::get('readBrandProducts', [BrandBusinessController::class, 'readBrandProducts']);
Route::post('indexBrandProducts', [BrandBusinessController::class, 'indexBrandProducts']);
Route::put('updateBrandProducts', [BrandBusinessController::class, 'updateBrandProducts']);
Route::delete('deleteBrandProducts', [BrandBusinessController::class, 'deleteBrandProducts']);

/* Brand Kit CRUD Operations */
Route::post('createBrandKit', [BrandKitController::class, 'createBrandKit']);
Route::get('readBrandKit', [BrandKitController::class, 'readBrandKit']);
Route::post('indexBrandKit', [BrandKitController::class, 'indexBrandKit']);
Route::put('updateBrandKit', [BrandKitController::class, 'updateBrandKit']);
Route::delete('deleteBrandKit', [BrandKitController::class, 'deleteBrandKit']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
