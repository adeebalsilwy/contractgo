<div class="notes-container">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">
                    <i class="bx bx-notepad text-secondary me-2"></i>
                    <?= get_label('notes', 'Notes') ?>
                </h5>
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addNoteModal">
                    <i class="bx bx-plus me-1"></i><?= get_label('add_note', 'Add Note') ?>
                </button>
            </div>
            
            <div class="notes-list">
                <!-- System Notes -->
                @if($estimate_invoice->note)
                <div class="note-item mb-3 p-3 rounded border border-info">
                    <div class="d-flex align-items-start">
                        <div class="avatar bg-label-info me-3 mt-1">
                            <i class="bx bx-message-square-detail"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0"><?= get_label('system_notes', 'System Notes') ?></h6>
                                <small class="text-muted">{{ format_date($estimate_invoice->updated_at, true) }}</small>
                            </div>
                            <p class="mb-2">{{ $estimate_invoice->note }}</p>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-xs me-2">
                                    <img src="{{ $estimate_invoice->creator->photo ? asset('storage/' . $estimate_invoice->creator->photo) : asset('storage/photos/no-image.jpg') }}" 
                                         class="rounded-circle" 
                                         alt="{{ $estimate_invoice->creator->first_name }} {{ $estimate_invoice->creator->last_name }}"
                                         width="20" height="20">
                                </div>
                                <small class="text-muted">
                                    {{ $estimate_invoice->creator->first_name }} {{ $estimate_invoice->creator->last_name }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Personal Notes -->
                @if($estimate_invoice->personal_note)
                <div class="note-item mb-3 p-3 rounded border border-warning">
                    <div class="d-flex align-items-start">
                        <div class="avatar bg-label-warning me-3 mt-1">
                            <i class="bx bx-lock"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0"><?= get_label('personal_notes', 'Personal Notes') ?></h6>
                                <small class="text-muted"><?= get_label('private', 'Private') ?></small>
                            </div>
                            <p class="mb-2">{{ $estimate_invoice->personal_note }}</p>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-xs me-2">
                                    <img src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : asset('storage/photos/no-image.jpg') }}" 
                                         class="rounded-circle" 
                                         alt="{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}"
                                         width="20" height="20">
                                </div>
                                <small class="text-muted">
                                    <?= get_label('visible_to_you_only', 'Visible to you only') ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Status Notes -->
                @if($estimate_invoice->status !== 'draft')
                <div class="note-item mb-3 p-3 rounded border border-success">
                    <div class="d-flex align-items-start">
                        <div class="avatar bg-label-success me-3 mt-1">
                            <i class="bx bx-check-circle"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0"><?= get_label('status_notes', 'Status Notes') ?></h6>
                                <small class="text-muted">{{ format_date($estimate_invoice->updated_at, true) }}</small>
                            </div>
                            <p class="mb-2">
                                <?= get_label('status_update_description', 'Status updated to') ?> <strong>{{ getExtractStatusText($estimate_invoice->status) }}</strong>
                            </p>
                            <div class="status-badge">
                                <span class="badge bg-{{ getExtractStatusColor($estimate_invoice->status) }}">
                                    {{ getExtractStatusText($estimate_invoice->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- General Notes -->
                <div class="note-item p-3 rounded border border-light">
                    <div class="d-flex align-items-start">
                        <div class="avatar bg-label-secondary me-3 mt-1">
                            <i class="bx bx-note"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0"><?= get_label('general_notes', 'General Notes') ?></h6>
                                <small class="text-muted"><?= get_label('no_notes', 'No notes added') ?></small>
                            </div>
                            <p class="mb-0 text-muted">
                                <?= get_label('no_general_notes_description', 'No general notes have been added to this extract yet') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Notes Summary -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <h5 class="mb-4">
                <i class="bx bx-stats text-primary me-2"></i>
                <?= get_label('notes_summary', 'Notes Summary') ?>
            </h5>
            
            <div class="notes-summary">
                <!-- Notes Statistics -->
                <div class="mb-4 p-3 bg-light rounded">
                    <h6 class="mb-3"><?= get_label('notes_statistics', 'Notes Statistics') ?></h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span><?= get_label('total_notes', 'Total Notes') ?>:</span>
                        <span class="fw-bold">
                            @php
                            $totalNotes = 0;
                            if($estimate_invoice->note) $totalNotes++;
                            if($estimate_invoice->personal_note) $totalNotes++;
                            if($estimate_invoice->status !== 'draft') $totalNotes++;
                            @endphp
                            {{ $totalNotes }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-info"><?= get_label('system_notes', 'System Notes') ?>:</span>
                        <span class="fw-bold text-info">{{ $estimate_invoice->note ? 1 : 0 }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-warning"><?= get_label('personal_notes', 'Personal Notes') ?>:</span>
                        <span class="fw-bold text-warning">{{ $estimate_invoice->personal_note ? 1 : 0 }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-success"><?= get_label('status_notes', 'Status Notes') ?>:</span>
                        <span class="fw-bold text-success">{{ $estimate_invoice->status !== 'draft' ? 1 : 0 }}</span>
                    </div>
                </div>
                
                <!-- Note Categories -->
                <div class="mb-4">
                    <h6 class="mb-3"><?= get_label('note_categories', 'Note Categories') ?></h6>
                    <div class="categories-list">
                        <div class="category-item mb-2 p-2 rounded border border-info">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-message-square-detail text-info me-2"></i>
                                <div>
                                    <div class="fw-medium small"><?= get_label('system_notes', 'System Notes') ?></div>
                                    <small class="text-muted"><?= get_label('system_generated', 'System generated notes') ?></small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="category-item mb-2 p-2 rounded border border-warning">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-lock text-warning me-2"></i>
                                <div>
                                    <div class="fw-medium small"><?= get_label('personal_notes', 'Personal Notes') ?></div>
                                    <small class="text-muted"><?= get_label('private_notes', 'Private notes for personal reference') ?></small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="category-item p-2 rounded border border-success">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-check-circle text-success me-2"></i>
                                <div>
                                    <div class="fw-medium small"><?= get_label('status_notes', 'Status Notes') ?></div>
                                    <small class="text-muted"><?= get_label('status_updates', 'Automatic status updates') ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Notes Management -->
                <div class="p-3 bg-success bg-opacity-10 rounded">
                    <h6 class="mb-2">
                        <i class="bx bx-cog text-success me-1"></i>
                        <?= get_label('notes_management', 'Notes Management') ?>
                    </h6>
                    <p class="mb-2 small text-success">
                        <i class="bx bx-check-circle me-1"></i>
                        <?= get_label('notes_secured', 'All notes are securely stored and organized by category') ?>
                    </p>
                    <p class="mb-0 small text-success">
                        <i class="bx bx-check-circle me-1"></i>
                        <?= get_label('audit_trail_maintained', 'Complete audit trail maintained for all note changes') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Note Modal -->
<div class="modal fade" id="addNoteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= get_label('add_note', 'Add Note') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addNoteForm">
                    @csrf
                    <input type="hidden" name="estimate_invoice_id" value="{{ $estimate_invoice->id }}">
                    
                    <div class="mb-3">
                        <label class="form-label"><?= get_label('note_type', 'Note Type') ?></label>
                        <select class="form-select" name="note_type" required>
                            <option value=""><?= get_label('select_note_type', 'Select note type') ?></option>
                            <option value="general"><?= get_label('general_note', 'General Note') ?></option>
                            <option value="technical"><?= get_label('technical_note', 'Technical Note') ?></option>
                            <option value="financial"><?= get_label('financial_note', 'Financial Note') ?></option>
                            <option value="personal"><?= get_label('personal_note', 'Personal Note') ?></option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= get_label('note_title', 'Note Title') ?></label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= get_label('note_content', 'Note Content') ?></label>
                        <textarea class="form-control" name="content" rows="4" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= get_label('priority', 'Priority') ?></label>
                        <select class="form-select" name="priority">
                            <option value="low"><?= get_label('low', 'Low') ?></option>
                            <option value="medium" selected><?= get_label('medium', 'Medium') ?></option>
                            <option value="high"><?= get_label('high', 'High') ?></option>
                            <option value="urgent"><?= get_label('urgent', 'Urgent') ?></option>
                        </select>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="is_private" id="isPrivate">
                        <label class="form-check-label" for="isPrivate">
                            <?= get_label('private_note', 'Private Note (Visible only to you)') ?>
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= get_label('cancel', 'Cancel') ?></button>
                <button type="button" class="btn btn-primary" onclick="saveNote()"><?= get_label('save_note', 'Save Note') ?></button>
            </div>
        </div>
    </div>
</div>