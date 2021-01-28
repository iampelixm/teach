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
        Schema::dropIfExists('course_module_users');
        Schema::create('course_module_users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('module_id')
                ->references('module_id')
                ->on('course_modules')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->references('id')
                ->on('users')
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
