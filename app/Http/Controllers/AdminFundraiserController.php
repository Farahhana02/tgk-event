<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Fundraiser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AdminDonorController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AdminFundraiserController extends Controller
{
    public function index()
    {
        $fundraisers = Fundraiser::orderBy('id', 'ASC')->get();
        return view('admin.fundraisers.index', compact('fundraisers'));
    }

    public function store(Request $request)
    {
        // Increase PHP limits for large file uploads
        ini_set('upload_max_filesize', '20M');
        ini_set('post_max_size', '20M');
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', 300);
        ini_set('max_input_time', 300);

        $request->validate([
            'programme_name' => 'required|string|max:255',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
            'target_amount' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:15360',
            'form_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240', // ✅ 10MB
            'description' => 'nullable|string',
        ]);

        // Format target amount (remove commas if any)
        $targetAmount = str_replace(',', '', $request->target_amount);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            
            // Generate unique filename
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // Store image
            $imagePath = $image->storeAs('fundraisers', $filename, 'public');
        }

                // ===============================
        // 🔹 3️⃣ UPLOAD DONATION FORM (START HERE)
        // ===============================
        $formPath = null;

        if ($request->hasFile('form_file')) {
            $formFile = $request->file('form_file');
            $filename = time().'_'.uniqid().'.'.$formFile->getClientOriginalExtension();
            $formPath = $formFile->storeAs('donation_forms', $filename, 'public');
        }

        Fundraiser::create([
            'programme_name' => $request->programme_name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'target_amount' => $targetAmount,
            'image_path' => $imagePath,
            'form_path' => $formPath,
            'description' => $request->description,
            'progress' => 0,
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Fundraiser created successfully.'
        ]);
    }

    public function edit($id)
    {
        $fundraiser = Fundraiser::find($id);

        if (!$fundraiser) {
            return response()->json([
                'success' => false,
                'message' => 'Fundraiser not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'fundraiser' => $fundraiser
        ]);
    }

    public function info($id)
    {
        $fundraiser = Fundraiser::find($id);

        if (!$fundraiser) {
            return response()->json(['success' => false], 404);
        }

        return response()->json([
            'success' => true,
            'fundraiser' => $fundraiser
        ]);
    }

    public function update(Request $request, $id)
    {
        // Increase PHP limits for large file uploads
        ini_set('upload_max_filesize', '20M');
        ini_set('post_max_size', '20M');
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', 300);
        ini_set('max_input_time', 300);

        $fundraiser = Fundraiser::find($id);

        if (!$fundraiser) {
            return response()->json([
                'success' => false,
                'message' => 'Fundraiser not found'
            ], 404);
        }

        // Validate the request
        $request->validate([
            'programme_name' => 'required|string|max:255',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
            'target_amount' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:15360', // 15MB = 15360KB
            'description' => 'nullable|string',
        ], [
            'image.max' => 'The image must not exceed 15MB.',
            'image.mimes' => 'Only JPG, PNG, GIF, and WEBP images are allowed.',
        ]);

        try {
            // Format target amount (remove commas if any)
            $targetAmount = str_replace(',', '', $request->target_amount);
            
            // Handle image upload
            $imagePath = $fundraiser->image_path;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                
                // Delete old image if exists
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                
                // Generate unique filename
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                
                // Store new image
                $imagePath = $image->storeAs('fundraisers', $filename, 'public');
            }
                // ===============================
                // 🔹 3️⃣ UPLOAD / REPLACE DONATION FORM
                // ===============================
                $formPath = $fundraiser->form_path;

                if ($request->hasFile('form_file')) {

                    if ($formPath && Storage::disk('public')->exists($formPath)) {
                        Storage::disk('public')->delete($formPath);
                    }

                    $formFile = $request->file('form_file');
                    $filename = time().'_'.uniqid().'.'.$formFile->getClientOriginalExtension();
                    $formPath = $formFile->storeAs('donation_forms', $filename, 'public');
                }

            $fundraiser->update([
                'programme_name' => $request->programme_name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'target_amount' => $targetAmount,
                'image_path' => $imagePath,
                'form_path' => $formPath,
                'description' => $request->description,
                'progress' => $request->progress ?? $fundraiser->progress,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Fundraiser updated successfully.',
                'fundraiser' => $fundraiser
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update fundraiser: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $fundraiser = Fundraiser::find($id);

        if (!$fundraiser) {
            return response()->json([
                'success' => false,
                'message' => 'Fundraiser not found.'
            ], 404);
        }

        try {
            // Delete image if exists
            if ($fundraiser->image_path && Storage::disk('public')->exists($fundraiser->image_path)) {
                Storage::disk('public')->delete($fundraiser->image_path);
            }

            $fundraiser->delete();

            return response()->json([
                'success' => true,
                'message' => 'Fundraiser deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete fundraiser: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $fundraiser = Fundraiser::with('donations')->findOrFail($id);
        
        // Calculate total raised from approved donations
        $totalRaised = $fundraiser->donations()
            ->where('status', 'approve')
            ->sum('amount_pledge');
        
        // Add total_raised and map donations to donors for blade compatibility
        $fundraiser->total_raised = $totalRaised;
        $fundraiser->donors = $fundraiser->donations;
        
        return view('admin.fundraisers.programme-detail', compact('fundraiser'));
    }
    /**
 * Export donor list to print view
 */
public function exportPrint($id)
{
    $fundraiser = Fundraiser::with('donations')->findOrFail($id);
    
    // Calculate total raised from approved donations
    $totalRaised = $fundraiser->donations()
        ->where('status', 'approved')
        ->sum('amount_pledge');
    
    $fundraiser->total_raised = $totalRaised;
    $fundraiser->donors = $fundraiser->donations;
    
    return view('admin.fundraisers.export-print', compact('fundraiser'));
}

/**
 * Export donor list to Excel
 */
public function exportExcel($id)
{
    $fundraiser = Fundraiser::with('donations')->findOrFail($id);
    
    // Calculate total raised
    $totalRaised = $fundraiser->donations()
        ->where('status', 'approved')
        ->sum('amount_pledge');
    
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Set document properties
    $spreadsheet->getProperties()
        ->setCreator('TGK EVENTS')
        ->setTitle('Donor List - ' . $fundraiser->programme_name)
        ->setSubject('Fundraiser Donors')
        ->setDescription('Donor list export for ' . $fundraiser->programme_name);
    
    // Header section
    $sheet->setCellValue('A1', 'DONOR LIST - ' . strtoupper($fundraiser->programme_name));
    $sheet->mergeCells('A1:H1');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
    // Programme info
    $sheet->setCellValue('A2', 'Programme:');
    $sheet->setCellValue('B2', strtoupper($fundraiser->programme_name));
    $sheet->setCellValue('D2', 'Date:');
    $sheet->setCellValue('E2', now()->format('d/m/Y'));
    
    $sheet->setCellValue('A3', 'Target:');
    $sheet->setCellValue('B3', 'RM ' . number_format($fundraiser->target_amount, 2));
    $sheet->setCellValue('D3', 'Total Raised:');
    $sheet->setCellValue('E3', 'RM ' . number_format($totalRaised, 2));
    
    $sheet->setCellValue('A4', 'Progress:');
    $sheet->setCellValue('B4', $fundraiser->progress . ' %');
    $sheet->setCellValue('D4', 'Total Donors:');
    $sheet->setCellValue('E4', $fundraiser->donations->count());
    
    // Style info section
    $sheet->getStyle('A2:A4')->getFont()->setBold(true);
    $sheet->getStyle('D2:D4')->getFont()->setBold(true);
    
    // Empty row
    $sheet->setCellValue('A5', '');
    
    // Column headers
    $headers = ['BIL', 'DONOR', 'EMAIL', 'PHONE', 'AMOUNT PLEDGE', 'NOTES', 'STATUS', 'DONATE TIME'];
    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '6', $header);
        $col++;
    }
    
    // Style header row
    $headerStyle = $sheet->getStyle('A6:H6');
    $headerStyle->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
    $headerStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('00542A');
    $headerStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
    // Data rows
    $row = 7;
    foreach ($fundraiser->donations as $index => $donation) {
        $sheet->setCellValue('A' . $row, $index + 1);
        $sheet->setCellValue('B' . $row, strtoupper($donation->donor_name));
        $sheet->setCellValue('C' . $row, strtolower($donation->email));
        $sheet->setCellValue('D' . $row, $donation->phone);
        $sheet->setCellValue('E' . $row, 'RM ' . number_format($donation->amount_pledge, 2));
        $sheet->setCellValue('F' . $row, strtoupper($donation->notes ?? '-'));
        $sheet->setCellValue('G' . $row, strtoupper($donation->status));
        $sheet->setCellValue('H' . $row, $donation->created_at->format('d/m/Y H:i'));
        
        // Alternate row colors
        if ($row % 2 == 0) {
            $sheet->getStyle('A' . $row . ':H' . $row)
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('F8F9FA');
        }
        
        $row++;
    }
    
    // Auto-size columns
    foreach (range('A', 'H') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }
    
    // Add borders to data table
    $lastRow = $row - 1;
    $sheet->getStyle('A6:H' . $lastRow)->getBorders()->getAllBorders()
        ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    
    // Generate and download file
    $writer = new Xlsx($spreadsheet);
    $filename = 'donors_' . $fundraiser->id . '_' . date('Y-m-d') . '.xlsx';
    $temp_file = tempnam(sys_get_temp_dir(), $filename);
    
    $writer->save($temp_file);
    
    return response()->download($temp_file, $filename, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ])->deleteFileAfterSend(true);
}
}