<?php

namespace App\Http\Controllers\API\BOT;

use App\Models\User;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends BotController
{
    public $chat_id;
    public $name;
    public $user;
    public $action;
    public $update;
    public $telegram;
    public $update_type;
    public $callback_data;

    public function associateUser()
    {
        $update = $this->update;
        //$keyboard = ['\register' => 'Регистрация', '\login' => 'Войти'];
        // $reply_markup = Telegram::replyKeyboardMarkup([
        //     'keyboard' => $keyboard,
        //     'resize_keyboard' => true,
        //     'one_time_keyboard' => true
        // ]);
        $keyboard = [
            "keyboard" => [
                [
                    ["text" => 'one but']
                ],
                [
                    ["text" => 'sec but']
                ]

            ],
            'resize_keyboard' => true
        ];

        $keyboard = [
            'inline_keyboard' =>
            [
                [
                    ['text' => 'Войти', "callback_data" => "action_login_process"],
                    ['text' => 'Зарегистрироваться', "callback_data" => "action_registration_process"],
                ]
            ]
        ];
        Telegram::sendMessage([
            'chat_id' => $this->chat_id,
            'text' => 'Привет, ' . $this->name . '!
Кажется, мы с вами не знакомы.
Я бот портала обучения.
Если у вас уже есть учетная запись - давайте ее проверим.
А нет - так зарегистрируем!
Что будем делать?:',
            'reply_markup' => json_encode($keyboard)
        ]);
    }


    public function processHook(Request $request, $key)
    {
        file_put_contents('tlgrm.json', json_encode($request->toArray(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        $update = $this->update = $request->toArray();
        if (isset($update['callback_query'])) {
            $this->update_type = 'callback';
            $this->chat_id = $update['callback_query']['from']['id'];
            $this->name = $update['callback_query']['from']['first_name'];
            $this->callback_data = $update['callback_query']['data'];
        } elseif (isset($update['message'])) {
            $this->update_type = 'message';
            $this->chat_id = $update['message']['from']['id'];
            $this->name = $update['message']['from']['first_name'];
        } else {
            return '';
        }

        $user = User::where('telegram_id', $this->chat_id)->first();
        if (!$user) return $this->associateUser();

        if ($this->update_type == 'message') {
            Telegram::sendMessage([
                'chat_id' => $this->chat_id,
                'text' => $this->name . ', я пока не умею отвечать на сообщения и реагирую только на команды.'
            ]);
            return '';
        }

        if ($this->update_type == 'callback') {
            $this->processCallback();
            return '';
        }

        return '';
    }

    public function processCallback()
    {
        if ($this->callback_data == 'action_login_process') {
            Telegram::sendMessage([
                'chat_id' => $this->chat_id,
                'text' => 'Так классно, что вы решили воспользоваться ботом! Сайты, все эти сложности, это уже давно прошлый век! ' .
                    'Время искусственного интеллекта пришло! Ну, почти... '
            ]);
            return '';
        }
    }
}
