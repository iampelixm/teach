<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramBotConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_bot_conversations', function (Blueprint $table) {
            Schema::dropIfExists('telegram_bot_conversations');
            $table->id();
            $table->timestamps();
            $table->integer('chat_id');
            $table->foreignId('chain_item_id')->references('id')->on('telegram_bot_conversation_chain_items')
                ->nullable(true)->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_bot_conversations');
    }
}
