<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramBot extends Model
{
    use HasFactory;

    public function chains()
    {
        return $this->hasMany(TelegramBotConversationChain::class, 'bot_id', 'id');
    }

    public function conversations()
    {
        return $this->hasMany(TelegramBotConversation::class, 'bot_id','id');
    }
}
