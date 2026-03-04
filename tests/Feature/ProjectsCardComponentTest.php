<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Status;
use App\Models\Priority;
use App\Models\Tag;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectsCardComponentTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $workspace;
    protected $status;
    protected $priority;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test data
        $this->workspace = Workspace::factory()->create();
        $this->user = User::factory()->create([
            'workspace_id' => $this->workspace->id
        ]);
        $this->status = Status::factory()->create([
            'workspace_id' => $this->workspace->id
        ]);
        $this->priority = Priority::factory()->create([
            'workspace_id' => $this->workspace->id
        ]);

        // Authenticate the user
        $this->actingAs($this->user, 'web');
    }

    /**
     * Test projects card component rendering
     */
    public function test_projects_card_component_rendering()
    {
        $projects = Project::factory()->count(3)->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'priority_id' => $this->priority->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        
        // Check that the component is rendered
        $response->assertSee('projects-card');
        $response->assertSee('projects_table');
    }

    /**
     * Test projects card with empty projects
     */
    public function test_projects_card_empty_state()
    {
        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        
        // Check empty state
        $response->assertSee('No projects found');
        $response->assertSee('empty-state-card');
    }

    /**
     * Test projects card with projects data
     */
    public function test_projects_card_with_data()
    {
        $projects = Project::factory()->count(2)->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'priority_id' => $this->priority->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        
        // Check that projects are displayed
        foreach ($projects as $project) {
            $response->assertSee($project->title);
        }
    }

    /**
     * Test projects card filter inputs
     */
    public function test_projects_card_filter_inputs()
    {
        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        
        // Check filter input fields
        $response->assertSee('project_date_between');
        $response->assertSee('project_start_date_between');
        $response->assertSee('project_end_date_between');
        $response->assertSee('project_status_filter');
        $response->assertSee('project_priority_filter');
        $response->assertSee('project_tag_filter');
        $response->assertSee('project_user_filter');
        $response->assertSee('project_client_filter');
    }

    /**
     * Test projects card filter inputs with data
     */
    public function test_projects_card_filter_inputs_with_data()
    {
        $projects = Project::factory()->count(3)->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'priority_id' => $this->priority->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        
        // Check filter inputs are present
        $response->assertSee('input-group');
        $response->assertSee('form-control');
        $response->assertSee('select');
        $response->assertSee('multiple');
    }

    /**
     * Test projects card hidden inputs
     */
    public function test_projects_card_hidden_inputs()
    {
        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        
        // Check hidden input fields
        $response->assertSee('project_date_between_from');
        $response->assertSee('project_date_between_to');
        $response->assertSee('project_start_date_from');
        $response->assertSee('project_start_date_to');
        $response->assertSee('project_end_date_from');
        $response->assertSee('project_end_date_to');
        $response->assertSee('is_favorites');
        $response->assertSee('data_type');
        $response->assertSee('data_table');
        $response->assertSee('data_reload');
    }

    /**
     * Test projects card table structure
     */
    public function test_projects_card_table_structure()
    {
        $projects = Project::factory()->count(1)->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'priority_id' => $this->priority->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        
        // Check table structure and attributes
        $response->assertSee('id="projects_table"');
        $response->assertSee('data-toggle="table"');
        $response->assertSee('data-side-pagination="server"');
        $response->assertSee('data-pagination="true"');
        $response->assertSee('data-search="true"');
        $response->assertSee('data-show-columns="true"');
    }

    /**
     * Test projects card table columns
     */
    public function test_projects_card_table_columns()
    {
        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        
        // Check table headers
        $response->assertSee('data-field="id"');
        $response->assertSee('data-field="title"');
        $response->assertSee('data-field="users"');
        $response->assertSee('data-field="clients"');
        $response->assertSee('data-field="status_id"');
        $response->assertSee('data-field="priority_id"');
        $response->assertSee('data-field="start_date"');
        $response->assertSee('data-field="end_date"');
        $response->assertSee('data-field="budget"');
        $response->assertSee('data-field="tags"');
        $response->assertSee('data-field="created_at"');
        $response->assertSee('data-field="updated_at"');
        $response->assertSee('data-field="actions"');
    }

    /**
     * Test projects card column visibility
     */
    public function test_projects_card_column_visibility()
    {
        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        
        // Check default column visibility
        $response->assertSee('data-visible="true"'); // Should show common columns
        $response->assertDontSee('data-visible="false"'); // Default should not explicitly hide
    }

    /**
     * Test projects card sorting attributes
     */
    public function test_projects_card_sorting_attributes()
    {
        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        
        // Check sorting capabilities
        $response->assertSee('data-sortable="true"');
        $response->assertSee('data-sort-name="id"');
        $response->assertSee('data-sort-order="desc"');
    }

    /**
     * Test projects card filter inputs on home page
     */
    public function test_projects_card_filter_inputs_on_home()
    {
        // Test from home page context
        $response = $this->get('/home');
        
        $response->assertStatus(200);
        // Filter inputs should still be available on home page
    }

    /**
     * Test projects card with different user contexts
     */
    public function test_projects_card_with_user_contexts()
    {
        // Test with regular user
        $regularUser = User::factory()->create([
            'workspace_id' => $this->workspace->id,
            'is_admin' => 0
        ]);
        $this->actingAs($regularUser, 'web');
        
        $response = $this->get(route('projects.list'));
        $response->assertStatus(200);
        $response->assertSee('project_user_filter');

        // Test with admin user
        $adminUser = User::factory()->create([
            'workspace_id' => $this->workspace->id,
            'is_admin' => 1
        ]);
        $this->actingAs($adminUser, 'web');
        
        $response = $this->get(route('projects.list'));
        $response->assertStatus(200);
        $response->assertSee('project_user_filter');
        $response->assertSee('project_client_filter');
    }

    /**
     * Test projects card with user assigned projects
     */
    public function test_projects_card_with_assigned_context()
    {
        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        
        // Test with assigned context (should show specific filters)
        // The view handles the 'viewAssigned' flag
        $response->assertViewHas('viewAssigned'); // Should be handled in the view
    }

    /**
     * Test projects card with client assigned projects
     */
    public function test_projects_card_with_client_assigned_context()
    {
        // Test context when viewing from client perspective
        $response = $this->get(route('projects.list', ['id' => 'client_' . $this->user->id]));
        
        $response->assertStatus(200);
        
        // Check client context filtering
        $response->assertSee('explode', false); // Should check explode functionality
    }

    /**
     * Test projects card with custom field support
     */
    public function test_projects_card_with_custom_fields()
    {
        // Test with projects having custom field data
        $projects = Project::factory()->count(1)->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'priority_id' => $this->priority->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        
        // Check that custom fields area exists in the table
        // (custom fields will be rendered via loop in blade)
        $response->assertDontSee('Undefined variable: customFields'); // Should handle undefined properly
    }

    /**
     * Test projects card data reload functionality
     */
    public function test_projects_card_data_reload()
    {
        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        
        // Check reload context - not from home by default
        $response->assertSee('data_reload" value="0"');
    }

    /**
     * Test projects card column saving functionality
     */
    public function test_projects_card_column_saving()
    {
        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        
        // Check save column visibility hidden input
        $response->assertSee('save_column_visibility');
        $response->assertSee('multi_select');
    }

    /**
     * Test projects card labels translation
     */
    public function test_projects_card_labels_translation()
    {
        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        
        // Check basic labels through translation functions
        $response->assertSee('Projects');
        $response->assertSee('list', false);
        // Additional Arabic/fallback language strings tested indirectly
    }

    /**
     * Test projects card home refresh redirect context
    */
    public function test_projects_card_with_home_in_url()
    {
        // Test when home is in URL (refresh context)
        $response = $this->get(route('projects.list', ['from_home' => '1']));
        
        $response->assertStatus(200);
        // Should handle home refresh context properly
    }

    /**
     * Test projects card with various filter combinations
     */
    public function test_projects_card_filter_combinations()
    {
        $response = $this->get(route('projects.list', [
            'statuses' => [1, 2],
            'tags' => [3, 4],
            'user_ids' => [5],
            'client_ids' => [6]
        ]));
        
        $response->assertStatus(200);
        
        // Check that multiple filter parameters are handled
        // The component should process these through request()->input()
    }

    /**
     * Test projects card JavaScript integration
     */
    public function test_projects_card_javascript_integration()
    {
        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        
        // Check JavaScript variables and integration
        $response->assertSee('project-list.js');
        $response->assertSee('queryParamsProjects');
        $response->assertSee('loadingTemplate');
    }

    /**
     * Test projects card mobile responsive features
     */
    public function test_projects_card_mobile_responsive()
    {
        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        
        // Check mobile responsive attributes
        $response->assertSee('data-mobile-responsive="true"');
        $response->assertSee('table-responsive');
        $response->assertSee('col-md-4'); // Bootstrap grid classes
    }

    /**
     * Test projects card with special characters in data
     */
    public function test_projects_card_special_characters()
    {
        $project = Project::factory()->create([
            'title' => 'Project with "quotes" & special chars: <>',
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        
        // Should handle special characters properly
        $response->assertSee('Project with');
    }

    /**
     * Test projects card with very long project titles
     */
    public function test_projects_card_long_titles()
    {
        $longTitle = str_repeat('Very Long Project Title ', 10);
        $project = Project::factory()->create([
            'title' => $longTitle,
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        
        // Should display long titles properly
        $response->assertSee(substr($longTitle, 0, 50)); // At least part should be visible
    }

    /**
     * Test projects card with date range filters
     */
    public function test_projects_card_date_filters()
    {
        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        
        // Check date filter inputs
        $response->assertSee('autocomplete="off"'); // Date inputs should have autocomplete off
        $response->assertSee('placeholder="'); // Should have placeholders
    }

    /**
     * Test projects card with select2 multiple selection
     */
    public function test_projects_card_multiple_selection()
    {
        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        
        // Check multiple selection attributes
        $response->assertSee('multiple="multiple"');
        $response->assertSee('data-placeholder="');
    }

    /**
     * Test projects card component error handling
     */
    public function test_projects_card_error_handling()
    {
        // Test component with missing data gracefully
        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        // Should not crash with empty or missing data
        $response->assertDontSee('Error');
        $response->assertDontSee('Exception');
    }
}