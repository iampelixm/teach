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
    </main>
@endsection
