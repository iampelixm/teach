@extends('layout.user')

@section('content')
    <main>
        <div class="container">
            @if($module->isDone())
            <h2 class="title text-center mt-2 mb-0">Поздравляем!</h2>
            <h4 class="title text-center">Вы успешно завершили модуль {{ $module->module_caption }}</h4>
            @else
                <h2 class="title text-center mt-2 mb-0">Модуль не завершен полностью</h2>
            @endif
            <h4 class="title pt-4">Вот как вы прошли этот модуль:</h4>
            @foreach ($module->lessons as $lesson)
                <div class="pt-3 border-bottom {{ !$lesson->checkDone() ? 'bg-warning' : '' }}">
                    <a href="{{ route('web.lessonPage', $lesson) }}">{{ $lesson->lesson_caption }}</a>
                    @if ($lesson->lesson_task)
                        <a href="{{ route('web.lessonTask', $lesson) }}">Посмотреть задание</a>
                    @endif
                    @if ($lesson->lesson_quiz)
                        <a href="{{ route('web.lessonQuiz', $lesson) }}">Посмотреть тест</a>
                    @endif

                    @if (!$lesson->checkDone())
                        Выполните задание урока
                    @endif
                </div>
            @endforeach
            @if ($lesson->module->isDone())
                @if ($lesson->module->course->availableModules->where('module_order', '>', $lesson->module->module_order)->first())
                    <a class="btn btn-success"
                        href="{{ route('web.module.page', $lesson->module->course->availableModules->where('module_order', '>', $lesson->module->module_order)->first()) }}">
                        К следующему модулю
                    </a>
                @else
                    <h1>Вы полностью завершили курс {{ $lesson->module->course->course_caption }}</h1>

                @endif
            @else
                <div class="alert alert-danger">Вам необходимо завершить все задания текущего модуля, что бы перейти к
                    следующему</div>
            @endif
        </div>
    </main>
@endsection
