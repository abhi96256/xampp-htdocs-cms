<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            if (!Schema::hasColumn('clients', 'lead_id')) {
                $table->unsignedBigInteger('lead_id')->nullable()->after('remark');
            }
            if (!Schema::hasColumn('clients', 'floor_no')) {
                $table->string('floor_no')->nullable()->after('lead_id');
            }
            if (!Schema::hasColumn('clients', 'unit_no')) {
                $table->string('unit_no')->nullable()->after('floor_no');
            }
            if (!Schema::hasColumn('clients', 'tower_no')) {
                $table->string('tower_no')->nullable()->after('unit_no');
            }
            if (!Schema::hasColumn('clients', 'actual_amount')) {
                $table->decimal('actual_amount', 20, 2)->nullable()->after('tower_no');
            }
            if (!Schema::hasColumn('clients', 'cheque_no')) {
                $table->string('cheque_no')->nullable()->after('actual_amount');
            }
            if (!Schema::hasColumn('clients', 'payment_plan')) {
                $table->string('payment_plan')->nullable()->after('cheque_no');
            }
            if (!Schema::hasColumn('clients', 'closed_by_name')) {
                $table->string('closed_by_name')->nullable()->after('closed_by');
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
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'lead_id', 
                'floor_no', 
                'unit_no', 
                'tower_no', 
                'actual_amount', 
                'cheque_no', 
                'payment_plan', 
                'closed_by_name'
            ]);
        });
    }
}
