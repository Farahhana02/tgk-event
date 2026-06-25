<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Participant List - {{ $programme->title }}</title>

    <style>
        @page {
    size: A4 landscape;
    margin: 12mm;
}

@media print {
    body {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}

        :root {
            --green: #00542A;
            --gold: #C08329;
            --gray: #6b7280;
        }

        * {
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            padding: 20px;
            font-size: 11px;
            color: #111827;
        }

        /* ================= HEADER ================= */
        .header {
            text-align: center;
            margin-bottom: 18px;
        }

        .header h1 {
            font-size: 20px;
            margin-bottom: 4px;
            color: var(--green);
            letter-spacing: 1px;
        }

        .header .subtitle {
            font-size: 13px;
            font-weight: bold;
        }

        .filters {
            margin-top: 6px;
            font-size: 11px;
            color: var(--gray);
        }

        .divider {
            border-bottom: 3px solid var(--green);
            margin: 14px 0;
        }

        /* ================= PROGRAMME INFO ================= */
        .programme-info {
            width: 100%;
            margin-bottom: 18px;
        }

        .programme-info td {
            padding: 4px 6px;
        }

        .programme-info strong {
            width: 110px;
            display: inline-block;
            color: var(--green);
        }

        /* ================= TABLE ================= */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            background: var(--green);
            color: #fff;
            padding: 8px 6px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }

        thead th.center {
            text-align: center;
        }

        tbody td {
            padding: 7px 6px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }

        tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        /* Participants columns */
        .participant-name {
            font-weight: bold;
        }

        .participant-position {
            color: var(--gray);
        }

        /* Status badge */
        .status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-approved {
            background: #d1fae5;
            color: #065f46;
        }

        /* Footer */
        .footer {
            margin-top: 18px;
            text-align: center;
            font-size: 10px;
            color: var(--gray);
        }

        @media print {
            thead { display: table-header-group; }
            tr { page-break-inside: avoid; }
        }
        .participant-table-no {
    text-align: center;
    font-weight: bold;
}
/* =========================================
   PARTICIPANTS HEADER SEPARATORS (PRINT)
========================================= */

/* White line under PARTICIPANTS header */
thead tr:first-child th[colspan="3"] {
    border-bottom: 2px solid #ffffff;
}

/* Vertical white lines between NAME | POSITION | TABLE NO */
thead tr:nth-child(2) th {
    text-align: center;
}

/* Remove last right border (after TABLE NO) */
thead tr:nth-child(2) th:last-child {
    border-right: none;
}
thead th {
    vertical-align: middle;
}
/* Ensure body cells are NOT affected */
tbody td {
    text-align: left;
}

/* Explicitly keep TABLE NO body centered (as intended) */
tbody td.center {
    text-align: center;
}
    </style>
</head>

<body>

{{-- ================= HEADER ================= --}}
<div class="header">
    <h1>PARTICIPANT LIST</h1>
    <div class="subtitle">{{ strtoupper($programme->title) }}</div>

    <div class="filters">
        {{ request('status') ? strtoupper(request('status')) : 'ALL STATUS' }}
        |
        {{ request('agency') ?: 'ALL AGENCY' }}
    </div>
</div>

<div class="divider"></div>

{{-- ================= PROGRAMME INFO ================= --}}
<table class="programme-info">
    <tr>
        <td><strong>Programme:</strong></td>
        <td>{{ $programme->title }}</td>
        <td><strong>Venue:</strong></td>
        <td>{{ $programme->venue ?? '-' }}</td>
    </tr>
    <tr>
        <td><strong>Start Date:</strong></td>
        <td>{{ optional($programme->start_date)->format('d/m/Y') }}</td>
        <td><strong>End Date:</strong></td>
        <td>{{ optional($programme->end_date)->format('d/m/Y') }}</td>
    </tr>
<tr>
    <td><strong>Time:</strong></td>
    <td colspan="3">
        @php
            $timeDisplay = '-';
            if ($programme->start_time) {
                try {
                    $timeDisplay = \Carbon\Carbon::parse($programme->start_time)->format('h:i A');
                } catch (\Exception $e) {
                    $timeDisplay = '-';
                }
            }
            if ($programme->end_time) {
                try {
                    $timeDisplay .= ' – ' . \Carbon\Carbon::parse($programme->end_time)->format('h:i A');
                } catch (\Exception $e) {
                    // Keep existing time display
                }
            }
        @endphp
        {{ $timeDisplay }}
    </td>
</tr>
</table>

{{-- ================= TABLE ================= --}}
<table>
    <thead>
        <tr>
            <th rowspan="2">NO</th>
            <th rowspan="2">COMPANY</th>
            <th rowspan="2">OFFICER</th>
            <th rowspan="2">PHONE</th>
            <th rowspan="2">PACKAGE</th>
            <th rowspan="2" class="center">QTY</th>
            <th rowspan="2">TOTAL</th>
            <th colspan="3" class="center">PARTICIPANTS</th>
            <th rowspan="2" class="center">STATUS</th>
        </tr>
        <tr>
            <th>NAME</th>
            <th>POSITION</th>
            <th class="center">TABLE NO</th>
        </tr>
    </thead>

    <tbody>
        @forelse($submissions as $i => $s)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $s->company_name }}</td>
            <td>{{ $s->officer_name }}</td>
            <td>{{ $s->phone_number }}</td>
            <td>{{ $s->package_name }}</td>
            <td class="center">{{ $s->quantity }}</td>
            <td>RM {{ number_format($s->total_price,2) }}</td>
            <td>
                @foreach($s->participants as $p)
                    <div class="participant-name">{{ $p->name }}</div>
                @endforeach
            </td>

            <td>
                @foreach($s->participants as $p)
                    <div class="participant-position">{{ $p->position }}</div>
                @endforeach
            </td>

            <td class="center">
                @foreach($s->participants as $p)
                    <div>{{ $p->table_number ?? '-' }}</div>
                @endforeach
            </td>

            <td class="center">
                <span class="status status-{{ $s->status }}">
                    {{ $s->status }}
                </span>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="11" style="text-align:center;padding:20px;">
                No submissions found.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- ================= FOOTER ================= --}}
<div class="footer">
    Total Submissions: <strong>{{ $submissions->count() }}</strong><br>
    Generated on {{ now()->format('d/m/Y H:i:s') }}<br>
    © KedahForward – Participation Management System
</div>

<script>
    window.onload = () => window.print();
</script>

</body>
</html>
