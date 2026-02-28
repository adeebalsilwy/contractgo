<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Workspace;
use App\Models\Contract;

class AutoWorkspaceAssignmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_assigns_user_to_first_workspace_automatically()
    {
        // Create a workspace
        $workspace = Workspace::factory()->create(['title' => 'Test Workspace']);
        
        // Create a user
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Simulate authenticated request that should trigger middleware
        $response = $this->actingAs($user)
                        ->get('/');

        // Assert user is now assigned to workspace
        $this->assertDatabaseHas('user_workspace', [
            'user_id' => $user->id,
            'workspace_id' => $workspace->id
        ]);

        // Assert user has default workspace
        $user->refresh();
        $this->assertEquals($workspace->id, $user->default_workspace_id);
    }

    /** @test */
    public function it_links_orphaned_contracts_to_workspace()
    {
        // Create a workspace
        $workspace = Workspace::factory()->create(['title' => 'Test Workspace']);
        
        // Create user
        $user = User::factory()->create(['email' => 'test@example.com']);
        $user->workspaces()->attach($workspace->id);

        // Create contract without workspace_id (orphaned)
        $contract = Contract::factory()->create([
            'workspace_id' => null,
            'title' => 'Test Contract'
        ]);

        // Verify it's orphaned
        $this->assertNull($contract->workspace_id);

        // Simulate authenticated request
        $this->actingAs($user)->get('/');

        // Refresh contract
        $contract->refresh();

        // Verify it's now assigned to workspace
        $this->assertEquals($workspace->id, $contract->workspace_id);
    }

    /** @test */
    public function it_does_not_reassign_already_assigned_users()
    {
        // Create workspaces
        $workspace1 = Workspace::factory()->create(['title' => 'Workspace 1']);
        $workspace2 = Workspace::factory()->create(['title' => 'Workspace 2']);
        
        // Create user and assign to workspace1
        $user = User::factory()->create(['email' => 'test@example.com']);
        $user->workspaces()->attach($workspace1->id);
        $user->default_workspace_id = $workspace1->id;
        $user->save();

        // Simulate authenticated request
        $this->actingAs($user)->get('/');

        // Verify user is still assigned to workspace1
        $this->assertTrue($user->workspaces()->where('workspace_id', $workspace1->id)->exists());
        $this->assertEquals($workspace1->id, $user->default_workspace_id);
        
        // Verify user is not assigned to workspace2
        $this->assertFalse($user->workspaces()->where('workspace_id', $workspace2->id)->exists());
    }
}