<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('office_payments', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->string('financial_year')->nullable();
            $table->string('location')->nullable();
            $table->decimal('cash_amount', 15, 2)->nullable();
            $table->string('cheque_nos')->nullable();
            $table->date('cheque_date')->nullable();
            $table->decimal('cheque_amount', 15, 2)->nullable();
            $table->decimal('online_amount', 15, 2)->nullable();
            $table->string('utr_nos')->nullable();
            $table->string('topology')->nullable();
            $table->string('in_favour_of')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('office_payments');
    }
}
