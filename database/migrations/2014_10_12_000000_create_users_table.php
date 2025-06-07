<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->onDelete('restrict');
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('user_name', 100)->unique();
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('dni', 10)->unique();
            $table->date('birth_date')->nullable();
            $table->string('photo_url')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('address')->nullable();
            $table->timestamp('last_sign_in')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
