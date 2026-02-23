@extends('layout')
@section('title')
    <?= get_label('create_contract_quantity', 'Create Contract Quantity') ?>
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
                        <li class="breadcrumb-item active"><?= get_label('create', 'Create') ?></li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('contract-quantities.index') }}">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('back_to_list', 'Back to List') ?>">
                        <i class='bx bx-arrow-back'></i>
                    </button>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><?= get_label('add_new_contract_quantity', 'Add New Contract Quantity') ?></h5>
                    </div>
                    <div class="card-body">
                        <form id="contractQuantityForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="contract_id" value="{{ $contract->id }}">

                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label"><?= get_label('contract', 'Contract') ?><span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{{ $contract->title }}" readonly>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label"><?= get_label('item_description', 'Item Description') ?><span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="item_description" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label"><?= get_label('requested_quantity', 'Requested Quantity') ?><span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="number" name="requested_quantity" class="form-control" min="0" step="0.01" required>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label"><?= get_label('unit', 'Unit') ?><span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="unit" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label"><?= get_label('unit_price', 'Unit Price') ?></label>
                                <div class="col-sm-10">
                                    <input type="number" name="unit_price" class="form-control" min="0" step="0.01">
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label"><?= get_label('notes', 'Notes') ?></label>
                                <div class="col-sm-10">
                                    <textarea name="notes" class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label"><?= get_label('supporting_documents', 'Supporting Documents') ?></label>
                                <div class="col-sm-10">
                                    <input type="file" name="supporting_documents[]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="text-muted"><?= get_label('multiple_files_allowed_pdf_jpg_png_max_10mb_each', 'Multiple files allowed (PDF, JPG, PNG, max 10MB each)') ?></small>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-primary"><?= get_label('submit_quantity', 'Submit Quantity') ?></button>
                                    <a href="{{ route('contract-quantities.index') }}" class="btn btn-secondary"><?= get_label('cancel', 'Cancel') ?></a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('assets/js/pages/contract-quantities.js') }}"></script>
    @endpush
@endsection