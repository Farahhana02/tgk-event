<?php

namespace App\Exports;

use App\Models\ParticipationProgramme;
use App\Models\ParticipationSubmission;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithStyles,
    WithEvents,
    ShouldAutoSize
};
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class ParticipantListExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    WithEvents,
    ShouldAutoSize
{
    protected $programme;
    protected $status;
    protected $agency;

    public function __construct(
        ParticipationProgramme $programme,
        $status = null,
        $agency = null
    ) {
        $this->programme = $programme;
        $this->status    = $status;
        $this->agency    = $agency;
    }

    /* ==================================================
       DATA ROWS (ONE ROW PER PARTICIPANT)
    ================================================== */
    public function collection()
    {
        $rows = collect();

        $submissions = ParticipationSubmission::where('programme_id', $this->programme->id)
            ->with(['participants', 'programmePackage'])
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->agency, fn ($q) => $q->where('company_name', $this->agency))
            ->orderBy('created_at')
            ->get();

        $no = 1;

        foreach ($submissions as $s) {
            foreach ($s->participants as $index => $p) {
                $rows->push([
                    $index === 0 ? $no : '',
                    $index === 0 ? $s->company_name : '',
                    $index === 0 ? $s->officer_name : '',
                    $index === 0 ? $s->phone_number : '',
                    $index === 0 ? $s->package_name : '',
                    $index === 0 ? $s->quantity : '',
                    $index === 0 ? $s->total_price : '',
                    $p->name,
                    $p->position,
                    $p->table_number ?? '-',  
                    $index === 0 ? strtoupper($s->status) : '', // STATUS → COLUMN K
                ]);
            }
            $no++;
        }

        return $rows;
    }

    /* ==================================================
       TOP HEADERS & PROGRAMME INFO
    ================================================== */
    public function headings(): array
    {
        // ✅ Format time in 12-hour format with AM/PM
        $timeDisplay = '-';
        if ($this->programme->start_time) {
            try {
                $timeDisplay = \Carbon\Carbon::parse($this->programme->start_time)->format('h:i A');
            } catch (\Exception $e) {
                $timeDisplay = '-';
            }
        }
        if ($this->programme->end_time) {
            try {
                $timeDisplay .= ' – ' . \Carbon\Carbon::parse($this->programme->end_time)->format('h:i A');
            } catch (\Exception $e) {
                // Keep existing time display
            }
        }

        return [
            ['PARTICIPANT LIST'],
            [],
            ['Programme : ' . $this->programme->title],
            ['Venue     : ' . ($this->programme->venue ?? '-')],
            ['Start     : ' . optional($this->programme->start_date)->format('d/m/Y')],
            ['End       : ' . optional($this->programme->end_date)->format('d/m/Y')],
            ['Time      : ' . $timeDisplay],
            [],
            [
                ($this->status ? strtoupper($this->status) : 'ALL STATUS')
                . ' | '
                . ($this->agency ?: 'ALL AGENCY')
            ],
            [],
            [
                'NO', 'COMPANY', 'OFFICER', 'PHONE',
                'PACKAGE', 'QTY', 'TOTAL',
                'NAME', 'POSITION','TABLE NO', 'STATUS'
            ],
        ];
    }

    /* ==================================================
       BASIC FONT STYLES
    ================================================== */
    public function styles(Worksheet $sheet)
    {
        return [
            1  => ['font' => ['bold' => true, 'size' => 16]],
            3  => ['font' => ['bold' => true]],
            4  => ['font' => ['bold' => true]],
            5  => ['font' => ['bold' => true]],
            6  => ['font' => ['bold' => true]],
            7  => ['font' => ['bold' => true]],
            9  => ['font' => ['bold' => true]],
            11 => ['font' => ['bold' => true]],
        ];
    }

    /* ==================================================
       EVENTS (MERGE, ALIGN, LANDSCAPE)
    ================================================== */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                /* ---- Merge header rows ---- */
                foreach ([1,3,4,5,6,7,9] as $row) {
                    $sheet->mergeCells("A{$row}:K{$row}");
                }

                /* ---- Table header styling ---- */
                $sheet->getStyle('A11:K11')->applyFromArray([
                    'font' => [
                        'bold'  => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                        'wrapText'   => true,
                    ],
                    'fill' => [
                        'fillType'   => 'solid',
                        'startColor' => ['rgb' => '00542A'],
                    ],
                ]);

                /* ---- Wrap participant columns ---- */
                $sheet->getStyle('C:C')->getAlignment()->setWrapText(true); // OFFICER
                $sheet->getStyle('H:J')->getAlignment()->setWrapText(true); // PARTICIPANT

                /* ---- Alignment ---- */
                $sheet->getStyle('A:A')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('F:G')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('J:J')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // TABLE NO
                $sheet->getStyle('K:K')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // STATUS


                /* ==================================================
                   MERGE TABLE ROWS (PER SUBMISSION)
                ================================================== */
                $currentRow = 12;

                $submissions = ParticipationSubmission::where('programme_id', $this->programme->id)
                    ->with('participants')
                    ->orderBy('created_at')
                    ->get();

                foreach ($submissions as $submission) {

                    $count = $submission->participants->count();

                    if ($count > 1) {
                        $endRow = $currentRow + $count - 1;

                        // Merge ONLY submission-level columns
                        foreach (['A','B','C','D','E','F','G','K'] as $col) {
                            $sheet->mergeCells("{$col}{$currentRow}:{$col}{$endRow}");
                            $sheet->getStyle("{$col}{$currentRow}:{$col}{$endRow}")
                                ->getAlignment()
                                ->setVertical(Alignment::VERTICAL_CENTER);
                        }

                        $currentRow = $endRow + 1;
                    } else {
                        $currentRow++;
                    }

                }

                /* ---- Page setup ---- */
                $sheet->getPageSetup()->setOrientation(
                    PageSetup::ORIENTATION_LANDSCAPE
                );
            }
        ];
    }
}