<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LesonUserAnswer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_user_answes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('answer');
            $table->foreignId('lesson_id')
                ->references('lessons')
                ->on('lesson_id')
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
        Schema::dropIfExists('lesson_user_answes');
    }
}