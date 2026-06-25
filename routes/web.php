<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminFundraiserController;
use App\Http\Controllers\AdminDonorController;
use App\Http\Controllers\FundraiserController;
use App\Http\Controllers\AdminProgramController;
use App\Http\Controllers\PublicProgramController;
use App\Http\Controllers\ParticipationAdminController;
use App\Http\Controllers\ParticipationPublicController;

/*
|--------------------------------------------------------------------------
| Public Section
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('home');
});

// Public programs listing
Route::get('/programs', [PublicProgramController::class, 'index'])
    ->name('programs.index');

// Public program detail
Route::get('/programs/{id}', [PublicProgramController::class, 'show'])
    ->name('programs.show');

// Public fundraisers listing
Route::get('/fundraisers', [FundraiserController::class, 'index'])
    ->name('fundraisers');

// Public fundraiser detail
Route::get('/fundraiser/{id}', [FundraiserController::class, 'detail'])
    ->name('fundraiser.detail');

// Public donation form page
Route::get('/fundraiser/{id}/donate', [FundraiserController::class, 'donateForm'])
    ->name('fundraiser.donate.form');

// Public donation submit
Route::post('/fundraiser/{id}/donate', [FundraiserController::class, 'donate'])
    ->name('fundraiser.donate');


// ===============================
// Public Participation (by token)
// ===============================
Route::get('/participation/{token}', [ParticipationPublicController::class, 'showForm'])
    ->name('participation.public.form');

Route::post('/participation/{token}', [ParticipationPublicController::class, 'submitForm'])
    ->name('participation.public.submit');

Route::get('/participation/{token}/success', [ParticipationPublicController::class, 'success'])
    ->name('participation.public.success');



/*
|--------------------------------------------------------------------------
| Admin Section
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {

    // ✅ LOGIN (PUBLIC)
    Route::get('/login', fn() => view('admin.login'))->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');

    // ✅ ALL ADMIN PAGES MUST LOGIN
    Route::middleware('admin.auth')->group(function () {

        // DASHBOARD
        Route::get('/', fn() => view('admin.admin-index'))->name('index');

        /*
        |--------------------------------------------------------------------------
        | Fundraiser Management
        |--------------------------------------------------------------------------
        */
        Route::get('/fundraisers', [AdminFundraiserController::class, 'index'])->name('fundraisers');
        Route::post('/fundraisers', [AdminFundraiserController::class, 'store'])->name('fundraisers.store');
        Route::get('/fundraisers/{id}/edit', [AdminFundraiserController::class, 'edit'])->name('fundraisers.edit');
        Route::get('/fundraisers/{id}/info', [AdminFundraiserController::class, 'info'])->name('fundraisers.info');
        Route::put('/fundraisers/{id}', [AdminFundraiserController::class, 'update'])->name('fundraisers.update');
        Route::delete('/fundraisers/{id}', [AdminFundraiserController::class, 'destroy'])->name('fundraisers.delete');
        Route::get('/fundraisers/{id}', [AdminFundraiserController::class, 'show'])->name('fundraisers.show');

        // Export fundraiser donors
        Route::get('/fundraisers/{id}/export/print', [AdminFundraiserController::class, 'exportPrint'])
            ->name('fundraisers.export.print');

        Route::get('/fundraisers/{id}/export/excel', [AdminFundraiserController::class, 'exportExcel'])
            ->name('fundraisers.export.excel');

        /*
        |--------------------------------------------------------------------------
        | Donor Management
        |--------------------------------------------------------------------------
        */
        Route::post('/donors', [AdminDonorController::class, 'store'])->name('donors.store');
        Route::get('/donors/{id}', [AdminDonorController::class, 'show'])->name('donors.show');
        Route::put('/donors/{id}', [AdminDonorController::class, 'update'])->name('donors.update');
        Route::delete('/donors/{id}', [AdminDonorController::class, 'destroy'])->name('donors.destroy');

        /*
        |--------------------------------------------------------------------------
        | PROGRAMME MANAGEMENT
        |--------------------------------------------------------------------------
        */

        // Main CRUD
        Route::get('/programs', [AdminProgramController::class, 'index'])->name('programs.index');
        Route::post('/programs', [AdminProgramController::class, 'store'])->name('programs.store');
        Route::get('/programs/{id}', [AdminProgramController::class, 'show'])->name('programs.show');
        Route::get('/programs/{id}/edit', [AdminProgramController::class, 'edit'])->name('programs.edit');
        Route::match(['PUT', 'POST'], '/programs/{id}', [AdminProgramController::class, 'update'])->name('programs.update');
        Route::delete('/programs/{id}', [AdminProgramController::class, 'destroy'])->name('programs.delete');

        // Toggle whole program visibility
        Route::post('/programs/{id}/toggle', [AdminProgramController::class, 'toggleVisibility'])
            ->name('programs.toggle');

        // Toggle section visibility
        Route::post('/programs/{id}/toggle-section', [AdminProgramController::class, 'toggleSection'])
            ->name('programs.toggle-section');

        Route::post('/programs/{id}/remove-file', [AdminProgramController::class, 'removeFile'])
            ->name('programs.remove-file');

        Route::post(
            '/programs/{id}/link-participation',
            [AdminProgramController::class, 'linkParticipation']
        )->name('programs.link-participation');

        /*
        |--------------------------------------------------------------------------
        | PROGRAM SECTION SAVE ROUTES
        |--------------------------------------------------------------------------
        */

        Route::put('/programs/{id}/overview', [AdminProgramController::class, 'saveOverview'])
            ->name('programs.overview.update');

        Route::put('/programs/{id}/tentative', [AdminProgramController::class, 'saveTentative'])
            ->name('programs.tentative.update');

        Route::post('/programs/{id}/vip', [AdminProgramController::class, 'saveVip'])
            ->name('programs.vip.update');

        Route::post('/programs/{id}/participation', [AdminProgramController::class, 'saveParticipation'])
            ->name('programs.participation.update');

        Route::post('/programs/{id}/sponsorship', [AdminProgramController::class, 'saveSponsorship'])
            ->name('admin.programs.sponsorship.update');

        /*
        |--------------------------------------------------------------------------
        | PROGRAMME ITEMS MANAGEMENT (NEW/UPDATED)
        |--------------------------------------------------------------------------
        */

        Route::get('/programs/{id}/programme-items', [AdminProgramController::class, 'getProgrammeItems'])
            ->name('programs.programme-items.index');

        Route::post('/programs/{id}/programme', [AdminProgramController::class, 'saveProgramme'])
            ->name('programs.programme.store');

        Route::get(
            '/programs/{programId}/programme-items/{itemId}/edit',
            [AdminProgramController::class, 'editProgrammeItem']
        )->name('programs.programme-items.edit');

        Route::post(
            '/programs/{programId}/programme-items/{itemId}',
            [AdminProgramController::class, 'updateProgrammeItem']
        )->name('programs.programme-items.update');

        Route::delete(
            '/programs/{programId}/programme-items/{itemId}',
            [AdminProgramController::class, 'deleteProgrammeItem']
        )->name('programs.programme-items.delete');

        Route::post(
            '/programs/{programId}/programme-items/{itemId}/delete-image',
            [AdminProgramController::class, 'deleteProgrammeImage']
        )->name('programs.programme-items.delete-image');

        /*
        |--------------------------------------------------------------------------
        | PHOTO ITEMS MANAGEMENT
        |--------------------------------------------------------------------------
        */

        Route::get('/programs/{id}/photo-items', [AdminProgramController::class, 'getPhotoItems'])
            ->name('programs.photo-items.index');

        Route::post('/programs/{id}/photo', [AdminProgramController::class, 'savePhoto'])
            ->name('programs.photo.store');

        Route::get(
            '/programs/{programId}/photo-items/{itemId}/edit',
            [AdminProgramController::class, 'editPhotoItem']
        )->name('programs.photo-items.edit');

        Route::post(
            '/programs/{programId}/photo-items/{itemId}',
            [AdminProgramController::class, 'updatePhotoItem']
        )->name('programs.photo-items.update');

        Route::delete(
            '/programs/{programId}/photo-items/{itemId}',
            [AdminProgramController::class, 'deletePhotoItem']
        )->name('programs.photo-items.delete');

        /*
        |--------------------------------------------------------------------------
        | Participation Routes - ADMIN (UPDATED FOR MASTER-DETAIL)
        |--------------------------------------------------------------------------
        */

        Route::get('/participations', [ParticipationAdminController::class, 'index'])
            ->name('participations.index');

        Route::post('/participations', [ParticipationAdminController::class, 'store'])
            ->name('participations.store');

        Route::get('/participations/{programme}', [ParticipationAdminController::class, 'info'])
            ->name('participations.info');

        Route::put('/participations/{programme}', [ParticipationAdminController::class, 'update'])
            ->name('participations.update');

        Route::delete('/participations/{programme}', [ParticipationAdminController::class, 'destroy'])
            ->name('participations.delete');

        Route::get('/participations/{programme}/participant-list', [ParticipationAdminController::class, 'participantList'])
            ->name('participations.participant_list');

        Route::get('/participations/{programme}/form', [ParticipationAdminController::class, 'form'])
            ->name('participations.form');

        Route::post('/participations/{programme}/form', [ParticipationAdminController::class, 'saveForm'])
            ->name('participations.form.save');

        Route::delete('/participations/{programme}/form/delete', [ParticipationAdminController::class, 'deleteForm'])
            ->name('participations.form.delete');

        // Master-Detail Package Management
        Route::post('/participations/{programme}/packages', [ParticipationAdminController::class, 'addPackageToProgramme'])
            ->name('participations.packages.store');

        Route::put('/participations/{programme}/packages/{programmePackage}', [ParticipationAdminController::class, 'updatePackageToProgramme'])
            ->name('participations.packages.update');

        Route::delete('/participations/{programme}/packages/{programmePackage}', [ParticipationAdminController::class, 'removePackageFromProgramme'])
            ->name('participations.packages.destroy');

        Route::put('/participations/{programme}/packages/{programmePackage}/price', [ParticipationAdminController::class, 'updatePackagePrice'])
            ->name('participations.packages.update_price');

        Route::post('/participations/{programme}/packages/new', [ParticipationAdminController::class, 'addNewPackageFromForm'])
            ->name('participations.packages.store.new');

        Route::put('/participations/{programme}/packages/{programmePackage}/update', [ParticipationAdminController::class, 'updatePackageFromForm'])
            ->name('participations.packages.update.form');

        // Master-Detail Payment Method Management
        Route::post('/participations/{programme}/payment-methods', [ParticipationAdminController::class, 'addPaymentMethodToProgramme'])
            ->name('participations.payment_methods.store');

        Route::put('/participations/{programme}/payment-methods/{programmePaymentMethod}', [ParticipationAdminController::class, 'updatePaymentMethodToProgramme'])
            ->name('participations.payment_methods.update');

        Route::delete('/participations/{programme}/payment-methods/{programmePaymentMethod}', [ParticipationAdminController::class, 'removePaymentMethodFromProgramme'])
            ->name('participations.payment_methods.destroy');

        Route::post('/participations/{programme}/payment-methods/new', [ParticipationAdminController::class, 'addNewPaymentMethodFromForm'])
            ->name('participations.payment_methods.store.new');

        Route::put('/participations/{programme}/payment-methods/{programmePaymentMethod}/update', [ParticipationAdminController::class, 'updatePaymentMethodFromForm'])
            ->name('participations.payment_methods.update.form');

        // Public link + preview
        Route::post('/participations/{programme}/generate-link', [ParticipationAdminController::class, 'generateLink'])
            ->name('participations.generate_link');

        Route::get('/participations/{programme}/preview', [ParticipationAdminController::class, 'preview'])
            ->name('participations.preview');

        // Export
        Route::get('/participations/{programme}/export/print', [ParticipationAdminController::class, 'exportPrint'])
            ->name('participations.export.print');

        Route::get('/participations/{programme}/export/excel', [ParticipationAdminController::class, 'exportExcel'])
            ->name('participations.export.excel');

        // Submission management
        Route::get('submissions/{submission}/edit', [ParticipationAdminController::class, 'editSubmission'])
            ->name('submissions.edit');

        Route::delete('submissions/{submission}', [ParticipationAdminController::class, 'deleteSubmission'])
            ->name('submissions.delete');

        Route::put('submissions/{submission}/participants', [ParticipationAdminController::class, 'updateParticipants'])
            ->name('submissions.participants.update');

        Route::delete('participants/{participant}', [ParticipationAdminController::class, 'deleteParticipant'])
            ->name('participants.delete');

        Route::put('submissions/{submission}/status', [ParticipationAdminController::class, 'updateStatus'])
            ->name('submissions.status.update');

        Route::prefix('participations')->name('participations.')->group(function () {
            Route::delete(
                '{programme}/qr',
                [ParticipationAdminController::class, 'deleteQR']
            )->name('qr.delete');
        });

        Route::get('/programs/{id}/participant-list', [AdminProgramController::class, 'getParticipantList'])
            ->name('programs.participant-list.get');

        // ✅ Logout (protected)
        Route::post('/logout', function (\Illuminate\Http\Request $request) {
            $request->session()->forget('admin_id');
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('admin.login');
        })->name('logout');
    });
});
