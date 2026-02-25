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
        Schema::table('users', function (Blueprint $table) {
            // Add remember_token field if it doesn't exist
            if (!Schema::hasColumn('users', 'remember_token')) {
                $table->text('remember_token')->nullable()->after('password');
            }
            
            // Add email_verification_mail_sent field if it doesn't exist
            if (!Schema::hasColumn('users', 'email_verification_mail_sent')) {
                $table->tinyInteger('email_verification_mail_sent')->nullable()->after('email_verified_at');
            }
            
            // Add acct_create_mail_sent field if it doesn't exist
            if (!Schema::hasColumn('users', 'acct_create_mail_sent')) {
                $table->tinyInteger('acct_create_mail_sent')->default(1)->after('email_verification_mail_sent');
            }
            
            // Add internal_purpose field if it doesn't exist
            if (!Schema::hasColumn('users', 'internal_purpose')) {
                $table->tinyInteger('internal_purpose')->default(0)->after('acct_create_mail_sent');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'remember_token',
                'email_verification_mail_sent',
                'acct_create_mail_sent',
                'internal_purpose'
            ]);
        });
    }
};