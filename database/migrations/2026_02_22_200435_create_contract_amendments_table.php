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
        Schema::create('contract_amendments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->onDelete('cascade');
            $table->foreignId('requested_by_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->string('amendment_type'); // price, quantity, specification, etc.
            $table->text('request_reason');
            $table->text('details')->nullable();
            
            // Original vs new values
            $table->decimal('original_price', 15, 2)->nullable();
            $table->decimal('new_price', 15, 2)->nullable();
            $table->decimal('original_quantity', 10, 2)->nullable();
            $table->decimal('new_quantity', 10, 2)->nullable();
            $table->string('original_unit')->nullable();
            $table->string('new_unit')->nullable();
            $table->text('original_description')->nullable();
            $table->text('new_description')->nullable();
            
            // Approval status
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('approval_comments')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            
            // Digital signature
            $table->string('digital_signature_path')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->foreignId('signed_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Audit trail
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_amendments');
    }
};