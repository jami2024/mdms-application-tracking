<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\CertificateTemplateController;
use App\Http\Controllers\Admin\DesignationController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\OrganogramController;
use App\Http\Controllers\Admin\ProductGradeController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\UpazilaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WorkflowConfigController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\EstablishmentController;
use App\Http\Controllers\MrpApplicationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

// Fortify registers /login, /logout, /two-factor-challenge,
// /forgot-password, /reset-password, /user/two-factor-authentication.

// Public certificate verification — what a QR-code scan lands on. No auth.
Route::get('/certificates/verify/{certificateNo}', [CertificateController::class, 'verify'])->name('certificates.verify');

Route::middleware(['auth',  'active'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [UserController::class, 'profile'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // Applicant-facing submission modules (Companies own Establishments/Devices/MRP).
    Route::prefix('companies')->name('companies.')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('index');
        Route::get('create', [CompanyController::class, 'create'])->name('create');
        Route::post('/', [CompanyController::class, 'store'])->name('store');
        Route::get('{company}', [CompanyController::class, 'show'])->name('show');
        Route::get('{company}/edit', [CompanyController::class, 'edit'])->name('edit');
        Route::put('{company}', [CompanyController::class, 'update'])->name('update');
        Route::delete('{company}', [CompanyController::class, 'destroy'])->name('destroy');
        Route::post('{company}/submit', [CompanyController::class, 'submit'])->name('submit');
        Route::post('{company}/verify-mobile', [CompanyController::class, 'verifyMobile'])->name('verify-mobile');
        Route::post('{company}/verify-email', [CompanyController::class, 'verifyEmail'])->name('verify-email');

        Route::get('{company}/establishments/create', [EstablishmentController::class, 'create'])->name('establishments.create');
        Route::post('{company}/establishments', [EstablishmentController::class, 'store'])->name('establishments.store');

        Route::get('{company}/devices/create', [DeviceController::class, 'create'])->name('devices.create');
        Route::post('{company}/devices', [DeviceController::class, 'store'])->name('devices.store');

        Route::get('{company}/mrp-applications/create', [MrpApplicationController::class, 'create'])->name('mrp-applications.create');
        Route::post('{company}/mrp-applications', [MrpApplicationController::class, 'store'])->name('mrp-applications.store');
    });

    Route::prefix('establishments')->name('establishments.')->group(function () {
        Route::get('{establishment}', [EstablishmentController::class, 'show'])->name('show');
        Route::get('{establishment}/edit', [EstablishmentController::class, 'edit'])->name('edit');
        Route::put('{establishment}', [EstablishmentController::class, 'update'])->name('update');
        Route::delete('{establishment}', [EstablishmentController::class, 'destroy'])->name('destroy');
        Route::post('{establishment}/submit', [EstablishmentController::class, 'submit'])->name('submit');
    });

    Route::prefix('devices')->name('devices.')->group(function () {
        Route::get('{device}', [DeviceController::class, 'show'])->name('show');
        Route::get('{device}/edit', [DeviceController::class, 'edit'])->name('edit');
        Route::put('{device}', [DeviceController::class, 'update'])->name('update');
        Route::delete('{device}', [DeviceController::class, 'destroy'])->name('destroy');
        Route::post('{device}/submit', [DeviceController::class, 'submit'])->name('submit');

        Route::get('packages/applications', [PackageController::class, 'packagesApplications'])->name('packages.applications');
        Route::get('final-packages/applications', [PackageController::class, 'finalPackagesApplications'])->name('final-packages.applications');

    });

    Route::prefix('mrp-applications')->name('mrp-applications.')->group(function () {
        Route::get('{mrpApplication}', [MrpApplicationController::class, 'show'])->name('show');
        Route::delete('{mrpApplication}', [MrpApplicationController::class, 'destroy'])->name('destroy');
        Route::post('{mrpApplication}/submit', [MrpApplicationController::class, 'submit'])->name('submit');
    });

    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');

    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('{payment}', [PaymentController::class, 'show'])->name('show');
        Route::get('{payment}/sandbox', [PaymentController::class, 'sandbox'])->name('sandbox');
        Route::post('{payment}/callback/success', [PaymentController::class, 'callbackSuccess'])->name('callback.success');
        Route::post('{payment}/callback/fail', [PaymentController::class, 'callbackFail'])->name('callback.fail');
        Route::post('{payment}/callback/cancel', [PaymentController::class, 'callbackCancel'])->name('callback.cancel');
        Route::post('{payment}/reconcile', [PaymentController::class, 'reconcile'])->name('reconcile');
    });
    Route::get('applications/{application}/pay', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('applications/{application}/pay', [PaymentController::class, 'store'])->name('payments.store');

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('applications', [ReportController::class, 'applications'])->name('applications');
        Route::get('applications/export/excel', [ReportController::class, 'applicationsExportExcel'])->name('applications.excel');
        Route::get('applications/export/pdf', [ReportController::class, 'applicationsExportPdf'])->name('applications.pdf');

        Route::get('revenue', [ReportController::class, 'revenue'])->name('revenue');
        Route::get('revenue/export/excel', [ReportController::class, 'revenueExportExcel'])->name('revenue.excel');
        Route::get('revenue/export/pdf', [ReportController::class, 'revenueExportPdf'])->name('revenue.pdf');

        Route::get('renewals', [ReportController::class, 'renewals'])->name('renewals');
        Route::get('renewals/export/excel', [ReportController::class, 'renewalsExportExcel'])->name('renewals.excel');
    });

    // Application review queue + workflow actions — open to any authenticated
    // staff member; the queue itself is scoped to their designation.
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/', [ApplicationController::class, 'index'])->name('index');
        Route::get('{application}', [ApplicationController::class, 'show'])->name('show');
        Route::post('{application}/comment', [ApplicationController::class, 'comment'])->name('comment');
        Route::post('{application}/forward', [ApplicationController::class, 'forward'])->name('forward');
        Route::post('{application}/backward', [ApplicationController::class, 'backward'])->name('backward');
        Route::post('{application}/approve', [ApplicationController::class, 'approve'])->name('approve');
        Route::post('{application}/reject', [ApplicationController::class, 'reject'])->name('reject');

        Route::get('{application}/certificate/create', [CertificateController::class, 'create'])->name('certificate.create');
        Route::post('{application}/certificate', [CertificateController::class, 'store'])->name('certificate.store');
    });

    Route::prefix('certificates')->name('certificates.')->group(function () {
        Route::get('{certificate}', [CertificateController::class, 'show'])->name('show');
        Route::get('{certificate}/download', [CertificateController::class, 'download'])->name('download');
        Route::get('{certificate}/preview', [CertificateController::class, 'preview'])->name('preview');
        Route::post('{certificate}/revoke', [CertificateController::class, 'revoke'])->name('revoke');
    });

    Route::middleware(['role:Admin'])->prefix('admin')->name('admin.')->group(function () {

        Route::get('users/export', [UserController::class, 'export'])->name('users.export');
        Route::post('users/import', [UserController::class, 'import'])->name('users.import');
        Route::put('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::put('users/{user}/status', [UserController::class, 'toggleStatus'])->name('users.status');
        Route::resource('users', UserController::class)->except(['show']);

        Route::resource('roles', RoleController::class)->except(['show']);

        Route::resource('organogram', OrganogramController::class)->only(['index', 'create', 'store', 'destroy']);

        // Common Settings
        Route::resource('designations', DesignationController::class)->except(['show']);
        Route::resource('divisions', DivisionController::class)->except(['show']);
        Route::resource('districts', DistrictController::class)->except(['show']);
        Route::resource('upazilas', UpazilaController::class)->except(['show']);
        Route::resource('product-grades', ProductGradeController::class)->except(['show']);

        // Workflow Config + nested steps
        Route::resource('workflow-configs', WorkflowConfigController::class)->except(['show']);
        Route::post('workflow-configs/{workflow_config}/steps', [WorkflowConfigController::class, 'storeStep'])->name('workflow-configs.steps.store');
        Route::delete('workflow-configs/{workflow_config}/steps/{step}', [WorkflowConfigController::class, 'destroyStep'])->name('workflow-configs.steps.destroy');
        Route::put('workflow-configs/{workflow_config}/steps/{step}/reorder', [WorkflowConfigController::class, 'reorderStep'])->name('workflow-configs.steps.reorder');

        // Certificate Templates
        Route::resource('certificate-templates', CertificateTemplateController::class)->except(['show']);

        // Activity Log
        Route::get('activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
        Route::get('activity-log/export/pdf', [ActivityLogController::class, 'exportPdf'])->name('activity-log.pdf');
        Route::get('activity-log/{activityLog}', [ActivityLogController::class, 'show'])->name('activity-log.show');
    });

    Route::prefix('services')->name('services.')->group(function () {
        Route::get('application/new', [ServiceController::class, 'index'])->name('add-new');
        Route::post('application/new/store', [ServiceController::class, 'applicationNewStore'])->name('applicationNewStore');
        Route::get('application/track', [ServiceController::class, 'applicationTrack'])->name('application-track');
        // demo package certificate download route for testing purposes. This is not a real certificate, just a sample template with dummy data.
        Route::get('certificates/demo-package', [CertificateController::class, 'generatePackagingDemoCertificate'])->name('certificates.generatePackagingDemoCertificate');
    });
    

    // All Phase 0-6 modules are now wired up.
});
