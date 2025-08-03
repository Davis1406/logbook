<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\TypeFormController;
use App\Http\Controllers\Setting;
use App\Http\Controllers\TraineeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\TrainingProgrammeController;
use App\Http\Controllers\RotationController;
use App\Http\Controllers\ObjectiveController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

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

/** for side bar menu active */
function set_active( $route ) {
    if( is_array( $route ) ){
        return in_array(Request::path(), $route) ? 'active' : '';
    }
    return Request::path() == $route ? 'active' : '';
}

Route::get('/', function () {
    return view('auth.login');
});

Route::group(['middleware'=>'auth'],function()
{
    Route::get('home',function()
    {
        return view('home');
    });
    Route::get('home',function()
    {
        return view('home');
    });
});

Auth::routes();

// ----------------------------login ------------------------------//
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
    Route::post('change/password', 'changePassword')->name('change/password');
});

// ----------------------------- register -------------------------//
Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'register')->name('register');
    Route::post('/register','storeUser')->name('register');    
});

// -------------------------- main dashboard ----------------------//
Route::controller(HomeController::class)->group(function () {
    Route::get('/home', 'index')->middleware('auth')->name('home');
    Route::get('user/profile/page', 'userProfile')->middleware('auth')->name('user/profile/page');
    Route::get('supervisor/dashboard', 'teacherDashboardIndex')->middleware('auth')->name('supervisor/dashboard');
    Route::get('traineee/dashboard', 'studentDashboardIndex')->middleware('auth')->name('trainee/dashboard');
});

// ----------------------------- user controller -------------------------//
Route::controller(UserManagementController::class)->group(function () {
    Route::get('add/user', 'createUser')->middleware('auth')->name('add/user');
    Route::post('store/user', 'storeUser')->middleware('auth')->name('store/user');
    Route::get('list/users', 'index')->middleware('auth')->name('list/users');
    Route::post('change/password', 'changePassword')->name('change/password');
    Route::get('view/user/edit/{id}', 'userView')->middleware('auth');
    Route::post('user/update', 'userUpdate')->name('user/update');
    Route::post('user/delete', 'userDelete')->name('user/delete');
});

// ------------------------ setting -------------------------------//
Route::controller(Setting::class)->group(function () {
    Route::get('setting/page', 'index')->middleware('auth')->name('setting/page');
});

// ------------------------ trainees -------------------------------//
Route::controller(TraineeController::class)->group(function () {
    Route::get('trainees/list', 'trainees')->middleware('auth')->name('trainees/list'); // list trainees
    Route::get('trainees/grid', 'traineestGrid')->middleware('auth')->name('trainees/grid'); // grid trainees
    Route::get('trainees/add/page', 'traineeAdd')->middleware('auth')->name('trainee/add'); // add trainee
    Route::post('trainees/add/save', 'traineeSave')->name('trainee/add/save'); // save record trainee
    Route::get('trainees/edit-trainee/{id}', 'traineesEdit')->middleware('auth')->name('trainees.edit');
    Route::post('trainees/update', 'traineeUpdate')->name('trainee/update'); // update record student
    Route::post('trainees/delete', 'studentDelete')->name('trainees.delete'); // delete record student
    Route::get('trainees/profile/{id}', 'studentProfile')->middleware('auth'); // profile student

});

// ------------------------ Supervisor -------------------------------//
Route::controller(SupervisorController::class)->group(function () {
    Route::get('supervisor/add/page', 'addSupervisor')->middleware('auth')->name('supervisor/add'); // page supervisor
    Route::get('supervisors/list', 'supervisorList')->middleware('auth')->name('supervisors/list'); // page supervisor
    Route::get('supervisor/grid/page', 'supervisorGrid')->middleware('auth')->name('supervisor/grid/page'); // page grid supervisor
    Route::post('supervisor/save', 'saveRecord')->middleware('auth')->name('supervisor/save'); // save record
    Route::get('supervisor/edit/{id}', 'editSupervisor'); // view supervisor record
    Route::post('supervisor/update', 'updateRecordSupervisor')->middleware('auth')->name('supervisor/update'); // update record
    Route::post('supervisor/delete', 'supervisorDelete')->name('supervisor/delete'); // delete record 
});

// ----------------------- hospitals -----------------------------//
Route::controller(HospitalController::class)->group(function () {
    Route::get('hospital/list/page', 'hospitalList')->middleware('auth')->name('hospital/list/page');
    Route::get('hospital/add/page', 'indexHospital')->middleware('auth')->name('hospital/add/page');
    Route::post('hospital/store', 'storeHospital')->middleware('auth')->name('hospital/store');
    Route::get('hospital/edit/{id}', 'editHospital')->middleware('auth')->name('hospital/edit');
    Route::post('hospital/update/{id}', 'updateHospital')->middleware('auth')->name('hospital/update');
    Route::get('hospital/delete/{id}', 'deleteHospital')->middleware('auth')->name('hospital/delete');
});


// ----------------------- training programmes -----------------------------//
Route::controller(TrainingProgrammeController::class)->group(function () {
    Route::get('training-programmes/list', 'list')->middleware('auth')->name('training-programmes.list');
    Route::get('training-programmes/add', 'create')->middleware('auth')->name('training-programmes.add');
    Route::post('training-programmes/store', 'store')->middleware('auth')->name('training-programmes.store');
    Route::get('training-programmes/edit/{id}', 'edit')->middleware('auth')->name('training-programmes.edit');
    Route::post('training-programmes/update/{id}', 'update')->middleware('auth')->name('training-programmes.update');
    Route::get('training-programmes/delete/{id}', 'destroy')->middleware('auth')->name('training-programmes.delete');
});


// ----------------------- rotations -----------------------------//
Route::controller(RotationController::class)->group(function () {
    Route::get('rotations/list', 'list')->middleware('auth')->name('rotations.list');
    Route::get('rotations/add', 'create')->middleware('auth')->name('rotations.add');
    Route::post('rotations/store', 'store')->middleware('auth')->name('rotations.store');
    Route::get('rotations/edit/{id}', 'edit')->middleware('auth')->name('rotations.edit');
    Route::post('rotations/update/{id}', 'update')->middleware('auth')->name('rotations.update');
    Route::get('rotations/delete/{id}', 'destroy')->middleware('auth')->name('rotations.delete');
});

// ----------------------- objectives -----------------------------//
Route::controller(ObjectiveController::class)->group(function () {
    Route::get('objectives/list', 'list')->middleware('auth')->name('objectives.list');
    Route::get('objectives/add', 'create')->middleware('auth')->name('objectives.add');
    Route::post('objectives/store', 'store')->middleware('auth')->name('objectives.store');
    Route::get('objectives/edit/{id}', 'edit')->middleware('auth')->name('objectives.edit');
    Route::post('objectives/update/{id}', 'update')->middleware('auth')->name('objectives.update');
    Route::get('objectives/delete/{id}', 'destroy')->middleware('auth')->name('objectives.delete');
});

// ----------------------- operations -----------------------------//
Route::controller(OperationController::class)->group(function () {
    // Admin/Trainee List Views
    Route::get('operations/list', 'list')->middleware('auth')->name('operations/list');
    
    // Trainee: Add / Edit Own
    Route::get('operations/add', 'create')->middleware('auth')->name('operations/add');
    Route::post('operations/store', 'store')->middleware('auth')->name('operations/store');
    Route::get('operations/edit/{id}', 'edit')->middleware('auth')->name('operations/edit');
    Route::post('operations/update/{id}', 'update')->middleware('auth')->name('operations/update');

    // Supervisor: Approve/Reject Operation
    Route::get('operations/approve/{id}', 'approve')->middleware('auth')->name('operations/approve');
    Route::get('operations/reject/{id}', 'reject')->middleware('auth')->name('operations/reject');

    // Common: View & Delete
    Route::get('operation/view/{id}', 'view')->middleware('auth')->name('operation/view');
    Route::get('operations/delete/{id}', 'destroy')->middleware('auth')->name('operations/delete');
});
