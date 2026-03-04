@extends('layouts.app')

@section('title')
    <?= get_label('create_template', 'Create Template') ?>
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
                        <li class="breadcrumb-item">
                            <a href="{{ route('templates.index') }}"><?= get_label('templates', 'Templates') ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?= get_label('create_template', 'Create Template') ?></li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('templates.index') }}">
                    <button type="button" class="btn btn-sm btn-secondary">
                        <i class='bx bx-arrow-back'></i> <?= get_label('back_to_templates', 'Back to Templates') ?>
                    </button>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bx bx-plus"></i> <?= get_label('create_new_template', 'Create New Template') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="createTemplateForm" method="POST" action="{{ route('templates.store') }}">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="templateType" class="form-label"><?= get_label('template_type', 'Template Type') ?> *</label>
                                    <select class="form-select" id="templateType" name="type" required>
                                        <option value=""><?= get_label('select_template_type', 'Select Template Type') ?></option>
                                        <option value="workflow" {{ old('type') == 'workflow' ? 'selected' : '' }}><?= get_label('workflow_template', 'Workflow Template') ?></option>
                                        <option value="contract" {{ old('type') == 'contract' ? 'selected' : '' }}><?= get_label('contract_template', 'Contract Template') ?></option>
                                        <option value="extract" {{ old('type') == 'extract' ? 'selected' : '' }}><?= get_label('extract_template', 'Extract Template') ?></option>
                                    </select>
                                    @error('type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="templateName" class="form-label"><?= get_label('template_name', 'Template Name') ?> *</label>
                                    <input type="text" class="form-control" id="templateName" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="templateStatus" class="form-label"><?= get_label('status', 'Status') ?> *</label>
                                    <select class="form-select" id="templateStatus" name="status" required>
                                        <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}><?= get_label('draft', 'Draft') ?></option>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}><?= get_label('active', 'Active') ?></option>
                                        <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}><?= get_label('archived', 'Archived') ?></option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="templateWorkspace" class="form-label"><?= get_label('workspace', 'Workspace') ?></label>
                                    <select class="form-select" id="templateWorkspace" name="workspace_id">
                                        <option value=""><?= get_label('all_workspaces', 'All Workspaces') ?></option>
                                        @foreach($workspaces as $workspace)
                                            <option value="{{ $workspace->id }}" {{ old('workspace_id') == $workspace->id ? 'selected' : '' }}>{{ $workspace->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('workspace_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="templateDescription" class="form-label"><?= get_label('description', 'Description') ?></label>
                                <textarea class="form-control" id="templateDescription" name="description" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="templateContent" class="form-label"><?= get_label('template_content', 'Template Content') ?> *</label>
                                <textarea class="form-control" id="templateContent" name="content" rows="15" required>{{ old('content') }}</textarea>
                                <div class="form-text">
                                    <?= get_label('template_variables_help', 'Use variables like {contract_number}, {client_name}, {current_date} in your template content.') ?>
                                </div>
                                @error('content')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="isDefault" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isDefault">
                                        <?= get_label('set_as_default', 'Set as Default Template') ?>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Workflow Template Specific Fields -->
                            <div id="workflowTemplateFields" style="display: none;">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h6 class="mb-0"><?= get_label('workflow_steps', 'Workflow Steps') ?></h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="workflowStepsContainer">
                                            <!-- Workflow steps will be added here dynamically -->
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="addWorkflowStep">
                                            <i class="bx bx-plus"></i> <?= get_label('add_step', 'Add Step') ?>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h6 class="mb-0"><?= get_label('available_variables', 'Available Variables') ?></h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6><?= get_label('contract_variables', 'Contract Variables') ?></h6>
                                                <ul class="list-unstyled">
                                                    <li><code>{contract_number}</code> - <?= get_label('contract_number', 'Contract Number') ?></li>
                                                    <li><code>{contract_title}</code> - <?= get_label('contract_title', 'Contract Title') ?></li>
                                                    <li><code>{client_name}</code> - <?= get_label('client_name', 'Client Name') ?></li>
                                                    <li><code>{project_name}</code> - <?= get_label('project_name', 'Project Name') ?></li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <h6><?= get_label('workflow_variables', 'Workflow Variables') ?></h6>
                                                <ul class="list-unstyled">
                                                    <li><code>{site_supervisor}</code> - <?= get_label('site_supervisor', 'Site Supervisor') ?></li>
                                                    <li><code>{quantity_approver}</code> - <?= get_label('quantity_approver', 'Quantity Approver') ?></li>
                                                    <li><code>{accountant}</code> - <?= get_label('accountant', 'Accountant') ?></li>
                                                    <li><code>{reviewer}</code> - <?= get_label('reviewer', 'Reviewer') ?></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('templates.index') }}" class="btn btn-secondary">
                                    <?= get_label('cancel', 'Cancel') ?>
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save"></i> <?= get_label('create_template', 'Create Template') ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
<script>
    $(document).ready(function() {
        // Show/hide workflow template fields based on template type
        $('#templateType').on('change', function() {
            if ($(this).val() === 'workflow') {
                $('#workflowTemplateFields').show();
            } else {
                $('#workflowTemplateFields').hide();
            }
        });
        
        // Initialize based on selected type
        if ($('#templateType').val() === 'workflow') {
            $('#workflowTemplateFields').show();
        }
        
        // Add workflow step functionality
        $('#addWorkflowStep').on('click', function() {
            const stepCount = $('#workflowStepsContainer .workflow-step').length + 1;
            const stepHtml = `
                <div class="workflow-step mb-3 p-3 border rounded">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0"><?= get_label('step', 'Step') ?> ${stepCount}</h6>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-step">
                            <i class="bx bx-trash"></i>
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label class="form-label"><?= get_label('step_name', 'Step Name') ?></label>
                            <input type="text" class="form-control" name="workflow_steps[${stepCount}][name]" placeholder="<?= get_label('enter_step_name', 'Enter step name') ?>">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label"><?= get_label('role', 'Role') ?></label>
                            <select class="form-select" name="workflow_steps[${stepCount}][role]">
                                <option value="site_supervisor"><?= get_label('site_supervisor', 'Site Supervisor') ?></option>
                                <option value="quantity_approver"><?= get_label('quantity_approver', 'Quantity Approver') ?></option>
                                <option value="accountant"><?= get_label('accountant', 'Accountant') ?></option>
                                <option value="reviewer"><?= get_label('reviewer', 'Reviewer') ?></option>
                                <option value="final_approver"><?= get_label('final_approver', 'Final Approver') ?></option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label"><?= get_label('sequence', 'Sequence') ?></label>
                            <input type="number" class="form-control" name="workflow_steps[${stepCount}][sequence]" value="${stepCount}" min="1">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label"><?= get_label('description', 'Description') ?></label>
                        <textarea class="form-control" name="workflow_steps[${stepCount}][description]" rows="2" placeholder="<?= get_label('enter_step_description', 'Enter step description') ?>"></textarea>
                    </div>
                </div>
            `;
            $('#workflowStepsContainer').append(stepHtml);
        });
        
        // Remove workflow step
        $(document).on('click', '.remove-step', function() {
            $(this).closest('.workflow-step').remove();
            // Renumber steps
            $('.workflow-step').each(function(index) {
                const stepNumber = index + 1;
                $(this).find('h6').text('<?= get_label('step', 'Step') ?> ' + stepNumber);
                $(this).find('input[name$="[sequence]"]').val(stepNumber);
            });
        });
        
        // Form submission
        $('#createTemplateForm').on('submit', function(e) {
            e.preventDefault();
            
            const formData = $(this).serialize();
            
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                beforeSend: function() {
                    $('button[type="submit"]').prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i> <?= get_label('creating', 'Creating') ?>...');
                },
                success: function(response) {
                    if (!response.error) {
                        toastr.success('<?= get_label('template_created_successfully', 'Template created successfully') ?>');
                        setTimeout(() => {
                            window.location.href = '{{ route('templates.index') }}';
                        }, 1500);
                    } else {
                        toastr.error(response.message);
                        $('button[type="submit"]').prop('disabled', false).html('<i class="bx bx-save"></i> <?= get_label('create_template', 'Create Template') ?>');
                    }
                },
                error: function(xhr) {
                    toastr.error('<?= get_label('something_went_wrong', 'Something went wrong') ?>');
                    $('button[type="submit"]').prop('disabled', false).html('<i class="bx bx-save"></i> <?= get_label('create_template', 'Create Template') ?>');
                }
            });
        });
    });
</script>
@endpush