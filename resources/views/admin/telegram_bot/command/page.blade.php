@extends('layout.admin')
@section('content')
    <main>
        <div class="container">
            <h1 class="title text-center">
                Команда {{ $command->caption }} бота {{ $bot->name }}
            </h1>
            <div class="text-right">
                <a href="{{ route('admin.telegram_bot.command.edit', [$bot, $command]) }}"
                    class="btn btn-info">Изменить команду</a>
                <x-form action="{{route('admin.telegram_bot.command.destroy', [$bot, $command])}}" method="POST">
                    @method("DELETE")
                <button class="btn btn-danger">Удалить команду</button> 
                </x-form>
            </div>
<a class="btn btn-sm btn-success" href="{{route('admin.telegram_bot.command.index', $bot->id)}}">К командам бота</a>
            <div>Описание бота: {{ $bot->presc }}</div>
            <div>Описание команды: {{ $command->prec }}</div>
            <h1 class="title">Действия</h1>
            <div class="text-right"><a class="btn btn-success"
                    href="{{ route('admin.telegram_bot.command.action.create', [$bot->id, $command->id]) }}">Создать</a>
                    
                @if (!collect($command->actions)->isEmpty())
                    @component('component.table', [
                        'items' => $command->actions,
                        'show_fields' => ['action'],
                        'captions' => ['action' =>'Команда'],
                        'link' => '/admin/telegram_bot/'.$bot->id.'/command/'.$command->id.'/action/',
                        'link_item_key' => 'id',
                        ])
                    @endcomponent
                @else
                    @component('component.alert', ['type' => 'warning'])
                        Действий еще нет.
                    @endcomponent
                @endif
    </main>
@endsection
