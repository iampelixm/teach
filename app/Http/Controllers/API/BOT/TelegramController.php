<?php

namespace App\Http\Controllers\API\BOT;

use App\Models\TelegramBot;
use Telegram\Bot\Api;
use App\Models\TelegramBotConversationChain;
use App\Models\TelegramBotConversationChainItem;
use App\Models\TelegramBotCommand;
use App\Models\User;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends BotController
{
    public $telegram; //фасад текущего бота
    public $update_type; //тип обновления
    public $callback_data; //
    public $action; //
    public $name; //

    public function __construct()
    {
        $this->chains = new TelegramBotConversationChain();
        $this->commands = new TelegramBotCommand();
    }

    public function processHook(Request $request, $key)
    {
        $bot_model = TelegramBot::where('key', $key)->first();
        if (!$bot_model) return '';
        $bot = new Api(
            $key,
            false,
            null
        );

        $this->bot = $bot;
        $this->bot_model = $bot_model;
        file_put_contents('tlgrm.json', json_encode($request->toArray(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        $update = $this->update = $request->toArray();
        if (isset($update['callback_query'])) {
            $this->update_type = 'callback';
            $this->chat_id = $update['callback_query']['from']['id'];
            $this->name = $update['callback_query']['from']['first_name'];
            $this->callback_data = $update['callback_query']['data'];
        } elseif (isset($update['message'])) {
            $this->update_type = 'message';
            $this->message = '';
            if (isset($update['message']['text']))
                $this->message = $update['message']['text'];

            //Команды присылаются как сообщения. Но команда всегда начинается с "/"
            if (mb_substr($this->message, 0, 1) == '/') {
                $this->message = mb_substr($this->message, 1);
                $this->update_type = 'command';
            }
            $this->chat_id = $update['message']['from']['id'];
            $this->name = $update['message']['from']['first_name'];
        } else {
            $this->sendMessage($this->name . ', я не знаю что такое вы мне прислали');
            return '';
        }

        $this->conversation = $bot_model->conversations()->firstOrCreate(['bot_id' => $bot_model->id, 'chat_id' => $this->chat_id]);

        //Команды обрабатываются до бесед и срабатывают в любом случае. 
        if ($this->update_type == 'command') {
            $this->processCommand();
            //return '';
        }

        if ($bot_model->auth_user) {
            $user = User::where('telegram_id', $this->chat_id)->first();
            //Если пользователь не определен то
            if (!$user) {
                //если при этом не определен этап беседы с пользователем
                if (!$this->conversation->chain_item_id) {
                    //найдем цепочки для авторизации и запустим ее
                    $chain = TelegramBotConversationChain::where('caption', 'auth')->first(); {
                        $this->startChain($chain->id);
                        return '';
                    }
                    //если беседа уже начата - продолжим ее
                }
            }
        }


        //если беседа не начата - начинаем беседу по дефолту.
        //такая беседа может быть любой, шуточной или с системой помощи по боту, зависит от задач
        if (!$this->conversation->chain_item_id) {
            if ($this->bot_model->default_chain) {
                $this->startChain($this->bot_model->default_chain);
            } else {
                //или выводим заглушку
                $this->sendMessage($this->name . ', я пока не умею отвечать на сообщения и реагирую только на команды. Попробуй /help');
            }
            //$this->sendMessage(print_r($this->update, true));
            return '';
        }

        if ($this->update_type == 'message') {
            $this->processConversation();
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
            $this->sendMessage([
                'chat_id' => $this->chat_id,
                'text' => 'Так классно, что вы решили воспользоваться ботом! Сайты, все эти сложности, это уже давно прошлый век! ' .
                    'Время искусственного интеллекта пришло! Ну, почти... '
            ]);
            return '';
        }
    }

    public function processConversation()
    {

        $chain_item = $this->conversation->chainItem;
        $check_function = $chain_item->check_function;
        $true_function = $chain_item->true_function;
        $false_function = $chain_item->false_function;

        $check_function_param = $chain_item->check_function_param;
        if (mb_substr($check_function_param, 0, 1) == '$') {
            $check_function_param = mb_substr($check_function_param, 1);
            $check_function_param = $this->$check_function_param ?? '';
        }

        $true_function_param = $chain_item->true_function_param;
        if (mb_substr($true_function_param, 0, 1) == '$') {
            $true_function_param = mb_substr($true_function_param, 1);
            $true_function_param = $this->$true_function_param ?? '';
        }

        $false_function_param = $chain_item->false_function_param;
        if (mb_substr($false_function_param, 0, 1) == '$') {
            $false_function_param = mb_substr($false_function_param, 1);
            $false_function_param = $this->$false_function_param ?? '';
        }

        if ($check_function) {
            if (method_exists($this, $check_function)) {
                // $this->sendMessage("Пытаюсь выполнить $check_function($check_function_param)");
                $check_result = $this->$check_function($check_function_param);
                //$this->sendMessage("Result: $check_result ");
                if ($check_result) {
                    //$this->sendMessage("Результат похож на правду");
                    if (method_exists($this, $true_function)) {
                        // $this->sendMessage("Выполняю TRUE ");
                        $this->$true_function($true_function_param);
                    } else {
                        // $this->sendMessage("Нет такого TRUE метода: $true_function ");
                    }
                } else {
                    // $this->sendMessage("Выполняю FALSE ");
                    if (method_exists($this, $false_function)) {
                        $this->$false_function($false_function_param);
                    } else {
                        // $this->sendMessage("нет такого FALSEметода $false_function");
                    }
                }
            } else {
                $this->sendMessage('Памажите! Я не знаю что делать... бот сломался. Напишите в техподдержку @pelixm');
                $this->sendMessage('Метод не найден: ' . $check_function);
                $this->breackChain();
            }
        } else {
            //Если нет проверочной функции - тупо дублируем текущее сообщение
            $this->sendMessage($this->conversation->chainItem->message);
        }
        return '';
    }

    public function parseText($text)
    {
        $out = $text;
        $matches = [];
        $var_pattern = '/\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/';
        while (preg_match($var_pattern, $out, $matches)) {
            foreach ($matches as $var_name) {

                $var_value = "!$var_name!";
                if (isset($this->$var_name)) $var_value = $this->$var_name;
                $out = preg_replace("/" . preg_quote("$" . $var_name) . "/", $var_value, $out);
            }
            break;
        }
        return $out;
    }

    public function sendMessage($text, $markup = '')
    {
        $text = $this->parseText($text);
        $this->bot->sendMessage([
            'chat_id' => $this->chat_id,
            'text' => $text
        ]);
    }
}
