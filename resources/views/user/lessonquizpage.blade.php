@extends('layout.user')
@section('content')
    <main>
        <div class="container">
            <a href="/">Курсы</a> /
            <a
                href="/course/{{ $modulelesson->module->course->course_id }}">{{ $modulelesson->module->course->course_caption }}</a>
            /
            <a href="/module/{{ $modulelesson->module->module_id }}">{{ $modulelesson->module->module_caption }}</a> /
            <a href="/lesson/{{ $modulelesson->lesson_id }}">{{ $modulelesson->lesson_caption }}</a>
            <h1 class="title text-center mt-2 mb-4">
                {{ $modulelesson->lesson_caption }}. Тестирование
            </h1>
            @if ($modulelesson->userAnswer)
                @if ($modulelesson->userAnswer->answer_quiz)
                    <div class="alert alert-info">Вы уже сдавали этот тест. Повторная сдача аннулирует предыдущий результат.
                        Не сдали тест? Смело пробуйте снова!
                    </div>
                @endif
            @endif
            <div id="lesson_quiz"></div>
            @if ($modulelesson->lesson_task)
                @if (!$modulelesson->userAnswer || !$modulelesson->userAnswer->answer_text)
                    <a class="btn btn-info"
                        href="{{ route('web.lessonTask', ['lesson_id' => $modulelesson->lesson_id]) }}">Завершить
                        урок</a>
                @else
                    <a class="btn btn-success"
                        href="{{ route('web.lesson.done', ['lesson_id' => $modulelesson->lesson_id]) }}">Завершить
                        урок</a>
                @endif
            @else
                <a class="btn btn-success"
                    href="{{ route('web.lesson.done', ['lesson_id' => $modulelesson->lesson_id]) }}">Завершить
                    урок</a>
            @endif

        </div>
    </main>
@endsection
@push('javascript')
    <script src="/js/quiz.js"></script>
    <script>
        var lesson_quiz = {!! $modulelesson->lesson_quiz ?? '' !!};
        var quiz_link = '/userlessonquiz';
        var quiz_data_append = {
            'user_id': {{ Auth::user()->id }},
            'lesson_id': {{ $modulelesson->lesson_id }},
            '_token': '{{ csrf_token() }}'
        }

        appjs.addEventListener('load', function(e) {
            buildQuiz('#lesson_quiz', lesson_quiz);
        }, false);

    </script>
@endpush

@push('css')
    <link href="{{ asset('css/quiz.css') }}" rel="stylesheet">
@endpush
