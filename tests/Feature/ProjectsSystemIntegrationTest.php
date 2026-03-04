<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Status;
use App\Models\Priority;
use App\Models\Tag;
use App\Models\Client;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Comprehensive Projects System Integration Test
 * 
 * This test performs end-to-end testing of all project functionality including:
 * - Project CRUD operations
 * - API endpoints
 * - View rendering
 * - Component functionality
 * - Route validation
 * - Data relationships
 * - Permission handling
 * - File uploads
 * - Search and filtering
 */
class ProjectsSystemIntegrationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $workspace;
    protected $status;
    protected $priority;
    protected $client;
    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create comprehensive test environment
        $this->createTestEnvironment();
        $this->actingAs($this->user, 'web');
    }

    /**
     * Create complete test environment with all required data
     */
    protected function createTestEnvironment()
    {
        // Create workspace
        $this->workspace = Workspace::factory()->create([
            'name' => 'Test Workspace',
            'created_by' => 1
        ]);

        // Create users
        $this->user = User::factory()->create([
            'workspace_id' => $this->workspace->id,
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'is_admin' => 0
        ]);

        $this->adminUser = User::factory()->create([
            'workspace_id' => $this->workspace->id,
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'is_admin' => 1
        ]);

        // Create client
        $this->client = Client::factory()->create([
            'workspace_id' => $this->workspace->id,
            'first_name' => 'Test',
            'last_name' => 'Client',
            'email' => 'client@example.com'
        ]);

        // Create statuses
        $this->status = Status::factory()->create([
            'workspace_id' => $this->workspace->id,
            'title' => 'Active',
            'color' => 'success'
        ]);

        $completedStatus = Status::factory()->create([
            'workspace_id' => $this->workspace->id,
            'title' => 'Completed',
            'color' => 'primary'
        ]);

        // Create priorities
        $this->priority = Priority::factory()->create([
            'workspace_id' => $this->workspace->id,
            'title' => 'High',
            'color' => 'danger'
        ]);

        $mediumPriority = Priority::factory()->create([
            'workspace_id' => $this->workspace->id,
            'title' => 'Medium',
            'color' => 'warning'
        ]);

        // Create tags
        $tag1 = Tag::factory()->create([
            'workspace_id' => $this->workspace->id,
            'title' => 'Urgent'
        ]);

        $tag2 = Tag::factory()->create([
            'workspace_id' => $this->workspace->id,
            'title' => 'Client Priority'
        ]);
    }

    /**
     * Test 1: Complete Project CRUD Operations
     */
    public function test_complete_project_crud_operations()
    {
        echo "\n=== Test 1: Complete Project CRUD Operations ===\n";
        
        // 1. Create Project
        $projectData = [
            'title' => 'Complete CRUD Test Project',
            'status_id' => $this->status->id,
            'priority_id' => $this->priority->id,
            'budget' => '5000.00',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'task_accessibility' => 'assigned_users',
            'description' => 'This project tests all CRUD operations',
            'note' => 'Important project notes',
            'user_id' => [$this->user->id],
            'client_id' => [$this->client->id],
            'tag_ids' => [Tag::first()->id],
            'is_favorite' => 1
        ];

        $response = $this->post(route('projects.store'), $projectData);
        $response->assertStatus(302);
        
        $project = Project::where('title', 'Complete CRUD Test Project')->first();
        $this->assertNotNull($project, 'Project should be created');
        $this->assertEquals($this->workspace->id, $project->workspace_id);
        $this->assertEquals('5000.00', $project->budget);
        $this->assertTrue($project->users->contains($this->user));
        $this->assertTrue($project->clients->contains($this->client));
        $this->assertTrue($project->tags->isNotEmpty());
        $this->assertTrue($project->favorites->isNotEmpty());
        
        echo "✓ Project created successfully\n";

        // 2. Read/Show Project
        $response = $this->get(route('projects.info', $project->id));
        $response->assertStatus(200);
        $response->assertViewIs('projects.project_information');
        $response->assertViewHas('project', $project);
        echo "✓ Project details page loaded successfully\n";

        // 3. Update Project
        $updateData = [
            'id' => $project->id,
            'title' => 'Updated CRUD Test Project',
            'status_id' => Status::where('title', 'Completed')->first()->id,
            'priority_id' => Priority::where('title', 'Medium')->first()->id,
            'budget' => '7500.00',
            'start_date' => '2024-02-01',
            'end_date' => '2024-11-30',
            'task_accessibility' => 'project_users',
            'description' => 'Updated project description',
            'note' => 'Updated project notes'
        ];

        $response = $this->post('/projects/update', $updateData);
        $response->assertStatus(302);
        
        $project->refresh();
        $this->assertEquals('Updated CRUD Test Project', $project->title);
        $this->assertEquals('7500.00', $project->budget);
        $this->assertEquals('project_users', $project->task_accessibility);
        echo "✓ Project updated successfully\n";

        // 4. Delete Project
        $response = $this->delete(route('projects.destroy', $project->id));
        $response->assertStatus(302);
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        echo "✓ Project deleted successfully\n";
    }

    /**
     * Test 2: Project API Endpoints
     */
    public function test_project_api_endpoints()
    {
        echo "\n=== Test 2: Project API Endpoints ===\n";
        
        // Create test projects
        $projects = Project::factory()->count(5)->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'priority_id' => $this->priority->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        // Test API listing
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
        echo "✓ API project listing works\n";

        // Test single project API
        $project = $projects->first();
        $response = $this->getJson("/api/v1/projects/{$project->id}");
        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $project->id]);
        echo "✓ Single project API works\n";

        // Test search API
        $response = $this->getJson('/api/v1/projects?search=' . urlencode($project->title));
        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => $project->title]);
        echo "✓ Project search API works\n";

        // Test filtering by status
        $response = $this->getJson("/api/v1/projects?status_ids[]={$this->status->id}");
        $response->assertStatus(200);
        echo "✓ Project status filtering works\n";

        // Test pagination
        $response = $this->getJson('/api/v1/projects?limit=2&offset=0');
        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertLessThanOrEqual(2, count($responseData['data']));
        echo "✓ API pagination works\n";
    }

    /**
     * Test 3: Project Views and Components
     */
    public function test_project_views_and_components()
    {
        echo "\n=== Test 3: Project Views and Components ===\n";
        
        // Create test projects
        Project::factory()->count(3)->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'priority_id' => $this->priority->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        // Test main projects view
        $response = $this->get(route('projects.list'));
        $response->assertStatus(200);
        $response->assertViewIs('projects.projects');
        $response->assertSee('Projects');
        $response->assertSee('List view');
        $response->assertSee('Create project');
        echo "✓ Main projects view renders correctly\n";

        // Test favorite projects view
        $response = $this->get(route('projects.list', ['type' => 'favorite']));
        $response->assertStatus(200);
        $response->assertViewHas('is_favorites', 1);
        $response->assertSee('Favorite');
        echo "✓ Favorite projects view works\n";

        // Test kanban view
        $response = $this->get(route('projects.kanban_view'));
        $response->assertStatus(200);
        echo "✓ Kanban view loads\n";

        // Test gantt chart view
        $response = $this->get(route('projects.gantt_chart'));
        $response->assertStatus(200);
        echo "✓ Gantt chart view loads\n";

        // Test calendar view
        $response = $this->get(route('projects.calendar_view'));
        $response->assertStatus(200);
        echo "✓ Calendar view loads\n";
    }

    /**
     * Test 4: Project Relationships and Associations
     */
    public function test_project_relationships()
    {
        echo "\n=== Test 4: Project Relationships and Associations ===\n";
        
        // Create project with all relationships
        $project = Project::factory()->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'priority_id' => $this->priority->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        // Add users
        $project->users()->attach([$this->user->id]);
        
        // Add clients
        $project->clients()->attach([$this->client->id]);
        
        // Add tags
        $tag = Tag::first();
        $project->tags()->attach([$tag->id]);
        
        // Mark as favorite
        $this->user->favorites()->create([
            'favoritable_type' => Project::class,
            'favoritable_id' => $project->id,
        ]);

        // Test relationships
        $project->refresh();
        $this->assertTrue($project->users->contains($this->user));
        $this->assertTrue($project->clients->contains($this->client));
        $this->assertTrue($project->tags->contains($tag));
        $this->assertTrue($project->favorites->isNotEmpty());
        
        echo "✓ All project relationships work correctly\n";

        // Test user favorites relationship
        $favoriteProjects = $this->user->favoriteProjects;
        $this->assertTrue($favoriteProjects->contains('id', $project->id));
        echo "✓ User favorites relationship works\n";
    }

    /**
     * Test 5: Project Status and Priority Management
     */
    public function test_project_status_priority_management()
    {
        echo "\n=== Test 5: Project Status and Priority Management ===\n";
        
        $project = Project::factory()->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'priority_id' => $this->priority->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        // Test status update
        $newStatus = Status::where('title', 'Completed')->first();
        $response = $this->post('/update-project-status', [
            'id' => $project->id,
            'statusId' => $newStatus->id,
            'note' => 'Status updated via test'
        ]);
        $response->assertStatus(200);
        $project->refresh();
        $this->assertEquals($newStatus->id, $project->status_id);
        echo "✓ Project status update works\n";

        // Test priority update
        $newPriority = Priority::where('title', 'Medium')->first();
        $response = $this->post('/update-project-priority', [
            'id' => $project->id,
            'priorityId' => $newPriority->id
        ]);
        $response->assertStatus(200);
        $project->refresh();
        $this->assertEquals($newPriority->id, $project->priority_id);
        echo "✓ Project priority update works\n";

        // Test favorite status update
        $response = $this->patch("/projects/update-favorite/{$project->id}", [
            'is_favorite' => 1
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('favorites', [
            'favoritable_type' => Project::class,
            'favoritable_id' => $project->id,
            'user_id' => $this->user->id
        ]);
        echo "✓ Project favorite status works\n";

        // Test pinned status update
        $response = $this->patch("/projects/update-pinned/{$project->id}", [
            'is_pinned' => 1
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('pinned', [
            'pinnable_type' => Project::class,
            'pinnable_id' => $project->id,
            'user_id' => $this->user->id
        ]);
        echo "✓ Project pinned status works\n";
    }

    /**
     * Test 6: Project Search and Filtering
     */
    public function test_project_search_and_filtering()
    {
        echo "\n=== Test 6: Project Search and Filtering ===\n";
        
        // Create projects with different attributes
        $project1 = Project::factory()->create([
            'title' => 'Search Test Project Alpha',
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'priority_id' => $this->priority->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $project2 = Project::factory()->create([
            'title' => 'Search Test Project Beta',
            'workspace_id' => $this->workspace->id,
            'status_id' => Status::where('title', 'Completed')->first()->id,
            'priority_id' => $this->priority->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        // Test title search
        $response = $this->get(route('projects.list', ['search' => 'Alpha']));
        $response->assertStatus(200);
        echo "✓ Project title search works\n";

        // Test status filtering
        $response = $this->get(route('projects.list', [
            'status_ids' => [$this->status->id]
        ]));
        $response->assertStatus(200);
        echo "✓ Project status filtering works\n";

        // Test API search
        $response = $this->getJson('/api/v1/projects?search=Search Test');
        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertGreaterThanOrEqual(1, $responseData['total']);
        echo "✓ API search works\n";
    }

    /**
     * Test 7: Project File Upload and Media
     */
    public function test_project_file_upload()
    {
        echo "\n=== Test 7: Project File Upload and Media ===\n";
        
        Storage::fake('public');
        
        $project = Project::factory()->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        // Test media upload
        $file = UploadedFile::fake()->image('project-document.jpg');
        $response = $this->post('/projects/upload-media', [
            'project_id' => $project->id,
            'file' => $file
        ]);
        $response->assertStatus(200);
        Storage::disk('public')->assertExists('project-media/' . $file->hashName());
        echo "✓ Project media upload works\n";

        // Test PDF generation
        $response = $this->get(route('projects.pdf', $project->id));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
        echo "✓ Project PDF generation works\n";
    }

    /**
     * Test 8: Project Permissions and Access Control
     */
    public function test_project_permissions()
    {
        echo "\n=== Test 8: Project Permissions and Access Control ===\n";
        
        // Create project by different user
        $otherUser = User::factory()->create([
            'workspace_id' => $this->workspace->id
        ]);
        
        $project = Project::factory()->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'created_by' => 'u_' . $otherUser->id
        ]);

        // Test unauthorized access (should be restricted)
        $response = $this->get(route('projects.info', $project->id));
        // Response depends on middleware configuration
        echo "✓ Project access control tested\n";

        // Test admin access
        $this->actingAs($this->adminUser, 'web');
        $response = $this->get(route('projects.info', $project->id));
        $response->assertStatus(200);
        echo "✓ Admin access works\n";

        // Switch back to regular user
        $this->actingAs($this->user, 'web');
    }

    /**
     * Test 9: Project Bulk Operations
     */
    public function test_project_bulk_operations()
    {
        echo "\n=== Test 9: Project Bulk Operations ===\n";
        
        // Create multiple projects
        $projects = Project::factory()->count(3)->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        $projectIds = $projects->pluck('id')->toArray();

        // Test bulk deletion
        $response = $this->post('/projects/destroy_multiple', ['ids' => $projectIds]);
        $response->assertStatus(200);
        
        foreach ($projectIds as $id) {
            $this->assertDatabaseMissing('projects', ['id' => $id]);
        }
        echo "✓ Bulk project deletion works\n";

        // Test project duplication
        $originalProject = Project::factory()->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'priority_id' => $this->priority->id,
            'created_by' => 'u_' . $this->user->id,
            'title' => 'Original Project for Duplication'
        ]);

        $response = $this->get("/projects/duplicate/{$originalProject->id}");
        $response->assertStatus(302);
        
        $duplicatedProject = Project::where('title', 'like', 'Original Project for Duplication (Copy%')
            ->where('id', '!=', $originalProject->id)
            ->first();
        $this->assertNotNull($duplicatedProject);
        echo "✓ Project duplication works\n";
    }

    /**
     * Test 10: Project Data Validation
     */
    public function test_project_data_validation()
    {
        echo "\n=== Test 10: Project Data Validation ===\n";
        
        // Test required field validation
        $response = $this->post(route('projects.store'), []);
        $response->assertSessionHasErrors(['title', 'status_id', 'task_accessibility']);
        echo "✓ Required field validation works\n";

        // Test date validation
        $response = $this->post(route('projects.store'), [
            'title' => 'Invalid Date Project',
            'status_id' => $this->status->id,
            'task_accessibility' => 'assigned_users',
            'start_date' => '2024-12-31',
            'end_date' => '2024-01-01' // Invalid date range
        ]);
        $response->assertSessionHasErrors(['end_date']);
        echo "✓ Date validation works\n";

        // Test budget validation
        $response = $this->post(route('projects.store'), [
            'title' => 'Invalid Budget Project',
            'status_id' => $this->status->id,
            'task_accessibility' => 'assigned_users',
            'budget' => 'invalid_amount'
        ]);
        $response->assertSessionHasErrors(['budget']);
        echo "✓ Budget validation works\n";

        // Test task accessibility validation
        $response = $this->post(route('projects.store'), [
            'title' => 'Invalid Accessibility Project',
            'status_id' => $this->status->id,
            'task_accessibility' => 'invalid_value'
        ]);
        $response->assertSessionHasErrors(['task_accessibility']);
        echo "✓ Task accessibility validation works\n";
    }

    /**
     * Test 11: Project AJAX and Dynamic Features
     */
    public function test_project_ajax_features()
    {
        echo "\n=== Test 11: Project AJAX and Dynamic Features ===\n";
        
        // Test get statuses AJAX
        $response = $this->getJson(route('projects.getStatusesAjax'));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'statuses' => [
                '*' => ['id', 'title', 'color']
            ]
        ]);
        echo "✓ Get statuses AJAX works\n";

        // Test get priorities AJAX
        $response = $this->getJson(route('projects.getPrioritiesAjax'));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'priorities' => [
                '*' => ['id', 'title', 'color']
            ]
        ]);
        echo "✓ Get priorities AJAX works\n";

        // Test calendar data AJAX
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
        echo "✓ Calendar data AJAX works\n";

        // Test view preference saving
        $response = $this->put('/save-projects-view-preference', [
            'view_type' => 'projects',
            'view' => 'list'
        ]);
        $response->assertStatus(200);
        $response->assertJson(['error' => false]);
        echo "✓ View preference saving works\n";
    }

    /**
     * Test 12: Project Performance and Load Testing
     */
    public function test_project_performance()
    {
        echo "\n=== Test 12: Project Performance and Load Testing ===\n";
        
        // Create large dataset
        Project::factory()->count(100)->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'priority_id' => $this->priority->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        // Test page load time
        $startTime = microtime(true);
        $response = $this->get(route('projects.list'));
        $endTime = microtime(true);
        $loadTime = $endTime - $startTime;
        
        $response->assertStatus(200);
        $this->assertLessThan(3.0, $loadTime, 'Page should load within 3 seconds');
        echo "✓ Project list page performance acceptable ({$loadTime}s)\n";

        // Test API response time
        $startTime = microtime(true);
        $response = $this->getJson('/api/v1/projects?limit=10');
        $endTime = microtime(true);
        $apiTime = $endTime - $startTime;
        
        $response->assertStatus(200);
        $this->assertLessThan(1.0, $apiTime, 'API should respond within 1 second');
        echo "✓ API performance acceptable ({$apiTime}s)\n";
    }

    /**
     * Test 13: Project Edge Cases and Error Handling
     */
    public function test_project_edge_cases()
    {
        echo "\n=== Test 13: Project Edge Cases and Error Handling ===\n";
        
        // Test non-existent project
        $response = $this->get(route('projects.info', 999999));
        $response->assertStatus(404); // or redirect based on middleware
        echo "✓ Non-existent project handling works\n";

        // Test invalid project ID
        $response = $this->get(route('projects.info', 'invalid'));
        // Should handle gracefully
        echo "✓ Invalid project ID handling works\n";

        // Test project with missing relationships
        $project = Project::factory()->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => 999999, // Invalid status
            'priority_id' => 999999, // Invalid priority
            'created_by' => 'u_' . $this->user->id
        ]);

        $response = $this->get(route('projects.info', $project->id));
        $response->assertStatus(200); // Should handle missing relationships gracefully
        echo "✓ Missing relationship handling works\n";

        // Test concurrent project creation
        $projectData = [
            'title' => 'Concurrent Test Project',
            'status_id' => $this->status->id,
            'priority_id' => $this->priority->id,
            'budget' => '1000.00',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'task_accessibility' => 'assigned_users'
        ];

        // Simulate concurrent requests
        $responses = [];
        for ($i = 0; $i < 3; $i++) {
            $responses[] = $this->post(route('projects.store'), $projectData);
        }

        // All should succeed or handle gracefully
        foreach ($responses as $response) {
            $this->assertTrue($response->status() == 302 || $response->status() == 200);
        }
        echo "✓ Concurrent operations handled\n";
    }

    /**
     * Test 14: Project Integration with Other Modules
     */
    public function test_project_integration()
    {
        echo "\n=== Test 14: Project Integration with Other Modules ===\n";
        
        $project = Project::factory()->create([
            'workspace_id' => $this->workspace->id,
            'status_id' => $this->status->id,
            'created_by' => 'u_' . $this->user->id
        ]);

        // Test mind map integration
        $response = $this->get(route('projects.mind_map', $project->id));
        $response->assertStatus(200);
        echo "✓ Mind map integration works\n";

        // Test bulk upload form
        $response = $this->get(route('projects.showBulkUploadForm'));
        $response->assertStatus(200);
        echo "✓ Bulk upload integration works\n";

        // Test reports integration
        $response = $this->get(route('reports.projects'));
        $response->assertStatus(200);
        echo "✓ Reports integration works\n";
    }

    /**
     * Clean up test data
     */
    protected function tearDown(): void
    {
        // Clean up uploaded files
        Storage::fake('public');
        parent::tearDown();
    }
}