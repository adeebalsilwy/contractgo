@extends('layout')
@section('title')
    <?= get_label('create_contract', 'Create Contract') ?>
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
                        <li class="breadcrumb-item active"><?= get_label('create', 'Create') ?></li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('contracts.index') }}">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('back_to_list', 'Back to List') ?>">
                        <i class='bx bx-arrow-back'></i>
                    </button>
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-gradient-primary text-white py-4">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-lg bg-white rounded-circle me-3">
                                <i class="bx bx-file text-primary fs-3"></i>
                            </div>
                            <div>
                                <h4 class="card-title mb-1 text-white"><?= get_label('create_new_contract', 'Create New Contract') ?></h4>
                                <p class="card-text mb-0 opacity-75">Complete the form below to create a professional contract</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <!-- Professional Tabbed Interface -->
                        <ul class="nav nav-tabs nav-fill mb-0" id="contractTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active d-flex align-items-center justify-content-center py-3" id="basic-info-tab" data-bs-toggle="tab" data-bs-target="#basic-info" type="button" role="tab">
                                    <div class="avatar avatar-sm bg-label-primary rounded-circle me-2">
                                        <i class="bx bx-info-circle"></i>
                                    </div>
                                    <span class="d-none d-md-inline">Basic Information</span>
                                    <span class="d-md-none">Info</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex align-items-center justify-content-center py-3" id="client-project-tab" data-bs-toggle="tab" data-bs-target="#client-project" type="button" role="tab">
                                    <div class="avatar avatar-sm bg-label-success rounded-circle me-2">
                                        <i class="bx bx-user"></i>
                                    </div>
                                    <span class="d-none d-md-inline">Client & Project</span>
                                    <span class="d-md-none">Client</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex align-items-center justify-content-center py-3" id="items-tab" data-bs-toggle="tab" data-bs-target="#items" type="button" role="tab">
                                    <div class="avatar avatar-sm bg-label-info rounded-circle me-2">
                                        <i class="bx bx-package"></i>
                                    </div>
                                    <span class="d-none d-md-inline">Contract Items</span>
                                    <span class="d-md-none">Items</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex align-items-center justify-content-center py-3" id="workflow-tab" data-bs-toggle="tab" data-bs-target="#workflow" type="button" role="tab">
                                    <div class="avatar avatar-sm bg-label-warning rounded-circle me-2">
                                        <i class="bx bx-sitemap"></i>
                                    </div>
                                    <span class="d-none d-md-inline">Workflow</span>
                                    <span class="d-md-none">Workflow</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex align-items-center justify-content-center py-3" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab">
                                    <div class="avatar avatar-sm bg-label-secondary rounded-circle me-2">
                                        <i class="bx bx-cog"></i>
                                    </div>
                                    <span class="d-none d-md-inline">Settings</span>
                                    <span class="d-md-none">Settings</span>
                                </button>
                            </li>
                        </ul>
                        
                        <form action="{{ url('/contracts/store') }}" method="POST" id="contract_form" class="needs-validation" novalidate>
                            @csrf
                            <div class="tab-content p-4" id="contractTabContent">
                                <!-- Basic Information Tab -->
                                <div class="tab-pane fade show active" id="basic-info" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-12 mb-4">
                                            <div class="card border-primary border-2">
                                                <div class="card-header bg-light-primary">
                                                    <h5 class="card-title mb-0 text-primary">
                                                        <i class="bx bx-info-circle me-2"></i>Contract Information
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row g-4">
                                                        <div class="col-md-12">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control form-control-lg" id="title" name="title" value="{{ old('title') }}" required placeholder="<?= get_label('enter_contract_title', 'Enter contract title') ?>">
                                                                <label for="title"><?= get_label('title', 'Title') ?> <span class="text-danger">*</span></label>
                                                                <div class="invalid-feedback">Please enter a contract title.</div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-6">
                                                            <div class="form-floating">
                                                                <input type="date" class="form-control form-control-lg" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                                                <label for="start_date"><?= get_label('start_date', 'Start Date') ?> <span class="text-danger">*</span></label>
                                                                <div class="invalid-feedback">Please select a start date.</div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-6">
                                                            <div class="form-floating">
                                                                <input type="date" class="form-control form-control-lg" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                                                <label for="end_date"><?= get_label('end_date', 'End Date') ?> <span class="text-danger">*</span></label>
                                                                <div class="invalid-feedback">Please select an end date.</div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-6">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control form-control-lg" id="value" name="value" value="{{ old('value') }}" required placeholder="0.00">
                                                                <label for="value"><?= get_label('contract_value', 'Contract Value') ?> <span class="text-danger">*</span></label>
                                                                <div class="form-text mt-2">
                                                                    <i class="bx bx-info-circle me-1"></i>
                                                                    NOTE: Contract value will be automatically recalculated based on associated extracts
                                                                </div>
                                                                <div class="invalid-feedback">Please enter a valid contract value.</div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-6">
                                                            <div class="form-floating">
                                                                <select class="form-select form-select-lg" id="profession_id" name="profession_id">
                                                                    <option value=""><?= get_label('select_profession', 'Select Profession') ?></option>
                                                                    @foreach($professions as $profession)
                                                                        <option value="{{ $profession->id }}" {{ old('profession_id') == $profession->id ? 'selected' : '' }}>
                                                                            {{ $profession->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <label for="profession_id"><?= get_label('profession', 'Profession') ?></label>
                                                                <div class="mt-2">
                                                                    <button class="btn btn-outline-primary btn-sm" type="button" id="create_profession_btn" data-bs-toggle="modal" data-bs-target="#create_profession_modal">
                                                                        <i class="bx bx-plus me-1"></i>Add New
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-12">
                                                            <div class="form-floating">
                                                                <textarea class="form-control" id="description" name="description" rows="4" placeholder="<?= get_label('enter_contract_description', 'Enter contract description') ?>">{{ old('description') }}</textarea>
                                                                <label for="description"><?= get_label('description', 'Description') ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Client & Project Tab -->
                                <div class="tab-pane fade" id="client-project" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-12 mb-4">
                                            <div class="card border-success border-2">
                                                <div class="card-header bg-light-success">
                                                    <h5 class="card-title mb-0 text-success">
                                                        <i class="bx bx-user me-2"></i>Client & Project Information
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row g-4">
                                                        <div class="col-md-6">
                                                            <div class="form-floating">
                                                                <select class="form-select form-select-lg select2-client" id="client_id" name="client_id" required data-placeholder="<?= get_label('select_client', 'Select Client') ?>">
                                                                    <option value=""><?= get_label('select_client', 'Select Client') ?></option>
                                                                    @foreach($clients as $client)
                                                                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }} data-profession="{{ $client->profession_id }}">
                                                                            {{ $client->first_name }} {{ $client->last_name }} 
                                                                            @if($client->company) ({{ $client->company }}) @endif
                                                                            @if($client->profession) - {{ $client->profession->name }} @endif
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <label for="client_id"><?= get_label('client', 'Client') ?> <span class="text-danger">*</span></label>
                                                                <div class="mt-2">
                                                                    <button class="btn btn-outline-success btn-sm" type="button" onclick="window.location.href='{{ url('/clients/create') }}'">
                                                                        <i class="bx bx-user-plus me-1"></i>Add New Client
                                                                    </button>
                                                                </div>
                                                                <div class="invalid-feedback">Please select a client.</div>
                                                            </div>
                                                            <div class="form-text mt-2">
                                                                <i class="bx bx-info-circle me-1"></i> 
                                                                <?= get_label('client_profession_note', 'Select a client to automatically filter by their profession') ?>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-6">
                                                            <div class="form-floating">
                                                                <select class="form-select form-select-lg select2-project" id="project_id" name="project_id" required data-placeholder="<?= get_label('select_project', 'Select Project') ?>">
                                                                    <option value=""><?= get_label('select_project', 'Select Project') ?></option>
                                                                    @foreach($projects as $project)
                                                                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                                                            {{ $project->title }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <label for="project_id"><?= get_label('project', 'Project') ?> <span class="text-danger">*</span></label>
                                                                <div class="mt-2">
                                                                    <button class="btn btn-outline-success btn-sm" type="button" id="create_project_btn" data-bs-toggle="modal" data-bs-target="#create_project_modal">
                                                                        <i class="bx bx-plus me-1"></i>Add New
                                                                    </button>
                                                                </div>
                                                                <div class="invalid-feedback">Please select a project.</div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-12">
                                                            <div class="form-floating">
                                                                <select class="form-select form-select-lg" id="contract_type_id" name="contract_type_id" required>
                                                                    <option value=""><?= get_label('select_contract_type', 'Select Contract Type') ?></option>
                                                                    @foreach($contractTypes as $type)
                                                                        <option value="{{ $type->id }}" {{ old('contract_type_id') == $type->id ? 'selected' : '' }}>
                                                                            {{ $type->type }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <label for="contract_type_id"><?= get_label('contract_type', 'Contract Type') ?> <span class="text-danger">*</span></label>
                                                                <div class="invalid-feedback">Please select a contract type.</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contract Items Tab -->
                                <div class="tab-pane fade" id="items" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-12 mb-4">
                                            <div class="card border-info border-2">
                                                <div class="card-header bg-light-info">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <h5 class="card-title mb-0 text-info">
                                                            <i class="bx bx-package me-2"></i>Contract Items
                                                        </h5>
                                                        <div class="d-flex gap-2">
                                                            <div class="dropdown">
                                                                <button type="button" class="btn btn-sm btn-outline-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="bx bx-template me-1"></i> Templates
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li><h6 class="dropdown-header">Construction Templates</h6></li>
                                                                    <li><a class="dropdown-item add-template-btn" href="#" data-template="construction"><i class="bx bx-building me-2"></i>Construction Materials</a></li>
                                                                    <li><a class="dropdown-item add-template-btn" href="#" data-template="electrical"><i class="bx bx-plug me-2"></i>Electrical Equipment</a></li>
                                                                    <li><a class="dropdown-item add-template-btn" href="#" data-template="plumbing"><i class="bx bx-water me-2"></i>Plumbing Materials</a></li>
                                                                    <li><hr class="dropdown-divider"></li>
                                                                    <li><h6 class="dropdown-header">Engineering Templates</h6></li>
                                                                    <li><a class="dropdown-item add-template-btn" href="#" data-template="mechanical"><i class="bx bx-cog me-2"></i>Mechanical Equipment</a></li>
                                                                    <li><a class="dropdown-item add-template-btn" href="#" data-template="civil"><i class="bx bx-road me-2"></i>Civil Engineering</a></li>
                                                                    <li><a class="dropdown-item add-template-btn" href="#" data-template="architectural"><i class="bx bx-home me-2"></i>Architectural Elements</a></li>
                                                                </ul>
                                                            </div>
                                                            <button type="button" class="btn btn-sm btn-outline-info" id="import_quantities_btn">
                                                                <i class="bx bx-import me-1"></i> Import Quantities
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-primary" id="add_item_btn">
                                                                <i class="bx bx-plus me-1"></i> Add Item
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
                                                        <i class="bx bx-info-circle me-2 fs-5"></i>
                                                        <div>
                                                            <div><strong>Important Note:</strong> Item prices are locked and cannot be modified after contract creation.</div>
                                                            <div class="mt-1"><small class="text-muted">Items are filtered based on the selected profession</small></div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-bordered" id="items_table">
                                                            <thead class="table-dark">
                                                                <tr>
                                                                    <th width="15%"><?= get_label('item', 'Item') ?></th>
                                                                    <th width="20%"><?= get_label('description', 'Description') ?></th>
                                                                    <th width="10%"><?= get_label('unit', 'Unit') ?></th>
                                                                    <th width="10%"><?= get_label('quantity', 'Quantity') ?></th>
                                                                    <th width="15%"><?= get_label('unit_price', 'Unit Price') ?></th>
                                                                    <th width="15%"><?= get_label('total', 'Total') ?></th>
                                                                    <th width="5%"><?= get_label('actions', 'Actions') ?></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="items_table_body">
                                                                <!-- Items will be added here dynamically -->
                                                            </tbody>
                                                            <tfoot>
                                                                <tr class="table-info fw-bold">
                                                                    <td colspan="5" class="text-end fs-5">Total Amount:</td>
                                                                    <td id="items_total_amount" class="fs-5">0.00</td>
                                                                    <td></td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Workflow Tab -->
                                <div class="tab-pane fade" id="workflow" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-12 mb-4">
                                            <div class="card border-warning border-2">
                                                <div class="card-header bg-light-warning">
                                                    <h5 class="card-title mb-0 text-warning">
                                                        <i class="bx bx-sitemap me-2"></i>Workflow Assignment
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <p class="text-muted small mb-4">Assign users to different workflow stages for contract approval process</p>
                                                    
                                                    <!-- Professional Workflow Visualization -->
                                                    <div class="workflow-diagram mb-5 p-4 bg-gradient rounded shadow-sm">
                                                        <div class="row text-center g-3">
                                                            <div class="col-2">
                                                                <div class="workflow-step p-3 bg-white rounded shadow-sm">
                                                                    <div class="avatar avatar-lg mx-auto mb-2 bg-label-primary">
                                                                        <i class="bx bx-user fs-3"></i>
                                                                    </div>
                                                                    <small class="fw-bold">Site Supervisor</small>
                                                                </div>
                                                            </div>
                                                            <div class="col-2 d-flex align-items-center justify-content-center">
                                                                <i class="bx bx-right-arrow-alt text-primary fs-3"></i>
                                                            </div>
                                                            <div class="col-2">
                                                                <div class="workflow-step p-3 bg-white rounded shadow-sm">
                                                                    <div class="avatar avatar-lg mx-auto mb-2 bg-label-warning">
                                                                        <i class="bx bx-check fs-3"></i>
                                                                    </div>
                                                                    <small class="fw-bold">Quantity Approver</small>
                                                                </div>
                                                            </div>
                                                            <div class="col-2 d-flex align-items-center justify-content-center">
                                                                <i class="bx bx-right-arrow-alt text-primary fs-3"></i>
                                                            </div>
                                                            <div class="col-2">
                                                                <div class="workflow-step p-3 bg-white rounded shadow-sm">
                                                                    <div class="avatar avatar-lg mx-auto mb-2 bg-label-success">
                                                                        <i class="bx bx-dollar fs-3"></i>
                                                                    </div>
                                                                    <small class="fw-bold">Accountant</small>
                                                                </div>
                                                            </div>
                                                            <div class="col-2 d-flex align-items-center justify-content-center">
                                                                <i class="bx bx-right-arrow-alt text-primary fs-3"></i>
                                                            </div>
                                                        </div>
                                                        <div class="row text-center g-3 mt-2">
                                                            <div class="col-2 offset-2">
                                                                <div class="workflow-step p-3 bg-white rounded shadow-sm">
                                                                    <div class="avatar avatar-lg mx-auto mb-2 bg-label-info">
                                                                        <i class="bx bx-edit fs-3"></i>
                                                                    </div>
                                                                    <small class="fw-bold">Reviewer</small>
                                                                </div>
                                                            </div>
                                                            <div class="col-2 d-flex align-items-center justify-content-center">
                                                                <i class="bx bx-right-arrow-alt text-primary fs-3"></i>
                                                            </div>
                                                            <div class="col-2">
                                                                <div class="workflow-step p-3 bg-white rounded shadow-sm">
                                                                    <div class="avatar avatar-lg mx-auto mb-2 bg-label-danger">
                                                                        <i class="bx bx-badge-check fs-3"></i>
                                                                    </div>
                                                                    <small class="fw-bold">Final Approver</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                                                        <i class="bx bx-info-circle me-2 fs-5"></i>
                                                        <div>
                                                            These assignments will define the approval workflow for this contract. Each stage must be completed before moving to the next.
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row g-4">
                                                        <div class="col-md-4">
                                                            <div class="form-floating">
                                                                <select class="form-select select2-user" id="site_supervisor_id" name="site_supervisor_id" data-placeholder="Select Site Supervisor">
                                                                    <option value=""><?= get_label('select_site_supervisor', 'Select Site Supervisor') ?></option>
                                                                    @foreach($users as $user)
                                                                        <option value="{{ $user->id }}" {{ old('site_supervisor_id') == $user->id ? 'selected' : '' }}>
                                                                            {{ $user->first_name }} {{ $user->last_name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <label for="site_supervisor_id">Site Supervisor</label>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-4">
                                                            <div class="form-floating">
                                                                <select class="form-select select2-user" id="quantity_approver_id" name="quantity_approver_id" data-placeholder="Select Quantity Approver">
                                                                    <option value=""><?= get_label('select_quantity_approver', 'Select Quantity Approver') ?></option>
                                                                    @foreach($users as $user)
                                                                        <option value="{{ $user->id }}" {{ old('quantity_approver_id') == $user->id ? 'selected' : '' }}>
                                                                            {{ $user->first_name }} {{ $user->last_name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <label for="quantity_approver_id">Quantity Approver</label>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-4">
                                                            <div class="form-floating">
                                                                <select class="form-select select2-user" id="accountant_id" name="accountant_id" data-placeholder="Select Accountant">
                                                                    <option value=""><?= get_label('select_accountant', 'Select Accountant') ?></option>
                                                                    @foreach($users as $user)
                                                                        <option value="{{ $user->id }}" {{ old('accountant_id') == $user->id ? 'selected' : '' }}>
                                                                            {{ $user->first_name }} {{ $user->last_name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <label for="accountant_id">Accountant</label>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-6">
                                                            <div class="form-floating">
                                                                <select class="form-select select2-user" id="reviewer_id" name="reviewer_id" data-placeholder="Select Reviewer">
                                                                    <option value=""><?= get_label('select_reviewer', 'Select Reviewer') ?></option>
                                                                    @foreach($users as $user)
                                                                        <option value="{{ $user->id }}" {{ old('reviewer_id') == $user->id ? 'selected' : '' }}>
                                                                            {{ $user->first_name }} {{ $user->last_name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <label for="reviewer_id">Reviewer</label>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-6">
                                                            <div class="form-floating">
                                                                <select class="form-select select2-user" id="final_approver_id" name="final_approver_id" data-placeholder="Select Final Approver">
                                                                    <option value=""><?= get_label('select_final_approver', 'Select Final Approver') ?></option>
                                                                    @foreach($users as $user)
                                                                        <option value="{{ $user->id }}" {{ old('final_approver_id') == $user->id ? 'selected' : '' }}>
                                                                            {{ $user->first_name }} {{ $user->last_name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <label for="final_approver_id">Final Approver</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Settings Tab -->
                                <div class="tab-pane fade" id="settings" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-12 mb-4">
                                            <div class="card border-secondary border-2">
                                                <div class="card-header bg-light-secondary">
                                                    <h5 class="card-title mb-0 text-secondary">
                                                        <i class="bx bx-cog me-2"></i>Additional Settings
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row g-4">
                                                        <div class="col-md-12">
                                                            <div class="form-floating">
                                                                <select class="form-select select2-multiple" id="extracts" name="extracts[]" multiple data-placeholder="Select extracts to link to this contract">
                                                                    @foreach($extracts as $extract)
                                                                        <option value="{{ $extract->id }}" {{ in_array($extract->id, old('extracts', [])) ? 'selected' : '' }}>
                                                                            {{ $extract->name }} ({{ ucfirst($extract->type) }}) - {{ format_currency($extract->final_total) }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <label for="extracts">Link Existing Extracts</label>
                                                                <div class="form-text mt-2">Select existing extracts that should be linked to this contract. You can also create new extracts after contract creation.</div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-6">
                                                            <div class="form-check form-switch form-switch-lg">
                                                                <input class="form-check-input" type="checkbox" id="auto_create_project" name="auto_create_project" value="1" {{ old('auto_create_project') ? 'checked' : '' }}>
                                                                <label class="form-check-label fw-bold" for="auto_create_project">
                                                                    Auto-create Project
                                                                </label>
                                                                <div class="form-text">Automatically create a project with contract details</div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-6">
                                                            <div class="form-check form-switch form-switch-lg">
                                                                <input class="form-check-input" type="checkbox" id="auto_start_workflow" name="auto_start_workflow" value="1" {{ old('auto_start_workflow') ? 'checked' : '' }}>
                                                                <label class="form-check-label fw-bold" for="auto_start_workflow">
                                                                    Auto-start Workflow
                                                                </label>
                                                                <div class="form-text">Automatically start the workflow process after contract creation</div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-6">
                                                            <div class="form-check form-switch form-switch-lg">
                                                                <input class="form-check-input" type="checkbox" id="require_signatures" name="require_signatures" value="1" {{ old('require_signatures', true) ? 'checked' : '' }}>
                                                                <label class="form-check-label fw-bold" for="require_signatures">
                                                                    Require Signatures
                                                                </label>
                                                                <div class="form-text">Require both parties to sign the contract</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="card-footer bg-light py-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        <i class="bx bx-info-circle me-1"></i>
                                        Required fields are marked with <span class="text-danger">*</span>
                                    </div>
                                    <div class="d-flex gap-3">
                                        <a href="{{ route('contracts.index') }}" class="btn btn-lg btn-outline-secondary px-4">
                                            <i class="bx bx-x me-1"></i> Cancel
                                        </a>
                                        <button type="submit" class="btn btn-lg btn-primary px-4" id="submit_btn">
                                            <i class="bx bx-save me-1"></i> Create Contract
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

    <!-- Create Profession Modal -->
    <div class="modal fade" id="create_profession_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="create_profession_form">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create Profession</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="profession_name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="profession_name" name="name" required placeholder="Enter profession name">
                            <div class="invalid-feedback" id="profession_name_error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="profession_description" class="form-label">Description</label>
                            <textarea class="form-control" id="profession_description" name="description" rows="3" placeholder="Enter profession description"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Create Project Modal -->
    <div class="modal fade" id="create_project_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="create_project_form">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create Project</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label for="project_title" class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="project_title" name="title" required placeholder="Enter project title">
                                <div class="invalid-feedback" id="project_title_error"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="project_status_id" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="project_status_id" name="status_id" required>
                                    <option value="">Select Status</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->id }}">{{ $status->title }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="project_status_id_error"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="project_priority_id" class="form-label">Priority</label>
                                <select class="form-select" id="project_priority_id" name="priority_id">
                                    <option value="">Select Priority</option>
                                    @foreach($priorities as $priority)
                                        <option value="{{ $priority->id }}">{{ $priority->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="project_start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="project_start_date" name="start_date">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="project_end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="project_end_date" name="end_date">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="project_budget" class="form-label">Budget</label>
                                <input type="text" class="form-control" id="project_budget" name="budget" placeholder="0.00">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="project_description" class="form-label">Description</label>
                                <textarea class="form-control" id="project_description" name="description" rows="3" placeholder="Enter project description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <style>
        /* Enhanced Professional Styles */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }
        
        .bg-gradient {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        
        .nav-tabs .nav-link {
            border: none;
            border-radius: 0;
            padding: 1rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            background: transparent;
            color: #6c757d;
        }
        
        .nav-tabs .nav-link.active {
            background: #fff;
            color: #696cff;
            border-bottom: 3px solid #696cff;
            box-shadow: 0 2px 10px rgba(105, 108, 255, 0.2);
        }
        
        .nav-tabs .nav-link:hover:not(.active) {
            background: rgba(105, 108, 255, 0.1);
            color: #696cff;
        }
        
        .form-floating > .form-control,
        .form-floating > .form-select {
            padding: 1.5rem 1rem 0.5rem;
            height: calc(3.5rem + 2px);
        }
        
        .form-floating > label {
            padding: 1.5rem 1rem 0.5rem;
            height: calc(3.5rem + 2px);
        }
        
        .workflow-diagram {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
        }
        
        .workflow-step {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .workflow-step:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2) !important;
        }
        
        .avatar {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        
        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }
        
        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .card {
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #696cff;
            box-shadow: 0 0 0 0.25rem rgba(105, 108, 255, 0.25);
        }
        
        .select2-container--default .select2-selection--single {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            height: calc(3.5rem + 2px);
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: calc(3.5rem + 2px);
            padding-left: 1rem;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(3.5rem + 2px);
        }
        
        .dropdown-menu {
            border-radius: 10px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }
        
        .dropdown-item {
            border-radius: 6px;
            margin: 2px 8px;
            transition: all 0.2s ease;
        }
        
        .dropdown-item:hover {
            background-color: rgba(105, 108, 255, 0.1);
            transform: translateX(5px);
        }
        
        @media (max-width: 991px) {
            .workflow-diagram .row {
                flex-direction: column;
            }
            
            .workflow-diagram .col {
                margin-bottom: 15px;
            }
            
            .workflow-diagram .col.d-flex {
                display: none !important;
            }
        }
        
        .item-row {
            transition: all 0.3s ease;
        }
        
        .item-row:hover {
            background-color: rgba(105, 108, 255, 0.05);
        }
        
        .remove-item-btn {
            transition: all 0.2s ease;
        }
        
        .remove-item-btn:hover {
            transform: scale(1.1);
        }
        
        .toast {
            border-radius: 8px;
        }
    </style>
    <script>
        $(document).ready(function() {
            // Initialize enhanced Select2 with better styling
            $('.select2-client, .select2-project, .select2-user, .select2-multiple').select2({
                placeholder: function() {
                    return $(this).data('placeholder') || 'Select an option';
                },
                allowClear: true,
                width: '100%',
                theme: 'bootstrap4',
                selectionCssClass: ':all:'
            });

            // Enhanced date validation with proper formatting
            $('#start_date, #end_date').on('change', function() {
                const startDate = new Date($('#start_date').val());
                const endDate = new Date($('#end_date').val());
                
                if (startDate && endDate && startDate > endDate) {
                    toastr.error('<?= get_label('end_date_must_be_after_start_date', 'End date must be after start date') ?>');
                    $(this).val('');
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            // Enhanced currency formatting
            $('#value').on('input', function() {
                let value = $(this).val().replace(/[^0-9.]/g, '');
                if (value) {
                    value = parseFloat(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    $(this).val(value);
                }
            });

            // Form validation and submission with enhanced feedback
            $('#contract_form').on('submit', function(e) {
                e.preventDefault();
                
                // Validate that at least one item is selected
                const hasItems = $('.item-select').filter(function() {
                    return $(this).val() !== '';
                }).length > 0;
                
                if (!hasItems) {
                    toastr.error('<?= get_label('please_add_at_least_one_item', 'Please add at least one item to the contract') ?>');
                    $('#contractTabs a[href="#items"]').tab('show');
                    return false;
                }
                
                const submit_btn = $(this).find('#submit_btn');
                const btn_html = submit_btn.html();
                const autoCreateProject = $('#auto_create_project').is(':checked');
                let formData = $(this).serialize();
                
                // Add flags to form data
                if (autoCreateProject) {
                    formData += '&auto_create_project=1';
                }
                formData += '&apply_default_workflow=1';
                
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: formData,
                    beforeSend: function() {
                        submit_btn.html('<i class="bx bx-loader-alt bx-spin me-1"></i> Creating...').attr('disabled', true);
                    },
                    success: function(response) {
                        if (!response.error) {
                            toastr.success('<?= get_label('contract_created_successfully', 'Contract created successfully') ?>');
                            
                            if (autoCreateProject && response.project_id) {
                                toastr.success('<?= get_label('project_created_automatically', 'Project created automatically') ?>');
                            }
                            
                            // Redirect to contract details page
                            setTimeout(function() {
                                window.location.href = '/contracts/' + response.id;
                            }, 1500);
                        } else {
                            submit_btn.html(btn_html).attr('disabled', false);
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        submit_btn.html(btn_html).attr('disabled', false);
                        const errors = xhr.responseJSON?.errors;
                        if (errors) {
                            $.each(errors, function(field, messages) {
                                // Handle nested item fields
                                if (field.startsWith('items.')) {
                                    const fieldParts = field.split('.');
                                    const rowIndex = fieldParts[1];
                                    const rowField = fieldParts[2];
                                    
                                    const targetRow = $(`[name^="items[${rowIndex}]"][name$="[${rowField}]"]`);
                                    if (targetRow.length > 0) {
                                        targetRow.addClass('is-invalid');
                                        targetRow.after(`<div class="invalid-feedback d-block">${messages[0]}</div>`);
                                    } else {
                                        toastr.error(messages[0]);
                                    }
                                } else {
                                    $(`#${field}`).addClass('is-invalid');
                                    $(`#${field}`).after(`<div class="invalid-feedback d-block">${messages[0]}</div>`);
                                }
                            });
                            toastr.error('<?= get_label('validation_errors', 'Please fix the validation errors') ?>');
                        } else {
                            toastr.error('<?= get_label('something_went_wrong', 'Something went wrong') ?>');
                        }
                    }
                });
            });

            // Enhanced client-profession bidirectional filtering
            $('#client_id').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const professionId = selectedOption.data('profession');
                
                if (professionId) {
                    $('#profession_id').val(professionId).trigger('change.select2');
                    toastr.info('<?= get_label('profession_auto_selected', 'Profession automatically selected based on client') ?>');
                } else {
                    $('#profession_id').val('').trigger('change.select2');
                }
            });
            
            // Filter clients by selected profession
            $('#profession_id').on('change', function() {
                const selectedProfession = $(this).val();
                
                $('#client_id option').each(function() {
                    const clientProfession = $(this).data('profession');
                    
                    if (selectedProfession === '' || clientProfession == selectedProfession) {
                        $(this).show().prop('disabled', false);
                    } else {
                        $(this).hide().prop('disabled', true);
                    }
                });
                
                // Reset client selection if current client doesn't match profession
                const currentClient = $('#client_id').val();
                if (currentClient) {
                    const currentClientProfession = $(`#client_id option[value="${currentClient}"]`).data('profession');
                    if (selectedProfession !== '' && currentClientProfession != selectedProfession) {
                        $('#client_id').val('').trigger('change.select2');
                        toastr.info('<?= get_label('client_reset_due_to_profession', 'Client selection cleared due to profession mismatch') ?>');
                    }
                }
                
                $('#client_id').trigger('change');
            });

            // Enhanced item management with better performance
            let itemCounter = 0;
            
            // Import quantities functionality
            $('#import_quantities_btn').on('click', function() {
                const clientId = $('#client_id').val();
                if (!clientId) {
                    toastr.error('<?= get_label('please_select_client_first', 'Please select a client first to import quantities') ?>');
                    $('#contractTabs a[href="#client-project"]').tab('show');
                    return;
                }
                
                showImportQuantitiesModal(clientId);
            });

            // Enhanced add item button
            $('#add_item_btn').on('click', function() {
                const btn = $(this);
                const originalText = btn.html();
                btn.html('<i class="bx bx-loader-alt bx-spin me-1"></i> Adding...').prop('disabled', true);
                
                setTimeout(function() {
                    addItemRow();
                    btn.html(originalText).prop('disabled', false);
                    toastr.success('Item row added successfully');
                }, 300);
            });

            // Enhanced import quantities modal
            function showImportQuantitiesModal(clientId) {
                const importBtn = $('#import_quantities_btn');
                const originalText = importBtn.html();
                importBtn.html('<i class="bx bx-loader-alt bx-spin me-1"></i> Loading...').prop('disabled', true);
                
                $.ajax({
                    url: '{{ route("contracts.getClientQuantities", ":clientId") }}'.replace(':clientId', clientId),
                    method: 'GET',
                    success: function(response) {
                        importBtn.html(originalText).prop('disabled', false);
                        
                        if (response.error) {
                            toastr.error(response.message);
                            return;
                        }
                        
                        const quantities = response.quantities;
                        if (quantities.length === 0) {
                            toastr.info('<?= get_label('no_quantities_available_for_import', 'No quantities available for import from this client') ?>');
                            return;
                        }
                        
                        // Create enhanced modal with better UX
                        const modalHtml = `
                            <div class="modal fade" id="import_quantities_modal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header bg-info text-white">
                                            <h5 class="modal-title">
                                                <i class="bx bx-import me-2"></i>Import Client Quantities
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-info">
                                                <i class="bx bx-info-circle me-2"></i>
                                                Select quantities to import into this contract. You can import multiple items at once.
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th width="5%"><input type="checkbox" id="select_all_quantities" class="form-check-input"></th>
                                                            <th width="20%"><i class="bx bx-package me-1"></i> Item</th>
                                                            <th width="25%"><i class="bx bx-detail me-1"></i> Description</th>
                                                            <th width="10%"><i class="bx bx-hash me-1"></i> Quantity</th>
                                                            <th width="10%"><i class="bx bx-ruler me-1"></i> Unit</th>
                                                            <th width="15%"><i class="bx bx-dollar me-1"></i> Unit Price</th>
                                                            <th width="15%"><i class="bx bx-money me-1"></i> Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                        `;
                        
                        quantities.forEach(function(quantity) {
                            const total = (parseFloat(quantity.requested_quantity || 0) * parseFloat(quantity.unit_price || 0)).toFixed(2);
                            const itemDescription = quantity.item_description || 'General Item';
                            const description = quantity.description || quantity.item_description || 'No Description';
                            
                            modalHtml += `
                                <tr class="item-row">
                                    <td>
                                        <input type="checkbox" class="form-check-input quantity-checkbox" 
                                               data-quantity='${JSON.stringify({
                                                   item_id: quantity.item_id,
                                                   title: itemDescription,
                                                   description: description,
                                                   unit: quantity.unit,
                                                   quantity: quantity.requested_quantity,
                                                   unit_price: quantity.unit_price
                                               }).replace(/'/g, "&apos;")}'>
                                    </td>
                                    <td><strong>${itemDescription}</strong></td>
                                    <td>${description}</td>
                                    <td>${quantity.requested_quantity || 0}</td>
                                    <td>${quantity.unit || 'Not Specified'}</td>
                                    <td>${formatPrice(quantity.unit_price || 0)}</td>
                                    <td><strong>${formatPrice(total)}</strong></td>
                                </tr>
                            `;
                        });
                        
                        modalHtml += `
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                <i class="bx bx-x me-1"></i>Close
                                            </button>
                                            <button type="button" class="btn btn-info" id="import_selected_quantities">
                                                <i class="bx bx-import me-1"></i>Import Selected (${quantities.length} items)
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        // Remove any existing modal and add new one
                        $('#import_quantities_modal').remove();
                        $('body').append(modalHtml);
                        
                        // Initialize modal with enhanced features
                        const modal = new bootstrap.Modal(document.getElementById('import_quantities_modal'));
                        modal.show();
                        
                        // Enhanced select all functionality
                        $('#select_all_quantities').on('change', function() {
                            $('.quantity-checkbox').prop('checked', $(this).is(':checked'));
                            updateImportButton();
                        });
                        
                        // Individual checkbox changes
                        $('.quantity-checkbox').on('change', function() {
                            const totalChecked = $('.quantity-checkbox:checked').length;
                            $('#select_all_quantities').prop('checked', totalChecked === $('.quantity-checkbox').length);
                            updateImportButton();
                        });
                        
                        // Enhanced import selected button functionality
                        $('#import_selected_quantities').on('click', function() {
                            const selectedQuantities = $('.quantity-checkbox:checked');
                            if (selectedQuantities.length === 0) {
                                toastr.error('<?= get_label('please_select_at_least_one_quantity', 'Please select at least one quantity to import') ?>');
                                return;
                            }
                            
                            const importBtn = $(this);
                            const importBtnText = importBtn.html();
                            importBtn.html('<i class="bx bx-loader-alt bx-spin me-1"></i>Importing...').prop('disabled', true);
                            
                            setTimeout(function() {
                                selectedQuantities.each(function() {
                                    const quantityData = $(this).data('quantity');
                                    addItemRow(quantityData);
                                });
                                
                                modal.hide();
                                $('#import_quantities_modal').remove();
                                toastr.success(`${selectedQuantities.length} quantities imported successfully`);
                                importBtn.html(importBtnText).prop('disabled', false);
                            }, 500);
                        });
                        
                        // Close modal cleanup
                        $('#import_quantities_modal').on('hidden.bs.modal', function () {
                            $(this).remove();
                        });
                        
                        // Initialize button state
                        updateImportButton();
                        
                        function updateImportButton() {
                            const selectedCount = $('.quantity-checkbox:checked').length;
                            const importBtn = $('#import_selected_quantities');
                            importBtn.html(`<i class="bx bx-import me-1"></i>Import Selected (${selectedCount} items)`);
                            importBtn.prop('disabled', selectedCount === 0);
                        }
                    },
                    error: function(xhr) {
                        importBtn.html(originalText).prop('disabled', false);
                        
                        if (xhr.status === 404) {
                            toastr.error('<?= get_label('client_not_found', 'Client not found or no quantities available') ?>');
                        } else {
                            toastr.error('<?= get_label('error_fetching_quantities', 'Error fetching client quantities') ?>');
                        }
                    }
                });
            }

            // Enhanced add item row function with better structure
            function addItemRow(itemData = null) {
                itemCounter++;
                const itemId = `item_${itemCounter}`;
                
                // Prepare initial values
                const item_id = itemData ? itemData.item_id : '';
                const title = itemData ? itemData.title : '';
                const description = itemData ? itemData.description : '';
                const unit = itemData ? itemData.unit : '';
                const quantity = itemData ? itemData.quantity : '';
                const unit_price = itemData ? itemData.unit_price : '';
                let total = '';
                
                // Calculate total if we have both quantity and price
                if (itemData && itemData.quantity && itemData.unit_price) {
                    total = formatPrice(parseFloat(itemData.quantity) * parseFloat(itemData.unit_price));
                }
                
                const row = `
                    <tr id="${itemId}" class="item-row">
                        <td>
                            <select class="form-select item-select" name="items[${itemCounter}][item_id]" required>
                                <option value="">Select Item</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}" 
                                            data-description="{{ $item->description }}" 
                                            data-unit="{{ $item->unit->title ?? '' }}" 
                                            data-price="{{ $item->effective_price }}" 
                                            data-profession="{{ $item->profession_id }}"
                                            ${(item_id && item_id == $item->id) || (title && title == $item->title) ? 'selected' : ''}>
                                        {{ $item->title }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <textarea class="form-control item-description" name="items[${itemCounter}][description]" rows="2" required>${description}</textarea>
                        </td>
                        <td>
                            <input type="text" class="form-control item-unit" name="items[${itemCounter}][unit]" value="${unit}" required>
                        </td>
                        <td>
                            <input type="number" class="form-control item-quantity" name="items[${itemCounter}][quantity]" min="0" step="0.01" value="${quantity}" required>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text"><?= $general_settings['currency_symbol'] ?? '$' ?></span>
                                <input type="text" class="form-control item-price" name="items[${itemCounter}][unit_price]" value="${unit_price}" required>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text"><?= $general_settings['currency_symbol'] ?? '$' ?></span>
                                <input type="text" class="form-control item-total" name="items[${itemCounter}][total]" value="${total}" readonly>
                            </div>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm remove-item-btn" title="Remove Item">
                                <i class="bx bx-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                
                $('#items_table_body').append(row);
                
                // Enhanced Select2 initialization with proper error handling
                const newSelect = $(`#${itemId} .item-select`);
                setTimeout(function() {
                    try {
                        newSelect.select2({
                            placeholder: 'Select Item',
                            allowClear: true,
                            width: '100%',
                            theme: 'bootstrap4'
                        });
                        
                        // Enhanced item selection handling
                        newSelect.on('select2:select', function(e) {
                            const selectedOption = $(this).find('option:selected');
                            const description = selectedOption.data('description');
                            const unit = selectedOption.data('unit');
                            const price = selectedOption.data('price');
                            
                            const row = $(this).closest('tr');
                            row.find('.item-description').val(description || '');
                            row.find('.item-unit').val(unit || '');
                            row.find('.item-price').val(formatPrice(price || 0));
                            
                            calculateItemTotal(itemId);
                        });
                    } catch (error) {
                        console.error('Error initializing Select2:', error);
                    }
                }, 100);
                
                // Filter items by selected profession
                setTimeout(function() {
                    filterItemsByProfession();
                }, 200);
                
                // Bind events for the new row
                bindItemEvents(itemId);
                
                // Scroll to the new item
                $('html, body').animate({
                    scrollTop: $(`#${itemId}`).offset().top - 100
                }, 500);
            }

            // Enhanced item filtering by profession
            function filterItemsByProfession() {
                const selectedProfession = $('#profession_id').val();
                
                $('.item-select').each(function() {
                    const selectElement = $(this);
                    const currentSelection = selectElement.val();
                    
                    // Destroy and recreate Select2 for filtering
                    if (selectElement.data('select2')) {
                        selectElement.select2('destroy');
                    }
                    
                    // Filter options based on profession
                    selectElement.find('option').each(function() {
                        const option = $(this);
                        const itemProfession = option.data('profession');
                        
                        if (selectedProfession === '' || itemProfession == selectedProfession || option.val() === '') {
                            option.show().prop('disabled', false);
                        } else {
                            option.hide().prop('disabled', true);
                        }
                    });
                    
                    // Reinitialize Select2 with filtered options
                    selectElement.select2({
                        placeholder: 'Select Item',
                        allowClear: true,
                        width: '100%',
                        theme: 'bootstrap4'
                    });
                    
                    // Restore valid selection
                    if (currentSelection) {
                        const selectedOption = selectElement.find(`option[value="${currentSelection}"]`);
                        if (selectedOption.length && !selectedOption.prop('disabled')) {
                            selectElement.val(currentSelection).trigger('change');
                        } else {
                            // Clear invalid selection
                            selectElement.val('').trigger('change');
                            const row = selectElement.closest('tr');
                            row.find('.item-description').val('');
                            row.find('.item-unit').val('');
                            row.find('.item-price').val('');
                            row.find('.item-total').val('');
                        }
                    }
                });
            }

            // Enhanced event binding with better error handling
            function bindItemEvents(itemId) {
                const row = $(`#${itemId}`);
                
                // Quantity change with debouncing
                let quantityTimer;
                row.find('.item-quantity').on('input', function() {
                    clearTimeout(quantityTimer);
                    quantityTimer = setTimeout(function() {
                        calculateItemTotal(itemId);
                    }, 300);
                });

                // Price change with debouncing
                let priceTimer;
                row.find('.item-price').on('input', function() {
                    clearTimeout(priceTimer);
                    priceTimer = setTimeout(function() {
                        calculateItemTotal(itemId);
                    }, 300);
                });

                // Remove item with confirmation
                row.find('.remove-item-btn').on('click', function() {
                    const itemTitle = row.find('.item-select option:selected').text() || 'this item';
                    if (confirm(`Are you sure you want to remove ${itemTitle}?`)) {
                        row.fadeOut(300, function() {
                            $(this).remove();
                            calculateItemsTotal();
                            toastr.info('Item removed successfully');
                        });
                    }
                });
            }

            // Enhanced item total calculation with better precision
            function calculateItemTotal(itemId) {
                const row = $(`#${itemId}`);
                const quantity = parseFloat(row.find('.item-quantity').val()) || 0;
                const price = parseFloat(row.find('.item-price').val().replace(/[^0-9.]/g, '')) || 0;
                const total = quantity * price;
                
                row.find('.item-total').val(formatPrice(total));
                calculateItemsTotal();
            }

            // Enhanced items total calculation with smooth animation
            function calculateItemsTotal() {
                let total = 0;
                $('.item-total').each(function() {
                    const value = parseFloat($(this).val().replace(/[^0-9.]/g, '')) || 0;
                    total += value;
                });
                
                const formattedTotal = formatPrice(total);
                $('#items_total_amount').text(formattedTotal);
                
                // Update contract value if empty
                if (!$('#value').val() && total > 0) {
                    $('#value').val(formattedTotal.replace(/[^0-9.]/g, ''));
                }
                
                // Smooth animation for total amount
                $('#items_total_amount').addClass('text-success');
                setTimeout(function() {
                    $('#items_total_amount').removeClass('text-success');
                }, 1000);
            }

            // Enhanced price formatting with proper localization
            function formatPrice(price) {
                const numPrice = parseFloat(price);
                if (isNaN(numPrice)) return '0.00';
                return numPrice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            }

            // Enhanced template items functionality
            function addTemplateItems(templateType) {
                const templates = {
                    construction: [
                        {title: 'Cement', description: 'Portland cement 50kg bags', unit: 'bag', quantity: 100, unit_price: 15},
                        {title: 'Sand', description: 'Construction sand', unit: 'm³', quantity: 10, unit_price: 80},
                        {title: 'Steel Bars', description: 'Reinforcement steel bars', unit: 'ton', quantity: 5, unit_price: 800},
                        {title: 'Concrete Blocks', description: 'Standard concrete blocks', unit: 'piece', quantity: 500, unit_price: 5},
                        {title: 'Bricks', description: 'Standard building bricks', unit: 'piece', quantity: 1000, unit_price: 2}
                    ],
                    electrical: [
                        {title: 'Cables', description: 'Electrical cables 2.5mm²', unit: 'meter', quantity: 500, unit_price: 3},
                        {title: 'Switches', description: 'Wall switches', unit: 'piece', quantity: 20, unit_price: 5},
                        {title: 'Outlets', description: 'Power outlets', unit: 'piece', quantity: 15, unit_price: 7},
                        {title: 'Circuit Breakers', description: 'Main circuit breakers', unit: 'piece', quantity: 5, unit_price: 25},
                        {title: 'Lighting Fixtures', description: 'LED lighting fixtures', unit: 'piece', quantity: 10, unit_price: 35}
                    ],
                    plumbing: [
                        {title: 'Pipes', description: 'PVC pipes 4 inch', unit: 'meter', quantity: 100, unit_price: 12},
                        {title: 'Fittings', description: 'Pipe fittings', unit: 'piece', quantity: 50, unit_price: 8},
                        {title: 'Valves', description: 'Water valves', unit: 'piece', quantity: 10, unit_price: 25},
                        {title: 'Toilets', description: 'Standard ceramic toilets', unit: 'piece', quantity: 5, unit_price: 120},
                        {title: 'Sinks', description: 'Kitchen/bathroom sinks', unit: 'piece', quantity: 8, unit_price: 75}
                    ]
                };
                
                const selectedTemplate = templates[templateType] || templates.construction;
                const $button = $(`.add-template-btn[data-template="${templateType}"]`);
                const originalText = $button.html();
                $button.html('<i class="bx bx-loader-alt bx-spin me-1"></i> Loading...').prop('disabled', true);
                
                // Add items with a delay for better user experience
                let addedCount = 0;
                selectedTemplate.forEach(function(item, index) {
                    setTimeout(function() {
                        addItemRow({
                            item_id: null,
                            title: item.title,
                            description: item.description,
                            unit: item.unit,
                            quantity: item.quantity,
                            unit_price: item.unit_price
                        });
                        addedCount++;
                        if (addedCount === selectedTemplate.length) {
                            $button.html(originalText).prop('disabled', false);
                            toastr.success(`${addedCount} template items added successfully`);
                        }
                    }, index * 200);
                });
            }
            
            // Add template selector functionality
            $(document).on('click', '.add-template-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const templateType = $(this).data('template');
                const templateNames = {
                    'construction': 'Construction Materials',
                    'electrical': 'Electrical Equipment',
                    'plumbing': 'Plumbing Materials',
                    'mechanical': 'Mechanical Equipment',
                    'civil': 'Civil Engineering',
                    'architectural': 'Architectural Elements'
                };
                addTemplateItems(templateType);
            });
            
            // Enhanced tab navigation with localStorage
            $('#contractTabs a').on('click', function (e) {
                e.preventDefault();
                $(this).tab('show');
            });
            
            $(document).on('shown.bs.tab', '#contractTabs a', function (e) {
                localStorage.setItem('contractTab', $(e.target).attr('href'));
            });
            
            // Initialize with last selected tab
            const lastTab = localStorage.getItem('contractTab');
            if (lastTab) {
                $(`#contractTabs a[href="${lastTab}"]`).tab('show');
            }
            
            // Enhanced form submission with better validation
            $('#create_profession_form').on('submit', function(e) {
                e.preventDefault();
                
                $.ajax({
                    url: '{{ url('/professions/store') }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (!response.error) {
                            toastr.success(response.message);
                            $('#create_profession_modal').modal('hide');
                            $('#create_profession_form')[0].reset();
                            
                            // Add new profession to dropdown
                            const newOption = new Option(response.data.name, response.data.id, true, true);
                            $('#profession_id').append(newOption).trigger('change.select2');
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $(`#profession_${key}_error`).text(value[0]);
                                $(`#profession_${key}`).addClass('is-invalid');
                            });
                        } else {
                            toastr.error('<?= get_label('something_went_wrong', 'Something went wrong') ?>');
                        }
                    }
                });
            });

            // Enhanced project creation
            $('#create_project_form').on('submit', function(e) {
                e.preventDefault();
                
                $.ajax({
                    url: '{{ url('/projects/store') }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (!response.error) {
                            toastr.success('<?= get_label('project_created_successfully', 'Project created successfully') ?>');
                            $('#create_project_modal').modal('hide');
                            $('#create_project_form')[0].reset();
                            
                            // Add new project to dropdown
                            const newOption = new Option(response.data.title, response.data.id, true, true);
                            $('#project_id').append(newOption).trigger('change.select2');
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $(`#project_${key}_error`).text(value[0]);
                                $(`#project_${key}`).addClass('is-invalid');
                            });
                        } else {
                            toastr.error('<?= get_label('something_went_wrong', 'Something went wrong') ?>');
                        }
                    }
                });
            });

            // Clear validation errors when modals close
            $('#create_profession_modal, #create_project_modal').on('hidden.bs.modal', function() {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
            });
            
            // Initialize with one item row
            setTimeout(function() {
                addItemRow();
                filterItemsByProfession();
            }, 500);
        });
    </script>
    @endpush
@endsection