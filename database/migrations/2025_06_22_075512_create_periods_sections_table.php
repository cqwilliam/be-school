<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodsSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periods_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('sections');
            $table->foreignId('period_id')->constrained('periods');
            $table->timestamps();

            $table->unique(['section_id', 'period_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('periods_sections');
    }
}
