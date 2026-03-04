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
        Schema::table('projects', function (Blueprint $table) {
            $table->text('description')->default('')->change();
            $table->text('note')->default('')->change();
            $table->string('client_can_discuss')->default('0')->change();
            $table->string('enable_tasks_time_entries')->default('0')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('description')->default(null)->change();
            $table->string('note')->default(null)->change();
            $table->string('client_can_discuss')->default(null)->change();
            $table->string('enable_tasks_time_entries')->default(null)->change();
        });
    }
};
