<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Status;
use App\Models\Priority;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectsViewTest extends TestCase
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
     * Test projects view rendering with data
     */
    public function test_projects_view_with_data()
    {
        // Create test projects
        $projects = Project::factory()->count(3)->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'priority_id' => $this->priority->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        $response->assertViewIs('projects.projects');
        $response->assertViewHas('projects');
        $response->assertViewHas('is_favorites', 0);
        $response->assertViewHas('customFields');
        
        // Check that projects are passed to the view
        $viewProjects = $response->viewData('projects');
        $this->assertCount(3, $viewProjects);
        
        // Check view content
        $response->assertSee('Projects');
        $response->assertSee('List view');
        $response->assertSee('Create project');
        $response->assertSee('Grid view');
        $response->assertSee('Kanban View');
        $response->assertSee('Gantt Chart View');
        $response->assertSee('Calendar view');
    }

    /**
     * Test projects view with favorite projects
     */
    public function test_projects_view_with_favorites()
    {
        // Create regular projects
        Project::factory()->count(2)->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        // Create favorite project
        $favoriteProject = Project::factory()->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        // Mark as favorite
        $this->user->favorites()->create([
            'favoritable_type' => Project::class,
            'favoritable_id' => $favoriteProject->id,
        ]);

        $response = $this->get(route('projects.list', ['type' => 'favorite']));
        
        $response->assertStatus(200);
        $response->assertViewHas('is_favorites', 1);
        $response->assertSee('Favorite');
        
        // Check that only favorite projects are shown
        $viewProjects = $response->viewData('projects');
        $this->assertCount(1, $viewProjects);
        $this->assertEquals($favoriteProject->id, $viewProjects->first()->id);
    }

    /**
     * Test projects view with empty data
     */
    public function test_projects_view_empty()
    {
        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        $response->assertViewIs('projects.projects');
        
        // Check that empty state is shown
        $viewProjects = $response->viewData('projects');
        $this->assertCount(0, $viewProjects);
        
        // Should show empty state message
        $response->assertSee('Projects');
        $response->assertSee('No projects found');
    }

    /**
     * Test projects view breadcrumbs
     */
    public function test_projects_view_breadcrumbs()
    {
        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        $response->assertSee('Home');
        $response->assertSee('Projects');
        $response->assertSee('List');
        $response->assertSee(route('home.index'));
    }

    /**
     * Test projects view with favorite type breadcrumbs
     */
    public function test_projects_view_favorite_breadcrumbs()
    {
        $response = $this->get(route('projects.list', ['type' => 'favorite']));
        
        $response->assertStatus(200);
        $response->assertSee('Home');
        $response->assertSee('Projects');
        $response->assertSee('Favorite');
        $response->assertSee('List');
    }

    /**
     * Test projects view action buttons
     */
    public function test_projects_view_action_buttons()
    {
        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        
        // Check all action buttons are present
        $response->assertSee('Create project', false);
        $response->assertSee('Grid view', false);
        $response->assertSee('Kanban View', false);
        $response->assertSee('Gantt Chart View', false);
        $response->assertSee('Calendar view', false);
        
        // Check button icons
        $response->assertSee('bx-plus', false);
        $response->assertSee('bxs-grid-alt', false);
        $response->assertSee('bx-layout', false);
        $response->assertSee('bx-bar-chart', false);
        $response->assertSee('bx-calendar', false);
    }

    /**
     * Test projects view with default view badge
     */
    public function test_projects_view_default_view_badge()
    {
        // Set user preference for default view
        $this->user->update([
            'default_projects_view' => 'projects/list'
        ]);

        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        $response->assertSee('Default View');
        $response->assertSee('bg-primary');
    }

    /**
     * Test projects view with non-default view
     */
    public function test_projects_view_non_default_badge()
    {
        // Set different default view
        $this->user->update([
            'default_projects_view' => 'projects'
        ]);

        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        $response->assertSee('Set as Default View');
        $response->assertSee('bg-secondary');
        $response->assertSee('id="set-default-view"');
        $response->assertSee('data-type="projects"');
        $response->assertSee('data-view="list"');
    }

    /**
     * Test projects view with filter parameters
     */
    public function test_projects_view_with_filters()
    {
        $response = $this->get(route('projects.list', [
            'statuses' => [1, 2],
            'tags' => [3, 4]
        ]));
        
        $response->assertStatus(200);
        
        // Check that filter parameters are processed correctly
        // The view should handle these parameters in the URL generation
        $response->assertSee('projects/list', false); // Should be in URLs
    }

    /**
     * Test projects view with favorite filter parameters
     */
    public function test_projects_view_with_favorite_filters()
    {
        $response = $this->get(route('projects.list', [
            'type' => 'favorite',
            'statuses' => [1],
            'tags' => [2]
        ]));
        
        $response->assertStatus(200);
        $response->assertViewHas('is_favorites', 1);
        
        // Check that favorite URLs are generated correctly
        $response->assertSee('projects/favorite', false);
    }

    /**
     * Test projects view component rendering
     */
    public function test_projects_view_component_rendering()
    {
        Project::factory()->count(2)->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        
        // Check that the projects-card component is rendered
        $response->assertSee('projects-card');
        $response->assertSee('projects_table');
        $response->assertSee('data_type');
        $response->assertSee('data_table');
        
        // Check filter inputs
        $response->assertSee('project_date_between');
        $response->assertSee('project_start_date_between');
        $response->assertSee('project_end_date_between');
        $response->assertSee('project_status_filter');
        $response->assertSee('project_priority_filter');
        $response->assertSee('project_tag_filter');
    }

    /**
     * Test projects view JavaScript variables
     */
    public function test_projects_view_javascript_variables()
    {
        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        
        // Check JavaScript variables are set
        $response->assertSee('label_update');
        $response->assertSee('label_delete');
        $response->assertSee('label_not_assigned');
        $response->assertSee('add_favorite');
        $response->assertSee('remove_favorite');
        $response->assertSee('label_duplicate');
        
        // Check JavaScript file inclusion
        $response->assertSee('project-list.js');
    }

    /**
     * Test projects view table structure
     */
    public function test_projects_view_table_structure()
    {
        Project::factory()->count(1)->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        
        // Check table structure
        $response->assertSee('projects_table');
        $response->assertSee('data-toggle="table"');
        $response->assertSee('data-url');
        $response->assertSee('data-side-pagination="server"');
        
        // Check table columns
        $response->assertSee('ID');
        $response->assertSee('Title');
        $response->assertSee('Users');
        $response->assertSee('Clients');
        $response->assertSee('Status');
        $response->assertSee('Priority');
        $response->assertSee('Starts at');
        $response->assertSee('Ends at');
        $response->assertSee('Budget');
        $response->assertSee('Tags');
        $response->assertSee('Created at');
        $response->assertSee('Updated at');
        $response->assertSee('Actions');
    }

    /**
     * Test projects view with custom fields
     */
    public function test_projects_view_with_custom_fields()
    {
        // Create projects with custom fields data
        $project = Project::factory()->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        // Custom fields handling should be tested through the component
    }

    /**
     * Test projects view responsive design elements
    public function test_projects_view_responsive_elements()
    {
        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        
        // Check responsive classes
        $response->assertSee('container-fluid');
        $response->assertSee('d-flex');
        $response->assertSee('justify-content-between');
        $response->assertSee('table-responsive');
    }

    /**
     * Test projects view accessibility features
     */
    public function test_projects_view_accessibility()
    {
        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        
        // Check accessibility attributes
        $response->assertSee('aria-label="breadcrumb"');
        $response->assertSee('data-bs-toggle="tooltip"');
        $response->assertSee('data-bs-placement="left"');
    }

    /**
     * Test projects view with different user roles
     */
    public function test_projects_view_with_different_roles()
    {
        // Test with admin user
        $adminUser = User::factory()->create([
            'workspace_id' => $this->workspace->id,
            'is_admin' => 1
        ]);
        
        $this->actingAs($adminUser, 'web');
        
        $response = $this->get(route('projects.list'));
        $response->assertStatus(200);
        
        // Test with regular user
        $this->actingAs($this->user, 'web');
        
        $response = $this->get(route('projects.list'));
        $response->assertStatus(200);
    }

    /**
     * Test projects view performance with large dataset
     */
    public function test_projects_view_performance()
    {
        // Create many projects to test pagination
        Project::factory()->count(50)->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $startTime = microtime(true);
        
        $response = $this->get(route('projects.list'));
        
        $endTime = microtime(true);
        $loadTime = $endTime - $startTime;
        
        $response->assertStatus(200);
        
        // Check that page loads reasonably fast (less than 2 seconds)
        $this->assertLessThan(2.0, $loadTime, 'Page should load within 2 seconds');
    }

    /**
     * Test projects view with search parameters
     */
    public function test_projects_view_with_search()
    {
        $response = $this->get(route('projects.list', ['search' => 'test']));
        
        $response->assertStatus(200);
        // Search parameter should be handled by the backend
    }

    /**
     * Test projects view with sorting parameters
     */
    public function test_projects_view_with_sorting()
    {
        $response = $this->get(route('projects.list', [
            'sort' => 'title',
            'order' => 'asc'
        ]));
        
        $response->assertStatus(200);
        // Sorting parameters should be handled by the backend
    }

    /**
     * Test projects view with pagination parameters
     */
    public function test_projects_view_with_pagination()
    {
        Project::factory()->count(20)->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $response = $this->get(route('projects.list', ['limit' => 5]));
        
        $response->assertStatus(200);
        // Pagination should be handled by the table component
    }
}