@extends('layout.admin')

@section('content')
<main>
    <div class="container">
        <a href="/">Курсы</a> / 
        <a href="/course/{{$coursemodule->course->course_id}}">{{$coursemodule->course->course_caption}}</a> /
        {{$coursemodule->module_caption}}       
        <h1 class="title text-center mt-2 mb-4">
            {{$coursemodule->module_caption}}
        </h1>
        <p>{{$coursemodule->module_presc}}</p>
        <h2 class="title text-center mt-2 mb-4 d-none">Уроки модуля</h2>
        @if(!collect($coursemodule->lessons)->isEmpty())
            @component('component.table', 
                [
                    'items'=>$coursemodule->lessons,
                    'captions'=>['lesson_caption'=>'Занятие', 'lesson_presc'=>'Описание'],
                    'link'=>'/lesson/',
                    'link_item_key'=>'lesson_id'
                ])
            @endcomponent        
        @else
            @component('component.alert', ['type'=>'warning'])
                Еще не созданы уроки
            @endcomponent
        @endif

    </div>
</main>
@endsection