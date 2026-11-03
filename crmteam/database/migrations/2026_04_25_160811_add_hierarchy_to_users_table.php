<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHierarchyToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('tl_1')->nullable();
            $table->unsignedBigInteger('tl_2')->nullable();
            $table->unsignedBigInteger('tl_3')->nullable();
            $table->unsignedBigInteger('tl_4')->nullable();
            $table->unsignedBigInteger('tl_5')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tl_1', 'tl_2', 'tl_3', 'tl_4', 'tl_5']);
        });
    }
}
