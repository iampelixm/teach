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