<?php

namespace App\Models;

use App\Http\Controllers\TelegramBotConversationChainController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramBotConversation extends Model
{
    use HasFactory;

    public $fillable=['bot_id','chat_id'];

    protected $attributes = [
        'chain_item_id' => null,
    ];
    public function bot()
    {
        return $this->belongsTo(TelegramBot::class, 'id', 'bot_id');
    }

    public function chainItem()
    {
        return $this->hasOne(TelegramBotConversationChainItem::class, 'id','chain_item_id');
    }

    public function chain()
    {
        //$item = TelegramBotConversationChainItem::find($this->chain_item_id);
        //return TelegramBotConversationChain::where('id',$item->chain_id);
        return $this->hasOneThrough(
            TelegramBotConversationChain::class, 
            TelegramBotConversationChainItem::class, 'id',
            'id','chain_item_id','chain_id');
    }
    public function nextChainItem()
    {
        //$chain=TelegramBotConversationChain::find($this->chain_item_id);
        return $this->chain->items()->where('telegram_bot_conversation_chain_items.id','>',$this->chain_item_id)->first();
        //return TelegramBotConversationChainItem::where('conversation_id')
    }
}
