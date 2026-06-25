<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Fundraiser;
use Illuminate\Support\Facades\Storage;

class AdminDonorController extends Controller
{
    public function store(Request $request)
    {
        // Increase PHP limits for large file uploads
        ini_set('upload_max_filesize', '30M');
        ini_set('post_max_size', '30M');
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', 300);

        $request->validate([
            'fundraiser_id' => 'required|exists:fundraisers,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:30720', // 30MB
            'submitted_form' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'status' => 'required|in:pending,approved',
        ]);

        // Handle receipt upload
        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receipt = $request->file('receipt');
            $filename = time() . '_' . uniqid() . '.' . $receipt->getClientOriginalExtension();
            $receiptPath = $receipt->storeAs('receipts', $filename, 'public');
        }
        $submittedFormPath = null;
        if ($request->hasFile('submitted_form')) {
            $form = $request->file('submitted_form');
            $filename = time().'_form_'.uniqid().'.'.$form->getClientOriginalExtension();
            $submittedFormPath = $form->storeAs('donations/forms', $filename, 'public');
        }

        // Create donation
        $donation = Donation::create([
            'fundraiser_id' => $request->fundraiser_id,
            'donor_name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'amount_pledge' => $request->amount,
            'notes' => $request->notes,
            'receipt_file' => $receiptPath,
            'submitted_form_path' => $submittedFormPath,
            'status' => $request->status,
            'donate_time' => now(),
        ]);

        // Update fundraiser progress only if status is 'approve'
        $this->updateFundraiserProgress($request->fundraiser_id);

        return response()->json([
            'success' => true,
            'message' => 'Donor added successfully.',
            'donation' => $donation
        ]);
    }

    public function update(Request $request, $id)
    {
        // Increase PHP limits for large file uploads
        ini_set('upload_max_filesize', '30M');
        ini_set('post_max_size', '30M');
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', 300);

        $donation = Donation::find($id);

        if (!$donation) {
            return response()->json([
                'success' => false,
                'message' => 'Donor not found.'
            ], 404);
        }

        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'phone'          => 'required|string|max:20',
            'amount'         => 'required|numeric|min:0',
            'notes'          => 'nullable|string',
            'receipt'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:30720', // 30MB
            'submitted_form' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB
            'status'         => 'required|in:pending,approved',
        ]);

        // Handle receipt upload
        $receiptPath = $donation->receipt_file;
        if ($request->hasFile('receipt')) {
            // Delete old receipt if exists
            if ($receiptPath && Storage::disk('public')->exists($receiptPath)) {
                Storage::disk('public')->delete($receiptPath);
            }

            $receipt = $request->file('receipt');
            $filename = time() . '_' . uniqid() . '.' . $receipt->getClientOriginalExtension();
            $receiptPath = $receipt->storeAs('receipts', $filename, 'public');
        }
        $formPath = $donation->submitted_form_path;

        if ($request->hasFile('submitted_form')) {
            if ($formPath && Storage::disk('public')->exists($formPath)) {
                Storage::disk('public')->delete($formPath);
            }

            $form = $request->file('submitted_form');
            $filename = time().'_form_'.uniqid().'.'.$form->getClientOriginalExtension();
            $formPath = $form->storeAs('donations/forms', $filename, 'public');
        }

        // Update donation
        $donation->update([
            'donor_name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'amount_pledge' => $request->amount,
            'notes' => $request->notes,
            'receipt_file' => $receiptPath,
            'submitted_form_path' => $formPath,
            'status' => $request->status,
        ]);

        // Update fundraiser progress
        $this->updateFundraiserProgress($donation->fundraiser_id);

        return response()->json([
            'success' => true,
            'message' => 'Donor updated successfully.',
            'donation' => $donation
        ]);
    }

    public function show($id)
    {
        $donation = Donation::find($id);

        if (!$donation) {
            return response()->json([
                'success' => false,
                'message' => 'Donor not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'donation' => $donation
        ]);
    }

    public function destroy($id)
    {
        $donation = Donation::find($id);

        if (!$donation) {
            return response()->json([
                'success' => false,
                'message' => 'Donor not found.'
            ], 404);
        }

        $fundraiserId = $donation->fundraiser_id;

        // Delete receipt if exists
        if ($donation->receipt_file && Storage::disk('public')->exists($donation->receipt_file)) {
            Storage::disk('public')->delete($donation->receipt_file);
        }

        $donation->delete();

        // Update fundraiser progress
        $this->updateFundraiserProgress($fundraiserId);

        return response()->json([
            'success' => true,
            'message' => 'Donor deleted successfully.'
        ]);
    }

    /**
     * Update fundraiser progress based on approved donations only
     */
    private function updateFundraiserProgress($fundraiserId)
    {
        $fundraiser = Fundraiser::find($fundraiserId);

        if (!$fundraiser) {
            return;
        }

        // Calculate total raised FROM APPROVED donations only
        $totalRaised = Donation::where('fundraiser_id', $fundraiserId)
            ->where('status', 'approved')   // <-- FIXED
            ->sum('amount_pledge');

        // Calculate progress percentage
        $progress = 0;
        if ($fundraiser->target_amount > 0) {
            $progress = round(($totalRaised / $fundraiser->target_amount) * 100, 2);
        }

        // Update fundraiser progress
        $fundraiser->update([
            'progress' => $progress
        ]);
    }
}