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
                        <li class="breadcrumb-item active"><?= get_label('mind_map', 'Mind Map') ?></li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('contracts.show', $contract->id) }}">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('back_to_contract', 'Back to Contract') ?>">
                        <i class='bx bx-arrow-back'></i>
                    </button>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-sitemap me-2"></i>
                            <?= get_label('contract_mind_map', 'Contract Mind Map') ?> - {{ $contract->title }}
                        </h5>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm" id="export-png">
                                <i class="bx bx-download me-1"></i><?= get_label('export_png', 'Export PNG') ?>
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="export-pdf">
                                <i class="bx bx-file me-1"></i><?= get_label('export_pdf', 'Export PDF') ?>
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="toggle-legend">
                                <i class="bx bx-info-circle me-1"></i><?= get_label('legend', 'Legend') ?>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="mindmap-container" style="height: 600px; width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Legend Modal -->
    <div class="modal fade" id="legendModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= get_label('mind_map_legend', 'Mind Map Legend') ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><?= get_label('contract_elements', 'Contract Elements') ?></h6>
                            <ul class="list-unstyled">
                                <li><span class="badge bg-primary me-2">C</span> <?= get_label('contract', 'Contract') ?></li>
                                <li><span class="badge bg-success me-2">P</span> <?= get_label('project', 'Project') ?></li>
                                <li><span class="badge bg-info me-2">CL</span> <?= get_label('client', 'Client') ?></li>
                                <li><span class="badge bg-warning me-2">CT</span> <?= get_label('contract_type', 'Contract Type') ?></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6><?= get_label('workflow_elements', 'Workflow Elements') ?></h6>
                            <ul class="list-unstyled">
                                <li><span class="badge bg-secondary me-2">SS</span> <?= get_label('site_supervisor', 'Site Supervisor') ?></li>
                                <li><span class="badge bg-secondary me-2">QA</span> <?= get_label('quantity_approver', 'Quantity Approver') ?></li>
                                <li><span class="badge bg-secondary me-2">AC</span> <?= get_label('accountant', 'Accountant') ?></li>
                                <li><span class="badge bg-secondary me-2">FA</span> <?= get_label('final_approver', 'Final Approver') ?></li>
                            </ul>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6><?= get_label('related_modules', 'Related Modules') ?></h6>
                            <ul class="list-unstyled">
                                <li><span class="badge bg-danger me-2">Q</span> <?= get_label('quantities', 'Quantities') ?> ({{ $contract->quantities_count }})</li>
                                <li><span class="badge bg-success me-2">A</span> <?= get_label('approvals', 'Approvals') ?> ({{ $contract->approvals_count }})</li>
                                <li><span class="badge bg-warning me-2">AM</span> <?= get_label('amendments', 'Amendments') ?> ({{ $contract->amendments_count }})</li>
                                <li><span class="badge bg-info me-2">JE</span> <?= get_label('journal_entries', 'Journal Entries') ?> ({{ $contract->journal_entries_count }})</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6><?= get_label('project_elements', 'Project Elements') ?></h6>
                            <ul class="list-unstyled">
                                <li><span class="badge bg-primary me-2">T</span> <?= get_label('tasks', 'Tasks') ?> ({{ $tasks->count() }})</li>
                                <li><span class="badge bg-secondary me-2">U</span> <?= get_label('users', 'Users') ?> ({{ $contract->project ? $contract->project->users->count() : 0 }})</li>
                                <li><span class="badge bg-info me-2">CL</span> <?= get_label('clients', 'Clients') ?> ({{ $contract->project ? $contract->project->clients->count() : 0 }})</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/vis-network@9.1.2/standalone/umd/vis-network.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mind map data structure
            const nodes = new vis.DataSet([
                // Main contract node
                {id: 'contract_{{ $contract->id }}', label: '{{ $contract->title }}\n{{ format_currency($contract->value) }}', shape: 'box', color: '#007bff', font: {color: 'white'}},
                
                // Contract relationships
                {id: 'project_{{ $contract->project_id }}', label: '{{ $contract->project->title ?? 'No Project' }}', shape: 'ellipse', color: '#28a745'},
                {id: 'client_{{ $contract->client_id }}', label: '{{ $contract->client->first_name ?? '' }} {{ $contract->client->last_name ?? 'No Client' }}', shape: 'circle', color: '#17a2b8'},
                {id: 'type_{{ $contract->contract_type_id }}', label: '{{ $contract->contract_type->type ?? 'No Type' }}', shape: 'database', color: '#ffc107'},
                
                // Workflow roles
                {id: 'supervisor_{{ $contract->site_supervisor_id }}', label: '{{ $contract->siteSupervisor->first_name ?? '' }} {{ $contract->siteSupervisor->last_name ?? 'No Supervisor' }}\n(Site Supervisor)', shape: 'dot', color: '#6c757d', size: 20},
                {id: 'approver_{{ $contract->quantity_approver_id }}', label: '{{ $contract->quantityApprover->first_name ?? '' }} {{ $contract->quantityApprover->last_name ?? 'No Approver' }}\n(Quantity Approver)', shape: 'dot', color: '#6c757d', size: 20},
                {id: 'accountant_{{ $contract->accountant_id }}', label: '{{ $contract->accountant->first_name ?? '' }} {{ $contract->accountant->last_name ?? 'No Accountant' }}\n(Accountant)', shape: 'dot', color: '#6c757d', size: 20},
                {id: 'final_{{ $contract->final_approver_id }}', label: '{{ $contract->finalApprover->first_name ?? '' }} {{ $contract->finalApprover->last_name ?? 'No Final Approver' }}\n(Final Approver)', shape: 'dot', color: '#6c757d', size: 20},
                
                // Related modules
                {id: 'quantities', label: '<?= get_label('quantities', 'Quantities') ?>\n({{ $contract->quantities_count }})', shape: 'triangle', color: '#dc3545'},
                {id: 'approvals', label: '<?= get_label('approvals', 'Approvals') ?>\n({{ $contract->approvals_count }})', shape: 'triangle', color: '#28a745'},
                {id: 'amendments', label: '<?= get_label('amendments', 'Amendments') ?>\n({{ $contract->amendments_count }})', shape: 'triangle', color: '#ffc107'},
                {id: 'journal', label: '<?= get_label('journal_entries', 'Journal Entries') ?>\n({{ $contract->journal_entries_count }})', shape: 'triangle', color: '#17a2b8'},
                
                // Project elements
                {id: 'tasks', label: '<?= get_label('tasks', 'Tasks') ?>\n({{ $tasks->count() }})', shape: 'star', color: '#007bff'},
                {id: 'project_users', label: '<?= get_label('users', 'Users') ?>\n({{ $contract->project ? $contract->project->users->count() : 0 }})', shape: 'star', color: '#6c757d'},
                {id: 'project_clients', label: '<?= get_label('clients', 'Clients') ?>\n({{ $contract->project ? $contract->project->clients->count() : 0 }})', shape: 'star', color: '#17a2b8'}
            ]);

            const edges = new vis.DataSet([
                // Contract relationships
                {from: 'contract_{{ $contract->id }}', to: 'project_{{ $contract->project_id }}', arrows: 'to', color: '#28a745'},
                {from: 'contract_{{ $contract->id }}', to: 'client_{{ $contract->client_id }}', arrows: 'to', color: '#17a2b8'},
                {from: 'contract_{{ $contract->id }}', to: 'type_{{ $contract->contract_type_id }}', arrows: 'to', color: '#ffc107'},
                
                // Workflow relationships
                {from: 'contract_{{ $contract->id }}', to: 'supervisor_{{ $contract->site_supervisor_id }}', arrows: 'to', color: '#6c757d'},
                {from: 'contract_{{ $contract->id }}', to: 'approver_{{ $contract->quantity_approver_id }}', arrows: 'to', color: '#6c757d'},
                {from: 'contract_{{ $contract->id }}', to: 'accountant_{{ $contract->accountant_id }}', arrows: 'to', color: '#6c757d'},
                {from: 'contract_{{ $contract->id }}', to: 'final_{{ $contract->final_approver_id }}', arrows: 'to', color: '#6c757d'},
                
                // Module relationships
                {from: 'contract_{{ $contract->id }}', to: 'quantities', arrows: 'to', color: '#dc3545'},
                {from: 'contract_{{ $contract->id }}', to: 'approvals', arrows: 'to', color: '#28a745'},
                {from: 'contract_{{ $contract->id }}', to: 'amendments', arrows: 'to', color: '#ffc107'},
                {from: 'contract_{{ $contract->id }}', to: 'journal', arrows: 'to', color: '#17a2b8'},
                
                // Project relationships
                {from: 'project_{{ $contract->project_id }}', to: 'tasks', arrows: 'to', color: '#007bff'},
                {from: 'project_{{ $contract->project_id }}', to: 'project_users', arrows: 'to', color: '#6c757d'},
                {from: 'project_{{ $contract->project_id }}', to: 'project_clients', arrows: 'to', color: '#17a2b8'}
            ]);

            // Network configuration
            const container = document.getElementById('mindmap-container');
            const data = {nodes: nodes, edges: edges};
            const options = {
                layout: {
                    hierarchical: {
                        direction: 'UD',
                        sortMethod: 'directed',
                        nodeSpacing: 150,
                        levelSeparation: 150
                    }
                },
                nodes: {
                    shape: 'box',
                    font: {
                        size: 14,
                        face: 'Arial',
                        align: 'center'
                    },
                    margin: 10,
                    widthConstraint: {
                        maximum: 200
                    }
                },
                edges: {
                    width: 2,
                    smooth: {
                        type: 'continuous'
                    }
                },
                physics: {
                    enabled: true,
                    hierarchicalRepulsion: {
                        nodeDistance: 150
                    }
                },
                interaction: {
                    dragNodes: true,
                    dragView: true,
                    zoomView: true,
                    selectable: true
                }
            };

            // Create the network
            const network = new vis.Network(container, data, options);

            // Handle node clicks
            network.on('click', function(params) {
                if (params.nodes.length > 0) {
                    const nodeId = params.nodes[0];
                    handleNodeClick(nodeId);
                }
            });

            // Handle node double clicks
            network.on('doubleClick', function(params) {
                if (params.nodes.length > 0) {
                    const nodeId = params.nodes[0];
                    handleNodeDoubleClick(nodeId);
                }
            });

            // Legend toggle
            document.getElementById('toggle-legend').addEventListener('click', function() {
                const legendModal = new bootstrap.Modal(document.getElementById('legendModal'));
                legendModal.show();
            });

            // Export functions
            document.getElementById('export-png').addEventListener('click', function() {
                const canvas = container.getElementsByTagName('canvas')[0];
                const link = document.createElement('a');
                link.download = 'contract-mindmap-{{ $contract->id }}.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            });

            document.getElementById('export-pdf').addEventListener('click', function() {
                alert('<?= get_label('pdf_export_coming_soon', 'PDF export coming soon') ?>');
            });

            // Helper functions
            function handleNodeClick(nodeId) {
                // Add visual feedback
                const node = nodes.get(nodeId);
                if (node) {
                    nodes.update({id: nodeId, font: {size: 16, bold: true}});
                    setTimeout(() => {
                        nodes.update({id: nodeId, font: {size: 14, bold: false}});
                    }, 1000);
                }
            }

            function handleNodeDoubleClick(nodeId) {
                // Navigate to related pages
                if (nodeId.startsWith('contract_')) {
                    window.location.href = '{{ route('contracts.show', $contract->id) }}';
                } else if (nodeId.startsWith('project_')) {
                    window.open('{{ $contract->project ? route('projects.info', $contract->project->id) : '#' }}', '_blank');
                } else if (nodeId.startsWith('client_')) {
                    window.open('{{ $contract->client ? route('clients.profile', $contract->client->id) : '#' }}', '_blank');
                } else if (nodeId.includes('quantities')) {
                    window.open('{{ route('contract-quantities.index') }}?contract_id={{ $contract->id }}', '_blank');
                } else if (nodeId.includes('approvals')) {
                    window.open('{{ route('contract-approvals.history', $contract->id) }}', '_blank');
                } else if (nodeId.includes('amendments')) {
                    window.open('{{ route('contract-amendments.index') }}?contract_id={{ $contract->id }}', '_blank');
                } else if (nodeId.includes('journal')) {
                    window.open('{{ route('journal-entries.index') }}?contract_id={{ $contract->id }}', '_blank');
                } else if (nodeId.includes('tasks')) {
                    window.open('{{ $contract->project ? url('projects/information/' . $contract->project->id) : '#' }}', '_blank');
                }
            }
        });
    </script>
    @endpush
@endsection