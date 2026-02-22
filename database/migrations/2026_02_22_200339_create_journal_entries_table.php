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
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_id')->nullable(); // Link to contract if applicable
            $table->unsignedBigInteger('invoice_id')->nullable(); // Link to invoice if applicable
            $table->string('entry_number'); // Journal entry number from Onyx Pro
            $table->string('entry_type')->default('journal'); // Type: journal, invoice, payment, etc.
            $table->date('entry_date'); // Date of the journal entry
            $table->string('reference_number')->nullable(); // Reference number
            $table->text('description')->nullable(); // Description of the entry
            $table->decimal('debit_amount', 15, 2)->default(0); // Debit amount
            $table->decimal('credit_amount', 15, 2)->default(0); // Credit amount
            $table->string('account_code'); // Account code from chart of accounts
            $table->string('account_name'); // Account name
            $table->unsignedBigInteger('created_by'); // User who created the entry
            $table->enum('status', ['pending', 'posted', 'reversed', 'cancelled'])->default('pending');
            $table->timestamp('posted_at')->nullable(); // When it was posted to accounting system
            $table->unsignedBigInteger('posted_by')->nullable(); // Who posted it
            $table->text('posting_notes')->nullable(); // Notes about posting
            $table->text('integration_data')->nullable(); // Additional data for integration (JSON)
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('set null');
            $table->foreign('invoice_id')->references('id')->on('estimates_invoices')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('posted_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_entries');
    }
};