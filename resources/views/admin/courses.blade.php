@extends('layout.admin')

@section('content')
<main>
    <div class="container">
        <h1 class="title text-center">Все курсы</h1>        
        @component('component.table', 
            [
                'items'=>$courses,
                'captions'=>['course_caption'=>'Название курса','course_presc'=>'Описание курса'],
                'link'=>'/admin/courses/',
                'link_item_key'=>'course_id'])
        @endcomponent
    </div>
</main>
@endsection