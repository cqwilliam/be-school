<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_user_id')->constrained('users');
            $table->foreignId('student_user_id')->constrained('users');
            $table->foreignId('class_session_id')->constrained('class_sessions');
            $table->string('status');
            $table->text('justification')->nullable();
            $table->timestamps();
            
            $table->unique(['class_session_id', 'student_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
