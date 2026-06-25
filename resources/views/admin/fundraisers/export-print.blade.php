<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor List - {{ $fundraiser->programme_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            color: #333;
            background: #fff;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #00542A;
        }
        
        .header h1 {
            color: #00542A;
            font-size: 28px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .header p {
            font-size: 16px;
            color: #666;
            text-transform: uppercase;
        }
        
        .info {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }
        
        .info-item strong {
            color: #00542A;
            font-weight: 700;
            text-transform: uppercase;
        }
        
        .info-item span {
            font-weight: 600;
            text-transform: uppercase;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 12px 8px;
            text-align: left;
        }
        
        th {
            background-color: #00542A;
            color: white;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 13px;
        }
        
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        td {
            font-size: 13px;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            display: inline-block;
            text-transform: uppercase;
        }
        
        .status-approved {
            background: #d4edda;
            color: #155724;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            display: inline-block;
            text-transform: uppercase;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        
        .no-print {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            margin: 0 5px;
            transition: all 0.3s ease;
        }
        
        .btn-print {
            background: #00542A;
            color: white;
        }
        
        .btn-print:hover {
            background: #003d1f;
            transform: translateY(-2px);
        }
        
        .btn-close {
            background: #6c757d;
            color: white;
        }
        
        .btn-close:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
        
        @media print {
            body {
                padding: 15px;
            }
            
            .no-print {
                display: none !important;
            }
            
            table {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DONOR LIST</h1>
        <p>{{ strtoupper($fundraiser->programme_name) }}</p>
    </div>
    
    <div class="info">
        <div class="info-grid">
            <div class="info-item">
                <strong>Programme:</strong>
                <span>{{ strtoupper($fundraiser->programme_name) }}</span>
            </div>
            <div class="info-item">
                <strong>Export Date:</strong>
                <span>{{ now()->format('d/m/Y') }}</span>
            </div>
            <div class="info-item">
                <strong>Target Amount:</strong>
                <span>RM {{ number_format($fundraiser->target_amount, 2) }}</span>
            </div>
            <div class="info-item">
                <strong>Total Raised:</strong>
                <span>RM {{ number_format($fundraiser->total_raised ?? 0, 2) }}</span>
            </div>
            <div class="info-item">
                <strong>Progress:</strong>
                <span>{{ $fundraiser->progress }} %</span>
            </div>
            <div class="info-item">
                <strong>Total Donors:</strong>
                <span>{{ $fundraiser->donors->count() }}</span>
            </div>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">BIL</th>
                <th style="width: 15%;">DONOR</th>
                <th style="width: 18%;">EMAIL</th>
                <th style="width: 12%;">PHONE</th>
                <th style="width: 12%;">AMOUNT</th>
                <th style="width: 18%;">NOTES</th>
                <th style="width: 10%;">STATUS</th>
                <th style="width: 10%;">DATE</th>
            </tr>
        </thead>
        <tbody>
            @forelse($fundraiser->donors as $index => $donor)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ strtoupper($donor->name) }}</td>
                <td>{{ strtolower($donor->email) }}</td>
                <td>{{ $donor->phone }}</td>
                <td>RM {{ number_format($donor->amount, 2) }}</td>
                <td>{{ strtoupper($donor->notes ?? '-') }}</td>
                <td style="text-align: center;">
                    <span class="status-{{ strtolower($donor->status) }}">
                        {{ strtoupper($donor->status) }}
                    </span>
                </td>
                <td style="text-align: center;">{{ $donor->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px; color: #999;">
                    NO DONORS YET
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        <p><strong>Generated on:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
        <p style="margin-top: 10px;">© All Rights Reserved TGK EVENTS</p>
    </div>
    
    <div class="no-print">
        <button onclick="window.print()" class="btn btn-print">
            🖨️ PRINT
        </button>
        <button onclick="window.close()" class="btn btn-close">
            ✖️ CLOSE
        </button>
    </div>
</body>
</html>