<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramBotCommand extends Model
{
    use HasFactory;

    public function bot()
    {
        return $this->belongsTo(TelegramBot::class, 'bot_id', 'id');
    }

    public function actions()
    {
        return $this->hasMany(TelegramBotCommandAction::class, 'telegram_bot_command_id', 'id');
    }
}
