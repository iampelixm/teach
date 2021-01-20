<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuleLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_lessons', function (Blueprint $table) {
            $table->id('lesson_id');
            $table->foreignId('module_id')->references('course_modules')->on('module_id');
            $table->string('lesson_caption');
            $table->string('lesson_presc');
            $table->text('lesson_text');
            $table->json('lesson_additional');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module_lessons');
    }
}
