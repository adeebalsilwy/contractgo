<!DOCTYPE html>
<html dir="{{ $isRtl ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ get_label('contract_pdf', 'Contract PDF') }} - {{ $contract->title ?? 'Document' }}</title>
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
            font-size: 12pt;
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
            max-width: 120px;
            height: auto;
            margin-bottom: 15px;
        }
        
        .company-name {
            font-size: 20pt;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 8px;
        }
        
        .company-name-ar {
            font-size: 18pt;
            font-weight: bold;
            color: #1a252f;
            margin-bottom: 5px;
        }
        
        .company-details {
            font-size: 11pt;
            color: #7f8c8d;
            margin-bottom: 3px;
        }
        
        .document-title {
            text-align: center;
            font-size: 22pt;
            font-weight: bold;
            color: #2c3e50;
            margin: 25px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
            background: linear-gradient(135deg, #2c3e50, #34495e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .document-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            flex-direction: {{ $isRtl ? 'row-reverse' : 'row' }};
        }
        
        .info-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            width: 48%;
        }
        
        .info-title {
            font-weight: bold;
            font-size: 13pt;
            color: #2c3e50;
            margin-bottom: 10px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 5px;
        }
        
        .info-item {
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
        }
        
        .info-label {
            font-weight: bold;
            color: #34495e;
            min-width: 120px;
        }
        
        .info-value {
            color: #2c3e50;
            font-weight: 500;
        }
        
        .parties-section {
            margin: 25px 0;
        }
        
        .party-card {
            background-color: #ffffff;
            border: 2px solid #bdc3c7;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .party-title {
            font-weight: bold;
            font-size: 16pt;
            color: #2c3e50;
            margin-bottom: 15px;
            text-align: center;
            background-color: #ecf0f1;
            padding: 10px;
            border-radius: 5px;
        }
        
        .party-details {
            margin-left: {{ $isRtl ? '0' : '25px' }};
            margin-right: {{ $isRtl ? '25px' : '0' }};
        }
        
        .article {
            margin: 25px 0;
            page-break-inside: avoid;
        }
        
        .article-title {
            font-weight: bold;
            font-size: 15pt;
            color: #2c3e50;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #3498db;
            color: white;
            border-radius: 5px;
            text-align: center;
        }
        
        .article-content {
            margin-left: {{ $isRtl ? '0' : '25px' }};
            margin-right: {{ $isRtl ? '25px' : '0' }};
            line-height: 1.8;
            text-align: justify;
        }
        
        .sub-article {
            margin: 15px 0;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #3498db;
        }
        
        .sub-article-title {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 8px;
        }
        
        .sub-article-content {
            margin-left: {{ $isRtl ? '0' : '20px' }};
            margin-right: {{ $isRtl ? '20px' : '0' }};
            text-align: justify;
        }
        
        .items-table-container {
            margin: 25px 0;
            overflow-x: auto;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .items-table th {
            background: linear-gradient(135deg, #34495e, #2c3e50);
            color: white;
            font-weight: bold;
            padding: 12px;
            text-align: center;
            border: 1px solid #2c3e50;
        }
        
        .items-table td {
            border: 1px solid #bdc3c7;
            padding: 10px;
            text-align: center;
        }
        
        .items-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .items-table tr:hover {
            background-color: #e3f2fd;
        }
        
        .total-section {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            color: #27ae60;
            margin: 30px 0;
            padding: 20px;
            border: 3px solid #27ae60;
            border-radius: 10px;
            background-color: #ecf0f1;
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.2);
        }
        
        .signatures-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        
        .signature-box {
            text-align: center;
            width: 45%;
            border-top: 2px solid #333;
            padding-top: 15px;
        }
        
        .signature-title {
            font-weight: bold;
            font-size: 12pt;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        
        .signature-name {
            font-weight: bold;
            margin-top: 40px;
            font-size: 14pt;
            color: #34495e;
        }
        
        .signature-line {
            margin: 15px 0;
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
            opacity: 0.07;
            z-index: -1;
            font-size: 120pt;
            font-weight: bold;
            color: #34495e;
            transform: rotate(-45deg);
            white-space: nowrap;
            top: 45%;
            left: 15%;
            pointer-events: none;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .highlight-box {
            background-color: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            text-align: center;
            font-weight: bold;
            color: #856404;
        }
        
        .currency-amount {
            font-weight: bold;
            color: #27ae60;
            font-size: 14pt;
        }
        
        /* RTL specific adjustments */
        @media print {
            body {
                direction: {{ $isRtl ? 'rtl' : 'ltr' }};
                text-align: {{ $isRtl ? 'right' : 'left' }};
            }
        }
        
        /* Mobile-specific styles */
        @media screen and (max-width: 768px) {
            body {
                font-size: 11pt;
                padding: 10px;
            }
            
            .header {
                padding: 10px;
                margin-bottom: 15px;
            }
            
            .document-title {
                font-size: 18pt;
                margin: 15px 0;
            }
            
            .info-box {
                width: 100%;
                margin-bottom: 15px;
            }
            
            .document-info {
                flex-direction: column;
            }
            
            .party-card {
                padding: 15px;
                margin-bottom: 15px;
            }
            
            .article {
                margin: 15px 0;
            }
            
            .article-title {
                font-size: 13pt;
                padding: 8px;
            }
            
            .items-table {
                font-size: 10pt;
            }
            
            .items-table th,
            .items-table td {
                padding: 6px 4px;
            }
            
            .signatures-section {
                flex-direction: column;
                gap: 30px;
            }
            
            .signature-box {
                width: 100%;
                margin-bottom: 20px;
            }
        }
        
        /* iOS Safari specific fixes */
        @supports (-webkit-touch-callout: none) {
            body {
                -webkit-text-size-adjust: 100%;
                text-size-adjust: 100%;
            }
            
            .document-title {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="watermark">{{ get_label('contract', 'Contract') }}</div>
    
    <div class="header">
        @if($companyInfo['logo'])
            <img src="{{ public_path('storage/' . $companyInfo['logo']) }}" alt="Company Logo" class="company-logo">
        @endif
        <div class="company-name-ar">{{ $companyInfo['name_ar'] ?? $companyInfo['name'] }}</div>
        <div class="company-name">{{ $companyInfo['name_en'] ?? $companyInfo['name'] }}</div>
        <div class="company-details">{{ $companyInfo['address'] }}</div>
        <div class="company-details">
            {{ get_label('phone', 'Phone') }}: {{ $companyInfo['phone'] }} | 
            {{ get_label('email', 'Email') }}: {{ $companyInfo['email'] }}
        </div>
        <div class="company-details">
            {{ get_label('website', 'Website') }}: {{ $companyInfo['website'] }} | 
            {{ get_label('vat_number', 'VAT') }}: {{ $companyInfo['vat_number'] }}
        </div>
    </div>
    
    <div class="document-title">{{ get_label('contract_for_work', 'Contract for Work') }}</div>
    
    <div class="document-info">
        <div class="info-box">
            <div class="info-title">{{ get_label('contract_information', 'Contract Information') }}</div>
            <div class="info-item">
                <span class="info-label">{{ get_label('contract_no', 'Contract No') }}:</span>
                <span class="info-value">{{ $contract->id ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ get_label('title', 'Title') }}:</span>
                <span class="info-value">{{ $contract->title ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ get_label('date', 'Date') }}:</span>
                <span class="info-value">{{ $contract->created_at ? format_date($contract->created_at) : now()->format('Y-m-d') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ get_label('status', 'Status') }}:</span>
                <span class="info-value">{{ $contract->status->title ?? 'N/A' }}</span>
            </div>
        </div>
        
        <div class="info-box">
            <div class="info-title">{{ get_label('contract_details', 'Contract Details') }}</div>
            <div class="info-item">
                <span class="info-label">{{ get_label('start_date', 'Start Date') }}:</span>
                <span class="info-value">{{ $contract->start_date ? format_date($contract->start_date) : 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ get_label('end_date', 'End Date') }}:</span>
                <span class="info-value">{{ $contract->end_date ? format_date($contract->end_date) : 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ get_label('project', 'Project') }}:</span>
                <span class="info-value">{{ $contract->project->title ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ get_label('client', 'Client') }}:</span>
                <span class="info-value">{{ $contract->client->first_name ?? '' }} {{ $contract->client->last_name ?? '' }}</span>
            </div>
        </div>
    </div>
    
    <div class="parties-section">
        <div class="party-card">
            <div class="party-title">{{ get_label('party_one_owner', 'Party One (Owner)') }}</div>
            <div class="party-details">
                <div class="info-item">
                    <span class="info-label">{{ get_label('represented_by', 'Represented by') }}:</span>
                    <span class="info-value">{{ $contract->owner_representative ?? ($contract->createdBy ? $contract->createdBy->first_name . ' ' . $contract->createdBy->last_name : 'N/A') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ get_label('id_card', 'ID Card') }}:</span>
                    <span class="info-value">{{ $contract->owner_id_card ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ get_label('phone', 'Phone') }}:</span>
                    <span class="info-value">{{ $contract->owner_phone ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ get_label('address', 'Address') }}:</span>
                    <span class="info-value">{{ $contract->owner_address ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
        
        <div class="party-card">
            <div class="party-title">{{ get_label('party_two_contractor', 'Party Two (Contractor)') }}</div>
            <div class="party-details">
                <div class="info-item">
                    <span class="info-label">{{ get_label('represented_by', 'Represented by') }}:</span>
                    <span class="info-value">{{ $contract->contractor_representative ?? ($contract->siteSupervisor ? $contract->siteSupervisor->first_name . ' ' . $contract->siteSupervisor->last_name : 'N/A') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ get_label('id_card', 'ID Card') }}:</span>
                    <span class="info-value">{{ $contract->contractor_id_card ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ get_label('phone', 'Phone') }}:</span>
                    <span class="info-value">{{ $contract->contractor_phone ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ get_label('address', 'Address') }}:</span>
                    <span class="info-value">{{ $contract->contractor_address ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="article">
        <div class="article-title">{{ get_label('article', 'Article') }} 1 - {{ get_label('contract_subject', 'Contract Subject') }}</div>
        <div class="article-content">
            {{ get_label('party_two_obligated_to_perform_work_according_to_schedule', 'Party two is obligated to perform work according to the approved schedule and drawings in the') }}
            {{ $contract->project->title ?? get_label('project', 'Project') }}
            {{ get_label('location_details', 'Location details') }}: {{ $contract->location ?? $contract->project->address ?? 'N/A' }}
        </div>
    </div>
    
    <div class="article">
        <div class="article-title">{{ get_label('article', 'Article') }} 2 - {{ get_label('party_two_obligations', 'Party Two Obligations') }}</div>
        <div class="article-content">
            <div class="sub-article">
                <div class="sub-article-title">{{ get_label('sub_article', 'Sub Article') }} 2.1</div>
                <div class="sub-article-content">{{ get_label('provide_skilled_labor', 'Provide skilled labor force capable of executing the work') }}</div>
            </div>
            <div class="sub-article">
                <div class="sub-article-title">{{ get_label('sub_article', 'Sub Article') }} 2.2</div>
                <div class="sub-article-content">{{ get_label('responsible_for_materials_and_tools', 'Responsible for all materials received from party one, and must return unused materials, also provide all required machinery and tools') }}</div>
            </div>
            <div class="sub-article">
                <div class="sub-article-title">{{ get_label('sub_article', 'Sub Article') }} 2.3</div>
                <div class="sub-article-content">{{ get_label('bear_costs_of_wasted_materials', 'Bear costs of wasted, lost, or mishandled materials') }}</div>
            </div>
            <div class="sub-article">
                <div class="sub-article-title">{{ get_label('sub_article', 'Sub Article') }} 2.4</div>
                <div class="sub-article-content">{{ get_label('execute_work_continuously', 'Execute work continuously without interruption or delays, and not cause any delays') }}</div>
            </div>
            <div class="sub-article">
                <div class="sub-article-title">{{ get_label('sub_article', 'Sub Article') }} 2.5</div>
                <div class="sub-article-content">{{ get_label('report_issues_before_execution', 'Report any observations before starting execution, notify party one in time about any errors or mistakes discovered, and not make any changes without written authorization from party one') }}</div>
            </div>
        </div>
    </div>
    
    <div class="article">
        <div class="article-title">{{ get_label('article', 'Article') }} 3 - {{ get_label('value_and_payment_method', 'Value and Payment Method') }}</div>
        <div class="article-content">
            {{ get_label('total_contract_value', 'Total contract value') }}: 
            <span class="currency-amount">{{ format_currency($contract->value ?? 0) }}</span>
            {{ $general_settings['currency_code'] ?? 'YER' }}.
            {{ get_label('payment_terms', 'Payment terms') }}: {{ $contract->payment_percentage ?? '35%' }} {{ get_label('as_advance_payment', 'as advance payment') }},
            {{ $contract->quality_guarantee_percentage ?? '15%' }} {{ get_label('as_quality_guarantee', 'as quality guarantee') }} {{ get_label('to_be_withheld_for_one_month_after_final_delivery', 'to be withheld for one month after final delivery') }}.
        </div>
    </div>
    
    @if(isset($items) && count($items) > 0)
    <div class="items-table-container">
        <div class="article-title">{{ get_label('items_table', 'Items Table') }}</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;">{{ get_label('no', 'No') }}</th>
                    <th style="width: 35%;">{{ get_label('description', 'Description') }}</th>
                    <th style="width: 10%;">{{ get_label('unit', 'Unit') }}</th>
                    <th style="width: 15%;">{{ get_label('quantity', 'Quantity') }}</th>
                    <th style="width: 15%;">{{ get_label('unit_price', 'Unit Price') }}</th>
                    <th style="width: 20%;">{{ get_label('total', 'Total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td style="text-align: {{ $isRtl ? 'right' : 'left' }};">{{ $item->name ?? $item['name'] ?? 'N/A' }}</td>
                    <td>{{ $item->unit->name ?? $item['unit'] ?? 'N/A' }}</td>
                    <td>{{ number_format($item->pivot->qty ?? $item['quantity'] ?? 0, 2) }}</td>
                    <td>{{ format_currency($item->pivot->unit_price ?? $item['unit_price'] ?? 0) }}</td>
                    <td>{{ format_currency(($item->pivot->qty ?? $item['quantity'] ?? 0) * ($item->pivot->unit_price ?? $item['unit_price'] ?? 0)) }}</td>
                </tr>
                @endforeach
                <tr style="background-color: #e8f5e8; font-weight: bold;">
                    <td colspan="5" style="text-align: {{ $isRtl ? 'left' : 'right' }}; font-weight: bold;">{{ get_label('total_amount', 'Total Amount') }}:</td>
                    <td style="font-weight: bold; color: #27ae60; background-color: #d4edda;">
                        {{ format_currency(collect($items)->sum(function($item) {
                            return ($item->pivot->qty ?? $item['quantity'] ?? 0) * ($item->pivot->unit_price ?? $item['unit_price'] ?? 0);
                        })) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif
    
    <div class="total-section">
        {{ get_label('total_contract_value', 'Total Contract Value') }}: {{ format_currency($contract->value ?? 0) }}
    </div>
    
    <div class="highlight-box">
        {{ get_label('contract_provisions_binding', 'Contract provisions are binding for both parties, and contract modification is only allowed with agreement of both parties and written approval') }}
    </div>
    
    <div class="signatures-section">
        <div class="signature-box">
            <div class="signature-title">{{ get_label('party_one_owner', 'Party One (Owner)') }}</div>
            <div class="signature-name">{{ $contract->owner_representative ?? ($contract->createdBy ? $contract->createdBy->first_name . ' ' . $contract->createdBy->last_name : 'N/A') }}</div>
            <div class="signature-line">{{ get_label('signature', 'Signature') }}: ______________________</div>
            <div class="signature-line">{{ get_label('stamp_fingerprint', 'Stamp/Fingerprint') }}: ______________________</div>
            <div class="signature-line">{{ get_label('date', 'Date') }}: ____/____/______</div>
        </div>
        <div class="signature-box">
            <div class="signature-title">{{ get_label('party_two_contractor', 'Party Two (Contractor)') }}</div>
            <div class="signature-name">{{ $contract->contractor_representative ?? ($contract->siteSupervisor ? $contract->siteSupervisor->first_name . ' ' . $contract->siteSupervisor->last_name : 'N/A') }}</div>
            <div class="signature-line">{{ get_label('signature', 'Signature') }}: ______________________</div>
            <div class="signature-line">{{ get_label('stamp_fingerprint', 'Stamp/Fingerprint') }}: ______________________</div>
            <div class="signature-line">{{ get_label('date', 'Date') }}: ____/____/______</div>
        </div>
    </div>
    
    <div class="footer">
        {{ $companyInfo['name_ar'] ?? $companyInfo['name'] }} | {{ $companyInfo['address'] }} | 
        {{ get_label('phone', 'Phone') }}: {{ $companyInfo['phone'] }} | {{ get_label('website', 'Website') }}: {{ $companyInfo['website'] }}
    </div>
</body>
</html>