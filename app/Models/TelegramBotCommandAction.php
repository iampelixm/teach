<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramBotCommandAction extends Model
{
    use HasFactory;

    public function command()
    {
        return $this->belongsTo(TelegramBotCommand::class, 'telegram_bot_command_id', 'id');
    }
}
