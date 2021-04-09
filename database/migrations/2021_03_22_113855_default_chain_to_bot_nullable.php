<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DefaultChainToBotNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('telegram_bots', function (Blueprint $table) {
            // $table->dropColumn('default_chain');
            $table->foreignId('default_chain')->nullable()->references('id')->on('telegram_bot_conversation_chains');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('telegram_bots', function (Blueprint $table) {
            //not needed
        });
    }
}
