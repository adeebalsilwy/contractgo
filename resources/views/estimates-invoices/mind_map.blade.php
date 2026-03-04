@extends('layout')
@section('title')
<?= $estimate_invoice->type == 'estimate' ? get_label('estimate_mind_map', 'Estimate Mind Map') : get_label('invoice_mind_map', 'Invoice Mind Map') ?>
@endsection
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-2 mt-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{ url('home') }}"><?= get_label('home', 'Home') ?></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ url('estimates-invoices') }}"><?= get_label('estimates_invoices', 'Estimates/Invoices') ?></a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="{{ route('estimates-invoices.view', $estimate_invoice->id) }}"><?= $estimate_invoice->type == 'estimate' ? get_label('view_estimate', 'View Estimate') : get_label('view_invoice', 'View Invoice') ?></a>
                    </li>
                    <li class="breadcrumb-item active"><?= get_label('mind_map', 'Mind Map') ?></li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('estimates-invoices.view', $estimate_invoice->id) }}">
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('back_to_extract', 'Back to Extract') ?>">
                    <i class='bx bx-arrow-back'></i>
                </button>
            </a>
        </div>
    </div>

    <div class="alert alert-primary" role="alert">
        <i class="bx bx-info-circle"></i> 
        {{ get_label('mind_map_collapse_info', 'Click the yellow dots to expand or collapse sections of the mind map.') }}
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="mind-map-container" id="mind-map"></div>
        </div>
        <div class="card-footer text-center">
            <button class="btn btn-primary export-mindmap-btn" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{ get_label('export_mindmap', 'Export Mind Map') }}">
                <i class="bx bx-export"></i>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('assets/js/mind-map.js') }}"></script>
<script src="{{ asset('assets/js/mindmap-screenshot.js') }}"></script>
<script src="{{ asset('assets/js/dom-to-image.js') }}"></script>
<script>
    $(document).ready(function () {
        // Define the mind map data structure for extracts
        window.mindMapData = {
            meta: {
                name: 'extract-mindmap',
                author: 'Tskify',
                version: '1.0'
            },
            format: 'node_tree',
            data: {
                id: 'root',
                topic: '<?= $estimate_invoice->type == 'estimate' ? get_label('estimate', 'Estimate') : get_label('invoice', 'Invoice') ?> #{{ $estimate_invoice->id }}',
                children: [
                    {
                        id: 'extract-info',
                        topic: '<?= get_label('information', 'Information') ?>',
                        direction: 'left',
                        children: [
                            {
                                id: 'status',
                                topic: '<?= get_label('status', 'Status') ?>: {{ get_label($estimate_invoice->status, ucfirst($estimate_invoice->status)) }}'
                            },
                            {
                                id: 'total',
                                topic: '<?= get_label('total', 'Total') ?>: {{ format_currency($estimate_invoice->final_total) }}'
                            },
                            {
                                id: 'items',
                                topic: '<?= get_label('items', 'Items') ?>: {{ $estimate_invoice->items_count }}'
                            }
                            @if($estimate_invoice->type === 'invoice')
                            ,{
                                id: 'payments',
                                topic: '<?= get_label('payments', 'Payments') ?>: {{ $estimate_invoice->payments_count }}'
                            }
                            @endif
                        ]
                    },
                    {
                        id: 'related-info',
                        topic: '<?= get_label('related_extracts', 'Related Extracts') ?>',
                        direction: 'right',
                        children: [
                            @if($estimate_invoice->client)
                            {
                                id: 'client',
                                topic: '<?= get_label('client', 'Client') ?>: {{ $estimate_invoice->client->first_name ?? '' }} {{ $estimate_invoice->client->last_name ?? '' }}'
                            },
                            @endif
                            @if($estimate_invoice->contract)
                            {
                                id: 'contract',
                                topic: '<?= get_label('contract', 'Contract') ?>: {{ $estimate_invoice->contract->title }}'
                            },
                            @endif
                            @if($estimate_invoice->project)
                            {
                                id: 'project',
                                topic: '<?= get_label('project', 'Project') ?>: {{ $estimate_invoice->project->title }}'
                            }
                            @endif
                        ].filter(child => child.topic.includes(':')) // Remove empty nodes
                    }
                    @if($relatedExtracts->count() > 0)
                    ,{
                        id: 'related-extracts',
                        topic: '<?= get_label('related_extracts', 'Related Extracts') ?>',
                        direction: 'bottom',
                        children: [
                            @foreach($relatedExtracts as $index => $related)
                            {
                                id: 'related_{{ $related->id }}',
                                topic: '{{ $related->type }}: {{ $related->name }} ({{ format_currency($related->final_total) }})'
                            }@if(!$loop->last),@endif
                            @endforeach
                        ]
                    }
                    @endif
                ]
            }
        };

        // Initialize jsMind with the same options as project mind maps
        var options = {
            container: 'mind-map',
            editable: false,
            theme: 'taskify',
            mode: 'full',
            support_html: true,
        };

        // Create a new jsMind instance
        var jm = new jsMind(options);
        jm.show(mindMapData);

        // Export functionality
        $('.export-mindmap-btn').on('click', function () {
            try {
                jm.shoot(); // Trigger the export
                setTimeout(function () {
                    toastr.success(label_mm_export_success);
                }, 2000);
            } catch (error) {
                toastr.error(label_mm_export_failed);
                console.log(error);
            }
        });
    });
</script>
@endpush
@endsection