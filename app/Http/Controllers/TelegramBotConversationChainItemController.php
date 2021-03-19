<?php

namespace App\Http\Controllers;

use App\Models\TelegramBotConversationChainItem;
use App\Models\TelegramBotConversationChain;
use App\Models\TelegramBot;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class TelegramBotConversationChainItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($bot_id, $chain_id)
    {
        $template_data = AdminController::getTemplateData();
        $template_data['chain_items'] = TelegramBotConversationChainItem::where('chain_id', $chain_id)->get();
        $template_data['chain'] = TelegramBotConversationChain::find($chain_id);
        $template_data['bot'] = TelegramBot::find($bot_id);
        return view('admin.telegram_bot.conversation_chain.chain_item.list', $template_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($bot_id, $chain_id)
    {
        $template_data = AdminController::getTemplateData();
        $template_data['bot'] = TelegramBot::find($bot_id);
        $template_data['chain'] = TelegramBotConversationChain::find($chain_id);
        return view('admin.telegram_bot.conversation_chain.chain_item.edit', $template_data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $valid = $request->validate(
            [
                'chain_id' => 'required|integer',
                'caption' => 'nullable|string',
                'message' => 'nullable|string',
                'check_function' => 'nullable|string',
                'true_function' => 'nullable|string',
                'chain_item' => 'nullable|string',
            ]
        );
        if (!$valid) return back()->withInput();

        $record = new TelegramBotConversationChainItem();
        $fields = Schema::getColumnListing($record->getTable());

        foreach ($fields as $field) {
            $record->$field = $request->input($field);
        }
        $record->save();
        return redirect(route('admin.telegram_bot.conversation_chain.chain_item.show', [$record->chain->bot, $record->chain, $record]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TelegramBotConversationChainItem  $telegramBotConversationChainItem
     * @return \Illuminate\Http\Response
     */
    public function show($bot_id, $chain_id, TelegramBotConversationChainItem $chain_item)
    {
        $template_data = AdminController::getTemplateData();
        $template_data['bot'] = $chain_item->chain->bot;
        $template_data['chain'] = $chain_item->chain;
        $template_data['chain_item'] = $chain_item;
        return view('admin.telegram_bot.conversation_chain.chain_item.edit ', $template_data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TelegramBotConversationChainItem  $telegramBotConversationChainItem
     * @return \Illuminate\Http\Response
     */
    public function edit($bot_id, $chain_id, TelegramBotConversationChainItem $chain_item)
    {
        $template_data = AdminController::getTemplateData();
        $template_data['bot'] = $chain_item->chain->bot;
        $template_data['chain'] = $chain_item->chain;
        $template_data['chain_item'] = $chain_item;
        return view('admin.telegram_bot.conversation_chain.chain_item.edit ', $template_data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TelegramBotConversationChainItem  $telegramBotConversationChainItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $bot_id, $chain_id, TelegramBotConversationChainItem $chain_item)
    {
        $valid = $request->validate(
            [
                'id'=>'required',
                'chain_id' => 'required|integer',
                'caption' => 'nullable|string',
                'message' => 'nullable|string',
                'check_function' => 'nullable|string',
                'true_function' => 'nullable|string',
                'chain_item' => 'nullable|string',
            ]
        );
        if (!$valid) return back()->withInput();

        $record = $chain_item;
        $fields = Schema::getColumnListing($record->getTable());

        foreach ($fields as $field) {
            $record->$field = $request->input($field);
        }
        $record->save();
        return redirect(route('admin.telegram_bot.conversation_chain.chain_item.show', [$bot_id, $chain_id, $record]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TelegramBotConversationChainItem  $telegramBotConversationChainItem
     * @return \Illuminate\Http\Response
     */
    public function destroy($bot_id, $chain_id, TelegramBotConversationChainItem $chain_item)
    {
        $chain_item->delete();
        return redirect(route('admin.telegram_bot.command.conversation_chain.show', [$bot_id, $chain_id]));
    }
}
