<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('workflow_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type')->default('workflow'); // workflow, contract, extract
            $table->string('category')->default('default'); // default, custom, shared
            $table->longText('content'); // Template content with variables
            $table->json('variables')->nullable(); // JSON array of available variables
            $table->json('workflow_steps')->nullable(); // JSON array of workflow steps
            $table->enum('status', ['draft', 'active', 'archived'])->default('draft');
            $table->unsignedBigInteger('workspace_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->boolean('is_default')->default(false);
            $table->string('version')->default('1.0');
            $table->text('preview_image')->nullable(); // Preview image path
            $table->timestamps();
            
            // Indexes
            $table->index(['workspace_id', 'type', 'status']);
            $table->index(['is_default', 'type']);
            $table->index(['created_by']);
            
            // Foreign keys
            $table->foreign('workspace_id')->references('id')->on('workspaces')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_templates');
    }
};
