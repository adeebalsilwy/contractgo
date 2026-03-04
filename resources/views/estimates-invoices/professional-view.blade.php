@php
use App\Models\Tax;

function getExtractStatusColor($status) {
    $statusColors = [
        'draft' => 'secondary',
        'submitted' => 'warning',
        'under_review' => 'info',
        'approved' => 'success',
        'rejected' => 'danger',
        'archived' => 'secondary'
    ];
    return $statusColors[$status] ?? 'secondary';
}

function getExtractStatusIcon($status) {
    $statusIcons = [
        'draft' => 'bx-edit',
        'submitted' => 'bx-send',
        'under_review' => 'bx-group',
        'approved' => 'bx-badge-check',
        'rejected' => 'bx-x-circle',
        'archived' => 'bx-archive'
    ];
    return $statusIcons[$status] ?? 'bx-help-circle';
}

function getExtractStatusText($status) {
    return ucfirst(str_replace('_', ' ', $status));
}

function getExtractProgressColor($percentage) {
    if ($percentage >= 80) return 'success';
    if ($percentage >= 50) return 'warning';
    if ($percentage >= 20) return 'info';
    return 'secondary';
}
@endphp

@extends('layout')
@section('title')
<?= $estimate_invoice->type == 'estimate' ? get_label('view_extract', 'View Extract') : get_label('view_invoice', 'View Invoice') ?>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-2 mt-4" id="section-not-to-print">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{url('home')}}"><?= get_label('home', 'Home') ?></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ url('estimates-invoices') }}"><?= get_label('estimates_invoices', 'Estimates/Invoices') ?></a>
                    </li>
                    <li class="breadcrumb-item active">
                        <?= $estimate_invoice->type == 'estimate' ? get_label('view_extract', 'View Extract') : get_label('view_invoice', 'View Invoice') ?>
                    </li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ url('estimates-invoices/mind-map/' . $estimate_invoice->id) }}" class="ms-2">
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('view_mind_map', 'View Mind Map') ?>">
                    <i class="bx bx-sitemap"></i>
                </button>
            </a>
            <a href="{{ url('estimates-invoices/pdf/' . $estimate_invoice->id) }}" class="ms-2">
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="{{ get_label('view_pdf', 'View PDF') }}">
                    <i class="bx bx-file"></i>
                </button>
            </a>
            <a href="{{url('estimates-invoices')}}"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('estimates_invoices', 'Estimates/Invoices') ?>"><i class="bx bx-list-ul"></i></button></a>
        </div>
    </div>

    <!-- Professional Extract Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start flex-wrap">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-3">
                                <h2 class="fw-bold mb-0 me-3">
                                    {{ $estimate_invoice->type == 'estimate' ? get_label('extract', 'Extract') : get_label('invoice', 'Invoice') }} #{{ $estimate_invoice->name }}
                                </h2>
                                <div class="badge bg-{{ getExtractStatusColor($estimate_invoice->status) }} px-3 py-2 rounded-pill">
                                    <i class="bx {{ getExtractStatusIcon($estimate_invoice->status) }} me-1"></i>
                                    <span class="fw-bold">{{ getExtractStatusText($estimate_invoice->status) }}</span>
                                </div>
                            </div>
                            
                            <!-- Key Extract Information -->
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-label-primary me-2">
                                            <i class="bx bx-calendar"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted"><?= get_label('from_date', 'From Date') ?></small>
                                            <div class="fw-medium">{{ format_date($estimate_invoice->from_date) }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-label-danger me-2">
                                            <i class="bx bx-calendar-x"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted"><?= get_label('to_date', 'To Date') ?></small>
                                            <div class="fw-medium">{{ format_date($estimate_invoice->to_date) }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-label-success me-2">
                                            <i class="bx bx-money"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted"><?= get_label('total_amount', 'Total Amount') ?></small>
                                            <div class="fw-medium text-success">{{ format_currency($estimate_invoice->final_total) }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-label-info me-2">
                                            <i class="bx bx-hash"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted"><?= get_label('items_count', 'Items Count') ?></small>
                                            <div class="fw-medium">{{ count($estimate_invoice->items) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Progress Visualization -->
                        <div class="mt-3 mt-md-0" style="min-width: 200px;">
                            <div class="text-center mb-2">
                                <span class="fw-bold text-primary">{{ $estimate_invoice->type == 'estimate' ? 'مستخلص' : 'فاتورة' }}</span>
                            </div>
                            <div class="text-center">
                                <span class="badge bg-{{ getExtractStatusColor($estimate_invoice->status) }} px-3 py-2">
                                    {{ $estimate_invoice->name }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Professional Workflow Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="nav-align-top">
                        <ul class="nav nav-tabs nav-tabs-widget mb-0" role="tablist">
                            <!-- Extract Overview Tab -->
                            <li class="nav-item">
                                <button type="button" 
                                        class="nav-link active" 
                                        role="tab" 
                                        data-bs-toggle="tab" 
                                        data-bs-target="#extract-overview" 
                                        aria-controls="extract-overview">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-label-primary me-2">
                                            <i class="bx bx-file"></i>
                                        </div>
                                        <div class="text-start">
                                            <div class="fw-bold"><?= get_label('extract_details', 'Extract Details') ?></div>
                                            <small class="text-muted"><?= get_label('overview', 'Overview') ?></small>
                                        </div>
                                    </div>
                                </button>
                            </li>
                            
                            <!-- Workflow Status Tab -->
                            <li class="nav-item">
                                <button type="button" 
                                        class="nav-link" 
                                        role="tab" 
                                        data-bs-toggle="tab" 
                                        data-bs-target="#workflow-status" 
                                        aria-controls="workflow-status">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-label-success me-2">
                                            <i class="bx bx-sitemap"></i>
                                        </div>
                                        <div class="text-start">
                                            <div class="fw-bold"><?= get_label('workflow_status', 'Workflow Status') ?></div>
                                            <small class="text-muted"><?= get_label('approval_process', 'Approval Process') ?></small>
                                        </div>
                                    </div>
                                </button>
                            </li>
                            
                            <!-- Related Documents Tab -->
                            <li class="nav-item">
                                <button type="button" 
                                        class="nav-link" 
                                        role="tab" 
                                        data-bs-toggle="tab" 
                                        data-bs-target="#related-documents" 
                                        aria-controls="related-documents">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-label-info me-2">
                                            <i class="bx bx-receipt"></i>
                                        </div>
                                        <div class="text-start">
                                            <div class="fw-bold"><?= get_label('related_documents', 'Related Documents') ?></div>
                                            <small class="text-muted"><?= get_label('contract_link', 'Contract Link') ?></small>
                                        </div>
                                    </div>
                                </button>
                            </li>
                            
                            <!-- Timeline & Notes Tab -->
                            <li class="nav-item">
                                <button type="button" 
                                        class="nav-link" 
                                        role="tab" 
                                        data-bs-toggle="tab" 
                                        data-bs-target="#timeline-notes" 
                                        aria-controls="timeline-notes">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-label-warning me-2">
                                            <i class="bx bx-time-five"></i>
                                        </div>
                                        <div class="text-start">
                                            <div class="fw-bold"><?= get_label('timeline_notes', 'Timeline & Notes') ?></div>
                                            <small class="text-muted"><?= get_label('history', 'History') ?></small>
                                        </div>
                                    </div>
                                </button>
                            </li>
                        </ul>
                        
                        <div class="tab-content p-4">
                            <!-- Extract Overview Tab Content -->
                            <div class="tab-pane fade show active" id="extract-overview" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <!-- Extract Items Summary -->
                                        <h5 class="mb-4">
                                            <i class="bx bx-list-ul text-primary me-2"></i>
                                            <?= $estimate_invoice->type == 'estimate' ? get_label('extract_items', 'Extract Items') : get_label('invoice_items', 'Invoice Items') ?>
                                        </h5>
                                        
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th><?= get_label('item_description', 'Item Description') ?></th>
                                                        <th><?= get_label('quantity', 'Quantity') ?></th>
                                                        <th><?= get_label('unit', 'Unit') ?></th>
                                                        <th><?= get_label('unit_price', 'Unit Price') ?></th>
                                                        <th><?= get_label('total', 'Total') ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $count = 0; @endphp
                                                    @foreach($estimate_invoice->items as $item)
                                                    @php $count++; @endphp
                                                    <tr>
                                                        <td>{{ $count }}</td>
                                                        <td>
                                                            <div>
                                                                <strong>{{ $item->title }}</strong>
                                                                @if($item->description)
                                                                <br><small class="text-muted">{{ $item->description }}</small>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>{{ $item->pivot->qty }}</td>
                                                        <td>{{ $item->pivot->unit_id ? $item->unit->title : '-' }}</td>
                                                        <td>{{ format_currency($item->pivot->rate) }}</td>
                                                        <td>{{ format_currency($item->pivot->amount) }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot class="table-light">
                                                    <tr>
                                                        <th colspan="5" class="text-end"><?= get_label('subtotal', 'Subtotal') ?>:</th>
                                                        <th>{{ format_currency($estimate_invoice->total) }}</th>
                                                    </tr>
                                                    @if($estimate_invoice->tax_amount > 0)
                                                    <tr>
                                                        <th colspan="5" class="text-end"><?= get_label('tax', 'Tax') ?>:</th>
                                                        <th>{{ format_currency($estimate_invoice->tax_amount) }}</th>
                                                    </tr>
                                                    @endif
                                                    <tr class="table-success">
                                                        <th colspan="5" class="text-end"><?= get_label('final_total', 'Final Total') ?>:</th>
                                                        <th>{{ format_currency($estimate_invoice->final_total) }}</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <!-- Quick Actions -->
                                        <h5 class="mb-4">
                                            <i class="bx bx-bolt text-warning me-2"></i>
                                            <?= get_label('quick_actions', 'Quick Actions') ?>
                                        </h5>
                                        
                                        <div class="d-grid gap-2 mb-4">
                                            <a href="{{ url('estimates-invoices/pdf/' . $estimate_invoice->id) }}" class="btn btn-outline-primary">
                                                <i class="bx bx-file me-1"></i>
                                                <?= get_label('download_pdf', 'Download PDF') ?>
                                            </a>
                                            
                                            @if($estimate_invoice->status === 'draft' && auth()->user()->can('edit_estimates_invoices'))
                                            <a href="{{ route('estimates-invoices.edit', $estimate_invoice->id) }}" class="btn btn-outline-warning">
                                                <i class="bx bx-edit me-1"></i>
                                                <?= get_label('edit_extract', 'Edit Extract') ?>
                                            </a>
                                            @endif
                                            
                                            @if($estimate_invoice->status === 'approved' && auth()->user()->can('create_payments'))
                                            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#recordPaymentModal">
                                                <i class="bx bx-credit-card me-1"></i>
                                                <?= get_label('record_payment', 'Record Payment') ?>
                                            </button>
                                            @endif
                                        </div>
                                        
                                        <!-- Status Information -->
                                        <div class="p-3 bg-light rounded">
                                            <h6 class="mb-3"><?= get_label('status_information', 'Status Information') ?></h6>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span><?= get_label('current_status', 'Current Status') ?>:</span>
                                                <span class="badge bg-{{ getExtractStatusColor($estimate_invoice->status) }}">
                                                    {{ getExtractStatusText($estimate_invoice->status) }}
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span><?= get_label('created_date', 'Created Date') ?>:</span>
                                                <span>{{ format_date($estimate_invoice->created_at) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span><?= get_label('last_updated', 'Last Updated') ?>:</span>
                                                <span>{{ format_date($estimate_invoice->updated_at) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Workflow Status Tab Content -->
                            <div class="tab-pane fade" id="workflow-status" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <h5 class="mb-4">
                                            <i class="bx bx-sitemap text-success me-2"></i>
                                            <?= get_label('approval_workflow', 'Approval Workflow') ?>
                                        </h5>
                                        
                                        <!-- Workflow Progress Visualization -->
                                        <div class="workflow-steps">
                                            @include('estimates-invoices.partials.workflow-steps', ['estimate_invoice' => $estimate_invoice])
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <h5 class="mb-4">
                                            <i class="bx bx-user-check text-info me-2"></i>
                                            <?= get_label('workflow_assignments', 'Workflow Assignments') ?>
                                        </h5>
                                        
                                        @include('estimates-invoices.partials.assignments', ['estimate_invoice' => $estimate_invoice])
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Related Documents Tab Content -->
                            <div class="tab-pane fade" id="related-documents" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5 class="mb-4">
                                            <i class="bx bx-link text-info me-2"></i>
                                            <?= get_label('related_contract', 'Related Contract') ?>
                                        </h5>
                                        
                                        @include('estimates-invoices.partials.related-contract', ['estimate_invoice' => $estimate_invoice])
                                    </div>
                                    <div class="col-md-4">
                                        <h5 class="mb-4">
                                            <i class="bx bx-file text-warning me-2"></i>
                                            <?= get_label('extract_documents', 'Extract Documents') ?>
                                        </h5>
                                        
                                        @include('estimates-invoices.partials.documents', ['estimate_invoice' => $estimate_invoice])
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Timeline & Notes Tab Content -->
                            <div class="tab-pane fade" id="timeline-notes" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <h5 class="mb-4">
                                            <i class="bx bx-time-five text-warning me-2"></i>
                                            <?= get_label('activity_timeline', 'Activity Timeline') ?>
                                        </h5>
                                        
                                        @include('estimates-invoices.partials.timeline', ['estimate_invoice' => $estimate_invoice])
                                    </div>
                                    <div class="col-lg-4">
                                        <h5 class="mb-4">
                                            <i class="bx bx-notepad text-secondary me-2"></i>
                                            <?= get_label('notes', 'Notes') ?>
                                        </h5>
                                        
                                        @include('estimates-invoices.partials.notes', ['estimate_invoice' => $estimate_invoice])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Print Section -->
    <div class="card" id="section-to-print">
        <div class="card-body">
            <!-- Row for logo and date time -->
            <div class="row mb-4">
                <div class="col-md-4 text-start">
                    <img src="{{asset($general_settings['full_logo'])}}" alt="" width="200px" />
                </div>

                <div class="col-md-8 text-end">
                    <p>
                        <?php
                        $timezone = config('app.timezone');
                        $currentTime = now()->tz($timezone);
                        echo '<span class="text-muted">' . $currentTime->format($php_date_format . ' H:i:s') . '</span>';
                        ?>
                    </p>
                </div>
            </div>
            
            <div class="row">
                <!-- Company details -->
                <div class="col-md-4">
                    <p class="no-margin-p">
                        <strong>{{ $general_settings['company_title'] ?? 'Company Name' }}</strong>
                    </p>
                    <!-- Company address and contact details -->
                    @if($general_settings['company_address'] ?? '')
                    <p class="no-margin-p">{{ $general_settings['company_address'] }}</p>
                    @endif
                    @php
                    $addressParts = [
                    $general_settings['company_city'] ?? '',
                    $general_settings['company_state'] ?? '',
                    $general_settings['company_country'] ?? '',
                    $general_settings['company_zip'] ?? '',
                    ];
                    $addressParts = array_filter($addressParts); // Remove empty values
                    $companyAddress = implode(', ', $addressParts);
                    @endphp
                    @if($companyAddress)
                    <p class="no-margin-p">{{ $companyAddress }}</p>
                    @endif
                    @if($general_settings['company_phone'] ?? '')
                    <p class="no-margin-p">{{get_label('phone','Phone')}}: {{ $general_settings['company_phone'] }}</p>
                    @endif
                    @if($general_settings['company_email'] ?? '')
                    <p class="no-margin-p">{{get_label('email','Email')}}: {{ $general_settings['company_email'] }}</p>
                    @endif
                    @if($general_settings['company_website'] ?? '')
                    <p class="no-margin-p">{{get_label('website','Website')}}: {{ $general_settings['company_website'] }}</p>
                    @endif
                    @if($general_settings['company_vat_number'] ?? '')
                    <p class="no-margin-p">{{get_label('vat_number','VAT Number')}}: {{ $general_settings['company_vat_number'] }}</p>
                    @endif
                </div>

                <!-- Billing details -->
                <div class="col-md-4">
                    <strong>{{ get_label('billing_details', 'Billing details') }}</strong>
                    <hr>
                    @if($estimate_invoice->client)
                    <p class="no-margin-p"><strong>{{ $estimate_invoice->client->first_name ?? '' }} {{ $estimate_invoice->client->last_name ?? '' }}</strong></p>
                    @if($estimate_invoice->client->company)
                    <p class="no-margin-p text-muted">{{ $estimate_invoice->client->company }}</p>
                    @endif
                    @else
                    @if($estimate_invoice->name)
                    <p class="no-margin-p"><strong>{{ $estimate_invoice->name }}</strong></p>
                    @endif
                    @endif
                    @if($estimate_invoice->address)
                    <p class="no-margin-p">{{ $estimate_invoice->address }}</p>
                    @endif
                    @php
                    $clientCityState = [];
                    if($estimate_invoice->client) {
                        if($estimate_invoice->client->city) $clientCityState[] = $estimate_invoice->client->city;
                        if($estimate_invoice->client->state) $clientCityState[] = $estimate_invoice->client->state;
                        if($estimate_invoice->client->country) $clientCityState[] = $estimate_invoice->client->country;
                        if($estimate_invoice->client->zip) $clientCityState[] = $estimate_invoice->client->zip;
                    } else {
                        if($estimate_invoice->city) $clientCityState[] = $estimate_invoice->city;
                        if($estimate_invoice->state) $clientCityState[] = $estimate_invoice->state;
                        if($estimate_invoice->country) $clientCityState[] = $estimate_invoice->country;
                        if($estimate_invoice->zip_code) $clientCityState[] = $estimate_invoice->zip_code;
                    }
                    $clientAddress = implode(', ', $clientCityState);
                    @endphp
                    @if($clientAddress)
                    <p class="no-margin-p">{{ $clientAddress }}</p>
                    @endif
                    @if($estimate_invoice->client && $estimate_invoice->client->phone)
                    <p class="no-margin-p">{{ get_label('phone', 'Phone') }}: {{ $estimate_invoice->client->phone }}</p>
                    @elseif($estimate_invoice->phone)
                    <p class="no-margin-p">{{ get_label('phone', 'Phone') }}: {{ $estimate_invoice->phone }}</p>
                    @endif
                    @if($estimate_invoice->client && $estimate_invoice->client->email)
                    <p class="no-margin-p">{{ get_label('email', 'Email') }}: {{ $estimate_invoice->client->email }}</p>
                    @endif
                </div>

                <!-- Extract details -->
                <div class="col-md-4">
                    <strong>{{ get_label('extract_details', 'Extract details') }}</strong>
                    <hr>
                    <p class="no-margin-p"><strong>{{ get_label('extract_no', 'Extract No.') }}:</strong> #{{ $estimate_invoice->name }}</p>
                    <p class="no-margin-p"><strong>{{ get_label('from_date', 'From date') }}:</strong> {{ format_date($estimate_invoice->from_date) }}</p>
                    <p class="no-margin-p"><strong>{{ get_label('to_date', 'To date') }}:</strong> {{ format_date($estimate_invoice->to_date) }}</p>
                    <p class="no-margin-p"><strong>{{ get_label('status', 'Status') }}:</strong> {{ $estimate_invoice->status }}</p>
                    
                    <!-- Related Contract/Project Details -->
                    @if($estimate_invoice->contract_id)
                    <div class="mt-3 pt-2 border-top">
                        <strong class="text-info">{{ get_label('related_contract', 'Related Contract') }}</strong>
                        <p class="no-margin-p"><strong>{{ get_label('contract_id', 'Contract ID') }}:</strong> #{{ $estimate_invoice->contract_id }}</p>
                        @if(isset($relatedContract))
                        <p class="no-margin-p"><strong>{{ get_label('contract_title', 'Contract Title') }}:</strong> {{ $relatedContract->title ?? 'N/A' }}</p>
                        @if(isset($relatedProject))
                        <p class="no-margin-p"><strong>{{ get_label('project', 'Project') }}:</strong> {{ $relatedProject->title ?? 'N/A' }}</p>
                        @endif
                        @if(isset($contractor))
                        <p class="no-margin-p"><strong>{{ get_label('contractor', 'Contractor') }}:</strong> {{ $contractor->first_name ?? '' }} {{ $contractor->last_name ?? '' }}</p>
                        @endif
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            <hr>
            
            <!-- Display Items -->
            <div class="row mt-4 mx-1">
                <div class="col-md-12">
                    <h5><?= $estimate_invoice->type == 'estimate' ? get_label('extract_summary', 'Extract summary') : get_label('invoice_summary', 'Invoice summary') ?></h5>
                    @if(count($estimate_invoice->items) > 0)
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ get_label('product_service', 'Product/Service') }}</th>
                                <th>{{ get_label('quantity', 'Quantity') }}</th>
                                <th>{{ get_label('unit', 'Unit') }}</th>
                                <th>{{ get_label('rate', 'Rate') }}</th>
                                <th>{{ get_label('tax', 'Tax') }}</th>
                                <th>{{ get_label('amount', 'Amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $count = 0;
                            @endphp
                            @foreach($estimate_invoice->items as $item)
                            @php
                            $count++;
                            @endphp
                            <tr>
                                <td>{{ $count }}</td>
                                <td>{{ $item->title }} <br><span class="text-muted">{{ $item->description }}</span></td>
                                <td>{{ $item->pivot->qty }}</td>
                                <td>
                                    {{ $item->pivot->unit_id ? $item->unit->title : '-' }}
                                </td>
                                <td>{{ format_currency($item->pivot->rate) }}</td>
                                <td>
                                    {{ $item->pivot->tax_id ? Tax::find($item->pivot->tax_id)->title .' - '. get_tax_data($item->pivot->tax_id, $item->pivot->rate * $item->pivot->qty,1)['dispTax'] : '-' }}
                                </td>
                                <td>{{ format_currency($item->pivot->amount) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p><?= get_label('no_items_found', 'No items found') ?></p>
                    @endif
                </div>
            </div>
            
            @if ($estimate_invoice->type == 'invoice')
            <div class="row mt-4 mx-1">
                <div class="col-md-12">
                    <h5><?= get_label('payment_summary', 'Payment summary') ?></h5>
                    @if(count($estimate_invoice->payments) > 0)
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ get_label('id', 'ID') }}</th>
                                <th>{{ get_label('amount', 'Amount') }}</th>
                                <th>{{ get_label('payment_method', 'Payment method') }}</th>
                                <th>{{ get_label('note', 'Note') }}</th>
                                <th>{{ get_label('payment_date', 'Payment date') }}</th>
                                <th>{{ get_label('amount_left', 'Amount left') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $i = 1;
                            @endphp
                            @foreach($estimate_invoice->payments as $payment)
                            @php
                            // Get the total paid amount for the invoice
                            $paid_amount = $estimate_invoice->payments->where('id', '<=', $payment->id)->sum('amount');
                                // Calculate the amount left
                                $amount_left = $estimate_invoice->final_total - $paid_amount;
                                @endphp
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $payment->id }}</td>
                                    <td>{{ format_currency($payment->amount) }}</td>
                                    <td>{{ $payment->paymentMethod->title ?? '-' }}</td>
                                    <td>{{ $payment->note ?? '-' }}</td>
                                    <td>{{ format_date($payment->payment_date) }}</td>
                                    <td>{{ format_currency($amount_left) }}</td>
                                </tr>
                                @php
                                $i++;
                                @endphp
                                @endforeach
                        </tbody>
                    </table>
                    @else
                    <p><?= get_label('no_payments_found_invoice', 'No payments found for this invoice.') ?></p>
                    @endif
                </div>
            </div>
            @endif
            
            <div class="row mt-4 mx-1">
                <div class="col-md-6">
                </div>
                <div class="col-md-6">
                    <!-- Net Payable -->
                    <div class="text-end mt-4">
                        <div class="invoice-detail-item">
                            <div class="invoice-detail-name"><?= get_label('sub_total', 'Sub total') ?></div>
                            <div class="invoice-detail-value">{{ format_currency($estimate_invoice->total) }}</div>
                        </div>
                        <div class="invoice-detail-item">
                            <div class="invoice-detail-name"><?= get_label('tax', 'Tax') ?></div>
                            <div class="invoice-detail-value">{{ format_currency($estimate_invoice->tax_amount) }}</div>
                        </div>
                        <div class="invoice-detail-item">
                            <div class="invoice-detail-name"><?= get_label('final_total', 'Final total') ?></div>
                            <div class="invoice-detail-value">{{ format_currency($estimate_invoice->final_total) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Note -->
            <div class="row mt-4 mx-1">
                <div class="col-md-6">
                    @if ($estimate_invoice->note)
                    <h5><?= get_label('note', 'Note') ?></h5>
                    <p>{{ $estimate_invoice->note ?: '-' }}</p>
                    @endif
                    @if ($estimate_invoice->personal_note)
                    <h5><?= get_label('personal_note', 'Personal note') ?></h5>
                    <p>{{ $estimate_invoice->personal_note ?: '-' }}</p>
                    @endif
                </div>
            </div>
            
            <div class="row mt-5">
                <div class="col-md-6 text-start">
                    <span class="text-muted">
                        <strong><?= get_label('created_at', 'Created at') ?>:</strong>
                        {{ format_date($estimate_invoice->created_at,true) }}
                    </span>
                </div>
                <div class="col-md-6 text-end">
                    <span class="text-muted">
                        <strong><?= get_label('last_updated_at', 'Last updated at') ?>:</strong>
                        {{ format_date($estimate_invoice->updated_at,true) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Record Payment Modal -->
<div class="modal fade" id="recordPaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= get_label('record_payment', 'Record Payment') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="recordPaymentForm">
                    @csrf
                    <input type="hidden" name="estimate_invoice_id" value="{{ $estimate_invoice->id }}">
                    
                    <div class="mb-3">
                        <label class="form-label"><?= get_label('payment_amount', 'Payment Amount') ?></label>
                        <input type="number" class="form-control" name="amount" step="0.01" min="0" max="{{ $estimate_invoice->final_total }}" required>
                        <div class="form-text"><?= get_label('remaining_balance', 'Remaining balance') ?>: {{ format_currency($estimate_invoice->final_total - $estimate_invoice->payments->sum('amount')) }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= get_label('payment_method', 'Payment Method') ?></label>
                        <select class="form-select" name="payment_method_id" required>
                            <option value=""><?= get_label('select_payment_method', 'Select payment method') ?></option>
                            @foreach($paymentMethods as $method)
                            <option value="{{ $method->id }}">{{ $method->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= get_label('payment_date', 'Payment Date') ?></label>
                        <input type="date" class="form-control" name="payment_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= get_label('note', 'Note') ?></label>
                        <textarea class="form-control" name="note" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= get_label('cancel', 'Cancel') ?></button>
                <button type="button" class="btn btn-primary" onclick="recordPayment()"><?= get_label('record_payment', 'Record Payment') ?></button>
            </div>
        </div>
    </div>
</div>

@endsection