<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramBotConversationChain extends Model
{
    use HasFactory;
    public function bot()
    {
        //return $this->belongsTo(TelegramBot::class,'bot_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(TelegramBotConversationChainItem::class, 'chain_id', 'id');
    }
}
