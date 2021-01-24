@extends('layout.admin')

@section('content')
<main>
    <div class="container">
        <a href="/">Курсы</a> / 
        {{$course->course_caption}}     
        <h1 class="title text-center mb-4">{{$course->course_caption}}</h1>
        <p style="font-size: 1.2rem">{!!$course->course_presc!!}</p>
        <h2 class="title text-center mt-3 d-none">Модули курса</h2>
        <div class="row">
        @if(!collect($course->modules)->isEmpty())
        @foreach($course->modules as $module)
        @component('component.card', 
            [
                'title'=>$module->module_caption,
                'body'=>$module->module_presc,
                'link'=>'/module/'.$module->module_id,
                'class'=>'col-lg-3 p-0 m-2'
            ])
        @endcomponent
        @endforeach
        {{--}}        
            @component('component.table', 
                [
                    'items'=>$course->modules,
                    'captions'=> ['module_caption'=>'Название модуля', 'module_presc'=>'Описание модуля'],
                    'link'=>'/module/',
                    'link_item_key'=>'module_id'
                ])
            @endcomponent
        --}}                    
        @else
            @component('component.alert', ['type'=>'warning'])
                Еще не созданы модули
            @endcomponent
        @endif
        </div>

    </div>
</main>
@endsection