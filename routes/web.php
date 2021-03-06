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

// Route::resource('roles','RoleController');
// Route::resource('users','UserController');
// Route::resource('products','ProductController');


 Route::get('/', 'HomeController@index')->name('home');

 Route::get('/home', 'HomeController@index');
 

Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false,
  ]);

Route::group(['middleware' => 'admin'], function () {

    Route::get('/admin/address/add', 'AddAddressController@show')->name('address-add');

    Route::post('/admin/address/add', 'AddAddressController@save')->name('address-add');

    Route::get('/admin/address/lookup/','LookupAddressController@index')->name('address-lookup');

    Route::get('/admin/address/datatables', 'LookupAddressController@getAddresses')->name('address-datatable');

    Route::delete('/manager/address/delete','LookupAddressController@removeAddress')->name('address-delete');

    Route::get('/admin/currency/add', 'AddCurrencyAdminController@show')->name('admin-currency-add');

    Route::post('/admin/currency/add', 'AddCurrencyAdminController@add')->name('admin-currency-add');
    
    Route::get('/admin/currency/lookup','LookupCurrencyAdminController@index')->name('admin-currency-lookup');

    Route::get('/admin/currency/datatables', 'LookupCurrencyAdminController@getCurrencies')->name('admin-currency-datatable');

    Route::get('/admin/currency/exchange', 'ExchangeCurrencyAdminController@show')->name('admin-currency-exchange');

    Route::post('/admin/currency/exchange', 'ExchangeCurrencyAdminController@save')->name('admin-currency-exchange');

    Route::get('/admin/currency/exchange/{id}', 'ExchangeCurrencyAdminController@getExhangeRate')->name('admin-currency-exchange-get');

    Route::get('/admin/users/add', 'AddUserController@show')->name('user-add');

    Route::post('/admin/users/add', 'AddUserController@add')->name('user-add');

    Route::get('/admin/users/lookup', 'LookupUserController@index')->name('user-lookup');

    Route::get('/admin/users/datatables', 'LookupUserController@getUsers')->name('user-datatable');
    
    Route::get('/admin/users/{id}', 'ViewUserController@show')->name('user-view');

    Route::get('/admin/user/download/{id}','ViewUserController@download')->name('user-download');
    
    Route::get('/admin/users/edit/{id}','EditUserController@show')->name('user-edit');

    Route::put('/admin/users/edit/{id}','EditUserController@save')->name('user-edit');

    Route::delete('/admin/users/delete','LookupUserController@removeUser')->name('user-delete');

    Route::get('/admin/settings/system', 'SystemSettingsController@show')->name('settings-system');

    Route::post('/admin/settings/system', 'SystemSettingsController@save')->name('settings-system');

    Route::get('/admin/settings/change-password', 'ChangePasswordAdminController@show')->name("admin-change-password");

    Route::post('/admin/settings/change-password', 'ChangePasswordAdminController@save')->name("admin-change-password");
    
    Route::get('/admin/address/{code}', 'GetAddressController@list')->name("admin-address-list");
});

Route::group(['middleware' => 'manager'], function () {

	Route::get('/manager/receipt/add','AddReceiptController@show')->name('receipt-add');
	
	Route::post('/manager/receipt/add','AddReceiptController@add')->name('receipt-add');

	Route::get('/manager/receipt/edit/{id}','EditReceiptController@show')->name('receipt-edit');
	
	Route::post('/manager/receipt/edit/{id}','EditReceiptController@edit')->name('receipt-edit');

    Route::get('/manager/receipts/lookup','LookupReceiptController@index')->name('receipt-lookup');

    Route::get('/manager/receipts/datatables', 'LookupReceiptController@getReceipts')->name('receipt-datatable');

    Route::delete('/manager/receipts/delete','LookupReceiptController@removeReceipt')->name('receipt-delete');

    Route::get('/manager/receipt/view/{id}', 'LookupReceiptController@getSpecificReceipts')->name('receipt-specific');


    Route::get('/manager/receiptpayments/lookup','LookupReceiptPaymentController@index')->name('receipt-payment-lookup');
   
    Route::get('/manager/receiptpayments/datatables','LookupReceiptPaymentController@getReceiptPayments')->name('receipt-payment-datatable');
    
    Route::delete('/manager/receiptpayments/delete','LookupReceiptPaymentController@removeReceiptPayments')->name('receipt-payment-delete');

    Route::get('/manager/receiptpayments/view/{id}', 'LookupReceiptPaymentController@getSpecificReceiptPayments')->name('receipt-payment-specific');

    Route::get('/manager/receiptpayments/edit/{id}','EditReceiptPaymentController@show')->name('receipt-payment-edit');
	
	Route::post('/manager/receiptpayments/edit/{id}','EditReceiptPaymentController@edit')->name('receipt-payment-edit');


    Route::get('/manager/receipt/payment/add','AddReceiptPaymentController@show')->name('receipt-payment-add');
	
	Route::post('/manager/receipt/payment/add','AddReceiptPaymentController@add')->name('receipt-payment-add');

  
    Route::get('/manager/halls/lookup','LookupHallController@index')->name('hall-lookup');

    Route::get('/manager/halls/add','AddHallController@show')->name('hall-add');

    Route::post('/manager/halls/add','AddHallController@add')->name('hall-add');

    Route::get('/manager/halls/datatables', 'LookupHallController@getHalls')->name('hall-datatable');

    Route::get('/manager/halls/edit/{id}','EditHallController@show')->name('hall-edit');

    Route::put('/manager/halls/edit/{id}','EditHallController@save')->name('hall-edit');

    Route::delete('/manager/halls/delete','LookupHallController@removeHall')->name('hall-delete');

    Route::get('/manager/currency/lookup','LookupCurrencyController@index')->name('currency-lookup');

    Route::get('/manager/currency/add', 'AddCurrencyController@show')->name('currency-add');

    Route::post('/manager/currency/add', 'AddCurrencyController@add')->name('currency-add');

    Route::get('/manager/currency/datatables', 'LookupCurrencyController@getCurrencies')->name('currency-datatable');

    Route::get('/manager/currency/exchange', 'ExchangeCurrencyController@show')->name('currency-exchange');

    Route::post('/manager/currency/exchange', 'ExchangeCurrencyController@save')->name('currency-exchange');

    Route::get('/manager/currency/exchange/{id}', 'ExchangeCurrencyController@getExhangeRate')->name('currency-exchange-get');
  
    Route::get('/manager/service/add', 'AddServiceController@show')->name('service-add');

    Route::post('/manager/service/add', 'AddServiceController@add') ->name('service-add');

    Route::get('/manager/service/lookup', 'LookupServiceController@index') ->name('service-lookup');

    Route::get('/manager/service/datatables', 'LookupServiceController@getServices')->name('service-datatable');

    Route::delete('/manager/service/delete','LookupServiceController@removeService')->name('service-delete');

    Route::get('/manager/service/edit/{id}','EditServiceController@show')->name('service-edit');

    Route::put('/manager/service/edit/{id}','EditServiceController@save')->name('service-edit');

    Route::get('/manager/moderator/lookup','LookupModeratorController@index')->name('moderator-lookup');

    Route::get('/manager/moderator/add', 'AddModeratorController@show')->name('moderator-add');

    Route::post('/manager/moderator/add', 'AddModeratorController@add')->name('moderator-add');

    Route::get('/manager/moderator/datatables', 'LookupModeratorController@getModerators')->name('moderator-datatable');

    Route::get('/manager/moderator/{id}','ViewModeratorController@show')->name('moderator-view');

    Route::get('/manager/moderator/download/{id}','ViewModeratorController@download')->name('moderator-download');

    Route::get('/manager/moderator/edit/{id}','EditModeratorController@show')->name('moderator-edit');

    Route::put('/manager/moderator/edit/{id}','EditModeratorController@save')->name('moderator-edit');

    Route::delete('/manager/moderator/delete','LookupModeratorController@removeModerator')->name('moderator-delete');

    Route::get('/manager/reservation/add', 'AddReservationController@show')->name('reservation-add');  

    Route::post('/manager/reservation/add', 'AddReservationController@add')->name('reservation-add');  
    
    Route::get('/manager/eventlist', 'EventlistController@show')->name('eventlist-add');  

    Route::post('/manager/eventlist', 'EventlistController@add')->name('eventlist-add');

    Route::delete('/manager/eventlist/delete','EventlistController@removeEventlist')->name('eventlist-delete');

    Route::get('/manager/reservation/calender', 'ViewCalenderReservationController@show')->name('reservation-calender');   

    Route::get('/manager/reservation/lookup', 'LookupReservationController@show')->name('reservation-show');

    Route::get('/manager/reservation/datatables', 'LookupReservationController@getReservations')->name('reservation-datatable');

    Route::put('/manager/reservation/cancel','LookupReservationController@cancelReservation')->name('reservation-cancel');

    Route::put('/manager/reservation/delay','LookupReservationController@delayReservation')->name('reservation-delay');

    Route::get('/manager/reservation/{code}/invoice', 'ReservationInvoiceController@show')->name('reservation-invoice');

    Route::get('/manager/reservation/{id}', 'ViewReservationController@show')->name("reservation-view");

    Route::get('/manager/reservation/{code}/eventlist', 'ReservationEventListSheetController@show')->name('reservation-eventlist-sheet');

    Route::get('/manager/reservation/{id}/payments/add', 'AddPaymentController@show')->name('payment-add');

    Route::post('/manager/reservation/{id}/payments/add', 'AddPaymentController@add')->name('payment-add');

    Route::get('/manager/reservation/{code}/payments/{payment_id}', 'PaymentReceiptController@show')->name('payment-receipt');

    Route::get('/manager/reservation/{code}/account-statement', 'ReservationAccountInvoiceController@show')->name('reservation-account-invoice');

    Route::get('/manager/reservation/{id}/edit', 'EditReservationController@show')->name('reservation-edit');

    Route::get('/manager/reservation/{id}/update/date', 'EditReservationController@showUpdateDate')->name('reservation-update-date');

    Route::put('/manager/reservation/{id}/update/date', 'EditReservationController@updateDate')->name('reservation-update-date');

    Route::get('/manager/reservation/{id}/update', 'EditReservationDetailsController@show')->name('reservation-update');

    Route::put('/manager/reservation/{id}/update', 'EditReservationDetailsController@update')->name('reservation-update');

    Route::get('/manager/customers/lookup', 'LookupCustomerController@show')->name('customer-lookup');

    Route::get('/manager/customers/datatables', 'LookupCustomerController@getCustomers')->name('customer-datatable');

    Route::get('/manager/customers/add', 'AddCustomerController@show')->name('customer-add');

    Route::post('/manager/customers/add', 'AddCustomerController@save')->name('customer-add');


    Route::get('/manager/supplier/lookup', 'LookupSupplierController@show')->name('supplier-lookup');
    Route::get('/manager/supplier/datatables', 'LookupSupplierController@getSupplier')->name('supplier-datatable');

    Route::get('/manager/supplier/add', 'AddSupplierController@show')->name('supplier-add');
    Route::post('/manager/supplier/add', 'AddSupplierController@save')->name('supplier-add');


    Route::get('/manager/employee/lookup', 'LookupEmployeeController@show')->name('employee-lookup');
    Route::get('/manager/employee/add', 'AddEmployeeController@show')->name('employee-add');
    Route::post('/manager/employee/add', 'AddEmployeeController@save')->name('employee-add');
    Route::get('/manager/employee/datatables', 'LookupEmployeeController@getEmployee')->name('employee-datatable');
    

    Route::get('/manager/settings/personal-info','PersonalInfoSettingsController@show')->name('settings-personal');

    Route::post('/manager/settings/personal-info','PersonalInfoSettingsController@save')->name('settings-personal');

    Route::get('/manager/settings/organization-info','OrganizationInfoSettingsController@show')->name('settings-organization');

    Route::post('/manager/settings/organization-info','OrganizationInfoSettingsController@save')->name('settings-organization');

    Route::get('/manager/settings/change-password','ChangePasswordController@show')->name('change-password');

    Route::post('/manager/settings/change-password','ChangePasswordController@save')->name('change-password');
    
    Route::get('/manager/address/{code}', 'GetAddressController@list')->name("address-list");    
	
	Route::post('/checkTime', 'AddReservationController@checkTime');

    Route::get('/manager/reports/account-statement/lookup', 'LookupAccountReportController@index')->name('report-account-lookup');

    Route::get('/manager/reports/account-statement/{id}', 'ViewAccountReportController@index')->name('report-account-view');

});

Route::get('/manager/activate','ActivateManagerController@show')->name("manager-activate");

Route::post('/manager/activate','ActivateManagerController@activate')->name("manager-activate");