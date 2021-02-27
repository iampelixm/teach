@extends('layout.admin')

@push('css')
    <link href="/css/plyr.css" rel="stylesheet">
@endpush
@section('content')
    <main>
        <div class="container">
            <a href="/">Курсы</a> /
            <a
                href="/course/{{ $modulelesson->module->course->course_id }}">{{ $modulelesson->module->course->course_caption }}</a>
            /
            <a href="/module/{{ $modulelesson->module->module_id }}">{{ $modulelesson->module->module_caption }}</a>
            <h1 class="title text-center mt-2 mb-4">
                {{ $modulelesson->lesson_caption }}
            </h1>

            @if (!collect($videos)->isEmpty())
                <div id="lesson_videos">
                    @foreach ($videos as $file_i => $file)
                        <video class="video-js" data-setup='{}' controls style="width: 100%" playsinline>
                            <source src="{{ Storage::url($file) }}" type="{{ Storage::mimeType($file) }}">
                        </video>
                    @endforeach
                </div>
            @endif
            <div class="lesson_content my-4">
                {!! $modulelesson->lesson_text !!}
            </div>
            @if (!collect($documents)->isEmpty())
                <div id="lesson_documents">
                    <h3 class="title">Дополнительные материалы</h3>
                    @foreach ($documents as $file_i => $file)
                        <div class="border mt-1">
                            <a
                                href="/file/download?file={{ $file }}">{{ collect(explode('/', $file))->last() }}</a>
                        </div>
                    @endforeach
                </div>
            @endif
            @if ($modulelesson->lesson_task)
                <a class="btn btn-info" href="/lessontask/{{ $modulelesson->lesson_id }}">Перейти к заданию</a>
            @endif
            @if ($modulelesson->lesson_quiz)
                <a class="btn btn-info" href="/lessonquiz/{{ $modulelesson->lesson_id }}">Перейти к тесту</a>
            @endif
        </div>
    </main>
@endsection

@push('javascript')
    {{-- <script src="/js/plyr.min.js"></script>
    <script>
        //const players = Plyr.setup('video');
        const players = videojs('video');

    </script> --}}
@endpush
