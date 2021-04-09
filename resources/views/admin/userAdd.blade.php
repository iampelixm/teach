@extends('layout.admin')

@section('content')
    <main>
        <div class="container">
            <h1 class="title text-center my-4">
                Создание пользователя
            </h1>
            <div class="row justify-content-center">

                <div class="col-lg-6">
                    <x-form action="/admin/user/add">
                        <x-form-input name="name" label="{{ __('Name') }}" />
                        <x-form-input name="email" label="{{ __('Email') }}" />
                        <x-form-input name="password" label="{{ __('Password') }}" />
			<x-form-input name="telegram_id" type="hidden" value="" />
                        <h3>Роли</h3>
                        <x-form-group>
                            @foreach (Silber\Bouncer\Database\Role::all() as $role)
                                <x-form-checkbox bind:Role id="{{ $role['name'] }}" name="roles[]"
                                    value="{{ $role['name'] }}" label="{{ $role['title'] }}" />
                            @endforeach
                        </x-form-group>
                        <x-form-submit>Сохранить</x-form-submit>
                    </x-form>
                </div>
            </div>
        </div>
    </main>
@endsection
