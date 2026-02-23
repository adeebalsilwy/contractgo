@extends('layout')
@section('title')
    <?= get_label('approve_contract_amendment', 'Approve Contract Amendment') ?>
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
                            <a href="{{ route('contract-amendments.index') }}"><?= get_label('contract_amendments', 'Contract Amendments') ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?= get_label('approve', 'Approve') ?></li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('contract-amendments.index') }}">
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
                        <h5><?= get_label('review_amendment_request', 'Review Amendment Request') ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h6><?= get_label('amendment_details', 'Amendment Details') ?></h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong><?= get_label('amendment_type', 'Amendment Type') ?>:</strong></td>
                                    <td>{{ ucfirst(str_replace('_', ' ', get_label($amendment->amendment_type, $amendment->amendment_type))) }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('request_reason', 'Request Reason') ?>:</strong></td>
                                    <td>{{ $amendment->request_reason }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('details', 'Details') ?>:</strong></td>
                                    <td>{{ $amendment->details ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('contract', 'Contract') ?>:</strong></td>
                                    <td>{{ $amendment->contract->title }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('requested_by', 'Requested By') ?>:</strong></td>
                                    <td>{{ $amendment->requestedBy->first_name ?? 'N/A' }} {{ $amendment->requestedBy->last_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('requested_at', 'Requested At') ?>:</strong></td>
                                    <td>{{ format_date($amendment->created_at, true) }}</td>
                                </tr>
                            </table>
                        </div>

                        <form id="amendmentApprovalForm">
                            @csrf
                            @method('PUT')

                            <div class="form-group mb-3">
                                <label for="status"><?= get_label('status', 'Status') ?><span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value=""><?= get_label('select_status', 'Select Status') ?></option>
                                    <option value="approved"><?= get_label('approve', 'Approve') ?></option>
                                    <option value="rejected"><?= get_label('reject', 'Reject') ?></option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="approval_comments"><?= get_label('approval_comments', 'Approval Comments') ?></label>
                                <textarea name="approval_comments" id="approval_comments" class="form-control" rows="4" placeholder="<?= get_label('enter_approval_or_rejection_comments', 'Enter approval or rejection comments...') ?>"></textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label for="signature"><?= get_label('electronic_signature', 'Electronic Signature') ?></label>
                                <div id="signature-pad" class="signature-pad">
                                    <canvas></canvas>
                                </div>
                                <input type="hidden" name="signature_data" id="signature_data">
                                <div class="mt-2">
                                    <button type="button" class="btn btn-secondary" id="clear-signature"><?= get_label('clear', 'Clear') ?></button>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-success"><?= get_label('submit_decision', 'Submit Decision') ?></button>
                                <a href="{{ route('contract-amendments.index') }}" class="btn btn-secondary ml-2"><?= get_label('cancel', 'Cancel') ?></a>
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