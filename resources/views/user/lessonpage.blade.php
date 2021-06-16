@extends('layout.user')

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

            @if (Auth::user()->isAn('su', 'coursemanager'))
                <div class="text-right py-2">
                    <a href="{{ route('admin.lesson.edit', $modulelesson) }}" class="btn btn-info">В редактор</a>
                </div>
            @endif
            @if (!collect($videos)->isEmpty())
                <div id="lesson_videos">
                    @foreach ($videos as $file_i => $file)
                        <video class="video-js vjs-fluid vjs-fill" data-setup='{}' controls style="width: 100%" playsinline>
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
                @if ($modulelesson->userAnswer && $modulelesson->userAnswer->answer_text)
                    <a class="btn btn-success" href="/lessontask/{{ $modulelesson->lesson_id }}">Перейти к заданию (уже
                        выполнено)</a>
                @else
                    <a class="btn btn-info" href="/lessontask/{{ $modulelesson->lesson_id }}">Перейти к заданию</a>
                @endif
            @endif
            @if (!$modulelesson->lesson_task || ($modulelesson->lesson_task && $modulelesson->userAnswer && $modulelesson->userAnswer->answer_text))
                @if ($modulelesson->lesson_quiz)
                    <a class="btn btn-info" href="/lessonquiz/{{ $modulelesson->lesson_id }}">Перейти к тесту</a>
                @endif
            @endif

            @if (!$modulelesson->isDone)
                @if ($modulelesson->lesson_task && $modulelesson->userAnswer && $modulelesson->userAnswer->answer_text != '')
                    <a class="btn btn-success"
                        href="{{ route('web.lesson.done', ['lesson_id' => $modulelesson->lesson_id]) }}">Завершить
                        урок</a>
                @endif
            @else
                @if ($next_lesson)
                    <a class="btn btn-outline-success" href="{{ route('web.lessonPage', $next_lesson->lesson_id) }}">
                        Далее: {{ $next_lesson->lesson_caption }}
                    </a>
                @else
                    @if($modulelesson->module->isDone())
                    <a class="btn btn-success" href="{{ route('web.module.endPage', $modulelesson->module) }}">
                        Завершить модуль
                    </a>
                    @else
                    У вас есть непройденое занятие
                    <a class="btn btn-success" href="{{ route('web.lessonPage', $modulelesson->module->notDoneAvailableLessons->first()) }}">
                        {{$modulelesson->module->notDoneAvailableLessons->first()->lesson_caption}}
                    </a>
                    @endif
                @endif
            @endif
        </div>
        Статус урока: {{ $modulelesson->status->lesson_status }}
    </main>
@endsection

@push('appjsload')
    $('oembed').each(function(ei, el)
    {
    let url=$(el).attr('url');
    url=url.replace(/^.*\//, '');
    url=url.replace(/^.*=/, '');
    let params={};
    params.video_id=url;
    var r=youtube_embed_template(params);
    $(r).insertAfter($(el).parent());
    });
@endpush
