@extends('layouts.app')

@section('title')
    <?= get_label('edit_template', 'Edit Template') ?>
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
                        <li class="breadcrumb-item active"><?= get_label('edit_template', 'Edit Template') ?></li>
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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bx bx-edit"></i> <?= get_label('edit_template', 'Edit Template') ?>: {{ $template->name }}
                        </h5>
                        <div class="badge bg-{{ $template->status === 'active' ? 'success' : ($template->status === 'draft' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($template->status) }}
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="editTemplateForm" method="POST" action="{{ route('templates.update', $template->id) }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="templateType" class="form-label"><?= get_label('template_type', 'Template Type') ?> *</label>
                                    <select class="form-select" id="templateType" name="type" required disabled>
                                        <option value="workflow" {{ $template->type === 'workflow' ? 'selected' : '' }}><?= get_label('workflow_template', 'Workflow Template') ?></option>
                                        <option value="contract" {{ $template->type === 'contract' ? 'selected' : '' }}><?= get_label('contract_template', 'Contract Template') ?></option>
                                        <option value="extract" {{ $template->type === 'extract' ? 'selected' : '' }}><?= get_label('extract_template', 'Extract Template') ?></option>
                                    </select>
                                    <input type="hidden" name="type" value="{{ $template->type }}">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="templateName" class="form-label"><?= get_label('template_name', 'Template Name') ?> *</label>
                                    <input type="text" class="form-control" id="templateName" name="name" value="{{ old('name', $template->name) }}" required>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="templateStatus" class="form-label"><?= get_label('status', 'Status') ?> *</label>
                                    <select class="form-select" id="templateStatus" name="status" required>
                                        <option value="draft" {{ old('status', $template->status) == 'draft' ? 'selected' : '' }}><?= get_label('draft', 'Draft') ?></option>
                                        <option value="active" {{ old('status', $template->status) == 'active' ? 'selected' : '' }}><?= get_label('active', 'Active') ?></option>
                                        <option value="archived" {{ old('status', $template->status) == 'archived' ? 'selected' : '' }}><?= get_label('archived', 'Archived') ?></option>
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
                                            <option value="{{ $workspace->id }}" {{ old('workspace_id', $template->workspace_id) == $workspace->id ? 'selected' : '' }}>{{ $workspace->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('workspace_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="templateDescription" class="form-label"><?= get_label('description', 'Description') ?></label>
                                <textarea class="form-control" id="templateDescription" name="description" rows="3">{{ old('description', $template->description) }}</textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="templateContent" class="form-label"><?= get_label('template_content', 'Template Content') ?> *</label>
                                <textarea class="form-control" id="templateContent" name="content" rows="15" required>{{ old('content', $template->content) }}</textarea>
                                <div class="form-text">
                                    <?= get_label('template_variables_help', 'Use variables like {contract_number}, {client_name}, {current_date} in your template content.') ?>
                                </div>
                                @error('content')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="isDefault" name="is_default" value="1" {{ old('is_default', $template->is_default) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isDefault">
                                        <?= get_label('set_as_default', 'Set as Default Template') ?>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Template Preview -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="bx bx-show"></i> <?= get_label('template_preview', 'Template Preview') ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="preview-content" style="min-height: 200px; background: #f8f9fa; padding: 15px; border-radius: 5px; border: 1px solid #dee2e6;">
                                        <!-- Preview will be updated dynamically -->
                                    </div>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="updatePreview">
                                            <i class="bx bx-refresh"></i> <?= get_label('update_preview', 'Update Preview') ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Workflow Template Specific Fields -->
                            @if($template->type === 'workflow')
                            <div id="workflowTemplateFields">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h6 class="mb-0"><?= get_label('workflow_steps', 'Workflow Steps') ?></h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="workflowStepsContainer">
                                            @if($template->workflow_steps)
                                                @foreach($template->workflow_steps as $index => $step)
                                                    <div class="workflow-step mb-3 p-3 border rounded">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <h6 class="mb-0"><?= get_label('step', 'Step') ?> {{ $index + 1 }}</h6>
                                                            <button type="button" class="btn btn-sm btn-outline-danger remove-step">
                                                                <i class="bx bx-trash"></i>
                                                            </button>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4 mb-2">
                                                                <label class="form-label"><?= get_label('step_name', 'Step Name') ?></label>
                                                                <input type="text" class="form-control" name="workflow_steps[{{ $index }}][name]" value="{{ $step['name'] ?? '' }}" placeholder="<?= get_label('enter_step_name', 'Enter step name') ?>">
                                                            </div>
                                                            <div class="col-md-4 mb-2">
                                                                <label class="form-label"><?= get_label('role', 'Role') ?></label>
                                                                <select class="form-select" name="workflow_steps[{{ $index }}][role]">
                                                                    <option value="site_supervisor" {{ ($step['role'] ?? '') == 'site_supervisor' ? 'selected' : '' }}><?= get_label('site_supervisor', 'Site Supervisor') ?></option>
                                                                    <option value="quantity_approver" {{ ($step['role'] ?? '') == 'quantity_approver' ? 'selected' : '' }}><?= get_label('quantity_approver', 'Quantity Approver') ?></option>
                                                                    <option value="accountant" {{ ($step['role'] ?? '') == 'accountant' ? 'selected' : '' }}><?= get_label('accountant', 'Accountant') ?></option>
                                                                    <option value="reviewer" {{ ($step['role'] ?? '') == 'reviewer' ? 'selected' : '' }}><?= get_label('reviewer', 'Reviewer') ?></option>
                                                                    <option value="final_approver" {{ ($step['role'] ?? '') == 'final_approver' ? 'selected' : '' }}><?= get_label('final_approver', 'Final Approver') ?></option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4 mb-2">
                                                                <label class="form-label"><?= get_label('sequence', 'Sequence') ?></label>
                                                                <input type="number" class="form-control" name="workflow_steps[{{ $index }}][sequence]" value="{{ $step['sequence'] ?? ($index + 1) }}" min="1">
                                                            </div>
                                                        </div>
                                                        <div class="mb-2">
                                                            <label class="form-label"><?= get_label('description', 'Description') ?></label>
                                                            <textarea class="form-control" name="workflow_steps[{{ $index }}][description]" rows="2" placeholder="<?= get_label('enter_step_description', 'Enter step description') }}">{{ $step['description'] ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
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
                            @endif
                            
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('templates.index') }}" class="btn btn-secondary">
                                    <?= get_label('cancel', 'Cancel') ?>
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save"></i> <?= get_label('update_template', 'Update Template') ?>
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
        // Initialize preview
        updatePreview();
        
        // Update preview on content change
        $('#templateContent').on('input', function() {
            updatePreview();
        });
        
        // Manual preview update
        $('#updatePreview').on('click', function() {
            updatePreview();
        });
        
        // Add workflow step functionality
        $('#addWorkflowStep').on('click', function() {
            const stepCount = $('#workflowStepsContainer .workflow-step').length;
            const stepIndex = stepCount;
            const stepHtml = `
                <div class="workflow-step mb-3 p-3 border rounded">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0"><?= get_label('step', 'Step') ?> ${stepCount + 1}</h6>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-step">
                            <i class="bx bx-trash"></i>
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label class="form-label"><?= get_label('step_name', 'Step Name') ?></label>
                            <input type="text" class="form-control" name="workflow_steps[${stepIndex}][name]" placeholder="<?= get_label('enter_step_name', 'Enter step name') ?>">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label"><?= get_label('role', 'Role') ?></label>
                            <select class="form-select" name="workflow_steps[${stepIndex}][role]">
                                <option value="site_supervisor"><?= get_label('site_supervisor', 'Site Supervisor') ?></option>
                                <option value="quantity_approver"><?= get_label('quantity_approver', 'Quantity Approver') ?></option>
                                <option value="accountant"><?= get_label('accountant', 'Accountant') ?></option>
                                <option value="reviewer"><?= get_label('reviewer', 'Reviewer') ?></option>
                                <option value="final_approver"><?= get_label('final_approver', 'Final Approver') ?></option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label"><?= get_label('sequence', 'Sequence') ?></label>
                            <input type="number" class="form-control" name="workflow_steps[${stepIndex}][sequence]" value="${stepCount + 1}" min="1">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label"><?= get_label('description', 'Description') ?></label>
                        <textarea class="form-control" name="workflow_steps[${stepIndex}][description]" rows="2" placeholder="<?= get_label('enter_step_description', 'Enter step description') ?>"></textarea>
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
        $('#editTemplateForm').on('submit', function(e) {
            e.preventDefault();
            
            const formData = $(this).serialize();
            
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                beforeSend: function() {
                    $('button[type="submit"]').prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i> <?= get_label('updating', 'Updating') ?>...');
                },
                success: function(response) {
                    if (!response.error) {
                        toastr.success('<?= get_label('template_updated_successfully', 'Template updated successfully') ?>');
                        setTimeout(() => {
                            window.location.href = '{{ route('templates.index') }}';
                        }, 1500);
                    } else {
                        toastr.error(response.message);
                        $('button[type="submit"]').prop('disabled', false).html('<i class="bx bx-save"></i> <?= get_label('update_template', 'Update Template') ?>');
                    }
                },
                error: function(xhr) {
                    toastr.error('<?= get_label('something_went_wrong', 'Something went wrong') ?>');
                    $('button[type="submit"]').prop('disabled', false).html('<i class="bx bx-save"></i> <?= get_label('update_template', 'Update Template') ?>');
                }
            });
        });
        
        // Update preview function
        function updatePreview() {
            const content = $('#templateContent').val();
            const previewContent = content.replace(/\{([^}]+)\}/g, '<span class="badge bg-primary">$&</span>');
            $('.preview-content').html(previewContent || '<em><?= get_label('no_content_to_preview', 'No content to preview') ?></em>');
        }
    });
</script>
@endpush