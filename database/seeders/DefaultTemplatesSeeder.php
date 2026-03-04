<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WorkflowTemplate;
use App\Models\ContractTemplate;
use App\Models\ExtractTemplate;
use App\Models\User;
use App\Models\Workspace;

class DefaultTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first workspace and user
        $workspace = Workspace::first();
        $user = User::first();
        
        if (!$workspace || !$user) {
            $this->command->info('No workspace or user found. Skipping template seeding.');
            return;
        }
        
        // Create default workflow template
        $this->createDefaultWorkflowTemplate($workspace, $user);
        
        // Create default contract template
        $this->createDefaultContractTemplate($workspace, $user);
        
        // Create default extract template
        $this->createDefaultExtractTemplate($workspace, $user);
        
        $this->command->info('Default templates created successfully!');
    }
    
    private function createDefaultWorkflowTemplate($workspace, $user)
    {
        WorkflowTemplate::create([
            'name' => 'Standard Construction Workflow',
            'description' => 'Default workflow template for construction projects',
            'type' => 'workflow',
            'category' => 'default',
            'content' => $this->getDefaultWorkflowContent(),
            'variables' => $this->getWorkflowVariables(),
            'workflow_steps' => $this->getDefaultWorkflowSteps(),
            'status' => 'active',
            'workspace_id' => $workspace->id,
            'created_by' => $user->id,
            'is_default' => true,
            'version' => '1.0'
        ]);
    }
    
    private function createDefaultContractTemplate($workspace, $user)
    {
        $workflowTemplate = WorkflowTemplate::where('is_default', true)->first();
        
        ContractTemplate::create([
            'name' => 'Standard Construction Contract',
            'description' => 'Professional contract template for construction projects',
            'workflow_template_id' => $workflowTemplate ? $workflowTemplate->id : null,
            'title_template' => 'Contract #{contract_number} - {contract_title}',
            'description_template' => $this->getDefaultContractContent(),
            'terms_conditions' => $this->getDefaultTermsAndConditions(),
            'default_items' => $this->getDefaultContractItems(),
            'workflow_assignments' => $this->getDefaultWorkflowAssignments(),
            'status' => 'active',
            'workspace_id' => $workspace->id,
            'created_by' => $user->id,
            'is_default' => true,
            'version' => '1.0'
        ]);
    }
    
    private function createDefaultExtractTemplate($workspace, $user)
    {
        $workflowTemplate = WorkflowTemplate::where('is_default', true)->first();
        
        ExtractTemplate::create([
            'name' => 'Standard Construction Extract',
            'description' => 'Professional extract template for construction projects',
            'workflow_template_id' => $workflowTemplate ? $workflowTemplate->id : null,
            'title_template' => 'Extract #{extract_number}',
            'description_template' => $this->getDefaultExtractContent(),
            'default_items' => $this->getDefaultExtractItems(),
            'numbering_pattern' => 'EX-{YEAR}-{SEQUENCE}',
            'workflow_assignments' => $this->getDefaultWorkflowAssignments(),
            'status' => 'active',
            'workspace_id' => $workspace->id,
            'created_by' => $user->id,
            'is_default' => true,
            'version' => '1.0'
        ]);
    }
    
    private function getDefaultWorkflowContent()
    {
        return "Standard workflow for construction contracts with automated approval process";
    }
    
    private function getWorkflowVariables()
    {
        return [
            'contract_number',
            'contract_title',
            'client_name',
            'project_name',
            'site_supervisor',
            'quantity_approver',
            'accountant',
            'reviewer',
            'final_approver'
        ];
    }
    
    private function getDefaultWorkflowSteps()
    {
        return [
            [
                'stage' => 'quantity_approval',
                'role' => 'quantity_approver',
                'description' => 'Site supervisor uploads quantities for approval'
            ],
            [
                'stage' => 'management_review',
                'role' => 'reviewer',
                'description' => 'Management review of approved quantities'
            ],
            [
                'stage' => 'accounting_review',
                'role' => 'accountant',
                'description' => 'Accounting processing and journal entry creation'
            ],
            [
                'stage' => 'final_approval',
                'role' => 'final_approver',
                'description' => 'Final approval and archiving'
            ]
        ];
    }
    
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
    
    private function getDefaultTermsAndConditions()
    {
        return "1. This contract is subject to the laws of Yemen.
2. All work shall be performed in accordance with approved plans and specifications.
3. Payment terms: 30 days from invoice date.
4. All modifications require written approval from both parties.
5. Work completion is subject to final inspection and approval.";
    }
    
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
