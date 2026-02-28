@extends('layout')
@section('title')
    <?= get_label('contract_approvals', 'Contract Approvals') ?>
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
                            <a href="{{ url('contracts') }}"><?= get_label('contracts', 'Contracts') ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?= get_label('contract_approvals', 'Contract Approvals') ?></li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" id="approvalTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="true">
                                    <i class="bx bx-time"></i> <?= get_label('pending_approvals', 'Pending Approvals') ?> <span class="badge bg-warning ms-1" id="pending-count">0</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab" aria-controls="approved" aria-selected="false">
                                    <i class="bx bx-check-circle"></i> <?= get_label('approved', 'Approved') ?> <span class="badge bg-success ms-1" id="approved-count">0</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button" role="tab" aria-controls="rejected" aria-selected="false">
                                    <i class="bx bx-x-circle"></i> <?= get_label('rejected', 'Rejected') ?> <span class="badge bg-danger ms-1" id="rejected-count">0</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="archived-tab" data-bs-toggle="tab" data-bs-target="#archived" type="button" role="tab" aria-controls="archived" aria-selected="false">
                                    <i class="bx bx-archive"></i> <?= get_label('archived', 'Archived') ?> <span class="badge bg-secondary ms-1" id="archived-count">0</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="false">
                                    <i class="bx bx-list-ul"></i> <?= get_label('all_approvals', 'All Approvals') ?> <span class="badge bg-primary ms-1" id="all-count">0</span>
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="approvalTabContent">
                            <!-- Pending Approvals Tab -->
                            <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0"><?= get_label('pending_approvals', 'Pending Approvals') ?></h5>
                                    <div class="d-flex gap-2">
                                        <input type="text" id="pending-search" class="form-control form-control-sm" placeholder="<?= get_label('search', 'Search') ?>...">
                                        <select id="pending-stage-filter" class="form-control form-control-sm">
                                            <option value=""><?= get_label('all_stages', 'All Stages') ?></option>
                                            <option value="quantity_approval"><?= get_label('quantity_approval', 'Quantity Approval') ?></option>
                                            <option value="management_review"><?= get_label('management_review', 'Management Review') ?></option>
                                            <option value="accounting_review"><?= get_label('accounting_review', 'Accounting Review') ?></option>
                                            <option value="final_approval"><?= get_label('final_approval', 'Final Approval') ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped" id="pending-approvals-table" data-toggle="table" data-url="{{ route('contract-approvals.list') }}" data-query-params="pendingQueryParams" data-pagination="true" data-side-pagination="server" data-page-list="[10, 25, 50, 100]" data-search="false">
                                        <thead>
                                            <tr>
                                                <th data-field="id" data-sortable="true">ID</th>
                                                <th data-field="contract" data-sortable="true"><?= get_label('contract', 'Contract') ?></th>
                                                <th data-field="approval_stage" data-sortable="true"><?= get_label('approval_stage', 'Approval Stage') ?></th>
                                                <th data-field="approver" data-sortable="true"><?= get_label('approver', 'Approver') ?></th>
                                                <th data-field="submitted_at" data-sortable="true"><?= get_label('submitted_at', 'Submitted At') ?></th>
                                                <th data-field="actions"><?= get_label('actions', 'Actions') ?></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                            <!-- Approved Approvals Tab -->
                            <div class="tab-pane fade" id="approved" role="tabpanel" aria-labelledby="approved-tab">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0"><?= get_label('approved_approvals', 'Approved Approvals') ?></h5>
                                    <div class="d-flex gap-2">
                                        <input type="text" id="approved-search" class="form-control form-control-sm" placeholder="<?= get_label('search', 'Search') ?>...">
                                        <select id="approved-stage-filter" class="form-control form-control-sm">
                                            <option value=""><?= get_label('all_stages', 'All Stages') ?></option>
                                            <option value="quantity_approval"><?= get_label('quantity_approval', 'Quantity Approval') ?></option>
                                            <option value="management_review"><?= get_label('management_review', 'Management Review') ?></option>
                                            <option value="accounting_review"><?= get_label('accounting_review', 'Accounting Review') ?></option>
                                            <option value="final_approval"><?= get_label('final_approval', 'Final Approval') ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped" id="approved-approvals-table" data-toggle="table" data-url="{{ route('contract-approvals.list') }}" data-query-params="approvedQueryParams" data-pagination="true" data-side-pagination="server" data-page-list="[10, 25, 50, 100]" data-search="false">
                                        <thead>
                                            <tr>
                                                <th data-field="id" data-sortable="true">ID</th>
                                                <th data-field="contract" data-sortable="true"><?= get_label('contract', 'Contract') ?></th>
                                                <th data-field="approval_stage" data-sortable="true"><?= get_label('approval_stage', 'Approval Stage') ?></th>
                                                <th data-field="approver" data-sortable="true"><?= get_label('approver', 'Approver') ?></th>
                                                <th data-field="approved_rejected_at" data-sortable="true"><?= get_label('approved_at', 'Approved At') ?></th>
                                                <th data-field="comments" data-sortable="true"><?= get_label('comments', 'Comments') ?></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                            <!-- Rejected Approvals Tab -->
                            <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0"><?= get_label('rejected_approvals', 'Rejected Approvals') ?></h5>
                                    <div class="d-flex gap-2">
                                        <input type="text" id="rejected-search" class="form-control form-control-sm" placeholder="<?= get_label('search', 'Search') ?>...">
                                        <select id="rejected-stage-filter" class="form-control form-control-sm">
                                            <option value=""><?= get_label('all_stages', 'All Stages') ?></option>
                                            <option value="quantity_approval"><?= get_label('quantity_approval', 'Quantity Approval') ?></option>
                                            <option value="management_review"><?= get_label('management_review', 'Management Review') ?></option>
                                            <option value="accounting_review"><?= get_label('accounting_review', 'Accounting Review') ?></option>
                                            <option value="final_approval"><?= get_label('final_approval', 'Final Approval') ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped" id="rejected-approvals-table" data-toggle="table" data-url="{{ route('contract-approvals.list') }}" data-query-params="rejectedQueryParams" data-pagination="true" data-side-pagination="server" data-page-list="[10, 25, 50, 100]" data-search="false">
                                        <thead>
                                            <tr>
                                                <th data-field="id" data-sortable="true">ID</th>
                                                <th data-field="contract" data-sortable="true"><?= get_label('contract', 'Contract') ?></th>
                                                <th data-field="approval_stage" data-sortable="true"><?= get_label('approval_stage', 'Approval Stage') ?></th>
                                                <th data-field="approver" data-sortable="true"><?= get_label('approver', 'Approver') ?></th>
                                                <th data-field="approved_rejected_at" data-sortable="true"><?= get_label('rejected_at', 'Rejected At') ?></th>
                                                <th data-field="comments" data-sortable="true"><?= get_label('rejection_reason', 'Rejection Reason') ?></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                            <!-- Archived Approvals Tab -->
                            <div class="tab-pane fade" id="archived" role="tabpanel" aria-labelledby="archived-tab">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0"><?= get_label('archived_approvals', 'Archived Approvals') ?></h5>
                                    <input type="text" id="archived-search" class="form-control form-control-sm" placeholder="<?= get_label('search', 'Search') ?>..." style="width: 200px;">
                                </div>
                                <div class="alert alert-info">
                                    <i class="bx bx-info-circle"></i> <?= get_label('archived_approvals_info', 'Archived approvals are stored for historical reference and compliance purposes.') ?>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped" id="archived-approvals-table" data-toggle="table" data-url="{{ route('contract-approvals.list') }}" data-query-params="archivedQueryParams" data-pagination="true" data-side-pagination="server" data-page-list="[10, 25, 50, 100]" data-search="false">
                                        <thead>
                                            <tr>
                                                <th data-field="id" data-sortable="true">ID</th>
                                                <th data-field="contract" data-sortable="true"><?= get_label('contract', 'Contract') ?></th>
                                                <th data-field="approval_stage" data-sortable="true"><?= get_label('approval_stage', 'Approval Stage') ?></th>
                                                <th data-field="status" data-sortable="true"><?= get_label('status', 'Status') ?></th>
                                                <th data-field="submitted_at" data-sortable="true"><?= get_label('submitted_at', 'Submitted At') ?></th>
                                                <th data-field="approved_rejected_at" data-sortable="true"><?= get_label('processed_at', 'Processed At') ?></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                            <!-- All Approvals Tab -->
                            <div class="tab-pane fade" id="all" role="tabpanel" aria-labelledby="all-tab">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0"><?= get_label('all_contract_approvals', 'All Contract Approvals') ?></h5>
                                    <div class="d-flex gap-2">
                                        <input type="text" id="all-search" class="form-control form-control-sm" placeholder="<?= get_label('search', 'Search') ?>...">
                                        <select id="all-status-filter" class="form-control form-control-sm">
                                            <option value=""><?= get_label('all_statuses', 'All Statuses') ?></option>
                                            <option value="pending"><?= get_label('pending', 'Pending') ?></option>
                                            <option value="approved"><?= get_label('approved', 'Approved') ?></option>
                                            <option value="rejected"><?= get_label('rejected', 'Rejected') ?></option>
                                        </select>
                                        <select id="all-stage-filter" class="form-control form-control-sm">
                                            <option value=""><?= get_label('all_stages', 'All Stages') ?></option>
                                            <option value="quantity_approval"><?= get_label('quantity_approval', 'Quantity Approval') ?></option>
                                            <option value="management_review"><?= get_label('management_review', 'Management Review') ?></option>
                                            <option value="accounting_review"><?= get_label('accounting_review', 'Accounting Review') ?></option>
                                            <option value="final_approval"><?= get_label('final_approval', 'Final Approval') ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped" id="all-approvals-table" data-toggle="table" data-url="{{ route('contract-approvals.list') }}" data-query-params="allQueryParams" data-pagination="true" data-side-pagination="server" data-page-list="[10, 25, 50, 100]" data-search="false">
                                        <thead>
                                            <tr>
                                                <th data-field="id" data-sortable="true">ID</th>
                                                <th data-field="contract" data-sortable="true"><?= get_label('contract', 'Contract') ?></th>
                                                <th data-field="approval_stage" data-sortable="true"><?= get_label('approval_stage', 'Approval Stage') ?></th>
                                                <th data-field="approver" data-sortable="true"><?= get_label('approver', 'Approver') ?></th>
                                                <th data-field="status" data-sortable="true"><?= get_label('status', 'Status') ?></th>
                                                <th data-field="submitted_at" data-sortable="true"><?= get_label('submitted_at', 'Submitted At') ?></th>
                                                <th data-field="approved_rejected_at" data-sortable="true"><?= get_label('processed_at', 'Processed At') ?></th>
                                                <th data-field="actions"><?= get_label('actions', 'Actions') ?></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('assets/js/pages/contract-approvals.js') }}"></script>
    @endpush
@endsection