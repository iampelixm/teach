<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramBotConversation extends Model
{
    use HasFactory;

    public function bot()
    {
        return $this->belongsTo(TelegramBot::class, 'id', 'bot_id');
    }
}
