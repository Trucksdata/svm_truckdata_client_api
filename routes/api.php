<?php

use App\Http\Controllers\ManufacturerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommonFaqsController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\DataCollectionController;
use App\Http\Controllers\EnergySourceController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\HomePageSettingController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\SpecificationCategoryController;
use App\Http\Controllers\SpecificationController;
use App\Http\Controllers\SpecificationOptionController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleSpecController;
use App\Http\Controllers\VehicleSpecValueController;
use App\Http\Controllers\VehicleTypeController;
use App\Http\Controllers\VehicleTypeSpecificationController;

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


Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::get('user', 'me');
    Route::post('logout', 'logout');
});

Route::apiResource('manufacturers', ManufacturerController::class);
Route::apiResource('energy-sources', EnergySourceController::class);
Route::apiResource('vehicle-types', VehicleTypeController::class);
Route::apiResource('specification-categories', SpecificationCategoryController::class);
Route::apiResource('specifications', SpecificationController::class);
Route::apiResource('specification-options', SpecificationOptionController::class);
Route::apiResource('file-upload', FileUploadController::class);
Route::apiResource('vehicle-type/specifications', VehicleTypeSpecificationController::class)->parameters([
    'specifications' => 'vehicleTypeSpecification'
]);;
Route::apiResource('series', SeriesController::class);
Route::apiResource('vehicles', VehicleController::class);
Route::apiResource('vehicles/{vehicle}/vehicle-specs', VehicleSpecController::class);
Route::apiResource('vehicle-specs/{vehicleSpec}/values', VehicleSpecValueController::class);

Route::controller(CompareController::class)->prefix('compare')->group(function () {
    Route::get('/vehicles', 'getMappedVehicles');
});

Route::apiResource('blog', BlogController::class);
Route::apiResource('home-page-settings', HomePageSettingController::class);
Route::apiResource('data-collection', DataCollectionController::class);
Route::apiResource('common-faqs', CommonFaqsController::class);
Route::post('import', [ImportController::class,'import']);
