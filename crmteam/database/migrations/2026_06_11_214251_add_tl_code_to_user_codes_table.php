<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTlCodeToUserCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_codes', function (Blueprint $table) {
            $table->string('tl_code')->nullable()->after('admin_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_codes', function (Blueprint $table) {
            $table->dropColumn('tl_code');
        });
    }
}
