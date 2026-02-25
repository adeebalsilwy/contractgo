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
            // Add phone column if it doesn't exist
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable();
            }
            
            // Add country_code column if it doesn't exist
            if (!Schema::hasColumn('users', 'country_code')) {
                $table->string('country_code', 10)->nullable();
            }
            
            // Add country_iso_code column if it doesn't exist
            if (!Schema::hasColumn('users', 'country_iso_code')) {
                $table->string('country_iso_code', 10)->nullable();
            }
            
            // Add address column if it doesn't exist
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable();
            }
            
            // Add city column if it doesn't exist
            if (!Schema::hasColumn('users', 'city')) {
                $table->string('city')->nullable();
            }
            
            // Add state column if it doesn't exist
            if (!Schema::hasColumn('users', 'state')) {
                $table->string('state')->nullable();
            }
            
            // Add country column if it doesn't exist
            if (!Schema::hasColumn('users', 'country')) {
                $table->string('country')->nullable();
            }
            
            // Add zip column if it doesn't exist
            if (!Schema::hasColumn('users', 'zip')) {
                $table->string('zip')->nullable();
            }
            
            // Add photo column if it doesn't exist
            if (!Schema::hasColumn('users', 'photo')) {
                $table->string('photo')->nullable();
            }
            
            // Add dob (date of birth) column if it doesn't exist
            if (!Schema::hasColumn('users', 'dob')) {
                $table->date('dob')->nullable();
            }
            
            // Add doj (date of joining) column if it doesn't exist
            if (!Schema::hasColumn('users', 'doj')) {
                $table->date('doj')->nullable();
            }
            
            // Add fcm_token column if it doesn't exist
            if (!Schema::hasColumn('users', 'fcm_token')) {
                $table->text('fcm_token')->nullable();
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
            $table->dropColumn([
                'phone',
                'country_code',
                'country_iso_code',
                'address',
                'city',
                'state',
                'country',
                'zip',
                'photo',
                'dob',
                'doj',
                'fcm_token'
            ]);
        });
    }
};