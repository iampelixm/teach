<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramBotCommandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_bot_commands', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('bot_id')->references('id')->on('telegram_bots')->onDelete('cascade')->onUpdate('cascade');
            $table->string('command');
            $table->string('caption');
            $table->text('presc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_bot_commands');
    }
}
