@extends('layout.admin')

@section('content')
<main>
    <div class="container">
        <h1 class="title text-center">Все курсы</h1>
        <div class="row">
        @foreach($courses as $course)
        @component('component.card', 
            [
                'title'=>$course->course_caption,
                'body'=>$course->course_presc,
                'link'=>'/course/'.$course->course_id,
                'class'=>'col-lg-3 p-0 m-2'
            ])
        @endcomponent
        @endforeach        
        </div>
        {{--        
        @component('component.table', 
            [
                'items'=>$courses,
                'captions'=>['course_caption'=>'Название курса','course_presc'=>'Описание курса'],
                'link'=>'/course/',
                'link_item_key'=>'course_id'])
        @endcomponent
        --}}
    </div>
</main>
@endsection