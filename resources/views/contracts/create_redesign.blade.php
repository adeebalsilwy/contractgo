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
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-file-blank"></i> <?= get_label('create_new_contract', 'Create New Contract') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Professional Tabbed Interface -->
                        <ul class="nav nav-tabs nav-fill mb-4" id="contractTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="basic-info-tab" data-bs-toggle="tab" data-bs-target="#basic-info" type="button" role="tab">
                                    <i class="bx bx-info-circle me-1"></i> Basic Information
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="client-project-tab" data-bs-toggle="tab" data-bs-target="#client-project" type="button" role="tab">
                                    <i class="bx bx-user me-1"></i> Client & Project
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="items-tab" data-bs-toggle="tab" data-bs-target="#items" type="button" role="tab">
                                    <i class="bx bx-package me-1"></i> Contract Items
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="workflow-tab" data-bs-toggle="tab" data-bs-target="#workflow" type="button" role="tab">
                                    <i class="bx bx-sitemap me-1"></i> Workflow
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab">
                                    <i class="bx bx-cog me-1"></i> Settings
                                </button>
                            </li>
                        </ul>
                        
                        <form action="{{ url('/contracts/store') }}" method="POST" id="contract_form">
                            @csrf
                            <div class="tab-content" id="contractTabContent">
                                <!-- Basic Information Tab -->
                                <div class="tab-pane fade show active" id="basic-info" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-12 mb-4">
                                            <div class="card bg-light border-primary shadow-sm">
                                                <div class="card-body">
                                                    <h6 class="card-title text-primary mb-3">
                                                        <i class="bx bx-info-circle me-1"></i> Contract Information
                                                    </h6>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label for="title" class="form-label fw-bold"><?= get_label('title', 'Title') ?> <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control form-control-lg" id="title" name="title" value="{{ old('title') }}" required placeholder="<?= get_label('enter_contract_title', 'Enter contract title') ?>">
                                                            @error('title')
                                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        
                                                        <div class="col-md-6 mb-3">
                                                            <label for="value" class="form-label fw-bold"><?= get_label('contract_value', 'Contract Value') ?> <span class="text-danger">*</span></label>
                                                            <div class="input-group input-group-lg">
                                                                <span class="input-group-text fs-4"><?= $general_settings['currency_symbol'] ?? '$' ?></span>
                                                                <input type="text" class="form-control fs-4 py-3" id="value" name="value" value="{{ old('value') }}" required placeholder="0.00">
                                                            </div>
                                                            <div class="form-text text-info mt-1">
                                                                <i class="bx bx-info-circle me-1"></i>
                                                                NOTE: Contract value will be automatically recalculated based on associated extracts
                                                            </div>
                                                            @error('value')
                                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        
                                                        <div class="col-md-6 mb-3">
                                                            <label for="start_date" class="form-label fw-bold"><?= get_label('start_date', 'Start Date') ?> <span class="text-danger">*</span></label>
                                                            <input type="date" class="form-control form-control-lg" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                                            @error('start_date')
                                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        
                                                        <div class="col-md-6 mb-3">
                                                            <label for="end_date" class="form-label fw-bold"><?= get_label('end_date', 'End Date') ?> <span class="text-danger">*</span></label>
                                                            <input type="date" class="form-control form-control-lg" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                                            @error('end_date')
                                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        
                                                        <div class="col-md-6 mb-3">
                                                            <label for="profession_id" class="form-label fw-bold"><?= get_label('profession', 'Profession') ?></label>
                                                            <div class="input-group input-group-lg">
                                                                <select class="form-select form-select-lg" id="profession_id" name="profession_id">
                                                                    <option value=""><?= get_label('select_profession', 'Select Profession') ?></option>
                                                                    @foreach($professions as $profession)
                                                                        <option value="{{ $profession->id }}" {{ old('profession_id') == $profession->id ? 'selected' : '' }}>
                                                                            {{ $profession->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <button class="btn btn-outline-primary" type="button" id="create_profession_btn" data-bs-toggle="modal" data-bs-target="#create_profession_modal">
                                                                    <i class="bx bx-plus"></i>
                                                                </button>
                                                            </div>
                                                            @error('profession_id')
                                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        
                                                        <div class="col-md-12 mb-3">
                                                            <label for="description" class="form-label fw-bold"><?= get_label('description', 'Description') ?></label>
                                                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="<?= get_label('enter_contract_description', 'Enter contract description') ?>">{{ old('description') }}</textarea>
                                                            @error('description')
                                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                                            @enderror
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
                                            <div class="card bg-light border-success shadow-sm">
                                                <div class="card-body">
                                                    <h6 class="card-title text-success mb-3">
                                                        <i class="bx bx-user me-1"></i> Client & Project Information
                                                    </h6>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label for="client_id" class="form-label fw-bold"><?= get_label('client', 'Client') ?> <span class="text-danger">*</span></label>
                                                            <div class="input-group input-group-lg">
                                                                <select class="form-select form-select-lg js-example-basic-single" id="client_id" name="client_id" required data-placeholder="<?= get_label('select_client', 'Select Client') ?>">
                                                                    <option value=""><?= get_label('select_client', 'Select Client') ?></option>
                                                                    @foreach($clients as $client)
                                                                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }} data-profession="{{ $client->profession_id }}">
                                                                            {{ $client->first_name }} {{ $client->last_name }} 
                                                                            @if($client->company) ({{ $client->company }}) @endif
                                                                            @if($client->profession) - {{ $client->profession->name }} @endif
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <button class="btn btn-outline-primary" type="button" id="create_client_btn" onclick="window.location.href='{{ url('/clients/create') }}'" title="<?= get_label('add_new_client', 'Add New Client') ?>">
                                                                    <i class="bx bx-user-plus"></i>
                                                                </button>
                                                            </div>
                                                            <div class="form-text mt-2">
                                                                <i class="bx bx-info-circle me-1"></i> 
                                                                <?= get_label('client_profession_note', 'Select a client to automatically filter by their profession') ?>
                                                            </div>
                                                            @error('client_id')
                                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        
                                                        <div class="col-md-6 mb-3">
                                                            <label for="project_id" class="form-label fw-bold"><?= get_label('project', 'Project') ?> <span class="text-danger">*</span></label>
                                                            <div class="input-group input-group-lg">
                                                                <select class="form-select form-select-lg js-example-basic-single" id="project_id" name="project_id" required data-placeholder="<?= get_label('select_project', 'Select Project') ?>">
                                                                    <option value=""><?= get_label('select_project', 'Select Project') ?></option>
                                                                    @foreach($projects as $project)
                                                                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                                                            {{ $project->title }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <button class="btn btn-outline-primary" type="button" id="create_project_btn" data-bs-toggle="modal" data-bs-target="#create_project_modal">
                                                                    <i class="bx bx-plus"></i>
                                                                </button>
                                                            </div>
                                                            @error('project_id')
                                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        
                                                        <div class="col-md-6 mb-3">
                                                            <label for="contract_type_id" class="form-label fw-bold"><?= get_label('contract_type', 'Contract Type') ?> <span class="text-danger">*</span></label>
                                                            <select class="form-select form-select-lg" id="contract_type_id" name="contract_type_id" required>
                                                                <option value=""><?= get_label('select_contract_type', 'Select Contract Type') ?></option>
                                                                @foreach($contractTypes as $type)
                                                                    <option value="{{ $type->id }}" {{ old('contract_type_id') == $type->id ? 'selected' : '' }}>
                                                                        {{ $type->type }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('contract_type_id')
                                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                                            @enderror
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
                                            <div class="card bg-light border-info shadow-sm">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                        <h6 class="card-title text-info mb-0">
                                                            <i class="bx bx-package me-1"></i> Contract Items
                                                        </h6>
                                                        <div class="d-flex gap-2">
                                                            <div class="dropdown">
                                                                <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="<?= get_label('add_template_items', 'Add Template Items') ?>">
                                                                    <i class="bx bx-template"></i> Templates
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li><a class="dropdown-item add-template-btn" href="#" data-template="construction">Construction Materials</a></li>
                                                                    <li><a class="dropdown-item add-template-btn" href="#" data-template="electrical">Electrical Equipment</a></li>
                                                                    <li><a class="dropdown-item add-template-btn" href="#" data-template="plumbing">Plumbing Materials</a></li>
                                                                    <li><hr class="dropdown-divider"></li>
                                                                    <li><a class="dropdown-item add-template-btn" href="#" data-template="mechanical">Mechanical Equipment</a></li>
                                                                    <li><a class="dropdown-item add-template-btn" href="#" data-template="civil">Civil Engineering</a></li>
                                                                    <li><a class="dropdown-item add-template-btn" href="#" data-template="architectural">Architectural Elements</a></li>
                                                                </ul>
                                                            </div>
                                                            <button type="button" class="btn btn-sm btn-secondary" id="import_quantities_btn" title="<?= get_label('import_from_client_quantities', 'Import from Client Quantities') ?>">
                                                                <i class="bx bx-import"></i> Import Quantities
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-primary" id="add_item_btn">
                                                                <i class="bx bx-plus"></i> Add Item
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
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
                                            <div class="card bg-light border-warning shadow-sm">
                                                <div class="card-body">
                                                    <h6 class="card-title text-warning mb-3">
                                                        <i class="bx bx-sitemap me-1"></i> Workflow Assignment
                                                    </h6>
                                                    <p class="text-muted small mb-3">Assign users to different workflow stages for contract approval process</p>
                                                    
                                                    <!-- Professional Workflow Visualization -->
                                                    <div class="workflow-diagram mb-4 p-4 bg-white rounded border shadow-sm">
                                                        <div class="row text-center g-3">
                                                            <div class="col-2">
                                                                <div class="workflow-step p-3 bg-label-primary rounded">
                                                                    <div class="avatar avatar-lg mx-auto mb-2">
                                                                        <i class="bx bx-user fs-3"></i>
                                                                    </div>
                                                                    <small class="fw-bold">Site Supervisor</small>
                                                                </div>
                                                            </div>
                                                            <div class="col-2 d-flex align-items-center justify-content-center">
                                                                <i class="bx bx-right-arrow-alt text-primary fs-3"></i>
                                                            </div>
                                                            <div class="col-2">
                                                                <div class="workflow-step p-3 bg-label-warning rounded">
                                                                    <div class="avatar avatar-lg mx-auto mb-2">
                                                                        <i class="bx bx-check fs-3"></i>
                                                                    </div>
                                                                    <small class="fw-bold">Quantity Approver</small>
                                                                </div>
                                                            </div>
                                                            <div class="col-2 d-flex align-items-center justify-content-center">
                                                                <i class="bx bx-right-arrow-alt text-primary fs-3"></i>
                                                            </div>
                                                            <div class="col-2">
                                                                <div class="workflow-step p-3 bg-label-success rounded">
                                                                    <div class="avatar avatar-lg mx-auto mb-2">
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
                                                                <div class="workflow-step p-3 bg-label-info rounded">
                                                                    <div class="avatar avatar-lg mx-auto mb-2">
                                                                        <i class="bx bx-edit fs-3"></i>
                                                                    </div>
                                                                    <small class="fw-bold">Reviewer</small>
                                                                </div>
                                                            </div>
                                                            <div class="col-2 d-flex align-items-center justify-content-center">
                                                                <i class="bx bx-right-arrow-alt text-primary fs-3"></i>
                                                            </div>
                                                            <div class="col-2">
                                                                <div class="workflow-step p-3 bg-label-danger rounded">
                                                                    <div class="avatar avatar-lg mx-auto mb-2">
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
                                                    
                                                    <div class="row g-3">
                                                        <div class="col-md-4 mb-3">
                                                            <label for="site_supervisor_id" class="form-label fw-bold">Site Supervisor</label>
                                                            <select class="form-select js-example-basic-single" id="site_supervisor_id" name="site_supervisor_id" data-placeholder="Select Site Supervisor">
                                                                <option value=""><?= get_label('select_site_supervisor', 'Select Site Supervisor') ?></option>
                                                                @foreach($users as $user)
                                                                    <option value="{{ $user->id }}" {{ old('site_supervisor_id') == $user->id ? 'selected' : '' }}>
                                                                        {{ $user->first_name }} {{ $user->last_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('site_supervisor_id')
                                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        
                                                        <div class="col-md-4 mb-3">
                                                            <label for="quantity_approver_id" class="form-label fw-bold">Quantity Approver</label>
                                                            <select class="form-select js-example-basic-single" id="quantity_approver_id" name="quantity_approver_id" data-placeholder="Select Quantity Approver">
                                                                <option value=""><?= get_label('select_quantity_approver', 'Select Quantity Approver') ?></option>
                                                                @foreach($users as $user)
                                                                    <option value="{{ $user->id }}" {{ old('quantity_approver_id') == $user->id ? 'selected' : '' }}>
                                                                        {{ $user->first_name }} {{ $user->last_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('quantity_approver_id')
                                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        
                                                        <div class="col-md-4 mb-3">
                                                            <label for="accountant_id" class="form-label fw-bold">Accountant</label>
                                                            <select class="form-select js-example-basic-single" id="accountant_id" name="accountant_id" data-placeholder="Select Accountant">
                                                                <option value=""><?= get_label('select_accountant', 'Select Accountant') ?></option>
                                                                @foreach($users as $user)
                                                                    <option value="{{ $user->id }}" {{ old('accountant_id') == $user->id ? 'selected' : '' }}>
                                                                        {{ $user->first_name }} {{ $user->last_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('accountant_id')
                                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        
                                                        <div class="col-md-6 mb-3">
                                                            <label for="reviewer_id" class="form-label fw-bold">Reviewer</label>
                                                            <select class="form-select js-example-basic-single" id="reviewer_id" name="reviewer_id" data-placeholder="Select Reviewer">
                                                                <option value=""><?= get_label('select_reviewer', 'Select Reviewer') ?></option>
                                                                @foreach($users as $user)
                                                                    <option value="{{ $user->id }}" {{ old('reviewer_id') == $user->id ? 'selected' : '' }}>
                                                                        {{ $user->first_name }} {{ $user->last_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('reviewer_id')
                                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        
                                                        <div class="col-md-6 mb-3">
                                                            <label for="final_approver_id" class="form-label fw-bold">Final Approver</label>
                                                            <select class="form-select js-example-basic-single" id="final_approver_id" name="final_approver_id" data-placeholder="Select Final Approver">
                                                                <option value=""><?= get_label('select_final_approver', 'Select Final Approver') ?></option>
                                                                @foreach($users as $user)
                                                                    <option value="{{ $user->id }}" {{ old('final_approver_id') == $user->id ? 'selected' : '' }}>
                                                                        {{ $user->first_name }} {{ $user->last_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('final_approver_id')
                                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                                            @enderror
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
                                            <div class="card bg-light border-secondary shadow-sm">
                                                <div class="card-body">
                                                    <h6 class="card-title text-secondary mb-3">
                                                        <i class="bx bx-cog me-1"></i> Additional Settings
                                                    </h6>
                                                    <div class="row g-4">
                                                        <div class="col-md-12 mb-3">
                                                            <label for="extracts" class="form-label fw-bold">Link Existing Extracts</label>
                                                            <select class="form-select js-example-basic-multiple" id="extracts" name="extracts[]" multiple data-placeholder="Select extracts to link to this contract">
                                                                @foreach($extracts as $extract)
                                                                    <option value="{{ $extract->id }}" {{ in_array($extract->id, old('extracts', [])) ? 'selected' : '' }}>
                                                                        {{ $extract->name }} ({{ ucfirst($extract->type) }}) - {{ format_currency($extract->final_total) }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <div class="form-text mt-2">Select existing extracts that should be linked to this contract. You can also create new extracts after contract creation.</div>
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
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-end gap-3 pt-3">
                                        <a href="{{ route('contracts.index') }}" class="btn btn-lg btn-secondary px-4">
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
        /* Enhanced tab styles */
        .nav-tabs .nav-link {
            border: 1px solid transparent;
            border-top-left-radius: 0.375rem;
            border-top-right-radius: 0.375rem;
            padding: 1rem 1.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
            font-size: 1rem;
        }
        
        .nav-tabs .nav-link.active {
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
            color: #696cff;
            border-bottom: 1px solid transparent;
        }
        
        .nav-tabs .nav-link:hover:not(.active) {
            background-color: #f8f9fa;
            border-color: #e9ecef;
        }
        
        .nav-tabs .nav-link i {
            margin-right: 0.5rem;
            font-size: 1.1em;
        }
        
        .tab-content {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 1.5rem;
            border-top-left-radius: 0;
        }
        
        /* Card enhancements */
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .card.bg-light {
            background-color: #f8f9fa !important;
        }
        
        /* Workflow diagram enhancements */
        .workflow-diagram {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
        }
        
        .workflow-step {
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .workflow-step:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        .workflow-step .avatar {
            width: 60px;
            height: 60px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }
        
        .workflow-step small {
            font-size: 0.75rem;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        /* Button enhancements */
        .btn {
            border-radius: 0.5rem;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            transition: transform 0.2s ease;
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        /* Form control enhancements */
        .form-control-lg {
            padding: 1rem 1.25rem;
            font-size: 1.25rem;
            line-height: 1.5;
        }
        
        .form-select-lg {
            padding: 1rem 2.25rem 1rem 1.25rem;
            font-size: 1.25rem;
            line-height: 1.5;
        }
        
        .form-label {
            font-weight: 600;
            color: #495057;
        }
        
        /* Table enhancements */
        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }
        
        /* Responsive adjustments */
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
    </style>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for enhanced dropdowns
            $('.js-example-basic-single').select2({
                placeholder: function() {
                    return $(this).data('placeholder') || 'Select an option';
                },
                allowClear: true,
                width: '100%'
            });
            
            $('.js-example-basic-multiple').select2({
                placeholder: function() {
                    return $(this).data('placeholder') || 'Select options';
                },
                allowClear: true,
                width: '100%'
            });

            // Date format conversion helper
            function convertDateFormat(dateString) {
                if (!dateString || !dateString.includes('-')) {
                    return dateString;
                }
                
                var parts = dateString.split('-');
                if (parts.length !== 3) {
                    return dateString;
                }

                // Check if it's already in YYYY-MM-DD format
                if (parts[0].length === 4 && parts[2].length <= 2) {
                    return dateString; // Already in correct format
                }

                // Assume it's DD-MM-YYYY format and convert to YYYY-MM-DD
                if (parts[2].length === 4) { // Year is at the end
                    return parts[2] + '-' + parts[1] + '-' + parts[0];
                }

                // If not clear, try to determine by looking at values
                var day = parseInt(parts[0]);
                var month = parseInt(parts[1]);
                var year = parseInt(parts[2]);

                // If first part is > 12, likely DD-MM-YYYY
                if (day > 12) {
                    return parts[2] + '-' + parts[1] + '-' + parts[0];
                }

                // If second part is > 12, likely MM-DD-YYYY
                if (month > 12) {
                    return parts[2] + '-' + parts[0] + '-' + parts[1];
                }

                // Default assumption: MM-DD-YYYY -> YYYY-MM-DD
                return parts[2] + '-' + parts[0] + '-' + parts[1];
            }

            // Date validation and format conversion
            $('#start_date, #end_date').on('change', function() {
                // Convert date to proper format if needed
                var dateValue = $(this).val();
                var convertedDate = convertDateFormat(dateValue);

                if (convertedDate !== dateValue) {
                    $(this).val(convertedDate);
                }

                var startDate = new Date($('#start_date').val());
                var endDate = new Date($('#end_date').val());

                if (startDate && endDate && startDate > endDate) {
                    toastr.error('<?= get_label('end_date_must_be_after_start_date', 'End date must be after start date') ?>');
                    $(this).val('');
                }
            });

            // Form validation and submission
            $('#contract_form').on('submit', function(e) {
                e.preventDefault();
                
                // Validate that at least one item is selected
                var hasItems = $('.item-select').filter(function() {
                    return $(this).val() !== '';
                }).length > 0;
                
                if (!hasItems) {
                    toastr.error('<?= get_label('please_add_at_least_one_item', 'Please add at least one item to the contract') ?>');
                    return false;
                }
                
                var submit_btn = $(this).find('#submit_btn');
                var btn_html = submit_btn.html();
                var autoCreateProject = $('#auto_create_project').is(':checked');
                var formData = $(this).serialize();
                
                // Add auto-create project flag to form data
                if (autoCreateProject) {
                    formData += '&auto_create_project=1';
                }
                
                // Add default workflow flag to form data
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
                            
                            // Handle auto-create project if requested
                            if (autoCreateProject && response.project_id) {
                                toastr.success('<?= get_label('project_created_automatically', 'Project created automatically') ?>');
                            }
                            
                            // Redirect to contract details page
                            window.location.href = '/contracts/' + response.id;
                        } else {
                            submit_btn.html(btn_html).attr('disabled', false);
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        submit_btn.html(btn_html).attr('disabled', false);
                        var errors = xhr.responseJSON?.errors;
                        if (errors) {
                            $.each(errors, function(field, messages) {
                                // Handle nested item fields
                                if (field.startsWith('items.')) {
                                    // For item validation errors, highlight the specific row
                                    var fieldParts = field.split('.');
                                    var rowIndex = fieldParts[1];
                                    var rowField = fieldParts[2];
                                    
                                    // Find the corresponding row and field
                                    var targetRow = $('[name^="items[' + rowIndex + ']"][name$="[' + rowField + ']"]');
                                    if (targetRow.length > 0) {
                                        targetRow.addClass('is-invalid');
                                        targetRow.after('<div class="invalid-feedback d-block">' + messages[0] + '</div>');
                                    } else {
                                        // Fallback to general error display
                                        toastr.error(messages[0]);
                                    }
                                } else {
                                    $('#' + field).addClass('is-invalid');
                                    $('#' + field).after('<div class="invalid-feedback d-block">' + messages[0] + '</div>');
                                }
                            });
                            toastr.error('<?= get_label('validation_errors', 'Please fix the validation errors') ?>');
                        } else {
                            toastr.error('<?= get_label('something_went_wrong', 'Something went wrong') ?>');
                        }
                    }
                });
            });

            // Professional client-profession bidirectional filtering
            $('#client_id').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var professionId = selectedOption.data('profession');
                
                if (professionId) {
                    // Auto-select the client's profession
                    $('#profession_id').val(professionId).trigger('change');
                    
                    // Show success notification
                    toastr.info('<?= get_label('profession_auto_selected', 'Profession automatically selected based on client') ?>');
                } else {
                    // Clear profession if client has none
                    $('#profession_id').val('').trigger('change');
                }
            });
            
            // Filter clients by selected profession
            $('#profession_id').on('change', function() {
                var selectedProfession = $(this).val();
                
                $('#client_id option').each(function() {
                    var clientProfession = $(this).data('profession');
                    
                    if (selectedProfession === '' || clientProfession == selectedProfession) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
                
                // Reset client selection if current client doesn't match profession
                var currentClient = $('#client_id').val();
                if (currentClient) {
                    var currentClientProfession = $('#client_id option[value="' + currentClient + '"]').data('profession');
                    if (selectedProfession !== '' && currentClientProfession != selectedProfession) {
                        $('#client_id').val('').trigger('change');
                        toastr.info('<?= get_label('client_reset_due_to_profession', 'Client selection cleared due to profession mismatch') ?>');
                    }
                }
                
                $('#client_id').trigger('change');
            });

            // Items functionality
            var itemCounter = 0;
            
            // Import quantities button click
            $('#import_quantities_btn').on('click', function() {
                var clientId = $('#client_id').val();
                if (!clientId) {
                    toastr.error('<?= get_label('please_select_client_first', 'Please select a client first to import quantities') ?>');
                    return;
                }
                
                // Show modal to select quantities to import
                showImportQuantitiesModal(clientId);
            });

            // Add item button click
            $('#add_item_btn').on('click', function() {
                // Add a temporary visual feedback
                const btn = $(this);
                const originalText = btn.html();
                btn.html('<i class="bx bx-loader-alt bx-spin me-1"></i> Adding...').prop('disabled', true);
                
                setTimeout(function() {
                    addItemRow();
                    btn.html(originalText).prop('disabled', false);
                }, 100);
            });

            // Function to show import quantities modal
            function showImportQuantitiesModal(clientId) {
                // Show loading indicator
                const importBtn = $('#import_quantities_btn');
                const originalText = importBtn.html();
                importBtn.html('<i class="bx bx-loader-alt bx-spin me-1"></i> Loading...').prop('disabled', true);
                
                // Fetch client's quantities via AJAX
                $.ajax({
                    url: '{{ route("contracts.getClientQuantities", ":clientId") }}'.replace(':clientId', clientId),
                    method: 'GET',
                    success: function(response) {
                        // Re-enable button
                        importBtn.html(originalText).prop('disabled', false);
                        
                        if (response.error) {
                            toastr.error(response.message);
                            return;
                        }
                        
                        var quantities = response.quantities;
                        if (quantities.length === 0) {
                            toastr.info('<?= get_label('no_quantities_available_for_import', 'No quantities available for import from this client') ?>');
                            return;
                        }
                        
                        // Create modal HTML
                        var modalHtml = `
                            <div class="modal fade" id="import_quantities_modal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Import Client Quantities</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Select quantities to import into this contract:</p>
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th width="5%"><input type="checkbox" id="select_all_quantities"></th>
                                                            <th width="20%">Item</th>
                                                            <th width="25%">Description</th>
                                                            <th width="10%">Quantity</th>
                                                            <th width="10%">Unit</th>
                                                            <th width="15%">Unit Price</th>
                                                            <th width="15%">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                        `;
                        
                        quantities.forEach(function(quantity) {
                            var total = (parseFloat(quantity.requested_quantity || 0) * parseFloat(quantity.unit_price || 0)).toFixed(2);
                            modalHtml += `
                                <tr>
                                    <td><input type="checkbox" class="quantity-checkbox" data-quantity='${JSON.stringify(quantity).replace(/'/g, "&apos;")}'>`;
                            modalHtml += `<td>${quantity.item_description || 'General Item'}</td>
                                    <td>${quantity.description || quantity.item_description || 'No Description'}</td>
                                    <td>${quantity.requested_quantity || 0}</td>
                                    <td>${quantity.unit || 'Not Specified'}</td>
                                    <td>${formatPrice(quantity.unit_price || 0)}</td>
                                    <td>${formatPrice(total)}</td>
                                </tr>
                            `;
                        });
                        
                        modalHtml += `
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary" id="import_selected_quantities">Import Selected</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        // Remove any existing import modal
                        $('#import_quantities_modal').remove();
                        
                        // Add modal to body
                        $('body').append(modalHtml);
                        
                        // Initialize modal
                        var modal = new bootstrap.Modal(document.getElementById('import_quantities_modal'));
                        modal.show();
                        
                        // Select all checkbox functionality
                        $('#select_all_quantities').on('change', function() {
                            $('.quantity-checkbox').prop('checked', $(this).is(':checked'));
                        });
                        
                        // Import selected button functionality
                        $('#import_selected_quantities').off('click').on('click', function() {
                            var selectedQuantities = $('.quantity-checkbox:checked');
                            if (selectedQuantities.length === 0) {
                                toastr.error('<?= get_label('please_select_at_least_one_quantity', 'Please select at least one quantity to import') ?>');
                                return;
                            }
                            
                            selectedQuantities.each(function() {
                                var quantityData = $(this).data('quantity');
                                addItemRow({
                                    item_id: quantityData.item_id || null,
                                    title: quantityData.item_description || 'Imported Item',
                                    description: quantityData.description || quantityData.item_description || '',
                                    unit: quantityData.unit || '',
                                    quantity: quantityData.requested_quantity || 0,
                                    unit_price: quantityData.unit_price || 0
                                });
                            });
                            
                            modal.hide();
                            $('#import_quantities_modal').remove();
                            toastr.success(selectedQuantities.length + ' quantities imported successfully');
                        });
                        
                        // Close modal cleanup
                        $('#import_quantities_modal').on('hidden.bs.modal', function () {
                            $(this).remove();
                        });
                    },
                    error: function(xhr) {
                        // Re-enable button
                        importBtn.html(originalText).prop('disabled', false);
                        
                        if (xhr.status === 404) {
                            toastr.error('<?= get_label('client_not_found', 'Client not found or no quantities available') ?>');
                        } else if (xhr.status === 500) {
                            toastr.error('<?= get_label('server_error', 'Server error occurred while fetching quantities') ?>');
                        } else {
                            toastr.error('<?= get_label('error_fetching_quantities', 'Error fetching client quantities') ?>');
                        }
                        console.error(xhr);
                    }
                });
            }

            // Function to add item row
            function addItemRow(itemData = null) {
                itemCounter++;
                var itemId = 'item_' + itemCounter;
                
                // Prepare initial values based on itemData
                var item_id = itemData ? itemData.item_id : '';
                var title = itemData ? itemData.title : '';
                var description = itemData ? itemData.description : '';
                var unit = itemData ? itemData.unit : '';
                var quantity = itemData ? itemData.quantity : '';
                var unit_price = itemData ? itemData.unit_price : '';
                var total = '';
                
                // Calculate total if we have both quantity and price
                if (itemData && itemData.quantity && itemData.unit_price) {
                    total = formatPrice(parseFloat(itemData.quantity) * parseFloat(itemData.unit_price));
                }
                
                var row = `
                    <tr id="${itemId}">
                        <td>
                            <select class="form-select item-select" name="items[${itemCounter}][item_id]">
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
                            <textarea class="form-control item-description" name="items[${itemCounter}][description]" rows="2">${description}</textarea>
                        </td>
                        <td>
                            <input type="text" class="form-control item-unit" name="items[${itemCounter}][unit]" value="${unit}">
                        </td>
                        <td>
                            <input type="number" class="form-control item-quantity" name="items[${itemCounter}][quantity]" min="0" step="0.01" value="${quantity}">
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text"><?= $general_settings['currency_symbol'] ?? '$' ?></span>
                                <input type="text" class="form-control item-price" name="items[${itemCounter}][unit_price]" value="${unit_price}">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text"><?= $general_settings['currency_symbol'] ?? '$' ?></span>
                                <input type="text" class="form-control item-total" name="items[${itemCounter}][total]" value="${total}" readonly>
                            </div>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-item-btn">
                                <i class="bx bx-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                
                $('#items_table_body').append(row);
                
                // Initialize select2 for the new item select
                var newSelect = $('#' + itemId + ' .item-select');
                // Wait a bit to ensure DOM is ready
                setTimeout(function() {
                    newSelect.select2({
                        placeholder: 'Select Item',
                        allowClear: true,
                        width: '100%',
                        minimumResultsForSearch: 10  // Only show search if more than 10 items
                    });
                    
                    // Using jQuery event delegation for dynamically added elements
                    setTimeout(function() {
                        newSelect.on('select2:select', function(e) {
                            // Handle selection change
                            var selectedOption = $(this).find('option:selected');
                            var description = selectedOption.data('description');
                            var unit = selectedOption.data('unit');
                            var price = selectedOption.data('price');
                            
                            var row = $(this).closest('tr');
                            row.find('.item-description').val(description);
                            row.find('.item-unit').val(unit);
                            row.find('.item-price').val(formatPrice(price));
                            
                            calculateItemTotal(row.attr('id'));
                        });
                    }, 100);
                }, 50);
                
                // If itemData was provided, trigger the change to populate related fields
                if (itemData && (itemData.item_id || itemData.title)) {
                    newSelect.trigger('change');
                }
                
                // Filter items by selected profession
                filterItemsByProfession();
                
                // Bind events for the new row
                bindItemEvents(itemId);
            }

            // Filter items by profession
            function filterItemsByProfession() {
                var selectedProfession = $('#profession_id').val();
                
                $('.item-select').each(function() {
                    var selectElement = $(this);
                    var currentSelect2 = selectElement.data('select2');
                    var currentSelection = selectElement.val();
                    
                    // If Select2 is initialized, destroy it temporarily
                    if (currentSelect2) {
                        selectElement.select2('destroy');
                    }
                    
                    // Show/hide options based on profession
                    selectElement.find('option').each(function() {
                        var option = $(this);
                        var itemProfession = option.data('profession');
                        
                        if (selectedProfession === '' || itemProfession == selectedProfession) {
                            option.show().prop('disabled', false);
                        } else {
                            option.hide().prop('disabled', true);
                        }
                    });
                    
                    // Reinitialize Select2
                    selectElement.select2({
                        placeholder: 'Select Item',
                        allowClear: true,
                        width: '100%',
                        minimumResultsForSearch: 10
                    });
                    
                    // Restore the previous selection if it's still valid
                    if (currentSelection) {
                        var selectedOption = selectElement.find('option[value="' + currentSelection + '"]');
                        if (selectedOption.length && !selectedOption.prop('disabled')) {
                            selectElement.val(currentSelection);
                            // Trigger change to populate related fields
                            var description = selectedOption.data('description');
                            var unit = selectedOption.data('unit');
                            var price = selectedOption.data('price');
                            
                            var row = selectElement.closest('tr');
                            row.find('.item-description').val(description);
                            row.find('.item-unit').val(unit);
                            row.find('.item-price').val(formatPrice(price));
                            
                            // Update the total
                            calculateItemTotal(row.attr('id'));
                        } else {
                            // If the previous selection is no longer valid, clear it
                            selectElement.val(null);
                            // Clear associated fields
                            var row = selectElement.closest('tr');
                            row.find('.item-description').val('');
                            row.find('.item-unit').val('');
                            row.find('.item-price').val('');
                            row.find('.item-total').val('');
                        }
                    }
                });
            }

            // Bind events for item row
            function bindItemEvents(itemId) {
                var row = $('#' + itemId);
                
                // Quantity change
                row.find('.item-quantity').on('input', function() {
                    calculateItemTotal(itemId);
                });

                // Remove item
                row.find('.remove-item-btn').on('click', function() {
                    $('#' + itemId).remove();
                    calculateItemsTotal();
                });
            }

            // Calculate item total
            function calculateItemTotal(itemId) {
                var row = $('#' + itemId);
                var quantity = parseFloat(row.find('.item-quantity').val()) || 0;
                var price = parseFloat(row.find('.item-price').val().replace(/[^0-9.]/g, '')) || 0;
                var total = quantity * price;
                
                row.find('.item-total').val(formatPrice(total));
                calculateItemsTotal();
            }

            // Calculate total for all items
            function calculateItemsTotal() {
                var total = 0;
                $('.item-total').each(function() {
                    var value = parseFloat($(this).val().replace(/[^0-9.]/g, '')) || 0;
                    total += value;
                });
                
                $('#items_total_amount').text(formatPrice(total));
                
                // Update contract value if it's empty
                if (!$('#value').val()) {
                    $('#value').val(formatPrice(total).replace(/[^0-9.]/g, ''));
                }
            }

            // Format price
            function formatPrice(price) {
                return parseFloat(price).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            }

            // Update item filtering when profession changes
            $('#profession_id').on('change', function() {
                setTimeout(function() {
                    filterItemsByProfession();
                }, 100); // Small delay to ensure Select2 is fully initialized
            });
            
            // Initialize item filtering when document is ready
            $(document).ready(function() {
                setTimeout(function() {
                    filterItemsByProfession();
                }, 300); // Slightly longer delay to ensure everything is loaded
            });

            // Initialize with one item row
            $(document).ready(function() {
                // Small delay to ensure DOM is fully loaded
                setTimeout(function() {
                    addItemRow();
                    // Ensure profession filtering is applied after initial render
                    setTimeout(function() {
                        filterItemsByProfession();
                    }, 200);
                }, 100);
            });
            
            // Load default workflow assignments if available
            function loadDefaultWorkflow() {
                // This can be extended to fetch default workflow from backend
                // For now, we'll show a notification that default workflow will be applied
                console.log('Default workflow will be applied automatically');
            }

            // Function to add template items
            function addTemplateItems(templateType) {
                // Define comprehensive templates with professional items
                var templates = {
                    construction: [
                        {title: 'Cement', description: 'Portland cement 50kg bags', unit: 'bag', quantity: 100, unit_price: 15},
                        {title: 'Sand', description: 'Construction sand', unit: 'm³', quantity: 10, unit_price: 80},
                        {title: 'Steel Bars', description: 'Reinforcement steel bars', unit: 'ton', quantity: 5, unit_price: 800},
                        {title: 'Concrete Blocks', description: 'Standard concrete blocks', unit: 'piece', quantity: 500, unit_price: 5},
                        {title: 'Bricks', description: 'Standard building bricks', unit: 'piece', quantity: 1000, unit_price: 2},
                        {title: 'Aggregate', description: 'Construction aggregate material', unit: 'm³', quantity: 15, unit_price: 70}
                    ],
                    electrical: [
                        {title: 'Cables', description: 'Electrical cables 2.5mm²', unit: 'meter', quantity: 500, unit_price: 3},
                        {title: 'Switches', description: 'Wall switches', unit: 'piece', quantity: 20, unit_price: 5},
                        {title: 'Outlets', description: 'Power outlets', unit: 'piece', quantity: 15, unit_price: 7},
                        {title: 'Circuit Breakers', description: 'Main circuit breakers', unit: 'piece', quantity: 5, unit_price: 25},
                        {title: 'Lighting Fixtures', description: 'LED lighting fixtures', unit: 'piece', quantity: 10, unit_price: 35},
                        {title: 'Distribution Board', description: 'Main distribution board', unit: 'piece', quantity: 2, unit_price: 150}
                    ],
                    plumbing: [
                        {title: 'Pipes', description: 'PVC pipes 4 inch', unit: 'meter', quantity: 100, unit_price: 12},
                        {title: 'Fittings', description: 'Pipe fittings', unit: 'piece', quantity: 50, unit_price: 8},
                        {title: 'Valves', description: 'Water valves', unit: 'piece', quantity: 10, unit_price: 25},
                        {title: 'Toilets', description: 'Standard ceramic toilets', unit: 'piece', quantity: 5, unit_price: 120},
                        {title: 'Sinks', description: 'Kitchen/bathroom sinks', unit: 'piece', quantity: 8, unit_price: 75},
                        {title: 'Water Heater', description: 'Electric water heater', unit: 'piece', quantity: 2, unit_price: 250}
                    ],
                    mechanical: [
                        {title: 'HVAC Units', description: 'Air conditioning units', unit: 'piece', quantity: 3, unit_price: 800},
                        {title: 'Pumps', description: 'Water pumps', unit: 'piece', quantity: 2, unit_price: 400},
                        {title: 'Fans', description: 'Ventilation fans', unit: 'piece', quantity: 10, unit_price: 60},
                        {title: 'Filters', description: 'Air filtration systems', unit: 'piece', quantity: 5, unit_price: 45},
                        {title: 'Compressors', description: 'Industrial compressors', unit: 'piece', quantity: 1, unit_price: 1200},
                        {title: 'Motors', description: 'Electric motors', unit: 'piece', quantity: 4, unit_price: 300}
                    ],
                    civil: [
                        {title: 'Asphalt', description: 'Road asphalt mix', unit: 'ton', quantity: 20, unit_price: 100},
                        {title: 'Gravel', description: 'Construction gravel', unit: 'm³', quantity: 25, unit_price: 45},
                        {title: 'Rebar', description: 'Reinforcement bars', unit: 'ton', quantity: 8, unit_price: 900},
                        {title: 'Concrete', description: 'Ready-mix concrete', unit: 'm³', quantity: 30, unit_price: 120},
                        {title: 'Drainage Pipes', description: 'Storm drainage pipes', unit: 'meter', quantity: 200, unit_price: 25},
                        {title: 'Manholes', description: 'Concrete manholes', unit: 'piece', quantity: 5, unit_price: 200}
                    ],
                    architectural: [
                        {title: 'Tiles', description: 'Ceramic floor tiles', unit: 'm²', quantity: 200, unit_price: 15},
                        {title: 'Doors', description: 'Interior wooden doors', unit: 'piece', quantity: 8, unit_price: 150},
                        {title: 'Windows', description: 'Aluminum frame windows', unit: 'piece', quantity: 12, unit_price: 200},
                        {title: 'Paint', description: 'Premium interior paint', unit: 'liter', quantity: 50, unit_price: 12},
                        {title: 'Flooring', description: 'Laminate flooring', unit: 'm²', quantity: 150, unit_price: 20},
                        {title: 'Ceiling Tiles', description: 'Acoustic ceiling tiles', unit: 'm²', quantity: 100, unit_price: 8}
                    ]
                };
                
                var selectedTemplate = templates[templateType] || templates.construction;
                
                selectedTemplate.forEach(function(item) {
                    addItemRow({
                        item_id: null, // Will be added as free-form item
                        title: item.title,
                        description: item.description,
                        unit: item.unit,
                        quantity: item.quantity,
                        unit_price: item.unit_price
                    });
                });
                
                toastr.success(templateType.charAt(0).toUpperCase() + templateType.slice(1) + ' template items added successfully');
            }
            
            // Add template selector functionality
            $(document).on('click', '.add-template-btn', function(e) {
                e.preventDefault();
                var templateType = $(this).data('template');
                addTemplateItems(templateType);
            });
            
            // Initialize tabs
            $('#contractTabs a').on('click', function (e) {
                e.preventDefault();
                $(this).tab('show');
            });
            
            // Store the currently selected tab in localStorage
            $(document).on('shown.bs.tab', '#contractTabs a', function (e) {
                localStorage.setItem('contractTab', $(e.target).attr('href'));
            });
            
            // On page load, show the last selected tab if exists
            var lastTab = localStorage.getItem('contractTab');
            if (lastTab) {
                $('#contractTabs a[href="' + lastTab + '"]').tab('show');
            }
            
            // Create profession form submission
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
                            var newOption = new Option(response.data.name, response.data.id, true, true);
                            $('#profession_id').append(newOption).trigger('change');
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('#profession_' + key + '_error').text(value[0]);
                                $('#profession_' + key).addClass('is-invalid');
                            });
                        } else {
                            toastr.error('<?= get_label('something_went_wrong', 'Something went wrong') ?>');
                        }
                    }
                });
            });

            // Create project form submission
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
                            var newOption = new Option(response.data.title, response.data.id, true, true);
                            $('#project_id').append(newOption).trigger('change');
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('#project_' + key + '_error').text(value[0]);
                                $('#project_' + key).addClass('is-invalid');
                            });
                        } else {
                            toastr.error('<?= get_label('something_went_wrong', 'Something went wrong') ?>');
                        }
                    }
                });
            });

            // Clear validation errors when modals are closed
            $('#create_profession_modal, #create_project_modal').on('hidden.bs.modal', function() {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
            });
        });
    </script>
    @endpush
@endsection