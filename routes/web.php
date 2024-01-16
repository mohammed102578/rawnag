<?php



use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RoleController;

use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Auth;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'guest']
    ],
    function () {

        Auth::routes();
        //company
        Route::get('/companies', [App\Http\Controllers\CompaniesController::class, 'companies'])->name('companies');
        Route::post('/store_companies', [App\Http\Controllers\CompaniesController::class, 'store_companies'])->name('store_companies');
    }
);


Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'auth']
    ],
    function () {

        Route::resource('roles', RoleController::class);

        Route::resource('users', UserController::class);

        Route::get('/logout_expierd', [App\Http\Controllers\LogoutController::class, 'logout_expierd'])->name('logout_expierd');
        Route::get('/logout_stop', [App\Http\Controllers\LogoutController::class, 'logout_stop'])->name('logout_stop');
        Route::get('/logout_user', [App\Http\Controllers\LogoutController::class, 'logout'])->name('logout_user');

        //cashier
        Route::match(array('GET', 'POST'), '/cashiers', 'App\Http\Controllers\CashierController@cashiers')->name('cashiers');

        Route::post('/search_medicines_cashiers', [App\Http\Controllers\CashierController::class, 'search_medicines_cashiers'])->name('search_medicines_cashiers');

        Route::post('/add_medicines_cashiers', [App\Http\Controllers\CashierController::class, 'add_medicines_cashiers'])->name('add_medicines_cashiers');


        //sales
        Route::get('/bills', [App\Http\Controllers\BillController::class, 'bills'])->name('bills');
        Route::get('/details_bills/{id}', [App\Http\Controllers\BillController::class, 'details_bills'])->name('details_bills');


        //dashboard
        Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');




        //medicines
        Route::get('/medicines', [App\Http\Controllers\MedicinesController::class, 'medicines'])->name('medicines');
        Route::post('/store_medicines', [App\Http\Controllers\MedicinesController::class, 'store_medicines'])->name('store_medicines');
        Route::post('/update_medicines/{id}', [App\Http\Controllers\MedicinesController::class, 'update_medicines'])->name('update_medicines');
        Route::get('/delete_medicines/{id}', [App\Http\Controllers\MedicinesController::class, 'delete_medicines'])->name('delete_medicines');
        Route::get('/expier_store_medicines', [App\Http\Controllers\MedicinesController::class, 'expier_store_medicines'])->name('expier_store_medicines');
        Route::get('/expier_date_medicines', [App\Http\Controllers\MedicinesController::class, 'expier_date_medicines'])->name('expier_date_medicines');



        Route::get('/medicines_en', [App\Http\Controllers\MedicinesController::class, 'medicines_en'])->name('medicines_en');
        Route::get('/expier_store_medicines_en', [App\Http\Controllers\MedicinesController::class, 'expier_store_medicines_en'])->name('expier_store_medicines_en');
        Route::get('/expier_date_medicines_en', [App\Http\Controllers\MedicinesController::class, 'expier_date_medicines_en'])->name('expier_date_medicines_en');



        //purchases
        Route::get('/purchases', [App\Http\Controllers\PurchaseController::class, 'purchases'])->name('purchases');
        Route::post('/store_purchases', [App\Http\Controllers\PurchaseController::class, 'store_purchases'])->name('store_purchases');
        Route::post('/update_purchases/{id}', [App\Http\Controllers\PurchaseController::class, 'update_purchases'])->name('update_purchases');
        Route::get('/delete_purchases/{id}', [App\Http\Controllers\PurchaseController::class, 'delete_purchases'])->name('delete_purchases');
        Route::get('/invoice_purchases/{id}', [App\Http\Controllers\PurchaseController::class, 'invoice_purchases'])->name('invoice_purchases');





        //stor
        Route::get('/stores_en', [App\Http\Controllers\StoreController::class, 'stores_en'])->name('stores_en');
        Route::get('/stores', [App\Http\Controllers\StoreController::class, 'stores'])->name('stores');
        Route::post('/update_stores/{id}', [App\Http\Controllers\StoreController::class, 'update_stores'])->name('update_stores');

        //determining_prices
        Route::get('/determining_prices_en', [App\Http\Controllers\determining_pricesController::class, 'determining_prices_en'])->name('determining_prices_en');

        Route::get('/determining_prices', [App\Http\Controllers\determining_pricesController::class, 'determining_prices'])->name('determining_prices');
        Route::post('/store_determining_prices', [App\Http\Controllers\determining_pricesController::class, 'store_determining_prices'])->name('store_determining_prices');
        Route::post('/update_determining_prices/{id}', [App\Http\Controllers\determining_pricesController::class, 'update_determining_prices'])->name('update_determining_prices');
        Route::get('/delete_determining_prices/{id}', [App\Http\Controllers\determining_pricesController::class, 'delete_determining_prices'])->name('delete_determining_prices');


        //reports
        Route::get('/reports_en', [App\Http\Controllers\ReportController::class, 'reports_en'])->name('reports_en');
        Route::match(array('GET', 'POST'), '/reports_sales_en', 'App\Http\Controllers\ReportController@reports_sales_en')->name('reports_sales_en');
        Route::match(array('GET', 'POST'), '/reports_purchas_en', 'App\Http\Controllers\ReportController@reports_purchas_en')->name('reports_purchas_en');
        Route::match(array('GET', 'POST'), '/reports_percent_medicine_en', 'App\Http\Controllers\ReportController@reports_percent_medicine_en')->name('reports_percent_medicine_en');



        Route::get('/reports', [App\Http\Controllers\ReportController::class, 'reports'])->name('reports');
        Route::match(array('GET', 'POST'), '/reports_sales', 'App\Http\Controllers\ReportController@reports_sales')->name('reports_sales');
        Route::match(array('GET', 'POST'), '/reports_purchas', 'App\Http\Controllers\ReportController@reports_purchas')->name('reports_purchas');
        Route::match(array('GET', 'POST'), '/reports_percent_medicine', 'App\Http\Controllers\ReportController@reports_percent_medicine')->name('reports_percent_medicine');

        //tables
        Route::get('/tables', [App\Http\Controllers\HomeController::class, 'tables'])->name('tables');




        //supplier

        Route::get('/suppliers', [App\Http\Controllers\SuplliersController::class, 'suppliers'])->name('suppliers');
        Route::post('/store_suppliers', [App\Http\Controllers\SuplliersController::class, 'store_suppliers'])->name('store_suppliers');
        Route::post('/update_suppliers/{id}', [App\Http\Controllers\SuplliersController::class, 'update_suppliers'])->name('update_suppliers');
        Route::get('/delete_suppliers/{id}', [App\Http\Controllers\SuplliersController::class, 'delete_suppliers'])->name('delete_suppliers');




        //packings

        Route::get('/packings', [App\Http\Controllers\PackingsControll::class, 'packings'])->name('packings');
        Route::post('/store_packings', [App\Http\Controllers\PackingsControll::class, 'store_packings'])->name('store_packings');
        Route::post('/update_packings/{id}', [App\Http\Controllers\PackingsControll::class, 'update_packings'])->name('update_packings');
        Route::get('/delete_packings/{id}', [App\Http\Controllers\PackingsControll::class, 'delete_packings'])->name('delete_packings');



        //payment methods
        Route::get('/payment_methods_en', [App\Http\Controllers\Payment_methodControll::class, 'payment_methods_en'])->name('payment_methods_en');


        Route::get('/payment_methods', [App\Http\Controllers\Payment_methodControll::class, 'payment_methods'])->name('payment_methods');
        Route::post('/store_payment_methods', [App\Http\Controllers\Payment_methodControll::class, 'store_payment_methods'])->name('store_payment_methods');
        Route::post('/update_payment_methods/{id}', [App\Http\Controllers\Payment_methodControll::class, 'update_payment_methods'])->name('update_payment_methods');
        Route::get('/delete_payment_methods/{id}', [App\Http\Controllers\Payment_methodControll::class, 'delete_payment_methods'])->name('delete_payment_methods');

        //invoice
        Route::get('/tickt/{id}', [App\Http\Controllers\InvoiceController::class, 'tickt'])->name('tickt');
        Route::get('/A4/{id}', [App\Http\Controllers\InvoiceController::class, 'A4'])->name('A4');
        Route::post('/store_invoices', [App\Http\Controllers\InvoiceController::class, 'store_invoices'])->name('store_invoices');


        Route::get('/tickt_en/{id}', [App\Http\Controllers\InvoiceController::class, 'tickt_en'])->name('tickt_en');
        Route::get('/A4_en/{id}', [App\Http\Controllers\InvoiceController::class, 'A4_en'])->name('A4_en');



        //log

        Route::get('/logs', [App\Http\Controllers\LogController::class, 'logs'])->name('logs');
        Route::post('/store_logs', [App\Http\Controllers\LogController::class, 'store_logs'])->name('store_logs');
        Route::get('/delete_all_logs', [App\Http\Controllers\LogController::class, 'delete_all_logs'])->name('delete_all_logs');
        Route::get('/delete_logs/{id}', [App\Http\Controllers\LogController::class, 'delete_logs'])->name('delete_logs');

        //shifts
        Route::get('/shifts', [App\Http\Controllers\ShiftsController::class, 'shifts'])->name('shifts');
        Route::post('/store_shifts', [App\Http\Controllers\ShiftsController::class, 'store_shifts'])->name('store_shifts');
        Route::post('/update_shifts/{id}', [App\Http\Controllers\ShiftsController::class, 'update_shifts'])->name('update_shifts');
        Route::get('/delete_shifts/{id}', [App\Http\Controllers\ShiftsController::class, 'delete_shifts'])->name('delete_shifts');
    }
);
