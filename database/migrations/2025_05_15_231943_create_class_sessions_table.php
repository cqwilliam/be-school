<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateClassSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('course_sections');
            $table->string('topic')->nullable();
            $table->date('date');
            $table->date('start_time');
            $table->date('end_time');
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users');
            
            $table->unique(['section_id', 'date', 'start_time']);            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_sessions');
    }
}
