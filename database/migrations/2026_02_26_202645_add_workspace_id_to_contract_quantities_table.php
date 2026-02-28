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
        Schema::table('contract_quantities', function (Blueprint $table) {
            $table->unsignedBigInteger('workspace_id')->nullable()->after('id');
            $table->foreign('workspace_id')->references('id')->on('workspaces')->onDelete('cascade');
        });
        
        // تحديث البيانات الموجودة لإضافة workspace_id
        DB::table('contract_quantities')->update(['workspace_id' => 1]);
        
        // جعل العمود مطلوبًا بعد تحديث البيانات
        Schema::table('contract_quantities', function (Blueprint $table) {
            $table->unsignedBigInteger('workspace_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contract_quantities', function (Blueprint $table) {
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });
    }
};
