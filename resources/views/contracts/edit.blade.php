@extends('layout')
@section('title')
    <?= get_label('edit_contract', 'Edit Contract') ?>
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
                            <a href="{{ route('contracts.index') }}"><?= get_label('contracts', 'Contracts') ?></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('contracts.show', $contract->id) }}"><?= get_label('contract_details', 'Contract Details') ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?= get_label('edit', 'Edit') ?></li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('contracts.show', $contract->id) }}">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('back_to_details', 'Back to Details') ?>">
                        <i class='bx bx-arrow-back'></i>
                    </button>
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-edit"></i> <?= get_label('edit_contract', 'Edit Contract') ?> - {{ $contract->title }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('contracts.update') }}" method="POST" id="contract_form">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" value="{{ $contract->id }}">
                            <div class="row">
                                <!-- Basic Contract Information -->
                                <div class="col-md-12 mb-4">
                                    <h6 class="border-bottom pb-2"><?= get_label('basic_information', 'Basic Information') ?></h6>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label"><?= get_label('title', 'Title') ?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $contract->title) }}" required>
                                    @error('title')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="value" class="form-label"><?= get_label('value', 'Value') ?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="value" name="value" value="{{ old('value', format_currency($contract->value, false, false)) }}" required placeholder="0.00">
                                    @error('value')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label"><?= get_label('start_date', 'Start Date') ?> <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date', $contract->start_date) }}" required>
                                    @error('start_date')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="end_date" class="form-label"><?= get_label('end_date', 'End Date') ?> <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date', $contract->end_date) }}" required>
                                    @error('end_date')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="client_id" class="form-label"><?= get_label('client', 'Client') ?> <span class="text-danger">*</span></label>
                                    <select class="form-select" id="client_id" name="client_id" required>
                                        <option value=""><?= get_label('select_client', 'Select Client') ?></option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ old('client_id', $contract->client_id) == $client->id ? 'selected' : '' }}>
                                                {{ $client->first_name }} {{ $client->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="project_id" class="form-label"><?= get_label('project', 'Project') ?> <span class="text-danger">*</span></label>
                                    <select class="form-select" id="project_id" name="project_id" required>
                                        <option value=""><?= get_label('select_project', 'Select Project') ?></option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ old('project_id', $contract->project_id) == $project->id ? 'selected' : '' }}>
                                                {{ $project->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('project_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="contract_type_id" class="form-label"><?= get_label('contract_type', 'Contract Type') ?> <span class="text-danger">*</span></label>
                                    <select class="form-select" id="contract_type_id" name="contract_type_id" required>
                                        <option value=""><?= get_label('select_contract_type', 'Select Contract Type') ?></option>
                                        @foreach($contractTypes as $type)
                                            <option value="{{ $type->id }}" {{ old('contract_type_id', $contract->contract_type_id) == $type->id ? 'selected' : '' }}>
                                                {{ $type->type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('contract_type_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <label for="description" class="form-label"><?= get_label('description', 'Description') ?></label>
                                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $contract->description) }}</textarea>
                                    @error('description')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Workflow Assignment Section -->
                                <div class="col-md-12 mb-4 mt-4">
                                    <h6 class="border-bottom pb-2"><?= get_label('workflow_assignments', 'Workflow Assignments') ?></h6>
                                    <p class="text-muted small"><?= get_label('workflow_assignments_description', 'Assign users to different workflow stages for contract approval process') ?></p>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="site_supervisor_id" class="form-label"><?= get_label('site_supervisor', 'Site Supervisor') ?></label>
                                    <select class="form-select" id="site_supervisor_id" name="site_supervisor_id">
                                        <option value=""><?= get_label('select_site_supervisor', 'Select Site Supervisor') ?></option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('site_supervisor_id', $contract->site_supervisor_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->first_name }} {{ $user->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('site_supervisor_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="quantity_approver_id" class="form-label"><?= get_label('quantity_approver', 'Quantity Approver') ?></label>
                                    <select class="form-select" id="quantity_approver_id" name="quantity_approver_id">
                                        <option value=""><?= get_label('select_quantity_approver', 'Select Quantity Approver') ?></option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('quantity_approver_id', $contract->quantity_approver_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->first_name }} {{ $user->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('quantity_approver_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="accountant_id" class="form-label"><?= get_label('accountant', 'Accountant') ?></label>
                                    <select class="form-select" id="accountant_id" name="accountant_id">
                                        <option value=""><?= get_label('select_accountant', 'Select Accountant') ?></option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('accountant_id', $contract->accountant_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->first_name }} {{ $user->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('accountant_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="reviewer_id" class="form-label"><?= get_label('reviewer', 'Reviewer') ?></label>
                                    <select class="form-select" id="reviewer_id" name="reviewer_id">
                                        <option value=""><?= get_label('select_reviewer', 'Select Reviewer') ?></option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('reviewer_id', $contract->reviewer_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->first_name }} {{ $user->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('reviewer_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="final_approver_id" class="form-label"><?= get_label('final_approver', 'Final Approver') ?></label>
                                    <select class="form-select" id="final_approver_id" name="final_approver_id">
                                        <option value=""><?= get_label('select_final_approver', 'Select Final Approver') ?></option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('final_approver_id', $contract->final_approver_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->first_name }} {{ $user->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('final_approver_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Workflow Status Management -->
                                <div class="col-md-12 mb-4 mt-4">
                                    <h6 class="border-bottom pb-2"><?= get_label('workflow_status', 'Workflow Status') ?></h6>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="workflow_status" class="form-label"><?= get_label('current_workflow_status', 'Current Workflow Status') ?></label>
                                    <input type="text" class="form-control" value="{{ ucfirst(str_replace('_', ' ', $contract->workflow_status)) }}" readonly>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="update_workflow_status" class="form-label"><?= get_label('update_workflow_status', 'Update Workflow Status') ?></label>
                                    <select class="form-select" id="update_workflow_status" name="update_workflow_status">
                                        <option value=""><?= get_label('no_change', 'No Change') ?></option>
                                        <option value="draft" {{ old('update_workflow_status') == 'draft' ? 'selected' : '' }}><?= get_label('draft', 'Draft') ?></option>
                                        <option value="quantity_approval" {{ old('update_workflow_status') == 'quantity_approval' ? 'selected' : '' }}><?= get_label('quantity_approval', 'Quantity Approval') ?></option>
                                        <option value="management_review" {{ old('update_workflow_status') == 'management_review' ? 'selected' : '' }}><?= get_label('management_review', 'Management Review') ?></option>
                                        <option value="accounting_processing" {{ old('update_workflow_status') == 'accounting_processing' ? 'selected' : '' }}><?= get_label('accounting_review', 'Accounting Review') ?></option>
                                        <option value="final_approval" {{ old('update_workflow_status') == 'final_approval' ? 'selected' : '' }}><?= get_label('final_approval', 'Final Approval') ?></option>
                                        <option value="approved" {{ old('update_workflow_status') == 'approved' ? 'selected' : '' }}><?= get_label('approved', 'Approved') ?></option>
                                        <option value="amendment_pending" {{ old('update_workflow_status') == 'amendment_pending' ? 'selected' : '' }}><?= get_label('amendment_pending', 'Amendment Pending') ?></option>
                                    </select>
                                    <div class="form-text"><?= get_label('workflow_status_change_warning', 'Changing workflow status will affect the contract approval process') ?></div>
                                </div>

                                <!-- Additional Settings -->
                                <div class="col-md-12 mb-4 mt-4">
                                    <h6 class="border-bottom pb-2"><?= get_label('additional_settings', 'Additional Settings') ?></h6>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_archived" name="is_archived" value="1" {{ old('is_archived', $contract->is_archived) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_archived">
                                            <?= get_label('archive_contract', 'Archive Contract') ?>
                                        </label>
                                        <div class="form-text"><?= get_label('archive_contract_help', 'Archive this contract to remove it from active workflows') ?></div>
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="col-md-12 mt-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('contracts.show', $contract->id) }}" class="btn btn-secondary">
                                            <i class="bx bx-x"></i> <?= get_label('cancel', 'Cancel') ?>
                                        </a>
                                        <button type="submit" class="btn btn-primary" id="submit_btn">
                                            <i class="bx bx-save"></i> <?= get_label('update_contract', 'Update Contract') ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Form validation and submission
        $('#contract_form').on('submit', function(e) {
            e.preventDefault();
            var submit_btn = $(this).find('#submit_btn');
            var btn_html = submit_btn.html();
            
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                beforeSend: function() {
                    submit_btn.html('<i class="bx bx-loader-alt bx-spin"></i> <?= get_label('updating', 'Updating') ?>...').attr('disabled', true);
                },
                success: function(response) {
                    if (!response.error) {
                        window.location.href = '/contracts/' + response.id;
                    } else {
                        submit_btn.html(btn_html).attr('disabled', false);
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    submit_btn.html(btn_html).attr('disabled', false);
                    var errors = xhr.responseJSON?.errors;
                    if (errors) {
                        $.each(errors, function(field, messages) {
                            $('#' + field).addClass('is-invalid');
                            $('#' + field).after('<div class="invalid-feedback d-block">' + messages[0] + '</div>');
                        });
                    } else {
                        alert('<?= get_label('something_went_wrong', 'Something went wrong') ?>');
                    }
                }
            });
        });

        // Date validation
        $('#start_date, #end_date').on('change', function() {
            var startDate = new Date($('#start_date').val());
            var endDate = new Date($('#end_date').val());
            
            if (startDate && endDate && startDate > endDate) {
                alert('<?= get_label('end_date_must_be_after_start_date', 'End date must be after start date') ?>');
                $(this).val('');
            }
        });

        // Currency formatting
        $('#value').on('input', function() {
            var value = $(this).val().replace(/[^0-9.]/g, '');
            if (value) {
                var parts = value.split('.');
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                $(this).val(parts.join('.'));
            }
        });

        // Workflow status change warning
        $('#update_workflow_status').on('change', function() {
            if ($(this).val() !== '') {
                if (!confirm('<?= get_label('workflow_status_change_confirmation', 'Are you sure you want to change the workflow status? This may affect the approval process.') ?>')) {
                    $(this).val('');
                }
            }
        });
    </script>
    @endpush
@endsection