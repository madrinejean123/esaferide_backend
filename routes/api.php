<?php

use App\Http\Controllers\Api\Admin\DriverController;
use App\Http\Controllers\Api\Admin\StudentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CampusPlaceController;
use App\Http\Controllers\Api\DriverProfileController;
use App\Http\Controllers\Api\FareSettingController;
use App\Http\Controllers\Api\FavouriteController;
use App\Http\Controllers\Api\StudentProfileController;
use App\Http\Controllers\Api\TripController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/link-firebase', [AuthController::class, 'linkFirebase']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/student/profile', [StudentProfileController::class, 'show']);
    Route::put('/student/profile', [StudentProfileController::class, 'update']);
    Route::post('/student/profile/photo', [StudentProfileController::class, 'uploadPhoto']);

    Route::get('/driver/profile', [DriverProfileController::class, 'show']);
    Route::put('/driver/profile', [DriverProfileController::class, 'update']);
    Route::post('/driver/profile/document', [DriverProfileController::class, 'uploadDocument']);

    Route::get('/fare-settings', [FareSettingController::class, 'show']);

    Route::get('/trips', [TripController::class, 'index']);
    Route::post('/trips', [TripController::class, 'store']);

    Route::get('/favourites', [FavouriteController::class, 'index']);
    Route::post('/favourites', [FavouriteController::class, 'store']);
    Route::delete('/favourites/{favourite}', [FavouriteController::class, 'destroy']);

    Route::get('/campus-places', [CampusPlaceController::class, 'index']);

    Route::middleware('admin')->group(function () {
        Route::put('/fare-settings', [FareSettingController::class, 'update']);
        Route::post('/campus-places', [CampusPlaceController::class, 'store']);
        Route::delete('/campus-places/{campusPlace}', [CampusPlaceController::class, 'destroy']);

        Route::prefix('admin')->group(function () {
            Route::get('/students', [StudentController::class, 'index']);
            Route::post('/students/{student}/suspend', [StudentController::class, 'suspend']);
            Route::post('/students/{student}/unsuspend', [StudentController::class, 'unsuspend']);

            Route::get('/drivers', [DriverController::class, 'index']);
            Route::post('/drivers/{driver}/verify', [DriverController::class, 'verify']);
            Route::post('/drivers/{driver}/reject', [DriverController::class, 'reject']);
            Route::post('/drivers/{driver}/suspend', [DriverController::class, 'suspend']);
            Route::post('/drivers/{driver}/unsuspend', [DriverController::class, 'unsuspend']);
        });
    });
});
