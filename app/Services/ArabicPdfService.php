<?php

namespace App\Services;

use Omaralalwi\Gpdf\Gpdf;
use Omaralalwi\Gpdf\GpdfConfig;
use Omaralalwi\Gpdf\Enums\GpdfDefaultSupportedFonts;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class ArabicPdfService
{
    protected $gpdf;
    protected $config;

    public function __construct()
    {
        // Load the Gpdf configuration with Arabic-specific settings
        $gpdfConfigFile = config('gpdf');
        
        // Ensure Arabic font settings are properly configured
        $gpdfConfigFile['default_font'] = GpdfDefaultSupportedFonts::TAJAWAL;
        $gpdfConfigFile['show_numbers_as_hindi'] = false; // Keep standard numbers for better compatibility
        
        $this->config = new GpdfConfig($gpdfConfigFile);
        $this->gpdf = new Gpdf($this->config);
    }

    /**
     * Generate PDF with full Arabic support using GPDF
     *
     * @param string $view
     * @param array $data
     * @param string $filename
     * @param bool $inline
     * @return Response
     */
    public function generateArabicPdf($view, $data = [], $filename = 'document.pdf', $inline = true)
    {
        // Add Arabic-specific data
        $arabicData = $this->getArabicPdfData();
        $data = array_merge($arabicData, $data);

        // Render the view with Arabic support
        $html = View::make($view, $data)->render();

        // Generate PDF with GPDF (handles Arabic perfectly)
        $pdfContent = $this->gpdf->generate($html);
        
        // Handle mobile devices
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
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
            'X-XSS-Protection' => '1; mode=block'
        ];
        
        // Mobile-specific adjustments
        if ($isMobile) {
            $headers['Content-Transfer-Encoding'] = 'binary';
            $headers['Content-Encoding'] = 'none';
            
            // Force attachment for iOS devices
            if ($this->isIOSDevice()) {
                $headers['Content-Disposition'] = "attachment; filename=\"{$filename}\"";
            }
        }
        
        return response($pdfContent, 200, $headers);
    }

    /**
     * Generate estimate/invoice PDF with full Arabic support
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
            'isArabic' => true,
            'useArabicFonts' => true
        ], $additionalData);

        $filename = "estimate-{$estimate->id}.pdf";
        
        return $this->generateArabicPdf('pdf.estimates.template', $data, $filename);
    }

    /**
     * Generate contract PDF with full Arabic support
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
            'isArabic' => true,
            'useArabicFonts' => true
        ], $additionalData);

        $filename = "contract-{$contract->id}-" . str_slug($contract->title ?? 'contract') . ".pdf";
        
        return $this->generateArabicPdf('pdf.contracts.template', $data, $filename);
    }

    /**
     * Get Arabic-specific PDF data
     *
     * @return array
     */
    protected function getArabicPdfData()
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
            'isRtl' => app()->getLocale() == 'ar',
            'isArabicContext' => true,
            'arabicFontFamily' => 'Tajawal, "DejaVu Sans", sans-serif'
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
        
        return false;
    }

    /**
     * Check specifically for iOS devices
     *
     * @return bool
     */
    protected function isIOSDevice()
    {
        $userAgent = request()->userAgent() ?? '';
        return stripos($userAgent, 'iPhone') !== false || stripos($userAgent, 'iPad') !== false;
    }
}