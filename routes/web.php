<?php

use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\Admin\BoqPartController;
use App\Http\Controllers\Admin\BoqItemController;
use App\Http\Controllers\Admin\BoqSubItemController;
use App\Http\Controllers\Admin\BoqVersionController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SchemeController;
use App\Http\Controllers\Admin\SchemeOptionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Common\DistrictController;
use App\Http\Controllers\Common\UnionController;
use App\Http\Controllers\Common\UpazilaController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\User\UserHomeController;
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
        Route::resource('projects', ProjectController::class)->names('admin.projects');
        Route::resource('packages', PackageController::class)->names('admin.packages');
        Route::resource('scheme-options', SchemeOptionController::class)->names('admin.scheme_options');
        Route::resource('schemes', SchemeController::class)->names('admin.schemes');
        Route::resource('units', UnitController::class)->names('admin.units');
        Route::resource('boq-parts', BoqPartController::class)->names('admin.boq_parts');
        Route::resource('boq-items', BoqItemController::class)->names('admin.boq_items');
        Route::resource('boq-sub-items', BoqSubItemController::class)->names('admin.boq_sub_items');
        Route::resource('boq-versions', BoqVersionController::class)->names('admin.boq_versions');
    });
});



Route::group(['prefix' => 'common'], function () {
Route::get('/get-districts-by-division/{division_id}', [DistrictController::class, 'getDistrictsByDivision'])->name('common.get_districts_by_division');
Route::get('/get-upazilas-by-district/{district_id}', [UpazilaController::class, 'getUpazilasByDistrict'])->name('common.get_upazilas_by_district');
Route::get('/get-unions-by-upazila/{upazila_id}', [UnionController::class, 'getUnionsByUpazila'])->name('common.get_unions_by_upazila');
Route::get('/get-boq-items-by-part/{boq_part_id}', [BoqItemController::class, 'getBoqItemsByPart'])->name('common.get_boq_items_by_part');
Route::get('/get-boq-sub-item-by-boq-items/{boq_item_id}', [BoqSubItemController::class, 'getBoqSubItemsByBoqItem'])->name('common.get_boq_sub_items_by_boq_item');
});


Route::group(['prefix' => ''], function () {
    Route::get('/login', [LoginController::class, 'user_login'])->name('user.login');
    Route::post('/login', [LoginController::class, 'user_login_post'])->name('user.login_post');
    Route::group(['middleware' => ['auth']], function () {
            Route::get('/logout', [LoginController::class, 'user_logout'])->name('user.logout');
        Route::get('/', [UserHomeController::class, 'index'])->name('user.home');
    });
});
