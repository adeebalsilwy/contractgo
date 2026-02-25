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
        Schema::table('clients', function (Blueprint $table) {
            // Add missing fields based on SQL dump
            
            // Add country_code field if it doesn't exist
            if (!Schema::hasColumn('clients', 'country_code')) {
                $table->string('country_code', 28)->nullable()->after('email');
            }
            
            // Add country_iso_code field if it doesn't exist
            if (!Schema::hasColumn('clients', 'country_iso_code')) {
                $table->string('country_iso_code', 28)->nullable()->after('country_code');
            }
            
            // Update phone field to match SQL dump
            if (Schema::hasColumn('clients', 'phone')) {
                $table->string('phone')->nullable()->change();
            } else {
                $table->string('phone')->nullable()->after('country_iso_code');
            }
            
            // Add password field if it doesn't exist (may already exist)
            if (!Schema::hasColumn('clients', 'password')) {
                $table->string('password')->nullable()->after('phone');
            }
            
            // Add dob field if it doesn't exist
            if (!Schema::hasColumn('clients', 'dob')) {
                $table->date('dob')->nullable()->after('password');
            }
            
            // Add doj field if it doesn't exist
            if (!Schema::hasColumn('clients', 'doj')) {
                $table->date('doj')->nullable()->after('dob');
            }
            
            // Update address field to match SQL dump
            if (Schema::hasColumn('clients', 'address')) {
                $table->string('address')->nullable()->change();
            }
            
            // Update city field to match SQL dump
            if (Schema::hasColumn('clients', 'city')) {
                $table->string('city')->nullable()->change();
            }
            
            // Update state field to match SQL dump
            if (Schema::hasColumn('clients', 'state')) {
                $table->string('state')->nullable()->change();
            }
            
            // Update country field to match SQL dump
            if (Schema::hasColumn('clients', 'country')) {
                $table->string('country')->nullable()->change();
            }
            
            // Update zip field to match SQL dump
            if (Schema::hasColumn('clients', 'zip')) {
                $table->string('zip')->nullable()->change();
            }
            
            // Add photo field if it doesn't exist
            if (!Schema::hasColumn('clients', 'photo')) {
                $table->string('photo')->nullable()->after('zip');
            }
            
            // Add status field if it doesn't exist
            if (!Schema::hasColumn('clients', 'status')) {
                $table->tinyInteger('status')->default(0)->after('photo');
            }
            
            // Add lang field if it doesn't exist
            if (!Schema::hasColumn('clients', 'lang')) {
                $table->string('lang', 28)->default('en')->after('status');
            }
            
            // Add remember_token field if it doesn't exist
            if (!Schema::hasColumn('clients', 'remember_token')) {
                $table->text('remember_token')->nullable()->after('lang');
            }
            
            // Add email_verification_mail_sent field if it doesn't exist
            if (!Schema::hasColumn('clients', 'email_verification_mail_sent')) {
                $table->tinyInteger('email_verification_mail_sent')->nullable()->after('remember_token');
            }
            
            // Add email_verified_at field if it doesn't exist
            if (!Schema::hasColumn('clients', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('email_verification_mail_sent');
            }
            
            // Add acct_create_mail_sent field if it doesn't exist
            if (!Schema::hasColumn('clients', 'acct_create_mail_sent')) {
                $table->tinyInteger('acct_create_mail_sent')->default(1)->after('email_verified_at');
            }
            
            // Add internal_purpose field if it doesn't exist
            if (!Schema::hasColumn('clients', 'internal_purpose')) {
                $table->tinyInteger('internal_purpose')->default(0)->after('acct_create_mail_sent');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'country_code',
                'country_iso_code',
                'dob',
                'doj',
                'photo',
                'status',
                'lang',
                'remember_token',
                'email_verification_mail_sent',
                'email_verified_at',
                'acct_create_mail_sent',
                'internal_purpose'
            ]);
        });
    }
};