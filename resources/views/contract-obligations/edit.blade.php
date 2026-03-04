@extends('layout')
@section('title')
    <?= get_label('edit_contract_obligation', 'Edit Contract Obligation') ?>
@endsection
@section('content')
    @php
    $menu = 'contracts';
    $sub_menu = 'contract-obligations';
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
                            <a href="{{ route('contract-obligations.index') }}"><?= get_label('contract_obligations', 'Contract Obligations') ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?= get_label('edit', 'Edit') ?></li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('contract-obligations.index') }}">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('back_to_list', 'Back to List') ?>">
                        <i class='bx bx-arrow-back'></i> <?= get_label('back', 'Back') ?>
                    </button>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><?= get_label('update_contract_obligation', 'Update Contract Obligation') ?></h5>
                    </div>
                    <div class="card-body">
                        <form id="contractObligationForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= get_label('contract', 'Contract') ?><span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="contract_id" name="contract_id" required>
                                        <option value=""><?= get_label('select_contract', 'Select Contract') ?></option>
                                        @foreach($contracts as $contract)
                                            <option value="{{ $contract->id }}" 
                                                {{ $obligation->contract_id == $contract->id ? 'selected' : '' }}>
                                                {{ $contract->title }} ({{ $contract->contract_number }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= get_label('party', 'Party') ?><span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="party_id" name="party_id" required>
                                        <option value=""><?= get_label('select_party', 'Select Party') ?></option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" 
                                                {{ $obligation->party_id == $user->id ? 'selected' : '' }}>
                                                {{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= get_label('party_type', 'Party Type') ?><span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="party_type" name="party_type" required>
                                        <option value=""><?= get_label('select_party_type', 'Select Party Type') ?></option>
                                        <option value="client" {{ $obligation->party_type == 'client' ? 'selected' : '' }}><?= get_label('client', 'Client') ?></option>
                                        <option value="contractor" {{ $obligation->party_type == 'contractor' ? 'selected' : '' }}><?= get_label('contractor', 'Contractor') ?></option>
                                        <option value="consultant" {{ $obligation->party_type == 'consultant' ? 'selected' : '' }}><?= get_label('consultant', 'Consultant') ?></option>
                                        <option value="supervisor" {{ $obligation->party_type == 'supervisor' ? 'selected' : '' }}><?= get_label('supervisor', 'Supervisor') ?></option>
                                        <option value="other" {{ $obligation->party_type == 'other' ? 'selected' : '' }}><?= get_label('other', 'Other') ?></option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= get_label('title', 'Title') ?><span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" value="{{ $obligation->title }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= get_label('obligation_type', 'Obligation Type') ?><span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="obligation_type" name="obligation_type" required>
                                        <option value=""><?= get_label('select_type', 'Select Type') ?></option>
                                        <option value="payment" {{ $obligation->obligation_type == 'payment' ? 'selected' : '' }}><?= get_label('payment', 'Payment') ?></option>
                                        <option value="delivery" {{ $obligation->obligation_type == 'delivery' ? 'selected' : '' }}><?= get_label('delivery', 'Delivery') ?></option>
                                        <option value="performance" {{ $obligation->obligation_type == 'performance' ? 'selected' : '' }}><?= get_label('performance', 'Performance') ?></option>
                                        <option value="compliance" {{ $obligation->obligation_type == 'compliance' ? 'selected' : '' }}><?= get_label('compliance', 'Compliance') ?></option>
                                        <option value="reporting" {{ $obligation->obligation_type == 'reporting' ? 'selected' : '' }}><?= get_label('reporting', 'Reporting') ?></option>
                                        <option value="other" {{ $obligation->obligation_type == 'other' ? 'selected' : '' }}><?= get_label('other', 'Other') ?></option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= get_label('priority', 'Priority') ?><span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="priority" name="priority" required>
                                        <option value=""><?= get_label('select_priority', 'Select Priority') ?></option>
                                        <option value="low" {{ $obligation->priority == 'low' ? 'selected' : '' }}><?= get_label('low', 'Low') ?></option>
                                        <option value="medium" {{ $obligation->priority == 'medium' ? 'selected' : '' }}><?= get_label('medium', 'Medium') ?></option>
                                        <option value="high" {{ $obligation->priority == 'high' ? 'selected' : '' }}><?= get_label('high', 'High') ?></option>
                                        <option value="critical" {{ $obligation->priority == 'critical' ? 'selected' : '' }}><?= get_label('critical', 'Critical') ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= get_label('status', 'Status') ?><span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="status" name="status" required>
                                        <option value=""><?= get_label('select_status', 'Select Status') ?></option>
                                        <option value="pending" {{ $obligation->status == 'pending' ? 'selected' : '' }}><?= get_label('pending', 'Pending') ?></option>
                                        <option value="in_progress" {{ $obligation->status == 'in_progress' ? 'selected' : '' }}><?= get_label('in_progress', 'In Progress') ?></option>
                                        <option value="completed" {{ $obligation->status == 'completed' ? 'selected' : '' }}><?= get_label('completed', 'Completed') ?></option>
                                        <option value="overdue" {{ $obligation->status == 'overdue' ? 'selected' : '' }}><?= get_label('overdue', 'Overdue') ?></option>
                                        <option value="cancelled" {{ $obligation->status == 'cancelled' ? 'selected' : '' }}><?= get_label('cancelled', 'Cancelled') ?></option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= get_label('due_date', 'Due Date') ?></label>
                                    <input type="date" name="due_date" class="form-control" value="{{ $obligation->due_date ? $obligation->due_date->format('Y-m-d') : '' }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= get_label('completed_date', 'Completed Date') ?></label>
                                    <input type="date" name="completed_date" class="form-control" value="{{ $obligation->completed_date ? $obligation->completed_date->format('Y-m-d') : '' }}">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= get_label('assigned_to', 'Assigned To') ?></label>
                                    <select class="form-select select2" id="assigned_to" name="assigned_to">
                                        <option value=""><?= get_label('select_assignee', 'Select Assignee') ?></option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" 
                                                {{ $obligation->assigned_to == $user->id ? 'selected' : '' }}>
                                                {{ $user->first_name }} {{ $user->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label"><?= get_label('description', 'Description') ?></label>
                                    <textarea name="description" class="form-control" rows="3">{{ $obligation->description }}</textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label"><?= get_label('notes', 'Notes') ?></label>
                                    <textarea name="notes" class="form-control" rows="3">{{ $obligation->notes }}</textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label"><?= get_label('supporting_documents', 'Supporting Documents') ?></label>
                                    <input type="file" name="supporting_documents[]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="text-muted"><?= get_label('multiple_files_allowed_pdf_jpg_png_max_10mb_each', 'Multiple files allowed (PDF, JPG, PNG, max 10MB each)') ?></small>
                                    
                                    @if($obligation->supporting_documents)
                                    <div class="mt-2">
                                        <strong><?= get_label('existing_documents', 'Existing Documents') ?>:</strong>
                                        <div class="d-flex flex-wrap gap-2 mt-2">
                                            @foreach($obligation->supporting_documents as $docIndex => $document)
                                                <a href="{{ asset('storage/' . $document) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                    <i class="bx bx-file"></i> {{ basename($document) }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary me-2"><?= get_label('update_obligation', 'Update Obligation') ?></button>
                                        <a href="{{ route('contract-obligations.index') }}" class="btn btn-secondary"><?= get_label('cancel', 'Cancel') ?></a>
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
        $(document).ready(function() {
            // Initialize select2 if available
            if($('.select2').length) {
                $('.select2').select2({
                    placeholder: '<?= get_label('select_option', 'Select Option') ?>',
                    allowClear: true
                });
            }
            
            // Form validation and submission handling
            $('#contractObligationForm').on('submit', function(e) {
                e.preventDefault();
                
                var formData = new FormData(this);
                
                $.ajax({
                    url: '{{ route('contract-obligations.update', $obligation->id) }}',
                    type: 'POST', // Using POST method with _method PUT
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.error) {
                            showToast('<?= get_label('error', 'Error') ?>', response.message, 'error');
                        } else {
                            showToast('<?= get_label('success', 'Success') ?>', response.message, 'success');
                            setTimeout(function() {
                                window.location.href = '{{ route('contract-obligations.index') }}';
                            }, 2000);
                        }
                    },
                    error: function(xhr) {
                        var errorMessage = '<?= get_label('something_went_wrong', 'Something went wrong') ?>';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showToast('<?= get_label('error', 'Error') ?>', errorMessage, 'error');
                    }
                });
            });
        });
    </script>
    @endpush
@endsection