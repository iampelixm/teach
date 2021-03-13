<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramBotConversationChainItem extends Model
{
    use HasFactory;

    public function chain()
    {
        return $this->belongsTo(TelegramBotConversationChain::class, 'chain_id','id');
    }
}
