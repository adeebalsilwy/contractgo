<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add the default_workspace_id column if it doesn't exist
            if (!Schema::hasColumn('users', 'default_workspace_id')) {
                $table->unsignedBigInteger('default_workspace_id')->nullable();
                $table->foreign('default_workspace_id')->references('id')->on('workspaces')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['default_workspace_id']);
            $table->dropColumn('default_workspace_id');
        });
    }
};