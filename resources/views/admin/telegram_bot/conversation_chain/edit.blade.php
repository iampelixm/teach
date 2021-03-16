@extends('layout.admin')
@php
if (!isset($chain)) {
    $chain = new App\Models\TelegramBotConversationChain();
}

@endphp
@section('content')
    <main>
        <div class="container">
            <h1 class="title text-center">
                {{ $chain->id ? 'Изменить цепочку ' . $chain->name . ' бота ' . $bot->name : 'Создание цепочки бота ' . $bot->name }}
            </h1>
            <x-form
                action="{{ $chain->id ? route('admin.telegram_bot.conversation_chain.update', [$bot->id,$chain->id]) : route('admin.telegram_bot.conversation_chain.store', $bot->id) }}"
                method="POST">
                @if ($chain && $chain->id)
                    @method('PATCH')
                @endif
                @csrf
                <x-form-input :bind="$chain" name="id" type="hidden" />
                <x-form-input :bind="$chain" name="bot_id" type="hidden" value="{{ $bot->id }}" />
                <x-form-input :bind="$chain" name="caption" label="Название цепочки" required aria-required="true" />
                <x-form-textarea :bind="$chain" name="start_message" label="Первое сообщение" />
                <button class="btn btn-success">Сохранить</button>
            </x-form>
        </div>
    </main>
@endsection
