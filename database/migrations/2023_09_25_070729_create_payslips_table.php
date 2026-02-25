<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('payslips')) {
            Schema::create('payslips', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('workspace_id');
                $table->unsignedBigInteger('payment_method_id')->nullable(); // Make nullable initially
                $table->date('month');
                $table->double('working_days');
                $table->double('lop_days');
                $table->double('paid_days');
                $table->double('basic_salary');
                $table->double('leave_deduction');
                $table->double('ot_hours');
                $table->double('ot_rate');
                $table->double('ot_payment');
                $table->double('total_allowance');
                $table->double('incentives');
                $table->double('bonus');
                $table->double('total_earnings');
                $table->double('total_deductions');
                $table->double('net_pay');
                $table->timestamp('payment_date')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
                $table->tinyInteger('status');
                $table->string('created_by', 56);
                $table->timestamps();
            });

            // Add foreign keys after table creation to avoid constraint issues
            Schema::table('payslips', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('workspace_id')->references('id')->on('workspaces')->onDelete('cascade');
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
        Schema::dropIfExists('payslips');
    }
};