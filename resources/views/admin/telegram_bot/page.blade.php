@extends('layout.admin')
@section('content')
    <main>
        <div class="container">
            <h1 class="title text-center">
                {{ $bot->id ? 'Бот ' . $bot->name : 'Создание бота' }}
            </h1>
            <div class="text-right">
                <a href="{{ route('admin.telegram_bot.edit', $bot) }}" class="btn btn-info">Изменить</a>
            </div>

            <div>Описание: {{ $bot->presc }}</div>
            <h1 class="title">Цепочки</h1>
            <div class="text-right"><a
                    href="{{ route('admin.telegram_bot.conversation_chain.create', $bot) }}">Создать</a>
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
                {{$bot->conversations}}
                @if (!collect($bot->conversations)->isEmpty())
                    @component('component.table', [
                        'items' => $bot->conversations,
                        'show_fields' => ['caption'],
                        'captions' => ['caption' => 'Название'],
                        'link' => route('admin.telegram_bot.show', $bot) . '/conversation_chain/',
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
