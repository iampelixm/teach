<?php

namespace App\Http\Controllers;

use App\Models\TelegramBotConversationChain;
use App\Models\TelegramBot;
use Illuminate\Http\Request;

use App\Models\TelegramBotConversation;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Schema;

class TelegramBotConversationChainController extends Controller
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
    public function index($bot_id)
    {
        $template_data = AdminController::getTemplateData();
        $template_data['chains'] = TelegramBotConversationChain::where('bot_id', $bot_id)->get();
        $template_data['bot_id'] = $bot_id;
        $template_data['bot'] = TelegramBot::find($bot_id);
        return view('admin.telegram_bot.conversation_chain.list', $template_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($bot_id)
    {
        $template_data = AdminController::getTemplateData();
        $template_data['bot'] = TelegramBot::find($bot_id);
        return view('admin.telegram_bot.conversation_chain.edit', $template_data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $bot_id)
    {
        $valid = $request->validate(
            [
                'caption' => 'required|string',
                'start_message' => 'nullable|string',
                'bot_id' => 'required'
            ]
        );
        if (!$valid) return back()->withInput();

        $record = new TelegramBotConversationChain();
        $fields = Schema::getColumnListing($record->getTable());

        foreach ($fields as $field) {
            $record->$field = $request->input($field);
        }
        $record->save();
        return redirect(route('admin.telegram_bot.conversation_chain.show', [$record->bot, $record]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TelegramBotConversationChain  $telegramBotConversationChain
     * @return \Illuminate\Http\Response
     */
    public function show($bot_id, TelegramBotConversationChain $conversation_chain)
    {
        $template_data = AdminController::getTemplateData();
        $template_data['bot'] = TelegramBot::find($bot_id);
        if (!$template_data['bot']) abort(404);
        $template_data['chain'] = $conversation_chain;
        return view('admin.telegram_bot.conversation_chain.page', $template_data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TelegramBotConversationChain  $telegramBotConversationChain
     * @return \Illuminate\Http\Response
     */
    public function edit($bot_id, TelegramBotConversationChain $conversation_chain)
    {
        echo $bot_id ?? '';
        dd($conversation_chain);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TelegramBotConversationChain  $telegramBotConversationChain
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TelegramBotConversationChain $conversation_chain)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TelegramBotConversationChain  $telegramBotConversationChain
     * @return \Illuminate\Http\Response
     */
    public function destroy(TelegramBotConversationChain $conversation_chain)
    {
        //
    }
}
