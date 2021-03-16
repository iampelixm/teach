@extends('layout.admin')
@php
if (!isset($action)) {
    $action = new App\Models\TelegramBotCommandAction();
}

@endphp
@section('content')
    <main>
        <div class="container">
            <h1 class="title text-center">
                {{ $action->id ? 'Изменить действие ' . $action->action . ' команды ' . $action->command->caption .' бота '. $action->command->bot->name : 'Создание действия команды ' . $command->caption }}
            </h1>
<a class="btn btn-info" href="{{route('admin.telegram_bot.command.show', [$bot->id, $command->id])}}">К команде</a>            
            <x-form
                action="{{ $action->id ? 
                    route('admin.telegram_bot.command.action.update', [$action->command->bot->id, $action->command->id, $action->id]) : 
                    route('admin.telegram_bot.command.action.store', [$command->bot->id, $command->id]) }}"
                method="POST">
                @if ($action && $action->id)
                    @method('PATCH')
                @endif
                @csrf
                <x-form-input :bind="$action" name="id" type="hidden" />
                <x-form-input :bind="$action" name="telegram_bot_command_id" type="hidden" value="{{ $command->id }}" />
                <x-form-input :bind="$action" name="action" label="Функция" required aria-required="true" />
                <x-form-textarea :bind="$action" name="action_param_1" label="Параметр 1" />
                <x-form-textarea :bind="$action" name="action_param_2" label="Параметр 2" />
                <x-form-textarea :bind="$action" name="action_param_3" label="Параметр 3" />
                <x-form-textarea :bind="$action" name="action_param_4" label="Параметр 4" />
                <x-form-textarea :bind="$action" name="action_param_5" label="Параметр 5" />
                <button class="btn btn-success">Сохранить</button>
            </x-form>
        </div>
    </main>
@endsection
