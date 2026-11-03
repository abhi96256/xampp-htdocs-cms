<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SyncDatabaseStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Add missing columns to clients
        Schema::table('clients', function (Blueprint $table) {
            if (!Schema::hasColumn('clients', 'lead_id')) $table->unsignedBigInteger('lead_id')->nullable();
            if (!Schema::hasColumn('clients', 'floor_no')) $table->string('floor_no')->nullable();
            if (!Schema::hasColumn('clients', 'unit_no')) $table->string('unit_no')->nullable();
            if (!Schema::hasColumn('clients', 'tower_no')) $table->string('tower_no')->nullable();
            if (!Schema::hasColumn('clients', 'actual_amount')) $table->decimal('actual_amount', 20, 2)->nullable();
            if (!Schema::hasColumn('clients', 'cheque_no')) $table->string('cheque_no')->nullable();
            if (!Schema::hasColumn('clients', 'payment_plan')) $table->string('payment_plan')->nullable();
            if (!Schema::hasColumn('clients', 'closed_by_name')) $table->string('closed_by_name')->nullable();
            if (!Schema::hasColumn('clients', 'attachment')) $table->string('attachment')->nullable();
        });

        // 2. Add missing columns to enquiries
        Schema::table('enquiries', function (Blueprint $table) {
            if (!Schema::hasColumn('enquiries', 'a_contact_no')) $table->string('a_contact_no')->nullable();
            if (!Schema::hasColumn('enquiries', 'address')) $table->string('address', 2048)->nullable();
            if (!Schema::hasColumn('enquiries', 'budget_range')) $table->string('budget_range')->nullable();
            if (!Schema::hasColumn('enquiries', 'other_config')) $table->string('other_config')->nullable();
        });

        // 3. Create missing tables
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->text('notification_text');
                $table->integer('status')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('activities')) {
            Schema::create('activities', function (Blueprint $table) {
                $table->id();
                $table->string('user');
                $table->text('activity_text');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('leads_transfer')) {
            Schema::create('leads_transfer', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('lead_id');
                $table->string('previous_user')->nullable();
                $table->string('new_user')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('remarks')) {
            Schema::create('remarks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('lead_id');
                $table->text('remarks');
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('incentive')) {
            Schema::create('incentive', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('lead_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('client_id')->nullable();
                $table->decimal('incentive_percentage', 10, 2)->nullable();
                $table->decimal('incentive_amount', 20, 2)->nullable();
                $table->text('remarks')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('user_codes')) {
            Schema::create('user_codes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('code');
                $table->string('admin_code')->nullable();
                $table->timestamps();
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
        // No down migration to avoid data loss on production
    }
}
