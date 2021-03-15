<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFunctionParamsToChainItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('telegram_bot_conversation_chain_items', function (Blueprint $table) {
            $table->string('check_function_param')->nullable(true);
            $table->string('true_function_param')->nullable(true);
            $table->string('false_function_param')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('telegram_bot_conversation_chain_items', function (Blueprint $table) {
            $table->dropColumn('check_function_param');
            $table->dropColumn('true_function_param');
            $table->dropColumn('false_function_param');
        });
    }
}
