<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FixDuplicateMigrationEntry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Delete the orphaned migration entry for 'create_comments_table'
        DB::table('migrations')->where('migration', 'create_comments_table')->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Re-add the migration entry if needed
        DB::table('migrations')->insert([
            'migration' => 'create_comments_table',
            'batch' => 1,
            'created_at' => now(),
        ]);
    }
}