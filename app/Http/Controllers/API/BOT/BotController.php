<?php

namespace App\Http\Controllers\API\BOT;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;

class BotController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $chat_id; // идентификатор чата с клиентом
    public $user; //модель пользователя
    public $update; //полученные данные
    public $message; //полученный текст
    public $bot_model; //модель бота
    public $bot; //фасад бота
    public $conversation; //модель общения
    public $chains; //объект модели цепочек
    public $commands; //оъект класса модели команд бота
    //общие функции ботов

    public function sendMessage($text)
    {
        //функцию переопределяет каждый тип бота под себя
    }

    public function breackChain()
    {
        $this->conversation->chain_item_id = null;
        $this->conversation->save();
    }

    public function checkAuth()
    {
        //asdasd
        return 'auth function';
    }

    public function messageTextEq($txt)
    {
        if ($txt == $this->message) {
            return true;
        }
        return false;
    }

    public function endChainWithMessage($msg)
    {
        $this->breackChain();
        $this->sendMessage($msg);
    }

    public function nextChainItem()
    {
        //$this->sendMessage('Переходим на новый уровень общения, всмысле к следующей части разговора');
        $next = $this->conversation->nextChainItem();
        if ($next) {
            //$this->sendMessage('Новый этап: ' . $next->id . ' называется: ' . $next->caption);
            $this->conversation->chain_item_id = $next->id;
            $this->conversation->save();
            $this->sendMessage($next->message);
        }
    }

    public function associateUserByCheckCode($code)
    {
        $user = User::where('check_code', $code)->first();
        if ($user) {
            $user->telegram_id = $this->chat_id;
            $user->check_code='';
            $user->save();
            return true;
        }
        return false;
    }

    public function generateLoginKey($login)
    {
        if ($login) {
            $user = User::where('email', $login)->first();
            if ($user) {
                $check_code = random_int(100000, 999999999);
                while (!(collect(User::where('check_code', $check_code)->get())->isEmpty())) {
                    $check_code = random_int(100000, 999999999);
                }
                $user->check_code = $check_code;
                $user->save();
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function startChain($chain_id)
    {
        $chain = $this->chains->find($chain_id);
        if (!$chain) return '';
        if ($chain->start_message) {
            $this->sendMessage($chain->start_message);
        }
        $first_step = $chain->items->first();
        $this->conversation->chain_item_id = $first_step->id;
        $this->conversation->save();
        $this->sendMessage($first_step->message);
    }

    public function processCommand()
    {
        $command_data=explode(' ',$this->message);
        $command=array_shift($command_data);
        $bot_command=$this->commands::where(['command'=>$command, 'bot_id'=>$this->bot_model->id])->first();
        if($bot_command)
        {
            $actions=$bot_command->actions;
            foreach($actions as $action)
            {
                $function=$action->action;
                $param1=$action->action_param_1;
                if (method_exists($this, $function)) {
                    $this->$function($param1);
                }
                else
                {
                    $this->sendMessage('unknown command received: ' . $action->action);
                }
                
            }
        }
        else
        {
            $this->sendMessage('Команда не опознана');
        }
    }
}
