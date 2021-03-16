<?php

namespace App\Http\Controllers\API\BOT;

use App\Models\TelegramBot;
use App\Models\TelegramBotConversationChain;
use App\Models\TelegramBotConversationChainItem;
use App\Models\TelegramBotCommand;
use App\Models\User;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends BotController
{
    public $telegram; //экземпляр класса телеги
    public $update_type; //тип обновления
    public $callback_data; //
    public $action; //
    public $name; //

    public function __construct()
    {
        $this->chains=new TelegramBotConversationChain();
        $this->commands=new TelegramBotCommand();
    }

    public function processHook(Request $request, $key)
    {
        $bot = TelegramBot::where('key', $key)->first();
        if(!$bot) return '';
        $this->bot = $bot;

        //file_put_contents('tlgrm.json', json_encode($request->toArray(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        $update = $this->update = $request->toArray();
        if (isset($update['callback_query'])) {
            $this->update_type = 'callback';
            $this->chat_id = $update['callback_query']['from']['id'];
            $this->name = $update['callback_query']['from']['first_name'];
            $this->callback_data = $update['callback_query']['data'];
        } elseif (isset($update['message'])) {
            $this->update_type = 'message';
            $this->message = $update['message']['text'];

            //Команды присылаются как сообщения. Но команда всегда начинается с "/"
            if(mb_substr($this->message,0,1) == '/')
            {
                $this->message=mb_substr($this->message, 1);
                $this->update_type='command';
            }
            $this->chat_id = $update['message']['from']['id'];
            $this->name = $update['message']['from']['first_name'];
        } else {
                $this->sendMessage($this->name . ', я не знаю что такое вы мне прислали');
            return '';
        }

        //TODO:: где-то здесь должна быть обработка комманд. Они не относятся к беседам, поэтому случаются раньше обработки беседы

        $this->conversation = $bot->conversations()->firstOrCreate(['bot_id' => $bot->id, 'chat_id' => $this->chat_id]);

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

        if ($this->update_type == 'command') {
            $this->processCommand();
            return '';
        }        

        //если беседа не начата - выведем список команд, некий хелп, что бы пользователь не был без ответа
        if (!$this->conversation->chain_item_id) {
            $this->sendMessage($this->name . ', я пока не умею отвечать на сообщения и реагирую только на команды.');
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
            Telegram::sendMessage([
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
            $check_function_param=mb_substr($check_function_param, 1);
            $check_function_param = $this->$check_function_param ?? '';
        }

        $true_function_param = $chain_item->true_function_param;
        if (mb_substr($true_function_param, 0, 1) == '$') {
            $true_function_param=mb_substr($true_function_param, 1);
            $true_function_param = $this->$true_function_param ?? '';
        }

        $false_function_param = $chain_item->false_function_param;
        if (mb_substr($false_function_param, 0, 1) == '$') {
            $false_function_param=mb_substr($false_function_param, 1);
            $false_function_param = $this->$false_function_param ?? '';
        }
        //$this->sendMessage('обработка запроса. Текущее звено '.$this->conversation->chain_item_id);

        //$this->startChain(4);
        //$this->sendMessage('Цепочка: '.$this->conversation->nextChainItem());
        if (method_exists($this, $check_function)) {
           // $this->sendMessage("Пытаюсь выполнить $check_function($check_function_param)");
            $check_result = $this->$check_function($check_function_param);
            //$this->sendMessage("Result: $check_result ");
            if ($check_result) {
                //$this->sendMessage("Результат похож на правду");
                if (method_exists($this, $true_function)) {
                    // $this->sendMessage("Выполняю TRUE ");
                    $this->$true_function($true_function_param);
                }
                else {
                    // $this->sendMessage("Нет такого TRUE метода: $true_function ");
                }
            } else {
                // $this->sendMessage("Выполняю FALSE ");
                if (method_exists($this, $false_function)) {
                    $this->$false_function($false_function_param);
                }
                else {
                    // $this->sendMessage("нет такого FALSEметода $false_function");
                }
            }
        } else {
            $this->sendMessage('Памажите! Я не знаю что делать... бот сломался. Напишите в техподдержку @pelixm');
            $this->sendMessage('Метод не найден: ' . $check_function);
            $this->breackChain();
        }
        return '';
    }









    public function sendMessage($text, $markup = '')
    {
        Telegram::sendMessage([
            'chat_id' => $this->chat_id,
            'text' => $text
        ]);
    }
}
