# GPDF Integration Documentation

## Overview
This project now includes comprehensive PDF generation capabilities using the **Gpdf** library with full Arabic language support. The integration provides professional PDF generation, export, and printing functionality across all major modules.

## Features Implemented

### 1. Core PDF Service
- **File**: `app/Services/PdfService.php`
- **Features**:
  - Unified PDF generation service
  - Arabic font support with Tajawal font
  - RTL (Right-to-Left) text support
  - Professional templates for all modules
  - Stream and download capabilities
  - Bulk PDF generation

### 2. Arabic-Optimized Templates
All PDF templates are designed with Arabic language support:

#### Contracts (`resources/views/pdf/contracts/template.blade.php`)
- Professional contract formatting
- Arabic-English bilingual support
- Party details with proper formatting
- Items table with currency formatting
- Signatures section
- Watermark and page numbering

#### Estimates/Invoices (`resources/views/pdf/estimates/template.blade.php`)
- Detailed financial breakdown
- Progress tracking visualization
- Multi-currency support
- Professional styling with charts

#### Projects (`resources/views/pdf/projects/template.blade.php`)
- Project timeline visualization
- Team member cards
- Statistics dashboard
- Progress indicators

#### Tasks (`resources/views/pdf/tasks/template.blade.php`)
- Task details with status badges
- Timeline view
- Assigned team members
- Comments section

#### Clients (`resources/views/pdf/clients/template.blade.php`)
- Client profile information
- Business details
- Project and contract history
- Statistics overview

#### Users (`resources/views/pdf/users/template.blade.php`)
- User profile details
- Role and permission information
- Assigned projects and tasks
- Activity timeline

#### Custom Reports (`resources/views/pdf/reports/custom.blade.php`)
- Flexible report generation
- Data tables with styling
- Chart placeholders
- Customizable sections

### 3. Controller Integration

#### Controllers Updated:
- `ContractsController.php` - Contract PDF generation
- `EstimatesInvoicesController.php` - Estimate/Invoice PDF generation
- `ProjectsController.php` - Project PDF generation
- `TasksController.php` - Task PDF generation
- `ClientController.php` - Client PDF generation
- `UserController.php` - User PDF generation

#### Methods Added to Each Controller:
```php
public function generatePdf($id)           // View PDF in browser
public function downloadPdf($id)          // Download PDF file
public function generateBulkPdf(Request $request)  // Generate multiple PDFs
```

### 4. Routes Added

#### Web Routes:
```php
// Contracts
Route::get('/contracts/{id}/pdf', [ContractsController::class, 'generatePdf'])->name('contracts.pdf');
Route::get('/contracts/{id}/download-pdf', [ContractsController::class, 'downloadPdf'])->name('contracts.download-pdf');
Route::post('/contracts/bulk-pdf', [ContractsController::class, 'generateBulkPdf'])->name('contracts.bulk-pdf');

// Estimates/Invoices
Route::get('/estimates-invoices/{id}/pdf', [EstimatesInvoicesController::class, 'generatePdf'])->name('estimates-invoices.pdf');
Route::get('/estimates-invoices/{id}/download-pdf', [EstimatesInvoicesController::class, 'downloadPdf'])->name('estimates-invoices.download-pdf');
Route::post('/estimates-invoices/bulk-pdf', [EstimatesInvoicesController::class, 'generateBulkPdf'])->name('estimates-invoices.bulk-pdf');

// Projects
Route::get('/projects/{id}/pdf', [ProjectsController::class, 'generatePdf'])->name('projects.pdf');
Route::get('/projects/{id}/download-pdf', [ProjectsController::class, 'downloadPdf'])->name('projects.download-pdf');
Route::post('/projects/bulk-pdf', [ProjectsController::class, 'generateBulkPdf'])->name('projects.bulk-pdf');

// Tasks
Route::get('/tasks/{id}/pdf', [TasksController::class, 'generatePdf'])->name('tasks.pdf');
Route::get('/tasks/{id}/download-pdf', [TasksController::class, 'downloadPdf'])->name('tasks.download-pdf');
Route::post('/tasks/bulk-pdf', [TasksController::class, 'generateBulkPdf'])->name('tasks.bulk-pdf');

// Clients
Route::get('/clients/{id}/pdf', [ClientController::class, 'generatePdf'])->name('clients.pdf');
Route::get('/clients/{id}/download-pdf', [ClientController::class, 'downloadPdf'])->name('clients.download-pdf');
Route::post('/clients/bulk-pdf', [ClientController::class, 'generateBulkPdf'])->name('clients.bulk-pdf');

// Users
Route::get('/users/{id}/pdf', [UserController::class, 'generatePdf'])->name('users.pdf');
Route::get('/users/{id}/download-pdf', [UserController::class, 'downloadPdf'])->name('users.download-pdf');
Route::post('/users/bulk-pdf', [UserController::class, 'generateBulkPdf'])->name('users.bulk-pdf');
```

## Usage Examples

### 1. Generate Single PDF
```php
// In controller
public function generatePdf($id)
{
    $contract = Contract::findOrFail($id);
    $pdfService = app('App\Services\PdfService');
    return $pdfService->generateContractPdf($contract);
}

// Via route
GET /contracts/1/pdf
```

### 2. Download PDF
```php
// In controller
public function downloadPdf($id)
{
    $contract = Contract::findOrFail($id);
    $pdfService = app('App\Services\PdfService');
    return $pdfService->streamPdf('pdf.contracts.template', [
        'contract' => $contract,
        'items' => $contract->items ?? [],
    ], 'contract-1.pdf', false); // false = download attachment
}

// Via route
GET /contracts/1/download-pdf
```

### 3. Bulk PDF Generation
```php
// In controller
public function generateBulkPdf(Request $request)
{
    $validatedData = $request->validate([
        'ids' => 'required|array',
        'ids.*' => 'integer|exists:contracts,id'
    ]);
    
    $contracts = Contract::whereIn('id', $validatedData['ids'])->get();
    $pdfService = app('App\Services\PdfService');
    
    return $pdfService->generateReportPdf('Contracts Report', [
        'contracts' => $contracts,
        'total_count' => $contracts->count(),
        'total_value' => $contracts->sum('value')
    ], 'pdf.reports.custom');
}

// Via route
POST /contracts/bulk-pdf
{
    "ids": [1, 2, 3, 4, 5]
}
```

## Configuration

### Gpdf Configuration (`config/gpdf.php`)
```php
return [
    GpdfSet::DEFAULT_FONT => GpdfDefaultSupportedFonts::TAJAWAL,
    GpdfSet::SHOW_NUMBERS_AS_HINDI => false,
    GpdfSet::MAX_CHARS_PER_LINE => 100,
    // ... other settings
];
```

### Font Support
- **Tajawal** (Primary Arabic font)
- **Almarai** (Arabic font)
- **Cairo** (Arabic font)
- **Noto Naskh Arabic** (Arabic font)
- **Markazi Text** (Arabic font)
- **DejaVu Sans Mono** (Fallback font)

## Arabic Language Features

### 1. RTL Support
- Automatic RTL detection based on locale
- Proper text alignment for Arabic content
- Correct page layout for right-to-left reading

### 2. Arabic Typography
- Professional Arabic font rendering
- Proper character spacing and kerning
- Support for Arabic ligatures

### 3. Bilingual Support
- Arabic-English mixed content
- Proper font switching between languages
- Cultural formatting for dates and numbers

## Testing

### Test Single PDF Generation
```bash
# Test contract PDF
curl -X GET "http://localhost/contracts/1/pdf" -H "Accept: application/pdf"

# Test project PDF download
curl -X GET "http://localhost/projects/1/download-pdf" -H "Accept: application/pdf" -o project.pdf
```

### Test Bulk PDF Generation
```bash
curl -X POST "http://localhost/contracts/bulk-pdf" \
  -H "Content-Type: application/json" \
  -H "Accept: application/pdf" \
  -d '{"ids": [1, 2, 3]}' \
  -o contracts-report.pdf
```

## Security Considerations

### 1. Access Control
- All PDF routes include proper permission middleware
- User access validation for each resource
- Workspace-based data isolation

### 2. Input Validation
- Strict validation for bulk operations
- XSS protection in PDF content
- File path sanitization

## Performance Optimization

### 1. Caching
- Font caching for improved performance
- Template compilation caching
- Memory optimization for large documents

### 2. Streaming
- Direct streaming to browser
- Memory-efficient PDF generation
- Chunked processing for large datasets

## Troubleshooting

### Common Issues:

1. **Font Not Loading**
   ```bash
   # Re-publish fonts
   php vendor/omaralalwi/gpdf/scripts/publish_fonts.php
   ```

2. **Arabic Text Not Displaying**
   ```php
   // Ensure proper encoding
   <meta charset="utf-8">
   <html dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
   ```

3. **PDF Generation Errors**
   ```bash
   # Check Gpdf installation
   composer require omaralalwi/gpdf
   ```

## Future Enhancements

### Planned Features:
- [ ] PDF watermarking
- [ ] Digital signatures
- [ ] PDF encryption
- [ ] Template customization UI
- [ ] Email PDF attachments
- [ ] PDF merging capabilities
- [ ] Advanced reporting charts

## Support

For issues with the Gpdf integration, please check:
- [Gpdf GitHub Repository](https://github.com/omaralalwi/Gpdf)
- Laravel error logs
- Browser developer console for PDF rendering issues

---
*This documentation covers the complete Gpdf integration for professional PDF generation with full Arabic language support.*