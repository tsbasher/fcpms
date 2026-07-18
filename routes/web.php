<?php

use App\Http\Controllers\Admin\AdminBillController;
use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\Admin\BoqPartController;
use App\Http\Controllers\Admin\BoqItemController;
use App\Http\Controllers\Admin\BoqSubItemController;
use App\Http\Controllers\Admin\BoqVersionController;
use App\Http\Controllers\Admin\BoqVersionDetailsController;
use App\Http\Controllers\Admin\BoqVersionExportImportController;
use App\Http\Controllers\Admin\ContractorController;
use App\Http\Controllers\Admin\ContractorUserController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SchemeController;
use App\Http\Controllers\Admin\SchemeOptionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\Common\DistrictController;
use App\Http\Controllers\Common\RegionController;
use App\Http\Controllers\Common\UnionController;
use App\Http\Controllers\Common\UpazilaController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\User\UserHomeController;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// })->name('welcome');

// Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin'], function () {

    Route::get('/login', [LoginController::class, 'admin_login'])->name('admin.login');
    Route::post('/login', [LoginController::class, 'admin_login_post'])->name('admin.login_post');

    Route::group(['middleware' => ['admin']], function () {
        Route::get('/logout', [LoginController::class, 'admin_logout'])->name('admin.logout');

        Route::get('/', [AdminHomeController::class, 'index'])->name('admin.home');
        Route::get('/check-existing-boq-version-details', [BoqVersionDetailsController::class, 'existingBoqVersionDetails'])->name('admin.check_existing_boq_version_details');
        Route::get('/boq-version-details/copy/{id}', [BoqVersionDetailsController::class, 'copy'])->name('admin.boq_version_details.copy');
        Route::get('/boq-version-export-import', [BoqVersionExportImportController::class, 'index'])->name('admin.boq_version_export_import.index');
        Route::get('/boq-version-export-import/export/{version_id}', [BoqVersionExportImportController::class, 'export'])->name('admin.boq_version_export_import.export');
        Route::get('/contractors/add-package/{contractor_id}', [ContractorController::class, 'add_package'])->name('admin.contractors.add_package');
        Route::post('/contractors/add-package/{contractor_id}', [ContractorController::class, 'store_package'])->name('admin.contractors.store_package');
        Route::get('/boq-versions/export', [BoqVersionController::class, 'export'])->name('admin.boq_versions.export');
        Route::get('/boq-versions/export-data', [BoqVersionController::class, 'export_data'])->name('admin.boq_versions.export_data');
        Route::get('/bills-by-package/{package_id}', [AdminBillController::class, 'getBillsByPackage'])->name('admin.bills.get_bills_by_package');
        Route::get('bill/details/shelter-wise-details', [AdminBillController::class, 'bill_show'])->name('admin.bills.shelter_wise_view');


        Route::resource('regions', RegionController::class)->names('admin.regions');
        Route::resource('projects', ProjectController::class)->names('admin.projects');
        Route::resource('packages', PackageController::class)->names('admin.packages');
        Route::resource('scheme-options', SchemeOptionController::class)->names('admin.scheme_options');
        Route::resource('schemes', SchemeController::class)->names('admin.schemes');
        Route::resource('units', UnitController::class)->names('admin.units');
        Route::resource('boq-parts', BoqPartController::class)->names('admin.boq_parts');
        Route::resource('boq-items', BoqItemController::class)->names('admin.boq_items');
        Route::resource('boq-sub-items', BoqSubItemController::class)->names('admin.boq_sub_items');
        Route::resource('boq-versions', BoqVersionController::class)->names('admin.boq_versions');
        Route::resource('boq-version-details', BoqVersionDetailsController::class)->names('admin.boq_version_details');
        Route::resource('contractors', ContractorController::class)->names('admin.contractors');
        Route::resource('contractor-users', ContractorUserController::class)->names('admin.contractor_users');
        Route::resource('bills', AdminBillController::class)->names('admin.bills');
    });
});



Route::group(['prefix' => 'common'], function () {
    Route::get('/get-districts-by-division/{division_id}', [DistrictController::class, 'getDistrictsByDivision'])->name('common.get_districts_by_division');
    Route::get('/get-upazilas-by-district/{district_id}', [UpazilaController::class, 'getUpazilasByDistrict'])->name('common.get_upazilas_by_district');
    Route::get('/get-unions-by-upazila/{upazila_id}', [UnionController::class, 'getUnionsByUpazila'])->name('common.get_unions_by_upazila');
    Route::get('/get-boq-items-by-part/{boq_part_id}', [BoqItemController::class, 'getBoqItemsByPart'])->name('common.get_boq_items_by_part');
    Route::get('/get-boq-sub-item-by-boq-items/{boq_item_id}', [BoqSubItemController::class, 'getBoqSubItemsByBoqItem'])->name('common.get_boq_sub_items_by_boq_item');
    Route::get('/get-boq-version-by-boq-package/{package_id}', [BoqVersionController::class, 'getBoqVersionsByPackage'])->name('common.get_boq_versions_by_package');
    Route::get('/get-unit-by-boq-item/{boq_item_id}', [UnitController::class, 'getUnitByBoqItem'])->name('common.get_unit_by_boq_item');
    Route::get('/get-unit-by-boq-sub-item/{boq_sub_item_id}', [UnitController::class, 'getUnitByBoqSubItem'])->name('common.get_unit_by_boq_sub_item');

    Route::get('/get-bill-boq-part-by-bill-scheme/{bill_id}/{scheme_id}', [BoqPartController::class, 'getBillBoqPartbyscheme'])->name('common.get_bill_boq_part_by_scheme');

    Route::get('/get-bill-boq-items-by-bill-part/{bill_id}/{scheme_id}/{boq_part_id}', [BoqItemController::class, 'getBillBoqItemsByPart'])->name('common.get_bill_boq_items_by_part');
});


Route::group(['prefix' => ''], function () {
    Route::get('/login', [LoginController::class, 'user_login'])->name('user.login');
    Route::post('/login', [LoginController::class, 'user_login_post'])->name('user.login_post');
    Route::group(['middleware' => ['auth']], function () {
        Route::get('/logout', [LoginController::class, 'user_logout'])->name('user.logout');
        Route::get('/', [UserHomeController::class, 'index'])->name('user.home');

        Route::get('/bill/details/scheme/{id}', [BillController::class, 'show_scheme'])->name('user.bills.details.scheme');
        // Route::get('/bills/add-scheme/{bill_id}', [BillController::class, 'addScheme'])->name('user.bills.add_scheme');
        Route::post('/bills/add-scheme/{bill_id}', [BillController::class, 'storeScheme'])->name('user.bills.store_scheme');
        Route::get('/bills/remove-scheme/{scheme_id}/{bill_id}', [BillController::class, 'removeScheme'])->name('user.bills.remove_scheme');

        Route::get('/bill/details/boq-part/{id}', [BillController::class, 'show_boq_part'])->name('user.bills.details.boq_part');
        // Route::get('/bills/add-boq_parts/{bill_id}', [BillController::class, 'addBoqPart'])->name('user.bills.add_boq_parts');
        Route::post('/bills/add-boq_parts/{bill_id}', [BillController::class, 'storeBoqPart'])->name('user.bills.store_boq_parts');
        Route::get('/bills/remove-boq-part/{id}/{bill_id}', [BillController::class, 'removeBoqPart'])->name('user.bills.remove_boq_part');


        Route::get('/bill/details/boq-item/{id}', [BillController::class, 'show_boq_item'])->name('user.bills.details.boq_item');
        // Route::get('/bills/add-boq_items/{bill_id}', [BillController::class, 'addBoqItem'])->name('user.bills.add_boq_items');
        Route::post('/bills/add-boq_items/{bill_id}', [BillController::class, 'storeBoqItem'])->name('user.bills.store_boq_items');
        Route::get('/bills/remove-boq-item/{id}/{bill_id}', [BillController::class, 'removeBoqItem'])->name('user.bills.remove_boq_item');



        Route::get('/bill/details/boq_subitem/{id}', [BillController::class, 'show_boq_subitem'])->name('user.bills.details.boq_subitem');
        // Route::get('/bills/add-boq_subitems/{bill_id}', [BillController::class, 'addBoqSubItem'])->name('user.bills.add_boq_subitems');
        Route::post('/bills/add-boq_subitems/{bill_id}', [BillController::class, 'storeBoqSubItem'])->name('user.bills.store_boq_subitems');
        Route::get('/bills/remove-boq-subitem/{id}/{bill_id}', [BillController::class, 'removeBoqSubItem'])->name('user.bills.remove_boq_subitem');



        Route::get('/bill/details/measurement/{id}', [BillController::class, 'show_measurement'])->name('user.bills.details.measurement');
        Route::post('/bill/details/add-measurement/{bill_id}', [BillController::class, 'storeMeasurement'])->name('user.bills.store_measurement');
        Route::get('/bill/details/remove-measurement/{id}/{bill_id}', [BillController::class, 'removeMeasurement'])->name('user.bills.remove_measurement');
        Route::get('/bill/details/unit-wise-view/{id}', [BillController::class, 'unitWiseView'])->name('user.bills.unit_wise_view');

        Route::get('bill/details/shelter-wise/{id}', [BillController::class, 'shelterWiseView'])->name('user.bills.shelter_wise_view');
        Route::get('bill/details', [BillController::class, 'report'])->name('user.bills.report');

        Route::get('bill/details/shelter-wise-details', [BillController::class, 'report_show'])->name('user.bills.shelter_wise_view');

        Route::get('bill/get-measurement-suggestions', [BillController::class, 'getMeasurementSuggestions'])->name('user.bills.get_measurement_suggestions');

        Route::resource('bills', BillController::class)->names('user.bills');
    });
});
