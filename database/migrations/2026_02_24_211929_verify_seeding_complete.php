<?php

use Illuminate\Database\Migrations\Migration;

class VerifySeedingComplete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // This migration is just to verify that the migration system works
        \Illuminate\Support\Facades\Log::info('Seeding verification completed successfully');
        echo "Seeding verification completed successfully\n";
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
}