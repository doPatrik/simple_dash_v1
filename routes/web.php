<?php

use App\Http\Api\Home\ChartDataApi;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Calendar\CalendarController;
use App\Http\Controllers\Expenditure\ExpenditureController;
use App\Http\Controllers\Expenditure\ExpenditureTypeController;
use App\Http\Controllers\NewBill\NewBillController;
use App\Http\Controllers\Notification\NotificationController;
use App\Http\Controllers\Overview\OverviewController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Profile\AddressController;
use App\Http\Controllers\Provider\ProviderController;
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
    return redirect('login');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function() {

//Home
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/home/getBarChartData', [ChartDataApi::class, 'getBarChartData']);
    Route::get('/home/getLineChartData', [ChartDataApi::class, 'getLineChartData']);
    Route::get('/home/getDoughnutChartData', [ChartDataApi::class, 'getDoughnutChartData']);

//Profile
    Route::group(['middleware' => 'menu:profile'], function () {
        Route::get('/profile', [App\Http\Controllers\Profile\ProfileController::class, 'edit'])->name('profile');
        Route::put('/profile/{user}', [App\Http\Controllers\Profile\ProfileController::class, 'update'])->name('profile.change');

        //Profile Addresses
        Route::resource('address', AddressController::class)->except([
            'index', 'show',
        ]);
        Route::get('address/{id_address}', [AddressController::class, 'address'])->name('address.select');
    });

//Providers
    Route::group(['middleware' => 'menu:provider.index'], function () {
        Route::resource('provider', ProviderController::class)->except([
            'show',
        ]);
    });

//NewBill
    Route::group(['middleware' => 'menu:newBill.index'], function () {
        Route::resource('newBill', NewBillController::class)->except([
            'create', 'show'
        ]);
    });

//Payment
    Route::group(['middleware' => 'menu:payment.index'], function () {
        Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
        Route::get('/payment/getBills', [PaymentController::class, 'getBills'])->name('payment.getBills');
        Route::post('/payment/payBills', [PaymentController::class, 'payBills'])->name('payment.payBills');
        Route::delete('/payment/{id_bill}', [PaymentController::class, 'destroy'])->name('payment.delete');
    });
//Overview
    Route::group(['middleware' => 'menu:overview'], function () {
        Route::get('/overview', [OverviewController::class, 'index'])->name('overview');
    });

//CalendarResource
    Route::group(['middleware' => 'menu:calendar'], function () {
        Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
        Route::get('/calendar/events', [CalendarController::class, 'getEvents'])->name('calendarEvents');
    });

//Expenditure
    Route::group(['middleware' => 'menu:expenditure'], function () {
        Route::get('/expenditure', [ExpenditureController::class, 'index'])->name('expenditure');
        Route::get('/expenditure/types', [ExpenditureController::class, 'getTypes']);
        Route::get('/expenditure/loadData', [ExpenditureController::class, 'loadData']);
        Route::post('/expenditure/store', [ExpenditureController::class, 'store'])->name('expenditure.store');
        Route::delete('/expenditure/destroy/{id}', [ExpenditureController::class, 'destroy']);
        Route::put('/expenditure/update/{id}', [ExpenditureController::class, 'update']);

        //Expenditure types
        Route::get('/expenditure/type/loadData', [ExpenditureTypeController::class, 'loadData']);
        Route::post('/expenditure/type/store', [ExpenditureTypeController::class, 'store']);
        Route::put('/expenditure/type/update/{id}', [ExpenditureTypeController::class, 'update']);
        Route::delete('/expenditure/type/destroy/{id}', [ExpenditureTypeController::class, 'destroy']);
    });

    Route::get('/notifications/get', [NotificationController::class, 'getNotifications']);
    Route::post('/notifications/notification/markAsRead', [NotificationController::class, 'markAsRead']);
//Admin
    Route::group(['middleware' => 'is_admin', 'prefix' => 'admin'], function () {

        //Users
        Route::get('/users', [AdminController::class, 'index'])->name('list_users');
        Route::get('/users/loadUsers', [AdminController::class, 'loadUsers']);
        Route::put('/users/update/{id}', [AdminController::class, 'update'])->name('update_user');

        //Menu
        Route::get('/menu/loadMenuItems', [AdminController::class, 'loadMenuItems']);
        Route::put('/menu/updateMenu/{id}', [AdminController::class, 'updateMenu'])->name('update_menu');
        Route::delete('/menu/delete/{id}', [AdminController::class, 'destroyMenu'])->name('delete_menu');
        Route::delete('/users/delete/{id}', [AdminController::class, 'destroy'])->name('delete_user');
    });
});
