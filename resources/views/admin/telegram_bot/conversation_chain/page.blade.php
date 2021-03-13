@extends('layout.admin')
@section('content')
    <main>
        <div class="container">
            <h1 class="title text-center">
                Цепочка {{ $chain->caption }} бота {{ $bot->name }}
            </h1>
            <div class="text-right">
                <a href="{{ route('admin.telegram_bot.conversation_chain.edit', [$bot, $chain]) }}"
                    class="btn btn-info">Изменить</a>
            </div>
<a href="{{route('admin.telegram_bot.conversation_chain.index', $bot->id)}}">К цепочкам бота</a>
            <div>Описание бота: {{ $bot->presc }}</div>
            <div>Стартовое сообщение: {{ $chain->start_message }}</div>
            <h1 class="title">Звенья</h1>
            <div class="text-right"><a class="btn btn-success"
                    href="{{ route('admin.telegram_bot.conversation_chain.chain_item.create', [$bot->id, $chain->id]) }}">Создать</a>
                    
                @if (!collect($chain->items)->isEmpty())
                    @component('component.table', [
                        'items' => $chain->items,
                        'show_fields' => ['caption', 'check_function'],
                        'captions' => ['caption' => 'название', 'check_function'=>'Функция проверки'],
                        'link' => '/admin/telegram_bot/'.$bot->id.'/conversation_chain/'.$chain->id.'/chain_item/',
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
    </main>
@endsection
