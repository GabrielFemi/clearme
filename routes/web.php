<?php

use App\Http\Controllers\Admin\CompleteOnboardingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
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


Route::get('/', function () {
    return redirect('/dashboard');
})->middleware(['auth']);

/** @var \Illuminate\Routing\Router $router */

$router->group(['prefix' => 'student', 'middleware' => ['auth', 'role:student']], function () use ($router) {
    $router->get('dashboard', [\App\Http\Controllers\Student\StudentController::class, 'dashboard'])->name('student.dashboard');
});

$router->group(['prefix' => 'clearance', 'middleware' => ['auth', 'role:student']], function () use ($router) {
    $router->get('student-affairs', function () {
        return "Student affairs!";
    });
    $router->get('ict-unit', function () {
        return "ICT Unit!";
    });
    $router->get('laboratories', function () {
        return "Laboratories";
    });
    $router->get('hall-of-residence', function () {
        return "Hall of residence";
    });
    $router->get('head-of-department', function () {
        return "Head of department";
    });
    $router->get('sports-division', function () {
        return "Sports division";
    });
    $router->get('security-unit', function () {
        return "Security unit";
    });
    $router->get('medicals', function () {
        return "Medicals";
    });
    $router->get('bursary-department', function () {
        return "Bursary department";
    });
});

$router->group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin']], function () use ($router) {
    $router->get('dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('admin.dashboard')->middleware('has_admin_completed_onboarding');
    $router->get('complete-onboarding', [CompleteOnboardingController::class, 'index'])->name('admin.complete-onboarding');
    $router->post('complete-onboarding', [CompleteOnboardingController::class, 'store']);
});

$router->get('profile', [ProfileController::class, 'index'])->middleware('auth');
$router->post('profile', [ProfileController::class, 'store']);

// Automatic redirect
$router->get('dashboard', function () {
    if (!Auth::check()) {
        return redirect(\route('login'));
    }

    if (Auth::user()->hasRole('admin')) {
        return redirect(\route('admin.dashboard'));
    };

    return redirect(\route('student.dashboard'));
});

Auth::routes();

$router->get('test', function () {
    return new \App\Mail\NewAccountEmailVerificationMail(['ea']);
});
