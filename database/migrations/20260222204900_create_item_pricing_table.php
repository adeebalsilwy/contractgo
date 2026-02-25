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
        if (!Schema::hasTable('item_pricing')) {
            Schema::create('item_pricing', function (Blueprint $table) {
                $table->id();
                $table->foreignId('item_id')->constrained()->onDelete('cascade');
                $table->foreignId('unit_id')->constrained()->onDelete('cascade');
                $table->decimal('price', 15, 2);
                $table->text('description')->nullable();
                $table->timestamps();
                
                // Ensure unique combination of item and unit
                $table->unique(['item_id', 'unit_id'], 'item_unit_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_pricing');
    }
};