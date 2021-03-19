@extends('layout.admin')
@section('content')
    <main>
        <div class="container">
            <h1 class="title text-center">
                {{ $bot->id ? 'Бот ' . $bot->name : 'Создание бота' }}
            </h1>
            <div class="text-right">
                <a href="{{ route('admin.telegram_bot.edit', $bot) }}" class="btn btn-info">Изменить бота</a>
            </div>

            <div>Описание: {{ $bot->presc }}</div>
            <h1 class="title">Беседы</h1>
            <div class="text-right"><a
                    class="btn btn-sm btn-info" href="{{ route('admin.telegram_bot.conversation_chain.create', $bot) }}">Создать беседу</a>
            </div>
            @if (!collect($bot->chains)->isEmpty())
                @component('component.table', [
                    'items' => $bot->chains,
                    'show_fields' => ['caption'],
                    'captions' => ['caption' => 'Название'],
                    'link' => route('admin.telegram_bot.show', $bot) . '/conversation_chain/',
                    'link_item_key' => 'id',
                    ])
                @endcomponent
            @else
                @component('component.alert', ['type' => 'warning'])
                    Цепочек еще нет.
                    <a class="btn btn-success"
                        href="{{ route('admin.telegram_bot.conversation_chain.create', $bot) }}">Создать</a>
                @endcomponent
            @endif
            <h2 class="title text-center">Пользователи бота</h2>
            @if (!collect($bot->conversations)->isEmpty())
                @component('component.table', [
                    'items' => $bot->conversations,
                    'show_fields' => ['updated_at'],
                    'captions' => ['updated_at' => 'Когда', 'chat_id' => 'ИД контакта'],
                    'link' => '',
                    'link_item_key' => 'id',
                    ])
                @endcomponent
            @else
                @component('component.alert', ['type' => 'warning'])
                    обращений не было
                @endcomponent
            @endif

            <h2 class="title text-center">Команды бота</h2>
            @if (!collect($bot->commands)->isEmpty())
                @component('component.table', [
                    'items' => $bot->commands,
                    'show_fields' => ['caption', 'command', 'presc'],
                    'captions' => ['caption' => 'Название', 'command' => 'команда', 'presc' => 'Описание'],
                    'link' => route('admin.telegram_bot.show', $bot) . '/command/',
                    'link_item_key' => 'id',
                    ])
                @endcomponent
            @else
                @component('component.alert', ['type' => 'warning'])
                    обращений не было
                @endcomponent
            @endif
    </main>
@endsection
