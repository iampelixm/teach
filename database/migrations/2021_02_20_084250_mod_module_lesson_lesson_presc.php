<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModModuleLessonLessonPresc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('module_lessons', function (Blueprint $table) {
            $table->text('lesson_presc')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('module_lessons', function (Blueprint $table) {
            $table->string('lesson_presc')->change();
        });
    }
}
