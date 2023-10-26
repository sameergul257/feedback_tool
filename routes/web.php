<?php

use App\Http\Controllers\FeedbackController;
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
    return redirect()->route('login');
});

Auth::routes();

Route::prefix('/feedback')->name('feedback.')->middleware('auth')->controller(FeedbackController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/create', 'store')->name('store');
    Route::get('/view/{id}', 'view')->name('view');
    Route::post('/vote', 'vote')->name('vote');
    Route::post('/addcomment', 'add_comment')->name('add_comment');
    Route::post('/getcommentslist', 'get_comments_list')->name('get_comments_list');
    Route::middleware('checkuserrole:admin')->group(function () {
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
    });
});
