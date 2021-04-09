@extends('layout.admin')
@php
if (!isset($bot)) {
    $bot = new App\Models\TelegramBot();
}
@endphp
@section('content')
    <main>
        <div class="container">
            <h1 class="title text-center">
                {{ $bot->id ? 'Изменить бота ' . $bot->name : 'Создание бота' }}
            </h1>

            @if($bot->id)
                <a href="{{route('admin.telegram_bot.register_webhook', $bot)}}">Подключить бота</a>
            @endif
            <x-form
                action="{{ $bot->id ? route('admin.telegram_bot.update', $bot->id) : route('admin.telegram_bot.store') }}"
                method="POST">
                @if ($bot && $bot->id)
                    @method('PATCH')
                @endif
                @csrf
                <x-form-input :bind="$bot" name="id" type="hidden" />
                <x-form-input :bind="$bot" name="name" label="Имя бота" required aria-required="true" />
                <x-form-select :bind="$bot" name="default_chain" label="Беседа по умолчанию">
                    @foreach ($bot->chains as $chain)
                        <option value="{{ $chain->id }}" {{$bot->default_chain == $chain->id ? 'selected' : ''}}>{{ $chain->caption }}</option>
                    @endforeach
                </x-form-select>
                <x-form-group>
                    <x-form-label label="Обязательная авторизация пользователя"/>
                    <x-form-radio :bind="$bot" name="auth_user" value="1" label="Да" />
                    <x-form-radio :bind="$bot" name="auth_user" value="0" label="Нет" />
                </x-form-group>
                <x-form-input :bind="$bot" name="key" label="API ключ" required aria-required="true" />
                <x-form-textarea :bind="$bot" name="presc" label="Описание" />
                <button class="btn btn-success">Сохранить</button>
            </x-form>
        </div>
    </main>
@endsection
