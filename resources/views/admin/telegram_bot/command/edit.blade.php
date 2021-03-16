@extends('layout.admin')
@php
if (!isset($command)) {
    $command = new App\Models\TelegramBotCommand();
}

@endphp
@section('content')
    <main>
        <div class="container">
            <h1 class="title text-center">
                {{ $command->id ? 'Изменить команду ' . $command->caption . ' бота ' . $bot->name : 'Создание команды бота ' . $bot->name }}
            </h1>
            <x-form
                action="{{ $command->id ? route('admin.telegram_bot.command.update', [$bot,$command]) : route('admin.telegram_bot.command.store', $bot->id) }}"
                method="POST">
                @if ($command && $command->id)
                    @method('PATCH')
                @endif
                @csrf
                <x-form-input :bind="$command" name="id" type="hidden" />
                <x-form-input :bind="$command" name="bot_id" type="hidden" value="{{ $bot->id }}" />
                <x-form-input :bind="$command" name="caption" label="Название команды" required aria-required="true" />
                <x-form-input :bind="$command" name="command" label="Команда" required aria-required="true" />
                <x-form-textarea :bind="$command" name="presc" label="Описание команды" />
                <button class="btn btn-success">Сохранить</button>
            </x-form>
        </div>
    </main>
@endsection
