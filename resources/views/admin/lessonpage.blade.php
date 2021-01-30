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
        
        <div id="quiz_builder_container">
        </div>
        <button class="btn btn-lg btn-success" onclick="addQuizBuilder('#quiz_builder_container'); $(this).hide()">Добавить квиз</button>
        <div id="lesson_videos">
            <h3 class="title">Видео материалы</h3>
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


    </div>
</main>

@endsection

@push('javascript')
<script src="/js/quiz_builder.js"></script>
<script>
    function abuildQuizData(container)
    {
        $.fn.serializeObject = function()
        {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
        };
        var quiz_data=[];      
        $(container).find('form').each(function(form_i, form)
        {
            quiz_data.push($(form).serializeObject());
        });

        console.log(quiz_data);
    }
</script>
@endpush