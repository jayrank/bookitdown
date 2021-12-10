<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\PreventBackHistory;

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
//Route::get('/clients', [App\Http\Controllers\ClientsController::class, 'index'])->name('clientList')->middleware(['can:client']);

Auth::routes();

Route::get('/', [App\Http\Controllers\Front\DashboardController::class, 'index'])->name('/');
Route::get('/search/{category?}', [App\Http\Controllers\Front\DashboardController::class, 'search'])->name('search');
Route::get('/search_detail/{id?}', [App\Http\Controllers\Front\DashboardController::class, 'search_detail'])->name('search_detail');
Route::post('/searchFilter', [App\Http\Controllers\Front\DashboardController::class, 'searchFilter'])->name('searchFilter');
Route::post('/searchLocation', [App\Http\Controllers\Front\DashboardController::class, 'searchLocation'])->name('searchLocation');
Route::post('/categorySearchFilter', [App\Http\Controllers\Front\DashboardController::class, 'categorySearchFilter'])->name('categorySearchFilter');
Route::post('/fetchReviews', [App\Http\Controllers\Front\DashboardController::class, 'fetchReviews'])->name('fetchReviews');
Route::get('/getLocationByIp', [App\Http\Controllers\Front\DashboardController::class, 'getLocationByIp'])->name('getLocationByIp');
Route::post('/UserCurrentLocation/', [App\Http\Controllers\Front\DashboardController::class, 'UserCurrentLocation'])->name('UserCurrentLocation');
Route::get('/getallsalon', [App\Http\Controllers\Front\DashboardController::class, 'getallsalon'])->name('getallsalon');
Route::get('/offers', [App\Http\Controllers\Front\DashboardController::class, 'offers'])->name('offers');


// SMS Webhook
Route::match(['get', 'post'],'/Telnyx_log', [App\Http\Controllers\LogController::class, 'Telnyx_log']);
Route::match(['get', 'post'],'/emailResponseLog', [App\Http\Controllers\LogController::class, 'emailResponseLog']);

Route::get('/sendTestEmail', [App\Http\Controllers\LogController::class, 'sendTestEmail']);

Route::get('/switchlogin/{lid?}/{email?}', [App\Http\Controllers\HomeController::class, 'autologin']);

// front route
Route::match(['get', 'post'],'/flogin', [App\Http\Controllers\frontController::class, 'login'])->name('flogin');
Route::match(['get', 'post'],'/fsignup', [App\Http\Controllers\frontController::class, 'signup'])->name('fsignup');
Route::match(['get', 'post'],'/forgot-password', [App\Http\Controllers\frontController::class, 'forgot_password'])->name('forgot-password');
Route::match(['get', 'post'],'/reset-password/{token}/{email}', [App\Http\Controllers\frontController::class, 'reset_password'])->name('reset-password');
Route::get('/redirect/{service}', [App\Http\Controllers\frontController::class, 'redirect'])->name('redirect');
Route::get('/callback/{service}', [App\Http\Controllers\frontController::class, 'callback']);
Route::get('/privacy-policy', [App\Http\Controllers\frontController::class, 'privacy_policy'])->name('privacy-policy');
Route::get('/website-terms', [App\Http\Controllers\frontController::class, 'website_terms'])->name('website-terms');
Route::get('/booking-terms', [App\Http\Controllers\frontController::class, 'booking_terms'])->name('booking-terms');
Route::get('/for-partners', [App\Http\Controllers\frontController::class, 'for_partners'])->name('for-partners');
Route::get('/pricing', [App\Http\Controllers\frontController::class, 'pricing'])->name('pricing');

//Frontend booking route
Route::get('/booking/{lid?}', [App\Http\Controllers\Front\BookingController::class, 'index'])->name('frontBooking');
Route::post('/getStaffList', [App\Http\Controllers\Front\BookingController::class, 'getStaffData']);
Route::post('/getBookingSlot', [App\Http\Controllers\Front\BookingController::class, 'getStaffTime']);
Route::post('/saveBookingApp', [App\Http\Controllers\Front\BookingController::class, 'saveBookingAppointment'])->name('saveBookingAppointment');
Route::post('/bookingFlowSignup', [App\Http\Controllers\Front\BookingController::class, 'bookingFlowSignup'])->name('bookingFlowSignup');
Route::post('/bookingFlowLogin', [App\Http\Controllers\Front\BookingController::class, 'bookingFlowLogin'])->name('bookingFlowLogin');

//particular salon booking link
Route::get('/BookNow/{uid?}', [App\Http\Controllers\Front\onlineBookingController::class, 'index'])->name('BookNow');
Route::post('/BookNowbyloc', [App\Http\Controllers\Front\onlineBookingController::class, 'getdatabyloc'])->name('BookNowbyloc');

Route::get('/reschedule/{lid?}/{aid?}', [App\Http\Controllers\Front\RescheduleAppointmentController::class, 'rescheduleAppointment'])->name('frontReschedule');
Route::post('/saveRescheduleApp', [App\Http\Controllers\Front\RescheduleAppointmentController::class, 'saveRescheduleAppointment'])->name('saveRescheduleAppointment');


//Frontend sell voucher route
Route::get('/sell_voucher/{lid?}', [App\Http\Controllers\Front\SellVoucherController::class, 'index'])->name('frontSellVoucher');
Route::post('/saveSellVoucher/{lid?}', [App\Http\Controllers\Front\SellVoucherController::class, 'saveSellVoucherData'])->name('saveSellVoucherData');
Route::post('/printSellVoucher',[App\Http\Controllers\Front\SellVoucherController::class, 'printSellVoucherData'])->name('printSellVoucherData');
Route::post('/getServices',[App\Http\Controllers\Front\SellVoucherController::class, 'getVoucherService'])->name('getVoucherService');

//Frontend appointment route
Route::get('/myAppointments/{appointmentId?}', [App\Http\Controllers\Front\AppointmentsController::class, 'myAppointments'])->name('myAppointments');
Route::get('/appointment/cancel-error/{appId?}', [App\Http\Controllers\Front\AppointmentsController::class, 'cancelError'])->name('cancelError');
Route::get('/appointment/reschedule-error/{appId?}', [App\Http\Controllers\Front\AppointmentsController::class, 'rescheduleError'])->name('rescheduleError');
Route::get('/myConsultationForm', [App\Http\Controllers\Front\AppointmentsController::class, 'myConsultationForm'])->name('myConsultationForm');
Route::get('/submitConsultationForm/{consultationId?}', [App\Http\Controllers\Front\AppointmentsController::class, 'submitConsultationForm'])->name('submitConsultationForm');
Route::post('/saveConsultationForm', [App\Http\Controllers\Front\AppointmentsController::class, 'saveConsultationForm'])->name('saveConsultationForm');
Route::get('/viewConsultationForm/{consultationId?}', [App\Http\Controllers\Front\AppointmentsController::class, 'viewConsultationForm'])->name('viewConsultationForm');
Route::post('/printConsultationForm', [App\Http\Controllers\Front\AppointmentsController::class, 'printConsultationForm'])->name('printConsultationForm');

Route::post('/cancelAppointment', [App\Http\Controllers\Front\AppointmentsController::class, 'cancelAppointment'])->name('cancelAppointment');
Route::post('/postReview', [App\Http\Controllers\Front\AppointmentsController::class, 'postReview'])->name('postReview');

//Frontend profile route
Route::get('/profile', [App\Http\Controllers\Front\ProfileController::class, 'index'])->name('profile');
Route::post('/updateProfile', [App\Http\Controllers\Front\ProfileController::class, 'updateProfile'])->name('updateProfile');
Route::get('/getAddresses', [App\Http\Controllers\Front\ProfileController::class, 'getAddresses'])->name('getAddresses');
Route::post('/addAddress', [App\Http\Controllers\Front\ProfileController::class, 'addAddress'])->name('addAddress');
Route::post('/updateAddress', [App\Http\Controllers\Front\ProfileController::class, 'updateAddress'])->name('updateAddress');
Route::post('/deleteAddress', [App\Http\Controllers\Front\ProfileController::class, 'deleteAddress'])->name('deleteAddress');
Route::post('/savePaymentDetail', [App\Http\Controllers\Front\ProfileController::class, 'savePaymentDetail'])->name('savePaymentDetail');
Route::post('/changePassword', [App\Http\Controllers\Front\ProfileController::class, 'changePassword'])->name('changePassword');


//Frontend favourites route
Route::get('/favourites', [App\Http\Controllers\Front\FavouritesController::class, 'index'])->name('favourites');
Route::post('/toggleFavourite', [App\Http\Controllers\Front\FavouritesController::class, 'toggleFavourite'])->name('toggleFavourite');

//Frontend vouchers route
Route::get('/myVouchers/{soldVoucherId?}', [App\Http\Controllers\Front\VouchersController::class, 'index'])->name('myVouchers');
Route::get('/viewVoucherInvoice/{soldVoucherId?}', [App\Http\Controllers\Front\VouchersController::class, 'viewVoucherInvoice'])->name('viewVoucherInvoice');
Route::get('/printVoucher/{soldVoucherId?}', [App\Http\Controllers\Front\VouchersController::class, 'printVoucher'])->name('printVoucher');
Route::get('/emailVoucher/{soldVoucherId?}', [App\Http\Controllers\Front\VouchersController::class, 'emailVoucher'])->name('emailVoucher');
Route::post('/sendVoucherByEmail',[App\Http\Controllers\Front\VouchersController::class, 'sendVoucherByEmail'])->name('sendVoucherByEmail');
Route::post('/printVoucherInvoice',[App\Http\Controllers\Front\VouchersController::class, 'printVoucherInvoice'])->name('printVoucherInvoice');
Route::get('/saveVoucherInvoicePdf/{id}',[App\Http\Controllers\Front\VouchersController::class, 'saveVoucherInvoicePdf'])->name('saveVoucherInvoicePdf');
Route::post('/sendVoucherInvoiceCopy', [App\Http\Controllers\Front\VouchersController::class, 'sendVoucherInvoiceCopy'])->name('sendVoucherInvoiceCopy');

//Frontend paid_plan route
Route::get('/myPaidPlans/{paidPlanId?}', [App\Http\Controllers\Front\PaidPlanController::class, 'index'])->name('myPaidPlans');

Route::group(['middleware' => ['FUser']], function () {

});

Route::get('/flogout', [App\Http\Controllers\frontController::class, 'logout'])->name('flogout');

//end
Route::group(['prefix' => 'partners','middleware'=>'PreventBackHistory'], function(){
	Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
	Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
	Route::post('/getapp', [App\Http\Controllers\HomeController::class, 'getapp'])->name('getapp');

	Route::match(['get', 'post'],'/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'forgot_password'])->name('partner-forgot-password');
	Route::match(['get', 'post'],'/reset-password/{token}/{email}', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'reset_password'])->name('partner-reset-password');

	Route::get('/myprofile', [App\Http\Controllers\HomeController::class, 'my_profile'])->name('my_profile');
	Route::post('/updateprofile', [App\Http\Controllers\HomeController::class, 'updateMyProfile'])->name('updateMyProfile');
	Route::post('/uploadprofilepic', [App\Http\Controllers\HomeController::class, 'updateProfileImage'])->name('updateProfileImage');

	Route::post('/appointmentSearch', [App\Http\Controllers\HomeController::class, 'appointmentSearch'])->name('appointmentSearch');
	Route::post('/clientSearch', [App\Http\Controllers\HomeController::class, 'clientSearch'])->name('clientSearch');
	
	Route::get('/roles', [App\Http\Controllers\PermissionController::class, 'Permission']);

	// Add Clients
	Route::get('/clients', [App\Http\Controllers\ClientsController::class, 'index'])->name('clientList')->middleware(['can:clients']);
	Route::get('/clientDetail/{id}', [App\Http\Controllers\ClientsController::class, 'clientDetail'])->name('clientDetail');
	Route::post('/add-clients', [App\Http\Controllers\ClientsController::class, 'add'])->name('addClient');
	Route::post('/get-clients', [App\Http\Controllers\ClientsController::class, 'getClientlist'])->name('getClient');
	Route::get('/delete-clients/{id}', [App\Http\Controllers\ClientsController::class, 'delete'])->name('deleteClient');
	Route::get('/view/{id?}/client', [App\Http\Controllers\ClientsController::class, 'viewclient'])->name('viewClient');
	Route::post('/updateClient', [App\Http\Controllers\ClientsController::class, 'updateClient'])->name('updateClient');
	Route::get('/clientinfoexcel', [App\Http\Controllers\ClientsController::class, 'clientdownloadExcel'])->name('clientinfoexcel');
	Route::get('/clientdownloadcsv', [App\Http\Controllers\ClientsController::class, 'clientdownloadcsv'])->name('clientdownloadcsv');
	
	Route::post('/getAppointments', [App\Http\Controllers\ClientsController::class, 'getAppointments'])->name('getAppointments');
	Route::post('/getClientProducts', [App\Http\Controllers\ClientsController::class, 'getClientProducts'])->name('getClientProducts');
	Route::post('/getClientInvoices', [App\Http\Controllers\ClientsController::class, 'getClientInvoices'])->name('getClientInvoices');
	Route::post('/getToBeCompletedConForm', [App\Http\Controllers\ClientsController::class, 'getToBeCompletedConForm'])->name('getToBeCompletedConForm');
	 
	Route::get('/staff', [App\Http\Controllers\StaffController::class, 'index'])->name('staffListing');
	Route::post('/get-staff', [App\Http\Controllers\StaffController::class, 'getStafflist'])->name('getStaff');
	Route::get('/staff_closed', [App\Http\Controllers\StaffController::class, 'staff_closed'])->name('staff_closed')->middleware(['can:closed_dates']);
	Route::post('/add_staff_closed', [App\Http\Controllers\StaffController::class, 'add_staff_closed'])->name('add_staff_closed');
	Route::post('/getclosed_date', [App\Http\Controllers\StaffController::class, 'getclosed_date'])->name('getclosed_date');
	Route::post('/deleteclosed_date', [App\Http\Controllers\StaffController::class, 'deleteStaff'])->name('deleteclosed_date');
	Route::get('/staff_members', [App\Http\Controllers\StaffController::class, 'staff_members'])->name('staff_members')->middleware(['can:staff_members']);
	Route::get('/staff_user_permission', [App\Http\Controllers\StaffController::class, 'staff_user_permission']);
	Route::get('/add_staff_member/{id?}', [App\Http\Controllers\StaffController::class, 'add_staff_member'])->name('add_staff_member');
	Route::post('/addStaff', [App\Http\Controllers\StaffController::class, 'addStaff'])->name('addStaff');
	Route::post('/deleteStaffMember', [App\Http\Controllers\StaffController::class, 'deleteStaffMember'])->name('deleteStaffMember');
	Route::get('/sendMail/{id?}', [App\Http\Controllers\StaffController::class, 'sendMail'])->name('sendMail');
	// staff export
	Route::get('/staffinfoexcel', [App\Http\Controllers\StaffController::class, 'staffinfodownloadExcel'])->name('staffinfoexcel');
	Route::get('/staffinfocsv', [App\Http\Controllers\StaffController::class, 'staffinfodownloadcsv'])->name('staffinfocsv');

	//set staff order
	Route::get('/setstafforder', [App\Http\Controllers\StaffController::class, 'setstafforder'])->name('setstafforder');
	Route::post('/stafftorder', [App\Http\Controllers\StaffController::class, 'stafftorder'])->name('stafftorder');

	// staff filter
	Route::get('/getStaffByLoc/{id}/{date}', [App\Http\Controllers\StaffController::class, 'getStaffByLoc'])->name('getStaffByLoc');
	Route::post('/getHoursByStaff', [App\Http\Controllers\StaffController::class, 'getHoursByStaff'])->name('getHoursByStaff');
	Route::post('/getDateByStaff', [App\Http\Controllers\StaffController::class, 'getDateByStaff'])->name('getDateByStaff');

	// staff add working hours
	Route::post('/addStaffWorkingHours', [App\Http\Controllers\StaffController::class, 'addStaffWorkingHours'])->name('addStaffWorkingHours');
	Route::get('/getStaffWorkingHours/{id}', [App\Http\Controllers\StaffController::class, 'getStaffWorkingHours'])->name('getStaffWorkingHours');
	Route::post('/editStaffWorkingHours', [App\Http\Controllers\StaffController::class, 'editStaffWorkingHours'])->name('editStaffWorkingHours');
	Route::post('/removeHours', [App\Http\Controllers\StaffController::class, 'removeHours'])->name('removeHours');

	// staff user permission
	Route::get('/getUserPermission', [App\Http\Controllers\StaffController::class, 'getUserPermission'])->name('getUserPermission')->middleware(['can:permission_levels']);
	Route::post('/saveUserStaffPermission', [App\Http\Controllers\StaffController::class, 'saveUserStaffPermission'])->name('saveUserStaffPermission');

	Route::get('/inventory', [App\Http\Controllers\InventoryController::class, 'index'])->name('inventory')->middleware(['can:products']);

	// Categories management
	Route::get('/categories', [App\Http\Controllers\InventoryController::class, 'categories'])->name('categories');
	Route::post('/addNewCategory', [App\Http\Controllers\InventoryController::class, 'addNewCategory'])->name('addNewCategory');
	Route::post('/getInventoryCategory', [App\Http\Controllers\InventoryController::class, 'getInventoryCategory'])->name('getInventoryCategory');

	// Brands management
	Route::get('/brands', [App\Http\Controllers\InventoryController::class, 'brands'])->name('brands');
	Route::post('/delete-category', [App\Http\Controllers\InventoryController::class, 'deleteCategory'])->name('deleteCategory');
	Route::post('/addNewBrand', [App\Http\Controllers\InventoryController::class, 'addNewBrand'])->name('addNewBrand');
	Route::post('/getInventoryBrands', [App\Http\Controllers\InventoryController::class, 'getInventoryBrands'])->name('getInventoryBrands');
	Route::post('/delete-brand', [App\Http\Controllers\InventoryController::class, 'deleteBrand'])->name('deleteBrand');

	// Supplier management
	Route::get('/suppliers', [App\Http\Controllers\InventoryController::class, 'suppliers'])->name('suppliers');
	Route::get('/addInventorySupplier/{id?}', [App\Http\Controllers\InventoryController::class, 'addInventorySupplier'])->name('addInventorySupplier');
	Route::post('/addNewSupplier', [App\Http\Controllers\InventoryController::class, 'addNewSupplier'])->name('addNewSupplier');
	Route::post('/getInventorySupplier', [App\Http\Controllers\InventoryController::class, 'getInventorySupplier'])->name('getInventorySupplier');
	Route::post('/delete-supplier', [App\Http\Controllers\InventoryController::class, 'deleteSupplier'])->name('deleteSupplier');

	// add product management
	Route::get('/addProductItem', [App\Http\Controllers\InventoryController::class, 'addProduct'])->name('addProduct');
	Route::get('/viewProduct/{id}', [App\Http\Controllers\InventoryController::class, 'viewProductItem'])->name('viewProductItem');
	Route::post('/viewStockLogs', [App\Http\Controllers\InventoryController::class, 'getInventoryOrderLogs'])->name('getInventoryOrderLogs'); 
	Route::get('/editProduct/{id?}', [App\Http\Controllers\InventoryController::class, 'editProductItem'])->name('editProductItem');
	Route::post('/createProduct', [App\Http\Controllers\InventoryController::class, 'ajaxAddProduct'])->name('ajaxAddProduct');
	Route::post('/increaseStock', [App\Http\Controllers\InventoryController::class, 'increaseProductStock'])->name('increaseProductStock');
	Route::post('/decreaseStock', [App\Http\Controllers\InventoryController::class, 'decreaseProductStock'])->name('decreaseProductStock');
	Route::post('/getProducts', [App\Http\Controllers\InventoryController::class, 'getProductList'])->name('getProducts');
	Route::post('/filterProductList', [App\Http\Controllers\InventoryController::class, 'filterProductList'])->name('filterProductList');
	Route::post('/deleteProduct', [App\Http\Controllers\InventoryController::class, 'deleteProductItem'])->name('deleteProductItem');

	// notification settings
	Route::get('/userNotificationSettings', [App\Http\Controllers\NotificationController::class, 'userNotificationSettings'])->name('userNotificationSettings');
	Route::post('/storeUserNotificationSettings', [App\Http\Controllers\NotificationController::class, 'storeUserNotificationSettings'])->name('storeUserNotificationSettings');
	
	// Order Management
	Route::group(['prefix' => 'order'], function(){
		Route::get('/', [App\Http\Controllers\InventoryController::class, 'ordersList'])->name('orders');
		Route::get('/create', [App\Http\Controllers\InventoryController::class, 'createOrder'])->name('createOrder');
		Route::post('/getCategory', [App\Http\Controllers\InventoryController::class, 'getProductCategories'])->name('getProductCategories');
		Route::post('/getProducts', [App\Http\Controllers\InventoryController::class, 'getProductsList'])->name('getProductsList');
		Route::post('/addToCartItem', [App\Http\Controllers\InventoryController::class, 'addToCartItem'])->name('addToCartItem');
		Route::post('/save', [App\Http\Controllers\InventoryController::class, 'saveOrder'])->name('saveOrder');
		Route::get('/view/{id?}', [App\Http\Controllers\InventoryController::class, 'viewOrder'])->name('viewOrder');
		Route::post('/getOrderList', [App\Http\Controllers\InventoryController::class, 'getOrders'])->name('getOrders');
		Route::get('/receive/{id?}', [App\Http\Controllers\InventoryController::class, 'receiveOrder'])->name('receiveOrder');
		Route::post('/receiveSave', [App\Http\Controllers\InventoryController::class, 'receiveSaveOrder'])->name('receiveSaveOrder');
		Route::post('/cancel', [App\Http\Controllers\InventoryController::class, 'cancelOrder'])->name('cancelOrder');
		Route::post('/sendOrderEmail', [App\Http\Controllers\InventoryController::class, 'sendPurchaseOrderEmail'])->name('sendPurchaseOrderEmail');	
		Route::get('/downloadPdf/{id?}', [App\Http\Controllers\InventoryController::class, 'generatePDF'])->name('generatePDF');
		Route::get('/savePdf/{id?}', [App\Http\Controllers\InventoryController::class, 'saveOrderPdf'])->name('saveOrderPdf');
		Route::post('/filterorder', [App\Http\Controllers\InventoryController::class, 'filterorder'])->name('filterorder');
		//export 
		Route::get('/productinfoexcel', [App\Http\Controllers\InventoryController::class, 'productinfoexcel'])->name('productinfoexcel');
		Route::get('/productinfocsv', [App\Http\Controllers\InventoryController::class, 'productinfocsv'])->name('productinfocsv');
		Route::get('/StockHistoryexcel/{id}', [App\Http\Controllers\InventoryController::class, 'StockHistoryexcel'])->name('StockHistoryexcel');
		Route::get('/StockHistorycsv/{id}', [App\Http\Controllers\InventoryController::class, 'StockHistorycsv'])->name('StockHistorycsv');
		Route::get('/ordersexcel', [App\Http\Controllers\InventoryController::class, 'ordersexcel'])->name('ordersexcel');
		Route::get('/orderscsv', [App\Http\Controllers\InventoryController::class, 'orderscsv'])->name('orderscsv');

		// Sidebar notification
		Route::get('/getsidebarnotification', [App\Http\Controllers\NotificationController::class, 'getSidebarNotification'])->name('getsidebarnotification');
		Route::get('/notificationMarkRead', [App\Http\Controllers\NotificationController::class, 'notificationMarkRead'])->name('notificationMarkRead');

	});

	//setup
	Route::group(['prefix' => 'setup'], function()
	{
		//location
	    Route::get('/', [App\Http\Controllers\SetupController::class, 'index'])->name('setup');
	    Route::get('/location', [App\Http\Controllers\SetupController::class, 'get_location'])->name('get_location');
	    Route::get('/location/add/', [App\Http\Controllers\SetupController::class, 'add_location'])->name('add_location');
	    Route::get('/location/{id}', [App\Http\Controllers\SetupController::class, 'location_detail'])->name('location_detail');
	    Route::any('/addlocation', [App\Http\Controllers\SetupController::class, 'addlocation'])->name('addlocation'); 
	    Route::any('/editlocation/{id}', [App\Http\Controllers\SetupController::class, 'editlocation'])->name('editlocation'); 
	    Route::any('/editbusinesstype/{id}', [App\Http\Controllers\SetupController::class, 'editbusinesstypes'])->name('editbusinesstypes'); 
	    Route::any('/location/contact-details/{id}', [App\Http\Controllers\SetupController::class, 'updateContactDetails'])->name('updateContactDetails'); 
	    Route::any('/deletelocation',[App\Http\Controllers\SetupController::class, 'deletelocation'])->name('deletelocation');
		Route::get('/locationOrder', [App\Http\Controllers\SetupController::class, 'locationOrder'])->name('locationOrder');
		Route::post('/updateLocationOrder', [App\Http\Controllers\SetupController::class, 'updateLocationOrder'])->name('updateLocationOrder');
	
		//resource
		Route::get('/resources',[App\Http\Controllers\SetupController::class, 'resources_index'])->name('resources');
		Route::any('/resources/get_resources',[App\Http\Controllers\SetupController::class, 'get_resources'])->name('get_resources');
		Route::any('/resources/add_resources',[App\Http\Controllers\SetupController::class, 'add_resources'])->name('add_resources');
		Route::any('/resources/delete_resources',[App\Http\Controllers\SetupController::class, 'delete_resources'])->name('delete_resources');
		
		//account
		Route::get('/account',[App\Http\Controllers\SetupController::class, 'account'])->name('account');
		Route::post('/accountSetting',[App\Http\Controllers\SetupController::class, 'accountSetting'])->name('accountSetting');

		//invoice template
		Route::get('/invoiceTemplate',[App\Http\Controllers\SetupController::class, 'invoiceTemplate'])->name('invoiceTemplate');
		Route::post('/invoice_template',[App\Http\Controllers\SetupController::class, 'invoice_template'])->name('invoice_template');

		//Invoice Sequencing
		Route::get('/InvoiceSequencing',[App\Http\Controllers\SetupController::class, 'InvoiceSequencing'])->name('InvoiceSequencing');
		Route::post('/Invoice_sequencing',[App\Http\Controllers\SetupController::class, 'Invoice_sequencing'])->name('Invoice_sequencing');

		//taxes
		Route::get('/taxes',[App\Http\Controllers\SetupController::class, 'taxes'])->name('taxes');
		Route::post('/locTates',[App\Http\Controllers\SetupController::class, 'locTates'])->name('locTates');
		Route::post('/saveTaxes',[App\Http\Controllers\SetupController::class, 'saveTaxes'])->name('saveTaxes');
		Route::post('/taxTates',[App\Http\Controllers\SetupController::class, 'taxTates'])->name('taxTates');
		Route::post('/updatetax',[App\Http\Controllers\SetupController::class, 'updatetax'])->name('updatetax');
		Route::post('/deletetax',[App\Http\Controllers\SetupController::class, 'deletetax'])->name('deletetax');
		Route::post('/saveLocTax',[App\Http\Controllers\SetupController::class, 'saveLocTax'])->name('saveLocTax');
		Route::post('/savetaxformula',[App\Http\Controllers\SetupController::class, 'savetaxformula'])->name('savetaxformula');
		Route::get('/getgrouptax',[App\Http\Controllers\SetupController::class, 'getgrouptax'])->name('getgrouptax');
		Route::post('/editGroupTaxes',[App\Http\Controllers\SetupController::class, 'editGroupTaxes'])->name('editGroupTaxes');
		Route::post('/saveGroupTax',[App\Http\Controllers\SetupController::class, 'saveGroupTax'])->name('saveGroupTax');
		Route::post('/updateGroupTax',[App\Http\Controllers\SetupController::class, 'updateGroupTax'])->name('updateGroupTax');

		//payment type
		Route::get('/paymentType',[App\Http\Controllers\SetupController::class, 'paymentType'])->name('paymentType');
		Route::post('/getpaymentdata',[App\Http\Controllers\SetupController::class, 'getpaymentdata'])->name('getpaymentdata');
		Route::post('/savePaymentType',[App\Http\Controllers\SetupController::class, 'savePaymentType'])->name('savePaymentType');
		Route::post('/updatePayType',[App\Http\Controllers\SetupController::class, 'updatePayType'])->name('updatePayType');
		Route::post('/deletePayType',[App\Http\Controllers\SetupController::class, 'deletePayType'])->name('deletePayType');
		Route::post('/payTypeorder',[App\Http\Controllers\SetupController::class, 'payTypeorder'])->name('payTypeorder');

		//discount 
		Route::get('/getdiscount',[App\Http\Controllers\SetupController::class, 'getdiscount'])->name('getdiscount');
		Route::post('/getDisData',[App\Http\Controllers\SetupController::class, 'getDisData'])->name('getDisData');
		Route::post('/saveDiscount',[App\Http\Controllers\SetupController::class, 'saveDiscount'])->name('saveDiscount');
		Route::post('/getdiscountdata',[App\Http\Controllers\SetupController::class, 'getdiscountdata'])->name('getdiscountdata');
		Route::post('/updateDiscount',[App\Http\Controllers\SetupController::class, 'updateDiscount'])->name('updateDiscount');
		Route::post('/deleteDisco',[App\Http\Controllers\SetupController::class, 'deleteDisco'])->name('deleteDisco');

		//sales setting
		Route::get('/getsales',[App\Http\Controllers\SetupController::class, 'getsales'])->name('getsales');
		Route::post('/saveSalesSetting',[App\Http\Controllers\SetupController::class, 'saveSalesSetting'])->name('saveSalesSetting');

		// referral sources
		Route::get('/showreferral',[App\Http\Controllers\SetupController::class, 'showreferral'])->name('showreferral');
		Route::post('/getreferral',[App\Http\Controllers\SetupController::class, 'getreferral'])->name('getreferral');
		Route::post('/saveReferral',[App\Http\Controllers\SetupController::class, 'saveReferral'])->name('saveReferral');
		Route::post('/getEditReferralData',[App\Http\Controllers\SetupController::class, 'getEditReferralData'])->name('getEditReferralData');
		Route::post('/updateReferral',[App\Http\Controllers\SetupController::class, 'updateReferral'])->name('updateReferral');
		Route::post('/referralorder',[App\Http\Controllers\SetupController::class, 'referralorder'])->name('referralorder');
		Route::post('/deleteRefe',[App\Http\Controllers\SetupController::class, 'deleteRefe'])->name('deleteRefe');

		Route::get('/cancellationReasons',[App\Http\Controllers\SetupController::class, 'cancellationReasons'])->name('cancellationReasons');
		Route::post('/cancellationReasonList',[App\Http\Controllers\SetupController::class, 'cancellationReasonList'])->name('cancellationReasonList');
		Route::post('/addCancellationReason',[App\Http\Controllers\SetupController::class, 'addCancellationReason'])->name('addCancellationReason');
		Route::post('/editCancellationReason',[App\Http\Controllers\SetupController::class, 'editCancellationReason'])->name('editCancellationReason');
		Route::post('/deleteCancellationReason',[App\Http\Controllers\SetupController::class, 'deleteCancellationReason'])->name('deleteCancellationReason');

		Route::get('/telnyx_setting',[App\Http\Controllers\SetupController::class, 'telnyx_setting'])->name('telnyx_setting');
		Route::post('/saveTelnyxSetting',[App\Http\Controllers\SetupController::class, 'saveTelnyxSetting'])->name('saveTelnyxSetting');
	});

	//Voucher
	Route::group(['prefix' => 'voucher', 'middleware' => 'can:view_voucher_list'],function()
	{
		Route::post('/getSer',[App\Http\Controllers\VoucherController::class, 'getServicedata'])->name('getSer');

		Route::get('/',[App\Http\Controllers\VoucherController::class, 'index'])->name('voucherindex');
		Route::get('/create_voucher',[App\Http\Controllers\VoucherController::class, 'create'])->name('create_voucher');
		Route::post('/create_voucher_sub',[App\Http\Controllers\VoucherController::class, 'createsub'])->name('create_voucher_sub');
		Route::get('/sellVoucher/{id}',[App\Http\Controllers\VoucherController::class, 'sellVoucher'])->name('sellVoucher');
		Route::get('/findItems/{id}',[App\Http\Controllers\VoucherController::class, 'findItems'])->name('findItems');
		Route::post('/getitems',[App\Http\Controllers\VoucherController::class, 'getitems'])->name('getitems');
		Route::get('/dupVoucher/{id}',[App\Http\Controllers\VoucherController::class, 'dupVoucher'])->name('dupVoucher');
		Route::get('/editVoucher/{id}',[App\Http\Controllers\VoucherController::class, 'editVoucher'])->name('editVoucher');
		Route::post('/updateVoucher',[App\Http\Controllers\VoucherController::class, 'updateVoucher'])->name('updateVoucher');
		Route::get('/deleteVoucher/{id}',[App\Http\Controllers\VoucherController::class, 'deleteVoucher'])->name('deleteVoucher');

	});

	//service
	Route::group(['prefix' => 'service'], function(){
		Route::get('/',[App\Http\Controllers\ServicesController::class, 'index'])->name('service')->middleware(['can:services']);
		Route::get('/services/add',[App\Http\Controllers\ServicesController::class, 'add'])->name('addService');
		Route::post('/services/ajaxAdd', [App\Http\Controllers\ServicesController::class, 'ajaxAdd'])->name('ajaxAdd');
		Route::get('/services/getservice/{id}', [App\Http\Controllers\ServicesController::class, 'getservice'])->name('getservice');
		Route::post('/services/editservice', [App\Http\Controllers\ServicesController::class, 'editservice'])->name('editservice');
		Route::post('/services/deleteServicePrice', [App\Http\Controllers\ServicesController::class, 'deleteServicePrice'])->name('deleteServicePrice');

		Route::post('/services/addcat', [App\Http\Controllers\ServicesController::class, 'addcat'])->name('addcat');
		Route::get('/services/getcat/{id}', [App\Http\Controllers\ServicesController::class, 'getcat'])->name('getcat');
		Route::post('/services/editcat', [App\Http\Controllers\ServicesController::class, 'editcat'])->name('editcat');
		Route::get('/services/deletecat/{id}', [App\Http\Controllers\ServicesController::class, 'deletecat'])->name('deletecat');
		// service sorting 
		Route::post('/services/catorder', [App\Http\Controllers\ServicesController::class, 'catorder'])->name('catorder');
		Route::post('/services/serviceorder', [App\Http\Controllers\ServicesController::class, 'serviceorder'])->name('serviceorder');

		//serice paid plans	
		Route::get('/services/plans', [App\Http\Controllers\ServicesController::class, 'plans'])->name('plans')->middleware(['can:paid_plans']);
		Route::get('/services/addPlans', [App\Http\Controllers\ServicesController::class, 'addPlans'])->name('addPlans');
		Route::post('/services/savePlans', [App\Http\Controllers\ServicesController::class, 'savePlans'])->name('savePlans');
		Route::get('/services/deleteService/{id}', [App\Http\Controllers\ServicesController::class, 'deleteService'])->name('deleteService');
		Route::get('/services/editPlans/{id}', [App\Http\Controllers\ServicesController::class, 'editPlans'])->name('editPlans');
		Route::post('/services/updatePlans', [App\Http\Controllers\ServicesController::class, 'updatePlans'])->name('updatePlans');
		Route::get('/services/deletePlans/{id}', [App\Http\Controllers\ServicesController::class, 'deletePlans'])->name('deletePlans');
		//
		Route::get('/serviceinfoexcel', [App\Http\Controllers\ServicesController::class, 'serviceinfoexcel'])->name('serviceinfoexcel');
		Route::get('/serviceinfocsv', [App\Http\Controllers\ServicesController::class, 'serviceinfocsv'])->name('serviceinfocsv');
		Route::get('/serviceinfopdf', [App\Http\Controllers\ServicesController::class, 'serviceinfopdf'])->name('serviceinfopdf');

	});

	// appointments calander
	Route::group(['prefix' => 'calander'], function(){
		Route::get('/',[App\Http\Controllers\CalanderController::class, 'index'])->name('calander');
		Route::post('/updateAppointmentService',[App\Http\Controllers\CalanderController::class, 'updateAppointmentService'])->name('updateAppointmentService');
		Route::post('/updateAppointmentConfirmation',[App\Http\Controllers\CalanderController::class, 'updateAppointmentConfirmation'])->name('updateAppointmentConfirmation');
		Route::post('/getStaffAppointment',[App\Http\Controllers\CalanderController::class, 'getStaffAppointment'])->name('getStaffAppointment');
		Route::post('/addStaffBlockedTime', [App\Http\Controllers\CalanderController::class, 'addStaffBlockedTime'])->name('addStaffBlockedTime');
		Route::post('/updateStaffBlockedTime', [App\Http\Controllers\CalanderController::class, 'updateStaffBlockedTime'])->name('updateStaffBlockedTime');

		Route::post('/deleteBlockedShift', [App\Http\Controllers\CalanderController::class, 'deleteStaffBlockedTime'])->name('deleteBlockedShift');


		Route::get('/fetchCalendarEvents', [App\Http\Controllers\CalanderController::class, 'fetchCalendarEvents'])->name('fetchCalendarEvents');
		
		Route::post('/rescheduleAppointment', [App\Http\Controllers\CalanderController::class, 'rescheduleAppointment'])->name('rescheduleAppointment');
	});

	// appointments
	Route::group(['prefix' => 'appointments'], function(){
		Route::get('/new/{locationId?}',[App\Http\Controllers\AppointmentsController::class, 'createAppointment'])->name('createAppointment');
		Route::post('/addAppointment',[App\Http\Controllers\AppointmentsController::class, 'createNewAppointment'])->name('createNewAppointment');
		Route::post('/getStaffPriceDetails',[App\Http\Controllers\AppointmentsController::class, 'getStaffPriceDetails'])->name('getStaffPriceDetails');
		Route::post('/checkRepeatAppointment',[App\Http\Controllers\AppointmentsController::class, 'checkForRepeatAppointment'])->name('checkForRepeatAppointment');
		Route::post('/searchClient',[App\Http\Controllers\AppointmentsController::class, 'searchClients'])->name('searchClients');
		Route::post('/getClientHistory',[App\Http\Controllers\AppointmentsController::class, 'getClientInformation'])->name('getClientInformation');
		Route::post('/blockClient',[App\Http\Controllers\AppointmentsController::class, 'blockClient'])->name('blockClient');
		Route::post('/unblockClient',[App\Http\Controllers\AppointmentsController::class, 'unblockClient'])->name('unblockClient');
		Route::get('/view/{id}',[App\Http\Controllers\AppointmentsController::class, 'viewAppointment'])->name('viewAppointment');
		Route::post('/changeAppointmentStatus',[App\Http\Controllers\AppointmentsController::class, 'appointmentStatus'])->name('appointmentStatus');
		Route::post('/cancelAppointment',[App\Http\Controllers\AppointmentsController::class, 'cancelAppointment'])->name('cancelAppointment');
		Route::post('/appointmentNoshow',[App\Http\Controllers\AppointmentsController::class, 'appointmentNoshow'])->name('appointmentNoshow');
		Route::post('/editAppointmentNote',[App\Http\Controllers\AppointmentsController::class, 'editAppointmentNote'])->name('editAppointmentNote');
		Route::get('/edit/{id}',[App\Http\Controllers\AppointmentsController::class, 'editAppointment'])->name('editAppointment');
		Route::post('/editAppointmentDetails',[App\Http\Controllers\AppointmentsController::class, 'editAppointmentInfo'])->name('editAppointmentInfo');
		Route::post('/removeAppointmentService',[App\Http\Controllers\AppointmentsController::class, 'removeAppointmentService'])->name('removeAppointmentService');
	});


	Route::get('/checkout/{locationId?}/{type?}/{id?}',[App\Http\Controllers\CheckoutController::class, 'checkoutAppointment'])->name('checkoutAppointment');
	Route::get('/checkout/refund/{locationId?}/{type?}/{id?}',[App\Http\Controllers\CheckoutController::class, 'refundInvoice'])->name('refundInvoice');
	Route::post('/saveRefundInvoice',[App\Http\Controllers\CheckoutController::class, 'createRefundInvoice'])->name('createRefundInvoice');
	Route::post('/createvoucher',[App\Http\Controllers\CheckoutController::class, 'createSaleVoucher'])->name('createSaleVoucher');
	Route::post('/getservicebycat',[App\Http\Controllers\CheckoutController::class, 'getServiceByCategory'])->name('getServiceByCategory');
	Route::post('/getproductbycat',[App\Http\Controllers\CheckoutController::class, 'getServiceByProduct'])->name('getServiceByProduct');
	Route::post('/getplan',[App\Http\Controllers\CheckoutController::class, 'getPaidPlan'])->name('getPaidPlan');
	Route::post('/additemcheckout',[App\Http\Controllers\CheckoutController::class, 'addItemToCheckout'])->name('addItemToCheckout');
	Route::post('/saveitem',[App\Http\Controllers\CheckoutController::class, 'saveCheckoutItem'])->name('saveCheckoutItem');
	Route::post('/getCustomerHistory',[App\Http\Controllers\CheckoutController::class, 'getCustomerInformation'])->name('getCustomerInformation');
	Route::get('/invoices/{id?}',[App\Http\Controllers\AppointmentsController::class, 'viewInvoice'])->name('viewInvoice');
	Route::get('/applyPayment/{id?}',[App\Http\Controllers\AppointmentsController::class, 'applyPayment'])->name('applyPayment');
	Route::post('/sendInvoiceMail',[App\Http\Controllers\AppointmentsController::class, 'sendInvoiceMail'])->name('sendInvoiceMail');
	Route::post('/voidInvoice',[App\Http\Controllers\AppointmentsController::class, 'markInvoiceVoid'])->name('markInvoiceVoid');
	Route::get('/saveInvoicePdf/{id}',[App\Http\Controllers\AppointmentsController::class, 'saveInvoicePdf'])->name('saveInvoicePdf');
	Route::post('/searchCustomer',[App\Http\Controllers\CheckoutController::class, 'searchCustomers'])->name('searchCustomers');
	Route::post('/searchVoucher',[App\Http\Controllers\CheckoutController::class, 'searchVoucherCode'])->name('searchVoucherCode');

	Route::post('/payUnpaidInvoice',[App\Http\Controllers\CheckoutController::class, 'payUnpaidInvoice'])->name('payUnpaidInvoice');

	Route::post('/printInvoice',[App\Http\Controllers\AppointmentsController::class, 'printInvoice'])->name('printInvoice');
	Route::get('/sendVoucherEmail/{id}',[App\Http\Controllers\AppointmentsController::class, 'sendVoucherEmail'])->name('sendVoucherEmail');
	Route::post('/sendVoucherInEmail',[App\Http\Controllers\AppointmentsController::class, 'sendVoucherInEmail'])->name('sendVoucherInEmail');
	Route::get('/printDownloadVoucher/{id}',[App\Http\Controllers\AppointmentsController::class, 'printDownloadVoucher'])->name('printDownloadVoucher');
	Route::post('/printDownloadedVoucher',[App\Http\Controllers\AppointmentsController::class, 'printDownloadedVoucher'])->name('printDownloadedVoucher');

	// marketing
	Route::group(['prefix' => 'marketing', 'middleware' => 'can:marketing'], function(){
		Route::get('/',[App\Http\Controllers\MarketingController::class, 'index'])->name('marketingCampaign');
		Route::get('/smart_campaigns',[App\Http\Controllers\MarketingController::class, 'smart_campaigns'])->name('smart_campaigns');
		Route::get('/add_campaigns/{id}',[App\Http\Controllers\MarketingController::class, 'add_campaigns'])->name('add_campaigns');
		Route::get('/edit_campaigns/{id}',[App\Http\Controllers\MarketingController::class, 'edit_campaigns'])->name('edit_campaigns');
		Route::get('/marketing_blast_messages',[App\Http\Controllers\MarketingController::class, 'marketing_blast_messages'])->name('marketing_blast_messages');
		Route::get('/add_email_message',[App\Http\Controllers\MarketingController::class, 'add_email_message'])->name('add_email_message');

		Route::post('/uploadCampaignImage',[App\Http\Controllers\MarketingController::class, 'uploadCampaignImage'])->name('uploadCampaignImage');
		Route::post('/deleteCampaignImage',[App\Http\Controllers\MarketingController::class, 'deleteCampaignImage'])->name('deleteCampaignImage');

		Route::post('/saveCampaign',[App\Http\Controllers\MarketingController::class, 'saveCampaign'])->name('saveCampaign');
		Route::post('/changeSalesStatus',[App\Http\Controllers\MarketingController::class, 'changeSalesStatus'])->name('changeSalesStatus');
		Route::post('/loadMarketingPreview',[App\Http\Controllers\MarketingController::class, 'loadMarketingPreview'])->name('loadMarketingPreview');
		Route::get('/add_sms_text_message',[App\Http\Controllers\MarketingController::class, 'add_sms_text_message'])->name('add_sms_text_message');
		Route::get('/edit_sms_message/{id}',[App\Http\Controllers\MarketingController::class, 'edit_sms_message'])->name('edit_sms_message');
		Route::get('/edit_email_message/{id}',[App\Http\Controllers\MarketingController::class, 'edit_email_message'])->name('edit_email_message');
		
		Route::post('/saveSMSBlast',[App\Http\Controllers\MarketingController::class, 'saveSMSBlast'])->name('saveSMSBlast');
		Route::post('/saveAsDraftSMSBlast',[App\Http\Controllers\MarketingController::class, 'saveAsDraftSMSBlast'])->name('saveAsDraftSMSBlast');
		
		Route::post('/deleteBlastMessages',[App\Http\Controllers\MarketingController::class, 'deleteBlastMessages'])->name('deleteBlastMessages');

		Route::post('/uploadEmailImage',[App\Http\Controllers\MarketingController::class, 'uploadEmailImage'])->name('uploadEmailImage');

		Route::post('/saveEmailBlast',[App\Http\Controllers\MarketingController::class, 'saveEmailBlast'])->name('saveEmailBlast');
		Route::post('/saveAsDraftEmailBlast',[App\Http\Controllers\MarketingController::class, 'saveAsDraftEmailBlast'])->name('saveAsDraftEmailBlast');
		
		Route::get('/SendSMS',[App\Http\Controllers\MarketingController::class, 'SendSMS'])->name('SendSMS');
		Route::get('/SendEmail',[App\Http\Controllers\MarketingController::class, 'SendEmail'])->name('SendEmail');
		Route::get('/QuickUpdate',[App\Http\Controllers\MarketingController::class, 'QuickUpdate'])->name('QuickUpdate');
		Route::get('/SpecialOffer',[App\Http\Controllers\MarketingController::class, 'SpecialOffer'])->name('SpecialOffer');
		Route::get('/CampaignEmail',[App\Http\Controllers\MarketingController::class, 'CampaignEmail'])->name('CampaignEmail');
		Route::post('/getFilteredClients',[App\Http\Controllers\MarketingController::class, 'getFilteredClients'])->name('getFilteredClients');
		Route::get('/getAllClients',[App\Http\Controllers\MarketingController::class, 'getAllClients'])->name('getAllClients');
		Route::post('/sendTestEmailBlast',[App\Http\Controllers\MarketingController::class, 'sendTestEmailBlast'])->name('sendTestEmailBlast');
		Route::post('/sendTestSMSBlast',[App\Http\Controllers\MarketingController::class, 'sendTestSMSBlast'])->name('sendTestSMSBlast');
		Route::post('/sendCampaignEmail',[App\Http\Controllers\MarketingController::class, 'sendCampaignEmail'])->name('sendCampaignEmail');

		Route::get('/overview/{id?}',[App\Http\Controllers\MarketingController::class, 'campaign_overview'])->name('campaign_overview');
		Route::get('/message_overview/{type}/{id}',[App\Http\Controllers\MarketingController::class, 'message_overview'])->name('message_overview');
		
		Route::post('/addCustomerPaymentCard',[App\Http\Controllers\MarketingController::class, 'addCustomerPaymentCard'])->name('addCustomerPaymentCard');
		Route::post('/cloneEmailBlastMessage',[App\Http\Controllers\MarketingController::class, 'cloneEmailBlastMessage'])->name('cloneEmailBlastMessage');
		Route::post('/cloneSmsBlastMessage',[App\Http\Controllers\MarketingController::class, 'cloneSmsBlastMessage'])->name('cloneSmsBlastMessage');

		Route::post('/resetCampaign',[App\Http\Controllers\MarketingController::class, 'resetCampaign'])->name('resetCampaign');
	});

	// Route::get('SendSMS/{to}/{from}/{body}',[App\Http\Controllers\API\TelnyxController::class, 'sendsms'])->name('send');
	//consultation form 
	Route::group(['prefix' => 'conForm', 'middleware' => 'can:manage_consultation_forms'], function()
	{
		Route::get('/',[App\Http\Controllers\ConsultationFormController::class, 'index'])->name('index');
		Route::get('/showconForm',[App\Http\Controllers\ConsultationFormController::class, 'showconForm'])->name('showconForm');
		Route::post('/getconform',[App\Http\Controllers\ConsultationFormController::class, 'getconform'])->name('getconform');
		Route::get('/showCreateform',[App\Http\Controllers\ConsultationFormController::class, 'showCreateform'])->name('showCreateform');
		Route::post('/conFormClientInfo',[App\Http\Controllers\ConsultationFormController::class, 'conFormClientInfo'])->name('conFormClientInfo');
		Route::get('/geteditconform/{id}',[App\Http\Controllers\ConsultationFormController::class, 'geteditconform'])->name('geteditconform');
		Route::post('/updateconform',[App\Http\Controllers\ConsultationFormController::class, 'updateConsultationFormDetails'])->name('updateconform');
		Route::get('/preview/{id}',[App\Http\Controllers\ConsultationFormController::class, 'preview'])->name('preview');
		Route::get('/overview/{id}',[App\Http\Controllers\ConsultationFormController::class, 'overview'])->name('overview');
		
		Route::get('/activeform/{id}',[App\Http\Controllers\ConsultationFormController::class, 'activeform'])->name('activeform');
		Route::get('/deactiveform/{id}',[App\Http\Controllers\ConsultationFormController::class, 'deactiveform'])->name('deactiveform');

		Route::post('/getConsultationForms',[App\Http\Controllers\ConsultationFormController::class, 'getConsultationForms'])->name('getConsultationForms');
		Route::get('/completeConsultationForm/{consultationId?}', [App\Http\Controllers\ConsultationFormController::class, 'completeConsultationForm'])->name('completeConsultationForm');
		Route::post('/completeSaveConsultationForm', [App\Http\Controllers\ConsultationFormController::class, 'completeSaveConsultationForm'])->name('completeSaveConsultationForm');
		Route::get('/viewClientConsultationForm/{consultationId?}', [App\Http\Controllers\ConsultationFormController::class, 'viewClientConsultationForm'])->name('viewClientConsultationForm');
		Route::post('/printClientConsultationForm', [App\Http\Controllers\ConsultationFormController::class, 'printClientConsultationForm'])->name('printClientConsultationForm');
		Route::post('/consultationFormEmailReminder', [App\Http\Controllers\ConsultationFormController::class, 'consultationFormEmailReminder'])->name('consultationFormEmailReminder');
		
		Route::post('/searchForServiceCategory', [App\Http\Controllers\ConsultationFormController::class, 'searchForServiceCategory'])->name('searchForServiceCategory');
		
		// dj created routes 
		Route::get('/addConsultationForm',[App\Http\Controllers\ConsultationFormController::class, 'addConsultationForm'])->name('addConsultationForm');
		Route::post('/saveConsultationFormDetails', [App\Http\Controllers\ConsultationFormController::class, 'saveConsultationFormDetails'])->name('saveConsultationFormDetails');
		Route::get('/editConsultationForm/{id?}',[App\Http\Controllers\ConsultationFormController::class, 'editConsultationForm'])->name('editConsultationForm');
	});

	//sales module
	Route::group(['prefix' => 'sales'], function()
	{
		Route::get('/dailySale',[App\Http\Controllers\SalesController::class, 'dailySale'])->name('dailySale');
		Route::post('/getDailysaleFilter',[App\Http\Controllers\SalesController::class, 'getDailysaleFilter'])->name('getDailysaleFilter');
		Route::post('/getDailySalesPDF',[App\Http\Controllers\SalesController::class, 'getDailySalesPDF'])->name('getDailySalesPDF');
		Route::get('/appointmentsList',[App\Http\Controllers\SalesController::class, 'appointmentsList'])->name('appointmentsList')->middleware(['can:sales_appointments']);
		Route::post('/getSalesAppointmentList',[App\Http\Controllers\SalesController::class, 'getSalesAppointmentList'])->name('getSalesAppointmentList');
		Route::get('/salesList',[App\Http\Controllers\SalesController::class, 'salesList'])->name('salesList')->middleware(['can:sales_invoices']);
		Route::post('/getSalesInvoiceList',[App\Http\Controllers\SalesController::class, 'getSalesInvoiceList'])->name('getSalesInvoiceList');
		Route::post('/getVoucherServices',[App\Http\Controllers\SalesController::class, 'getVoucherServices'])->name('getVoucherServices');
		Route::get('/vouchers',[App\Http\Controllers\SalesController::class, 'vouchers'])->name('vouchers')->middleware(['can:sales_vouchers']);
		Route::post('/getSalesVoucherList',[App\Http\Controllers\SalesController::class, 'getSalesVoucherList'])->name('getSalesVoucherList');
		Route::get('/paidPlans',[App\Http\Controllers\SalesController::class, 'paidPlans'])->name('paidPlans');
		Route::post('/getPaidplanList',[App\Http\Controllers\SalesController::class, 'getPaidplanList'])->name('getPaidplanList');
		Route::post('/appointmentdownloadExcel',[App\Http\Controllers\SalesController::class, 'appointmentdownloadExcel'])->name('appointmentdownloadExcel');
		Route::post('/appointmentdownloadcsv',[App\Http\Controllers\SalesController::class, 'appointmentdownloadcsv'])->name('appointmentdownloadcsv');
		Route::post('/appointmentdownloadpdf',[App\Http\Controllers\SalesController::class, 'appointmentdownloadpdf'])->name('appointmentdownloadpdf');
		Route::post('/invoicesdownloadExcel',[App\Http\Controllers\SalesController::class, 'invoicesdownloadExcel'])->name('invoicesdownloadExcel');
		Route::post('/invoicesdownloadcsv',[App\Http\Controllers\SalesController::class, 'invoicesdownloadcsv'])->name('invoicesdownloadcsv');
		Route::post('/invoicedownloadpdf',[App\Http\Controllers\SalesController::class, 'invoicedownloadpdf'])->name('invoicedownloadpdf');
		Route::post('/vouchersdownloadExcel',[App\Http\Controllers\SalesController::class, 'vouchersdownloadExcel'])->name('vouchersdownloadExcel');
		Route::post('/vouchersdownloadcsv',[App\Http\Controllers\SalesController::class, 'vouchersdownloadcsv'])->name('vouchersdownloadcsv');
		Route::post('/vouchersdownloadpdf',[App\Http\Controllers\SalesController::class, 'vouchersdownloadpdf'])->name('vouchersdownloadpdf');
		
		Route::post('/dailySalesExcelExport',[App\Http\Controllers\SalesController::class, 'dailySalesExcelExport'])->name('dailySalesExcelExport');
		Route::post('/dailySalesCsvExport',[App\Http\Controllers\SalesController::class, 'dailySalesCsvExport'])->name('dailySalesCsvExport');
	});

	// client Message module
	Route::group(['prefix' => 'clientMessage'], function()
	{
		Route::get('/',[App\Http\Controllers\ClientsMessagesController::class, 'index'])->name('clientMessage')->middleware(['can:messages']);
		Route::post('/getmessage',[App\Http\Controllers\ClientsMessagesController::class, 'getmessage'])->name('getmessage');
		Route::get('/clientMessageSetting',[App\Http\Controllers\ClientsMessagesController::class, 'clientMessageSetting'])->name('clientMessageSetting');
		// new appointment
		Route::get('/newAppoinmentNoti',[App\Http\Controllers\ClientsMessagesController::class, 'newAppoinmentNoti'])->name('newAppoinmentNoti');
		Route::post('/sendTestEmail',[App\Http\Controllers\ClientsMessagesController::class, 'testEmail'])->name('testEmail');
		Route::post('/sendTestSms',[App\Http\Controllers\ClientsMessagesController::class, 'testSms'])->name('testSms');
		Route::post('/updateNewAppoNoti',[App\Http\Controllers\ClientsMessagesController::class, 'updateNewAppoNoti'])->name('updateNewAppoNoti');
		// Reminder Notification
		Route::get('/remiderNotification',[App\Http\Controllers\ClientsMessagesController::class, 'remiderNotification'])->name('remiderNotification');
		Route::post('/updateRemiderNoti',[App\Http\Controllers\ClientsMessagesController::class, 'updateRemiderNoti'])->name('updateRemiderNoti');
		// Reschedule Notification
		Route::get('/rescheduleNotification',[App\Http\Controllers\ClientsMessagesController::class, 'rescheduleNotification'])->name('rescheduleNotification');
		Route::post('/updateRescheduleNoti',[App\Http\Controllers\ClientsMessagesController::class, 'updateRescheduleNoti'])->name('updateRescheduleNoti');
		// Thank you Notification 
		Route::get('/thankyouNotification',[App\Http\Controllers\ClientsMessagesController::class, 'thankyouNotification'])->name('thankyouNotification');
		Route::post('/updatethankyouNotifi',[App\Http\Controllers\ClientsMessagesController::class, 'updatethankyouNotifi'])->name('updatethankyouNotifi');
		// Cancellation Notification
		Route::get('/cancellationNotification',[App\Http\Controllers\ClientsMessagesController::class, 'cancellationNotification'])->name('cancellationNotification');
		Route::post('/updatecancellationNotifi',[App\Http\Controllers\ClientsMessagesController::class, 'updatecancellationNotifi'])->name('updatecancellationNotifi');
		//No Show Notification
		Route::get('/noShowNotification',[App\Http\Controllers\ClientsMessagesController::class, 'noShowNotification'])->name('noShowNotification');
		Route::post('/updatenoShowNotifi',[App\Http\Controllers\ClientsMessagesController::class, 'updatenoShowNotifi'])->name('updatenoShowNotifi');
		//Tipping Notification
		Route::get('/tippingNotification',[App\Http\Controllers\ClientsMessagesController::class, 'tippingNotification'])->name('tippingNotification');
		Route::post('/updatetippingNotifi',[App\Http\Controllers\ClientsMessagesController::class, 'updatetippingNotifi'])->name('updatetippingNotifi');
		//enable disable notification
		Route::get('/desablenotification',[App\Http\Controllers\ClientsMessagesController::class, 'desablenotification'])->name('desablenotification');
		Route::get('/enablenotification',[App\Http\Controllers\ClientsMessagesController::class, 'enablenotification'])->name('enablenotification');
		
	});

	Route::group(['prefix' => 'online_booking'], function(){
		Route::get('/',[App\Http\Controllers\OnlineBookingController::class, 'index'])->name('onlineBooking')->middleware(['can:online_booking']);
		Route::get('/online_profile',[App\Http\Controllers\OnlineBookingController::class, 'online_profile'])->name('online_profile');
		Route::get('/edit_profile/{id?}/{makeLocationOnline?}',[App\Http\Controllers\OnlineBookingController::class, 'edit_online_profile'])->name('edit_online_profile');
		Route::post('/saveOnlineProfile',[App\Http\Controllers\OnlineBookingController::class, 'saveOnlineProfile'])->name('saveOnlineProfile');
		Route::post('/deleteLocationImage',[App\Http\Controllers\OnlineBookingController::class, 'deleteLocationImage'])->name('deleteLocationImage');
		Route::get('/settings',[App\Http\Controllers\OnlineBookingController::class, 'settings'])->name('online_settings');
		Route::post('/saveOnlineSetting',[App\Http\Controllers\OnlineBookingController::class, 'saveOnlineSetting'])->name('saveOnlineSetting');
		Route::post('/makeLocationOffline',[App\Http\Controllers\OnlineBookingController::class, 'makeLocationOffline'])->name('makeLocationOffline');
		Route::get('/confirmOnlineLocation/{locationId}',[App\Http\Controllers\OnlineBookingController::class, 'confirmOnlineLocation'])->name('confirmOnlineLocation');
		Route::post('/confirmOnlineLocationPost',[App\Http\Controllers\OnlineBookingController::class, 'confirmOnlineLocationPost'])->name('confirmOnlineLocationPost');
		Route::get('/profileListingSuccessful/{locationId}',[App\Http\Controllers\OnlineBookingController::class, 'profileListingSuccessful'])->name('profileListingSuccessful');
		Route::match(array('GET', 'POST'),'/clientReview',[App\Http\Controllers\OnlineBookingController::class, 'clientReview'])->name('clientReview');
		Route::post('/updateReviewStatus', [App\Http\Controllers\OnlineBookingController::class, 'updateReviewStatus'])->name('updateReviewStatus');
		Route::get('/button_links',[App\Http\Controllers\OnlineBookingController::class, 'button_links'])->name('button_links');
		Route::get('/get_booking_link', [App\Http\Controllers\OnlineBookingController::class, 'bookingLink'])->name('get_booking_link');

	});

	Route::group(['prefix' => 'overview'], function(){
		Route::get('/',[App\Http\Controllers\OverviewController::class, 'index'])->name('overviewModule');/*->middleware(['can:online_booking'])*/
		Route::get('/wallet',[App\Http\Controllers\OverviewController::class, 'wallet'])->name('overviewWallet');
		/*Route::get('/online_profile',[App\Http\Controllers\OverviewController::class, 'online_profile'])->name('online_profile');
		Route::get('/edit_profile/{id?}/{makeLocationOnline?}',[App\Http\Controllers\OverviewController::class, 'edit_online_profile'])->name('edit_online_profile');
		Route::post('/saveOnlineProfile',[App\Http\Controllers\OverviewController::class, 'saveOnlineProfile'])->name('saveOnlineProfile');*/
	});

	// client Blast messages module
	Route::group(['prefix' => 'BlastMessages'], function()
	{
		Route::get('/',[App\Http\Controllers\BlastMessagesController::class, 'index'])->name('showBlastMessages');

	});
	
	//sales module
	Route::group(['prefix' => 'analytics'], function(){
		Route::get('/',[App\Http\Controllers\AnalyticsController::class, 'index'])->name('analyticsHome')->middleware(['can:all_reports']);
		Route::post('/getAnalyticsReportFilter',[App\Http\Controllers\AnalyticsController::class, 'getAnalyticsReportFilter'])->name('getAnalyticsReportFilter');
		//
		Route::post('/getAnalyticsTotalAppointments',[App\Http\Controllers\AnalyticsController::class, 'getAnalyticsTotalAppointments'])->name('getAnalyticsTotalAppointments');
		Route::post('/getAnalyticsTotalSales',[App\Http\Controllers\AnalyticsController::class, 'getAnalyticsTotalSales'])->name('getAnalyticsTotalSales');
		Route::post('/getAnalyticsOccupancy',[App\Http\Controllers\AnalyticsController::class, 'getAnalyticsOccupancy'])->name('getAnalyticsOccupancy');
		Route::post('/getAnalyticsClientRetention',[App\Http\Controllers\AnalyticsController::class, 'getAnalyticsClientRetention'])->name('getAnalyticsClientRetention');
		//
		Route::get('/list',[App\Http\Controllers\AnalyticsController::class, 'reportList'])->name('reportList');
		
		Route::get('/financesSummary',[App\Http\Controllers\AnalyticsController::class, 'financesSummary'])->name('financesSummary');
		Route::post('/getFinancesSummary',[App\Http\Controllers\AnalyticsController::class, 'getFinancesSummary'])->name('getFinancesSummary');
		Route::post('/getFinancesSummaryPDF',[App\Http\Controllers\AnalyticsController::class, 'getFinancesSummaryPDF'])->name('getFinancesSummaryPDF');
		Route::post('/getFinancesSummaryCSV',[App\Http\Controllers\AnalyticsController::class, 'getFinancesSummaryCSV'])->name('getFinancesSummaryCSV');
		Route::post('/getFinancesSummaryExcel',[App\Http\Controllers\AnalyticsController::class, 'getFinancesSummaryExcel'])->name('getFinancesSummaryExcel');
		
		
		Route::get('/paymentsSummary',[App\Http\Controllers\AnalyticsController::class, 'paymentsSummary'])->name('paymentsSummary');
		Route::post('/getPaymentSummary',[App\Http\Controllers\AnalyticsController::class, 'getPaymentSummary'])->name('getPaymentSummary');
		Route::post('/getPaymentSummaryPDF',[App\Http\Controllers\AnalyticsController::class, 'getPaymentSummaryPDF'])->name('getPaymentSummaryPDF');
		Route::post('/getPaymentSummaryCSV',[App\Http\Controllers\AnalyticsController::class, 'getPaymentSummaryCSV'])->name('getPaymentSummaryCSV');
		Route::post('/getPaymentSummaryExcel',[App\Http\Controllers\AnalyticsController::class, 'getPaymentSummaryExcel'])->name('getPaymentSummaryExcel');
		
		Route::get('/paymentsLog',[App\Http\Controllers\AnalyticsController::class, 'paymentsLog'])->name('paymentsLog');
		Route::post('/getPaymentLog',[App\Http\Controllers\AnalyticsController::class, 'getPaymentLog'])->name('getPaymentLog');
		Route::post('/getPaymentLogCSV',[App\Http\Controllers\AnalyticsController::class, 'getPaymentLogCSV'])->name('getPaymentLogCSV');
		
		Route::get('/taxesSummary',[App\Http\Controllers\AnalyticsController::class, 'taxesSummary'])->name('taxesSummary');
		Route::post('/getTaxesSummary',[App\Http\Controllers\AnalyticsController::class, 'getTaxesSummary'])->name('getTaxesSummary');
		Route::post('/getTaxesSummaryPDF',[App\Http\Controllers\AnalyticsController::class, 'getTaxesSummaryPDF'])->name('getTaxesSummaryPDF');
		Route::post('/getTaxesSummaryCSV',[App\Http\Controllers\AnalyticsController::class, 'getTaxesSummaryCSV'])->name('getTaxesSummaryCSV');
		Route::post('/getTaxesSummaryExcel',[App\Http\Controllers\AnalyticsController::class, 'getTaxesSummaryExcel'])->name('getTaxesSummaryExcel');
		
		Route::get('/tipCollections',[App\Http\Controllers\AnalyticsController::class, 'tipCollections'])->name('tipCollections');
		Route::post('/getTipsCollected',[App\Http\Controllers\AnalyticsController::class, 'getTipsCollected'])->name('getTipsCollected');
		Route::post('/getTipsCollectedPDF',[App\Http\Controllers\AnalyticsController::class, 'getTipsCollectedPDF'])->name('getTipsCollectedPDF');
		Route::post('/getTipsCollectedCSV',[App\Http\Controllers\AnalyticsController::class, 'getTipsCollectedCSV'])->name('getTipsCollectedCSV');
		Route::post('/getTipsCollectedExcel',[App\Http\Controllers\AnalyticsController::class, 'getTipsCollectedExcel'])->name('getTipsCollectedExcel');
		
		Route::get('/discountsSummary',[App\Http\Controllers\AnalyticsController::class, 'discountsSummary'])->name('discountsSummary');
		Route::post('/getDiscountSummary',[App\Http\Controllers\AnalyticsController::class, 'getDiscountSummary'])->name('getDiscountSummary');
		Route::post('/getDiscountSummaryByProduct',[App\Http\Controllers\AnalyticsController::class, 'getDiscountSummaryByProduct'])->name('getDiscountSummaryByProduct');
		Route::post('/getDiscountSummaryByServices',[App\Http\Controllers\AnalyticsController::class, 'getDiscountSummaryByServices'])->name('getDiscountSummaryByServices');
		Route::post('/getDiscountSummaryByStaff',[App\Http\Controllers\AnalyticsController::class, 'getDiscountSummaryByStaff'])->name('getDiscountSummaryByStaff');
		Route::post('/getDiscountSummaryByType',[App\Http\Controllers\AnalyticsController::class, 'getDiscountSummaryByType'])->name('getDiscountSummaryByType');
		Route::post('/getDiscountSummaryPDF',[App\Http\Controllers\AnalyticsController::class, 'getDiscountSummaryPDF'])->name('getDiscountSummaryPDF');
		Route::post('/getDiscountSummaryCSV',[App\Http\Controllers\AnalyticsController::class, 'getDiscountSummaryCSV'])->name('getDiscountSummaryCSV');
		Route::post('/getDiscountSummaryExcel',[App\Http\Controllers\AnalyticsController::class, 'getDiscountSummaryExcel'])->name('getDiscountSummaryExcel');
		
		Route::get('/outstandingInvoices',[App\Http\Controllers\AnalyticsController::class, 'outstandingInvoices'])->name('outstandingInvoices');
		Route::post('/getOutstandingInvoices',[App\Http\Controllers\AnalyticsController::class, 'getOutstandingInvoices'])->name('getOutstandingInvoices');
		Route::post('/getOutstandingInvoicePDF',[App\Http\Controllers\AnalyticsController::class, 'getOutstandingInvoicePDF'])->name('getOutstandingInvoicePDF');
		Route::post('/getOutstandingInvoiceCSV',[App\Http\Controllers\AnalyticsController::class, 'getOutstandingInvoiceCSV'])->name('getOutstandingInvoiceCSV');
		Route::post('/getOutstandingInvoiceExcel',[App\Http\Controllers\AnalyticsController::class, 'getOutstandingInvoiceExcel'])->name('getOutstandingInvoiceExcel');
		
		Route::get('/stockOnHand',[App\Http\Controllers\AnalyticsController::class, 'stockOnHand'])->name('stockOnHand');
		Route::get('/productSalesPerformance',[App\Http\Controllers\AnalyticsController::class, 'productSalesPerformance'])->name('productSalesPerformance');
		Route::get('/stockMovementLog',[App\Http\Controllers\AnalyticsController::class, 'stockMovementLog'])->name('stockMovementLog');
		Route::get('/stockMovementSummary',[App\Http\Controllers\AnalyticsController::class, 'stockMovementSummary'])->name('stockMovementSummary');
		Route::get('/productConsumption',[App\Http\Controllers\AnalyticsController::class, 'productConsumption'])->name('productConsumption');
		
		Route::get('/appointmentsSummary',[App\Http\Controllers\AnalyticsController::class, 'appointmentsSummary'])->name('appointmentsSummary');
		
		Route::get('/appointmentCancellations',[App\Http\Controllers\AnalyticsController::class, 'appointmentCancellations'])->name('appointmentCancellations');
		Route::post('/getAppointmentCancellations',[App\Http\Controllers\AnalyticsController::class, 'getAppointmentCancellations'])->name('getAppointmentCancellations');
		Route::post('/getAppointmentCancellationsSummary',[App\Http\Controllers\AnalyticsController::class, 'getAppointmentCancellationsSummary'])->name('getAppointmentCancellationsSummary');
		Route::post('/getAppointmentCancellationsPDF',[App\Http\Controllers\AnalyticsController::class, 'getAppointmentCancellationsPDF'])->name('getAppointmentCancellationsPDF');
		Route::post('/getAppointmentCancellationsCSV',[App\Http\Controllers\AnalyticsController::class, 'getAppointmentCancellationsCSV'])->name('getAppointmentCancellationsCSV');
		Route::post('/getAppointmentCancellationsExcel',[App\Http\Controllers\AnalyticsController::class, 'getAppointmentCancellationsExcel'])->name('getAppointmentCancellationsExcel');
		
		Route::get('/clientsList',[App\Http\Controllers\AnalyticsController::class, 'clientsList'])->name('clientsList');
		Route::post('/getClientList',[App\Http\Controllers\AnalyticsController::class, 'getClientList'])->name('getClientList');
		Route::post('/getClientListPDF',[App\Http\Controllers\AnalyticsController::class, 'getClientListPDF'])->name('getClientListPDF');
		Route::post('/getClientListCSV',[App\Http\Controllers\AnalyticsController::class, 'getClientListCSV'])->name('getClientListCSV');
		Route::post('/getClientListExcel',[App\Http\Controllers\AnalyticsController::class, 'getClientListExcel'])->name('getClientListExcel');
		
		Route::get('/clientRetention',[App\Http\Controllers\AnalyticsController::class, 'clientRetention'])->name('clientRetention');
		Route::post('/getClientRetention',[App\Http\Controllers\AnalyticsController::class, 'getClientRetention'])->name('getClientRetention');
		Route::post('/getClientRetentionPDF',[App\Http\Controllers\AnalyticsController::class, 'getClientRetentionPDF'])->name('getClientRetentionPDF');
		Route::post('/getClientRetentionCSV',[App\Http\Controllers\AnalyticsController::class, 'getClientRetentionCSV'])->name('getClientRetentionCSV');
		Route::post('/getClientRetentionExcel',[App\Http\Controllers\AnalyticsController::class, 'getClientRetentionExcel'])->name('getClientRetentionExcel');
		
		Route::get('/staffWorkingHours',[App\Http\Controllers\AnalyticsController::class, 'staffWorkingHours'])->name('staffWorkingHours');
		Route::post('/getStaffWorkingHoursReport',[App\Http\Controllers\AnalyticsController::class, 'getStaffWorkingHoursReport'])->name('getStaffWorkingHoursReport');
		Route::post('/getStaffWorkingHoursPDF',[App\Http\Controllers\AnalyticsController::class, 'getStaffWorkingHoursPDF'])->name('getStaffWorkingHoursPDF');
		Route::post('/getStaffWorkingHoursCSV',[App\Http\Controllers\AnalyticsController::class, 'getStaffWorkingHoursCSV'])->name('getStaffWorkingHoursCSV');
		Route::post('/getStaffWorkingHoursExcel',[App\Http\Controllers\AnalyticsController::class, 'getStaffWorkingHoursExcel'])->name('getStaffWorkingHoursExcel');
		
		Route::get('/salesByItem',[App\Http\Controllers\AnalyticsController::class, 'salesByItem'])->name('salesByItem');
		Route::post('/getSalesByItem',[App\Http\Controllers\AnalyticsController::class, 'getSalesByItem'])->name('getSalesByItem');
		Route::post('/getSalesByItemPDF',[App\Http\Controllers\AnalyticsController::class, 'getSalesByItemPDF'])->name('getSalesByItemPDF');
		Route::post('/getSalesByItemCSV',[App\Http\Controllers\AnalyticsController::class, 'getSalesByItemCSV'])->name('getSalesByItemCSV');
		Route::post('/getSalesByItemExcel',[App\Http\Controllers\AnalyticsController::class, 'getSalesByItemExcel'])->name('getSalesByItemExcel');
		
		Route::get('/salesByType',[App\Http\Controllers\AnalyticsController::class, 'salesByType'])->name('salesByType');
		
		Route::get('/salesByService',[App\Http\Controllers\AnalyticsController::class, 'salesByService'])->name('salesByService');
		Route::post('/getSalesByService',[App\Http\Controllers\AnalyticsController::class, 'getSalesByService'])->name('getSalesByService');
		Route::post('/getSalesByServicePDF',[App\Http\Controllers\AnalyticsController::class, 'getSalesByServicePDF'])->name('getSalesByServicePDF');
		Route::post('/getSalesByServiceCSV',[App\Http\Controllers\AnalyticsController::class, 'getSalesByServiceCSV'])->name('getSalesByServiceCSV');
		Route::post('/getSalesByServiceExcel',[App\Http\Controllers\AnalyticsController::class, 'getSalesByServiceExcel'])->name('getSalesByServiceExcel');
		
		Route::get('/salesByProduct',[App\Http\Controllers\AnalyticsController::class, 'salesByProduct'])->name('salesByProduct');
		Route::post('/getSalesByProduct',[App\Http\Controllers\AnalyticsController::class, 'getSalesByProduct'])->name('getSalesByProduct');
		Route::post('/getSalesByProductPDF',[App\Http\Controllers\AnalyticsController::class, 'getSalesByProductPDF'])->name('getSalesByProductPDF');
		Route::post('/getSalesByProductCSV',[App\Http\Controllers\AnalyticsController::class, 'getSalesByProductCSV'])->name('getSalesByProductCSV');
		Route::post('/getSalesByProductExcel',[App\Http\Controllers\AnalyticsController::class, 'getSalesByProductExcel'])->name('getSalesByProductExcel');
		
		Route::get('/salesByLocation',[App\Http\Controllers\AnalyticsController::class, 'salesByLocation'])->name('salesByLocation');
		Route::post('/getSalesByLocation',[App\Http\Controllers\AnalyticsController::class, 'getSalesByLocation'])->name('getSalesByLocation');
		Route::post('/getSalesByLocationPDF',[App\Http\Controllers\AnalyticsController::class, 'getSalesByLocationPDF'])->name('getSalesByLocationPDF');
		Route::post('/getSalesByLocationCSV',[App\Http\Controllers\AnalyticsController::class, 'getSalesByLocationCSV'])->name('getSalesByLocationCSV');
		Route::post('/getSalesByLocationExcel',[App\Http\Controllers\AnalyticsController::class, 'getSalesByLocationExcel'])->name('getSalesByLocationExcel');
		
		Route::get('/salesByChannel',[App\Http\Controllers\AnalyticsController::class, 'salesByChannel'])->name('salesByChannel');
		
		Route::get('/salesByClient',[App\Http\Controllers\AnalyticsController::class, 'salesByClient'])->name('salesByClient');
		Route::post('/getSalesByClient',[App\Http\Controllers\AnalyticsController::class, 'getSalesByClient'])->name('getSalesByClient');
		Route::post('/getSalesByClientPDF',[App\Http\Controllers\AnalyticsController::class, 'getSalesByClientPDF'])->name('getSalesByClientPDF');
		Route::post('/getSalesByClientCSV',[App\Http\Controllers\AnalyticsController::class, 'getSalesByClientCSV'])->name('getSalesByClientCSV');
		Route::post('/getSalesByClientExcel',[App\Http\Controllers\AnalyticsController::class, 'getSalesByClientExcel'])->name('getSalesByClientExcel');
		
		Route::get('/salesByStaffBreakdown',[App\Http\Controllers\AnalyticsController::class, 'salesByStaffBreakdown'])->name('salesByStaffBreakdown');
		
		Route::get('/salesByStaff',[App\Http\Controllers\AnalyticsController::class, 'salesByStaff'])->name('salesByStaff');
		Route::post('/getSalesByStaff',[App\Http\Controllers\AnalyticsController::class, 'getSalesByStaff'])->name('getSalesByStaff');
		Route::post('/getSalesByStaffPDF',[App\Http\Controllers\AnalyticsController::class, 'getSalesByStaffPDF'])->name('getSalesByStaffPDF');
		Route::post('/getSalesByStaffCSV',[App\Http\Controllers\AnalyticsController::class, 'getSalesByStaffCSV'])->name('getSalesByStaffCSV');
		Route::post('/getSalesByStaffExcel',[App\Http\Controllers\AnalyticsController::class, 'getSalesByStaffExcel'])->name('getSalesByStaffExcel');
		
		Route::get('/salesByHour',[App\Http\Controllers\AnalyticsController::class, 'salesByHour'])->name('salesByHour');
		Route::get('/salesByHourOfDay',[App\Http\Controllers\AnalyticsController::class, 'salesByHourOfDay'])->name('salesByHourOfDay');
		
		Route::get('/salesByDay',[App\Http\Controllers\AnalyticsController::class, 'salesByDay'])->name('salesByDay');
		Route::post('/getSalesByDay',[App\Http\Controllers\AnalyticsController::class, 'getSalesByDay'])->name('getSalesByDay');
		Route::post('/getSalesByDayPDF',[App\Http\Controllers\AnalyticsController::class, 'getSalesByDayPDF'])->name('getSalesByDayPDF');
		Route::post('/getSalesByDayCSV',[App\Http\Controllers\AnalyticsController::class, 'getSalesByDayCSV'])->name('getSalesByDayCSV');
		Route::post('/getSalesByDayExcel',[App\Http\Controllers\AnalyticsController::class, 'getSalesByDayExcel'])->name('getSalesByDayExcel');
		
		Route::get('/salesByMonth',[App\Http\Controllers\AnalyticsController::class, 'salesByMonth'])->name('salesByMonth');
		Route::post('/getSalesByMonth',[App\Http\Controllers\AnalyticsController::class, 'getSalesByMonth'])->name('getSalesByMonth');
		Route::post('/getSalesByMonthPDF',[App\Http\Controllers\AnalyticsController::class, 'getSalesByMonthPDF'])->name('getSalesByMonthPDF');
		Route::post('/getSalesByMonthCSV',[App\Http\Controllers\AnalyticsController::class, 'getSalesByMonthCSV'])->name('getSalesByMonthCSV');
		Route::post('/getSalesByMonthExcel',[App\Http\Controllers\AnalyticsController::class, 'getSalesByMonthExcel'])->name('getSalesByMonthExcel');
		
		Route::get('/salesByQuarter',[App\Http\Controllers\AnalyticsController::class, 'salesByQuarter'])->name('salesByQuarter');
		
		Route::get('/salesByYear',[App\Http\Controllers\AnalyticsController::class, 'salesByYear'])->name('salesByYear');
		Route::post('/getSalesByYear',[App\Http\Controllers\AnalyticsController::class, 'getSalesByYear'])->name('getSalesByYear');
		Route::post('/getSalesByYearPDF',[App\Http\Controllers\AnalyticsController::class, 'getSalesByYearPDF'])->name('getSalesByYearPDF');
		Route::post('/getSalesByYearCSV',[App\Http\Controllers\AnalyticsController::class, 'getSalesByYearCSV'])->name('getSalesByYearCSV');
		Route::post('/getSalesByYearExcel',[App\Http\Controllers\AnalyticsController::class, 'getSalesByYearExcel'])->name('getSalesByYearExcel');
		
		Route::get('/tipsByStaff',[App\Http\Controllers\AnalyticsController::class, 'tipsByStaff'])->name('tipsByStaff');
		Route::post('/getTipsByStaff',[App\Http\Controllers\AnalyticsController::class, 'getTipsByStaff'])->name('getTipsByStaff');
		Route::post('/getTipsByStaffPDF',[App\Http\Controllers\AnalyticsController::class, 'getTipsByStaffPDF'])->name('getTipsByStaffPDF');
		Route::post('/getTipsByStaffCSV',[App\Http\Controllers\AnalyticsController::class, 'getTipsByStaffCSV'])->name('getTipsByStaffCSV');
		Route::post('/getTipsByStaffExcel',[App\Http\Controllers\AnalyticsController::class, 'getTipsByStaffExcel'])->name('getTipsByStaffExcel');
		
		Route::get('/staffCommission',[App\Http\Controllers\AnalyticsController::class, 'staffCommission'])->name('staffCommission');
		Route::post('/getStaffCommission',[App\Http\Controllers\AnalyticsController::class, 'getStaffCommission'])->name('getStaffCommission');
		Route::post('/getStaffCommissionPDF',[App\Http\Controllers\AnalyticsController::class, 'getStaffCommissionPDF'])->name('getStaffCommissionPDF');
		Route::post('/getStaffCommissionCSV',[App\Http\Controllers\AnalyticsController::class, 'getStaffCommissionCSV'])->name('getStaffCommissionCSV');
		Route::post('/getStaffCommissionExcel',[App\Http\Controllers\AnalyticsController::class, 'getStaffCommissionExcel'])->name('getStaffCommissionExcel');
		
		Route::get('/staffCommissionDetailed',[App\Http\Controllers\AnalyticsController::class, 'staffCommissionDetailed'])->name('staffCommissionDetailed');
		Route::post('/getStaffCommissionDetailed',[App\Http\Controllers\AnalyticsController::class, 'getStaffCommissionDetailed'])->name('getStaffCommissionDetailed');
		Route::post('/getStaffCommissionDetailedPDF',[App\Http\Controllers\AnalyticsController::class, 'getStaffCommissionDetailedPDF'])->name('getStaffCommissionDetailedPDF');
		Route::post('/getStaffCommissionDetailedCSV',[App\Http\Controllers\AnalyticsController::class, 'getStaffCommissionDetailedCSV'])->name('getStaffCommissionDetailedCSV');
		Route::post('/getStaffCommissionDetailedExcel',[App\Http\Controllers\AnalyticsController::class, 'getStaffCommissionDetailedExcel'])->name('getStaffCommissionDetailedExcel');
		
		Route::get('/stockOnHand',[App\Http\Controllers\AnalyticsController::class, 'stockOnHand'])->name('stockOnHand');
		Route::post('/getStockOnHand',[App\Http\Controllers\AnalyticsController::class, 'getStockOnHand'])->name('getStockOnHand');
		Route::post('/getStockOnHandPDF',[App\Http\Controllers\AnalyticsController::class, 'getStockOnHandPDF'])->name('getStockOnHandPDF');
		Route::post('/getStockOnHandCSV',[App\Http\Controllers\AnalyticsController::class, 'getStockOnHandCSV'])->name('getStockOnHandCSV');
		Route::post('/getStockOnHandExcel',[App\Http\Controllers\AnalyticsController::class, 'getStockOnHandExcel'])->name('getStockOnHandExcel');
		
		Route::get('/productSalesPerformance',[App\Http\Controllers\AnalyticsController::class, 'productSalesPerformance'])->name('productSalesPerformance');
		Route::post('/getProductSalesPerformance',[App\Http\Controllers\AnalyticsController::class, 'getProductSalesPerformance'])->name('getProductSalesPerformance');
		Route::post('/getProductSalesPerformancePDF',[App\Http\Controllers\AnalyticsController::class, 'getProductSalesPerformancePDF'])->name('getProductSalesPerformancePDF');
		Route::post('/getProductSalesPerformanceCSV',[App\Http\Controllers\AnalyticsController::class, 'getProductSalesPerformanceCSV'])->name('getProductSalesPerformanceCSV');
		Route::post('/getProductSalesPerformanceExcel',[App\Http\Controllers\AnalyticsController::class, 'getProductSalesPerformanceExcel'])->name('getProductSalesPerformanceExcel');
		
		Route::get('/stockMovementLog',[App\Http\Controllers\AnalyticsController::class, 'stockMovementLog'])->name('stockMovementLog');
		Route::post('/getStockMovementLog',[App\Http\Controllers\AnalyticsController::class, 'getStockMovementLog'])->name('getStockMovementLog');
		Route::post('/getStockMovementLogPDF',[App\Http\Controllers\AnalyticsController::class, 'getStockMovementLogPDF'])->name('getStockMovementLogPDF');
		Route::post('/getStockMovementLogCSV',[App\Http\Controllers\AnalyticsController::class, 'getStockMovementLogCSV'])->name('getStockMovementLogCSV');
		Route::post('/getStockMovementLogExcel',[App\Http\Controllers\AnalyticsController::class, 'getStockMovementLogExcel'])->name('getStockMovementLogExcel');
		
		Route::get('/productConsumption',[App\Http\Controllers\AnalyticsController::class, 'productConsumption'])->name('productConsumption');
		Route::post('/getProductConsumption',[App\Http\Controllers\AnalyticsController::class, 'getProductConsumption'])->name('getProductConsumption');
		Route::post('/getProductConsumptionPDF',[App\Http\Controllers\AnalyticsController::class, 'getProductConsumptionPDF'])->name('getProductConsumptionPDF');
		Route::post('/getProductConsumptionCSV',[App\Http\Controllers\AnalyticsController::class, 'getProductConsumptionCSV'])->name('getProductConsumptionCSV');
		Route::post('/getProductConsumptionExcel',[App\Http\Controllers\AnalyticsController::class, 'getProductConsumptionExcel'])->name('getProductConsumptionExcel');
		
		Route::get('/taxesSummary',[App\Http\Controllers\AnalyticsController::class, 'taxesSummary'])->name('taxesSummary');
		Route::post('/getTaxesSummary',[App\Http\Controllers\AnalyticsController::class, 'getTaxesSummary'])->name('getTaxesSummary');
		Route::post('/getTaxesSummaryPDF',[App\Http\Controllers\AnalyticsController::class, 'getTaxesSummaryPDF'])->name('getTaxesSummaryPDF');
		Route::post('/getTaxesSummaryCSV',[App\Http\Controllers\AnalyticsController::class, 'getTaxesSummaryCSV'])->name('getTaxesSummaryCSV');
		Route::post('/getTaxesSummaryExcel',[App\Http\Controllers\AnalyticsController::class, 'getTaxesSummaryExcel'])->name('getTaxesSummaryExcel');
		
		Route::get('/salesLog',[App\Http\Controllers\AnalyticsController::class, 'salesLog'])->name('salesLog');
		
		Route::get('/vouchersOutstandingBalance',[App\Http\Controllers\AnalyticsController::class, 'vouchersOutstandingBalance'])->name('vouchersOutstandingBalance');
		Route::get('/voucherSales',[App\Http\Controllers\AnalyticsController::class, 'voucherSales'])->name('voucherSales');
		Route::get('/voucherRedemptions',[App\Http\Controllers\AnalyticsController::class, 'voucherRedemptions'])->name('voucherRedemptions');
		
		Route::get('/roster',[App\Http\Controllers\AnalyticsController::class, 'roster'])->name('roster');
		// Route::get('/tips',[App\Http\Controllers\AnalyticsController::class, 'tips'])->name('tips');
		Route::get('/commissionSummary',[App\Http\Controllers\AnalyticsController::class, 'commissionSummary'])->name('commissionSummary');
		Route::get('/commissionDetailed',[App\Http\Controllers\AnalyticsController::class, 'commissionDetailed'])->name('commissionDetailed');
	});	
});
Route::get('/tilled_form',[App\Http\Controllers\TilledController::class, 'index']);
Route::get('/submitTilled/{accId}',[App\Http\Controllers\TilledController::class, 'submitTilled'])->name('submitTilled');