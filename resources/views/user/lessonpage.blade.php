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
        <h1 class="title text-center my-4">
            {{$modulelesson->lesson_caption}}
        </h1>
        
        @if(!collect($videos)->isEmpty())
        <div id="lesson_videos">
            @foreach($videos as $file_i=>$file)
            <video controls style="width: 100%" class="vpl" playsinline>
                <source src="/file/get?file={{$file}}" type="video/mp4">
            </video>
            @endforeach
        </div>
        @endif
        <div class="lesson_content border my-4">
            {!!$modulelesson->lesson_text!!}
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