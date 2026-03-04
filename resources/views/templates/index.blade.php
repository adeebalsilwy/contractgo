@extends('layout')
@section('title')
    <?= get_label('template_management', 'Template Management') ?>
@endsection
@section('content')
    @php
    $menu = 'settings';
    $sub_menu = 'templates';
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
                            <a href="{{ url('settings') }}"><?= get_label('settings', 'Settings') ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?= get_label('templates', 'Templates') ?></li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#createTemplateModal">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('create_template', 'Create Template') ?>">
                        <i class='bx bx-plus'></i>
                    </button>
                </a>
            </div>
        </div>

        <!-- Template Management Dashboard -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bx bx-file"></i> <?= get_label('template_library', 'Template Library') ?>
                        </h5>
                        <div class="d-flex gap-2">
                            <!-- Template Type Filter -->
                            <select id="templateTypeFilter" class="form-select form-select-sm" style="width: 150px;">
                                <option value="all"><?= get_label('all_types', 'All Types') ?></option>
                                <option value="workflow"><?= get_label('workflow_templates', 'Workflow Templates') ?></option>
                                <option value="contract"><?= get_label('contract_templates', 'Contract Templates') ?></option>
                                <option value="extract"><?= get_label('extract_templates', 'Extract Templates') ?></option>
                            </select>
                            
                            <!-- Status Filter -->
                            <select id="templateStatusFilter" class="form-select form-select-sm" style="width: 120px;">
                                <option value="all"><?= get_label('all_statuses', 'All Statuses') ?></option>
                                <option value="draft"><?= get_label('draft', 'Draft') ?></option>
                                <option value="active"><?= get_label('active', 'Active') ?></option>
                                <option value="archived"><?= get_label('archived', 'Archived') ?></option>
                            </select>
                            
                            <!-- Search -->
                            <div class="input-group input-group-sm" style="width: 200px;">
                                <input type="text" id="templateSearch" class="form-control" placeholder="<?= get_label('search_templates', 'Search templates...') ?>">
                                <button class="btn btn-outline-secondary" type="button" id="searchButton">
                                    <i class="bx bx-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <!-- Template Statistics -->
                        <div class="row mb-4">
                            <div class="col-md-3 mb-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <i class="bx bx-file fs-1"></i>
                                        <h3 class="mb-0" id="totalTemplates">0</h3>
                                        <p class="mb-0"><?= get_label('total_templates', 'Total Templates') ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <i class="bx bx-check-circle fs-1"></i>
                                        <h3 class="mb-0" id="activeTemplates">0</h3>
                                        <p class="mb-0"><?= get_label('active_templates', 'Active Templates') ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <i class="bx bx-edit fs-1"></i>
                                        <h3 class="mb-0" id="draftTemplates">0</h3>
                                        <p class="mb-0"><?= get_label('draft_templates', 'Draft Templates') ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <i class="bx bx-star fs-1"></i>
                                        <h3 class="mb-0" id="defaultTemplates">0</h3>
                                        <p class="mb-0"><?= get_label('default_templates', 'Default Templates') ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Template List -->
                        <div class="table-responsive">
                            <table class="table table-striped" id="templatesTable">
                                <thead>
                                    <tr>
                                        <th><?= get_label('name', 'Name') ?></th>
                                        <th><?= get_label('type', 'Type') ?></th>
                                        <th><?= get_label('status', 'Status') ?></th>
                                        <th><?= get_label('workspace', 'Workspace') ?></th>
                                        <th><?= get_label('created_by', 'Created By') ?></th>
                                        <th><?= get_label('version', 'Version') ?></th>
                                        <th><?= get_label('default', 'Default') ?></th>
                                        <th><?= get_label('created_at', 'Created At') ?></th>
                                        <th><?= get_label('actions', 'Actions') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Template rows will be loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Template Modal -->
    <div class="modal fade" id="createTemplateModal" tabindex="-1" aria-labelledby="createTemplateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createTemplateModalLabel">
                        <i class="bx bx-plus"></i> <?= get_label('create_new_template', 'Create New Template') ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createTemplateForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="templateType" class="form-label"><?= get_label('template_type', 'Template Type') ?> *</label>
                                <select class="form-select" id="templateType" name="type" required>
                                    <option value=""><?= get_label('select_template_type', 'Select Template Type') ?></option>
                                    <option value="workflow"><?= get_label('workflow_template', 'Workflow Template') ?></option>
                                    <option value="contract"><?= get_label('contract_template', 'Contract Template') ?></option>
                                    <option value="extract"><?= get_label('extract_template', 'Extract Template') ?></option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="templateName" class="form-label"><?= get_label('template_name', 'Template Name') ?> *</label>
                                <input type="text" class="form-control" id="templateName" name="name" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="templateStatus" class="form-label"><?= get_label('status', 'Status') ?> *</label>
                                <select class="form-select" id="templateStatus" name="status" required>
                                    <option value="draft"><?= get_label('draft', 'Draft') ?></option>
                                    <option value="active"><?= get_label('active', 'Active') ?></option>
                                    <option value="archived"><?= get_label('archived', 'Archived') ?></option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="templateWorkspace" class="form-label"><?= get_label('workspace', 'Workspace') ?></label>
                                <select class="form-select" id="templateWorkspace" name="workspace_id">
                                    <option value=""><?= get_label('all_workspaces', 'All Workspaces') ?></option>
                                    @foreach($workspaces as $workspace)
                                        <option value="{{ $workspace->id }}">{{ $workspace->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="templateDescription" class="form-label"><?= get_label('description', 'Description') ?></label>
                            <textarea class="form-control" id="templateDescription" name="description" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="templateContent" class="form-label"><?= get_label('template_content', 'Template Content') ?> *</label>
                            <textarea class="form-control" id="templateContent" name="content" rows="10" required></textarea>
                            <div class="form-text">
                                <?= get_label('template_variables_help', 'Use variables like {contract_number}, {client_name}, {current_date} in your template content.') ?>
                            </div>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="isDefaultTemplate" name="is_default" value="1">
                            <label class="form-check-label" for="isDefaultTemplate">
                                <?= get_label('set_as_default', 'Set as Default Template') ?>
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= get_label('cancel', 'Cancel') ?></button>
                    <button type="button" class="btn btn-primary" id="saveTemplateBtn">
                        <i class="bx bx-save"></i> <?= get_label('save_template', 'Save Template') ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Template Preview Modal -->
    <div class="modal fade" id="previewTemplateModal" tabindex="-1" aria-labelledby="previewTemplateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewTemplateModalLabel">
                        <i class="bx bx-show"></i> <?= get_label('template_preview', 'Template Preview') ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="previewContent">
                        <!-- Preview content will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= get_label('close', 'Close') ?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Template Variables Help Modal -->
    <div class="modal fade" id="variablesHelpModal" tabindex="-1" aria-labelledby="variablesHelpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="variablesHelpModalLabel">
                        <i class="bx bx-help-circle"></i> <?= get_label('template_variables', 'Template Variables') ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><?= get_label('general_variables', 'General Variables') ?></h6>
                            <ul class="list-unstyled">
                                <li><code>{current_date}</code> - <?= get_label('current_date', 'Current Date') ?></li>
                                <li><code>{current_datetime}</code> - <?= get_label('current_datetime', 'Current Date and Time') ?></li>
                                <li><code>{company_name}</code> - <?= get_label('company_name', 'Company Name') ?></li>
                                <li><code>{company_address}</code> - <?= get_label('company_address', 'Company Address') ?></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6><?= get_label('contract_variables', 'Contract Variables') ?></h6>
                            <ul class="list-unstyled">
                                <li><code>{contract_number}</code> - <?= get_label('contract_number', 'Contract Number') ?></li>
                                <li><code>{contract_title}</code> - <?= get_label('contract_title', 'Contract Title') ?></li>
                                <li><code>{client_name}</code> - <?= get_label('client_name', 'Client Name') ?></li>
                                <li><code>{project_name}</code> - <?= get_label('project_name', 'Project Name') ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= get_label('close', 'Close') ?></button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#templatesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("templates.list") }}',
                data: function(d) {
                    d.type = $('#templateTypeFilter').val();
                    d.status = $('#templateStatusFilter').val();
                    d.search = $('#templateSearch').val();
                }
            },
            columns: [
                { data: 'name', name: 'name' },
                { data: 'type', name: 'type' },
                { data: 'status', name: 'status' },
                { data: 'workspace', name: 'workspace' },
                { data: 'created_by', name: 'created_by' },
                { data: 'version', name: 'version' },
                { data: 'is_default', name: 'is_default' },
                { data: 'created_at', name: 'created_at' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            order: [[7, 'desc']], // Order by created_at desc
            pageLength: 25,
            language: {
                search: "{{ get_label('search', 'Search') }}:",
                lengthMenu: "{{ get_label('show_entries', 'Show _MENU_ entries') }}",
                info: "{{ get_label('showing_entries', 'Showing _START_ to _END_ of _TOTAL_ entries') }}",
                paginate: {
                    first: "{{ get_label('first', 'First') }}",
                    last: "{{ get_label('last', 'Last') }}",
                    next: "{{ get_label('next', 'Next') }}",
                    previous: "{{ get_label('previous', 'Previous') }}"
                }
            }
        });

        // Filter events
        $('#templateTypeFilter, #templateStatusFilter').on('change', function() {
            table.draw();
        });

        $('#searchButton').on('click', function() {
            table.draw();
        });

        $('#templateSearch').on('keypress', function(e) {
            if (e.which === 13) {
                table.draw();
            }
        });

        // Save template button
        $('#saveTemplateBtn').on('click', function() {
            var formData = $('#createTemplateForm').serialize();
            
            $.ajax({
                url: '{{ route("templates.store") }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.error) {
                        toastr.error(response.message);
                    } else {
                        toastr.success(response.message);
                        $('#createTemplateModal').modal('hide');
                        $('#createTemplateForm')[0].reset();
                        table.draw();
                    }
                },
                error: function(xhr) {
                    toastr.error('An error occurred while saving the template.');
                }
            });
        });

        // Preview template
        $(document).on('click', '.preview-template', function() {
            var templateId = $(this).data('id');
            
            $.ajax({
                url: '/templates/' + templateId + '/preview',
                method: 'GET',
                success: function(response) {
                    if (response.error) {
                        toastr.error(response.message);
                    } else {
                        $('#previewContent').html('<pre>' + response.preview + '</pre>');
                        $('#previewTemplateModal').modal('show');
                    }
                },
                error: function(xhr) {
                    toastr.error('An error occurred while loading the preview.');
                }
            });
        });

        // Duplicate template
        $(document).on('click', '.duplicate-template', function() {
            var templateId = $(this).data('id');
            var newName = prompt('{{ get_label("enter_new_template_name", "Enter new template name") }}');
            
            if (newName) {
                $.ajax({
                    url: '/templates/' + templateId + '/duplicate',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: newName
                    },
                    success: function(response) {
                        if (response.error) {
                            toastr.error(response.message);
                        } else {
                            toastr.success(response.message);
                            table.draw();
                        }
                    },
                    error: function(xhr) {
                        toastr.error('An error occurred while duplicating the template.');
                    }
                });
            }
        });

        // Delete template
        $(document).on('click', '.delete-template', function() {
            var templateId = $(this).data('id');
            
            if (confirm('{{ get_label("confirm_delete_template", "Are you sure you want to delete this template?") }}')) {
                $.ajax({
                    url: '/templates/' + templateId,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.error) {
                            toastr.error(response.message);
                        } else {
                            toastr.success(response.message);
                            table.draw();
                        }
                    },
                    error: function(xhr) {
                        toastr.error('An error occurred while deleting the template.');
                    }
                });
            }
        });

        // Load template statistics
        function loadTemplateStats() {
            // This would typically make an AJAX call to get statistics
            // For now, we'll update with dummy data
            $('#totalTemplates').text('12');
            $('#activeTemplates').text('8');
            $('#draftTemplates').text('3');
            $('#defaultTemplates').text('3');
        }

        // Load statistics on page load
        loadTemplateStats();
    });
</script>
@endpush