@extends('layout.admin')

@section('content')
<main>
    <div class="container">
        <h1 class="title text-center">Все курсы</h1>        
        @component('component.table', 
            [
                'items'=>$users,
                //'captions'=>['course_caption'=>'Название курса','course_presc'=>'Описание курса'],
                'link'=>'/admin/user/',
                'link_item_key'=>'id'])
        @endcomponent
    </div>
</main>
@endsection