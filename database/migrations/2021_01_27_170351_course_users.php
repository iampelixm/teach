<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CourseUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('course_id')
                ->references('courses')
                ->on('course_id')
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
        Schema::dropIfExists('course_users');
    }
}