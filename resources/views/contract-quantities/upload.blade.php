@extends('layout')
@section('title')
    <?= get_label('upload_contract_quantities', 'Upload Contract Quantities') ?>
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
                        <li class="breadcrumb-item active"><?= get_label('upload', 'Upload') ?></li>
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
                        <h5><?= get_label('upload_quantities_for_contract', 'Upload Quantities for Contract') ?>: {{ $contract->title }}</h5>
                    </div>
                    <div class="card-body">
                        <form id="bulkUploadForm">
                            @csrf
                            <input type="hidden" name="contract_id" value="{{ $contract->id }}">

                            <div class="table-responsive">
                                <table class="table table-bordered" id="quantitiesTable">
                                    <thead>
                                        <tr>
                                            <th><?= get_label('item_description', 'Item Description') ?></th>
                                            <th><?= get_label('requested_quantity', 'Requested Quantity') ?></th>
                                            <th><?= get_label('unit', 'Unit') ?></th>
                                            <th><?= get_label('unit_price', 'Unit Price') ?></th>
                                            <th><?= get_label('notes', 'Notes') ?></th>
                                            <th><?= get_label('action', 'Action') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" name="quantities[0][item_description]" class="form-control" required></td>
                                            <td><input type="number" name="quantities[0][requested_quantity]" class="form-control" min="0" step="0.01" required></td>
                                            <td><input type="text" name="quantities[0][unit]" class="form-control" required></td>
                                            <td><input type="number" name="quantities[0][unit_price]" class="form-control" min="0" step="0.01"></td>
                                            <td><textarea name="quantities[0][notes]" class="form-control"></textarea></td>
                                            <td><button type="button" class="btn btn-danger btn-sm remove-row"><?= get_label('remove', 'Remove') ?></button></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6">
                                                <button type="button" class="btn btn-success" id="addRow"><?= get_label('add_row', 'Add Row') ?></button>
                                                <button type="submit" class="btn btn-primary"><?= get_label('upload_quantities', 'Upload Quantities') ?></button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
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