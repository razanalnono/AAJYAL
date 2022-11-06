<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Dashboard\RateController;
use App\Http\Controllers\API\Dashboard\AdminController;
use App\Http\Controllers\API\Dashboard\CitiesController;

use App\Http\Controllers\API\Dashboard\CourseController;


use App\Http\Controllers\API\Dashboard\GroupsController;
use App\Http\Controllers\API\LandingPage\InfoController;
use App\Http\Controllers\API\LandingPage\NewsController;
use App\Http\Controllers\API\LandingPage\PageController;
use App\Http\Controllers\API\Auth\AccessTokensController;
use App\Http\Controllers\API\Dashboard\TraineeController;
use App\Http\Controllers\API\Dashboard\TrainerController;
use App\Http\Controllers\API\Dashboard\ProjectsController;
use App\Http\Controllers\API\LandingPage\SocialController;
use App\Http\Controllers\API\Dashboard\PlatformsController;
use App\Http\Controllers\API\LandingPage\ContactController;
use App\Http\Controllers\API\LandingPage\OurWorkController;
use App\Http\Controllers\API\Dashboard\FinanciersController;
use App\Http\Controllers\API\LandingPage\ProgramsController;
use App\Http\Controllers\API\Dashboard\AttendencesController;
use App\Http\Controllers\API\Dashboard\AchievementsController;
use App\Http\Controllers\API\Dashboard\PresenceAbsencesController;
use App\Http\Controllers\API\Dashboard\FinanciersProjectsController;
use App\Http\Controllers\Api\GlobalNotificationController;

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
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });




Route::post('/login/{type}', [AccessTokensController::class, 'login']);
Route::post('changePassword/{type}',[AccessTokensController::class,'changePassword']);
//Route::post('/verify', [AccessTokensController::class, 'verify']);


Route::group(['prefix' => '/dashboard'], function () {
    Route::apiResource('/admin', AdminController::class);
    Route::post('/admin/{admin}', [AdminController::class, 'update']);

    Route::apiResource('/trainee', TraineeController::class);
    Route::post('/trainee/{trainee}', [TraineeController::class,'update']);

    Route::apiResource('/trainer', TrainerController::class);
    Route::post('/trainer/{trainer}', [TrainerController::class, 'update']);
});

Route::apiResource('courses', CourseController::class);
Route::apiResource('/news', NewsController::class);
Route::post('/news/{news}', [NewsController::class, 'update']);
Route::apiResource('/page', PageController::class);
Route::post('/page/{page}', [PageController::class, 'update']);
Route::apiResource('/ourWork', OurWorkController::class);
Route::post('/ourWork/{work}', [OurWorkController::class, 'update']);
Route::apiResource('/contact', ContactController::class);
Route::apiResource('/info', InfoController::class);
Route::apiResource('/programs', ProgramsController::class);
Route::post('/programs/{program}', [ProgramsController::class, 'update']);
Route::apiResource('/social', SocialController::class);
Route::post('/social/{social}', [SocialController::class, 'update']);
Route::apiResource('/projects', ProjectsController::class);
Route::apiResource('/groups', GroupsController::class);

// Route::post('/deleteTraineeFromGroup', [GroupsController::class, 'destroyTrainees']);
// Route::apiResource('/achievements', AchievementsController::class);
// Route::post('/achievements/{achievement}', [AchievementsController::class, 'update']);

// Route::apiResource('/attendences', AttendencesController::class);


// Route::get('get-presence_absence-for-course', [PresenceAbsencesController::class, 'index']);
// Route::post('store-presence_absence-for-course', [PresenceAbsencesController::class, 'store']);
// Route::put('update-presence_absence-for-course/{id}', [PresenceAbsencesController::class, 'update']);

// Route::apiResource('/cities', CitiesController::class);

// Route::apiResource('/trainees', TraineeController::class);
// Route::post('/trainees/{trainee}', [TraineeController::class, 'update']);
// Route::post('/import-excel', [GroupsController::class, 'import']);
// Route::apiResource('rates', RateController::class);
// Route::apiResource('platforms', PlatformsController::class);
// Route::apiResource('financiers', FinanciersController::class);
// Route::post('/financiers/{financier}', [FinanciersController::class, 'update']);
// Route::get('show-avaliable-financiers', [FinanciersProjectsController::class, 'showFinanciers']);
// Route::post('add-financiers', [FinanciersProjectsController::class, 'store']);

// Route::post('send_notification',[GlobalNotificationController::class,'send']);
Route::post('send',[GlobalNotificationController::class,'send']);


// Route::middleware('auth:sanctum')->group(['prefix' => '/dashboard'], function () {
    
//     Route::apiResource('/admin', AdminController::class);
//     Route::post('/admin/{admin}', [AdminController::class, 'update']);

//     Route::apiResource('/trainee', TraineeController::class);
//     Route::post('/trainee/{trainee}', [TraineeController::class, 'update']);

//     Route::apiResource('/trainer', TrainerController::class);
//     Route::post('/trainer/{trainer}', [TrainerController::class, 'update']);

//     Route::apiResource('/cities', CitiesController::class);

//     Route::post('/import-excel', [GroupsController::class, 'import']);
//     Route::apiResource('rates', RateController::class);
//     Route::apiResource('platforms', PlatformsController::class);
//     Route::apiResource('financiers', FinanciersController::class);
//     Route::post('/financiers/{financier}', [FinanciersController::class, 'update']);
//     Route::get('show-avaliable-financiers', [FinanciersProjectsController::class, 'showFinanciers']);
//     Route::post('add-financiers', [FinanciersProjectsController::class, 'store']);
//     Route::get('get-presence_absence-for-course', [PresenceAbsencesController::class, 'index']);
//     Route::post('store-presence_absence-for-course', [PresenceAbsencesController::class, 'store']);
//     Route::put('update-presence_absence-for-course/{id}', [PresenceAbsencesController::class, 'update']);
//     Route::post('/deleteTraineeFromGroup', [GroupsController::class, 'destroyTrainees']);
//     Route::apiResource('/achievements', AchievementsController::class);
//     Route::post('/achievements/{achievement}', [AchievementsController::class, 'update']);

//     Route::apiResource('/attendences', AttendencesController::class);
// });