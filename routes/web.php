<?php

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
#TODO REF: https://laraveldaily.com/how-to-structure-routes-in-large-laravel-projects/
/* Route::get('/', function () {
     return view('welcome');
 });*/
#Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
/** front-end routes */


Route::get('/', 'HomeController@index');
#Route::get('/', "AdminController@adminLoginForm")->name("adminlogin");
Route::get('/', 'HomeController@index')->name('/');
  Route::get('/', 'HomeController@index')->name('home');
/** front-end routes */



/* Start Admin Panel Routes */
Route::get("/admin/login", "AdminController@adminLoginForm")->name("adminlogin");
Route::POST("/admin/check-login", "AdminController@checkUserLogin")->name("checklogin");
Route::get("/admin/logout", "AdminController@logout")->name("adminlogout");

Route::group(['middleware' => ['admin', 'prevent-back-history']], function() {
Route::get("admin",  function(){ return redirect()->route('admin-dashboard'); })->name('admin');
Route::get("admin/dashboard/", "AdminHomeController@dashboard")->name('admin-dashboard');
Route::post("admin/dashboard-count-ajax/", "AdminHomeController@countAjax")->name('admin-dashboard-count-ajax');
Route::get("admin/profile", "AdminHomeController@profile")->name('profile-admin');
Route::post("admin/chart-data", "AdminHomeController@chartData")->name('chart-data');
Route::POST('admin/weekly_data', 'AdminHomeController@weekly_data')->name('admin-weekly-data');

Route::get("admin/users","AdminUserController@list")->name('admin-users-list');
Route::post("admin/users/list", "AdminUserController@list_fetch")->name('admin-users-list-fetch');
Route::get("admin/users/view/{id}", "AdminUserController@view")->name('admin-users-view');
Route::post("admin/users/status", "AdminUserController@change_status")->name('admin-users-change-status');
Route::get("admin/users/add", "AdminUserController@add")->name('admin-add-user');
Route::post("admin/users/insert", "AdminUserController@insert")->name('admin-insert-user');
Route::get("admin/users/edit/{id}", "AdminUserController@edit")->name('admin-edit-user');
Route::post("admin/users/update/{id}", "AdminUserController@update")->name('admin-update-user');

Route::get("admin/supervisors","AdminSupervisorController@list")->name('admin-supervisor-list');
Route::post("admin/supervisors/list", "AdminSupervisorController@list_fetch")->name('admin-supervisor-list-fetch');
Route::get("admin/supervisor/view/{id}", "AdminSupervisorController@view")->name('admin-supervisor-view');
Route::post("admin/supervisor/status", "AdminSupervisorController@change_status")->name('admin-supervisor-change-status');
Route::get("admin/supervisor/add", "AdminSupervisorController@add")->name('admin-add-supervisor');
Route::post("admin/supervisor/insert", "AdminSupervisorController@insert")->name('admin-insert-supervisor');
Route::get("admin/supervisor/edit/{id}", "AdminSupervisorController@edit")->name('admin-edit-supervisor');
Route::post("admin/supervisor/update/{id}", "AdminSupervisorController@update")->name('admin-update-supervisor');

Route::get("admin/lots/{id?}","LotsController@list")->name('admin-lot-list');
Route::post("admin/lots/list", "LotsController@list_fetch")->name('admin-lots-list-fetch');
Route::get("admin/lot/view/{id}", "LotsController@view")->name('admin-lots-view');
Route::post("admin/lot/status", "LotsController@change_status")->name('admin-lot-change-status');
Route::get("admin/lot/add", "LotsController@add")->name('admin-add-lot');
Route::post("admin/lot/insert", "LotsController@insert")->name('admin-insert-lot');
Route::get("admin/lot/edit/{id}", "LotsController@edit")->name('admin-edit-lot');
Route::post("admin/lot/update/{id}", "LotsController@update")->name('admin-update-lots');
Route::post("admin/list/lot/bays/", "LotsController@list_lot_bays")->name('list-lot-bays');

Route::get("admin/bays","SlotsController@list")->name('admin-slots-list');
Route::post("admin/bays/list", "SlotsController@list_fetch")->name('admin-slots-list-fetch');
Route::get("admin/bays/view", "SlotsController@view")->name('admin-slots-view');
Route::post("admin/bays/status", "SlotsController@change_status")->name('admin-slots-change-status');
Route::get("admin/bays/add", "SlotsController@add")->name('admin-add-slots');
Route::post("admin/bays/insert", "SlotsController@insert")->name('admin-insert-slots');
Route::get("admin/bays/edit/{id}", "SlotsController@edit")->name('admin-edit-slot');
Route::post("admin/bays/update/{id}", "SlotsController@update")->name('admin-update-slots');




Route::get("admin/transaction/{id?}","TransactionController@list")->name('admin-transaction-list');
Route::post("admin/transaction/list", "TransactionController@listFetch")->name('admin-transaction-list-fetch');
Route::get("admin/lot/view/{id}", "LotsController@view")->name('admin-lots-view');

Route::post("admin/transaction/status", "TransactionController@change_status")->name('admin-transaction-change-status');
Route::get("admin/transaction/add", "TransactionController@add")->name('admin-add-transaction');
Route::post("admin/transaction/insert", "TransactionController@insert")->name('admin-insert-transaction');
Route::get("admin/transaction/view/{id}", "TransactionController@view")->name('admin-view-transaction');


Route::get("admin/customers","CustomerController@list")->name('admin-customers-list');
Route::post("admin/customers/list/{id?}", "CustomerController@list_fetch")->name('admin-customers-list-fetch');
Route::get("admin/customer/view/{id}", "CustomerController@view")->name('admin-customers-view');
Route::post("admin/customer/status", "CustomerController@change_status")->name('admin-customer-change-status');
// Route::get("admin/customer/add", "CustomerController@add")->name('admin-add-customer');
Route::post("admin/customer/insert", "CustomerController@insert")->name('admin-insert-customer');
Route::get("admin/customer/edit/{id}", "CustomerController@edit")->name('admin-edit-customer');
Route::get("admin/customer/detail/{id}", "CustomerController@customer_detail")->name('admin-customer-detail');
Route::post("admin/customer/update/{id}", "CustomerController@update")->name('admin-update-customer');

Route::post("admin/vehicle/list/{id}", "NumberPlateController@list_fetch")->name('admin-vehicle-list-fetch');
Route::post("admin/vehicle/status", "NumberPlateController@change_status")->name('admin-vehicle-change-status');

Route::post("admin/cash/insert/{id}", "AddedCashController@insert")->name('admin-insert-cash');

/** orders */
Route::get("admin/orders","OrderController@list")->name('admin-orders-list');
Route::post("admin/orders/list", "OrderController@list_fetch")->name('admin-orders-list-fetch');
Route::get("admin/orders/view/{id}", "OrderController@view")->name('admin-orders-view');
Route::post("admin/orders/status", "OrderController@change_status")->name('admin-orders-change-status');
Route::get("admin/orders/send-invoice/{id}/{customer_id}", "OrderController@send_invoice")->name('admin-orders-send-invoice');
Route::post("admin/orders/export", "OrderController@orders_export")->name('admin-orders-export');
Route::post("admin/orders/fetch-data", "OrderController@order_pdf_upload_fetch_data")->name('admin-orders-fetch-data');
Route::post("admin/orders/send-mail", "OrderController@order_pdf_store_send_mail")->name('admin-orders-send-mail');



/** orders */

/** voucher-orders */
Route::get("admin/voucher/voucher-order","VoucherOrderController@list")->name('admin-voucher-orders-list');
Route::post("admin/voucher/voucher-order/list", "VoucherOrderController@list_fetch")->name('admin-voucher-orders-list-fetch');
Route::get("admin/voucher/voucher-order/view/{id}", "VoucherOrderController@view")->name('admin-voucher-orders-view');
Route::post("admin/voucher/voucher-order/status", "VoucherOrderController@change_status")->name('admin-voucher-orders-change-status');
Route::get("admin/voucher/voucher-order/send-invoice/{id}/{user_id}", "VoucherOrderController@send_invoice")->name('admin-voucher-orders-send-invoice');
/** voucher-orders */

/* Coupon */
Route::get("admin/coupon","CouponController@couponList")->name('admin-coupon-list');
Route::post("admin/coupon/fetch-data","CouponController@couponListFetch")->name('admin-coupon-list-fetch');
Route::get("admin/coupon/add","CouponController@couponAdd")->name('admin-add-coupon');
Route::post("admin/coupon/insert","CouponController@couponInsert")->name('admin-insert-coupon');
Route::get("admin/coupon/edit/{id}","CouponController@couponEdit")->name('admin-coupon-edit');
Route::post("admin/coupon/update/{id}","CouponController@couponUpdate")->name('admin-coupon-update');
Route::post("admin/coupon/change-status","CouponController@couponChangeStatus")->name('admin-coupon-change-status');
/* Coupon */
/* Coupon Report */
Route::get("admin/report-coupon","CouponReportController@list")->name('admin-coupon-report-list');
Route::post("admin/report-coupon/list-fetch","CouponReportController@fetchList")->name('admin-coupon-report-list-fetch');
Route::post("admin/report-coupon/export","CouponReportController@export")->name('admin-coupon-report-export');
/* Coupon Report */

/** settings */
Route::get("admin/settings","SettingController@setting_list")->name('setting-list');
Route::post("admin/settings-update","SettingController@setting_update")->name('setting-update');
Route::post("admin/settings-logo-update","SettingController@setting_logo_update")->name('setting-logo-update');
/** settings */

/** report */
/** report-order */

Route::get("admin/report-order", "ReportController@order_list")->name('admin-report-order-list');
Route::post("admin/report-order/list", "ReportController@order_lists")->name('admin-report-order-lists');
Route::get("admin/report-order/view/{id}", "ReportController@order_view")->name('admin-report-order-view');
Route::post("admin/report-order/status", "ReportController@order_change_status")->name('admin-report-order-change-status');

/** report-order */

/** report-voucher-order */

Route::get("admin/report-voucher", "ReportController@voucher_list")->name('admin-report-voucher-list');
Route::post("admin/report-voucher/list", "ReportController@voucher_lists")->name('admin-report-voucher-lists');
Route::get("admin/report-voucher/view/{id}", "ReportController@voucher_view")->name('admin-report-voucher-view');
Route::post("admin/report-voucher/status", "ReportController@voucher_change_status")->name('admin-report-voucher-change-status');

/** report-voucher-order */
/** report */

/*Category*/
Route::get("admin/category","CategoryController@list")->name('admin-category-list');
Route::post("admin/category/list/fetch","CategoryController@listFetch")->name('admin-category-list-fetch');
Route::get("admin/category/add","CategoryController@add")->name('admin-add-category');
Route::post("admin/category/insert","CategoryController@insert")->name('admin-insert-category');
Route::get("admin/category/edit/{id}","CategoryController@edit")->name('admin-category-edit');
Route::post("admin/category/update/{id}","CategoryController@update")->name('admin-category-update');
Route::get("admin/category/delete/{id}","CategoryController@delete")->name('admin-category-delete');
/*Category*/

/** product */
Route::get("admin/product","ProductController@list")->name('admin-product-list');
Route::post("admin/product/lists", "ProductController@lists")->name('admin-product-lists');
Route::get("admin/product/add", "ProductController@add")->name('admin-product-add');
Route::post("admin/product/insert", "ProductController@insert")->name('admin-product-insert');
Route::get("admin/product/edit/{id}", "ProductController@edit")->name('admin-product-edit');
Route::post("admin/product/update/{id}", "ProductController@update")->name('admin-product-update');
Route::get("admin/product/view/{id}","ProductController@view")->name('admin-product-view');
Route::post("admin/product/change-status","ProductController@change_status")->name('admin-product-change-status');
Route::post("admin/product/remove-image", "ProductController@remove_image")->name('admin-product-remove-image');
/** product */

/** email template */
Route::get("admin/email","EmailTemplateController@email_list")->name('email-list');
Route::POST("admin/email/list-fetch-email", "EmailTemplateController@list_fetch_email")->name('admin-list-fetch-email');
Route::get("admin/email/email-view/{email_id}","EmailTemplateController@view_email")->name('admin-email-view');
Route::GET("admin/email/edit-email/{email_id}", "EmailTemplateController@edit_email")->name('admin-edit-email');
Route::post("admin/email/update-email/{email_id}", "EmailTemplateController@update_email_post")->name('admin-update-email-post');
/** email template */
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

});

