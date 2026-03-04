<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use App\Models\Status;
use App\Models\Priority;
use App\Models\Tag;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProjectsControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $workspace;
    protected $status;
    protected $priority;
    protected $client;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test data
        $this->workspace = Workspace::factory()->create();
        $this->user = User::factory()->create([
            'workspace_id' => $this->workspace->id
        ]);
        $this->client = Client::factory()->create([
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
     * Test project index page
     */
    public function test_project_index_page()
    {
        $response = $this->get(route('projects.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('projects.projects');
        $response->assertViewHas('projects');
        $response->assertViewHas('is_favorites');
        $response->assertViewHas('customFields');
    }

    /**
     * Test project list view
     */
    public function test_project_list_view()
    {
        $response = $this->get(route('projects.list'));
        
        $response->assertStatus(200);
        $response->assertViewIs('projects.projects');
    }

    /**
     * Test favorite projects view
     */
    public function test_favorite_projects_view()
    {
        $response = $this->get(route('projects.list', ['type' => 'favorite']));
        
        $response->assertStatus(200);
        $response->assertViewHas('is_favorites', 1);
    }

    /**
     * Test project creation
     */
    public function test_create_project()
    {
        $projectData = [
            'title' => 'Test Project',
            'status_id' => $this->status->id,
            'priority_id' => $this->priority->id,
            'budget' => '1000.00',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'task_accessibility' => 'assigned_users',
            'description' => 'Test project description',
            'note' => 'Test project note'
        ];

        $response = $this->post(route('projects.store'), $projectData);
        
        $response->assertStatus(302); // Redirect after successful creation
        $this->assertDatabaseHas('projects', [
            'title' => 'Test Project',
            'status_id' => $this->status->id,
            'workspace_id' => $this->workspace->id
        ]);
    }

    /**
     * Test project creation validation
     */
    public function test_create_project_validation()
    {
        $response = $this->post(route('projects.store'), [
            // Missing required fields
        ]);
        
        $response->assertSessionHasErrors(['title', 'status_id', 'task_accessibility']);
    }

    /**
     * Test project creation with invalid dates
     */
    public function test_create_project_invalid_dates()
    {
        $projectData = [
            'title' => 'Invalid Date Project',
            'status_id' => $this->status->id,
            'priority_id' => $this->priority->id,
            'budget' => '1000.00',
            'start_date' => '2024-12-31',
            'end_date' => '2024-01-01', // End date before start date
            'task_accessibility' => 'assigned_users'
        ];

        $response = $this->post(route('projects.store'), $projectData);
        
        $response->assertSessionHasErrors(['end_date']);
    }

    /**
     * Test project creation with invalid budget
     */
    public function test_create_project_invalid_budget()
    {
        $projectData = [
            'title' => 'Invalid Budget Project',
            'status_id' => $this->status->id,
            'priority_id' => $this->priority->id,
            'budget' => 'invalid_budget',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'task_accessibility' => 'assigned_users'
        ];

        $response = $this->post(route('projects.store'), $projectData);
        
        $response->assertSessionHasErrors(['budget']);
    }

    /**
     * Test project creation with users and clients
     */
    public function test_create_project_with_users_and_clients()
    {
        $projectData = [
            'title' => 'Project with Users and Clients',
            'status_id' => $this->status->id,
            'priority_id' => $this->priority->id,
            'budget' => '5000.00',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'task_accessibility' => 'project_users',
            'user_id' => [$this->user->id],
            'client_id' => [$this->client->id],
            'is_favorite' => 1
        ];

        $response = $this->post(route('projects.store'), $projectData);
        
        $response->assertStatus(302);
        
        $project = Project::where('title', 'Project with Users and Clients')->first();
        $this->assertNotNull($project);
        $this->assertTrue($project->users->contains($this->user));
        $this->assertTrue($project->clients->contains($this->client));
        $this->assertTrue($project->favorites->isNotEmpty());
    }

    /**
     * Test project creation with tags
     */
    public function test_create_project_with_tags()
    {
        $tag = Tag::factory()->create([
            'workspace_id' => $this->workspace->id
        ]);

        $projectData = [
            'title' => 'Project with Tags',
            'status_id' => $this->status->id,
            'priority_id' => $this->priority->id,
            'budget' => '2500.00',
            'start_date' => '2024-01-01',
            'end_date' => '2024-06-30',
            'task_accessibility' => 'assigned_users',
            'tag_ids' => [$tag->id]
        ];

        $response = $this->post(route('projects.store'), $projectData);
        
        $response->assertStatus(302);
        
        $project = Project::where('title', 'Project with Tags')->first();
        $this->assertNotNull($project);
        $this->assertTrue($project->tags->contains($tag));
    }

    /**
     * Test project show page
     */
    public function test_show_project()
    {
        $project = Project::factory()->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'priority_id' => $this->priority->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $response = $this->get(route('projects.info', $project->id));
        
        $response->assertStatus(200);
        $response->assertViewIs('projects.project_information');
        $response->assertViewHas('project', $project);
    }

    /**
     * Test project update
     */
    public function test_update_project()
    {
        $project = Project::factory()->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'priority_id' => $this->priority->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $newStatus = Status::factory()->create([
            'workspace_id' => $this->workspace->id
        ]);

        $updateData = [
            'id' => $project->id,
            'title' => 'Updated Project Title',
            'status_id' => $newStatus->id,
            'priority_id' => $this->priority->id,
            'budget' => '2000.00',
            'start_date' => '2024-02-01',
            'end_date' => '2024-11-30',
            'task_accessibility' => 'project_users',
            'description' => 'Updated description',
            'note' => 'Updated note'
        ];

        $response = $this->post('/projects/update', $updateData);
        
        $response->assertStatus(302);
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'title' => 'Updated Project Title',
            'status_id' => $newStatus->id,
            'budget' => 2000.00
        ]);
    }

    /**
     * Test project update validation
     */
    public function test_update_project_validation()
    {
        $project = Project::factory()->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $response = $this->post('/projects/update', [
            'id' => $project->id,
            // Missing required fields
        ]);
        
        $response->assertSessionHasErrors(['title', 'status_id', 'task_accessibility']);
    }

    /**
     * Test project deletion
     */
    public function test_delete_project()
    {
        $project = Project::factory()->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $response = $this->delete(route('projects.destroy', $project->id));
        
        $response->assertStatus(302);
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    /**
     * Test bulk project deletion
     */
    public function test_delete_multiple_projects()
    {
        $projects = Project::factory()->count(3)->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $projectIds = $projects->pluck('id')->toArray();

        $response = $this->post('/projects/destroy_multiple', ['ids' => $projectIds]);
        
        $response->assertStatus(200);
        foreach ($projectIds as $id) {
            $this->assertDatabaseMissing('projects', ['id' => $id]);
        }
    }

    /**
     * Test project favorite status update
     */
    public function test_update_project_favorite_status()
    {
        $project = Project::factory()->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $response = $this->patch("/projects/update-favorite/{$project->id}", [
            'is_favorite' => 1
        ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('favorites', [
            'favoritable_type' => Project::class,
            'favoritable_id' => $project->id,
            'user_id' => $this->user->id
        ]);
    }

    /**
     * Test project pinned status update
     */
    public function test_update_project_pinned_status()
    {
        $project = Project::factory()->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $response = $this->patch("/projects/update-pinned/{$project->id}", [
            'is_pinned' => 1
        ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('pinned', [
            'pinnable_type' => Project::class,
            'pinnable_id' => $project->id,
            'user_id' => $this->user->id
        ]);
    }

    /**
     * Test project status update
     */
    public function test_update_project_status()
    {
        $project = Project::factory()->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $newStatus = Status::factory()->create([
            'workspace_id' => $this->workspace->id
        ]);

        $response = $this->post('/update-project-status', [
            'id' => $project->id,
            'statusId' => $newStatus->id,
            'note' => 'Status updated via test'
        ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'status_id' => $newStatus->id
        ]);
    }

    /**
     * Test project priority update
     */
    public function test_update_project_priority()
    {
        $project = Project::factory()->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'priority_id' => $this->priority->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $newPriority = Priority::factory()->create([
            'workspace_id' => $this->workspace->id
        ]);

        $response = $this->post('/update-project-priority', [
            'id' => $project->id,
            'priorityId' => $newPriority->id
        ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'priority_id' => $newPriority->id
        ]);
    }

    /**
     * Test project duplicate
     */
    public function test_duplicate_project()
    {
        $project = Project::factory()->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'priority_id' => $this->priority->id,
            'created_by' => 'u_' . $this->user->id,
            'title' => 'Original Project'
        ]);

        // Add some relationships
        $project->users()->attach($this->user->id);
        $project->clients()->attach($this->client->id);

        $response = $this->get("/projects/duplicate/{$project->id}");
        
        $response->assertStatus(302); // Redirect after duplication
        
        $duplicatedProject = Project::where('title', 'like', 'Original Project (Copy%')
            ->where('id', '!=', $project->id)
            ->first();
        
        $this->assertNotNull($duplicatedProject);
        $this->assertEquals($project->status_id, $duplicatedProject->status_id);
        $this->assertEquals($project->priority_id, $duplicatedProject->priority_id);
    }

    /**
     * Test project API listing
     */
    public function test_api_list_projects()
    {
        Project::factory()->count(5)->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $response = $this->getJson('/api/v1/projects');
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'error',
            'message',
            'total',
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'status',
                    'priority',
                    'users',
                    'clients',
                    'start_date',
                    'end_date',
                    'budget'
                ]
            ]
        ]);
    }

    /**
     * Test single project API retrieval
     */
    public function test_api_get_single_project()
    {
        $project = Project::factory()->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'priority_id' => $this->priority->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $response = $this->getJson("/api/v1/projects/{$project->id}");
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'error',
            'message',
            'total',
            'data' => [
                [
                    'id',
                    'title',
                    'status',
                    'priority',
                    'users',
                    'clients',
                    'start_date',
                    'end_date',
                    'budget'
                ]
            ]
        ]);
        $response->assertJsonFragment(['id' => $project->id]);
    }

    /**
     * Test project search API
     */
    public function test_api_search_projects()
    {
        $project1 = Project::factory()->create([
            'title' => 'Search Test Project 1',
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $project2 = Project::factory()->create([
            'title' => 'Another Project',
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $response = $this->getJson('/api/v1/projects?search=Search Test');
        
        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => 'Search Test Project 1']);
        $response->assertJsonMissing(['title' => 'Another Project']);
    }

    /**
     * Test project filtering by status
     */
    public function test_api_filter_projects_by_status()
    {
        $status1 = Status::factory()->create([
            'workspace_id' => $this->workspace->id
        ]);
        
        $status2 = Status::factory()->create([
            'workspace_id' => $this->workspace->id
        ]);

        $project1 = Project::factory()->create([
            'title' => 'Status 1 Project',
            'workspace_id' => $this->workspace->id,
            'status_id' => $status1->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $project2 = Project::factory()->create([
            'title' => 'Status 2 Project',
            'workspace_id' => $this->workspace->id,
            'status_id' => $status2->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $response = $this->getJson("/api/v1/projects?status_ids[]={$status1->id}");
        
        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => 'Status 1 Project']);
        $response->assertJsonMissing(['title' => 'Status 2 Project']);
    }

    /**
     * Test project kanban view
     */
    public function test_kanban_view()
    {
        $response = $this->get(route('projects.kanban_view'));
        
        $response->assertStatus(200);
        $response->assertViewIs('projects.kanban_view');
    }

    /**
     * Test project gantt chart view
     */
    public function test_gantt_chart_view()
    {
        $response = $this->get(route('projects.gantt_chart'));
        
        $response->assertStatus(200);
        $response->assertViewIs('projects.gantt_chart');
    }

    /**
     * Test calendar view
     */
    public function test_calendar_view()
    {
        $response = $this->get(route('projects.calendar_view'));
        
        $response->assertStatus(200);
        $response->assertViewIs('projects.calendar_view');
    }

    /**
     * Test get calendar data
     */
    public function test_get_calendar_data()
    {
        $project = Project::factory()->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'start_date' => '2024-01-15',
            'end_date' => '2024-01-30',
            'created_by' => 'u_' . $this->user->id
        ]);

        $response = $this->getJson('/projects/get-calendar-data?start=2024-01-01&end=2024-01-31');
        
        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $project->id]);
        $response->assertJsonFragment(['title' => $project->title]);
    }

    /**
     * Test get statuses
     */
    public function test_get_statuses()
    {
        $response = $this->getJson(route('projects.getStatusesAjax'));
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'statuses' => [
                '*' => ['id', 'title', 'color']
            ]
        ]);
    }

    /**
     * Test get priorities
     */
    public function test_get_priorities()
    {
        $response = $this->getJson(route('projects.getPrioritiesAjax'));
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'priorities' => [
                '*' => ['id', 'title', 'color']
            ]
        ]);
    }

    /**
     * Test mind map view
     */
    public function test_mind_map_view()
    {
        $project = Project::factory()->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $response = $this->get(route('projects.mind_map', $project->id));
        
        $response->assertStatus(200);
        $response->assertViewIs('projects.mind_map');
    }

    /**
     * Test media upload
     */
    public function test_upload_media()
    {
        Storage::fake('public');
        
        $project = Project::factory()->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $file = UploadedFile::fake()->image('project-image.jpg');

        $response = $this->post('/projects/upload-media', [
            'project_id' => $project->id,
            'file' => $file
        ]);
        
        $response->assertStatus(200);
        Storage::disk('public')->assertExists('project-media/' . $file->hashName());
    }

    /**
     * Test project PDF generation
     */
    public function test_generate_project_pdf()
    {
        $project = Project::factory()->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $response = $this->get(route('projects.pdf', $project->id));
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    /**
     * Test unauthorized access
     */
    public function test_unauthorized_access()
    {
        $otherUser = User::factory()->create([
            'workspace_id' => $this->workspace->id
        ]);
        
        $project = Project::factory()->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'created_by' => 'u_' . $otherUser->id
        ]);

        // Try to access project created by another user
        $response = $this->get(route('projects.info', $project->id));
        
        // Should be redirected or show error based on permissions
        $response->assertStatus(302); // or 403 depending on middleware
    }

    /**
     * Test bulk upload form
     */
    public function test_show_bulk_upload_form()
    {
        $response = $this->get(route('projects.showBulkUploadForm'));
        
        $response->assertStatus(200);
        $response->assertViewIs('projects.bulk_upload');
    }

    /**
     * Test default view preference
     */
    public function test_save_view_preference()
    {
        $response = $this->put('/save-projects-view-preference', [
            'view_type' => 'projects',
            'view' => 'list'
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['error' => false]);
    }

    protected function tearDown(): void
    {
        // Clean up any uploaded files
        Storage::fake('public');
        parent::tearDown();
    }
}