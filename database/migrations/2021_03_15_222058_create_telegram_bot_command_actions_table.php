<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramBotCommandActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_bot_command_actions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('telegram_bot_command_id')->references('id')->on('telegram_bot_commands')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->string('action');
            $table->string('action_param_1')->nullable();
            $table->string('action_param_2')->nullable();
            $table->string('action_param_3')->nullable();
            $table->string('action_param_4')->nullable();
            $table->string('action_param_5')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_bot_command_actions');
    }
}
