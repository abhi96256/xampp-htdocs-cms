<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraProjectsToEnquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->foreignId('project_id_1')->nullable()->after('project_id')->constrained('projects')->onDelete('restrict');
            $table->foreignId('project_id_2')->nullable()->after('project_id_1')->constrained('projects')->onDelete('restrict');
            $table->foreignId('project_id_3')->nullable()->after('project_id_2')->constrained('projects')->onDelete('restrict');
            $table->foreignId('project_id_4')->nullable()->after('project_id_3')->constrained('projects')->onDelete('restrict');
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
            $table->dropForeign(['project_id_1']);
            $table->dropForeign(['project_id_2']);
            $table->dropForeign(['project_id_3']);
            $table->dropForeign(['project_id_4']);
            $table->dropColumn(['project_id_1', 'project_id_2', 'project_id_3', 'project_id_4']);
        });
    }
}
