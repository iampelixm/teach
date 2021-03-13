<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramBotConversationChainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_bot_conversation_chains', function (Blueprint $table) {
            Schema::dropIfExists('telegram_bot_conversation_chains');
            $table->id();
            $table->timestamps();
            $table->string('caption');
            $table->foreignId('bot_id')->references('id')->on('telegram_bots')->onDelete('cascade')->onUpdate('cascade');
            $table->text('start_message');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_bot_conversation_chains');
    }
}
