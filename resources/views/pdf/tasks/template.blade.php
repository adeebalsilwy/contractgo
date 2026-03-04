<!DOCTYPE html>
<html dir="{{ $isRtl ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <title>{{ get_label('task_pdf', 'Task PDF') }} - {{ $task->title ?? 'Document' }}</title>
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
        
        .task-info-grid {
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
        
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 10pt;
            text-align: center;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .status-in-progress {
            background-color: #cce5ff;
            color: #004085;
            border: 1px solid #b8daff;
        }
        
        .status-completed {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .priority-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 9pt;
            text-align: center;
        }
        
        .priority-high {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .priority-medium {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .priority-low {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
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
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
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
        
        .comments-section {
            margin-top: 20px;
        }
        
        .comment {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        
        .comment-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid #eee;
        }
        
        .comment-author {
            font-weight: bold;
            color: #2c3e50;
        }
        
        .comment-date {
            color: #7f8c8d;
            font-size: 10pt;
        }
        
        .comment-content {
            line-height: 1.6;
            color: #333;
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
            height: 20px;
            background-color: #ecf0f1;
            border-radius: 10px;
            overflow: hidden;
            margin: 10px 0;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #2ecc71, #3498db);
            border-radius: 10px;
            text-align: center;
            line-height: 20px;
            color: white;
            font-weight: bold;
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
    <div class="watermark">{{ get_label('task', 'Task') }}</div>
    
    <div class="header">
        @if($companyInfo['logo'])
            <img src="{{ public_path('storage/' . $companyInfo['logo']) }}" alt="Company Logo" class="company-logo">
        @endif
        <div class="company-name">{{ $companyInfo['name'] ?? 'Company Name' }}</div>
        <div>{{ $companyInfo['address'] ?? 'Company Address' }}</div>
    </div>
    
    <div class="document-title">{{ get_label('task_details', 'Task Details') }}</div>
    
    <div class="task-info-grid">
        <div class="info-card">
            <div class="info-card-title">{{ get_label('task_information', 'Task Information') }}</div>
            <div class="info-item">
                <span class="info-label">{{ get_label('task_title', 'Task Title') }}:</span>
                <span class="info-value">{{ $task->title ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ get_label('task_id', 'Task ID') }}:</span>
                <span class="info-value">{{ $task->id ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ get_label('status', 'Status') }}:</span>
                <span class="info-value">
                    <span class="status-badge status-{{ str_slug($task->status->title ?? 'pending') }}">
                        {{ $task->status->title ?? 'Pending' }}
                    </span>
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ get_label('priority', 'Priority') }}:</span>
                <span class="info-value">
                    <span class="priority-badge priority-{{ str_slug($task->priority->title ?? 'medium') }}">
                        {{ $task->priority->title ?? 'Medium' }}
                    </span>
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ get_label('created_date', 'Created Date') }}:</span>
                <span class="info-value">{{ $task->created_at ? format_date($task->created_at) : 'N/A' }}</span>
            </div>
        </div>
        
        <div class="info-card">
            <div class="info-card-title">{{ get_label('task_timeline', 'Task Timeline') }}</div>
            <div class="info-item">
                <span class="info-label">{{ get_label('start_date', 'Start Date') }}:</span>
                <span class="info-value">{{ $task->start_date ? format_date($task->start_date) : 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ get_label('due_date', 'Due Date') }}:</span>
                <span class="info-value">{{ $task->due_date ? format_date($task->due_date) : 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ get_label('completed_date', 'Completed Date') }}:</span>
                <span class="info-value">{{ $task->completed_at ? format_date($task->completed_at) : 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ get_label('progress', 'Progress') }}:</span>
                <span class="info-value">{{ $task->progress ?? 0 }}%</span>
            </div>
            <div class="progress-container">
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ $task->progress ?? 0 }}%">
                        {{ $task->progress ?? 0 }}%
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if($project)
    <div class="section">
        <div class="info-card-title">{{ get_label('project_information', 'Project Information') }}</div>
        <div class="info-item">
            <span class="info-label">{{ get_label('project_name', 'Project Name') }}:</span>
            <span class="info-value">{{ $project->title ?? 'N/A' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">{{ get_label('project_status', 'Project Status') }}:</span>
            <span class="info-value">{{ $project->status->title ?? 'N/A' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">{{ get_label('project_priority', 'Project Priority') }}:</span>
            <span class="info-value">{{ $project->priority->title ?? 'N/A' }}</span>
        </div>
    </div>
    @endif
    
    <div class="section">
        <div class="section-title">{{ get_label('task_description', 'Task Description') }}</div>
        <div style="line-height: 1.8; text-align: justify;">
            {{ $task->description ?? get_label('no_description_provided', 'No description provided for this task.') }}
        </div>
    </div>
    
    @if(isset($users) && count($users) > 0)
    <div class="section">
        <div class="section-title">{{ get_label('assigned_team', 'Assigned Team') }}</div>
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
    
    @if(isset($comments) && count($comments) > 0)
    <div class="section">
        <div class="section-title">{{ get_label('task_comments', 'Task Comments') }}</div>
        <div class="comments-section">
            @foreach($comments as $comment)
            <div class="comment">
                <div class="comment-header">
                    <div class="comment-author">
                        {{ $comment->user->first_name ?? '' }} {{ $comment->user->last_name ?? '' }}
                    </div>
                    <div class="comment-date">
                        {{ $comment->created_at ? format_date($comment->created_at) : 'N/A' }}
                    </div>
                </div>
                <div class="comment-content">
                    {{ $comment->comment ?? 'N/A' }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    
    <div class="section">
        <div class="section-title">{{ get_label('task_activity_timeline', 'Task Activity Timeline') }}</div>
        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-date">{{ $task->created_at ? format_date($task->created_at) : 'N/A' }}</div>
                <div class="timeline-content">
                    <strong>{{ get_label('task_created', 'Task Created') }}</strong><br>
                    {{ get_label('task_was_created_by', 'Task was created by') }} 
                    {{ $task->createdBy->first_name ?? '' }} {{ $task->createdBy->last_name ?? '' }}
                </div>
            </div>
            
            @if($task->start_date)
            <div class="timeline-item">
                <div class="timeline-date">{{ format_date($task->start_date) }}</div>
                <div class="timeline-content">
                    <strong>{{ get_label('task_started', 'Task Started') }}</strong><br>
                    {{ get_label('work_on_task_began', 'Work on task began') }}
                </div>
            </div>
            @endif
            
            @if($task->completed_at)
            <div class="timeline-item">
                <div class="timeline-date">{{ format_date($task->completed_at) }}</div>
                <div class="timeline-content">
                    <strong>{{ get_label('task_completed', 'Task Completed') }}</strong><br>
                    {{ get_label('task_was_marked_as_completed', 'Task was marked as completed') }}
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <div class="footer">
        {{ $companyInfo['name'] ?? 'Company Name' }} | {{ $companyInfo['address'] ?? 'Company Address' }} | 
        {{ get_label('generated_on', 'Generated on') }}: {{ now()->format('Y-m-d H:i:s') }}
    </div>
</body>
</html>