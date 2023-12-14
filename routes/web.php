<?php

use App\Http\Livewire\Admin\Activity;
use App\Http\Livewire\CreateGoals;
use App\Http\Livewire\CreatePlan;
use App\Http\Livewire\CreateWeek;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\Exercise\Add;
use App\Http\Livewire\ExerciseHistory;
use App\Http\Livewire\Help;
use App\Http\Livewire\History;
use App\Http\Livewire\Log;
use App\Http\Livewire\Plan;
use App\Http\Livewire\Plan\SettingsExercises;
use App\Http\Livewire\PlanHistory;
use App\Http\Livewire\SkinFoldTest;
use App\Http\Livewire\SkinFoldTestPublic;
use App\Http\Livewire\Track;
use App\Http\Livewire\ViewExercises;
use App\Http\Livewire\ViewGoals;
use App\Http\Livewire\ViewPlan;
use App\Http\Livewire\ViewWeek;
use App\Http\Livewire\Welcome;
use App\Http\Livewire\Wilks;
use Illuminate\Support\Facades\Route;

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


Route::get('/', ['as' => 'welcome', 'uses' => Welcome::class]);
Route::get('/skinfold', ['as' => 'skinFoldTest', 'uses' => SkinFoldTest::class]);


Route::group(['middleware' => ['auth']], function () {
    //  Homepage Route - Redirect based on user role is in controller.
    Route::get('/dashboard/{dateSelected?}', ['as' => 'dashboard', 'uses' => Dashboard::class]);
    // Settings
    Route::get('/plan', ['as' => 'plan', 'uses' => Plan\Settings::class]);
    Route::get('/exercises', ['as' => 'exercises', 'uses' => SettingsExercises::class]);

    // Wilks
    Route::get('/wilks', ['as' => 'wilks', 'uses' => Wilks::class]);

    // Sub pages
    Route::get('/skinfold/{id?}', ['as' => 'skinFoldTest', 'uses' => SkinFoldTest::class]);
    Route::get('/track/{id?}', ['as' => 'track', 'uses' => Track::class]);
    Route::get('/history', ['as' => 'history', 'uses' => History::class]);

    // Dashboards have plural
    Route::get('/exercises/view/{dateRange?}', ['as' => 'viewExercises', 'uses' => ViewExercises::class]);
    //Route::get('/muscles/view', ['as' => 'viewExercises', 'uses' => ViewExercises::class]);

    Route::get('/exercise/view/{exerciseId}', ['as' => 'exercise', 'uses' => ExerciseHistory::class]);


    // Goal
    Route::get('/goals/create/{goalId?}', ['as' => 'createGoals', 'uses' => CreateGoals::class]);
    Route::get('/goals/view/{goalId?}', ['as' => 'viewGoals', 'uses' => ViewGoals::class]);


    // Plan
    Route::get('/plan/view/{planID?}', ['as' => 'viewPlan', 'uses' => ViewPlan::class]);
    Route::get('/plan/create/{planID?}', ['as' => 'createPlan', 'uses' => CreatePlan::class]);
    Route::get('/plan/history', ['as' => 'planHistory', 'uses' => PlanHistory::class]);

    // Week
    Route::get('/plan/{planID}/week/view/{weekID}', ['as' => 'viewWeek', 'uses' => ViewWeek::class]);
    Route::get('/week/create/{weekID?}', ['as' => 'createWeek', 'uses' => CreateWeek::class]);

    Route::get('/log/{date?}', ['as' => 'log', 'uses' => Log::class]);

    // Route::get('/add/{$id}', ['as' => 'add', 'uses' =>  Add::class]);

    Route::get('/exercise/add/{date?}', Add::class);
    Route::get('/plan/{planId}/week/{weekId}/workout/{workoutId}/exercise/{exerciseId}/', Add::class);
    Route::get('/plan/{planId}/week/{weekId}/workout/{workoutId}/exercise/{exerciseId}/reps/{reps?}/weight/{weight?}', Add::class);
    Route::get('/plan/{planId}/week/{weekId}/workout/{workoutId}/exercise/{exerciseId}/slot/{slot?}/reps/{reps?}/weight/{weight?}', Add::class);


    // Help
    Route::get('/help', ['as' => 'help', 'uses' => Help::class]);

    // Admin stuff
    Route::get('/admin/activity', ['as' => 'admin.activity', 'uses' => Activity::class]);

});
