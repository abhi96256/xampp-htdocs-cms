<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDprsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dprs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('date');
            $table->string('around_the_clock'); // MORNING, AFTERNOON, EVENNING, LATE EVENNING
            
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            
            // Flier
            $table->integer('flier_distributed')->nullable();
            $table->string('location')->nullable();
            
            // Calling
            $table->integer('no_of_person_called')->nullable();
            $table->integer('success_call')->nullable();
            
            // Social Media
            $table->integer('no_blog_update_upload')->nullable();
            $table->integer('developer_site_update_upload')->nullable();
            $table->integer('update_upload_facebook')->nullable();
            $table->integer('update_upload_instagram')->nullable();
            $table->integer('update_upload_linkedin')->nullable();
            $table->integer('nos_of_lead_generated_location')->nullable();
            
            // Visit
            $table->integer('nos_of_developer')->nullable();
            $table->string('developer_name')->nullable();
            
            $table->timestamps();
            
            // Prevent duplicate entries for same user, date and shift
            $table->unique(['user_id', 'date', 'around_the_clock']);
            
            // Indexing for faster searching
            $table->index(['date', 'around_the_clock']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dprs');
    }
}
