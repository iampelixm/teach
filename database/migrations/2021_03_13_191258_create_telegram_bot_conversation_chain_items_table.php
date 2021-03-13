<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramBotConversationChainItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_bot_conversation_chain_items', function (Blueprint $table) {
            Schema::dropIfExists('telegram_bot_conversation_chain_items');
            $table->id();
            $table->timestamps();
            $table->foreignId('chain_id')->references('id')->on('telegram_bot_conversation_chains')->onDelete('cascade')->onUpdate('cascade');
            $table->string('caption');
            $table->text('message');
            $table->string('check_function')->nullable(true);
            $table->string('true_function')->nullable(true);
            $table->string('false_function')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_bot_conversation_chain_items');
    }
}
