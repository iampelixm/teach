@extends('layout.admin')

@section('content')
<main>
    <div class="container">
        <h1 class="title text-center">Курс {{$course->course_caption}}</h1>
        <p>{!!$course->course_presc!!}</p>
        <h2 class="title text-center mt-3">Модули курса</h2>
        @if(!collect($course->modules)->isEmpty())
            @component('component.table', 
                [
                    'items'=>$course->modules,
                    'captions'=> ['module_caption'=>'Название модуля', 'module_presc'=>'Описание модуля'],
                    'link'=>'/module/',
                    'link_item_key'=>'module_id'
                ])
            @endcomponent        
        @else
            @component('component.alert', ['type'=>'warning'])
                Еще не созданы модули
            @endcomponent
        @endif

    </div>
</main>
@endsection