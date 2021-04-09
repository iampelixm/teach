<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LongConversationText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('telegram_bot_command_actions', function (Blueprint $table) {
            $table->longText('action_param_1')->change();
            $table->longText('action_param_2')->change();
            $table->longText('action_param_3')->change();
            $table->longText('action_param_4')->change();
            $table->longText('action_param_5')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('telegram_bot_command_actions', function (Blueprint $table) {
            //
        });
    }
}
