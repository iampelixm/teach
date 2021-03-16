@extends('layout.admin')
@section('content')
    <main>
        <div class="container">
            <h1 class="title text-center">
                Звенья цепочки {{$chain->caption}} бота {{ $bot->name }}
            </h1>
            <a href="{{route('admin.telegram_bot.conversation_chain.show', [$bot->id, $chain->id])}}">К цепочке</a>
            @if (!collect($chain_items)->isEmpty())
                @component('component.table', [
                    'items' => $chain_items,
                    'show_fields' => ['caption'],
                    'captions' => ['caption' => 'Назнваие'],
                    'link' => '/admin/telegram_bot/'.$bot->id.'/conversation_chain/'.$chain->id.'/chain_item/',
                    'link_item_key' => 'id',
                    ])
                @endcomponent
            @else
                @component('component.alert', ['type' => 'warning'])
                    Звеньев еще нет.
                    <a class="btn btn-success" href="{{ route('admin.telegram_bot.conversation_chain.chain_item.create', [$bot->id, $chain->id]) }}">Создать</a>
                @endcomponent
            @endif
        </div>
    </main>
@endsection
