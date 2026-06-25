<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\ProgrammeItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\PhotoItem;

class AdminProgramController extends Controller
{
public function index(Request $request)
{
    $search = trim($request->get('search', ''));

    $programs = Program::query()
        ->when($search !== '', function ($query) use ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('theme', 'like', "%{$search}%");
        })
        ->orderByDesc('created_at')
        ->paginate(10)
        ->withQueryString();

    return view('admin.programs.index', compact('programs', 'search'));
}

    /** -------------------------------------------------------------
     *  CREATE NEW PROGRAM - FIXED
     * ------------------------------------------------------------ */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'event_date' => 'required',
                'event_time' => 'required',
                'location' => 'required|string|max:255',
                'theme' => 'required|string|max:255',
            ]);

            $eventDate = null;
            if ($request->event_date) {
                try {
                    $eventDate = Carbon::createFromFormat('d/m/Y', $request->event_date)->format('Y-m-d');
                } catch (\Exception $e) {
                    // Try parsing as Y-m-d format if d/m/Y fails
                    try {
                        $eventDate = Carbon::parse($request->event_date)->format('Y-m-d');
                    } catch (\Exception $e2) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Invalid date format'
                        ], 422);
                    }
                }
            }

            // Parse event time properly
            $eventTime = null;
            if ($request->event_time) {
                try {
                    $eventTime = Carbon::parse($request->event_time)->format('H:i:s');
                } catch (\Exception $e) {
                    $eventTime = $request->event_time;
                }
            }

            // CRITICAL FIX: Use DB insert instead of Eloquent to avoid casting issues
            $programId = \DB::table('programs')->insertGetId([
                'title' => $request->title,
                'event_date' => $eventDate,
                'event_time' => $eventTime,
                'location' => $request->location,
                'theme' => $request->theme,
                'is_visible' => true,
                'visible_sections' => json_encode([
                    'overview' => true,
                    'tentative' => true,
                    'vip' => true,
                    'participation' => true,
                    'sponsorship' => true,
                    'programme' => true,
                    'photo' => true,
                    'link-participation' => true
                    
                ]),
                'introduction' => json_encode([]),
                'background' => json_encode([]),
                'objectives' => json_encode([]),
                'schedules' => json_encode([]),
                'vip_list' => json_encode([]),
                'participation_description' => json_encode([]),
                'participation_prices' => json_encode([]),
                'sponsorship_description' => json_encode([]),
                'sponsorship_packages' => json_encode([]),
                'programme_images' => json_encode([]),
                'programme_description' => json_encode([]),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Programme created successfully!',
                'redirect' => route('admin.programs.show', $programId)
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating program: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /** -------------------------------------------------------------
     *  SHOW ADMIN DETAIL PAGE
     * ------------------------------------------------------------ */
    public function show($id)
    {
        $program = Program::findOrFail($id);
        return view('admin.programs.detail', compact('program'));
    }

    /** -------------------------------------------------------------
     *  GET PROGRAM DATA FOR EDIT MODAL
     * ------------------------------------------------------------ */
    public function edit($id)
    {
        try {
            $program = Program::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'program' => [
                    'id' => $program->id,
                    'title' => $program->title,
                    'event_date' => $program->event_date,
                    'event_date_formatted' => $program->formatted_event_date,
                    'event_time' => $program->event_time,
                    'event_time_formatted' => $program->event_time ? 
                        Carbon::parse($program->event_time)->format('H:i') : null,
                    'location' => $program->location,
                    'theme' => $program->theme,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /** -------------------------------------------------------------
     *  UPDATE BASIC PROGRAM INFO (FROM INDEX PAGE) - FIXED
     * ------------------------------------------------------------ */
    public function update(Request $request, $id)
    {
        try {
            $program = Program::findOrFail($id);

            $request->validate([
                'title' => 'required|string|max:255',
                'event_date' => 'required',
                'event_time' => 'required',
                'location' => 'required|string|max:255',
                'theme' => 'required|string|max:255',
            ]);

            $eventDate = null;
            if ($request->event_date) {
                try {
                    $eventDate = Carbon::createFromFormat('d/m/Y', $request->event_date)->format('Y-m-d');
                } catch (\Exception $e) {
                    // Try parsing as Y-m-d format if d/m/Y fails
                    try {
                        $eventDate = Carbon::parse($request->event_date)->format('Y-m-d');
                    } catch (\Exception $e2) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Invalid date format. Please use DD/MM/YYYY'
                        ], 422);
                    }
                }
            }

            // Parse event time properly
            $eventTime = null;
            if ($request->event_time) {
                try {
                    $eventTime = Carbon::parse($request->event_time)->format('H:i:s');
                } catch (\Exception $e) {
                    $eventTime = $request->event_time;
                }
            }

            // CRITICAL FIX: Use direct DB update to avoid model casting issues
            // This ensures the data persists correctly
            \DB::table('programs')
                ->where('id', $id)
                ->update([
                    'title' => $request->title,
                    'event_date' => $eventDate,
                    'event_time' => $eventTime,
                    'location' => $request->location,
                    'theme' => $request->theme,
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Programme updated successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating program: ' . $e->getMessage());
            Log::error('Request data: ' . json_encode($request->all()));
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /** -------------------------------------------------------------
     *  DELETE PROGRAM
     * ------------------------------------------------------------ */
    public function destroy($id)
    {
        try {
            $program = Program::findOrFail($id);
            
            // Delete associated files
            if ($program->participation_form && $program->participation_form_type === 'file') {
                Storage::disk('public')->delete($program->participation_form);
            }
            
            if ($program->sponsorship_form && $program->sponsorship_form_type === 'file') {
                Storage::disk('public')->delete($program->sponsorship_form);
            }
            
            if ($program->sponsorship_additional_files) {
                Storage::disk('public')->delete($program->sponsorship_additional_files);
            }
            
            // Delete VIP images
            if ($program->vip_list) {
                foreach ($program->vip_list as $vip) {
                    if (isset($vip['image'])) {
                        Storage::disk('public')->delete($vip['image']);
                    }
                }
            }
            
            // Delete programme items and their images
            $programmeItems = ProgrammeItem::where('program_id', $id)->get();
            foreach ($programmeItems as $item) {
                if ($item->images) {
                    foreach ($item->images as $image) {
                        Storage::disk('public')->delete($image);
                    }
                }
            }
            
            $program->delete();

            return response()->json([
                'success' => true,
                'message' => 'Programme deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /** -------------------------------------------------------------
     *  UPDATE OVERVIEW SECTION - FIXED VERSION
     * ------------------------------------------------------------ */
public function saveOverview(Request $request, $id)
{
    try {
        $program = Program::findOrFail($id);
        
        Log::info('=== OVERVIEW SAVE START ===');
        Log::info('Program ID: ' . $id);
        Log::info('Request data:', $request->all());
        
        // Process introduction
        $introduction = [];
        if ($request->has('introduction')) {
            if (is_array($request->introduction)) {
                foreach ($request->introduction as $paragraph) {
                    $cleaned = trim($paragraph);
                    if (!empty($cleaned)) {
                        $introduction[] = $cleaned;
                    }
                }
            }
        }
        
        // Process background
        $background = [];
        if ($request->has('background')) {
            if (is_array($request->background)) {
                foreach ($request->background as $paragraph) {
                    $cleaned = trim($paragraph);
                    if (!empty($cleaned)) {
                        $background[] = $cleaned;
                    }
                }
            }
        }
        
        // Process objectives
        $objectives = [];
        if ($request->has('objectives')) {
            if (is_array($request->objectives)) {
                foreach ($request->objectives as $objective) {
                    $cleaned = trim($objective);
                    if (!empty($cleaned)) {
                        $objectives[] = $cleaned;
                    }
                }
            }
        }
        
        Log::info('Processed data:', [
            'introduction_count' => count($introduction),
            'background_count' => count($background),
            'objectives_count' => count($objectives)
        ]);
        
        // Use DB facade to bypass Eloquent
        $updated = \DB::table('programs')
            ->where('id', $id)
            ->update([
                'introduction' => json_encode($introduction),
                'background' => json_encode($background),
                'objectives' => json_encode($objectives),
                'updated_at' => now()
            ]);
        
        Log::info('DB Update result: ' . ($updated ? 'SUCCESS' : 'NO CHANGES'));
        
        // Verify saved data
        $saved = \DB::table('programs')
            ->where('id', $id)
            ->first(['introduction', 'background', 'objectives']);
        
        Log::info('Verification:', [
            'introduction' => $saved->introduction,
            'background' => $saved->background,
            'objectives' => $saved->objectives
        ]);
        
        Log::info('=== OVERVIEW SAVE END ===');
        
        return response()->json([
            'success' => true,
            'message' => 'Overview saved successfully'
        ]);
        
    } catch (\Exception $e) {
        Log::error('=== OVERVIEW SAVE ERROR ===');
        Log::error('Error: ' . $e->getMessage());
        Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}

    /** -------------------------------------------------------------
     *  UPDATE TENTATIVE SECTION - FIXED VERSION WITH DEBUG
     * ------------------------------------------------------------ */
public function saveTentative(Request $request, $id)
{
    try {
        $program = Program::findOrFail($id);
        
        Log::info('=== TENTATIVE SAVE START ===');
        Log::info('Program ID: ' . $id);
        
        // Get schedules from request
        $schedules = [];
        
        // Check if schedules is sent as JSON string
        if ($request->has('schedules') && is_string($request->schedules)) {
            $decoded = json_decode($request->schedules, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $schedules = $decoded;
            }
        } 
        // Check if schedules is sent as array
        elseif ($request->has('schedules') && is_array($request->schedules)) {
            $schedules = $request->schedules;
        }
        
        // Clean up schedules - remove empty entries
        $cleanedSchedules = [];
        foreach ($schedules as $schedule) {
            $time = isset($schedule['time']) ? trim($schedule['time']) : '';
            $description = isset($schedule['description']) ? trim($schedule['description']) : '';
            
            // Only include if at least one field has content
            if (!empty($time) || !empty($description)) {
                $cleanedSchedules[] = [
                    'time' => $time,
                    'description' => $description
                ];
            }
        }
        
        Log::info('Processed schedules:', [
            'raw_count' => count($schedules),
            'cleaned_count' => count($cleanedSchedules),
            'data' => $cleanedSchedules
        ]);
        
        // Update using DB facade
        $updated = \DB::table('programs')
            ->where('id', $id)
            ->update([
                'schedules' => json_encode($cleanedSchedules),
                'updated_at' => now()
            ]);
        
        Log::info('DB Update result: ' . ($updated ? 'SUCCESS' : 'NO CHANGES'));
        
        // Verify
        $saved = \DB::table('programs')
            ->where('id', $id)
            ->first(['schedules']);
        
        Log::info('Verification:', [
            'schedules' => $saved->schedules
        ]);
        
        Log::info('=== TENTATIVE SAVE END ===');
        
        return response()->json([
            'success' => true,
            'message' => 'Tentative saved successfully'
        ]);
        
    } catch (\Exception $e) {
        Log::error('=== TENTATIVE SAVE ERROR ===');
        Log::error('Error: ' . $e->getMessage());
        Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
        
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}
    /** -------------------------------------------------------------
     *  SAVE VIP SECTION
     * ------------------------------------------------------------ */
// Replace the saveVip method in AdminProgramController.php with this:

public function saveVip(Request $request, $id)
{
    try {
        $program = Program::findOrFail($id);
        
        Log::info('=== VIP SAVE START ===');
        Log::info('Program ID: ' . $id);
        Log::info('Request has vip_list: ' . ($request->has('vip_list') ? 'yes' : 'no'));
        
        // Validate the request
        $request->validate([
            'vip_list.*.name' => 'required|string|max:255',
            'vip_list.*.position' => 'required|string|max:255',
            'vip_list.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'vip_list.*.existing_image' => 'nullable|string'
        ]);

        $vip_list = [];

        if ($request->has('vip_list') && is_array($request->vip_list)) {
            foreach ($request->vip_list as $index => $vip) {
                
                Log::info("Processing VIP {$index}", [
                    'name' => $vip['name'] ?? 'N/A',
                    'position' => $vip['position'] ?? 'N/A',
                    'has_file' => $request->hasFile("vip_list.{$index}.image"),
                    'has_existing' => isset($vip['existing_image'])
                ]);
                
                // Skip if name or position is empty
                if (empty($vip['name']) || empty($vip['position'])) {
                    Log::warning("Skipping VIP {$index} - empty name or position");
                    continue;
                }
                
                $entry = [
                    'name'     => trim($vip['name']),
                    'position' => trim($vip['position']),
                ];

                // Handle image upload
                if ($request->hasFile("vip_list.{$index}.image")) {
                    // Delete old image if exists
                    if (isset($program->vip_list[$index]['image'])) {
                        $oldImage = $program->vip_list[$index]['image'];
                        if (Storage::disk('public')->exists($oldImage)) {
                            Storage::disk('public')->delete($oldImage);
                            Log::info("Deleted old image: {$oldImage}");
                        }
                    }
                    
                    // Store new image
                    $file = $request->file("vip_list.{$index}.image");
                    $filename = time() . '_' . $index . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $imagePath = $file->storeAs('vip_images', $filename, 'public');
                    $entry['image'] = $imagePath;
                    
                    Log::info("Uploaded new image: {$imagePath}");
                } 
                elseif (isset($vip['existing_image']) && !empty($vip['existing_image'])) {
                    // Keep existing image
                    $entry['image'] = $vip['existing_image'];
                    Log::info("Keeping existing image: {$vip['existing_image']}");
                }

                $vip_list[] = $entry;
            }
        }
        
        // Ensure at least one VIP entry
        if (empty($vip_list)) {
            Log::error('No valid VIP entries provided');
            return response()->json([
                'success' => false,
                'message' => 'At least one VIP entry with name and position is required'
            ], 422);
        }

        Log::info('Final VIP list count: ' . count($vip_list));
        Log::info('VIP data:', $vip_list);

        // Update using DB facade to avoid casting issues
        $updated = \DB::table('programs')
            ->where('id', $id)
            ->update([
                'vip_list' => json_encode($vip_list),
                'updated_at' => now()
            ]);

        Log::info('DB update result: ' . ($updated ? 'success' : 'no changes'));
        
        // Verify saved data
        $saved = \DB::table('programs')
            ->where('id', $id)
            ->first(['vip_list']);
        
        Log::info('Verification - saved vip_list: ' . $saved->vip_list);
        Log::info('=== VIP SAVE END ===');

        return response()->json([
            'success' => true,
            'message' => 'VIP list updated successfully'
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('VIP Validation Error: ' . json_encode($e->errors()));
        return response()->json([
            'success' => false, 
            'message' => 'Validation failed: ' . json_encode($e->errors())
        ], 422);
    } catch (\Exception $e) {
        Log::error('VIP Save Error: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        return response()->json([
            'success' => false, 
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}
    /** -------------------------------------------------------------
     *  SAVE PARTICIPATION SECTION
     * ------------------------------------------------------------ */
    public function saveParticipation(Request $request, $id)
{
    try {
        $program = Program::findOrFail($id);

        $request->validate([
            'participation_description' => 'array',
            'participation_prices' => 'array',
            'participation_additional_files' => 'nullable|file|mimes:pdf|max:30720', // NEW
            'participation_form_type' => 'required|in:file,link',
            'participation_form_file' => 'nullable|file|mimes:pdf|max:30720',
            'participation_form_link' => 'nullable|url'
        ]);

        // Filter descriptions
        $descriptions = array_filter($request->participation_description ?? [], function($item) {
            return !empty(trim($item));
        });

        // Filter prices - ONLY INCLUDE IF AT LEAST ONE FIELD HAS CONTENT
        $prices = [];
        if ($request->participation_prices) {
            foreach ($request->participation_prices as $price) {
                // Check if either description OR amount has content
                if (!empty(trim($price['description'] ?? '')) || !empty(trim($price['amount'] ?? ''))) {
                    $prices[] = [
                        'description' => trim($price['description'] ?? ''),
                        'amount' => trim($price['amount'] ?? '')
                    ];
                }
            }
        }

        // Handle additional files (NEW)
        $additionalFiles = $program->participation_additional_files;
        if ($request->hasFile('participation_additional_files')) {
            // Delete old file if exists
            if ($additionalFiles) {
                Storage::disk('public')->delete($additionalFiles);
            }
            $additionalFiles = $request->file('participation_additional_files')
                ->store('participation_files', 'public');
        }

        // Handle form
        $formValue = $program->participation_form;
        if ($request->participation_form_type === 'file' && $request->hasFile('participation_form_file')) {
            if ($program->participation_form && $program->participation_form_type === 'file') {
                Storage::disk('public')->delete($program->participation_form);
            }
            $formValue = $request->file('participation_form_file')
                ->store('participation_forms', 'public');
        } elseif ($request->participation_form_type === 'link') {
            $formValue = $request->participation_form_link;
        }

        // CRITICAL FIX: Use DB update
        \DB::table('programs')
            ->where('id', $id)
            ->update([
                'participation_description' => json_encode(array_values($descriptions)),
                'participation_prices' => json_encode($prices), // Filtered array
                'participation_additional_files' => $additionalFiles, // NEW
                'participation_form' => $formValue,
                'participation_form_type' => $request->participation_form_type,
                'updated_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Participation updated successfully'
        ]);

    } catch (\Exception $e) {
        Log::error('Error saving participation: ' . $e->getMessage());
        return response()->json([
            'success' => false, 
            'message' => $e->getMessage()
        ], 500);
    }
}

    /** -------------------------------------------------------------
     *  SAVE SPONSORSHIP SECTION
     * ------------------------------------------------------------ */
public function saveSponsorship(Request $request, $id)
{
    try {
        $program = Program::findOrFail($id);

        $request->validate([
            'sponsorship_description' => 'array',
            'sponsorship_packages' => 'array',
            'sponsorship_additional_files' => 'nullable|file|mimes:pdf|max:30720',
            'sponsorship_form_type' => 'required|in:file,link',
            'sponsorship_form_file' => 'nullable|file|mimes:pdf|max:30720',
            'sponsorship_form_link' => 'nullable|url'
        ]);

        // ========== 1. FILTER DESCRIPTIONS ==========
        $descriptions = [];
        if ($request->has('sponsorship_description') && is_array($request->sponsorship_description)) {
            $descriptions = array_filter($request->sponsorship_description, function($item) {
                return !empty(trim($item));
            });
            $descriptions = array_values($descriptions); // Re-index array
        }

        // ========== 2. FILTER PACKAGES - ONLY INCLUDE IF AT LEAST ONE FIELD HAS CONTENT ==========
        $packages = [];
        
        if ($request->has('sponsorship_packages') && is_array($request->sponsorship_packages)) {
            foreach ($request->sponsorship_packages as $package) {
                $desc = trim($package['description'] ?? '');
                $amount = trim($package['amount'] ?? '');
                
                // Only add if at least one field has content
                if (!empty($desc) || !empty($amount)) {
                    $packages[] = [
                        'description' => $desc,
                        'amount' => $amount
                    ];
                }
            }
        }

        // ========== 3. HANDLE ADDITIONAL FILES ==========
        $additionalFiles = $program->sponsorship_additional_files;
        
        if ($request->hasFile('sponsorship_additional_files')) {
            // Delete old file if exists
            if ($additionalFiles) {
                Storage::disk('public')->delete($additionalFiles);
            }
            $additionalFiles = $request->file('sponsorship_additional_files')
                ->store('sponsorship_files', 'public');
        }
        // If no new file, keep existing file or null

        // ========== 4. HANDLE FORM ==========
        $formValue = $program->sponsorship_form;
        $formType = $request->sponsorship_form_type;
        
        if ($formType === 'file' && $request->hasFile('sponsorship_form_file')) {
            // Delete old form file if exists
            if ($program->sponsorship_form && $program->sponsorship_form_type === 'file') {
                Storage::disk('public')->delete($program->sponsorship_form);
            }
            $formValue = $request->file('sponsorship_form_file')
                ->store('sponsorship_forms', 'public');
        } elseif ($formType === 'link') {
            $formValue = $request->sponsorship_form_link ?? '';
        }
        // If no file uploaded and no link, keep existing value

        // ========== 5. UPDATE DATABASE - NO NEW COLUMNS ==========
        \DB::table('programs')
            ->where('id', $id)
            ->update([
                'sponsorship_description' => json_encode(array_values($descriptions)),
                'sponsorship_packages' => json_encode($packages),
                'sponsorship_additional_files' => $additionalFiles,
                'sponsorship_form' => $formValue,
                'sponsorship_form_type' => $formType,
                'updated_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Sponsorship section updated successfully',
            'data' => [
                'packages_count' => count($packages),
                'has_description' => count($descriptions) > 0,
                'has_additional_files' => !empty($additionalFiles),
                'has_form' => !empty($formValue)
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Error saving sponsorship: ' . $e->getMessage());
        Log::error('Request data: ' . json_encode($request->all()));
        
        return response()->json([
            'success' => false, 
            'message' => $e->getMessage()
        ], 500);
    }
}

public function removeFile(Request $request, $id)
{
    try {
        // Find the program
        $program = Program::findOrFail($id);

        // Get the field name and file path from request
        $fieldName = $request->input('field_name');
        $filePath = $request->input('file_path');

        Log::info('Removing file:', [
            'program_id' => $id,
            'field_name' => $fieldName,
            'file_path' => $filePath
        ]);

        // Validate field name to prevent abuse
        $allowedFields = [
            'sponsorship_additional_files',
            'sponsorship_form',
            'participation_additional_files',
            'participation_form'
        ];

        if (!in_array($fieldName, $allowedFields)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid field name'
            ], 403);
        }

        // Delete file from storage if it exists
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
            Log::info('File deleted from storage: ' . $filePath);
        }

        // Update database - set field to null
        \DB::table('programs')
            ->where('id', $id)
            ->update([
                $fieldName => null,
                'updated_at' => now()
            ]);

        Log::info('Database updated, field set to null: ' . $fieldName);

        return response()->json([
            'success' => true,
            'message' => 'File removed successfully'
        ], 200);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        Log::error('Program not found: ' . $id);
        return response()->json([
            'success' => false,
            'message' => 'Program not found'
        ], 404);

    } catch (\Exception $e) {
        Log::error('Error removing file: ' . $e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to remove file: ' . $e->getMessage()
        ], 500);
    }
}

    /** =====================================================================
     *  PROGRAMME SECTION - METHODS FOR MULTIPLE PROGRAMME ITEMS
     * ===================================================================== */

    /**
     * Get all programme items for a program
     */
    public function getProgrammeItems($id)
    {
        try {
            $program = Program::findOrFail($id);
            $items = ProgrammeItem::where('program_id', $id)
                ->orderBy('order')
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'description' => json_decode($item->description, true) ?? [],
                        'images' => $item->images ?? [],
                        'order' => $item->order
                    ];
                });

            return response()->json($items);
        } catch (\Exception $e) {
            Log::error('Error getting programme items: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Save new programme item
     */
    public function saveProgramme(Request $request, $id)
    {
        try {
            $program = Program::findOrFail($id);

            $request->validate([
                'programme_title' => 'required|string|max:255',
                'programme_description' => 'nullable|array',
                'programme_images' => 'nullable|array',
                'programme_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:15360',
            ]);

            // Upload images
            $imagePaths = [];
            if ($request->hasFile('programme_images')) {
                $images = is_array($request->file('programme_images')) 
                    ? $request->file('programme_images') 
                    : [$request->file('programme_images')];
                
                foreach ($images as $image) {
                    if (count($imagePaths) >= 3) break;
                    
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('programme_images', $filename, 'public');
                    $imagePaths[] = $path;
                }
            }

            // Process description
            $description = [];
            if ($request->has('programme_description') && is_array($request->programme_description)) {
                foreach ($request->programme_description as $paragraph) {
                    if (!empty(trim($paragraph))) {
                        $description[] = trim($paragraph);
                    }
                }
            }

            // Get highest order
            $maxOrder = ProgrammeItem::where('program_id', $id)->max('order') ?? 0;

            // Create item
            ProgrammeItem::create([
                'program_id' => $program->id,
                'title' => $request->programme_title,
                'description' => json_encode($description),
                'images' => $imagePaths,
                'order' => $maxOrder + 1
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Programme item added successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error saving programme: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get single programme item for editing
     */
    public function editProgrammeItem($programId, $itemId)
    {
        try {
            $item = ProgrammeItem::where('program_id', $programId)
                ->where('id', $itemId)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'item' => [
                    'id' => $item->id,
                    'title' => $item->title,
                    'description' => json_decode($item->description, true) ?? [],
                    'images' => $item->images ?? [],
                    'order' => $item->order
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update existing programme item
     */
    public function updateProgrammeItem(Request $request, $programId, $itemId)
    {
        try {
            $item = ProgrammeItem::where('program_id', $programId)
                ->where('id', $itemId)
                ->firstOrFail();

            $request->validate([
                'programme_title' => 'required|string|max:255',
                'programme_description' => 'nullable|array',
                'programme_images' => 'nullable|array|max:3',
                'programme_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:15360',
            ]);

            // Get existing images
            $existingImages = $item->images ?? [];
            
            // Upload new images if any
            if ($request->hasFile('programme_images')) {
                $images = is_array($request->file('programme_images')) 
                    ? $request->file('programme_images') 
                    : [$request->file('programme_images')];
                
                foreach ($images as $image) {
                    if (count($existingImages) >= 3) break;
                    
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('programme_images', $filename, 'public');
                    $existingImages[] = $path;
                }
            }

            // Process description
            $description = [];
            if ($request->has('programme_description') && is_array($request->programme_description)) {
                foreach ($request->programme_description as $paragraph) {
                    if (!empty(trim($paragraph))) {
                        $description[] = trim($paragraph);
                    }
                }
            }

            $item->update([
                'title' => $request->programme_title,
                'description' => json_encode($description),
                'images' => array_slice($existingImages, 0, 3)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Programme item updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating programme item: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete programme item
     */
    public function deleteProgrammeItem($programId, $itemId)
    {
        try {
            $item = ProgrammeItem::where('program_id', $programId)
                ->where('id', $itemId)
                ->firstOrFail();

            // Delete images
            if ($item->images) {
                foreach ($item->images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }

            $item->delete();

            return response()->json([
                'success' => true,
                'message' => 'Programme item deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete single image from programme item
     */
    public function deleteProgrammeImage(Request $request, $programId, $itemId)
    {
        try {
            $item = ProgrammeItem::where('program_id', $programId)
                ->where('id', $itemId)
                ->firstOrFail();

            $imagePath = $request->input('image_path');
            $images = $item->images ?? [];

            // Find and remove the image
            $key = array_search($imagePath, $images);
            if ($key !== false) {
                Storage::disk('public')->delete($images[$key]);
                unset($images[$key]);
                $images = array_values($images);
            }

            $item->update(['images' => $images]);

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /** -------------------------------------------------------------
     *  TOGGLE SECTION VISIBILITY
     * ------------------------------------------------------------ */
    public function toggleSection(Request $request, $id)
    {
        try {
            $program = Program::findOrFail($id);

            $visibleSections = $program->visible_sections ?? [];
            $visibleSections[$request->section] = $request->is_visible;

            // CRITICAL FIX: Use DB update
            \DB::table('programs')
                ->where('id', $id)
                ->update([
                    'visible_sections' => json_encode($visibleSections),
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Section visibility updated'
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /** -------------------------------------------------------------
     *  TOGGLE WHOLE PROGRAM VISIBILITY
     * ------------------------------------------------------------ */
    public function toggleVisibility(Request $request, $id)
    {
        try {
            $program = Program::findOrFail($id);
            $program->is_visible = $request->is_visible;
            $program->save();

            return response()->json([
                'success' => true,
                'message' => 'Program visibility updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


  /** =====================================================================
     *  PHOTO SECTION - METHODS FOR PHOTO ITEMS
     * ===================================================================== */

    /**
     * Get all photo items for a program
     */
    public function getPhotoItems($id)
    {
        try {
            $program = Program::findOrFail($id);
            $items = PhotoItem::where('program_id', $id)
                ->orderBy('order')
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'description' => $item->description,
                        'image' => $item->image,
                        'order' => $item->order
                    ];
                });

            return response()->json($items);
        } catch (\Exception $e) {
            Log::error('Error getting photo items: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

// Find the savePhoto method in AdminProgramController.php and replace it with this:

/**
 * Save new photo items (unlimited photos)
 */
public function savePhoto(Request $request, $id)
{
    try {
        $program = Program::findOrFail($id);

        $request->validate([
            'photo_images' => 'required|array', // Removed max:5 limit
            'photo_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:15360',
        ]);

        // Get highest order
        $maxOrder = PhotoItem::where('program_id', $id)->max('order') ?? 0;

        $savedPhotos = [];

        // Upload and save each photo
        if ($request->hasFile('photo_images')) {
            foreach ($request->file('photo_images') as $index => $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('photo_images', $filename, 'public');

                $photoItem = PhotoItem::create([
                    'program_id' => $program->id,
                    'title' => 'Photo',
                    'image' => $path,
                    'order' => $maxOrder + $index + 1
                ]);

                $savedPhotos[] = $photoItem;
            }
        }

        return response()->json([
            'success' => true,
            'message' => count($savedPhotos) . ' photo(s) added successfully',
            'photos' => $savedPhotos
        ]);

    } catch (\Exception $e) {
        Log::error('Error saving photo: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

    /**
     * Get single photo item for editing
     */
    public function editPhotoItem($programId, $itemId)
    {
        try {
            $item = PhotoItem::where('program_id', $programId)
                ->where('id', $itemId)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'item' => [
                    'id' => $item->id,
                    'title' => $item->title,
                    'description' => $item->description,
                    'image' => $item->image,
                    'order' => $item->order
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update existing photo item
     */
    public function updatePhotoItem(Request $request, $programId, $itemId)
    {
        try {
            $item = PhotoItem::where('program_id', $programId)
                ->where('id', $itemId)
                ->firstOrFail();

            $request->validate([
                'photo_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:15360',
            ]);

            $imagePath = $item->image;

            // Upload new image if provided
            if ($request->hasFile('photo_image')) {
                // Delete old image
                if ($imagePath) {
                    Storage::disk('public')->delete($imagePath);
                }

                $file = $request->file('photo_image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $imagePath = $file->storeAs('photo_images', $filename, 'public');
            }

            $item->update([
                'title' => $request->input('photo_title', $item->title),
                'description' => $request->input('photo_description', $item->description),
                'image' => $imagePath
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Photo updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating photo item: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete photo item
     */
    public function deletePhotoItem($programId, $itemId)
    {
        try {
            $item = PhotoItem::where('program_id', $programId)
                ->where('id', $itemId)
                ->firstOrFail();

            // Delete image
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }

            $item->delete();

            return response()->json([
                'success' => true,
                'message' => 'Photo deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    /**
 * Get participant list for this program (from participation module)
 */
/**
 * Get participant list for this program (from participation module)
 */
public function getParticipantList($id)
{
    try {
        $program = Program::findOrFail($id);
        
        Log::info('=== GET PARTICIPANT LIST START ===');
        Log::info('Program ID: ' . $id);
        Log::info('Participation Programme ID: ' . ($program->participation_programme_id ?? 'null'));
        
        // Check if program is linked to a participation programme
        if (!$program->participation_programme_id) {
            return response()->json([
                'success' => true,
                'message' => 'No participation programme linked to this programme',
                'submissions' => []
            ]);
        }
        
        // Get approved submissions with participants
        $submissions = \App\Models\ParticipationSubmission::where('programme_id', $program->participation_programme_id)
            ->where('status', 'approved')
            ->with(['participants' => function($query) {
                $query->orderBy('sort_order');
            }])
            ->orderBy('company_name')
            ->get();
        
        Log::info('Found submissions: ' . $submissions->count());
        
        $formattedSubmissions = $submissions->map(function($submission) {
            return [
                'id' => $submission->id,
                'company_name' => $submission->company_name,
                'participants' => $submission->participants->map(function($participant) {
                    return [
                        'name' => $participant->name,
                        'position' => $participant->position,
                        'table_number' => $participant->table_number ?? '-'
                    ];
                })->toArray()
            ];
        });
        
        $totalParticipants = $submissions->sum(function($s) {
            return $s->participants->count();
        });
        
        Log::info('Total participants: ' . $totalParticipants);
        Log::info('=== GET PARTICIPANT LIST END ===');
        
        return response()->json([
            'success' => true,
            'submissions' => $formattedSubmissions,
            'total_companies' => $submissions->count(),
            'total_participants' => $totalParticipants
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error getting participant list: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
/**
 * Link program to participation programme
 */
public function linkParticipation(Request $request, $id)
{
    try {
        $program = Program::findOrFail($id);
        
        Log::info('=== LINK PARTICIPATION START ===');
        Log::info('Program ID: ' . $id);
        Log::info('Request data:', $request->all());
        
        $request->validate([
            'participation_programme_id' => 'nullable|exists:participation_programmes,id',
        ]);
        
        $participationProgrammeId = $request->input('participation_programme_id');
        
        Log::info('Participation Programme ID: ' . ($participationProgrammeId ?? 'null'));
        
        // If linking to a new participation programme, unlink it from any other program first
        // (one participation programme can only be linked to one program)
        if ($participationProgrammeId) {
            \App\Models\Program::where('participation_programme_id', $participationProgrammeId)
                ->where('id', '!=', $id)
                ->update(['participation_programme_id' => null]);
            
            Log::info('Unlinked participation programme from other programs');
        }
        
        // Update the program using DB facade
        \DB::table('programs')
            ->where('id', $id)
            ->update([
                'participation_programme_id' => $participationProgrammeId,
                'updated_at' => now()
            ]);
        
        Log::info('Program updated with participation_programme_id: ' . ($participationProgrammeId ?? 'null'));
        
        $message = $participationProgrammeId 
            ? 'Successfully linked to participation programme' 
            : 'Participation programme link removed';
        
        Log::info('=== LINK PARTICIPATION END ===');
        
        return response()->json([
            'success' => true,
            'message' => $message
        ]);
        
    } catch (\Exception $e) {
        Log::error('=== LINK PARTICIPATION ERROR ===');
        Log::error('Error: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
}