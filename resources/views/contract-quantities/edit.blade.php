@extends('layout')
@section('title')
    <?= get_label('edit_contract_quantity', 'Edit Contract Quantity') ?>
@endsection
@section('content')
    @php
    $menu = 'contracts';
    $sub_menu = 'contract-quantities';
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
                            <a href="{{ route('contract-quantities.index') }}"><?= get_label('contract_quantities', 'Contract Quantities') ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?= get_label('edit', 'Edit') ?></li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('contract-quantities.index') }}">
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
                        <h5><?= get_label('update_contract_quantity', 'Update Contract Quantity') ?></h5>
                    </div>
                    <div class="card-body">
                        <form id="contractQuantityForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= get_label('contract', 'Contract') ?></label>
                                    <input type="text" class="form-control" value="{{ $contract->title }}" readonly>
                                    <small class="text-muted">Client: {{ $contract->client->first_name ?? 'N/A' }} {{ $contract->client->last_name ?? '' }}</small>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= get_label('item', 'Item') ?></label>
                                    <select class="form-select select2" id="item_id" name="item_id" onchange="fillItemDetails(this.value)">
                                        <option value=""><?= get_label('select_item', 'Select Item') ?></option>
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}" 
                                                data-description="{{ $item->title }}" 
                                                data-unit="{{ $item->unit->title ?? '' }}" 
                                                data-price="{{ $item->price }}"
                                                {{ $item->id == $contractQuantity->item_id ? 'selected' : '' }}>
                                                {{ $item->title }} ({{ $item->unit->title ?? 'N/A' }}) - {{ format_currency($item->price) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= get_label('item_description', 'Item Description') ?><span class="text-danger">*</span></label>
                                    <input type="text" name="item_description" id="item_description" class="form-control" value="{{ $contractQuantity->item_description }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= get_label('requested_quantity', 'Requested Quantity') ?><span class="text-danger">*</span></label>
                                    <input type="number" name="requested_quantity" id="requested_quantity" class="form-control" value="{{ $contractQuantity->requested_quantity }}" min="0" step="0.01" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= get_label('unit', 'Unit') ?><span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="unit" name="unit" required>
                                        <option value=""><?= get_label('select_unit', 'Select Unit') ?></option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->title }}" {{ $unit->title == $contractQuantity->unit ? 'selected' : '' }}>{{ $unit->title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= get_label('unit_price', 'Unit Price') ?></label>
                                    <input type="number" name="unit_price" id="unit_price" class="form-control" value="{{ $contractQuantity->unit_price }}" min="0" step="0.01">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label"><?= get_label('notes', 'Notes') ?></label>
                                    <textarea name="notes" class="form-control">{{ $contractQuantity->notes }}</textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label"><?= get_label('supporting_documents', 'Supporting Documents') ?></label>
                                    <input type="file" name="supporting_documents[]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="text-muted"><?= get_label('multiple_files_allowed_pdf_jpg_png_max_10mb_each', 'Multiple files allowed (PDF, JPG, PNG, max 10MB each)') ?></small>
                                    
                                    @if($contractQuantity->supporting_documents)
                                    <div class="mt-2">
                                        <strong><?= get_label('existing_documents', 'Existing Documents') ?>:</strong>
                                        <div class="d-flex flex-wrap gap-2 mt-2">
                                            @foreach(json_decode($contractQuantity->supporting_documents) as $docIndex => $document)
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
                                        <button type="submit" class="btn btn-primary me-2"><?= get_label('update_quantity', 'Update Quantity') ?></button>
                                        <a href="{{ route('contract-quantities.index') }}" class="btn btn-secondary"><?= get_label('cancel', 'Cancel') ?></a>
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
                    placeholder: '<?= get_label('select_item', 'Select Item') ?>',
                    allowClear: true
                });
            }
            
            // Function to fill item details
            function fillItemDetails(itemId) {
                if(itemId) {
                    var $selectedOption = $('option:selected', '#item_id');
                    var description = $selectedOption.data('description');
                    var unit = $selectedOption.data('unit');
                    var price = $selectedOption.data('price');
                    
                    if(description) {
                        $('#item_description').val(description);
                    }
                    if(unit) {
                        $('#unit').val(unit).trigger('change');
                    }
                    if(price) {
                        $('#unit_price').val(price);
                    }
                }
            }
            
            // Form validation and submission handling
            $('#contractQuantityForm').on('submit', function(e) {
                e.preventDefault();
                
                var formData = new FormData(this);
                
                $.ajax({
                    url: '{{ route("contract-quantities.update", $contractQuantity->id) }}',
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
                                window.location.href = '{{ route("contract-quantities.index") }}';
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
            
            // Calculate total amount when quantity or price changes
            $('#requested_quantity, #unit_price').on('input', function() {
                var quantity = parseFloat($('#requested_quantity').val()) || 0;
                var price = parseFloat($('#unit_price').val()) || 0;
                var total = quantity * price;
                
                if ($('#unit_price').val() && $('#requested_quantity').val()) {
                    // You could display total amount somewhere if needed
                }
            });
        });
    </script>
    @endpush
@endsection