@extends('layout')
@section('title')
    <?= get_label('create_contract_obligation', 'Create Contract Obligation') ?>
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
                        <li class="breadcrumb-item active"><?= get_label('create', 'Create') ?></li>
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
                        <h5><?= get_label('add_new_contract_obligation', 'Add New Contract Obligation') ?></h5>
                    </div>
                    <div class="card-body">
                        <form id="contractObligationForm" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= get_label('contract', 'Contract') ?><span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="contract_id" name="contract_id" required>
                                        <option value=""><?= get_label('select_contract', 'Select Contract') ?></option>
                                        @foreach($contracts as $contract)
                                            <option value="{{ $contract->id }}" 
                                                @if(isset($contract) && $contract->id == old('contract_id')) selected @endif>
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
                                            <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= get_label('party_type', 'Party Type') ?><span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="party_type" name="party_type" required>
                                        <option value=""><?= get_label('select_party_type', 'Select Party Type') ?></option>
                                        <option value="client"><?= get_label('client', 'Client') ?></option>
                                        <option value="contractor"><?= get_label('contractor', 'Contractor') ?></option>
                                        <option value="consultant"><?= get_label('consultant', 'Consultant') ?></option>
                                        <option value="supervisor"><?= get_label('supervisor', 'Supervisor') ?></option>
                                        <option value="other"><?= get_label('other', 'Other') ?></option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= get_label('title', 'Title') ?><span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= get_label('obligation_type', 'Obligation Type') ?><span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="obligation_type" name="obligation_type" required>
                                        <option value=""><?= get_label('select_type', 'Select Type') ?></option>
                                        <option value="payment"><?= get_label('payment', 'Payment') ?></option>
                                        <option value="delivery"><?= get_label('delivery', 'Delivery') ?></option>
                                        <option value="performance"><?= get_label('performance', 'Performance') ?></option>
                                        <option value="compliance"><?= get_label('compliance', 'Compliance') ?></option>
                                        <option value="reporting"><?= get_label('reporting', 'Reporting') ?></option>
                                        <option value="other"><?= get_label('other', 'Other') ?></option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= get_label('priority', 'Priority') ?><span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="priority" name="priority" required>
                                        <option value=""><?= get_label('select_priority', 'Select Priority') ?></option>
                                        <option value="low"><?= get_label('low', 'Low') ?></option>
                                        <option value="medium"><?= get_label('medium', 'Medium') ?></option>
                                        <option value="high"><?= get_label('high', 'High') ?></option>
                                        <option value="critical"><?= get_label('critical', 'Critical') ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= get_label('due_date', 'Due Date') ?></label>
                                    <input type="date" name="due_date" class="form-control">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= get_label('assigned_to', 'Assigned To') ?></label>
                                    <select class="form-select select2" id="assigned_to" name="assigned_to">
                                        <option value=""><?= get_label('select_assignee', 'Select Assignee') ?></option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label"><?= get_label('description', 'Description') ?></label>
                                    <textarea name="description" class="form-control" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label"><?= get_label('notes', 'Notes') ?></label>
                                    <textarea name="notes" class="form-control" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label"><?= get_label('supporting_documents', 'Supporting Documents') ?></label>
                                    <input type="file" name="supporting_documents[]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="text-muted"><?= get_label('multiple_files_allowed_pdf_jpg_png_max_10mb_each', 'Multiple files allowed (PDF, JPG, PNG, max 10MB each)') ?></small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary me-2"><?= get_label('create_obligation', 'Create Obligation') ?></button>
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
                    url: '{{ route('contract-obligations.store') }}',
                    type: 'POST',
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