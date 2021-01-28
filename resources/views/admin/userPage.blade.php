@extends('layout.admin')

@section('content')
<main>
    <div class="container">
        <h1 class="title text-center my-4">
            Пользователь {{$user->name}}
        </h1>
        <div class="row justify-content-center">
            
            <div class="col-lg-6">
                <h3>Основные данные</h3>
                <x-form action="/admin/user/update">
                    <x-form-input type="hidden" :bind="$user" name="id"/>
                    <x-form-input :bind="$user" name="name" label="{{__('Name')}}"/>
                    <x-form-input :bind="$user" name="email" label="{{__('Email')}}"/>
                    <x-form-input name="password" label="{{__('Password')}}"/>
                    <x-form-submit>Сохранить</x-form-submit>
                </x-form>
            </div>
            <div class="col-lg-6">
        <h3>Роли пользователя</h3>
            <x-form action="/admin/user/update">
                <x-form-input type="hidden" :bind="$user" name="id"/>
                <x-form-group>
            @foreach($roles as $role)
                @if($user->isAn($role['name']))
                    <x-form-checkbox id="{{$role['name']}}" name="roles[]" checked value="{{$role['name']}}" label="{{$role['title']}}" />
                @else
                    <x-form-checkbox bind:Role id="{{$role['name']}}" name="roles[]" value="{{$role['name']}}" label="{{$role['title']}}" />
                @endif
            @endforeach
                </x-form-group>
            <x-form-submit>Сохранить</x-form-submit>
            </x-form>
            </div>
        </div>
        <h3 class="title my-4 mb-2">Открытые курсы</h3>
        @foreach($user->courses as $course)
            <div class="p-2">
            {{$course->course_caption}}
            @foreach($user->modules as $module)
                @if($module->course_id == $course->course_id)
                    <div class="p-2 pl-2">
                    {{$module->module_caption}}
                    @foreach($user->lessons as $lesson)
                        @if($lesson->module_id == $module->module_id)
                        <div class="p-2 pl-4">
                            {{$lesson->lesson_caption}}
                        </div>
                        @endif
                    </div>
                    @endforeach
                @endif
            @endforeach
            </div>
        @endforeach
    </div>
</main>

@endsection