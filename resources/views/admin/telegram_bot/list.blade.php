@extends('layout.admin')
@section('content')
    <main>
        <div class="container">
            <h1 class="title text-center">
                Список телеграм ботов
            </h1>
            @if (!collect($bots)->isEmpty())
                @component('component.table', [
                    'items' => $bots,
                    'show_fields' => ['name', 'presc'],
                    'captions' => ['name' => 'Имя', 'presc' => 'Описание'],
                    'link' => '/admin/telegram_bot/',
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
