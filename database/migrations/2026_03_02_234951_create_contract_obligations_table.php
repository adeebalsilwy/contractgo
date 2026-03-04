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
        if (!Schema::hasTable('contract_obligations')) {
            Schema::create('contract_obligations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('contract_id');
                $table->unsignedBigInteger('party_id'); // Could be client, contractor, or other party
                $table->enum('party_type', ['client', 'contractor', 'consultant', 'supervisor', 'other']); // Type of party
                $table->string('title');
                $table->text('description')->nullable();
                $table->enum('obligation_type', ['payment', 'delivery', 'performance', 'compliance', 'reporting', 'other']);
                $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
                $table->enum('status', ['pending', 'in_progress', 'completed', 'overdue', 'cancelled'])->default('pending');
                $table->date('due_date')->nullable();
                $table->date('completed_date')->nullable();
                $table->unsignedBigInteger('assigned_to')->nullable(); // User responsible for tracking
                $table->text('notes')->nullable();
                $table->text('supporting_documents')->nullable(); // JSON array of document paths
                $table->enum('compliance_status', ['compliant', 'non_compliant', 'partially_compliant'])->default('non_compliant');
                $table->text('compliance_notes')->nullable();
                $table->unsignedBigInteger('compliance_checked_by')->nullable();
                $table->timestamp('compliance_checked_at')->nullable();
                $table->timestamps();

                // Foreign key constraints
                $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
                $table->foreign('party_id')->references('id')->on('users')->onDelete('cascade'); // Assuming parties are users
                $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
                $table->foreign('compliance_checked_by')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_obligations');
    }
};