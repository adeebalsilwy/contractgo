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
        Schema::create('contract_price_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('workspace_id')->constrained()->onDelete('cascade');
            $table->string('item_description');
            $table->decimal('old_unit_price', 15, 2)->nullable();
            $table->decimal('new_unit_price', 15, 2);
            $table->decimal('quantity', 15, 2);
            $table->decimal('old_total_amount', 15, 2)->nullable();
            $table->decimal('new_total_amount', 15, 2);
            $table->string('change_reason')->nullable();
            $table->string('change_type'); // 'creation', 'update', 'deletion'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_price_audits');
    }
};
