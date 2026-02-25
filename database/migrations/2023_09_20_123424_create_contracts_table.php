<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('contracts')) {
            Schema::create('contracts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('workspace_id');
                $table->unsignedBigInteger('project_id');
                $table->unsignedBigInteger('client_id');
                $table->string('title'); // Add title column
                $table->decimal('value', 10, 2); // Add value column as decimal
                $table->unsignedBigInteger('contract_type_id')->nullable(); // Add contract_type_id nullable initially
                $table->text('description');
                $table->date('start_date');
                $table->date('end_date');
                $table->timestamps();
            });
            
            // Add foreign keys after table creation to avoid constraint issues
            Schema::table('contracts', function (Blueprint $table) {
                $table->foreign('workspace_id')->references('id')->on('workspaces')->onDelete('cascade');
                $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
                $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('contracts');
    }
}
