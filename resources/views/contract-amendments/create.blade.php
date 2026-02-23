@extends('layout')
@section('title')
    <?= get_label('request_contract_amendment', 'Request Contract Amendment') ?>
@endsection
@section('content')
    @php
    $menu = 'contracts';
    $sub_menu = 'contract-amendments';
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
                        <li class="breadcrumb-item active"><?= get_label('request_amendment', 'Request Amendment') ?></li>
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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><?= get_label('request_amendment_for_contract', 'Request Amendment for Contract') ?>: {{ $contract->title }}</h5>
                    </div>
                    <div class="card-body">
                        <form id="contractAmendmentForm">
                            @csrf
                            <input type="hidden" name="contract_id" value="{{ $contract->id }}">

                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label"><?= get_label('contract_title', 'Contract Title') ?></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{{ $contract->title }}" readonly>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label"><?= get_label('amendment_type', 'Amendment Type') ?><span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="amendment_type" class="form-control" required>
                                        <option value=""><?= get_label('select_amendment_type', 'Select Amendment Type') ?></option>
                                        <option value="price"><?= get_label('price_change', 'Price Change') ?></option>
                                        <option value="quantity"><?= get_label('quantity_change', 'Quantity Change') ?></option>
                                        <option value="specification"><?= get_label('specification_change', 'Specification Change') ?></option>
                                        <option value="duration"><?= get_label('duration_change', 'Duration Change') ?></option>
                                        <option value="other"><?= get_label('other', 'Other') ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label"><?= get_label('request_reason', 'Request Reason') ?><span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <textarea name="request_reason" class="form-control" required></textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-label"><?= get_label('details', 'Details') ?></label>
                                <div class="col-sm-10">
                                    <textarea name="details" class="form-control"></textarea>
                                </div>
                            </div>

                            <!-- Dynamic fields based on amendment type -->
                            <div class="form-group row amendment-field" id="original-price-field" style="display:none;">
                                <label class="col-sm-2 col-form-label"><?= get_label('original_price', 'Original Price') ?></label>
                                <div class="col-sm-10">
                                    <input type="number" name="original_price" class="form-control" step="0.01">
                                </div>
                            </div>

                            <div class="form-group row amendment-field" id="new-price-field" style="display:none;">
                                <label class="col-sm-2 col-form-label"><?= get_label('new_price', 'New Price') ?></label>
                                <div class="col-sm-10">
                                    <input type="number" name="new_price" class="form-control" step="0.01">
                                </div>
                            </div>

                            <div class="form-group row amendment-field" id="original-quantity-field" style="display:none;">
                                <label class="col-sm-2 col-form-label"><?= get_label('original_quantity', 'Original Quantity') ?></label>
                                <div class="col-sm-10">
                                    <input type="number" name="original_quantity" class="form-control" step="0.01">
                                </div>
                            </div>

                            <div class="form-group row amendment-field" id="new-quantity-field" style="display:none;">
                                <label class="col-sm-2 col-form-label"><?= get_label('new_quantity', 'New Quantity') ?></label>
                                <div class="col-sm-10">
                                    <input type="number" name="new_quantity" class="form-control" step="0.01">
                                </div>
                            </div>

                            <div class="form-group row amendment-field" id="original-unit-field" style="display:none;">
                                <label class="col-sm-2 col-form-label"><?= get_label('original_unit', 'Original Unit') ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="original_unit" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row amendment-field" id="new-unit-field" style="display:none;">
                                <label class="col-sm-2 col-form-label"><?= get_label('new_unit', 'New Unit') ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="new_unit" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row amendment-field" id="original-description-field" style="display:none;">
                                <label class="col-sm-2 col-form-label"><?= get_label('original_description', 'Original Description') ?></label>
                                <div class="col-sm-10">
                                    <textarea name="original_description" class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="form-group row amendment-field" id="new-description-field" style="display:none;">
                                <label class="col-sm-2 col-form-label"><?= get_label('new_description', 'New Description') ?></label>
                                <div class="col-sm-10">
                                    <textarea name="new_description" class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-primary"><?= get_label('submit_amendment_request', 'Submit Amendment Request') ?></button>
                                    <a href="{{ route('contracts.show', $contract->id) }}" class="btn btn-secondary"><?= get_label('cancel', 'Cancel') ?></a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('assets/js/pages/contract-amendments.js') }}"></script>
    @endpush
@endsection