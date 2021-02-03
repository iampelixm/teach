@extends('layout.admin')

@push('css')
<link href="/css/plyr.css" rel="stylesheet">
@endpush
@section('content')
<main>
    <div class="container">
        <a href="/">Курсы</a> / 
        <a href="/course/{{$modulelesson->module->course->course_id}}">{{$modulelesson->module->course->course_caption}}</a> /
        <a href="/module/{{$modulelesson->module->module_id}}">{{$modulelesson->module->module_caption}}</a>
        <h1 class="title text-center mt-2 mb-4">
            {{$modulelesson->lesson_caption}}. Задание
        </h1>
        
        <div class="lesson_content border my-4">
            {!!$modulelesson->lesson_task!!}
        </div>
        @if(!collect($documents)->isEmpty())
        <div id="lesson_documents">
            <h3 class="title">Дополнительные материалы</h3>
            @foreach($documents as $file_i=>$file)
            <div class="border mt-1">
                <a href="/file/download?file={{$file}}">{{collect(explode('/',$file))->last()}}</a>
            </div>
            @endforeach      
        </div>
        @endif
    </div>
</main>
<script src="/js/plyr.min.js"></script>
<script>
const players = Plyr.setup('video');
</script>
@endsection