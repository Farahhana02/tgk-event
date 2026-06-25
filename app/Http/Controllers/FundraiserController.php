<?php

namespace App\Http\Controllers;

use App\Models\Fundraiser;
use App\Models\Donation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FundraiserController extends Controller
{
    /**
     * Display a listing of active fundraisers (public page)
     */
    public function index()
    {
        $fundraisers = Fundraiser::where('end_date', '>=', Carbon::now())
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($fundraisers as $fundraiser) {
            $totalRaised = $fundraiser->donors()
                ->where('status', 'approved')
                ->sum('amount_pledge');

            $fundraiser->total_raised = $totalRaised;
            $fundraiser->progress = $fundraiser->target_amount > 0
                ? round(($totalRaised / $fundraiser->target_amount) * 100, 2)
                : 0;
        }

        return view('fundraisers', compact('fundraisers'));
    }

    /**
     * Display the specified fundraiser detail (public page)
     */
    public function detail($id)
    {
        $fundraiser = Fundraiser::with(['donors' => function ($query) {
            $query->where('status', 'approved');
        }])->findOrFail($id);

        $totalRaised = $fundraiser->donors()
            ->where('status', 'approved')
            ->sum('amount_pledge');

        $fundraiser->total_raised = $totalRaised;
        $fundraiser->progress = $fundraiser->target_amount > 0
            ? round(($totalRaised / $fundraiser->target_amount) * 100, 2)
            : 0;

        return view('fundraiser-detail', compact('fundraiser'));
    }

    /**
     * Show donation form
     */
    public function donateForm($id)
    {
        $fundraiser = Fundraiser::findOrFail($id);
        return view('fundraiser-donate', compact('fundraiser'));
    }

    /**
     * Handle donation submission from public
     */
    public function donate(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'notes' => 'nullable|string',

            'receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:30720',

            // ⭐ CHANGED — hardcopy completed form (REQUIRED)
            'submitted_form' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $fundraiser = Fundraiser::findOrFail($id);

        // Receipt upload (optional)
        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')
                ->store('donations/receipts', 'public');
        }

        // ⭐ CHANGED — completed hardcopy form upload
        $submittedFormPath = null;
        if ($request->hasFile('submitted_form')) {
            $submittedFormPath = $request->file('submitted_form')
                ->store('donations/forms', 'public');
        }

        // Create donation
        Donation::create([
            'fundraiser_id' => $fundraiser->id,
            'donor_name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'amount_pledge' => $request->amount,
            'notes' => $request->notes,
            'receipt_file' => $receiptPath,

            // ⭐ CHANGED
            'submitted_form_path' => $submittedFormPath,

            'status' => 'pending',
            'donate_time' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your donation! Your contribution is currently pending and need secretariat approval. Our secretariat team will contact you once it has been approved.'
        ]);
    }
}
