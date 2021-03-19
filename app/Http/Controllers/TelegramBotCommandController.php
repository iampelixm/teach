<?php

namespace App\Http\Controllers;

use App\Models\TelegramBotCommand;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Schema;
use App\Models\TelegramBot;
use App\Models\TelegramBotCommandAction;

class TelegramBotCommandController extends Controller
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
        $template_data['commands'] = TelegramBotCommand::where('bot_id', $bot_id)->get();
        $template_data['bot_id'] = $bot_id;
        $template_data['bot'] = TelegramBot::find($bot_id);
        return view('admin.telegram_bot.command.list', $template_data);
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
        return view('admin.telegram_bot.command.edit', $template_data);
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
                'presc' => 'required|string',
                'bot_id' => 'required'
            ]
        );
        if (!$valid) return back()->withInput();

        $record = new TelegramBotCommand();
        $fields = Schema::getColumnListing($record->getTable());

        foreach ($fields as $field) {
            $record->$field = $request->input($field);
        }
        $record->save();
        return redirect(route('admin.telegram_bot.command.show', [$record->bot, $record]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TelegramBotCommand  $telegramBotCommand
     * @return \Illuminate\Http\Response
     */
    public function show($bot_id, TelegramBotCommand $command)
    {
        $template_data = AdminController::getTemplateData();
        $template_data['bot'] = TelegramBot::find($bot_id);
        if (!$template_data['bot']) abort(404);
        $template_data['command'] = $command;

        return view('admin.telegram_bot.command.page', $template_data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TelegramBotCommand  $telegramBotCommand
     * @return \Illuminate\Http\Response
     */
    public function edit($bot_id, TelegramBotCommand $command)
    {
        $template_data = AdminController::getTemplateData();
        $template_data['bot'] = TelegramBot::find($bot_id);
        if (!$template_data['bot']) abort(404);
        $template_data['command'] = $command;
        return view('admin.telegram_bot.command.edit', $template_data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TelegramBotCommand  $telegramBotCommand
     * @return \Illuminate\Http\Response
     */
    public function update($bot_id, Request $request, TelegramBotCommand $command)
    {
        $valid = $request->validate(
            [
                'id' => 'required',
                'caption' => 'required|string',
                'presc' => 'required|string',
                'bot_id' => 'required'
            ]
        );
        if (!$valid) return back()->withInput();

        $record = $command;
        $fields = Schema::getColumnListing($record->getTable());

        foreach ($fields as $field) {
            $record->$field = $request->input($field);
        }
        $record->save();
        return redirect(route('admin.telegram_bot.command.show', [$record->bot, $record]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TelegramBotCommand  $telegramBotCommand
     * @return \Illuminate\Http\Response
     */
    public function destroy($bot_id, TelegramBotCommand $command)
    {
        $command->delete();
        return redirect(route('admin.telegram_bot.command.index', $bot_id));
    }
}
