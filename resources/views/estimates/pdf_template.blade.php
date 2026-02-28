<!DOCTYPE html>
<html dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>{{ get_label('estimate_pdf', 'Estimate PDF') }}</title>
    <style>
        @page {
            margin: 15mm;
            size: A4;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            font-size: 11pt;
            margin: 0;
            padding: 0;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        
        .company-name-ar {
            font-size: 16pt;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .company-name-en {
            font-size: 14pt;
            font-weight: bold;
            color: #34495e;
            margin-bottom: 10px;
        }
        
        .section {
            margin: 15px 0;
            padding: 10px;
            border: 1px solid #bdc3c7;
            border-radius: 5px;
            background-color: #f8f9fa;
        }
        
        .section-title {
            font-weight: bold;
            font-size: 13pt;
            color: #2c3e50;
            margin-bottom: 10px;
            text-align: center;
            text-decoration: underline;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px;
            border-bottom: 1px dashed #ecf0f1;
        }
        
        .info-label {
            font-weight: bold;
            width: 30%;
        }
        
        .info-value {
            width: 70%;
            text-align: right;
        }
        
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        .summary-table th,
        .summary-table td {
            border: 1px solid #bdc3c7;
            padding: 8px;
            text-align: center;
        }
        
        .summary-table th {
            background-color: #34495e;
            color: white;
            font-weight: bold;
        }
        
        .summary-table .label-cell {
            background-color: #ecf0f1;
            font-weight: bold;
        }
        
        .detailed-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        .detailed-table th,
        .detailed-table td {
            border: 1px solid #bdc3c7;
            padding: 6px;
            text-align: center;
        }
        
        .detailed-table th {
            background-color: #34495e;
            color: white;
            font-weight: bold;
        }
        
        .detailed-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .detailed-table .description-cell {
            text-align: left;
            padding-left: 10px;
        }
        
        .signatures {
            margin-top: 30px;
            display: flex;
            justify-content: space-around;
        }
        
        .signature-box {
            text-align: center;
            width: 30%;
            border-top: 1px solid #333;
            padding-top: 10px;
        }
        
        .signature-name {
            font-weight: bold;
            margin-top: 25px;
        }
        
        .signature-position {
            font-size: 9pt;
            color: #7f8c8d;
            margin-bottom: 5px;
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
            padding: 5px 0;
        }
        
        .watermark {
            position: fixed;
            opacity: 0.05;
            z-index: -1;
            font-size: 120pt;
            font-weight: bold;
            color: #34495e;
            transform: rotate(-45deg);
            white-space: nowrap;
            top: 40%;
            left: 10%;
        }
        
        .highlight-box {
            background-color: #e8f5e8;
            border: 2px solid #27ae60;
            border-radius: 5px;
            padding: 10px;
            margin: 10px 0;
            text-align: center;
            font-weight: bold;
            color: #27ae60;
        }
    </style>
</head>
<body>
    <div class="watermark">{{ get_label('estimate', 'Estimate') }}</div>
    
    <div class="header">
        <div class="company-name-ar">{{ $company_title_ar ?? get_label('modern_al_aqariah_company_ar', 'الشركة العقارية الحديثة المحدودة') }}</div>
        <div class="company-name-en">{{ $company_title_en ?? get_label('modern_al_aqariah_company_en', 'Modern Al-Aqariah Company Limited') }}</div>
    </div>
    
    <div class="section">
        <div class="section-title">{{ get_label('estimate_details', 'Estimate Details') }}</div>
        
        <div class="info-row">
            <div class="info-label">{{ get_label('estimate_number', 'Estimate Number') }}:</div>
            <div class="info-value">{{ $estimate_number ?? $estimate->id ?? 'N/A' }}</div>
        </div>
        
        <div class="info-row">
            <div class="info-label">{{ get_label('contract_number', 'Contract Number') }}:</div>
            <div class="info-value">{{ $contract_number ?? 'N/A' }}</div>
        </div>
        
        <div class="info-row">
            <div class="info-label">{{ get_label('item', 'Item') }}:</div>
            <div class="info-value">{{ $item_description ?? get_label('gypsum_work', '11 الجبسيات') }}</div>
        </div>
        
        <div class="info-row">
            <div class="info-label">{{ get_label('estimate_date', 'Estimate Date') }}:</div>
            <div class="info-value">{{ $estimate_date ?? $estimate->created_at->format('Y-m-d') ?? now()->format('Y-m-d') }}</div>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">{{ get_label('project_details', 'Project Details') }}</div>
        
        <div class="info-row">
            <div class="info-label">{{ get_label('project_name', 'Project Name') }}:</div>
            <div class="info-value">{{ $project_name ?? get_label('adn_bank_islamic_project', 'مشروع بنك عدن الأول الاسلامي - كريتر') }}</div>
        </div>
        
        <div class="info-row">
            <div class="info-label">{{ get_label('project_location', 'Project Location') }}:</div>
            <div class="info-value">{{ $project_location ?? get_label('yemen_aden_shirah', 'الجمهورية اليمنية - عدن - صيرة') }}</div>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">{{ get_label('contractor_details', 'Contractor Details') }}</div>
        
        <div class="info-row">
            <div class="info-label">{{ get_label('engineer_name', 'Engineer Name') }}:</div>
            <div class="info-value">{{ $engineer_name ?? get_label('not_specified', '-') }}</div>
        </div>
        
        <div class="info-row">
            <div class="info-label">{{ get_label('contractor_name', 'Contractor Name') }}:</div>
            <div class="info-value">{{ $contractor_name ?? get_label('mohammed_ali_abdo_hawban', 'محمد علي عبده وهبان') }}</div>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">{{ get_label('financial_summary', 'Financial Summary') }}</div>
        
        <table class="summary-table">
            <tr>
                <td class="label-cell">{{ get_label('contract_value_yer', 'Value of Contract (YER)') }}</td>
                <td>{{ format_currency($contract_value ?? 13600.00) }}</td>
            </tr>
            <tr>
                <td class="label-cell">{{ get_label('net_value', 'Net Value') }}</td>
                <td>{{ format_currency($net_value ?? 12240.00) }}</td>
            </tr>
            <tr>
                <td class="label-cell">{{ get_label('completion_percentage', 'Completion Percentage') }}</td>
                <td>{{ $completion_percentage ?? '90.00' }}%</td>
            </tr>
            <tr>
                <td class="label-cell">{{ get_label('total_to_date', 'Total To Date') }}</td>
                <td>{{ format_currency($total_to_date ?? 12240.00) }}</td>
            </tr>
        </table>
    </div>
    
    <div class="section">
        <div class="section-title">{{ get_label('contractual_data', 'Contractual Data') }}</div>
        
        <table class="detailed-table">
            <thead>
                <tr>
                    <th rowspan="2">{{ get_label('no', 'No') }}</th>
                    <th rowspan="2">{{ get_label('description', 'Description') }}</th>
                    <th rowspan="2">{{ get_label('unit', 'Unit') }}</th>
                    <th colspan="3">{{ get_label('total_work_to_date', 'Total Work Until Date') }}</th>
                    <th colspan="3">{{ get_label('previous_work_done', 'Previous Work Done') }}</th>
                    <th colspan="3">{{ get_label('current_estimate_work', 'Current Estimate Work Done') }}</th>
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
                        $totalProgress = 0;
                        
                        // Calculate totals from the items
                        foreach($items as $item) {
                            $totalQuantity += $item->pivot->qty ?? 1;
                            $totalAmount += $item->pivot->amount ?? 0;
                        }
                        
                        $avgProgress = count($items) > 0 ? ($totalAmount / ($estimate->total ?? 1)) * 100 : 0;
                    @endphp
                    
                    @foreach($items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="description-cell">{{ $item->name ?? $item['name'] ?? 'N/A' }}</td>
                        <td>{{ $item->unit->name ?? $item['unit'] ?? 'M²' }}</td>
                        
                        <!-- Total work until date -->
                        <td>{{ number_format($item->pivot->qty ?? 200.000, 3) }}</td>
                        <td>{{ format_currency($item->pivot->amount ?? 13600.00) }}</td>
                        <td>{{ number_format(($item->pivot->amount ?? 13600.00) / ($estimate->total ?? 1) * 100, 2) }}%</td>
                        
                        <!-- Previous work done -->
                        <td>{{ number_format(0, 3) }}</td>
                        <td>{{ format_currency(0) }}</td>
                        <td>{{ number_format(0, 2) }}%</td>
                        
                        <!-- Current estimate work done -->
                        <td>{{ number_format($item->pivot->qty ?? 180.000, 3) }}</td>
                        <td>{{ format_currency($item->pivot->amount ?? 12240.00) }}</td>
                        <td>{{ number_format((($item->pivot->amount ?? 12240.00) / ($estimate->total ?? 1)) * 100, 2) }}%</td>
                    </tr>
                    @endforeach
                    
                    <!-- Totals row -->
                    <tr>
                        <td colspan="3" style="text-align: right; font-weight: bold;">{{ get_label('total', 'Total') }}</td>
                        
                        <!-- Total work until date -->
                        <td><strong>{{ number_format($totalQuantity, 3) }}</strong></td>
                        <td><strong>{{ format_currency($totalAmount) }}</strong></td>
                        <td><strong>{{ number_format($avgProgress, 2) }}%</strong></td>
                        
                        <!-- Previous work done -->
                        <td><strong>{{ number_format(0, 3) }}</strong></td>
                        <td><strong>{{ format_currency(0) }}</strong></td>
                        <td><strong>{{ number_format(0, 2) }}%</strong></td>
                        
                        <!-- Current estimate work done -->
                        <td><strong>{{ number_format($totalQuantity, 3) }}</strong></td>
                        <td><strong>{{ format_currency($totalAmount) }}</strong></td>
                        <td><strong>{{ number_format($avgProgress, 2) }}%</strong></td>
                    </tr>
                    
                @else
                    <!-- Default sample data based on the example provided -->
                    <tr>
                        <td>1</td>
                        <td class="description-cell">{{ get_label('gypsum_board_work_incl_materials', 'عمل جبس بورد شامل المواد') }}</td>
                        <td>M²</td>
                        
                        <!-- Total work until date -->
                        <td>200.000</td>
                        <td>{{ format_currency(13600.00) }}</td>
                        <td>100.00%</td>
                        
                        <!-- Previous work done -->
                        <td>0.000</td>
                        <td>{{ format_currency(0.00) }}</td>
                        <td>0.00%</td>
                        
                        <!-- Current estimate work done -->
                        <td>180.000</td>
                        <td>{{ format_currency(12240.00) }}</td>
                        <td>90.00%</td>
                    </tr>
                    
                    <!-- Totals row -->
                    <tr>
                        <td colspan="3" style="text-align: right; font-weight: bold;">{{ get_label('total', 'Total') }}</td>
                        
                        <!-- Total work until date -->
                        <td><strong>200.000</strong></td>
                        <td><strong>{{ format_currency(13600.00) }}</strong></td>
                        <td><strong>100.00%</strong></td>
                        
                        <!-- Previous work done -->
                        <td><strong>0.000</strong></td>
                        <td><strong>{{ format_currency(0.00) }}</strong></td>
                        <td><strong>0.00%</strong></td>
                        
                        <!-- Current estimate work done -->
                        <td><strong>180.000</strong></td>
                        <td><strong>{{ format_currency(12240.00) }}</strong></td>
                        <td><strong>90.00%</strong></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    
    <div class="highlight-box">
        {{ get_label('net_value_calculation', 'Net Value Calculation') }}: {{ format_currency($net_value ?? 12240.00) }}
    </div>
    
    <div class="signatures">
        <div class="signature-box">
            <div class="signature-position">{{ get_label('engineering_side', 'Engineering Side') }}</div>
            <div class="signature-name">{{ $engineer_name ?? get_label('not_specified', '-') }}</div>
        </div>
        <div class="signature-box">
            <div class="signature-position">{{ get_label('contractor', 'Contractor') }}</div>
            <div class="signature-name">{{ $contractor_name ?? get_label('mohammed_ali_abdo_hawban', 'محمد علي عبده وهبان') }}</div>
        </div>
        <div class="signature-box">
            <div class="signature-position">{{ get_label('project_management', 'Project Management') }}</div>
            <div class="signature-name">{{ get_label('not_specified', '-') }}</div>
        </div>
    </div>
    
    <div class="footer">
        {{ $company_title_ar ?? get_label('modern_al_aqariah_company_ar', 'الشركة العقارية الحديثة المحدودة') }} | {{ $company_title_en ?? get_label('modern_al_aqariah_company_en', 'Modern Al-Aqariah Company Limited') }}
    </div>
</body>
</html>