<x-form action="{{ route('web.profile.update') }}">
    @csrf
    <x-form-input type="hidden" :bind="$user" name="id" />
    <x-form-input :bind="$user" name="name" label="Ваше имя:" />
    <x-form-input type="hidden" :bind="$user" name="phone" label="Телефон:" />
    <x-form-input :bind="$user" name="email" label="Электронная почта (login):" disabled />
    <x-form-input :bind="$user" name="check_code" label="Проверочный код:" disabled />
    <x-form-checkbox name="checkbox" label="Изменить пароль" data-toggle="collapse" href="#profile_change_password"
        role="button" aria-expanded="false" aria-controls="profile_change_password" />
    <div class="form-group collapse" id="profile_change_password">
        <x-form-input name="password" type="password" label="Введите новый пароль" />
    </div>
    <button class="btn btn-success">Сохранить</button>
</x-form>
