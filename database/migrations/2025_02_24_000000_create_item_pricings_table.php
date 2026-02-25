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
        if (!Schema::hasTable('item_pricings')) {
            Schema::create('item_pricings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('item_id')->constrained()->onDelete('cascade');
                $table->foreignId('unit_id')->nullable()->constrained()->nullOnDelete();
                $table->decimal('price', 15, 2);
                $table->decimal('cost_price', 15, 2)->nullable();
                $table->decimal('min_selling_price', 15, 2)->nullable();
                $table->decimal('max_selling_price', 15, 2)->nullable();
                $table->json('pricing_tiers')->nullable(); // For bulk pricing tiers
                $table->json('discounts')->nullable(); // Available discounts
                $table->json('taxes')->nullable(); // Associated taxes
                $table->boolean('is_active')->default(true);
                $table->timestamp('valid_from')->nullable();
                $table->timestamp('valid_until')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                
                $table->index(['item_id', 'is_active']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_pricings');
    }
};