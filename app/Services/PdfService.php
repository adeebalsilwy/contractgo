<?php

namespace App\Services;

use Omaralalwi\Gpdf\Gpdf;
use Omaralalwi\Gpdf\GpdfConfig;
use Omaralalwi\Gpdf\Enums\GpdfDefaultSupportedFonts;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class PdfService
{
    protected $gpdf;
    protected $config;

    public function __construct()
    {
        // Load the Gpdf configuration
        $gpdfConfigFile = config('gpdf');
        $this->config = new GpdfConfig($gpdfConfigFile);
        $this->gpdf = new Gpdf($this->config);
    }

    /**
     * Generate PDF from view with Arabic support
     *
     * @param string $view
     * @param array $data
     * @param string $filename
     * @param string $orientation
     * @param string $paperSize
     * @return string PDF content
     */
    public function generatePdf($view, $data = [], $filename = 'document', $orientation = 'portrait', $paperSize = 'A4')
    {
        // Add common data for all PDFs
        $commonData = $this->getCommonPdfData();
        $data = array_merge($commonData, $data);

        // Render the view
        $html = View::make($view, $data)->render();

        // Generate PDF with proper Arabic support
        return $this->gpdf->generate($html);
    }

    /**
     * Generate and stream PDF directly to browser
     *
     * @param string $view
     * @param array $data
     * @param string $filename
     * @param bool $inline
     * @return Response
     */
    public function streamPdf($view, $data = [], $filename = 'document.pdf', $inline = true)
    {
        $pdfContent = $this->generatePdf($view, $data);
        
        // For mobile devices, always use attachment to ensure proper download
        $isMobile = $this->isMobileDevice();
        $disposition = ($inline && !$isMobile) ? 'inline' : 'attachment';
        
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "{$disposition}; filename=\"{$filename}\"",
            'Content-Length' => strlen($pdfContent),
            'Cache-Control' => 'private, must-revalidate, post-check=0, pre-check=0',
            'Pragma' => 'public',
            'Expires' => '0',
            'Accept-Ranges' => 'bytes',
        ];
        
        // Add mobile-specific headers
        if ($isMobile) {
            $headers['Content-Transfer-Encoding'] = 'binary';
            $headers['Content-Encoding'] = 'none';
            
            // Additional headers for better mobile compatibility
            $headers['X-Content-Type-Options'] = 'nosniff';
            $headers['X-Frame-Options'] = 'DENY';
            $headers['X-XSS-Protection'] = '1; mode=block';
            
            // For iOS devices, add specific headers
            if (stripos(request()->userAgent() ?? '', 'iPhone') !== false || 
                stripos(request()->userAgent() ?? '', 'iPad') !== false) {
                $headers['Content-Disposition'] = 'attachment; filename="' . $filename . '"';
            }
        }
        
        return response($pdfContent, 200, $headers);
    }

    /**
     * Generate and save PDF to storage
     *
     * @param string $view
     * @param array $data
     * @param string $path
     * @param string $filename
     * @param bool $withStream
     * @return array
     */
    public function savePdf($view, $data = [], $path = null, $filename = 'document.pdf', $withStream = false)
    {
        $pdfContent = $this->generatePdf($view, $data);
        
        if (!$path) {
            $path = 'pdfs/' . date('Y/m');
        }
        
        $fullPath = $path . '/' . $filename;
        Storage::put($fullPath, $pdfContent);
        
        $fileUrl = Storage::url($fullPath);
        
        if ($withStream) {
            return [
                'content' => $pdfContent,
                'url' => $fileUrl,
                'path' => $fullPath
            ];
        }
        
        return [
            'url' => $fileUrl,
            'path' => $fullPath
        ];
    }

    /**
     * Generate PDF with custom configuration
     *
     * @param string $view
     * @param array $data
     * @param array $customConfig
     * @return string
     */
    public function generatePdfWithConfig($view, $data = [], $customConfig = [])
    {
        // Create temporary config with custom settings
        $gpdfConfigFile = config('gpdf');
        $gpdfConfigFile = array_merge($gpdfConfigFile, $customConfig);
        
        $tempConfig = new GpdfConfig($gpdfConfigFile);
        $tempGpdf = new Gpdf($tempConfig);
        
        $commonData = $this->getCommonPdfData();
        $data = array_merge($commonData, $data);
        
        $html = View::make($view, $data)->render();
        
        return $tempGpdf->generate($html);
    }

    /**
     * Check if the request is from a mobile device
     *
     * @return bool
     */
    protected function isMobileDevice()
    {
        $userAgent = request()->userAgent() ?? '';
        
        $mobileKeywords = [
            'Mobile', 'Android', 'iPhone', 'iPad', 'iPod', 'BlackBerry', 
            'Windows Phone', 'Opera Mini', 'IEMobile', 'Mobile Safari'
        ];
        
        foreach ($mobileKeywords as $keyword) {
            if (stripos($userAgent, $keyword) !== false) {
                return true;
            }
        }
        
        // Check for mobile-specific headers
        $mobileHeaders = [
            'HTTP_X_WAP_PROFILE',
            'HTTP_PROFILE',
            'HTTP_X_OPERAMINI_FEATURES',
            'HTTP_X_DEVICE_USER_AGENT'
        ];
        
        foreach ($mobileHeaders as $header) {
            if (request()->header(str_replace('HTTP_', '', $header))) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get common data for all PDFs
     *
     * @return array
     */
    protected function getCommonPdfData()
    {
        $workspaceId = getWorkspaceId();
        $workspace = $workspaceId ? \App\Models\Workspace::find($workspaceId) : null;
        $user = getAuthenticatedUser();
        $generalSettings = get_settings('general_settings');
        
        return [
            'workspace' => $workspace,
            'workspace_id' => $workspaceId,
            'user' => $user,
            'general_settings' => $generalSettings,
            'companyInfo' => $this->getCompanyInfo(),
            'currentDate' => now()->format('Y-m-d'),
            'currentDateTime' => now()->format('Y-m-d H:i:s'),
            'locale' => app()->getLocale(),
            'isRtl' => app()->getLocale() == 'ar'
        ];
    }

    /**
     * Get company information for PDF headers
     *
     * @return array
     */
    protected function getCompanyInfo()
    {
        $generalSettings = get_settings('general_settings');
        
        return [
            'name' => $generalSettings['company_title'] ?? 'Company Name',
            'name_ar' => $generalSettings['company_title_ar'] ?? 'اسم الشركة',
            'name_en' => $generalSettings['company_title_en'] ?? 'Company Name',
            'address' => $generalSettings['company_address'] ?? 'Company Address',
            'phone' => $generalSettings['company_phone'] ?? '+1234567890',
            'email' => $generalSettings['company_email'] ?? 'info@company.com',
            'website' => $generalSettings['company_website'] ?? 'www.company.com',
            'vat_number' => $generalSettings['company_vat_number'] ?? 'VAT123456789',
            'logo' => $generalSettings['company_logo'] ?? null,
            'full_logo' => $generalSettings['full_logo'] ?? null
        ];
    }

    /**
     * Generate contract PDF
     *
     * @param mixed $contract
     * @param array $additionalData
     * @return Response
     */
    public function generateContractPdf($contract, $additionalData = [])
    {
        $data = array_merge([
            'contract' => $contract,
            'items' => $contract->items ?? [],
        ], $additionalData);

        // For mobile devices, force download to ensure proper handling
        $isMobile = $this->isMobileDevice();
        $filename = "contract-{$contract->id}-" . str_slug($contract->title ?? 'contract') . ".pdf";
        
        return $this->streamPdf('pdf.contracts.template', $data, $filename, !$isMobile);
    }

    /**
     * Generate estimate/invoice PDF
     *
     * @param mixed $estimate
     * @param array $additionalData
     * @return Response
     */
    public function generateEstimatePdf($estimate, $additionalData = [])
    {
        $data = array_merge([
            'estimate' => $estimate,
            'items' => $estimate->items ?? [],
        ], $additionalData);

        return $this->streamPdf('pdf.estimates.template', $data, "estimate-{$estimate->id}.pdf");
    }

    /**
     * Generate project PDF report
     *
     * @param mixed $project
     * @param array $additionalData
     * @return Response
     */
    public function generateProjectPdf($project, $additionalData = [])
    {
        $data = array_merge([
            'project' => $project,
            'tasks' => $project->tasks ?? [],
            'users' => $project->users ?? [],
            'clients' => $project->clients ?? [],
        ], $additionalData);

        return $this->streamPdf('pdf.projects.template', $data, "project-{$project->id}.pdf");
    }

    /**
     * Generate task PDF report
     *
     * @param mixed $task
     * @param array $additionalData
     * @return Response
     */
    public function generateTaskPdf($task, $additionalData = [])
    {
        $data = array_merge([
            'task' => $task,
            'project' => $task->project ?? null,
            'users' => $task->users ?? [],
            'comments' => $task->comments ?? [],
        ], $additionalData);

        return $this->streamPdf('pdf.tasks.template', $data, "task-{$task->id}.pdf");
    }

    /**
     * Generate client PDF report
     *
     * @param mixed $client
     * @param array $additionalData
     * @return Response
     */
    public function generateClientPdf($client, $additionalData = [])
    {
        $data = array_merge([
            'client' => $client,
            'projects' => $client->projects ?? [],
            'contracts' => $client->contracts ?? [],
        ], $additionalData);

        return $this->streamPdf('pdf.clients.template', $data, "client-{$client->id}.pdf");
    }

    /**
     * Generate user PDF report
     *
     * @param mixed $user
     * @param array $additionalData
     * @return Response
     */
    public function generateUserPdf($user, $additionalData = [])
    {
        $data = array_merge([
            'user' => $user,
            'projects' => $user->projects ?? [],
            'tasks' => $user->tasks ?? [],
            'roles' => $user->roles ?? [],
        ], $additionalData);

        return $this->streamPdf('pdf.users.template', $data, "user-{$user->id}.pdf");
    }

    /**
     * Generate custom report PDF
     *
     * @param string $title
     * @param array $data
     * @param string $template
     * @return Response
     */
    public function generateReportPdf($title, $data, $template = 'pdf.reports.custom')
    {
        $pdfData = array_merge([
            'title' => $title,
            'report_data' => $data,
            'generated_at' => now(),
        ], $data);

        return $this->streamPdf($template, $pdfData, "report-" . str_slug($title) . ".pdf");
    }
}