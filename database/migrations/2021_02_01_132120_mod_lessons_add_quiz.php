<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModLessonsAddQuiz extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('module_lessons', function (Blueprint $table) {
            $table->json('lesson_quiz')->nullable();
            $table->renameColumn('task', 'lesson_task');
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
            $table->renameColumn('lesson_task', 'task');
            $table->dropColumn('lesson_quiz');
        });
    }
}
