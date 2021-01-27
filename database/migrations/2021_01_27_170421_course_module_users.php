<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CourseModuleUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_module_users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('module_id')
                ->references('course_modules')
                ->on('module_id')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->references('users')
                ->on('id')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_module_users');
    }
}
