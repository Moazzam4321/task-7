<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\ImageController;
use App\Http\Middleware\Auth;
use App\Http\Middleware\is_authenticated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
      //----------- SignUp Route---------
 Route::post('/registration', [ClientController::class, 'signUp'])->name('register.post'); 
  // ------------- Email Verified Route --------- 
  Route::get('account/verify/{token}', [ClientController::class, 'emailVerify'])->name('user.verify'); 
  Route::post('/login', [ClientController::class, 'postLogin'])->name('login.post'); 
     // ----------- Forgot Password Raoute ---------
 Route::post('/forgot', [ClientController::class, 'forgotPassword'])->name('forgotPassword.post'); 
     // ----------- Reset Password Route ---------
 Route::post('/reset', [UserController::class, 'passwordReset'])->name('reset.password');
      //----------- Update Route---------
Route::post('/update', [Dashboard::class, 'profile_update'])->name('register.post')->middleware(is_authenticated::class); 
     //----------- UploadImage Route---------
 Route::post('/upload', [ImageController::class, 'upload_image'])->name('register.post')->middleware(is_authenticated::class);
    //----------- Logout Route---------
Route::post('/logout', [Dashboard::class, 'profile_update'])->name('register.post')->middleware(is_authenticated::class); 
    //----------- List all images in dashboard Route---------
 Route::get('/show/image', [ImageController::class, 'profile_update'])->name('register.post')->middleware(Auth::class); 
   //----------- Remove Image Route---------
Route::post('/remove', [ImageController::class, 'destroy'])->name('register.post')->middleware(Auth::class); 
    //----------- View Image Link Route---------
Route::get('/Share/Link/id', [ImageController::class, 'Link_view'])->name('register.post')->middleware(is_authenticated::class);
     //----------- Ask user email for whom to share picture Link Route---------
Route::get('/Share/id/email', [ImageController::class, 'Link_view'])->name('register.post')->middleware(is_authenticated::class);
      //----------- Ask user email for whom to share picture Link Route---------
Route::get('/Share/id/email/pass', [ImageController::class, 'verify_image'])->name('register.post');
       //----------- Logout Route---------
Route::get('/search', [ImageController::class, 'search'])->name('register.post')->middleware(is_authenticated::class); 


