<!DOCTYPE html>
<html dir="{{ $isRtl ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <title>{{ get_label('client_pdf', 'Client PDF') }} - {{ $client->first_name ?? '' }} {{ $client->last_name ?? '' }}</title>
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
        
        .client-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .info-card {
            background: white;
            border: 2px solid #bdc3c7;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .info-card-title {
            font-weight: bold;
            font-size: 14pt;
            color: #2c3e50;
            margin-bottom: 15px;
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
        }
        
        .info-item {
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
        }
        
        .info-label {
            font-weight: bold;
            color: #34495e;
            min-width: 140px;
        }
        
        .info-value {
            color: #2c3e50;
            font-weight: 500;
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
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
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
        
        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
            margin: 15px 0;
        }
        
        .project-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        
        .project-title {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 12pt;
        }
        
        .project-status {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 9pt;
            font-weight: bold;
        }
        
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-completed {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .contracts-list {
            margin: 15px 0;
        }
        
        .contract-item {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        
        .contract-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid #eee;
        }
        
        .contract-title {
            font-weight: bold;
            color: #2c3e50;
            font-size: 12pt;
        }
        
        .contract-value {
            font-weight: bold;
            color: #27ae60;
            font-size: 12pt;
        }
        
        .contract-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            font-size: 10pt;
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
    <div class="watermark">{{ get_label('client', 'Client') }}</div>
    
    <div class="header">
        @if($companyInfo['logo'])
            <img src="{{ public_path('storage/' . $companyInfo['logo']) }}" alt="Company Logo" class="company-logo">
        @endif
        <div class="company-name">{{ $companyInfo['name'] ?? 'Company Name' }}</div>
        <div>{{ $companyInfo['address'] ?? 'Company Address' }}</div>
    </div>
    
    <div class="document-title">{{ get_label('client_profile', 'Client Profile') }}</div>
    
    <div class="client-info-grid">
        <div class="info-card">
            <div class="info-card-title">{{ get_label('personal_information', 'Personal Information') }}</div>
            <div class="info-item">
                <span class="info-label">{{ get_label('client_name', 'Client Name') }}:</span>
                <span class="info-value">{{ $client->first_name ?? '' }} {{ $client->last_name ?? '' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ get_label('client_id', 'Client ID') }}:</span>
                <span class="info-value">{{ $client->id ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ get_label('email', 'Email') }}:</span>
                <span class="info-value">{{ $client->email ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ get_label('phone', 'Phone') }}:</span>
                <span class="info-value">{{ $client->phone ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ get_label('address', 'Address') }}:</span>
                <span class="info-value">{{ $client->address ?? 'N/A' }}</span>
            </div>
        </div>
        
        <div class="info-card">
            <div class="info-card-title">{{ get_label('business_information', 'Business Information') }}</div>
            <div class="info-item">
                <span class="info-label">{{ get_label('company', 'Company') }}:</span>
                <span class="info-value">{{ $client->company ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ get_label('profession', 'Profession') }}:</span>
                <span class="info-value">{{ $client->profession->title ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ get_label('vat_number', 'VAT Number') }}:</span>
                <span class="info-value">{{ $client->vat_number ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ get_label('status', 'Status') }}:</span>
                <span class="info-value">{{ $client->status ? get_label('active', 'Active') : get_label('inactive', 'Inactive') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ get_label('created_date', 'Created Date') }}:</span>
                <span class="info-value">{{ $client->created_at ? format_date($client->created_at) : 'N/A' }}</span>
            </div>
        </div>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $client->projects_count ?? count($projects ?? []) }}</div>
            <div class="stat-label">{{ get_label('total_projects', 'Total Projects') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $client->contracts_count ?? count($contracts ?? []) }}</div>
            <div class="stat-label">{{ get_label('total_contracts', 'Total Contracts') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ format_currency($client->total_contract_value ?? 0) }}</div>
            <div class="stat-label">{{ get_label('total_value', 'Total Value') }}</div>
        </div>
    </div>
    
    @if(isset($projects) && count($projects) > 0)
    <div class="section">
        <div class="section-title">{{ get_label('client_projects', 'Client Projects') }}</div>
        <div class="projects-grid">
            @foreach($projects as $project)
            <div class="project-card">
                <div class="project-title">{{ $project->title ?? 'N/A' }}</div>
                <div style="margin: 10px 0;">
                    <span class="project-status status-{{ $project->status->title ?? 'active' }}">
                        {{ $project->status->title ?? 'Active' }}
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ get_label('start_date', 'Start Date') }}:</span>
                    <span class="info-value">{{ $project->start_date ? format_date($project->start_date) : 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ get_label('end_date', 'End Date') }}:</span>
                    <span class="info-value">{{ $project->end_date ? format_date($project->end_date) : 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ get_label('progress', 'Progress') }}:</span>
                    <span class="info-value">{{ $project->progress ?? 0 }}%</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    
    @if(isset($contracts) && count($contracts) > 0)
    <div class="section">
        <div class="section-title">{{ get_label('client_contracts', 'Client Contracts') }}</div>
        <div class="contracts-list">
            @foreach($contracts as $contract)
            <div class="contract-item">
                <div class="contract-header">
                    <div class="contract-title">{{ $contract->title ?? 'N/A' }}</div>
                    <div class="contract-value">{{ format_currency($contract->value ?? 0) }}</div>
                </div>
                <div class="contract-details">
                    <div>
                        <strong>{{ get_label('contract_id', 'Contract ID') }}:</strong> {{ $contract->id ?? 'N/A' }}
                    </div>
                    <div>
                        <strong>{{ get_label('status', 'Status') }}:</strong> {{ $contract->status->title ?? 'N/A' }}
                    </div>
                    <div>
                        <strong>{{ get_label('start_date', 'Start Date') }}:</strong> {{ $contract->start_date ? format_date($contract->start_date) : 'N/A' }}
                    </div>
                    <div>
                        <strong>{{ get_label('end_date', 'End Date') }}:</strong> {{ $contract->end_date ? format_date($contract->end_date) : 'N/A' }}
                    </div>
                    <div>
                        <strong>{{ get_label('project', 'Project') }}:</strong> {{ $contract->project->title ?? 'N/A' }}
                    </div>
                    <div>
                        <strong>{{ get_label('type', 'Type') }}:</strong> {{ $contract->contractType->title ?? 'N/A' }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    
    <div class="section">
        <div class="section-title">{{ get_label('client_summary', 'Client Summary') }}</div>
        <div style="line-height: 1.8; text-align: justify;">
            {{ get_label('client_profile_summary', 'This document provides a comprehensive overview of the client profile including personal information, business details, projects, and contracts associated with this client. The information is current as of the generation date.') }}
        </div>
    </div>
    
    <div class="footer">
        {{ $companyInfo['name'] ?? 'Company Name' }} | {{ $companyInfo['address'] ?? 'Company Address' }} | 
        {{ get_label('generated_on', 'Generated on') }}: {{ now()->format('Y-m-d H:i:s') }}
    </div>
</body>
</html>