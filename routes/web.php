<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UsersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*
In this code i enclosed the admin routes using a route group of /admin
and applied the auth middleware to those routes
so only the logged in users can view those pages.
*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('/', function () {
        // return view('welcome');
        return view('pages.home.index');
    });

    Route::resources([
        '/users' => UsersController::class
    ]);

    Route::get('/my-profile', [UsersController::class, 'getProfile']);
    Route::get('/my-profile/edit', [UsersController::class, 'getEditProfile']);
    Route::patch('/my-profile/edit', [UsersController::class, 'postEditProfile']);

    Route::get('/forbidden', function() {
        return view('pages.forbidden.forbidden_area');
    });
});

// The default route “/” to redirect to the /admin route.
Route::get('/', function () {
    return redirect()->to('/admin');
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
