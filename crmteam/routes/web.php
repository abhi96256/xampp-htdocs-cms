<?php
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AccountsController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DuesController;
use App\Http\Controllers\EnquiriesController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\FollowupsController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\UserAccountController;
use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\DprController;
use App\Http\Controllers\OfficePaymentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserAgreementController;
use App\Http\Controllers\MonthlyVisitController;

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

// Superadmin routes
Route::get('/superadmin/login', [\App\Http\Controllers\SuperadminController::class, 'login'])->name('superadmin.login');
Route::post('/superadmin/login', [\App\Http\Controllers\SuperadminController::class, 'postLogin'])->name('superadmin.postLogin');
Route::get('/superadmin/dashboard', [\App\Http\Controllers\SuperadminController::class, 'dashboard'])->name('superadmin.dashboard');
Route::post('/superadmin/logout', [\App\Http\Controllers\SuperadminController::class, 'logout'])->name('superadmin.logout');

// signup
Route::group(['prefix' => '/account'], function () {
    Route::get('signin', [AccountsController::class, 'index'])->name('signin.index');
    Route::post('signin', [AccountsController::class, 'signin'])->name('signin');
    Route::get('register', [AccountsController::class, 'register'])->name('register');
    Route::post('register', [AccountsController::class, 'storeRegister'])->name('register.store');
    Route::get('register/otp', [AccountsController::class, 'showRegisterOtpForm'])->name('register.otp.form');
    Route::post('register/otp', [AccountsController::class, 'verifyRegisterOtp'])->name('register.otp.verify');
    Route::get('2fa', [AccountsController::class, 'two_factor_auth'])->name('signin.2fa');
    Route::post('2fa', [AccountsController::class, 'two_factor_auth'])->name('signin.2fa.post');
	
    Route::match(['get', 'post'], 'signout', [AccountsController::class, 'signout'])->name('signout')->middleware('auth');
    // OTP login routes
    Route::get('login-otp', [AccountsController::class, 'showLoginOtpForm'])->name('login.otp.form');
    Route::post('login-otp', [AccountsController::class, 'verifyLoginOtp'])->name('login.otp.verify');

    // Forgot Password routes (Multi-OTP)
    Route::get('forgot-password', [AccountsController::class, 'forgotPasswordIndex'])->name('forgot-password.index');
    Route::post('forgot-password/send', [AccountsController::class, 'sendForgotPasswordOts'])->name('forgot-password.send');
    Route::post('forgot-password/resend', [AccountsController::class, 'resendForgotPasswordOtp'])->name('forgot-password.resend');
    Route::post('forgot-password/verify', [AccountsController::class, 'verifyForgotPasswordAndReset'])->name('forgot-password.verify');
});

// dashboard routes
Route::group(['prefix' => '/', 'middleware' => 'auth'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
});

// facebook leads retrieval route
Route::group(['prefix' => '/fb'], function () {
    Route::get('/webhook', [EnquiriesController::class, 'storeFbLead'])->name('enquiries.fbLeadHook');
    Route::post('/webhook', [EnquiriesController::class, 'storeFbLead'])->name('enquiries.storeFbLead');
});

// ppc leads retrieval route
Route::group(['prefix' => '/ppc'], function () {
    Route::post('/store', [EnquiriesController::class, 'storePpcLead'])->name('enquiries.storePpcLead');
});

// enquiries routes
Route::group(['prefix' => '/enquiries', 'middleware' => ['auth', 'can:leads_access']], function () {
    Route::get('/', [EnquiriesController::class, 'index'])->name('enquiries.index');
    Route::post('/', [EnquiriesController::class, 'index'])->name('enquiries.index.post');
    Route::post('/date_range', [EnquiriesController::class, 'index'])->name('enquiries.date_range');
	Route::post('/advance_search', [EnquiriesController::class, 'index'])->name('enquiries.advance_search');
    Route::get('/filter/{filter?}', [EnquiriesController::class, 'index'])->name('enquiries.filter');
    Route::get('/{id}/show', [EnquiriesController::class, 'show'])->name('enquiries.show')->middleware('can:leads_show');
    Route::get('/create', [EnquiriesController::class, 'create'])->name('enquiries.create')->middleware('can:leads_create');
	//Route::get('/{user_id}', [EnquiriesController::class, 'index'])->name('enquiries.index');
    Route::get('/import', [EnquiriesController::class, 'import'])->name('enquiries.import')->middleware('can:leads_import');
    Route::post('/store_import', [EnquiriesController::class, 'store_import'])->name('enquiries.store_import')->middleware('can:leads_import');
	Route::get('/export', [EnquiriesController::class, 'export'])->name('enquiries.export')->middleware();
	Route::post('/export', [EnquiriesController::class, 'export'])->name('enquiries.export.post')->middleware();
	Route::get('/2fa', [EnquiriesController::class, 'export'])->name('enquiries.2fa')->middleware();
	Route::post('/2fa', [EnquiriesController::class, 'export'])->name('enquiries.2fa.post')->middleware();
	Route::get('/import_template', [EnquiriesController::class, 'import_template'])->name('enquiries.import_template');
	
    Route::post('/store', [EnquiriesController::class, 'store'])->name('enquiries.store')->middleware('can:leads_create');
    Route::get('/{id}/edit', [EnquiriesController::class, 'edit'])->name('enquiries.edit')->middleware('can:leads_edit');
    Route::post('/{id}/update', [EnquiriesController::class, 'update'])->name('enquiries.update')->middleware('can:leads_edit');
    Route::post('/{id}/update/status', [EnquiriesController::class, 'updateStatus'])->name('enquiries.updateStatus')->middleware('can:leads_edit');
    Route::post('/{id}/destroy', [EnquiriesController::class, 'destroy'])->name('enquiries.destroy')->middleware('can:leads_delete');

    Route::get('/{id}/close', [EnquiriesController::class, 'close'])->name('enquiries.close')->middleware('can:leads_access');
});

// followups routes
Route::group(['prefix' => '/followups', 'middleware' => ['auth', 'can:followup_access']], function () {
    Route::post('/store', [FollowupsController::class, 'store'])->name('followups.store')->middleware('can:followup_create');
    Route::post('/{id}/update', [FollowupsController::class, 'update'])->name('followups.update')->middleware('can:followup_edit');
    Route::post('/{id}/destroy', [FollowupsController::class, 'destroy'])->name('followups.destroy')->middleware('can:followup_delete');
});

// clients routes
Route::group(['prefix' => '/clients', 'middleware' => ['auth', 'can:client_access']], function () {
    Route::get('/', [ClientsController::class, 'index'])->name('clients.index');
    Route::get('/create', [ClientsController::class, 'create'])->name('clients.create');
    Route::post('/store', [ClientsController::class, 'store'])->name('clients.store');
    Route::get('/{id}/edit', [ClientsController::class, 'edit'])->name('clients.edit')->middleware('can:client_edit');
    Route::post('/{id}/update', [ClientsController::class, 'update'])->name('clients.update')->middleware('can:client_edit');
    Route::get('/{id}/show', [ClientsController::class, 'show'])->name('clients.show')->middleware('can:client_show');
    Route::post('/{id}/destroy', [ClientsController::class, 'destroy'])->name('clients.destroy')->middleware('can:client_delete');
    Route::get('/{id}/convert-to-lead', [ClientsController::class, 'convertToLead'])->name('clients.convertToLead');
});

// projects routes
Route::group(['prefix' => '/projects', 'middleware' => ['auth', 'can:project_access']], function () {
    Route::get('/', [ProjectsController::class, 'index'])->name('projects.index');
    Route::get('/create', [ProjectsController::class, 'create'])->name('projects.create')->middleware('can:project_create');
    Route::post('/store', [ProjectsController::class, 'store'])->name('projects.store')->middleware('can:project_create');
    Route::get('/{id}/edit', [ProjectsController::class, 'edit'])->name('projects.edit')->middleware('can:project_edit');
    Route::post('/{id}/update', [ProjectsController::class, 'update'])->name('projects.update')->middleware('can:project_edit');
    Route::get('/{id}/show', [ProjectsController::class, 'show'])->name('projects.show')->middleware('can:project_show');
    Route::post('/{id}/destroy', [ProjectsController::class, 'destroy'])->name('projects.destroy')->middleware('can:project_delete');
});

// dues routes
Route::group(['prefix' => '/dues', 'middleware' => ['auth', 'can:dues_access']], function () {
    Route::get('/', [DuesController::class, 'index'])->name('dues.index');
    Route::get('/create', [DuesController::class, 'create'])->name('dues.create')->middleware('can:dues_create');
    Route::post('/store', [DuesController::class, 'store'])->name('dues.store')->middleware('can:dues_create');
    Route::get('/{id}/edit', [DuesController::class, 'edit'])->name('dues.edit')->middleware('can:dues_edit');
    Route::post('/{id}/update', [DuesController::class, 'update'])->name('dues.update')->middleware('can:dues_edit');
    Route::post('/{id}/pay', [DuesController::class, 'pay'])->name('dues.pay')->middleware('can:dues_edit');
    Route::post('/{id}/destroy', [DuesController::class, 'destroy'])->name('dues.destroy')->middleware('can:dues_delete');
});

// bankaccount routes
Route::group(['prefix' => '/payments', 'middleware' => ['auth', 'can:payment_access']], function () {
    Route::get('/', [PaymentsController::class, 'index'])->name('payments.index');
    Route::get('/create', [PaymentsController::class, 'create'])->name('payments.create')->middleware('can:payment_create');
    Route::post('/store', [PaymentsController::class, 'store'])->name('payments.store')->middleware('can:payment_create');
    Route::get('/{id}/edit', [PaymentsController::class, 'edit'])->name('payments.edit')->middleware('can:payment_edit');
    Route::post('/{id}/update', [PaymentsController::class, 'update'])->name('payments.update')->middleware('can:payment_edit');
    Route::post('/{id}/destroy', [PaymentsController::class, 'destroy'])->name('payments.destroy')->middleware('can:payment_delete');
});

// expenses routes
Route::group(['prefix' => '/expenses', 'middleware' => ['auth', 'can:expenses_access']], function () {
    Route::get('/', [ExpensesController::class, 'index'])->name('expenses.index');
    Route::get('/create', [ExpensesController::class, 'create'])->name('expenses.create')->middleware('can:expenses_create');
    Route::post('/store', [ExpensesController::class, 'store'])->name('expenses.store')->middleware('can:expenses_create');
    Route::get('/{id}/edit', [ExpensesController::class, 'edit'])->name('expenses.edit')->middleware('can:expenses_edit');
    Route::post('/{id}/update', [ExpensesController::class, 'update'])->name('expenses.update')->middleware('can:expenses_edit');
    Route::post('/{id}/destroy', [ExpensesController::class, 'destroy'])->name('expenses.destroy')->middleware('can:expenses_delete');
});
// wallet routes
Route::group(['prefix' => '/wallet', 'middleware' => ['auth', 'can:wallet_access']], function () {
    Route::get('/', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/create', [WalletController::class, 'create'])->name('wallet.create')->middleware('can:wallet_create');
    Route::post('/store', [WalletController::class, 'store'])->name('wallet.store')->middleware('can:wallet_create');
    Route::get('/{id}/edit', [WalletController::class, 'edit'])->name('wallet.edit')->middleware('can:wallet_edit');
    Route::post('/{id}/update', [WalletController::class, 'update'])->name('wallet.update')->middleware('can:wallet_edit');
    Route::post('/{id}/destroy', [WalletController::class, 'destroy'])->name('wallet.destroy')->middleware('can:wallet_delete');
});

// invoices routes
Route::group(['prefix' => '/invoices', 'middleware' => ['auth', 'can:payment_access']], function () {
    Route::get('/', [InvoicesController::class, 'index'])->name('invoices.index');
    Route::get('/export', [InvoicesController::class, 'export'])->name('invoices.export');
    Route::get('/import', [InvoicesController::class, 'import'])->name('invoices.import');
    Route::post('/store_import', [InvoicesController::class, 'store_import'])->name('invoices.store_import');
    Route::get('/import_template', [InvoicesController::class, 'import_template'])->name('invoices.import_template');
	Route::get('/{id}/show', [InvoicesController::class, 'show'])->name('invoices.show');
    Route::get('/{id}/edit', [InvoicesController::class, 'edit'])->name('invoices.edit');
    Route::get('/create', [InvoicesController::class, 'create'])->name('invoices.create')->middleware('can:payment_create');
    Route::post('/store', [InvoicesController::class, 'store'])->name('invoices.store')->middleware('can:payment_create');
    Route::post('/{id}/destroy', [InvoicesController::class, 'destroy'])->name('invoices.destroy')->middleware('can:payment_delete');
	Route::post('/{id}/update', [InvoicesController::class, 'update'])->name('invoices.update');
	Route::get('/{id}/download', [InvoicesController::class, 'download'])->name('invoices.download');
});

// reports routes
 Route::group(['prefix' => '/reports', 'middleware' => ['auth']], function () {
    Route::get('/', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/sell', [ReportsController::class, 'sell'])->name('reports.sell');
    Route::get('/pending', [ReportsController::class, 'pending'])->name('reports.pending');
    Route::get('/incentive', [ReportsController::class, 'incentive'])->name('reports.incentive');
	Route::get('/cincentive', [ReportsController::class, 'cincentive'])->name('reports.cincentive');
	Route::post('/date_range', [ReportsController::class, 'index'])->name('reports.date_range');
	Route::get('/transfer', [ReportsController::class, 'transfer'])->name('reports.transfer');
	Route::get('/expenses', [ReportsController::class, 'expenses'])->name('reports.expenses');
}); 
// reports routes
Route::group(['prefix' => '/activities', 'middleware' => ['auth', 'can:report_access']], function () { 
    Route::get('/', [ActivitiesController::class, 'index'])->name('activities.index');
});

// myaccount routes
Route::group(['prefix' => '/myaccount', 'middleware' => 'auth'], function () {
    Route::get('/', [AccountsController::class, 'myAccount'])->name('myaccount.index');
    Route::post('/{id}/update', [AccountsController::class, 'updateMyPersonalDetails'])->name('myaccount.update');
    Route::post('/{id}/password/change', [AccountsController::class, 'changeMyPassword'])->name('myaccount.changepassword');
	Route::get('/2fa', [AccountsController::class, 'two_factor_auth_pwd'])->name('myaccount.2fa');
	Route::get('/notification', [AccountsController::class, 'notification'])->name('myaccount.navbar-notification');
	Route::post('/notification', [AccountsController::class, 'notification'])->name('myaccount.navbar-notification.post');
	Route::post('/send_notification', [AccountsController::class, 'send_notification'])->name('myaccount.send_notification');
    Route::post('/2fa', [AccountsController::class, 'two_factor_auth_pwd'])->name('myaccount.2fa.post');
    Route::post('/send_password_otp', [AccountsController::class, 'sendPasswordOtp'])->name('myaccount.send_password_otp');
    Route::post('/change_password_with_otp', [AccountsController::class, 'changePasswordWithOtp'])->name('myaccount.change_password_with_otp');
});

// useraccount routes
Route::group(['prefix' => '/useraccount', 'middleware' => ['auth', 'can:user_management_access']], function () {
    Route::get('/', [UserAccountController::class, 'index'])->name('useraccounts.index');
    Route::get('/{id}/show', [UserAccountController::class, 'show'])->name('useraccounts.show');
    Route::get('/create', [UserAccountController::class, 'create'])->name('useraccounts.create');
    Route::post('/store', [UserAccountController::class, 'store'])->name('useraccounts.store');
    Route::get('/{id}/edit', [UserAccountController::class, 'edit'])->name('useraccounts.edit');
    Route::post('/{id}/update', [UserAccountController::class, 'update'])->name('useraccounts.update'); 
	Route::post('/{id}/update_password', [UserAccountController::class, 'update_password'])->name('useraccounts.update_password');
    Route::post('/{id}/update_permissions', [UserAccountController::class, 'updateDirectPermissions'])->name('useraccounts.update_permissions');
    Route::get('/{id}/destroy', [UserAccountController::class, 'destroy'])->name('useraccounts.destroy');
    Route::get('/{id}/accept', [UserAccountController::class, 'acceptUser'])->name('useraccounts.accept');
    Route::get('/{id}/decline', [UserAccountController::class, 'declineUser'])->name('useraccounts.decline');
});

// roles routes
Route::group(['prefix' => '/roles_permissions', 'middleware' => ['auth', 'can:role_access']], function () {
    Route::get('/', [RolesController::class, 'index'])->name('roles.index')->middleware('can:role_access');
    Route::get('/create', [RolesController::class, 'create'])->name('roles.create')->middleware('can:role_access');
    Route::post('/create', [RolesController::class, 'create'])->name('roles.create.post')->middleware('can:role_access');
    Route::get('/{id}/edit', [RolesController::class, 'edit'])->name('roles.edit')->middleware('can:role_access');
    Route::post('/{id}/edit', [RolesController::class, 'edit'])->name('roles.edit.post')->middleware('can:role_access');
});

// team management routes
Route::group(['prefix' => '/teams', 'middleware' => ['auth', 'can:role_access']], function () {
    Route::get('/', [TeamController::class, 'index'])->name('teams.index');
    Route::get('/create', [TeamController::class, 'create'])->name('teams.create');
    Route::post('/store', [TeamController::class, 'store'])->name('teams.store');
    
    // Move specific routes BEFORE parameterized routes to avoid conflicts
    Route::get('/user-permissions/{user_id}', [TeamController::class, 'getUserPermissions'])->name('teams.getUserPermissions');
    Route::get('/role-permissions/{role_id}', [TeamController::class, 'getRolePermissions'])->name('teams.getRolePermissions');
    Route::post('/user-permissions/update', [TeamController::class, 'updateUserPermissions'])->name('teams.updateUserPermissions');
    Route::post('/role-permissions', [TeamController::class, 'updateRolePermissions'])->name('teams.updateRolePermissions');

    Route::get('/{id}/edit', [TeamController::class, 'edit'])->name('teams.edit');
    Route::post('/{id}/update', [TeamController::class, 'update'])->name('teams.update');
    Route::post('/{id}/destroy', [TeamController::class, 'destroy'])->name('teams.destroy');
    Route::post('/{id}/member/add', [TeamController::class, 'addMember'])->name('teams.addMember');
    Route::post('/{id}/member/update', [TeamController::class, 'updateMember'])->name('teams.updateMember');
    Route::post('/{id}/member/remove', [TeamController::class, 'removeMember'])->name('teams.removeMember');
    Route::get('/{id}/hierarchy', [TeamController::class, 'hierarchy'])->name('teams.hierarchy');
});
// transactions route
Route::group(['prefix' => '/transactions', 'middleware' => ['auth', 'can:transaction_access']], function () {
    Route::get('/', [TransactionsController::class, 'index'])->name('transactions.index')->middleware('can:transaction_access');
});

// DPR (Daily Progress Report) routes
Route::group(['prefix' => '/dpr', 'middleware' => ['auth', 'can:dpr_access']], function () {
    Route::get('/', [DprController::class, 'index'])->name('dpr.index');
    Route::get('/create', [DprController::class, 'create'])->name('dpr.create')->middleware('can:dpr_create');
    Route::post('/store', [DprController::class, 'store'])->name('dpr.store')->middleware('can:dpr_create');
    Route::get('/export', [DprController::class, 'export'])->name('dpr.export');
    Route::post('/import', [DprController::class, 'import'])->name('dpr.import');
});

// Office Payment routes
Route::group(['prefix' => '/office_payment', 'middleware' => ['auth', 'can:office_payment_access']], function () {
    Route::get('/', [OfficePaymentController::class, 'index'])->name('office_payment.index');
    Route::get('/create', [OfficePaymentController::class, 'create'])->name('office_payment.create')->middleware('can:office_payment_create');
    Route::get('/export', [OfficePaymentController::class, 'export'])->name('office_payment.export');
    Route::get('/import', [OfficePaymentController::class, 'import'])->name('office_payment.import');
    Route::post('/store_import', [OfficePaymentController::class, 'store_import'])->name('office_payment.store_import');
    Route::get('/import_template', [OfficePaymentController::class, 'import_template'])->name('office_payment.import_template');
    Route::post('/store', [OfficePaymentController::class, 'store'])->name('office_payment.store')->middleware('can:office_payment_create');
    Route::get('/{id}/edit', [OfficePaymentController::class, 'edit'])->name('office_payment.edit')->middleware('can:office_payment_edit');
    Route::post('/{id}/update', [OfficePaymentController::class, 'update'])->name('office_payment.update')->middleware('can:office_payment_edit');
    Route::post('/{id}/destroy', [OfficePaymentController::class, 'destroy'])->name('office_payment.destroy')->middleware('can:office_payment_delete');
});

// Attendance routes
Route::group(['prefix' => '/attendance', 'middleware' => ['auth', 'can:attendance_access']], function () {
    Route::get('/', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/create', [AttendanceController::class, 'create'])->name('attendance.create')->middleware('can:attendance_create');
    Route::post('/store', [AttendanceController::class, 'store'])->name('attendance.store')->middleware('can:attendance_create');
    Route::get('/verify-otp', [AttendanceController::class, 'verifyOtpForm'])->name('attendance.verify_otp');
    Route::get('/verify-otp-form', [AttendanceController::class, 'verifyOtpForm'])->name('attendance.verify_otp_form');
    Route::post('/verify-otp', [AttendanceController::class, 'verifyOtp'])->name('attendance.verify_otp.post');
    Route::get('/{id}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit')->middleware('can:attendance_edit');
    Route::post('/{id}/update', [AttendanceController::class, 'update'])->name('attendance.update')->middleware('can:attendance_edit');
    Route::post('/{id}/destroy', [AttendanceController::class, 'destroy'])->name('attendance.destroy')->middleware('can:attendance_delete');
});

Route::get('/update-hosting', function () {
    // 1. Clear all cache
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');

    // 2. Add new permissions to DB if they don't exist
    $actions = ['access', 'create', 'edit', 'show', 'delete'];
    foreach ($actions as $act) {
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'site_visits ' . $act, 'guard_name' => 'web']);
    }

    return "All Caches Cleared & Missing Permissions Generated Successfully! Your hosting is fully updated.";
});

// ─── Client Verification / Agreements (Admin - Auth Protected) ───
Route::group(['prefix' => '/agreements', 'middleware' => ['auth', 'can:user_management_access']], function () {
    Route::get('/', [UserAgreementController::class, 'index'])->name('agreements.index');
    Route::get('/create', [UserAgreementController::class, 'create'])->name('agreements.create');
    Route::post('/send', [UserAgreementController::class, 'send'])->name('agreements.send');
    Route::post('/{id}/resend', [UserAgreementController::class, 'resend'])->name('agreements.resend');
    Route::get('/{id}/print', [UserAgreementController::class, 'printDoc'])->name('agreements.print');
    Route::post('/{id}/upload-document', [UserAgreementController::class, 'uploadDocument'])->name('agreements.document.upload');
    Route::get('/{id}/download-document', [UserAgreementController::class, 'downloadDocument'])->name('agreements.document.download');
    Route::get('/{id}/view-document', [UserAgreementController::class, 'viewDocument'])->name('agreements.document.view');
    Route::post('/{id}/destroy', [UserAgreementController::class, 'destroy'])->name('agreements.destroy');
});

// ─── Monthly Visits ────────────────────────────────────────────────
Route::group(['prefix' => '/monthly-visits', 'middleware' => ['auth']], function () {
    Route::get('/', [MonthlyVisitController::class, 'index'])->name('monthly_visits.index');
    Route::get('/create', [MonthlyVisitController::class, 'create'])->name('monthly_visits.create');
    Route::post('/store', [MonthlyVisitController::class, 'store'])->name('monthly_visits.store');
    Route::get('/{id}/edit', [MonthlyVisitController::class, 'edit'])->name('monthly_visits.edit');
    Route::post('/{id}/update', [MonthlyVisitController::class, 'update'])->name('monthly_visits.update');
    Route::post('/{id}/destroy', [MonthlyVisitController::class, 'destroy'])->name('monthly_visits.destroy');
});

// ─── Public Signing Pages (No Auth Required) ─────────────────────
Route::get('/verify-agreement/{token}', [UserAgreementController::class, 'verifyPage'])->name('agreements.verify.page');
Route::post('/verify-agreement/{token}/sign', [UserAgreementController::class, 'submitOtp'])->name('agreements.submit');
