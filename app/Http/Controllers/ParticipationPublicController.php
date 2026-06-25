<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ParticipationProgramme;
use App\Models\ParticipationProgrammePackage;
use App\Models\ProgrammePaymentMethod;
use App\Models\ParticipationSubmission;
use App\Models\ParticipationParticipant;

class ParticipationPublicController extends Controller
{
    /* =====================================================
       HELPER - CALCULATE EXPECTED PARTICIPANTS
    ===================================================== */
    private function expectedParticipants($package, int $quantity): int
    {
        if ($package->package_type === 'multi_person') {
            return max(1, $quantity * (int) ($package->people_per_package ?? 1));
        }
        return max(1, $quantity);
    }

    /* =====================================================
       SHOW PUBLIC FORM (COMMERCIAL vs PARTICIPANT-ONLY)
    ===================================================== */
    public function showForm(string $token)
    {
        $programme = ParticipationProgramme::where('public_token', $token)->firstOrFail();

        // ❌ FORM NOT ACTIVE
        if (!$programme->is_active) {
            return view('participations.form-inactive', compact('programme'));
        }

        /* ===============================
           PACKAGES (PRICE > 0 ONLY)
        =============================== */
        $packages = ParticipationProgrammePackage::where('programme_id', $programme->id)
            ->where('is_active', true)
            ->where('price', '>', 0)
            ->with('package')
            ->orderBy('sort_order')
            ->get();

        /* ===============================
           PAYMENT METHODS
        =============================== */
        $paymentMethods = ProgrammePaymentMethod::where('programme_id', $programme->id)
            ->where('is_active', true)
            ->with('paymentMethod')
            ->get();

        /* ===============================
           COMMERCIAL FLOW FLAG
        =============================== */
        $hasCommercialFlow = $packages->count() > 0 && $paymentMethods->count() > 0;

        /* ===============================
           PACKAGE DATA FOR JS
        =============================== */
        $packageData = [];
        foreach ($packages as $pkg) {
            $packageData[$pkg->id] = [
                'id' => $pkg->id,
                'name' => $pkg->package->name,
                'package_type' => $pkg->package->package_type,
                'price' => (float) $pkg->price,
                'people_per_package' =>
                    $pkg->people_per_package ?? $pkg->package->people_per_package ?? 1,
            ];
        }

        /* ===============================
           PAYMENT DATA FOR JS
        =============================== */
        $paymentData = [];
        foreach ($paymentMethods as $pm) {
            $paymentData[$pm->id] = [
                'id' => $pm->id,
                'bank' => $pm->paymentMethod->bank,
                'account_number' =>
                    $pm->account_number ?? $pm->paymentMethod->account_number,
                'account_name' =>
                    $pm->account_name ?? $pm->paymentMethod->account_name,
            ];
        }

        return view('participations.form', [
            'programme' => $programme,
            'packages' => $packages,
            'paymentMethods' => $paymentMethods,
            'packageData' => $packageData,
            'paymentData' => $paymentData,
            'hasCommercialFlow' => $hasCommercialFlow, // ⭐ IMPORTANT
        ]);
    }

    /* =====================================================
       SUBMIT FORM (DUAL MODE SAFE)
    ===================================================== */
    public function submitForm(Request $request, string $token)
    {
        $programme = ParticipationProgramme::where('public_token', $token)->firstOrFail();

        // ❌ FORM NOT ACTIVE
        if (!$programme->is_active) {
            return back()->withErrors([
                'form' => 'This registration form is no longer accepting submissions.'
            ])->withInput();
        }

        /* ===============================
           DETECT COMMERCIAL FLOW AGAIN
        =============================== */
        $packagesCount = ParticipationProgrammePackage::where('programme_id', $programme->id)
            ->where('is_active', true)
            ->where('price', '>', 0)
            ->count();

        $paymentsCount = ProgrammePaymentMethod::where('programme_id', $programme->id)
            ->where('is_active', true)
            ->count();

        $hasCommercialFlow = $packagesCount > 0 && $paymentsCount > 0;

        /* ===============================
           BASE VALIDATION (ALL MODES)
        =============================== */
        $rules = [
            'company_name' => 'required|string|max:255',
            'officer_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:30',
            'participants' => 'required|array|min:1',
            'participants.*.name' => 'required|string|max:255',
            'participants.*.position' => 'required|string|max:255',
            'supporting_document' => 'nullable|file|max:30720|mimes:pdf,doc,docx,jpg,jpeg,png',

        ];

        /* ===============================
           COMMERCIAL VALIDATION ONLY
        =============================== */
        if ($hasCommercialFlow) {
            $maxKb = (int) ($programme->receipt_max_mb ?? 20) * 1024;

            $rules = array_merge($rules, [
                'package_id' => 'required|exists:participation_programme_packages,id',
                'quantity' => 'required|integer|min:1|max:1000',
                'payment_method_id' => 'required|exists:programme_payment_methods,id',
                'receipt' => "required|file|max:{$maxKb}",
            ]);
        }

        $validated = $request->validate($rules);

        /* ===============================
           FORMAT + BASIC CHECKS
        =============================== */
        $company = strtoupper($validated['company_name']);

        if (!preg_match("/^[A-Z0-9 \.\,&\-\(\)']+$/", $company)) {
            return back()->withErrors([
                'company_name' => 'Invalid company name format.'
            ])->withInput();
        }

        if (!preg_match("/^[A-Za-z0-9 .]+$/", $validated['officer_name'])) {
            return back()->withErrors([
                'officer_name' => 'Invalid officer name.'
            ])->withInput();
        }

        if (!preg_match("/^[0-9]+$/", $validated['phone_number'])) {
            return back()->withErrors([
                'phone_number' => 'Phone number must contain digits only.'
            ])->withInput();
        }

        /* ===============================
           DEFAULT VALUES (PARTICIPANT-ONLY)
        =============================== */
        $programmePackage = null;
        $programmePaymentMethod = null;
        $quantity = 0;
        $expected = count($validated['participants']);
        $total = 0;
        $receiptPath = null;
        $receiptFile = null;
        $supportingPath = null;
        $supportingFile = null;


        /* ===============================
           COMMERCIAL PROCESSING
        =============================== */
        if ($hasCommercialFlow) {

            $programmePackage = ParticipationProgrammePackage::where('id', $validated['package_id'])
                ->where('programme_id', $programme->id)
                ->where('is_active', true)
                ->with('package')
                ->firstOrFail();

            $programmePaymentMethod = ProgrammePaymentMethod::where('id', $validated['payment_method_id'])
                ->where('programme_id', $programme->id)
                ->where('is_active', true)
                ->with('paymentMethod')
                ->firstOrFail();

            $quantity = (int) $validated['quantity'];
            $expected = $this->expectedParticipants($programmePackage, $quantity);

            if (count($validated['participants']) !== $expected) {
                return back()->withErrors([
                    'participants' => "Participants must be exactly {$expected}."
                ])->withInput();
            }

            $total = $programmePackage->price * $quantity;

            $receiptFile = $request->file('receipt');
            $receiptPath = $receiptFile->store('participation/receipts', 'public');
        }

       // ===============================
// SUPPORTING DOCUMENT (PUBLIC)
// ===============================
$supportingPath = null;
$supportingFile = null;

if ($request->hasFile('supporting_document')) {
    $supportingFile = $request->file('supporting_document');
    $supportingPath = $supportingFile->store(
        'participation/supporting-documents',
        'public'
    );
}


        /* ===============================
           CREATE SUBMISSION
        =============================== */
        $submission = ParticipationSubmission::create([
            'programme_id' => $programme->id,
            'company_name' => $company,
            'officer_name' => $validated['officer_name'],
            'phone_number' => $validated['phone_number'],

            'participation_programme_package_id' => $programmePackage?->id,
            'quantity' => $quantity,
            'expected_participants' => $expected,
            'unit_price' => $programmePackage?->price ?? 0,
            'total_price' => $total,

            'programme_payment_method_id' => $programmePaymentMethod?->id,

            'receipt_path' => $receiptPath,
            'receipt_original_name' => $receiptFile?->getClientOriginalName(),
            'receipt_size' => $receiptFile?->getSize(),
            'receipt_mime' => $receiptFile?->getMimeType(),

            'supporting_document_path' => $supportingPath,
            'supporting_document_original' => $supportingFile?->getClientOriginalName(),
            'supporting_document_size' => $supportingFile?->getSize(),
            'supporting_document_mime' => $supportingFile?->getMimeType(),


            'status' => 'pending',
        ]);

        /* ===============================
           CREATE PARTICIPANTS
        =============================== */
        $i = 1;
        foreach ($validated['participants'] as $p) {
            ParticipationParticipant::create([
                'submission_id' => $submission->id,
                'name' => $p['name'],
                'position' => $p['position'],
                'sort_order' => $i++,
            ]);
        }

        return redirect()->route('participation.public.success', $token);
    }

    /* =====================================================
       SUCCESS PAGE
    ===================================================== */
    public function success(string $token)
    {
        $programme = ParticipationProgramme::where('public_token', $token)->firstOrFail();
        return view('participations.success', compact('programme'));
    }
}


