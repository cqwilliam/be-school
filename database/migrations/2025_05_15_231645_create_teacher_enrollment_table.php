<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherEnrollmentTable extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_enrollment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->foreignId('section_period_id')->constrained('section_periods')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['teacher_id', 'section_period_id']);
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_enrollment');
    }
}
