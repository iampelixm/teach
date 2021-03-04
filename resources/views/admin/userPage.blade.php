@extends('layout.admin')

@section('content')
    <main>
        <div class="container">
            <h1 class="title text-center my-4">
                Пользователь <b>{{ $user->name }}</b>
            </h1>
            <div class="text-right">
                <a class="btn btn-danger" href="{{ route('admin.user.delete', ['user_id' => $user->id]) }}">Удалить</a>
            </div>
            <div class="row justify-content-center">

                <div class="col-lg-6">
                    <h3>Основные данные</h3>

                    <x-form action="/admin/user/update">
                        <x-form-input type="hidden" :bind="$user" name="id" />
                        <x-form-input :bind="$user" name="name" label="{{ __('Name') }}" />
                        <x-form-input :bind="$user" name="email" label="{{ __('Email') }}" />
                        <x-form-input name="password" label="{{ __('Password') }}" />
                        <x-form-submit>Сохранить</x-form-submit>
                    </x-form>
                </div>
                <div class="col-lg-6">
                    <h3>Роли пользователя</h3>
                    <x-form action="/admin/user/update">
                        <x-form-input type="hidden" :bind="$user" name="id" />
                        <x-form-group>
                            @foreach ($roles as $role)
                                @if ($user->isAn($role['name']))
                                    <x-form-checkbox id="{{ $role['name'] }}" name="roles[]" checked
                                        value="{{ $role['name'] }}" label="{{ $role['title'] }}" />
                                @else
                                    <x-form-checkbox bind:Role id="{{ $role['name'] }}" name="roles[]"
                                        value="{{ $role['name'] }}" label="{{ $role['title'] }}" />
                                @endif
                            @endforeach
                        </x-form-group>
                        <x-form-submit>Сохранить</x-form-submit>
                    </x-form>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <h3 class="title my-4 mb-2">Открытые курсы</h3>
                    <x-form action="/admin/user/updateLessonAccess">
                        <x-form-input :bind="$user" name="id" type="hidden" />
                        @foreach ($user->courses as $course)
                            <div class="">
                                <x-form-checkbox
                                    onclick="$('.course_'+$(this).val()).prop('checked',$(this).prop('checked'));"
                                    class="course course_{{ $course->course_id }}" checked name="courses[]"
                                    value="{{ $course->course_id }}" label="{{ $course->course_caption }}" />
                                @foreach ($user->modules as $module)
                                    @if ($module->course_id == $course->course_id)
                                        <div class="pt-2 pl-2">
                                            <x-form-checkbox
                                                onclick="$('.module_'+$(this).val()).prop('checked',$(this).prop('checked'));"
                                                class="module module_{{ $module->module_id }} course_{{ $course->course_id }}"
                                                checked name="modules[]" value="{{ $module->module_id }}"
                                                label="{{ $module->module_caption }}" />
                                            @foreach ($user->lessons as $lesson)
                                                @if ($lesson->module_id == $module->module_id)
                                                    <div class="pt-2 pl-2">
                                                        <x-form-checkbox
                                                            class="lesson module_{{ $module->module_id }} course_{{ $course->course_id }}"
                                                            checked name="lessons[]" value="{{ $lesson->lesson_id }}"
                                                            label="{{ $lesson->lesson_caption }}" />
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endforeach
                        <x-form-submit class="mt-4">Обновить</x-form-submit>
                    </x-form>
                </div>
                <div class="col-lg-6">
                    <h3 class="title my-4 mb-2">Все курсы</h3>
                    <x-form action="/admin/user/addLessonAccess">
                        <x-form-input :bind="$user" name="id" type="hidden" />
                        @foreach ($courses as $course)
                            <div class="">
                                <div class="form-inline">
                                    <b class="mr-2 btn btn-sm btn-info" data-toggle="collapse"
                                        data-target="#moduless_all_{{ $course->course_id }}" aria-expanded="false"
                                        aria-controls="moduless_all_{{ $course->course_id }}">+</b>
                                    <x-form-checkbox id="course_{{ $course->course_id }}_all"
                                        onclick="$('.course_'+$(this).val()).prop('checked',$(this).prop('checked'));"
                                        class="course course_{{ $course->course_id }}" name="courses[]"
                                        value="{{ $course->course_id }}" label="{{ $course->course_caption }}" />
                                </div>
                                @foreach ($course->modules as $module)
                                    <div id="moduless_all_{{ $course->course_id }}" class="collapse pt-2 pl-2">
                                        <div class="form-inline">
                                            <b class="mr-2 btn btn-sm btn-info" data-toggle="collapse"
                                                data-target="#lessons_{{ $module->module_id }}_all" aria-expanded="false"
                                                aria-controls="lessons_{{ $module->module_id }}_all">+</b>
                                            <x-form-checkbox id="module_{{ $module->module_id }}_all"
                                                onclick="$('.module_'+$(this).val()).prop('checked',$(this).prop('checked'));"
                                                class="module module_{{ $module->module_id }} course_{{ $course->course_id }}"
                                                name="modules[]" value="{{ $module->module_id }}"
                                                label="{{ $module->module_caption }}" />
                                        </div>
                                        <div id="lessons_{{ $module->module_id }}_all" class="collapse pt-2 pl-4">
                                            @foreach ($module->lessons as $lesson)

                                                <x-form-checkbox id="lesson_{{ $lesson->lesson_id }}_all"
                                                    is="lesson_{{ $lesson->lesson_id }}_all"
                                                    class="lesson module_{{ $module->module_id }} course_{{ $course->course_id }}"
                                                    name="lessons[]" value="{{ $lesson->lesson_id }}"
                                                    label="{{ $lesson->lesson_caption }}" />

                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                        <x-form-submit class="mt-4">Обновить</x-form-submit>
                    </x-form>
                </div>
            </div>
        </div>
    </main>

@endsection

@push('javascript')
    <script>


    </script>
@endpush
