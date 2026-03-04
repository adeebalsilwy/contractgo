<?php

namespace App\Http\Controllers;

use App\Models\WorkflowTemplate;
use App\Models\ContractTemplate;
use App\Models\ExtractTemplate;
use App\Models\Workspace;
use App\Models\User;
use App\Services\TemplateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class TemplateController extends Controller
{
    protected $workspace;
    protected $user;
    protected $templateService;
    
    public function __construct(TemplateService $templateService)
    {
        $this->middleware(function ($request, $next) {
            $this->workspace = Workspace::find(getWorkspaceId());
            $this->user = getAuthenticatedUser();
            return $next($request);
        });
        
        $this->templateService = $templateService;
    }
    
    /**
     * Display template management dashboard
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'all');
        $status = $request->get('status', 'all');
        $search = $request->get('search');
        
        // Get templates based on type
        $templates = $this->getTemplatesByType($type, $status, $search);
        
        return view('templates.index', compact('templates', 'type', 'status', 'search'));
    }
    
    /**
     * Show template creation form
     */
    public function create(Request $request)
    {
        $type = $request->get('type', 'workflow');
        $workspaces = Workspace::all();
        $users = User::all();
        
        return view('templates.create', compact('type', 'workspaces', 'users'));
    }
    
    /**
     * Store new template
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required|in:workflow,contract,extract',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'content' => 'required|string',
                'status' => 'required|in:draft,active,archived',
                'is_default' => 'boolean',
                'workspace_id' => 'nullable|exists:workspaces,id',
                'variables' => 'nullable|array',
                'workflow_steps' => 'nullable|array'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => $validator->errors()->first()
                ]);
            }
            
            $templateData = $request->only([
                'name', 'description', 'content', 'status', 'workspace_id',
                'variables', 'workflow_steps', 'is_default'
            ]);
            
            $templateData['type'] = $request->type;
            $templateData['category'] = 'custom';
            $templateData['created_by'] = $this->user->id;
            $templateData['version'] = '1.0';
            
            $template = $this->createTemplateByType($request->type, $templateData);
            
            Session::flash('message', 'Template created successfully.');
            return response()->json([
                'error' => false,
                'id' => $template->id,
                'message' => 'Template created successfully.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Show template edit form
     */
    public function edit($id, Request $request)
    {
        $template = $this->getTemplateById($id);
        $workspaces = Workspace::all();
        $users = User::all();
        
        if (!$template) {
            abort(404, 'Template not found');
        }
        
        return view('templates.edit', compact('template', 'workspaces', 'users'));
    }
    
    /**
     * Update template
     */
    public function update(Request $request, $id)
    {
        try {
            $template = $this->getTemplateById($id);
            
            if (!$template) {
                return response()->json([
                    'error' => true,
                    'message' => 'Template not found'
                ]);
            }
            
            // Check authorization
            if (!isAdminOrHasAllDataAccess() && $this->user->id != $template->created_by) {
                abort(403, 'Unauthorized to edit this template');
            }
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'content' => 'required|string',
                'status' => 'required|in:draft,active,archived',
                'is_default' => 'boolean',
                'workspace_id' => 'nullable|exists:workspaces,id'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => $validator->errors()->first()
                ]);
            }
            
            $templateData = $request->only([
                'name', 'description', 'content', 'status', 'workspace_id', 'is_default'
            ]);
            
            // Handle variables and workflow steps for workflow templates
            if ($request->has('variables')) {
                $templateData['variables'] = $request->variables;
            }
            
            if ($request->has('workflow_steps')) {
                $templateData['workflow_steps'] = $request->workflow_steps;
            }
            
            // Handle version increment
            if ($template->isDirty(array_keys($templateData))) {
                $templateData['version'] = $this->incrementVersion($template->version);
            }
            
            $template->update($templateData);
            
            Session::flash('message', 'Template updated successfully.');
            return response()->json([
                'error' => false,
                'message' => 'Template updated successfully.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Delete template
     */
    public function destroy($id)
    {
        try {
            $template = $this->getTemplateById($id);
            
            if (!$template) {
                return response()->json([
                    'error' => true,
                    'message' => 'Template not found'
                ]);
            }
            
            // Check authorization
            if (!isAdminOrHasAllDataAccess() && $this->user->id != $template->created_by) {
                abort(403, 'Unauthorized to delete this template');
            }
            
            // Don't delete default templates
            if ($template->is_default) {
                return response()->json([
                    'error' => true,
                    'message' => 'Cannot delete default templates'
                ]);
            }
            
            $template->delete();
            
            Session::flash('message', 'Template deleted successfully.');
            return response()->json([
                'error' => false,
                'message' => 'Template deleted successfully.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Update template priorities via drag-and-drop
     */
    public function updatePriorities(Request $request)
    {
        try {
            $priorities = $request->get('priorities', []);
            
            DB::transaction(function () use ($priorities) {
                foreach ($priorities as $index => $item) {
                    $template = $this->getTemplateById($item['id']);
                    if ($template) {
                        $template->update(['priority' => $index + 1]);
                    }
                }
            });
            
            return response()->json([
                'error' => false,
                'message' => 'Template priorities updated successfully.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Preview template with sample data
     */
    public function preview($id, Request $request)
    {
        try {
            $template = $this->getTemplateById($id);
            
            if (!$template) {
                return response()->json([
                    'error' => true,
                    'message' => 'Template not found'
                ]);
            }
            
            // Generate sample context data
            $sampleData = $this->generateSampleContext($template->type);
            
            // Process template with sample data
            $previewContent = $this->templateService->replaceVariables(
                $template->content, 
                $sampleData
            );
            
            return response()->json([
                'error' => false,
                'preview' => $previewContent,
                'sample_data' => $sampleData
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Duplicate template
     */
    public function duplicate($id, Request $request)
    {
        try {
            $template = $this->getTemplateById($id);
            
            if (!$template) {
                return response()->json([
                    'error' => true,
                    'message' => 'Template not found'
                ]);
            }
            
            $newName = $request->get('name', $template->name . ' (Copy)');
            
            $newTemplate = $template->duplicate($newName);
            
            Session::flash('message', 'Template duplicated successfully.');
            return response()->json([
                'error' => false,
                'id' => $newTemplate->id,
                'message' => 'Template duplicated successfully.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get templates list for AJAX requests
     */
    public function list(Request $request)
    {
        try {
            $type = $request->get('type', 'all');
            $status = $request->get('status', 'all');
            $search = $request->get('search');
            
            $templates = $this->getTemplatesByType($type, $status, $search);
            
            // Format for DataTable
            $rows = [];
            foreach ($templates as $template) {
                $rows[] = [
                    'id' => $template->id,
                    'name' => $template->name,
                    'type' => ucfirst($template->type),
                    'status' => $this->getStatusBadge($template->status),
                    'workspace' => $template->workspace->name ?? 'All Workspaces',
                    'created_by' => $template->creator->first_name . ' ' . $template->creator->last_name,
                    'version' => $template->version,
                    'is_default' => $template->is_default ? '<span class="badge bg-success">Default</span>' : '',
                    'created_at' => format_date($template->created_at, true),
                    'actions' => $this->getActionButtons($template)
                ];
            }
            
            return response()->json([
                'total' => $templates->count(),
                'rows' => $rows
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Private helper methods
     */
    private function getTemplatesByType($type, $status, $search)
    {
        $query = null;
        
        switch ($type) {
            case 'workflow':
                $query = WorkflowTemplate::query();
                break;
            case 'contract':
                $query = ContractTemplate::query();
                break;
            case 'extract':
                $query = ExtractTemplate::query();
                break;
            default:
                // Union all template types
                $workflowTemplates = WorkflowTemplate::select('id', 'name', 'type', 'status', 'workspace_id', 'created_by', 'version', 'is_default', 'created_at')
                    ->selectRaw('"workflow" as template_type');
                $contractTemplates = ContractTemplate::select('id', 'name', 'type', 'status', 'workspace_id', 'created_by', 'version', 'is_default', 'created_at')
                    ->selectRaw('"contract" as template_type');
                $extractTemplates = ExtractTemplate::select('id', 'name', 'type', 'status', 'workspace_id', 'created_by', 'version', 'is_default', 'created_at')
                    ->selectRaw('"extract" as template_type');
                
                $query = $workflowTemplates->union($contractTemplates)->union($extractTemplates);
                break;
        }
        
        if ($query) {
            if ($status !== 'all') {
                $query->where('status', $status);
            }
            
            if ($search) {
                $query->where('name', 'like', "%{$search}%")
                       ->orWhere('description', 'like', "%{$search}%");
            }
            
            if (!isAdminOrHasAllDataAccess()) {
                $query->where('created_by', $this->user->id);
            }
            
            $query->orderBy('created_at', 'desc');
        }
        
        return $query ? $query->get() : collect();
    }
    
    private function getTemplateById($id)
    {
        // Try to find in each template type
        return WorkflowTemplate::find($id) ?: 
               ContractTemplate::find($id) ?: 
               ExtractTemplate::find($id);
    }
    
    private function createTemplateByType($type, $data)
    {
        switch ($type) {
            case 'workflow':
                return WorkflowTemplate::create($data);
            case 'contract':
                return ContractTemplate::create($data);
            case 'extract':
                return ExtractTemplate::create($data);
            default:
                throw new \Exception('Invalid template type');
        }
    }
    
    private function incrementVersion($version)
    {
        $parts = explode('.', $version);
        $parts[count($parts) - 1]++;
        return implode('.', $parts);
    }
    
    private function generateSampleContext($templateType)
    {
        $baseData = [
            'current_date' => date('Y-m-d'),
            'current_datetime' => date('Y-m-d H:i:s'),
            'company_name' => getWorkspaceName(),
            'company_address' => getWorkspaceAddress() ?? 'Sample Address',
            'company_phone' => getWorkspacePhone() ?? '+967 123 456 789',
            'company_email' => getWorkspaceEmail() ?? 'info@company.com'
        ];
        
        switch ($templateType) {
            case 'contract':
                return array_merge($baseData, [
                    'contract_number' => 'CTR-001',
                    'contract_title' => 'Sample Construction Contract',
                    'contract_value' => '1,500,000 YER',
                    'start_date' => date('Y-m-d'),
                    'end_date' => date('Y-m-d', strtotime('+1 year')),
                    'client_name' => 'Sample Client Name',
                    'project_name' => 'Sample Construction Project',
                    'site_supervisor' => 'Sample Supervisor',
                    'quantity_approver' => 'Sample Approver',
                    'accountant' => 'Sample Accountant',
                    'reviewer' => 'Sample Reviewer',
                    'final_approver' => 'Sample Final Approver'
                ]);
            
            case 'extract':
                return array_merge($baseData, [
                    'extract_number' => 'EX-2026-001',
                    'extract_date' => date('Y-m-d'),
                    'contract_number' => 'CTR-001',
                    'contract_title' => 'Sample Construction Contract',
                    'contract_value' => '1,500,000 YER',
                    'client_name' => 'Sample Client Name',
                    'project_name' => 'Sample Construction Project'
                ]);
            
            default:
                return $baseData;
        }
    }
    
    private function getStatusBadge($status)
    {
        $badges = [
            'draft' => '<span class="badge bg-secondary">Draft</span>',
            'active' => '<span class="badge bg-success">Active</span>',
            'archived' => '<span class="badge bg-dark">Archived</span>'
        ];
        
        return $badges[$status] ?? '<span class="badge bg-secondary">' . ucfirst($status) . '</span>';
    }
    
    private function getActionButtons($template)
    {
        $buttons = '';
        
        // Preview button
        $buttons .= '<a href="javascript:void(0);" class="btn btn-sm btn-info preview-template" data-id="' . $template->id . '" title="Preview">';
        $buttons .= '<i class="bx bx-show"></i></a> ';
        
        // Edit button
        if ($template->isEditable()) {
            $buttons .= '<a href="' . route('templates.edit', $template->id) . '" class="btn btn-sm btn-primary" title="Edit">';
            $buttons .= '<i class="bx bx-edit"></i></a> ';
        }
        
        // Duplicate button
        $buttons .= '<a href="javascript:void(0);" class="btn btn-sm btn-secondary duplicate-template" data-id="' . $template->id . '" title="Duplicate">';
        $buttons .= '<i class="bx bx-copy"></i></a> ';
        
        // Delete button (only for non-default, editable templates)
        if (!$template->is_default && $template->isEditable()) {
            $buttons .= '<button type="button" class="btn btn-sm btn-danger delete-template" data-id="' . $template->id . '" title="Delete">';
            $buttons .= '<i class="bx bx-trash"></i></button>';
        }
        
        return $buttons;
    }
}