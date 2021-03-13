@extends('layout.admin')
@section('content')
    <main>
        <div class="container">
            <h1 class="title text-center">
                Цепочки бота {{ $bot->name }}
            </h1>
            <a href="{{route('admin.telegram_bot.show', $bot->id)}}">К боту</a>
            @if (!collect($chains)->isEmpty())
                @component('component.table', [
                    'items' => $chains,
                    'show_fields' => ['caption'],
                    'captions' => ['caption' => 'Назнваие'],
                    'link' => route('admin.telegram_bot.conversation_chain.index', $bot_id) . '/',
                    'link_item_key' => 'id',
                    ])
                @endcomponent
            @else
                @component('component.alert', ['type' => 'warning'])
                    Ботов еще нет.
                    <a class="btn btn-success" href="{{ route('admin.telegram_bot.create') }}">Создать</a>
                @endcomponent
            @endif
        </div>
    </main>
@endsection
