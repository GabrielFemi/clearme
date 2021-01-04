<?php

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
    return view('welcome');
});

/** @var \Illuminate\Routing\Router $router */

$router->group(['prefix' => 'student', 'middleware' => ['auth', 'role:student']], function() use ($router){
    $router->get('dashboard', [\App\Http\Controllers\Student\StudentController::class, 'dashboard'])->name('student.dashboard');
});

$router->group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin']], function() use ($router){
    $router->get('dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('admin.dashboard');
});

$router->get('profile', [ProfileController::class, 'index'])->middleware('auth');
$router->post('profile', [ProfileController::class, 'store']);

// Automatic redirect
$router->get('dashboard', function () {
    if(! Auth::check()) {
        return redirect(\route('login'));
    }

    if(Auth::user()->hasRole('admin')){
        return redirect(\route('admin.dashboard'));
    };

    return redirect(\route('student.dashboard'));
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
