<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsGuardiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students_guardians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('guardian_user_id')->constrained('users')->onDelete('cascade');
            $table->string('relationship', 100)->nullable(); // Father, Mother, Legal Guardian
            $table->timestamps();

            $table->unique(['student_user_id', 'guardian_user_id']); // Asegura que cada estudiante tenga un solo apoderado
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students_guardians');
    }
}
