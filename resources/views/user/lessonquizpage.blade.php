@extends('layout.user')
@section('content')
<main>
    <div class="container">
        <a href="/">Курсы</a> / 
        <a href="/course/{{$modulelesson->module->course->course_id}}">{{$modulelesson->module->course->course_caption}}</a> /
        <a href="/module/{{$modulelesson->module->module_id}}">{{$modulelesson->module->module_caption}}</a>
        <h1 class="title text-center mt-2 mb-4">
            {{$modulelesson->lesson_caption}}. Тестирование
        </h1>
        <button class="btn btn-lg btn-success" onclick="$(this).hide(); buildQuiz('#lesson_quiz', lesson_quiz);">Пройти тест</button>
        <div id="lesson_quiz"></div>
    </div>
</main>
@endsection
@push('javascript')
<script src="/js/quiz.js"></script>
<script>
    var lesson_quiz={!!$modulelesson->lesson_quiz ?? ''!!};
    var quiz_link='/userlessonquiz';
    var quiz_data_append={
        'user_id': {{Auth::user()->id}},
        'lesson_id': {{$modulelesson->lesson_id}},
        '_token': '{{csrf_token()}}'
    }
</script>
@endpush

@push('css')
<link href="{{ asset('css/quiz.css') }}" rel="stylesheet">
@endpush