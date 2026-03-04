<!DOCTYPE html>
<html dir="{{ $isRtl ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Report' }}</title>
    <style>
        @page {
            margin: 25mm;
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
        
        .company-name {
            font-size: 18pt;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 8px;
        }
        
        .document-title {
            text-align: center;
            font-size: 20pt;
            font-weight: bold;
            color: #2c3e50;
            margin: 20px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .report-info {
            text-align: center;
            margin-bottom: 25px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        
        .report-date {
            color: #6c757d;
            font-size: 11pt;
        }
        
        .section {
            margin: 25px 0;
            padding: 20px;
            border: 1px solid #bdc3c7;
            border-radius: 10px;
            background-color: #f8f9fa;
        }
        
        .section-title {
            font-weight: bold;
            font-size: 14pt;
            color: #2c3e50;
            margin-bottom: 15px;
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .data-table th {
            background: linear-gradient(135deg, #34495e, #2c3e50);
            color: white;
            font-weight: bold;
            padding: 12px;
            text-align: center;
            border: 1px solid #2c3e50;
        }
        
        .data-table td {
            border: 1px solid #bdc3c7;
            padding: 10px;
            text-align: center;
        }
        
        .data-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .data-table tr:hover {
            background-color: #e3f2fd;
        }
        
        .summary-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 25px 0;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #3498db, #2c3e50);
            color: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }
        
        .stat-number {
            font-size: 24pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 11pt;
            font-weight: 500;
        }
        
        .chart-placeholder {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
            border: 2px dashed #bdc3c7;
            color: #7f8c8d;
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
            font-size: 120pt;
            font-weight: bold;
            color: #34495e;
            transform: rotate(-45deg);
            white-space: nowrap;
            top: 40%;
            left: 10%;
            pointer-events: none;
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
    <div class="watermark">{{ get_label('report', 'Report') }}</div>
    
    <div class="header">
        @if($companyInfo['logo'])
            <img src="{{ public_path('storage/' . $companyInfo['logo']) }}" alt="Company Logo" class="company-logo">
        @endif
        <div class="company-name">{{ $companyInfo['name'] ?? 'Company Name' }}</div>
        <div>{{ $companyInfo['address'] ?? 'Company Address' }}</div>
    </div>
    
    <div class="document-title">{{ $title ?? 'Custom Report' }}</div>
    
    <div class="report-info">
        <div class="report-date">
            {{ get_label('generated_on', 'Generated on') }}: {{ $generated_at ? format_date($generated_at) : now()->format('Y-m-d H:i:s') }}
        </div>
        @if(isset($report_period))
        <div class="report-date">
            {{ get_label('report_period', 'Report Period') }}: {{ $report_period }}
        </div>
        @endif
    </div>
    
    @if(isset($summary_stats) && is_array($summary_stats))
    <div class="summary-stats">
        @foreach($summary_stats as $stat)
        <div class="stat-card">
            <div class="stat-number">{{ $stat['value'] ?? 0 }}</div>
            <div class="stat-label">{{ $stat['label'] ?? 'Stat' }}</div>
        </div>
        @endforeach
    </div>
    @endif
    
    @if(isset($report_data) && is_array($report_data))
    <div class="section">
        <div class="section-title">{{ get_label('report_data', 'Report Data') }}</div>
        
        @if(isset($report_data['table_data']) && is_array($report_data['table_data']))
        <table class="data-table">
            <thead>
                <tr>
                    @foreach($report_data['table_headers'] ?? [] as $header)
                    <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($report_data['table_data'] as $row)
                <tr>
                    @foreach($row as $cell)
                    <td>{{ $cell }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        
        @if(isset($report_data['chart_data']))
        <div class="chart-placeholder">
            <div style="font-size: 16pt; margin-bottom: 10px;">📊</div>
            <div>{{ get_label('chart_will_be_displayed_here', 'Chart will be displayed here') }}</div>
            <div style="font-size: 10pt; margin-top: 10px;">{{ get_label('charts_not_available_in_pdf', 'Charts are not available in PDF format') }}</div>
        </div>
        @endif
        
        @if(isset($report_data['text_content']))
        <div style="line-height: 1.8; text-align: justify; margin-top: 20px;">
            {{ $report_data['text_content'] }}
        </div>
        @endif
    </div>
    @endif
    
    @if(isset($additional_sections) && is_array($additional_sections))
        @foreach($additional_sections as $section)
        <div class="section">
            <div class="section-title">{{ $section['title'] ?? 'Section' }}</div>
            <div style="line-height: 1.8; text-align: justify;">
                {{ $section['content'] ?? '' }}
            </div>
        </div>
        @endforeach
    @endif
    
    <div class="section">
        <div class="section-title">{{ get_label('report_summary', 'Report Summary') }}</div>
        <div style="line-height: 1.8; text-align: justify;">
            {{ $summary ?? get_label('report_generated_successfully', 'This report has been generated successfully. The data presented reflects the current state of the system as of the generation date.') }}
        </div>
    </div>
    
    <div class="footer">
        {{ $companyInfo['name'] ?? 'Company Name' }} | {{ $companyInfo['address'] ?? 'Company Address' }} | 
        {{ get_label('generated_on', 'Generated on') }}: {{ now()->format('Y-m-d H:i:s') }}
    </div>
</body>
</html>