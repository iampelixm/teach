@extends('layout.admin')

@section('content')
<main>
    <div class="container">
        <a href="/admin">Главная</a> / 
        <a href="/admin/courses/{{$modulelesson->module->course->course_id}}">{{$modulelesson->module->course->course_caption}}</a> /
        <a href="/admin/modules/{{$modulelesson->module->module_id}}">{{$modulelesson->module->module_caption}}</a> /
        {{$modulelesson->lesson_caption}}
        <h1 class="title text-center my-4">
            Занятие {{$modulelesson->lesson_caption}}
        </h1>
        
        <h3 class="title mt-4">Видео материалы</h3>
        <div id="lesson_videos">
            
            @foreach($videos as $file_i=>$file)
            <div class="border mt-1">
                <a class="btn btn-outline-danger btn-sm" href="/admin/lessons/deletefile?file={{$file}}">X</a>
                {{collect(explode('/',$file))->last()}}
            </div>
            @endforeach
            <x-form action="/admin/lessons/upload" enctype="multipart/form-data">
                <x-form-input type="file" name="file" accept="video/*"/>
                <x-form-input type="hidden" name="type" value="video"/>
                <x-form-input type="hidden" name="lesson_id" value="{{$modulelesson->lesson_id}}"/>                
                <x-form-submit>Загрузить</x-form-submit>
            </x-form>

        </div>

        <div id="lesson_documents">
            <h3 class="title">Дополнительные материалы</h3>
            @foreach($documents as $file_i=>$file)
            <div class="border mt-1">
                <a class="btn btn-outline-danger btn-sm" href="/admin/lessons/deletefile?file={{$file}}">X</a>
                {{collect(explode('/',$file))->last()}}
            </div>
            @endforeach
            <x-form action="/admin/lessons/upload" enctype="multipart/form-data">
                <x-form-input type="file" name="file"/>
                <x-form-input type="hidden" name="type" value="document"/>
                <x-form-input type="hidden" name="lesson_id" value="{{$modulelesson->lesson_id}}"/>
                <x-form-submit>Загрузить</x-form-submit>
            </x-form>            
        </div>        
        <div id="lesson_form">
            @component('component.formLesson', ['lesson'=>$modulelesson])
            @endcomponent 
        </div>
        <h3 class="title mt-4">Опросник</h3>
        <div id="quiz_builder_wrapper" class="collapse">
            <div id="quiz_builder_container">
            </div>
            <button class="btn btn-success" onclick="quizBuilderAddQuestion('#quiz_builder_container');">Добавить вопрос</button>
            <button class="btn btn-info" onclick="saveQuiz('#quiz_builder_container', '/admin/lessons/update');">Сохранить</button>
            <button class="btn btn-warning" onclick="buildQuiz(buildQuizData('#quiz_builder_container'), '#quiz_out',)">Смотреть</button>
        </div>
        <button class="btn btn-success" data-toggle="collapse" data-target="#quiz_builder_wrapper" aria-expanded="false" aria-controls="quiz_builder_wrapper" onclick="$(this).hide(); quizBuilderLoadQuiz('#quiz_builder_container', {{$modulelesson->lesson_quiz ?? ''}});">Загрузить квиз</button>
        <div id="quiz_out"></div>
    </div>
</main>
@endsection

@push('javascript')
<script src="/js/quiz_builder.js"></script>
<script src="/js/quiz.js"></script>
<script>
function saveQuiz(container, link)
{
    var quizdata=buildQuizData(container);
    console.log('sqving', quizdata);
    var fdata=new FormData();
    fdata.append('lesson_id','{{$modulelesson->lesson_id}}');
    fdata.append('lesson_quiz',quizdata);

    fdata={};
    fdata.lesson_id='{{$modulelesson->lesson_id}}';
    fdata.lesson_quiz=quizdata;
    fdata._token="{{csrf_token()}}";
    $.post(
        link,
        fdata,
        function(response){
            //console.log(response);
        },
        ''
    ).fail(function(resp){console.log(resp)});
}
</script>
@endpush

@push('css')
<link href="{{ asset('css/quiz.css') }}" rel="stylesheet">
@endpush