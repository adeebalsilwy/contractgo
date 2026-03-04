<?php

namespace App\Services;

use App\Models\WorkflowTemplate;
use App\Models\ContractTemplate;
use App\Models\ExtractTemplate;
use App\Models\Contract;
use App\Models\EstimatesInvoice;

class TemplateService
{
    /**
     * Apply default contract template to a new contract
     */
    public function applyDefaultContractTemplate(Contract $contract)
    {
        $template = ContractTemplate::where('is_default', true)
            ->where('status', 'active')
            ->where('workspace_id', getWorkspaceId())
            ->first();
            
        if (!$template) {
            $template = $this->createDefaultContractTemplate();
        }
        
        // Apply template content
        $variables = $this->getContractVariables($contract);
        $content = $this->replaceVariables($template->description_template, $variables);
        
        // Update contract with templated content
        $contract->update([
            'description' => $content,
            'workflow_status' => 'draft',
            'template_id' => $template->id
        ]);
        
        // Apply workflow template
        $this->applyWorkflowTemplate($contract, $template->workflow_template_id);
        
        // Generate contract items from template
        $this->generateContractItems($contract, $template);
        
        return $contract;
    }
    
    /**
     * Apply default extract template to a new extract
     */
    public function applyDefaultExtractTemplate(EstimatesInvoice $extract)
    {
        $template = ExtractTemplate::where('is_default', true)
            ->where('status', 'active')
            ->where('workspace_id', getWorkspaceId())
            ->first();
            
        if (!$template) {
            $template = $this->createDefaultExtractTemplate();
        }
        
        // Generate extract number
        $extractNumber = $template->generateNumber();
        
        // Apply template content
        $variables = $this->getExtractVariables($extract, $extractNumber);
        $content = $this->replaceVariables($template->description_template, $variables);
        
        // Update extract with templated content
        $extract->update([
            'name' => $extractNumber,
            'description' => $content,
            'template_id' => $template->id
        ]);
        
        // Apply workflow template
        $this->applyWorkflowTemplate($extract, $template->workflow_template_id);
        
        return $extract;
    }
    
    /**
     * Get contract variables for template replacement
     */
    public function getContractVariables(Contract $contract)
    {
        return [
            '{contract_number}' => $contract->id,
            '{contract_title}' => $contract->title,
            '{contract_value}' => format_currency($contract->value ?? 0),
            '{start_date}' => format_date($contract->start_date),
            '{end_date}' => format_date($contract->end_date),
            '{client_name}' => $contract->client->first_name . ' ' . $contract->client->last_name ?? '',
            '{project_name}' => $contract->project->title ?? '',
            '{current_date}' => format_date(now()),
            '{current_datetime}' => now()->format('Y-m-d H:i:s'),
            // Workflow variables
            '{site_supervisor}' => $contract->siteSupervisor->first_name . ' ' . $contract->siteSupervisor->last_name ?? '',
            '{quantity_approver}' => $contract->quantityApprover->first_name . ' ' . $contract->quantityApprover->last_name ?? '',
            '{accountant}' => $contract->accountant->first_name . ' ' . $contract->accountant->last_name ?? '',
            '{reviewer}' => $contract->reviewer->first_name . ' ' . $contract->reviewer->last_name ?? '',
            '{final_approver}' => $contract->finalApprover->first_name . ' ' . $contract->finalApprover->last_name ?? '',
            // Company information
            '{company_name}' => getWorkspaceName(),
            '{company_address}' => getWorkspaceAddress() ?? '',
            '{company_phone}' => getWorkspacePhone() ?? '',
            '{company_email}' => getWorkspaceEmail() ?? ''
        ];
    }
    
    /**
     * Get extract variables for template replacement
     */
    public function getExtractVariables(EstimatesInvoice $extract, $extractNumber = null)
    {
        $contract = $extract->contract;
        
        $variables = [
            '{extract_number}' => $extractNumber ?? $extract->name,
            '{extract_date}' => format_date($extract->created_at),
            '{contract_number}' => $contract->id ?? '',
            '{contract_title}' => $contract->title ?? '',
            '{contract_value}' => format_currency($contract->value ?? 0),
            '{client_name}' => $contract->client->first_name . ' ' . $contract->client->last_name ?? '',
            '{project_name}' => $contract->project->title ?? '',
            '{current_date}' => format_date(now()),
            '{current_datetime}' => now()->format('Y-m-d H:i:s'),
            '{company_name}' => getWorkspaceName(),
            '{company_address}' => getWorkspaceAddress() ?? '',
            '{company_phone}' => getWorkspacePhone() ?? '',
            '{company_email}' => getWorkspaceEmail() ?? ''
        ];
        
        // Add workflow variables if contract exists
        if ($contract) {
            $variables = array_merge($variables, [
                '{site_supervisor}' => $contract->siteSupervisor->first_name . ' ' . $contract->siteSupervisor->last_name ?? '',
                '{quantity_approver}' => $contract->quantityApprover->first_name . ' ' . $contract->quantityApprover->last_name ?? '',
                '{accountant}' => $contract->accountant->first_name . ' ' . $contract->accountant->last_name ?? '',
                '{reviewer}' => $contract->reviewer->first_name . ' ' . $contract->reviewer->last_name ?? '',
                '{final_approver}' => $contract->finalApprover->first_name . ' ' . $contract->finalApprover->last_name ?? ''
            ]);
        }
        
        return $variables;
    }
    
    /**
     * Replace variables in template content
     */
    public function replaceVariables($content, $variables)
    {
        if (empty($content) || empty($variables)) {
            return $content;
        }
        
        // Replace all variables
        foreach ($variables as $variable => $value) {
            $content = str_replace($variable, $value ?? '', $content);
        }
        
        return $content;
    }
    
    /**
     * Apply workflow template to contract or extract
     */
    private function applyWorkflowTemplate($model, $templateId)
    {
        $workflowTemplate = WorkflowTemplate::find($templateId);
        if ($workflowTemplate) {
            $steps = $workflowTemplate->getWorkflowSteps();
            foreach ($steps as $step) {
                // Create workflow steps based on template
                ContractApproval::create([
                    'contract_id' => $model->id,
                    'approval_stage' => $step['stage'] ?? 'quantity_approval',
                    'approver_id' => $this->assignApprover($step['role'] ?? 'quantity_approver', $model),
                    'status' => 'pending'
                ]);
            }
        }
    }
    
    /**
     * Assign approver based on role and model context
     */
    private function assignApprover($role, $model)
    {
        // Try to get approver from model relationships first
        switch ($role) {
            case 'site_supervisor':
                return $model->site_supervisor_id ?? null;
            case 'quantity_approver':
                return $model->quantity_approver_id ?? null;
            case 'accountant':
                return $model->accountant_id ?? null;
            case 'reviewer':
                return $model->reviewer_id ?? null;
            case 'final_approver':
                return $model->final_approver_id ?? null;
            default:
                // Return current user as fallback
                return auth()->id();
        }
    }
    
    /**
     * Generate workflow steps from template
     */
    public function generateWorkflowFromTemplate($contract, $templateId = null)
    {
        if (!$templateId) {
            // Get default workflow template
            $workflowTemplate = WorkflowTemplate::where('is_default', true)
                ->where('status', 'active')
                ->where('workspace_id', getWorkspaceId())
                ->first();
        } else {
            $workflowTemplate = WorkflowTemplate::find($templateId);
        }
        
        if ($workflowTemplate) {
            // Clear existing approvals for this contract
            ContractApproval::where('contract_id', $contract->id)->delete();
            
            // Generate new workflow steps
            $steps = $workflowTemplate->getWorkflowSteps();
            $sequence = 1;
            
            foreach ($steps as $step) {
                ContractApproval::create([
                    'contract_id' => $contract->id,
                    'approval_stage' => $step['stage'] ?? 'quantity_approval',
                    'approver_id' => $this->assignApprover($step['role'] ?? 'quantity_approver', $contract),
                    'status' => 'pending',
                    'sequence' => $sequence++
                ]);
            }
            
            // Update contract workflow status
            $contract->update([
                'workflow_status' => 'quantity_approval',
                'quantity_approval_status' => 'pending'
            ]);
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Get workflow variables for template replacement
     */
    public function getWorkflowVariables($contract)
    {
        return [
            '{workflow_status}' => $contract->workflow_status,
            '{quantity_approval_status}' => $contract->quantity_approval_status,
            '{management_review_status}' => $contract->management_review_status,
            '{accounting_review_status}' => $contract->accounting_review_status,
            '{final_approval_status}' => $contract->final_approval_status,
            '{pending_approvals}' => ContractApproval::where('contract_id', $contract->id)
                ->where('status', 'pending')->count(),
            '{completed_approvals}' => ContractApproval::where('contract_id', $contract->id)
                ->where('status', 'approved')->count(),
            '{rejected_approvals}' => ContractApproval::where('contract_id', $contract->id)
                ->where('status', 'rejected')->count()
        ];
    }
    
    /**
     * Generate contract items from template
     */
    private function generateContractItems(Contract $contract, ContractTemplate $template)
    {
        $items = $template->getDefaultItems();
        foreach ($items as $item) {
            // Create contract quantities based on template items
            // Implementation would depend on the specific requirements
        }
    }
    
    /**
     * Create default contract template if none exists
     */
    private function createDefaultContractTemplate()
    {
        $template = new ContractTemplate();
        $template->name = 'Default Contract Template';
        $template->description = 'Professional contract template for construction projects';
        $template->title_template = 'Contract #{contract_number} - {contract_title}';
        $template->description_template = $this->getDefaultContractContent();
        $template->terms_conditions = $this->getDefaultTermsAndConditions();
        $template->default_items = $this->getDefaultContractItems();
        $template->workflow_assignments = $this->getDefaultWorkflowAssignments();
        $template->status = 'active';
        $template->workspace_id = getWorkspaceId();
        $template->created_by = auth()->id();
        $template->is_default = true;
        $template->version = '1.0';
        $template->save();
        
        return $template;
    }
    
    /**
     * Create default extract template if none exists
     */
    private function createDefaultExtractTemplate()
    {
        $template = new ExtractTemplate();
        $template->name = 'Default Extract Template';
        $template->description = 'Professional extract template for construction projects';
        $template->title_template = 'Extract #{extract_number}';
        $template->description_template = $this->getDefaultExtractContent();
        $template->default_items = $this->getDefaultExtractItems();
        $template->numbering_pattern = 'EX-{YEAR}-{SEQUENCE}';
        $template->workflow_assignments = $this->getDefaultWorkflowAssignments();
        $template->status = 'active';
        $template->workspace_id = getWorkspaceId();
        $template->created_by = auth()->id();
        $template->is_default = true;
        $template->version = '1.0';
        $template->save();
        
        return $template;
    }
    
    /**
     * Get default contract content
     */
    private function getDefaultContractContent()
    {
        return "This contract is made between {client_name} (hereinafter referred to as \"Client\") and {company_name} (hereinafter referred to as \"Contractor\") for the execution of {project_name}.

**Contract Details:**
- Contract Number: {contract_number}
- Contract Title: {contract_title}
- Contract Value: {contract_value}
- Start Date: {start_date}
- End Date: {end_date}
- Project Location: {project_name}

**Parties:**
- Client: {client_name}
- Contractor: {company_name}
- Site Supervisor: {site_supervisor}
- Quantity Approver: {quantity_approver}
- Accountant: {accountant}
- Reviewer: {reviewer}
- Final Approver: {final_approver}

This contract is governed by the standard terms and conditions attached.";
    }
    
    /**
     * Get default extract content
     */
    private function getDefaultExtractContent()
    {
        return "Extract #{extract_number} for Contract #{contract_number}

**Extract Details:**
- Extract Number: {extract_number}
- Extract Date: {extract_date}
- Contract Number: {contract_number}
- Contract Title: {contract_title}
- Contract Value: {contract_value}

**Project Information:**
- Project Name: {project_name}
- Client: {client_name}

This extract represents the current status of work completion and associated costs as of {current_date}.";
    }
    
    /**
     * Get default terms and conditions
     */
    private function getDefaultTermsAndConditions()
    {
        return "1. This contract is subject to the laws of Yemen.
2. All work shall be performed in accordance with approved plans and specifications.
3. Payment terms: 30 days from invoice date.
4. All modifications require written approval from both parties.
5. Work completion is subject to final inspection and approval.";
    }
    
    /**
     * Get default contract items
     */
    private function getDefaultContractItems()
    {
        return [
            [
                'description' => 'Site Preparation',
                'unit' => 'Square Meter',
                'unit_price' => 100.00,
                'quantity' => 0
            ],
            [
                'description' => 'Foundation Work',
                'unit' => 'Cubic Meter',
                'unit_price' => 150.00,
                'quantity' => 0
            ],
            [
                'description' => 'Structural Work',
                'unit' => 'Square Meter',
                'unit_price' => 200.00,
                'quantity' => 0
            ]
        ];
    }
    
    /**
     * Get default extract items
     */
    private function getDefaultExtractItems()
    {
        return [
            [
                'description' => 'Work Progress',
                'unit' => 'Percentage',
                'unit_price' => 0,
                'quantity' => 0
            ]
        ];
    }
    
    /**
     * Get default workflow assignments
     */
    private function getDefaultWorkflowAssignments()
    {
        return [
            'site_supervisor' => null,
            'quantity_approver' => null,
            'accountant' => null,
            'reviewer' => null,
            'final_approver' => null
        ];
    }
}