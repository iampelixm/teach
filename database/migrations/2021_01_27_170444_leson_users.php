<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LesonUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('lesson_users');
        Schema::create('lesson_users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('lesson_id')
                ->references('lesson_id')
                ->on('module_lessons')
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
        Schema::dropIfExists('lesson_users');
    }
}
