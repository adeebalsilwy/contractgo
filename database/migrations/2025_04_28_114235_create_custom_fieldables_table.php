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
        if (!Schema::hasTable('custom_fieldables')) {
            Schema::create('custom_fieldables', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('custom_field_id');
                $table->unsignedBigInteger('custom_fieldable_id');
                $table->string('custom_fieldable_type');
                $table->text('value')->nullable();
                $table->timestamps();

                // Add shorter index name to avoid identifier name too long error
                $table->index(['custom_fieldable_id', 'custom_fieldable_type'], 'cfbl_fieldable_id_type_index');
            });
            
            // Add foreign key after table creation to avoid constraint issues
            if (Schema::hasTable('custom_fields')) {
                Schema::table('custom_fieldables', function (Blueprint $table) {
                    $table->foreign('custom_field_id')
                        ->references('id')
                        ->on('custom_fields')
                        ->onDelete('cascade');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_fieldables');
    }
};