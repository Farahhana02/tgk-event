<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\ParticipationProgramme;
use App\Models\ParticipationSubmission;

class PublicProgramController extends Controller
{
    /**
     * Display listing of visible programs
     */
    public function index()
    {
        $programs = Program::visible()
            ->orderBy('event_date', 'desc')
            ->get();

        return view('programs.index', compact('programs'));
    }

public function show($id)
{
    $program = Program::visible()
        ->with('photoItems')
        ->findOrFail($id);

    $section = request()->get('section', 'overview');

    // Decode visible sections
    $visibleSections = $program->visible_sections ?? [];

    if (is_string($visibleSections)) {
        $visibleSections = json_decode($visibleSections, true) ?? [];
    }

    if (!is_array($visibleSections)) {
        $visibleSections = [];
    }

    // IMPORTANT: Map 'participant-list' section to 'link-participation' visibility
    $sectionToCheck = $section === 'participant-list' ? 'link-participation' : $section;
    
    // Check if requested section is visible
    if (!($visibleSections[$sectionToCheck] ?? false)) {
        $section = collect($visibleSections)
            ->filter(fn ($v) => $v === true)
            ->keys()
            ->first() ?? 'overview';
    }

    // Initialize participant data with proper structure
    $participantData = [
        'submissions' => collect([]),
        'total_companies' => 0,
        'total_participants' => 0,
        'message' => null
    ];

    // Load participant data if section is participant-list
    if ($section === 'participant-list') {
        $participantData = $this->loadParticipantData($id);
    }

    return view('programs.show', compact('program', 'section', 'participantData'));
}

/**
 * Load participant data from linked participation programme
 * 
 * @param int $programId
 * @return array|null
 */
private function loadParticipantData($programId)
{
    try {
        // Find the program
        $program = \App\Models\Program::findOrFail($programId);
        
        \Log::info('=== LOAD PARTICIPANT DATA (PUBLIC) ===');
        \Log::info('Program ID: ' . $programId);
        \Log::info('Participation Programme ID: ' . ($program->participation_programme_id ?? 'null'));
        
        // Check if it has a linked participation programme
        if (!$program->participation_programme_id) {
            return [
                'submissions' => collect([]),
                'total_companies' => 0,
                'total_participants' => 0,
                'message' => 'No participation programme linked to this programme'
            ];
        }
        
        // Get approved submissions with participants
        $submissions = \App\Models\ParticipationSubmission::where('programme_id', $program->participation_programme_id)
            ->where('status', 'approved')
            ->with(['participants' => function($query) {
                $query->orderBy('sort_order');
            }])
            ->orderBy('company_name')
            ->get();
        
        \Log::info('Found submissions: ' . $submissions->count());
        
        $totalParticipants = $submissions->sum(function($submission) {
            return $submission->participants->count();
        });
        
        \Log::info('Total participants: ' . $totalParticipants);
        
        return [
            'submissions' => $submissions,
            'total_companies' => $submissions->count(),
            'total_participants' => $totalParticipants,
            'message' => null
        ];
        
    } catch (\Exception $e) {
        \Log::error('Error loading participant data: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return [
            'submissions' => collect([]),
            'total_companies' => 0,
            'total_participants' => 0,
            'message' => 'Error loading participant data: ' . $e->getMessage()
        ];
    }
}
    
}