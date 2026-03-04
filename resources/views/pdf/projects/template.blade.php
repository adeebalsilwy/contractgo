<!DOCTYPE html>
<html dir="{{ $isRtl ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <title>{{ get_label('project_pdf', 'Project PDF') }} - {{ $project->title ?? 'Document' }}</title>
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
        
        .project-info-grid {
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
            min-width: 120px;
        }
        
        .info-value {
            color: #2c3e50;
            font-weight: 500;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
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
        
        .team-members {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin: 15px 0;
        }
        
        .member-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        
        .member-name {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .member-role {
            color: #7f8c8d;
            font-size: 10pt;
        }
        
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #3498db;
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 20px;
            padding: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -25px;
            top: 20px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #3498db;
            border: 3px solid white;
            box-shadow: 0 0 0 2px #3498db;
        }
        
        .timeline-date {
            font-weight: bold;
            color: #3498db;
            margin-bottom: 5px;
        }
        
        .timeline-content {
            color: #2c3e50;
        }
        
        .progress-container {
            margin: 15px 0;
        }
        
        .progress-bar {
            height: 25px;
            background-color: #ecf0f1;
            border-radius: 12px;
            overflow: hidden;
            margin: 10px 0;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #2ecc71, #3498db);
            border-radius: 12px;
            text-align: center;
            line-height: 25px;
            color: white;
            font-weight: bold;
            font-size: 11pt;
            transition: width 0.3s ease;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 10pt;
            text-align: center;
        }
        
        .status-active {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .status-completed {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
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
    <div class="watermark">{{ get_label('project', 'Project') }}</div>
    
    <div class="header">
        @if($companyInfo['logo'])
            <img src="{{ public_path('storage/' . $companyInfo['logo']) }}" alt="Company Logo" class="company-logo">
        @endif
        <div class="company-name">{{ $companyInfo['name'] ?? 'Company Name' }}</div>
        <div>{{ $companyInfo['address'] ?? 'Company Address' }}</div>
    </div>
    
    <div class="document-title">{{ get_label('project_details', 'Project Details') }}</div>
    
    <div class="project-info-grid">
        <div class="info-card">
            <div class="info-card-title">{{ get_label('project_information', 'Project Information') }}</div>
            <div class="info-item">
                <span class="info-label">{{ get_label('project_name', 'Project Name') }}:</span>
                <span class="info-value">{{ $project->title ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ get_label('project_id', 'Project ID') }}:</span>
                <span class="info-value">{{ $project->id ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ get_label('status', 'Status') }}:</span>
                <span class="info-value">
                    <span class="status-badge status-{{ $project->status->title ?? 'active' }}">
                        {{ $project->status->title ?? 'Active' }}
                    </span>
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ get_label('priority', 'Priority') }}:</span>
                <span class="info-value">{{ $project->priority->title ?? 'Normal' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ get_label('created_date', 'Created Date') }}:</span>
                <span class="info-value">{{ $project->created_at ? format_date($project->created_at) : 'N/A' }}</span>
            </div>
        </div>
        
        <div class="info-card">
            <div class="info-card-title">{{ get_label('project_timeline', 'Project Timeline') }}</div>
            <div class="info-item">
                <span class="info-label">{{ get_label('start_date', 'Start Date') }}:</span>
                <span class="info-value">{{ $project->start_date ? format_date($project->start_date) : 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ get_label('end_date', 'End Date') }}:</span>
                <span class="info-value">{{ $project->end_date ? format_date($project->end_date) : 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ get_label('duration', 'Duration') }}:</span>
                <span class="info-value">
                    @if($project->start_date && $project->end_date)
                        {{ \Carbon\Carbon::parse($project->start_date)->diffInDays(\Carbon\Carbon::parse($project->end_date)) }} {{ get_label('days', 'Days') }}
                    @else
                        N/A
                    @endif
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ get_label('progress', 'Progress') }}:</span>
                <span class="info-value">{{ $project->progress ?? 0 }}%</span>
            </div>
            <div class="progress-container">
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ $project->progress ?? 0 }}%">
                        {{ $project->progress ?? 0 }}%
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $project->tasks_count ?? count($tasks ?? []) }}</div>
            <div class="stat-label">{{ get_label('total_tasks', 'Total Tasks') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $project->completed_tasks_count ?? count(array_filter($tasks ?? [], function($task) { return $task->status->title ?? '' === 'Completed'; })) }}</div>
            <div class="stat-label">{{ get_label('completed_tasks', 'Completed Tasks') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $project->team_members_count ?? count($users ?? []) }}</div>
            <div class="stat-label">{{ get_label('team_members', 'Team Members') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $project->client_count ?? count($clients ?? []) }}</div>
            <div class="stat-label">{{ get_label('clients', 'Clients') }}</div>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">{{ get_label('project_description', 'Project Description') }}</div>
        <div style="line-height: 1.8; text-align: justify;">
            {{ $project->description ?? get_label('no_description_provided', 'No description provided for this project.') }}
        </div>
    </div>
    
    @if(isset($users) && count($users) > 0)
    <div class="section">
        <div class="section-title">{{ get_label('project_team', 'Project Team') }}</div>
        <div class="team-members">
            @foreach($users as $user)
            <div class="member-card">
                <div class="member-name">{{ $user->first_name ?? '' }} {{ $user->last_name ?? '' }}</div>
                <div class="member-role">{{ $user->getRoleNames()->first() ?? 'Team Member' }}</div>
                <div style="font-size: 9pt; color: #7f8c8d; margin-top: 8px;">
                    {{ $user->email ?? 'N/A' }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    
    @if(isset($tasks) && count($tasks) > 0)
    <div class="section">
        <div class="section-title">{{ get_label('recent_tasks', 'Recent Tasks') }}</div>
        <div class="timeline">
            @foreach($tasks->take(5) as $task)
            <div class="timeline-item">
                <div class="timeline-date">
                    {{ $task->created_at ? format_date($task->created_at) : 'N/A' }}
                </div>
                <div class="timeline-content">
                    <strong>{{ $task->title ?? 'N/A' }}</strong><br>
                    {{ \Str::limit($task->description ?? '', 100) }}
                    <div style="margin-top: 8px;">
                        <span class="status-badge status-{{ $task->status->title ?? 'pending' }}">
                            {{ $task->status->title ?? 'Pending' }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    
    @if(isset($clients) && count($clients) > 0)
    <div class="section">
        <div class="section-title">{{ get_label('project_clients', 'Project Clients') }}</div>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 15px;">
            @foreach($clients as $client)
            <div style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                <div style="font-weight: bold; color: #2c3e50; margin-bottom: 8px;">
                    {{ $client->first_name ?? '' }} {{ $client->last_name ?? '' }}
                </div>
                <div style="color: #7f8c8d; font-size: 10pt; margin-bottom: 5px;">
                    {{ $client->email ?? 'N/A' }}
                </div>
                <div style="color: #7f8c8d; font-size: 10pt;">
                    {{ $client->phone ?? 'N/A' }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    
    <div class="footer">
        {{ $companyInfo['name'] ?? 'Company Name' }} | {{ $companyInfo['address'] ?? 'Company Address' }} | 
        {{ get_label('generated_on', 'Generated on') }}: {{ now()->format('Y-m-d H:i:s') }}
    </div>
</body>
</html>