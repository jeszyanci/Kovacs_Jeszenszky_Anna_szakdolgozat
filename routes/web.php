<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NewsController;

use App\Http\Controllers\LogController;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;
use App\Models\Repair;

//views
Route::view('/login', 'login')
    ->name('loginPage')
    ->middleware('guest');

Route::view('/', 'mainPage')
    ->name('mainPage')
    ->middleware('auth');

Route::view('/profile', 'profile')
    ->name('profilePage')
    ->middleware('auth');

Route::view('/newOrder', 'newOrderPage')
    ->name('newOrderPage')
    ->middleware('auth');

Route::view('/ordersList', 'ordersList')
    ->name('ordersListPage')
    ->middleware('auth');

Route::view('/newRepair', 'newRepairPage')
    ->name('newRepairPage')
    ->middleware('auth');

Route::view('/repairsList', 'repairsListPage')
    ->name('repairsListPage')
    ->middleware('auth');

Route::view('/logList', 'logListPage')
    ->name('logListPage')
    ->middleware('auth');

Route::view('/graphs', 'graphsPage')
    ->name('graphsPage')
    ->middleware('auth');

// login
Route::get('/login/signIn', [LoginController::class, 'authenticate']);

// logout
Route::get('/logout', function () {
    Log::create('user', 'User logged out');
    Auth::logout();
});

// user
Route::get('/profile/data', [ProfileController::class, 'getLoggedInUser'])
    ->name('getUserData')
    ->middleware('auth');

Route::get('/profile/data/getAllUser', [ProfileController::class, 'getAllUser'])
    ->name('getAllUserData')
    ->middleware('auth');

Route::get('/profile/data/saveUserData', [ProfileController::class, 'saveUserData'])
    ->name('saveUserData')
    ->middleware('auth');

Route::get('/profile/data/addNewUser', [ProfileController::class, 'addNewUser'])
    ->name('addNewUser')
    ->middleware('auth');

Route::get('/profile/data/deleteUser', [ProfileController::class, 'deleteUser'])
    ->name('deleteUser')
    ->middleware('auth');

//newOrder
Route::get('/newOrder/saveOrder',   [OrderController::class, 'saveOrder']);
Route::post('/newOrder/saveSketch', [OrderController::class, 'saveSketch']);
Route::get('/newOrder/getMethods',  [OrderController::class, 'getMethods']);

// orderList
Route::get('/ordersList/getList',        [OrderController::class, 'getList']);
Route::get('/ordersList/getStates',      [OrderController::class, 'getStates']);
Route::get('/ordersList/search',         [OrderController::class, 'searchInOrders']);
Route::get('/ordersList/checkDeadlines', [OrderController::class, 'checkDeadlines']);
Route::post('/ordersList/save_methodProgress', [OrderController::class, 'save_methodProgress']);

//newRepair
Route::get('/newRepair/saveRepair',   [RepairController::class, 'saveRepair']);
Route::post('/newRepair/saveSketch',  [RepairController::class, 'saveSketch']);

// repairList
Route::get('/repairsList/getList',   [RepairController::class, 'getList']);

// news
Route::post('/news/saveNews', [NewsController::class, 'saveNews']);
Route::get('/news/getNews',  [NewsController::class, 'getNews']);
Route::post('/news/deleteNews', [NewsController::class, 'deleteNews']);

// log
Route::get('/log/getLogData', [LogController::class, 'getLogData']);