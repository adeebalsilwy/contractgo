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
        if (!Schema::hasTable('role_status')) {
            Schema::create('role_status', function (Blueprint $table) {
                $table->unsignedBigInteger('role_id');
                $table->unsignedBigInteger('status_id');
                $table->primary(['role_id', 'status_id']);
            });
            
            // Add foreign keys after table creation to avoid constraint issues
            Schema::table('role_status', function (Blueprint $table) {
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            });
            
            // Add the status_id foreign key separately to handle constraint issues
            if (Schema::hasTable('statuses')) {
                Schema::table('role_status', function (Blueprint $table) {
                    $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_status');
    }
};