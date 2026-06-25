<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\ParticipationProgramme;
use App\Models\Package;
use App\Models\ParticipationProgrammePackage;
use App\Models\PaymentMethod;
use App\Models\ProgrammePaymentMethod;
use App\Models\ParticipationSubmission;
use App\Models\ParticipationParticipant;

use App\Exports\ParticipantListExport;
use Maatwebsite\Excel\Facades\Excel;

class ParticipationAdminController extends Controller
{
    private function ensureAdmin()
    {
        if (!session()->has('admin_id')) {
            return redirect()->route('admin.login');
        }
        return null;
    }

    /* =====================================================
   FORM PAGE - Show Master Templates + Selected Items (UPDATED)
===================================================== */
public function form(Request $request, ParticipationProgramme $programme)
{
    if ($redirect = $this->ensureAdmin()) return $redirect;

    // ✅ Get ALL master packages (library) WITH USAGE INFO
    $allExistingPackages = Package::where('is_active', true)
        ->orderBy('name')
        ->get()
        ->map(function($package) use ($programme) {
            // Check if this package is already used in current programme
            $isUsedInCurrent = $programme->programmePackages()
                ->where('package_id', $package->id)
                ->exists();
            
            // Count total usage across all programmes
            $totalUsage = ParticipationProgrammePackage::where('package_id', $package->id)->count();
            
            return (object) [
                'id' => $package->id,
                'name' => $package->name,
                'package_type' => $package->package_type,
                'price' => $package->default_price,
                'people_per_package' => $package->people_per_package,
                'description' => $package->description,
                'is_used_in_current' => $isUsedInCurrent,
                'total_usage' => $totalUsage,
            ];
        });

    // ✅ Get ALL master payment methods (library) WITH USAGE INFO
    $allExistingPaymentMethods = PaymentMethod::where('is_active', true)
        ->orderBy('bank')
        ->get()
        ->map(function($method) use ($programme) {
            // Check if this payment method is already used in current programme
            $isUsedInCurrent = $programme->programmePaymentMethods()
                ->where('payment_method_id', $method->id)
                ->exists();
            
            // Count total usage across all programmes
            $totalUsage = ProgrammePaymentMethod::where('payment_method_id', $method->id)->count();
            
            return (object) [
                'id' => $method->id,
                'bank' => $method->bank,
                'account_name' => $method->account_name,
                'account_number' => $method->account_number,
                'is_used_in_current' => $isUsedInCurrent,
                'total_usage' => $totalUsage,
            ];
        });

    // ✅ Get selected items for THIS programme (snapshots) WITH OVERRIDE INFO
    $selectedProgrammePackages = $programme->programmePackages()
        ->with('package')
        ->orderBy('sort_order')
        ->get()
        ->map(function($progPkg) {
            // Check if this is an override (values differ from master)
            $isPriceOverride = $progPkg->price != $progPkg->package->default_price;
            $isPeopleOverride = $progPkg->people_per_package != $progPkg->package->people_per_package;
            $isDescOverride = $progPkg->description != $progPkg->package->description;
            
            $isOverride = $isPriceOverride || $isPeopleOverride || $isDescOverride;
            
            // Get master values for comparison
            $masterPrice = $progPkg->package->default_price;
            $masterPeople = $progPkg->package->people_per_package;
            $masterDescription = $progPkg->package->description;
            
            return (object) [
                'id' => $progPkg->id,
                'name' => $progPkg->package->name,
                'package_type' => $progPkg->package->package_type,
                'price' => $progPkg->price,
                'people_per_package' => $progPkg->people_per_package,
                'description' => $progPkg->description,
                'is_locked' => $progPkg->is_locked,
                'is_override' => $isOverride,
                'master_data' => [
                    'default_price' => $masterPrice,
                    'default_people' => $masterPeople,
                    'default_description' => $masterDescription,
                ],
            ];
        });

    // ✅ Get selected payment methods for THIS programme WITH OVERRIDE INFO
    $selectedPaymentMethods = $programme->programmePaymentMethods()
        ->with('paymentMethod')
        ->where('is_active', true)
        ->get()
        ->map(function($progPM) {
            // Check if this is an override
            $isNameOverride = $progPM->account_name != $progPM->paymentMethod->account_name;
            $isNumberOverride = $progPM->account_number != $progPM->paymentMethod->account_number;
            
            $isOverride = $isNameOverride || $isNumberOverride;
            
            // Get master values for comparison
            $masterAccountName = $progPM->paymentMethod->account_name;
            $masterAccountNumber = $progPM->paymentMethod->account_number;
            
            return (object) [
                'id' => $progPM->id,
                'bank' => $progPM->paymentMethod->bank,
                'account_name' => $progPM->account_name ?? $masterAccountName,
                'account_number' => $progPM->account_number ?? $masterAccountNumber,
                'is_override' => $isOverride,
                'master_data' => [
                    'default_account_name' => $masterAccountName,
                    'default_account_number' => $masterAccountNumber,
                ],
            ];
        });

    $publicLink = $programme->public_token 
        ? route('participation.public.form', $programme->public_token) 
        : null;

    return view('admin.participations.form', [
        'programme'                   => $programme,
        'allExistingPackages'         => $allExistingPackages,
        'allExistingPaymentMethods'   => $allExistingPaymentMethods,
        'selectedProgrammePackages'   => $selectedProgrammePackages,
        'selectedPaymentMethods'      => $selectedPaymentMethods,
        'publicLink'                  => $publicLink,
    ]);
}
    /* =====================================================
       ADD PACKAGE TO PROGRAMME (Create Snapshot)
    ===================================================== */
    public function addPackageToProgramme(Request $request, ParticipationProgramme $programme)
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;

        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'price'      => 'required|numeric|min:0',
        ]);

        // ✅ Check if already added
        $exists = ParticipationProgrammePackage::where('programme_id', $programme->id)
            ->where('package_id', $validated['package_id'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'This package is already added to this programme.');
        }

        // ✅ Get master package
        $masterPackage = Package::findOrFail($validated['package_id']);

        // ✅ Create snapshot
        ParticipationProgrammePackage::create([
            'programme_id'       => $programme->id,
            'package_id'         => $validated['package_id'],
            'price'              => $validated['price'],
            'people_per_package' => $masterPackage->people_per_package,
            'is_locked'          => false,
            'sort_order'         => $programme->programmePackages()->count() + 1,
            'is_active'          => true,
        ]);

        return back()->with('success', 'Package added to programme successfully.');
    }
/* =====================================================
   ADD PACKAGE TO PROGRAMME (Create Snapshot with Override Detection)
===================================================== */
public function addNewPackageFromForm(Request $request, ParticipationProgramme $programme)
{
    if ($redirect = $this->ensureAdmin()) return $redirect;

    $validated = $request->validate([
        'label'              => 'required|string|max:255',
        'package_type'       => 'required|in:one_person,multi_person',
        'price'              => 'required|numeric|min:0',
        'people_per_package' => 'nullable|integer|min:1',
        'description'        => 'nullable|string',
        'sort_order'         => 'nullable|integer|min:1',
    ]);

    // Check if package already exists in master
    $existingPackage = Package::where('name', $validated['label'])
        ->where('package_type', $validated['package_type'])
        ->first();

    if ($existingPackage) {
        // Use existing master package
        $masterPackage = $existingPackage;
        
        // Check if already added to this programme
        $alreadyAdded = ParticipationProgrammePackage::where('programme_id', $programme->id)
            ->where('package_id', $masterPackage->id)
            ->exists();
            
        if ($alreadyAdded) {
            return back()->with('error', 'This package is already added to this programme.');
        }
    } else {
        // Create new master package
        $masterPackage = Package::create([
            'name'              => $validated['label'],
            'package_type'      => $validated['package_type'],
            'default_price'     => $validated['price'],
            'people_per_package' => $validated['people_per_package'] ?? null,
            'description'       => $validated['description'] ?? null,
            'is_active'         => true,
        ]);
    }

    // Initialize base data
    $programmePackageData = [
        'programme_id'       => $programme->id,
        'package_id'         => $masterPackage->id,
        'price'              => $validated['price'], // Start with submitted price
        'people_per_package' => $validated['people_per_package'] ?? $masterPackage->people_per_package,
        'description'        => $validated['description'] ?? $masterPackage->description,
        'sort_order'         => $validated['sort_order'] ?? ($programme->programmePackages()->count() + 1),
        'is_locked'          => false,
        'is_active'          => true,
    ];

    // Check if we have overrides for existing package
    if ($existingPackage) {
        // Only override price if it differs
        if (floatval($validated['price']) != floatval($existingPackage->default_price)) {
            $programmePackageData['price'] = $validated['price'];
        } else {
            $programmePackageData['price'] = $existingPackage->default_price;
        }
        
        // Only override people_per_package if it differs
        if (($validated['people_per_package'] ?? null) != $existingPackage->people_per_package) {
            $programmePackageData['people_per_package'] = $validated['people_per_package'] ?? null;
        } else {
            $programmePackageData['people_per_package'] = $existingPackage->people_per_package;
        }
        
        // Only override description if it differs
        if (trim($validated['description'] ?? '') != trim($existingPackage->description ?? '')) {
            $programmePackageData['description'] = $validated['description'] ?? null;
        } else {
            $programmePackageData['description'] = $existingPackage->description;
        }
    }

    ParticipationProgrammePackage::create($programmePackageData);

    return back()->with('success', 'Package added to programme successfully.');
}
    /* =====================================================
       REMOVE PACKAGE FROM PROGRAMME
    ===================================================== */
    public function removePackageFromProgramme(ParticipationProgramme $programme, ParticipationProgrammePackage $programmePackage)
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;

        if ($programmePackage->programme_id !== $programme->id) {
            abort(403);
        }

        // ✅ Check if has submissions
        if ($programmePackage->submissions()->exists()) {
            return back()->with('error', 'Cannot remove package that has submissions.');
        }

        $programmePackage->delete();

        return back()->with('success', 'Package removed from programme.');
    }

    /* =====================================================
       UPDATE PACKAGE PRICE (Before Lock)
    ===================================================== */
    public function updatePackagePrice(Request $request, ParticipationProgramme $programme, ParticipationProgrammePackage $programmePackage)
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;

        if ($programmePackage->programme_id !== $programme->id) {
            abort(403);
        }

        // ✅ Check if locked
        if ($programmePackage->is_locked) {
            return back()->with('error', 'Cannot edit locked package.');
        }

        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
        ]);

        $programmePackage->update([
            'price' => $validated['price'],
        ]);

        return back()->with('success', 'Package price updated successfully.');
    }

    /* =====================================================
       ADD PAYMENT METHOD TO PROGRAMME
    ===================================================== */
    public function addPaymentMethodToProgramme(Request $request, ParticipationProgramme $programme)
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;

        $validated = $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        // ✅ Check if already added
        $exists = ProgrammePaymentMethod::where('programme_id', $programme->id)
            ->where('payment_method_id', $validated['payment_method_id'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'This payment method is already added to this programme.');
        }

        // ✅ Add to programme
        ProgrammePaymentMethod::create([
            'programme_id'       => $programme->id,
            'payment_method_id'  => $validated['payment_method_id'],
            'is_active'          => true,
        ]);

        return back()->with('success', 'Payment method added to programme successfully.');
    }

/* =====================================================
   ADD PAYMENT METHOD FROM FORM (With Override Detection)
===================================================== */
public function addNewPaymentMethodFromForm(Request $request, ParticipationProgramme $programme)
{
    if ($redirect = $this->ensureAdmin()) return $redirect;

    $validated = $request->validate([
        'bank'           => 'required|string|max:255',
        'account_name'   => 'required|string|max:255',
        'account_number' => 'required|string|max:255',
    ]);

    // Check if payment method already exists in master (by account number)
    $existingPayment = PaymentMethod::where('account_number', $validated['account_number'])->first();

    if ($existingPayment) {
        // Same account number exists - update the master with new bank/name
        // (Assuming account number uniquely identifies a payment method)
        $existingPayment->update([
            'bank'           => $validated['bank'],
            'account_name'   => $validated['account_name'],
        ]);
        
        $masterPayment = $existingPayment;
    } else {
        // No existing payment method with this account number - create new
        $masterPayment = PaymentMethod::create([
            'bank'           => $validated['bank'],
            'account_name'   => $validated['account_name'],
            'account_number' => $validated['account_number'],
            'is_active'      => true,
        ]);
    }

    // Check if this payment method is already added to this programme
    $alreadyAdded = ProgrammePaymentMethod::where('programme_id', $programme->id)
        ->where('payment_method_id', $masterPayment->id)
        ->exists();
        
    if ($alreadyAdded) {
        return back()->with('error', 'This payment method is already added to this programme.');
    }

    // Add to programme
    ProgrammePaymentMethod::create([
        'programme_id'       => $programme->id,
        'payment_method_id'  => $masterPayment->id,
        'is_active'          => true,
    ]);

    return back()->with('success', 'Payment method added to programme successfully.');
}
    /* =====================================================
       REMOVE PAYMENT METHOD FROM PROGRAMME
    ===================================================== */
    public function removePaymentMethodFromProgramme(ParticipationProgramme $programme, ProgrammePaymentMethod $programmePaymentMethod)
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;

        if ($programmePaymentMethod->programme_id !== $programme->id) {
            abort(403);
        }

        // ✅ Check if has submissions
        if ($programmePaymentMethod->submissions()->exists()) {
            return back()->with('error', 'Cannot remove payment method that has submissions.');
        }

        $programmePaymentMethod->delete();

        return back()->with('success', 'Payment method removed from programme.');
    }

    /* =====================================================
       LOCK PROGRAMME (Make Prices Immutable)
    ===================================================== */
    public function lockProgramme(ParticipationProgramme $programme)
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;

        // ✅ Deactivate programme
        $programme->update(['is_active' => false]);

        return back()->with('success', 'Programme locked successfully. Prices are now immutable.');
    }

    /* =====================================================
       SAVE FORM SETTINGS
    ===================================================== */
public function saveForm(Request $request, ParticipationProgramme $programme)
{
    if ($redirect = $this->ensureAdmin()) return $redirect;

    $validated = $request->validate([
        'is_active'      => 'nullable|boolean',
        'receipt_max_mb' => 'nullable|integer|min:1|max:50',
        'qr'             => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
        'upload_form'    => 'nullable|file|mimes:pdf,doc,docx|max:30720',
    ]);

    $updateData = [
        'is_active'      => $validated['is_active'] ?? $programme->is_active,
        'receipt_max_mb' => $validated['receipt_max_mb'] ?? $programme->receipt_max_mb,
    ];

    /* ===============================
       QR UPLOAD (already correct)
    =============================== */
    if ($request->hasFile('qr')) {
        if ($programme->qr_path && Storage::disk('public')->exists($programme->qr_path)) {
            Storage::disk('public')->delete($programme->qr_path);
        }

        $updateData['qr_path'] =
            $request->file('qr')->store('participation/qr', 'public');
    }

    /* ===============================
       🔥 UPLOAD PARTICIPATION FORM
    =============================== */
    if ($request->hasFile('upload_form')) {
        // Delete old form if exists
        if ($programme->upload_form_path &&
            Storage::disk('public')->exists($programme->upload_form_path)) {
            Storage::disk('public')->delete($programme->upload_form_path);
        }

        // Store new form
        $updateData['upload_form_path'] =
            $request->file('upload_form')
                    ->store('participation/forms', 'public');
    }

    $programme->update($updateData);

    return back()->with('success', 'Settings updated successfully.');
}

/* =====================================================
   DELETE UPLOAD FORM
===================================================== */
public function deleteForm(ParticipationProgramme $programme)
{
    if ($redirect = $this->ensureAdmin()) return $redirect;

    // Check if upload form file exists
    if ($programme->upload_form_path) {
        // Delete from storage
        if (Storage::disk('public')->exists($programme->upload_form_path)) {
            Storage::disk('public')->delete($programme->upload_form_path);
        }

        // Clear from database
        $programme->update(['upload_form_path' => null]);

        return back()->with('success', 'Upload form deleted successfully.');
    }

    return back()->with('info', 'No upload form to delete.');
}

    /* =====================================================
       GENERATE PUBLIC LINK
    ===================================================== */
    public function generateLink(ParticipationProgramme $programme)
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;

        if (!$programme->public_token) {
            $programme->update(['public_token' => Str::random(40)]);
        }

        return back()->with('public_link', route('participation.public.form', $programme->public_token));
    }

    public function preview(ParticipationProgramme $programme)
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;

        if (!$programme->public_token) {
            return back()->with('error', 'Please generate the public link first.');
        }

        return redirect()->route('participation.public.form', $programme->public_token);
    }

    /* =====================================================
       INDEX - List All Programmes
    ===================================================== */
    public function index(Request $request)
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;

        $q = trim((string) $request->get('search', ''));

        $programmes = ParticipationProgramme::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                      ->orWhere('venue', 'like', "%{$q}%");
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.participations.index', compact('programmes', 'q'));
    }

    /* =====================================================
       STORE - Create New Programme
    ===================================================== */
    public function store(Request $request)
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;

        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time'   => 'nullable|date_format:H:i',
            'venue'      => 'nullable|string|max:255',
        ]);

        ParticipationProgramme::create([
            'title'          => $validated['title'],
            'start_date'     => $validated['start_date'] ?? null,
            'end_date'       => $validated['end_date'] ?? null,
            'start_time'     => $validated['start_time'] ?? null,
            'end_time'       => $validated['end_time'] ?? null,
            'venue'          => $validated['venue'] ?? null,
            'is_active'      => false,
            'receipt_max_mb' => 20,
        ]);

        return redirect()->route('admin.participations.index')
            ->with('success', 'Programme created successfully.');
    }

    /* =====================================================
       INFO - Programme Info Hub
    ===================================================== */
    public function info(ParticipationProgramme $programme)
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;
        return view('admin.participations.info', compact('programme'));
    }

    /* =====================================================
       UPDATE - Edit Programme Details
    ===================================================== */
    public function update(Request $request, ParticipationProgramme $programme)
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;

        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time'   => 'nullable|date_format:H:i',
            'venue'      => 'nullable|string|max:255',
        ]);

        $programme->update($validated);

        return redirect()->route('admin.participations.index')
            ->with('success', 'Programme updated successfully.');
    }

    /* =====================================================
       DESTROY - Delete Programme
    ===================================================== */
    public function destroy(ParticipationProgramme $programme)
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;

        // Delete submission receipts
        foreach ($programme->submissions as $submission) {
            if ($submission->receipt_path && Storage::disk('public')->exists($submission->receipt_path)) {
                Storage::disk('public')->delete($submission->receipt_path);
            }
            $submission->participants()->delete();
        }

        $programme->submissions()->delete();
        $programme->programmePackages()->delete();
        $programme->programmePaymentMethods()->delete();

        if ($programme->qr_path && Storage::disk('public')->exists($programme->qr_path)) {
            Storage::disk('public')->delete($programme->qr_path);
        }

        $programme->delete();

        return redirect()->route('admin.participations.index')
            ->with('success', 'Programme deleted successfully.');
    }

    /* =====================================================
       PARTICIPANT LIST - View Submissions
    ===================================================== */
    public function participantList(Request $request, ParticipationProgramme $programme)
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;

        $status = $request->get('status');
        $agency = trim((string) $request->get('agency', ''));
        $search = trim((string) $request->get('q', ''));

        $submissions = ParticipationSubmission::where('programme_id', $programme->id)
            ->with(['participants', 'programmePackage.package'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($agency !== '', fn ($q) => $q->where('company_name', $agency))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('company_name', 'like', "%{$search}%")
                          ->orWhere('officer_name', 'like', "%{$search}%")
                          ->orWhere('phone_number', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $agencies = ParticipationSubmission::where('programme_id', $programme->id)
            ->select('company_name')
            ->distinct()
            ->orderBy('company_name')
            ->pluck('company_name');

        return view('admin.participations.participant-list', compact('programme', 'submissions', 'agencies'));
    }

    /* =====================================================
       EDIT SUBMISSION
    ===================================================== */
    public function editSubmission(ParticipationSubmission $submission)
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;
        $submission->load(['participants', 'programme', 'programmePackage.package']);
        return view('admin.participations.submission-edit', compact('submission'));
    }

    /* =====================================================
       DELETE SUBMISSION
    ===================================================== */
    public function deleteSubmission(ParticipationSubmission $submission)
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;

        if ($submission->receipt_path && Storage::disk('public')->exists($submission->receipt_path)) {
            Storage::disk('public')->delete($submission->receipt_path);
        }

        $submission->participants()->delete();
        $programmeId = $submission->programme_id;
        $submission->delete();

        return redirect()->route('admin.participations.participant_list', $programmeId)
            ->with('success', 'Submission deleted successfully.');
    }

    /* =====================================================
       UPDATE PARTICIPANTS
    ===================================================== */
    public function updateParticipants(Request $request, ParticipationSubmission $submission)
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;

        $validated = $request->validate([
            'participants' => 'required|array',
            'participants.*.id' => 'nullable|exists:participation_participants,id',
            'participants.*.name' => 'required|string|max:255',
            'participants.*.position' => 'nullable|string|max:255',
            'participants.*.table_number' => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($validated, $submission) {
            foreach ($validated['participants'] as $index => $data) {
                if (!empty($data['id'])) {
                    ParticipationParticipant::where('id', $data['id'])
                        ->where('submission_id', $submission->id)
                        ->update([
                            'name'       => $data['name'],
                            'position'   => $data['position'] ?? null,
                            'table_number' => $data['table_number'] ?? null,
                            'sort_order' => $index + 1,
                        ]);
                } else {
                    ParticipationParticipant::create([
                        'submission_id' => $submission->id,
                        'name'          => $data['name'],
                        'position'      => $data['position'] ?? null,
                        'table_number'  => $data['table_number'] ?? null,
                        'sort_order'    => $index + 1,
                    ]);
                }
            }

            $qty = $submission->participants()->count();
            $price = optional($submission->programmePackage)->price ?? 0;
            $total = $qty * $price;

            $submission->update([
                'quantity'    => $qty,
                'total_price' => $total,
            ]);
        });

        return back()->with('success', 'Participants updated successfully.');
    }

    /* =====================================================
       DELETE PARTICIPANT
    ===================================================== */
    public function deleteParticipant(ParticipationParticipant $participant)
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;

        $submission = $participant->submission;
        $participant->delete();

        $qty = $submission->participants()->count();
        $price = optional($submission->programmePackage)->price ?? 0;
        $total = $qty * $price;

        $submission->update([
            'quantity'    => $qty,
            'total_price' => $total,
        ]);

        return response()->json([
            'success' => true,
            'qty'     => $qty,
            'total'   => number_format($total, 2),
        ]);
    }

    /* =====================================================
       EXPORT PRINT
    ===================================================== */
    public function exportPrint(Request $request, ParticipationProgramme $programme)
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;

        $status = $request->get('status');
        $agency = $request->get('agency');

        $submissions = ParticipationSubmission::where('programme_id', $programme->id)
            ->with(['participants', 'programmePackage.package'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($agency, fn ($q) => $q->where('company_name', $agency))
            ->orderBy('created_at')
            ->get();

        return view('admin.participations.export-print', compact('programme', 'submissions'));
    }

    /* =====================================================
       EXPORT EXCEL
    ===================================================== */
    public function exportExcel(Request $request, ParticipationProgramme $programme)
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;

        return Excel::download(
            new ParticipantListExport($programme, $request->status, $request->agency),
            'participant-list-' . $programme->id . '.xlsx'
        );
    }

    /* =====================================================
       UPDATE SUBMISSION STATUS
    ===================================================== */
    public function updateStatus(Request $request, ParticipationSubmission $submission)
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;

        $validated = $request->validate([
            'status' => 'required|in:pending,approved',
        ]);

        if ($submission->status === $validated['status']) {
            return back()->with('info', 'Status is already '.$validated['status'].'.');
        }

        $submission->update(['status' => $validated['status']]);

        return back()->with('success', 'Submission status updated to '.$validated['status'].'.');
    }
/* =====================================================
   UPDATE PACKAGE FROM FORM (With Override Logic)
===================================================== */
public function updatePackageFromForm(Request $request, ParticipationProgramme $programme, ParticipationProgrammePackage $programmePackage)
{
    if ($redirect = $this->ensureAdmin()) return $redirect;

    if ($programmePackage->programme_id !== $programme->id) {
        abort(403);
    }

    // Check if locked
    if ($programmePackage->is_locked) {
        return back()->with('error', 'Cannot edit locked package.');
    }

    $validated = $request->validate([
        'label'              => 'required|string|max:255',
        'package_type'       => 'required|in:one_person,multi_person',
        'price'              => 'required|numeric|min:0',
        'people_per_package' => 'nullable|integer|min:1',
        'description'        => 'nullable|string',
    ]);

    // Update master package (name and type cannot be changed if used elsewhere)
    $masterPackage = $programmePackage->package;
    
    // Only update master if not used in other programmes
    $otherUsage = ParticipationProgrammePackage::where('package_id', $masterPackage->id)
        ->where('programme_id', '!=', $programme->id)
        ->exists();
    
    if (!$otherUsage) {
        $masterPackage->update([
            'name'              => $validated['label'],
            'package_type'      => $validated['package_type'],
            'default_price'     => $validated['price'],
            'people_per_package' => $validated['people_per_package'] ?? null,
            'description'       => $validated['description'] ?? null,
        ]);
    }

    // Determine override values for THIS programme
    $overrideData = [];
    
    // FIXED: Only include price if it differs from master AND is not null
    if (floatval($validated['price']) != floatval($masterPackage->default_price)) {
        $overrideData['price'] = $validated['price'];
    }
    // FIXED: Don't set price to null - if it's the same as master, just don't include it
    
    // FIXED: Only include people_per_package if it differs
    if (($validated['people_per_package'] ?? null) != $masterPackage->people_per_package) {
        $overrideData['people_per_package'] = $validated['people_per_package'] ?? null;
    }
    // FIXED: Don't set people_per_package to null
    
    // FIXED: Only include description if it differs
    if (trim($validated['description'] ?? '') != trim($masterPackage->description ?? '')) {
        $overrideData['description'] = $validated['description'] ?? null;
    }
    // FIXED: Don't set description to null
    
    // Add sort_order to update
    if (isset($validated['sort_order'])) {
        $overrideData['sort_order'] = $validated['sort_order'];
    }

    // Update programme package snapshot
    // FIXED: Only update if there are override values OR sort_order changed
    if (!empty($overrideData)) {
        $programmePackage->update($overrideData);
    }

    return back()->with('success', 'Package updated successfully.');
}
/* =====================================================
   UPDATE PAYMENT METHOD FROM FORM (With Override Logic)
===================================================== */
public function updatePaymentMethodFromForm(Request $request, ParticipationProgramme $programme, ProgrammePaymentMethod $programmePaymentMethod)
{
    if ($redirect = $this->ensureAdmin()) return $redirect;

    if ($programmePaymentMethod->programme_id !== $programme->id) {
        abort(403);
    }

    $validated = $request->validate([
        'bank'           => 'required|string|max:255',
        'account_name'   => 'required|string|max:255',
        'account_number' => 'required|string|max:255',
    ]);

    // Update master payment method directly
    // Since programme_payment_methods doesn't have override columns,
    // we need to update the master record
    $masterPayment = $programmePaymentMethod->paymentMethod;
    
    // Check if this master payment method is used in other programmes
    $otherUsage = ProgrammePaymentMethod::where('payment_method_id', $masterPayment->id)
        ->where('programme_id', '!=', $programme->id)
        ->exists();
    
    if ($otherUsage) {
        // If used in other programmes, create a new master payment method
        $newMasterPayment = PaymentMethod::create([
            'bank'           => $validated['bank'],
            'account_name'   => $validated['account_name'],
            'account_number' => $validated['account_number'],
            'is_active'      => true,
        ]);
        
        // Update the programme_payment_method to point to the new master
        $programmePaymentMethod->update([
            'payment_method_id' => $newMasterPayment->id,
        ]);
    } else {
        // If not used elsewhere, update the existing master
        $masterPayment->update([
            'bank'           => $validated['bank'],
            'account_name'   => $validated['account_name'],
            'account_number' => $validated['account_number'],
        ]);
    }

    return back()->with('success', 'Payment method updated successfully.');
}
/* =====================================================
   DELETE QR CODE (FIXED)
===================================================== */
public function deleteQR(ParticipationProgramme $programme)
{
    if ($redirect = $this->ensureAdmin()) return $redirect;

    // Check if QR file exists
    if ($programme->qr_path) {
        // Delete from storage
        if (Storage::disk('public')->exists($programme->qr_path)) {
            Storage::disk('public')->delete($programme->qr_path);
        }

        // Clear from database
        $programme->update(['qr_path' => null]);

        return back()->with('success', 'QR code deleted successfully.');
    }

    return back()->with('info', 'No QR code to delete.');
}
}