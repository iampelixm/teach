@extends('layout.admin')
@section('content')
    <main>
        <div class="container">
            <h1 class="title text-center">
                Команды бота {{ $bot->name }}
            </h1>
            <a class="btn btn-sm btn-success my-2" href="{{route('admin.telegram_bot.show', $bot->id)}}">К боту</a>
            <a class="btn btn-sm btn-success my-2" href="{{route('admin.telegram_bot.command.create', $bot)}}">Создать команду</a>
            @if (!collect($commands)->isEmpty())
                @component('component.table', [
                    'items' => $commands,
                    'show_fields' => ['caption'],
                    'captions' => ['caption' => 'Назнваие'],
                    'link' => route('admin.telegram_bot.command.index', $bot_id) . '/',
                    'link_item_key' => 'id',
                    ])
                @endcomponent
            @else
                @component('component.alert', ['type' => 'warning'])
                    Команд еще нет.
                    <a class="btn btn-success" href="{{ route('admin.telegram_bot.command.create', $bot) }}">Создать</a>
                @endcomponent
            @endif
        </div>
    </main>
@endsection
