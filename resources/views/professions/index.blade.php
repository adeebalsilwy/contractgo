@extends('layout')
@section('title')
    <?= get_label('professions', 'Professions') ?>
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1">
                        <li class="breadcrumb-item">
                            <a href="{{ url('home') }}"><?= get_label('home', 'Home') ?></a>
                        </li>
                        <li class="breadcrumb-item active">
                            <?= get_label('professions', 'Professions') ?>
                        </li>
                    </ol>
                </nav>
                <h4 class="fw-bold mb-0">
                    <i class="menu-icon tf-icons bx bx-briefcase text-primary me-2"></i>
                    <?= get_label('professions_management', 'Professions Management') ?>
                </h4>
                <p class="text-muted mb-0"><?= get_label('manage_professional_professions', 'Manage professional professions and categories') ?></p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary" id="add-profession-btn" data-bs-toggle="modal" data-bs-target="#create_profession_modal">
                    <i class='bx bx-plus me-1'></i>
                    <?= get_label('create_profession', 'Create Profession') ?>
                </button>
                <button type="button" class="btn btn-outline-secondary" id="import-professions-btn" data-bs-toggle="modal" data-bs-target="#import_professions_modal">
                    <i class='bx bx-import me-1'></i>
                    <?= get_label('import_professions', 'Import Professions') ?>
                </button>
                <button type="button" class="btn btn-outline-info" id="add-yemeni-professions-btn">
                    <i class='bx bx-flag me-1'></i>
                    <?= get_label('add_yemeni_professions', 'Add Yemeni Professions') ?>
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-start border-primary border-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bx bx-briefcase bx-lg text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0" id="total-professions">{{ $professions->total() ?? 0 }}</h3>
                                <small class="text-muted"><?= get_label('total_professions', 'Total Professions') ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-start border-success border-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bx bx-check-double bx-lg text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0" id="active-professions">0</h3>
                                <small class="text-muted"><?= get_label('active_professions', 'Active Professions') ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-start border-warning border-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bx bx-calendar bx-lg text-warning"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0" id="recent-professions">0</h3>
                                <small class="text-muted"><?= get_label('recent_professions', 'Added This Month') ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-start border-info border-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bx bx-category bx-lg text-info"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0" id="categories-count">0</h3>
                                <small class="text-muted"><?= get_label('categories', 'Categories') ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="card">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-table me-2"></i>
                        <?= get_label('professions_list', 'Professions List') ?>
                    </h5>
                    <div class="d-flex gap-2">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <span class="input-group-text"><i class="bx bx-search"></i></span>
                            <input type="text" class="form-control" id="professions-search" placeholder="<?= get_label('search_professions', 'Search professions...') ?>">
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="refresh-professions">
                            <i class="bx bx-refresh me-1"></i>
                            <?= get_label('refresh', 'Refresh') ?>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="professions-table">
                        <thead class="table-light">
                            <tr>
                                <th><?= get_label('id', 'ID') ?></th>
                                <th><?= get_label('name', 'Name') ?></th>
                                <th><?= get_label('description', 'Description') ?></th>
                                <th><?= get_label('category', 'Category') ?></th>
                                <th><?= get_label('associated_clients', 'Associated Clients') ?></th>
                                <th><?= get_label('created_at', 'Created At') ?></th>
                                <th><?= get_label('status', 'Status') ?></th>
                                <th><?= get_label('actions', 'Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($professions as $profession)
                                <tr>
                                    <td>{{ $profession->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-initial rounded-circle bg-label-primary">{{ substr($profession->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $profession->name }}</h6>
                                                <small class="text-muted">ID: {{ $profession->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-truncate d-inline-block" style="max-width: 200px;">
                                            {{ $profession->description ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-secondary">General</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-info">{{ $profession->clients_count ?? 0 }} <?= get_label('clients', 'Clients') ?></span>
                                    </td>
                                    <td>{{ format_date($profession->created_at, true) }}</td>
                                    <td>
                                        <span class="badge bg-success"><?= get_label('active', 'Active') ?></span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item edit-profession" href="javascript:void(0);" 
                                                   data-id="{{ $profession->id }}" 
                                                   data-name="{{ $profession->name }}" 
                                                   data-description="{{ $profession->description }}">
                                                    <i class="bx bx-edit-alt me-1"></i> <?= get_label('edit', 'Edit') ?>
                                                </a>
                                                <a class="dropdown-item view-clients" href="javascript:void(0);" data-id="{{ $profession->id }}">
                                                    <i class="bx bx-group me-1"></i> <?= get_label('view_clients', 'View Clients') ?>
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item delete" href="javascript:void(0);" data-id="{{ $profession->id }}" data-type="professions">
                                                    <i class="bx bx-trash me-1"></i> <?= get_label('delete', 'Delete') ?>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <div class="py-5">
                                            <i class="bx bx-briefcase bx-xxl text-muted"></i>
                                            <h4 class="mt-3"><?= get_label('no_professions_found', 'No Professions Found') ?></h4>
                                            <p class="text-muted"><?= get_label('create_first_profession', 'Create your first profession to get started') ?></p>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create_profession_modal">
                                                <i class="bx bx-plus me-1"></i> <?= get_label('create_profession', 'Create Profession') ?>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($professions->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            <?= get_label('showing', 'Showing') ?> {{ $professions->firstItem() }} <?= get_label('to', 'to') ?> {{ $professions->lastItem() }} <?= get_label('of', 'of') ?> {{ $professions->total() }} <?= get_label('entries', 'entries') ?>
                        </div>
                        <div>
                            {{ $professions->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Create Profession Modal -->
    <div class="modal fade" id="create_profession_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="create_profession_form">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bx bx-briefcase me-2"></i>
                            <?= get_label('create_profession', 'Create Profession') ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label"><?= get_label('profession_name', 'Profession Name') ?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required placeholder="<?= get_label('enter_profession_name', 'Enter profession name') ?>">
                                    <div class="invalid-feedback" id="name_error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="category" class="form-label"><?= get_label('category', 'Category') ?></label>
                                    <select class="form-select" id="category" name="category">
                                        <option value=""><?= get_label('select_category', 'Select Category') ?></option>
                                        <option value="construction"><?= get_label('construction', 'Construction') ?></option>
                                        <option value="services"><?= get_label('services', 'Services') ?></option>
                                        <option value="technology"><?= get_label('technology', 'Technology') ?></option>
                                        <option value="healthcare"><?= get_label('healthcare', 'Healthcare') ?></option>
                                        <option value="education"><?= get_label('education', 'Education') ?></option>
                                        <option value="other"><?= get_label('other', 'Other') ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label"><?= get_label('description', 'Description') ?></label>
                            <textarea class="form-control description" id="description" name="description" rows="4" placeholder="<?= get_label('enter_profession_description', 'Enter profession description') ?>"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="keywords" class="form-label"><?= get_label('keywords', 'Keywords') ?></label>
                            <input type="text" class="form-control" id="keywords" name="keywords" placeholder="<?= get_label('enter_keywords', 'Enter keywords separated by commas') ?>">
                            <small class="text-muted"><?= get_label('keywords_help', 'Enter relevant keywords for better searchability') ?></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?= get_label('close', 'Close') ?></button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i> <?= get_label('create', 'Create') ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Profession Modal -->
    <div class="modal fade" id="edit_profession_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="edit_profession_form">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_profession_id" name="id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bx bx-edit me-2"></i>
                            <?= get_label('edit_profession', 'Edit Profession') ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="edit_name" class="form-label"><?= get_label('profession_name', 'Profession Name') ?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_name" name="name" required>
                                    <div class="invalid-feedback" id="edit_name_error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="edit_category" class="form-label"><?= get_label('category', 'Category') ?></label>
                                    <select class="form-select" id="edit_category" name="category">
                                        <option value=""><?= get_label('select_category', 'Select Category') ?></option>
                                        <option value="construction"><?= get_label('construction', 'Construction') ?></option>
                                        <option value="services"><?= get_label('services', 'Services') ?></option>
                                        <option value="technology"><?= get_label('technology', 'Technology') ?></option>
                                        <option value="healthcare"><?= get_label('healthcare', 'Healthcare') ?></option>
                                        <option value="education"><?= get_label('education', 'Education') ?></option>
                                        <option value="other"><?= get_label('other', 'Other') ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label"><?= get_label('description', 'Description') ?></label>
                            <textarea class="form-control description" id="edit_description" name="description" rows="4"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_keywords" class="form-label"><?= get_label('keywords', 'Keywords') ?></label>
                            <input type="text" class="form-control" id="edit_keywords" name="keywords" placeholder="<?= get_label('enter_keywords', 'Enter keywords separated by commas') ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?= get_label('close', 'Close') ?></button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i> <?= get_label('update', 'Update') ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Import Professions Modal -->
    <div class="modal fade" id="import_professions_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bx bx-import me-2"></i>
                        <?= get_label('import_professions', 'Import Professions') ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="bx bx-cloud-upload bx-xxl text-primary"></i>
                        <h4 class="mt-3"><?= get_label('import_from_file', 'Import from File') ?></h4>
                        <p class="text-muted"><?= get_label('upload_professions_excel', 'Upload an Excel or CSV file with your professions data') ?></p>
                    </div>
                    <form id="import_professions_form" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="import_file" class="form-label"><?= get_label('select_file', 'Select File') ?> <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="import_file" name="file" accept=".xlsx,.xls,.csv" required>
                            <div class="invalid-feedback" id="import_file_error"></div>
                            <small class="text-muted"><?= get_label('supported_formats', 'Supported formats: XLSX, XLS, CSV') ?></small>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="first_row_headers" name="first_row_headers" checked>
                                <label class="form-check-label" for="first_row_headers">
                                    <?= get_label('first_row_headers', 'First row contains headers') ?>
                                </label>
                            </div>
                        </div>
                    </form>
                    <div class="alert alert-info">
                        <h6 class="alert-heading"><?= get_label('download_template', 'Download Template') ?></h6>
                        <p class="mb-0"><?= get_label('download_sample_file', 'Download our sample file to see the required format') ?></p>
                        <a href="#" class="btn btn-sm btn-outline-info mt-2">
                            <i class="bx bx-download me-1"></i> <?= get_label('download_sample', 'Download Sample') ?>
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?= get_label('close', 'Close') ?></button>
                    <button type="button" class="btn btn-primary" id="import-submit-btn">
                        <i class="bx bx-upload me-1"></i> <?= get_label('import', 'Import') ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize statistics
            function loadStats() {
                // This would be replaced with actual AJAX call to get real stats
                $('#active-professions').text('{{ $professions->count() }}');
                $('#recent-professions').text('0'); // Would need to calculate from database
                $('#categories-count').text('6');
            }
            
            loadStats();

            // Search functionality
            let searchTimeout;
            $('#professions-search').on('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    // Would implement actual search with AJAX
                    console.log('Searching for:', $('#professions-search').val());
                }, 500);
            });

            // Refresh button
            $('#refresh-professions').on('click', function() {
                location.reload();
            });

            // Add Yemeni Professions button
            $('#add-yemeni-professions-btn').on('click', function() {
                showYemeniProfessionsModal();
            });

            // Create profession form submission
            $('#create_profession_form').on('submit', function(e) {
                e.preventDefault();
                
                $.ajax({
                    url: '{{ route("professions.store") }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (!response.error) {
                            toastr.success(response.message);
                            $('#create_profession_modal').modal('hide');
                            $('#create_profession_form')[0].reset();
                            location.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('#' + key + '_error').text(value[0]);
                                $('#' + key).addClass('is-invalid');
                            });
                        } else {
                            toastr.error('<?= get_label('something_went_wrong', 'Something went wrong') ?>');
                        }
                    }
                });
            });

            // Edit profession modal
            $('.edit-profession').on('click', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let description = $(this).data('description');
                
                $('#edit_profession_id').val(id);
                $('#edit_name').val(name);
                $('#edit_description').val(description);
                
                $('#edit_profession_form').attr('action', '/professions/' + id + '/update');
            });

            // Edit profession form submission
            $('#edit_profession_form').on('submit', function(e) {
                e.preventDefault();
                
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'PUT',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (!response.error) {
                            toastr.success(response.message);
                            $('#edit_profession_modal').modal('hide');
                            location.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('#edit_' + key + '_error').text(value[0]);
                                $('#edit_' + key).addClass('is-invalid');
                            });
                        } else {
                            toastr.error('<?= get_label('something_went_wrong', 'Something went wrong') ?>');
                        }
                    }
                });
            });

            // Import professions
            $('#import-submit-btn').on('click', function() {
                let formData = new FormData($('#import_professions_form')[0]);
                
                $.ajax({
                    url: '{{ url('/professions/import') }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (!response.error) {
                            toastr.success(response.message);
                            $('#import_professions_modal').modal('hide');
                            $('#import_professions_form')[0].reset();
                            location.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('#' + key + '_error').text(value[0]);
                                $('#' + key).addClass('is-invalid');
                            });
                        } else {
                            toastr.error('<?= get_label('something_went_wrong', 'Something went wrong') ?>');
                        }
                    }
                });
            });

            // Clear validation errors when modal is closed
            $('#create_profession_modal, #edit_profession_modal, #import_professions_modal').on('hidden.bs.modal', function() {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
            });
        });

        // Function to show Yemeni professions modal
        function showYemeniProfessionsModal() {
            // Yemeni professions data
            const yemeniProfessions = [
                { name: 'الطب والجراحة', category: 'healthcare', description: 'الأطباء والجراحون والممارسين الصحيين' },
                { name: 'الهندسة المدنية', category: 'construction', description: 'المهندسون المدنيون ومتخصصو البناء' },
                { name: 'التعليم والتدريس', category: 'education', description: 'المعلمون والمدربون والأساتذة الجامعيون' },
                { name: 'القانون والقضاء', category: 'services', description: 'المحامون والقضاة والمستشارون القانونيون' },
                { name: 'المحاسبة والمالية', category: 'services', description: 'المحاسبون والمدققون الماليون' },
                { name: 'الطب البيطري', category: 'healthcare', description: 'أطباء البيوت والحيوانات' },
                { name: 'الصيدلة', category: 'healthcare', description: 'الصيادلة وفنيو الصيدلة' },
                { name: 'التمريض', category: 'healthcare', description: 'الممرضون وفنيو التمريض' },
                { name: 'الهندسة الكهربائية', category: 'technology', description: 'المهندسون الكهربائيون وفنيو الكهرباء' },
                { name: 'الهندسة الميكانيكية', category: 'technology', description: 'المهندسون الميكانيكيون وفنيو الميكانيكا' },
                { name: 'العمارة والتصميم الداخلي', category: 'construction', description: 'المعماريون ومصممو الديكور' },
                { name: 'التسويق والمبيعات', category: 'services', description: 'المسوقون ومسؤولو المبيعات' },
                { name: 'الإدارة والإدارة العامة', category: 'services', description: 'الإداريون ومديرو المشاريع' },
                { name: 'الإعلام والصحافة', category: 'services', description: 'الصحفيون والمذيعون والكتاب' },
                { name: 'الترجمة والتأويل', category: 'services', description: 'المترجمون والمؤدون' },
                { name: 'تقنية المعلومات', category: 'technology', description: 'مبرمجو الحاسوب ومطورو البرمجيات' },
                { name: 'التصميم الجرافيكي', category: 'technology', description: 'المصممون الجرافيكيون وفنيو التصميم' },
                { name: 'الطب النفسي', category: 'healthcare', description: 'الأطباء النفسيون والمحللون النفسيون' },
                { name: 'الطب الباطني', category: 'healthcare', description: 'أطباء الباطنة والأمراض المزمنة' },
                { name: 'جراحة القلب', category: 'healthcare', description: 'جراحو القلب والقسطرة' },
                { name: 'الطب النسائي', category: 'healthcare', description: 'النسائيون والولادة' },
                { name: 'طب الأطفال', category: 'healthcare', description: 'أطباء الأطفال والطوارئ' },
                { name: 'الطب العائلي', category: 'healthcare', description: 'الأطباء العائليون والرعاية الأولية' },
                { name: 'الأشعة والتصوير الطبي', category: 'healthcare', description: 'فنيو الأشعة والأجهزة الطبية' },
                { name: 'العلاج الطبيعي', category: 'healthcare', description: 'أخصائيو العلاج الطبيعي والتأهيل' },
                { name: 'الطب الشرعي', category: 'healthcare', description: 'الأطباء الشرعيون وخبراء الطب الشرعي' },
                { name: 'الطب الطوارئ', category: 'healthcare', description: 'أطباء الطوارئ والإنعاش' },
                { name: 'الطب المهني', category: 'healthcare', description: 'أطباء المهن الصحية والبيئة' },
                { name: 'الطب التجميلي', category: 'healthcare', description: 'الجراحون التجميليون والترميميون' },
                { name: 'الطب النووي', category: 'healthcare', description: 'أطباء الطب النووي والإشعاعي' },
                { name: 'الهندسة الكيميائية', category: 'technology', description: 'المهندسون الكيميائيون وفنيو العمليات' },
                { name: 'الهندسة النفطية', category: 'technology', description: 'مهندسو النفط والغاز' },
                { name: 'الهندسة الزراعية', category: 'technology', description: 'المهندسون الزراعيون وفنيو المياه' },
                { name: 'الهندسة البحرية', category: 'technology', description: 'المهندسون البحريون وفنيو السفن' },
                { name: 'الهندسة النووية', category: 'technology', description: 'المهندسون النوويون وفنيو الطاقة' },
                { name: 'الهندسة الصناعية', category: 'technology', description: 'المهندسون الصناعيون وتحسين العمليات' },
                { name: 'الهندسة البيئية', category: 'technology', description: 'المهندسون البيئيون وحماية البيئة' },
                { name: 'الهندسة التشييدية', category: 'construction', description: 'مهندسو التشييد والمراقبة' },
                { name: 'الهندسة المساحية', category: 'technology', description: 'المساحون والمصورون الجويون' },
                { name: 'الهندسة الجيولوجية', category: 'technology', description: 'الجيولوجيون وفنيو التنقيب' },
                { name: 'الهندسة المناجم', category: 'technology', description: 'مهندسو المناجم وفنيو الاستخراج' },
                { name: 'الهندسة الجيوتقنية', category: 'technology', description: 'المهندسون الجيوتقنيون وتحليل التربة' },
                { name: 'الهندسة الزلزالية', category: 'technology', description: 'المهندسون الزلزاليون ومقاومة الزلازل' },
                { name: 'الهندسة الحرارية', category: 'technology', description: 'المهندسون الحراريون ونقل الحرارة' },
                { name: 'الهندسة المائية', category: 'technology', description: 'المهندسون المائيون ومحطات التحلية' },
                { name: 'الهندسة النووية', category: 'technology', description: 'المهندسون النوويون ومحطات الطاقة' },
                { name: 'الهندسة الصوتية', category: 'technology', description: 'المهندسون الصوتيون وتحكم الضوضاء' },
                { name: 'الهندسة البصرية', category: 'technology', description: 'المهندسون البصريون وأنظمة الإضاءة' },
                { name: 'الهندسة الإحصائية', category: 'technology', description: 'المهندسون الإحصائيون وتحليل البيانات' },
                { name: 'الهندسة الإدارية', category: 'technology', description: 'الإداريون الهندسيون وتطوير الأنظمة' }
            ];

            // Create modal content
            let modalContent = `
                <div class="modal fade" id="yemeni_professions_modal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="bx bx-flag me-2"></i>
                                    <?= get_label('yemeni_professions', 'Yemeni Professions') ?>
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading"><?= get_label('yemeni_professions_info', '50 Yemeni Professions') ?></h6>
                                    <p class="mb-0"><?= get_label('select_professions_to_add', 'Select the professions you want to add to your system') ?></p>
                                </div>
                                <div class="row" id="yemeni-professions-grid">
                                    ${yemeniProfessions.map((prof, index) => `
                                        <div class="col-md-4 mb-3">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="prof_${index}" value="${index}" data-profession='${JSON.stringify(prof)}'>
                                                        <label class="form-check-label" for="prof_${index}">
                                                            <h6 class="mb-1">${prof.name}</h6>
                                                            <small class="text-muted">${prof.description}</small>
                                                            <div class="mt-2">
                                                                <span class="badge bg-label-${getCategoryColor(prof.category)}">${getCategoryLabel(prof.category)}</span>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="d-flex justify-content-between w-100">
                                    <div>
                                        <button type="button" class="btn btn-outline-secondary" id="select-all-professions">
                                            <?= get_label('select_all', 'Select All') ?>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" id="deselect-all-professions">
                                            <?= get_label('deselect_all', 'Deselect All') ?>
                                        </button>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?= get_label('close', 'Close') ?></button>
                                        <button type="button" class="btn btn-primary" id="add-selected-professions">
                                            <i class="bx bx-plus me-1"></i> <?= get_label('add_selected', 'Add Selected') ?> (<span id="selected-count">0</span>)
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Add modal to body and show it
            $('body').append(modalContent);
            $('#yemeni_professions_modal').modal('show');

            // Update selected count
            function updateSelectedCount() {
                const count = $('#yemeni-professions-grid input[type="checkbox"]:checked').length;
                $('#selected-count').text(count);
            }

            // Select all
            $('#select-all-professions').on('click', function() {
                $('#yemeni-professions-grid input[type="checkbox"]').prop('checked', true);
                updateSelectedCount();
            });

            // Deselect all
            $('#deselect-all-professions').on('click', function() {
                $('#yemeni-professions-grid input[type="checkbox"]').prop('checked', false);
                updateSelectedCount();
            });

            // Update count on checkbox change
            $('#yemeni-professions-grid').on('change', 'input[type="checkbox"]', function() {
                updateSelectedCount();
            });

            // Add selected professions
            $('#add-selected-professions').on('click', function() {
                const selectedCheckboxes = $('#yemeni-professions-grid input[type="checkbox"]:checked');
                const selectedProfessions = [];
                
                selectedCheckboxes.each(function() {
                    const professionData = $(this).data('profession');
                    selectedProfessions.push(professionData);
                });

                if (selectedProfessions.length === 0) {
                    toastr.warning('<?= get_label('please_select_at_least_one', 'Please select at least one profession') ?>');
                    return;
                }

                // Add professions via AJAX
                $.ajax({
                    url: '{{ url('/professions/bulk-add') }}',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        professions: selectedProfessions
                    },
                    success: function(response) {
                        if (!response.error) {
                            toastr.success(response.message);
                            $('#yemeni_professions_modal').modal('hide');
                            location.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('<?= get_label('something_went_wrong', 'Something went wrong') ?>');
                    }
                });
            });

            // Remove modal when closed
            $('#yemeni_professions_modal').on('hidden.bs.modal', function() {
                $(this).remove();
            });
        }

        // Helper functions for category display
        function getCategoryColor(category) {
            const colors = {
                'construction': 'primary',
                'services': 'success', 
                'technology': 'info',
                'healthcare': 'danger',
                'education': 'warning',
                'other': 'secondary'
            };
            return colors[category] || 'secondary';
        }

        function getCategoryLabel(category) {
            const labels = {
                'construction': '<?= get_label('construction', 'Construction') ?>',
                'services': '<?= get_label('services', 'Services') ?>',
                'technology': '<?= get_label('technology', 'Technology') ?>',
                'healthcare': '<?= get_label('healthcare', 'Healthcare') ?>',
                'education': '<?= get_label('education', 'Education') ?>',
                'other': '<?= get_label('other', 'Other') ?>'
            };
            return labels[category] || category;
        }
    </script>
    @endpush
@endsection