<?php

namespace App\Http\Controllers;


use App\Models\TelegramBotCommand;
use App\Models\TelegramBotCommandAction;
use Illuminate\Http\Request;
use App\Models\TelegramBot;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Schema;

class TelegramBotCommandActionController extends Controller
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
    public function index($bot_id, $command_id)
    {
        $template_data = AdminController::getTemplateData();
        $template_data['actions'] = TelegramBotCommandAction::where('telegram_bot_command_id', $command_id)->get();
        $template_data['command'] = TelegramBotCommand::find($command_id);
        $template_data['bot'] = TelegramBot::find($bot_id);
        return view('admin.telegram_bot.command.action.list', $template_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($bot_id, $command_id)
    {
        $template_data = AdminController::getTemplateData();
        $template_data['bot'] = TelegramBot::find($bot_id);
        $template_data['command'] = TelegramBotCommand::find($command_id);
        return view('admin.telegram_bot.command.action.edit', $template_data);
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
                'telegram_bot_command_id' => 'required|integer',
                'action' => 'nullable|string'
            ]
        );
        if (!$valid) return back()->withInput();

        $record = new TelegramBotCommandAction();
        $fields = Schema::getColumnListing($record->getTable());

        foreach ($fields as $field) {
            $record->$field = $request->input($field);
        }
        $record->save();
        return redirect(route('admin.telegram_bot.command.action.show', [$record->command->bot, $record->command, $record]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TelegramBotCommandAction  $telegramBotCommandAction
     * @return \Illuminate\Http\Response
     */
    public function show($bot_id, $command_id, TelegramBotCommandAction $action)
    {
        $template_data = AdminController::getTemplateData();
        $template_data['bot'] = $action->command->bot;
        $template_data['command'] = $action->command;
        $template_data['action'] = $action;
        return view('admin.telegram_bot.command.action.edit', $template_data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TelegramBotCommandAction  $telegramBotCommandAction
     * @return \Illuminate\Http\Response
     */
    public function edit($bot_id, $command_id, TelegramBotCommandAction $action)
    {
        $template_data = AdminController::getTemplateData();
        $template_data['bot'] = $action->command->bot;
        $template_data['command'] = $action->command;
        $template_data['action'] = $action;
        return view('admin.telegram_bot.command.action.edit', $template_data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TelegramBotCommandAction  $telegramBotCommandAction
     * @return \Illuminate\Http\Response
     */
    public function update($bot_id, $command_id, Request $request, TelegramBotCommandAction $action)
    {
        $valid = $request->validate(
            [
                'id'=>'required',
                'telegram_bot_command_id' => 'required|integer',
                'action' => 'nullable|string'
            ]
        );
        if (!$valid) return back()->withInput();

        $record = $action;
        $fields = Schema::getColumnListing($record->getTable());

        foreach ($fields as $field) {
            $record->$field = $request->input($field);
        }
        $record->save();
        return redirect(route('admin.telegram_bot.command.action.show', [$record->command->bot, $record->command, $record]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TelegramBotCommandAction  $telegramBotCommandAction
     * @return \Illuminate\Http\Response
     */
    public function destroy($bot_id, $command_id, TelegramBotCommandAction $action)
    {
        $action->delete();
        return redirect(route('admin.telegram_bot.command.show', [$action->command->bot, $action->command]));
    }
}
