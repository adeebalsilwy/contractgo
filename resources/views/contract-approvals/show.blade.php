@extends('layout')
@section('title')
    <?= get_label('contract_approval', 'Contract Approval') ?> - <?= ucfirst(str_replace('_', ' ', get_label($stage, $stage))) ?>
@endsection
@section('content')
    @php
    $menu = 'contracts';
    $sub_menu = 'contract-approvals';
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
                            <a href="{{ route('contract-approvals.index') }}"><?= get_label('contract_approvals', 'Contract Approvals') ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?= get_label('approval_review', 'Approval Review') ?></li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('contract-approvals.index') }}">
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
                        <h5><?= get_label('review_contract_for', 'Review Contract for') ?> {{ ucfirst(str_replace('_', ' ', get_label($stage, $stage))) }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h6><?= get_label('contract_information', 'Contract Information') ?></h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong><?= get_label('title', 'Title') ?>:</strong></td>
                                    <td>{{ $contract->title }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('value', 'Value') ?>:</strong></td>
                                    <td>{{ $contract->value ? format_currency($contract->value) : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('start_date', 'Start Date') ?>:</strong></td>
                                    <td>{{ format_date($contract->start_date) }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('end_date', 'End Date') ?>:</strong></td>
                                    <td>{{ format_date($contract->end_date) }}</td>
                                </tr>
                                <tr>
                                    <td><strong><?= get_label('description', 'Description') ?>:</strong></td>
                                    <td>{{ $contract->description }}</td>
                                </tr>
                            </table>
                        </div>

                        <form id="approvalForm">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="comments"><?= get_label('comments', 'Comments') ?></label>
                                <textarea name="comments" id="comments" class="form-control" rows="4" placeholder="<?= get_label('enter_your_comments_here', 'Enter your comments here...') ?>"></textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label for="approval_signature"><?= get_label('electronic_signature', 'Electronic Signature') ?></label>
                                <div id="signature-pad" class="signature-pad">
                                    <canvas></canvas>
                                </div>
                                <input type="hidden" name="approval_signature" id="approval_signature">
                                <div class="mt-2">
                                    <button type="button" class="btn btn-secondary" id="clear-signature"><?= get_label('clear', 'Clear') ?></button>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-success" id="approveBtn"><?= get_label('approve', 'Approve') ?></button>
                                <button type="button" class="btn btn-danger ml-2" id="rejectBtn"><?= get_label('reject', 'Reject') ?></button>
                                <a href="{{ route('contract-approvals.index') }}" class="btn btn-secondary ml-2"><?= get_label('cancel', 'Cancel') ?></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('assets/js/pages/contract-approvals.js') }}"></script>
    @endpush
@endsection