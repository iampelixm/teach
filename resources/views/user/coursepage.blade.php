@extends('layout.admin')

@section('content')
<main>
    <div class="container">
        <a href="/">Курсы</a> / 
        {{$course->course_caption}}     
        <h1 class="title text-center mt-2 mb-4">{{$course->course_caption}}</h1>
        <p style="font-size: 1.2rem">{!!$course->course_presc!!}</p>
        <h2 class="title text-center mt-3 d-none">Модули курса</h2>
        <div class="row">
        @if(!collect($course->availableModules)->isEmpty())
        
        @foreach($course->availableModules as $module)
        <div class="col-lg-4 p-2">
        @component('component.card', 
            [
                'title'=>$module->module_caption,
                'body'=>$module->module_presc,
                'link'=>'/module/'.$module->module_id,
                'class'=>'p-0'
            ])
        @endcomponent
        </div>
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