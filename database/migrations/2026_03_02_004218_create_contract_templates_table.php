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
        Schema::create('contract_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('workflow_template_id')->nullable();
            $table->unsignedBigInteger('pdf_template_id')->nullable();
            $table->unsignedBigInteger('notification_template_id')->nullable();
            $table->text('title_template')->nullable();
            $table->longText('description_template')->nullable();
            $table->longText('terms_conditions')->nullable();
            $table->json('default_items')->nullable(); // Default contract items/bonds
            $table->json('workflow_assignments')->nullable(); // Default role assignments
            $table->enum('status', ['draft', 'active', 'archived'])->default('draft');
            $table->unsignedBigInteger('workspace_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->boolean('is_default')->default(false);
            $table->string('version')->default('1.0');
            $table->text('preview_image')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['workspace_id', 'status']);
            $table->index(['is_default']);
            $table->index(['created_by']);
            
            // Foreign keys
            $table->foreign('workflow_template_id')->references('id')->on('workflow_templates')->onDelete('set null');
            $table->foreign('pdf_template_id')->references('id')->on('templates')->onDelete('set null');
            $table->foreign('notification_template_id')->references('id')->on('templates')->onDelete('set null');
            $table->foreign('workspace_id')->references('id')->on('workspaces')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_templates');
    }
};
