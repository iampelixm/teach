@extends('layout.admin')
@php
if (!isset($chain_item)) {
    $chain_item = new App\Models\TelegramBotConversationChainItem();
}

@endphp
@section('content')
    <main>
        <div class="container">
            <h1 class="title text-center">
                {{ $chain_item->id ? 'Изменить звено ' . $chain_item->caption . ' бота ' . $bot->name : 'Создание звена цепочки ' . $chain->caption }}
            </h1>
<a class="btn btn-info" href="{{route('admin.telegram_bot.conversation_chain.show', [$bot->id, $chain->id])}}">К цепочке</a>            
            <x-form
                action="{{ $chain_item->id ? 
                    route('admin.telegram_bot.conversation_chain.chain_item.update', [$chain_item->chain->bot->id, $chain_item->chain->id, $chain_item->id]) : 
                    route('admin.telegram_bot.conversation_chain.chain_item.store', [$bot->id, $chain->id]) }}"
                method="POST">
                @if ($chain_item && $chain_item->id)
                    @method('PATCH')
                @endif
                @csrf
                <x-form-input :bind="$chain_item" name="id" type="hidden" />
                <x-form-input :bind="$chain_item" name="chain_id" type="hidden" value="{{ $chain->id }}" />
                <x-form-input :bind="$chain_item" name="caption" label="Название звена" required aria-required="true" />
                <x-form-textarea :bind="$chain_item" name="message" label="Сообщение пользователю" />
                <x-form-input :bind="$chain_item" name="check_function" label="Функция проверки" required aria-required="true" />
                <x-form-input :bind="$chain_item" name="true_function" label="Функция если проверка ОК" required aria-required="true" />
                <x-form-input :bind="$chain_item" name="false_function" label="Функция если проверка НЕ ОК" required aria-required="true" />
                <button class="btn btn-success">Сохранить</button>
            </x-form>
        </div>
    </main>
@endsection
