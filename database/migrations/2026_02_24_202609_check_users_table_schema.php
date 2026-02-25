<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $columns = Schema::getColumnListing('users');
        echo "Columns in users table:\n";
        foreach ($columns as $column) {
            echo "- $column\n";
        }
        
        // Check if status column exists
        if (in_array('status', $columns)) {
            echo "\nStatus column EXISTS in users table\n";
        } else {
            echo "\nStatus column DOES NOT EXIST in users table\n";
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};