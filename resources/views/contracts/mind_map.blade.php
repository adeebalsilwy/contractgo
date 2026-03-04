@extends('layout')
@section('title')
    <?= get_label('contract_mind_map', 'Contract Mind Map') ?>
@endsection
@section('content')
    @php
        $menu = 'contracts';
        $sub_menu = 'contracts';
    @endphp

    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-2 mt-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1">
                        <li class="breadcrumb-item">
                            <a href="{{ url('home') }}"><?= get_label('home', 'Home') ?></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ url('contracts') }}"><?= get_label('contracts', 'Contracts') ?></a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{ route('contracts.show', $contract->id) }}"><?= get_label('contract_details', 'Contract Details') ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?= get_label('mind_map_view', 'Mind Map View') ?></li>
                    </ol>
                </nav>
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
            // Define the mind map data structure
            window.mindMapData = {
                meta: {
                    name: 'contract-mindmap',
                    author: 'Tskify',
                    version: '1.0'
                },
                format: 'node_tree',
                data: {
                    id: 'root',
                    topic: '{{ $contract->title }}',
                    children: [
                        {
                            id: 'contract-info',
                            topic: '{{ get_label('contract_information', 'Contract Information') }}',
                            direction: 'left',
                            children: [
                                {
                                    id: 'client',
                                    topic: '{{ get_label('client', 'Client') }}: {{ $contract->client->company_name ?? $contract->client->first_name . " " . $contract->client->last_name }}'
                                },
                                {
                                    id: 'status',
                                    topic: '{{ get_label('status', 'Status') }}: {{ get_label($contract->status, ucfirst($contract->status)) }}'
                                },
                                {
                                    id: 'value',
                                    topic: '{{ get_label('value', 'Value') }}: {{ format_currency($contract->value) }}'
                                },
                                {
                                    id: 'dates',
                                    topic: '{{ get_label('dates', 'Dates') }}',
                                    children: [
                                        {
                                            id: 'start_date',
                                            topic: '{{ get_label('start_date', 'Start Date') }}: {{ format_date($contract->start_date) }}'
                                        },
                                        {
                                            id: 'end_date',
                                            topic: '{{ get_label('end_date', 'End Date') }}: {{ format_date($contract->end_date) }}'
                                        }
                                    ]
                                }
                            ]
                        }
                        @if($contract->workflow && $contract->workflow->steps->count() > 0)
                        ,{
                            id: 'workflow',
                            topic: '{{ get_label('workflow', 'Workflow') }}',
                            direction: 'right',
                            children: [
                                @foreach($contract->workflow->steps as $index => $step)
                                {
                                    id: 'step_{{ $step->id }}',
                                    topic: '{{ $step->name }} ({{ get_label($step->status, ucfirst($step->status)) }})'
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
                        toastr.success('{{ get_label('mindmap_export_success', 'Mind map exported successfully') }}');
                    }, 2000);
                } catch (error) {
                    toastr.error('{{ get_label('mindmap_export_failed', 'Failed to export mind map') }}');
                    console.log(error);
                }
            });
        });
    </script>
    @endpush
@endsection