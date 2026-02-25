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
        if (!Schema::hasTable('comment_attachments')) {
            Schema::create('comment_attachments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('comment_id');
                $table->string('file_name');
                $table->string('file_path');
                $table->string('file_type'); // e.g., pdf, excel, image, etc.
                $table->timestamps();
            });
            
            // Add foreign key after table creation to avoid constraint issues
            if (Schema::hasTable('comments')) {
                Schema::table('comment_attachments', function (Blueprint $table) {
                    $table->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_attachments');
    }
};