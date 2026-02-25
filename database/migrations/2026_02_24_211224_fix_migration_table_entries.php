<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FixMigrationTableEntries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Delete the orphaned migration entry for 'create_comments_table' without timestamp
        DB::table('migrations')->where('migration', 'create_comments_table')->delete();
        
        // Insert the correct migration entry if it doesn't exist
        $correctMigration = '2024_08_09_100000_create_comments_table';
        $count = DB::table('migrations')->where('migration', $correctMigration)->count();
        
        if ($count == 0) {
            DB::table('migrations')->insert([
                'migration' => $correctMigration,
                'batch' => 1, // Assuming it was part of the first batch
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove the correct migration entry and restore the orphaned one
        $correctMigration = '2024_08_09_100000_create_comments_table';
        DB::table('migrations')->where('migration', $correctMigration)->delete();
        
        DB::table('migrations')->insert([
            'migration' => 'create_comments_table',
            'batch' => 1,
        ]);

    }
}