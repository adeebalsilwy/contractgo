<!DOCTYPE html>
<html dir="{{ $isRtl ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <title>{{ get_label('estimate_pdf', 'Estimate PDF') }} - {{ $estimate->title ?? 'Document' }}</title>
    <style>
        @page {
            margin: 20mm;
            size: A4;
            @bottom-right {
                content: "{{ get_label('page', 'Page') }} " counter(page) " {{ get_label('of', 'of') }} " counter(pages);
                font-size: 10pt;
                color: #666;
            }
        }
        
        @font-face {
            font-family: 'Tajawal';
            src: url('{{ public_path('vendor/gpdf/fonts/Tajawal-Normal.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        
        @font-face {
            font-family: 'Tajawal';
            src: url('{{ public_path('vendor/gpdf/fonts/Tajawal-Bold.ttf') }}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }
        
        body {
            font-family: 'Tajawal', 'DejaVu Sans', sans-serif;
            line-height: 1.6;
            font-size: 11pt;
            margin: 0;
            padding: 0;
            color: #333;
            direction: {{ $isRtl ? 'rtl' : 'ltr' }};
            text-align: {{ $isRtl ? 'right' : 'left' }};
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        
        .company-logo {
            max-width: 100px;
            height: auto;
            margin-bottom: 12px;
        }
        
        .company-name-ar {
            font-size: 18pt;
            font-weight: bold;
            color: #1a252f;
            margin-bottom: 5px;
        }
        
        .company-name-en {
            font-size: 16pt;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .document-title {
            text-align: center;
            font-size: 20pt;
            font-weight: bold;
            color: #2c3e50;
            margin: 20px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            background: linear-gradient(135deg, #3498db, #2c3e50);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .section {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #bdc3c7;
            border-radius: 8px;
            background-color: #f8f9fa;
            page-break-inside: avoid;
        }
        
        .section-title {
            font-weight: bold;
            font-size: 14pt;
            color: #2c3e50;
            margin-bottom: 15px;
            text-align: center;
            text-decoration: underline;
            padding-bottom: 8px;
            border-bottom: 2px solid #3498db;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .info-card {
            background: white;
            border-radius: 6px;
            padding: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 6px 0;
            border-bottom: 1px dashed #ecf0f1;
        }
        
        .info-label {
            font-weight: bold;
            color: #34495e;
            min-width: 120px;
        }
        
        .info-value {
            color: #2c3e50;
            font-weight: 500;
            text-align: {{ $isRtl ? 'left' : 'right' }};
        }
        
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .summary-table th {
            background: linear-gradient(135deg, #34495e, #2c3e50);
            color: white;
            font-weight: bold;
            padding: 12px;
            text-align: center;
        }
        
        .summary-table td {
            border: 1px solid #bdc3c7;
            padding: 10px;
            text-align: center;
        }
        
        .summary-table .label-cell {
            background-color: #ecf0f1;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .summary-table .amount-cell {
            font-weight: bold;
            color: #27ae60;
            font-size: 12pt;
        }
        
        .detailed-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .detailed-table th {
            background: linear-gradient(135deg, #34495e, #2c3e50);
            color: white;
            font-weight: bold;
            padding: 10px;
            text-align: center;
            border: 1px solid #2c3e50;
        }
        
        .detailed-table td {
            border: 1px solid #bdc3c7;
            padding: 8px;
            text-align: center;
        }
        
        .detailed-table th[colspan] {
            background: #2c3e50;
        }
        
        .detailed-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .detailed-table tr:hover {
            background-color: #e3f2fd;
        }
        
        .detailed-table .description-cell {
            text-align: {{ $isRtl ? 'right' : 'left' }};
            padding-{{ $isRtl ? 'right' : 'left' }}: 15px;
        }
        
        .signatures-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-around;
            page-break-inside: avoid;
        }
        
        .signature-box {
            text-align: center;
            width: 30%;
            border-top: 2px solid #333;
            padding-top: 15px;
        }
        
        .signature-title {
            font-weight: bold;
            font-size: 11pt;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        
        .signature-name {
            font-weight: bold;
            margin-top: 30px;
            font-size: 12pt;
            color: #34495e;
        }
        
        .signature-line {
            margin: 10px 0;
            color: #7f8c8d;
            font-size: 10pt;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9pt;
            color: #7f8c8d;
            border-top: 1px solid #bdc3c7;
            padding: 8px 0;
            background-color: #f8f9fa;
        }
        
        .watermark {
            position: fixed;
            opacity: 0.05;
            z-index: -1;
            font-size: 100pt;
            font-weight: bold;
            color: #34495e;
            transform: rotate(-45deg);
            white-space: nowrap;
            top: 40%;
            left: 10%;
            pointer-events: none;
        }
        
        .highlight-box {
            background-color: #e8f5e8;
            border: 2px solid #27ae60;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
            font-weight: bold;
            color: #27ae60;
            box-shadow: 0 2px 8px rgba(39, 174, 96, 0.2);
        }
        
        .currency-amount {
            font-weight: bold;
            color: #27ae60;
            font-size: 14pt;
        }
        
        .progress-bar {
            height: 20px;
            background-color: #ecf0f1;
            border-radius: 10px;
            margin: 10px 0;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #3498db, #2ecc71);
            border-radius: 10px;
            text-align: center;
            line-height: 20px;
            color: white;
            font-weight: bold;
            font-size: 10pt;
        }
        
        @media print {
            body {
                direction: {{ $isRtl ? 'rtl' : 'ltr' }};
                text-align: {{ $isRtl ? 'right' : 'left' }};
            }
        }
    </style>
</head>
<body>
    <div class="watermark">{{ get_label('estimate', 'Estimate') }}</div>
    
    <div class="header">
        @if($companyInfo['logo'])
            <img src="{{ public_path('storage/' . $companyInfo['logo']) }}" alt="Company Logo" class="company-logo">
        @endif
        <div class="company-name-ar">{{ $companyInfo['name_ar'] ?? $companyInfo['name'] }}</div>
        <div class="company-name-en">{{ $companyInfo['name_en'] ?? $companyInfo['name'] }}</div>
    </div>
    
    <div class="document-title">{{ get_label('estimate_details', 'Estimate Details') }}</div>
    
    <div class="info-grid">
        <div class="info-card">
            <div class="section-title">{{ get_label('estimate_information', 'Estimate Information') }}</div>
            <div class="info-row">
                <span class="info-label">{{ get_label('estimate_number', 'Estimate Number') }}:</span>
                <span class="info-value">{{ $estimate->id ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ get_label('contract_number', 'Contract Number') }}:</span>
                <span class="info-value">{{ $estimate->contract_id ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ get_label('estimate_date', 'Estimate Date') }}:</span>
                <span class="info-value">{{ $estimate->created_at ? format_date($estimate->created_at) : now()->format('Y-m-d') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ get_label('status', 'Status') }}:</span>
                <span class="info-value">{{ $estimate->status->title ?? 'N/A' }}</span>
            </div>
        </div>
        
        <div class="info-card">
            <div class="section-title">{{ get_label('project_details', 'Project Details') }}</div>
            <div class="info-row">
                <span class="info-label">{{ get_label('project_name', 'Project Name') }}:</span>
                <span class="info-value">{{ $estimate->project->title ?? $estimate->contract->project->title ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ get_label('project_location', 'Project Location') }}:</span>
                <span class="info-value">{{ $estimate->project->address ?? $estimate->contract->project->address ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ get_label('client', 'Client') }}:</span>
                <span class="info-value">
                    {{ $estimate->client->first_name ?? $estimate->contract->client->first_name ?? '' }} 
                    {{ $estimate->client->last_name ?? $estimate->contract->client->last_name ?? '' }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ get_label('item', 'Item') }}:</span>
                <span class="info-value">{{ $estimate->item_description ?? get_label('gypsum_work', 'Gypsum Work') }}</span>
            </div>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">{{ get_label('financial_summary', 'Financial Summary') }}</div>
        
        <table class="summary-table">
            <tr>
                <td class="label-cell">{{ get_label('contract_value', 'Contract Value') }}</td>
                <td class="amount-cell">{{ format_currency($estimate->contract_value ?? 0) }}</td>
            </tr>
            <tr>
                <td class="label-cell">{{ get_label('net_value', 'Net Value') }}</td>
                <td class="amount-cell">{{ format_currency($estimate->net_value ?? 0) }}</td>
            </tr>
            <tr>
                <td class="label-cell">{{ get_label('completion_percentage', 'Completion Percentage') }}</td>
                <td class="amount-cell">
                    {{ number_format($estimate->completion_percentage ?? 0, 2) }}%
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $estimate->completion_percentage ?? 0 }}%">
                            {{ number_format($estimate->completion_percentage ?? 0, 2) }}%
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label-cell">{{ get_label('total_to_date', 'Total To Date') }}</td>
                <td class="amount-cell">{{ format_currency($estimate->total_to_date ?? 0) }}</td>
            </tr>
        </table>
    </div>
    
    <div class="section">
        <div class="section-title">{{ get_label('detailed_work_breakdown', 'Detailed Work Breakdown') }}</div>
        
        <table class="detailed-table">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 5%;">{{ get_label('no', 'No') }}</th>
                    <th rowspan="2" style="width: 25%;">{{ get_label('description', 'Description') }}</th>
                    <th rowspan="2" style="width: 8%;">{{ get_label('unit', 'Unit') }}</th>
                    <th colspan="3" style="width: 21%;">{{ get_label('total_work_to_date', 'Total Work Until Date') }}</th>
                    <th colspan="3" style="width: 21%;">{{ get_label('previous_work_done', 'Previous Work Done') }}</th>
                    <th colspan="3" style="width: 21%;">{{ get_label('current_estimate_work', 'Current Estimate Work Done') }}</th>
                </tr>
                <tr>
                    <th>{{ get_label('quantity', 'Qty') }}</th>
                    <th>{{ get_label('value', 'Value') }}</th>
                    <th>{{ get_label('percentage', 'Perc') }}</th>
                    <th>{{ get_label('quantity', 'Qty') }}</th>
                    <th>{{ get_label('value', 'Value') }}</th>
                    <th>{{ get_label('percentage', 'Perc') }}</th>
                    <th>{{ get_label('quantity', 'Qty') }}</th>
                    <th>{{ get_label('value', 'Value') }}</th>
                    <th>{{ get_label('percentage', 'Perc') }}</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($items) && count($items) > 0)
                    @php
                        $totalQuantity = 0;
                        $totalAmount = 0;
                        $totalPreviousQty = 0;
                        $totalPreviousAmount = 0;
                        $totalCurrentQty = 0;
                        $totalCurrentAmount = 0;
                        
                        foreach($items as $item) {
                            $totalQuantity += $item->pivot->qty ?? 0;
                            $totalAmount += $item->pivot->amount ?? 0;
                            $totalCurrentQty += $item->pivot->current_qty ?? 0;
                            $totalCurrentAmount += $item->pivot->current_amount ?? 0;
                        }
                        
                        $completionPercentage = $totalAmount > 0 ? ($totalCurrentAmount / $totalAmount) * 100 : 0;
                    @endphp
                    
                    @foreach($items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="description-cell">{{ $item->name ?? $item['name'] ?? 'N/A' }}</td>
                        <td>{{ $item->unit->name ?? $item['unit'] ?? 'M²' }}</td>
                        
                        <!-- Total work until date -->
                        <td>{{ number_format($item->pivot->qty ?? 0, 3) }}</td>
                        <td>{{ format_currency($item->pivot->amount ?? 0) }}</td>
                        <td>{{ number_format($totalAmount > 0 ? (($item->pivot->amount ?? 0) / $totalAmount) * 100 : 0, 2) }}%</td>
                        
                        <!-- Previous work done -->
                        <td>{{ number_format($item->pivot->previous_qty ?? 0, 3) }}</td>
                        <td>{{ format_currency($item->pivot->previous_amount ?? 0) }}</td>
                        <td>{{ number_format($totalAmount > 0 ? (($item->pivot->previous_amount ?? 0) / $totalAmount) * 100 : 0, 2) }}%</td>
                        
                        <!-- Current estimate work done -->
                        <td>{{ number_format($item->pivot->current_qty ?? 0, 3) }}</td>
                        <td>{{ format_currency($item->pivot->current_amount ?? 0) }}</td>
                        <td>{{ number_format($totalAmount > 0 ? (($item->pivot->current_amount ?? 0) / $totalAmount) * 100 : 0, 2) }}%</td>
                    </tr>
                    @endforeach
                    
                    <!-- Totals row -->
                    <tr style="background-color: #e8f5e8; font-weight: bold;">
                        <td colspan="3" style="text-align: {{ $isRtl ? 'left' : 'right' }};">{{ get_label('total', 'Total') }}</td>
                        
                        <!-- Total work until date -->
                        <td>{{ number_format($totalQuantity, 3) }}</td>
                        <td>{{ format_currency($totalAmount) }}</td>
                        <td>100.00%</td>
                        
                        <!-- Previous work done -->
                        <td>{{ number_format($totalPreviousQty, 3) }}</td>
                        <td>{{ format_currency($totalPreviousAmount) }}</td>
                        <td>{{ number_format($totalAmount > 0 ? ($totalPreviousAmount / $totalAmount) * 100 : 0, 2) }}%</td>
                        
                        <!-- Current estimate work done -->
                        <td>{{ number_format($totalCurrentQty, 3) }}</td>
                        <td>{{ format_currency($totalCurrentAmount) }}</td>
                        <td>{{ number_format($completionPercentage, 2) }}%</td>
                    </tr>
                @else
                    <!-- Sample data -->
                    <tr>
                        <td>1</td>
                        <td class="description-cell">{{ get_label('gypsum_board_work_incl_materials', 'Gypsum Board Work Including Materials') }}</td>
                        <td>M²</td>
                        <td>200.000</td>
                        <td>{{ format_currency(13600.00) }}</td>
                        <td>100.00%</td>
                        <td>0.000</td>
                        <td>{{ format_currency(0.00) }}</td>
                        <td>0.00%</td>
                        <td>180.000</td>
                        <td>{{ format_currency(12240.00) }}</td>
                        <td>90.00%</td>
                    </tr>
                    <tr style="background-color: #e8f5e8; font-weight: bold;">
                        <td colspan="3" style="text-align: {{ $isRtl ? 'left' : 'right' }};">{{ get_label('total', 'Total') }}</td>
                        <td>200.000</td>
                        <td>{{ format_currency(13600.00) }}</td>
                        <td>100.00%</td>
                        <td>0.000</td>
                        <td>{{ format_currency(0.00) }}</td>
                        <td>0.00%</td>
                        <td>180.000</td>
                        <td>{{ format_currency(12240.00) }}</td>
                        <td>90.00%</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    
    <div class="highlight-box">
        {{ get_label('net_value_calculation', 'Net Value Calculation') }}: 
        <span class="currency-amount">{{ format_currency($estimate->net_value ?? 12240.00) }}</span>
    </div>
    
    <div class="signatures-section">
        <div class="signature-box">
            <div class="signature-title">{{ get_label('engineering_side', 'Engineering Side') }}</div>
            <div class="signature-name">{{ $estimate->engineer_name ?? get_label('engineer_name', 'Engineer Name') }}</div>
            <div class="signature-line">{{ get_label('signature', 'Signature') }}: ________________</div>
            <div class="signature-line">{{ get_label('date', 'Date') }}: ____/____/______</div>
        </div>
        <div class="signature-box">
            <div class="signature-title">{{ get_label('contractor', 'Contractor') }}</div>
            <div class="signature-name">{{ $estimate->contractor_name ?? get_label('contractor_name', 'Contractor Name') }}</div>
            <div class="signature-line">{{ get_label('signature', 'Signature') }}: ________________</div>
            <div class="signature-line">{{ get_label('date', 'Date') }}: ____/____/______</div>
        </div>
        <div class="signature-box">
            <div class="signature-title">{{ get_label('project_management', 'Project Management') }}</div>
            <div class="signature-name">{{ get_label('project_manager', 'Project Manager') }}</div>
            <div class="signature-line">{{ get_label('signature', 'Signature') }}: ________________</div>
            <div class="signature-line">{{ get_label('date', 'Date') }}: ____/____/______</div>
        </div>
    </div>
    
    <div class="footer">
        {{ $companyInfo['name_ar'] ?? $companyInfo['name'] }} | {{ $companyInfo['name_en'] ?? $companyInfo['name'] }} | 
        {{ get_label('phone', 'Phone') }}: {{ $companyInfo['phone'] }} | {{ get_label('website', 'Website') }}: {{ $companyInfo['website'] }}
    </div>
</body>
</html>