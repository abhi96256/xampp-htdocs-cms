<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToEnquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enquiries', function (Blueprint $table) {
            if (!Schema::hasColumn('enquiries', 'a_contact_no')) {
                $table->string('a_contact_no')->nullable()->after('contact_no');
            }
            if (!Schema::hasColumn('enquiries', 'address')) {
                $table->string('address', 2048)->nullable()->after('subject');
            }
            if (!Schema::hasColumn('enquiries', 'budget_range')) {
                $table->string('budget_range')->nullable()->after('address');
            }
            if (!Schema::hasColumn('enquiries', 'other_config')) {
                $table->string('other_config')->nullable()->after('budget_range');
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
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropColumn(['a_contact_no', 'address', 'budget_range', 'other_config']);
        });
    }
}
