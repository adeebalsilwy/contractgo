<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('comments')) {
            Schema::create('comments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('commentable_type');
                $table->unsignedBigInteger('commentable_id');
                $table->longText('content');
                $table->timestamps();
                $table->softDeletes();

                $table->index(['commentable_type', 'commentable_id']);
                $table->index('parent_id');
                $table->index('user_id');
            });
            
            // Add foreign keys after table creation to avoid constraint issues
            Schema::table('comments', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            });
            
            // Add parent_id foreign key separately to handle constraint issues
            if (Schema::hasTable('comments')) {
                Schema::table('comments', function (Blueprint $table) {
                    $table->foreign('parent_id')->references('id')->on('comments')->onDelete('cascade');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}