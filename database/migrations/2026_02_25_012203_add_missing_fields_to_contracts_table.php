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
        Schema::table('contracts', function (Blueprint $table) {
            // Add missing fields based on SQL dump
            
            // Change value column to varchar if it's still decimal
            $table->string('value')->change();
            
            // Add promisor_sign field if it doesn't exist
            if (!Schema::hasColumn('contracts', 'promisor_sign')) {
                $table->text('promisor_sign')->nullable()->after('end_date');
            }
            
            // Add promisee_sign field if it doesn't exist
            if (!Schema::hasColumn('contracts', 'promisee_sign')) {
                $table->text('promisee_sign')->nullable()->after('promisor_sign');
            }
            
            // Add signed_pdf field if it doesn't exist
            if (!Schema::hasColumn('contracts', 'signed_pdf')) {
                $table->text('signed_pdf')->nullable()->after('promisee_sign');
            }
            
            // Add created_by field if it doesn't exist
            if (!Schema::hasColumn('contracts', 'created_by')) {
                $table->string('created_by', 56)->after('signed_pdf');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn([
                'promisor_sign',
                'promisee_sign',
                'signed_pdf',
                'created_by'
            ]);
            
            // Revert value column back to decimal
            $table->decimal('value', 15, 2)->change();
        });
    }
};