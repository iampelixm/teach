<?php

namespace App\Http\Controllers;

use App\Models\TelegramBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\AdminController;

class TelegramBotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $template_data = AdminController::getTemplateData();
        $template_data['bots'] = TelegramBot::all();
        return view('admin.telegram_bot.list', $template_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $template_data = AdminController::getTemplateData();
        return view('admin.telegram_bot.edit', $template_data);
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
                'name' => 'required|string',
                'key' => 'required|string'
            ]
        );
        if (!$valid) return back()->withInput();

        $record = new TelegramBot();
        $fields = Schema::getColumnListing($record->getTable());

        foreach ($fields as $field) {
            $record->$field = $request->input($field);
        }
        $record->save();

        return redirect(route('admin.telegram_bot.show', $record));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TelegramBot  $telegramBot
     * @return \Illuminate\Http\Response
     */
    public function show(TelegramBot $telegramBot)
    {
        $template_data = AdminController::getTemplateData();
        $template_data['bot'] = $telegramBot;
        return view('admin.telegram_bot.page', $template_data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TelegramBot  $telegramBot
     * @return \Illuminate\Http\Response
     */
    public function edit(TelegramBot $telegramBot)
    {
        $template_data = AdminController::getTemplateData();
        $template_data['bot'] = $telegramBot;
        return view('admin.telegram_bot.edit', $template_data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TelegramBot  $telegramBot
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TelegramBot $telegramBot)
    {
        $valid = $request->validate(
            [
                'name' => 'required|string',
                'key' => 'required|string'
            ]
        );
        if (!$valid) return back()->withInput();

        $record = $telegramBot;
        $fields = Schema::getColumnListing($record->getTable());

        foreach ($fields as $field) {
            $record->$field = $request->input($field);
        }
        $record->save();

        return redirect(route('admin.telegram_bot.show', $record));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TelegramBot  $telegramBot
     * @return \Illuminate\Http\Response
     */
    public function destroy(TelegramBot $telegramBot)
    {
        $telegramBot->remove();
        return redirect(route('admin.telegram_bot.list'));
    }
}
