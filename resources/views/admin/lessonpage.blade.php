@extends('layout.admin')

@section('content')
    <main>
        <div class="container">
            <a href="/admin">Главная</a> /
            <a
                href="/admin/courses/{{ $modulelesson->module->course->course_id }}">{{ $modulelesson->module->course->course_caption }}</a>
            /
            <a
                href="/admin/modules/{{ $modulelesson->module->module_id }}">{{ $modulelesson->module->module_caption }}</a>
            /
            {{ $modulelesson->lesson_caption }}
            <h1 class="title text-center my-4">
                Урок {{ $modulelesson->lesson_caption }}
            </h1>
            <div class="text-right">
                <a class="btn btn-info" href="{{ route('web.lessonPage', ['lesson_id' => $modulelesson->lesson_id]) }}">
                    Смотреть
                </a>
            </div>
            <h3 class="title mt-4">Видео материалы</h3>
            <div id="lesson_videos">

                @foreach ($videos as $file_i => $file)
                    <div class="border mt-1">
                        <a class="btn btn-outline-danger" href="/admin/lessons/deletefile?file={{ $file }}">X</a>
                        <button class="btn btn-outline-success" data-role="dialog" data-dialog="videoplayer"
                            data-data='{"video":"{{ Storage::url($file) }}"}'> &#9658;</button>

                        <button class="btn btn-outline-warning" data-toggle="collapse" href="#{{ Str::slug($file, '_') }}"
                            role="button" aria-expanded="false"
                            aria-controls="{{ Str::slug($file, '_') }}">Обрезать</button>
                        {{ collect(explode('/', $file))->last() }}
                        {{-- длит.: {{ FFMpeg::open($file)->getDurationInSeconds() }} сек. --}}
                        <div class="collapse" id="{{ Str::slug($file, '_') }}">

                            <x-form class="form-inline" action="{{ route('admin.video.trim') }}">
                                <x-form-input type="hidden" name="video" value="{{ $file }}" />
                                <x-form-input value="0" min="0" step=".01" type="number" name="start"
                                    label="От начала (секунд)" />
                                <x-form-input value="0" min="0" step=".01" type="number" name="end"
                                    label="От конца (секунд)" />
                                <x-form-submit>Обрезать</x-form-submit>
                            </x-form>
                        </div>
                    </div>
                @endforeach
                <x-form action="/admin/lessons/upload" enctype="multipart/form-data">
                    <x-form-input type="file" name="file" accept="video/*" />
                    <x-form-input type="hidden" name="type" value="video" />
                    <x-form-input type="hidden" name="lesson_id" value="{{ $modulelesson->lesson_id }}" />
                    <x-form-submit>Загрузить</x-form-submit>
                </x-form>

            </div>

            <div id="lesson_documents">
                <h3 class="title">Дополнительные материалы</h3>
                @foreach ($documents as $file_i => $file)
                    <div class="border mt-1">
                        <a class="btn btn-outline-danger btn-sm"
                            href="/admin/lessons/deletefile?file={{ $file }}">X</a>
                        {{ collect(explode('/', $file))->last() }}
                    </div>
                @endforeach
                <x-form action="/admin/lessons/upload" enctype="multipart/form-data">
                    <x-form-input type="file" name="file" />
                    <x-form-input type="hidden" name="type" value="document" />
                    <x-form-input type="hidden" name="lesson_id" value="{{ $modulelesson->lesson_id }}" />
                    <x-form-submit>Загрузить</x-form-submit>
                </x-form>
            </div>
            <div id="lesson_form">
                @component('component.formLesson', ['lesson' => $modulelesson])
                @endcomponent
            </div>
            <div class="text-right">
                <button class="btn btn-danger ajaxyesno" data-role="dialog" data-requesttype="post" data-dialog="yesno"
                    data-href="/admin/lessons/delete" data-action="ajax"
                    data-data='{"lesson_id": "{{ $modulelesson->lesson_id }}"}' data-title="Удалить урок?"
                    data-message="Точно удалить это занятие? Это действие необратимо." data-success="">
                    Удалить
                </button>
            </div>
            <h3 class="title mt-4">Опросник</h3>
            <div id="quiz_builder_wrapper" class="collapse">
                <div id="quiz_builder_container">
                </div>
                <button class="btn btn-success" onclick="quizBuilderAddQuestion('#quiz_builder_container');">Добавить
                    вопрос</button>
                <button class="btn btn-info"
                    onclick="saveQuiz('#quiz_builder_container', '/admin/lessons/update');">Сохранить</button>
                <button class="btn btn-warning"
                    onclick="buildQuiz('#quiz_out', buildQuizData('#quiz_builder_container'))">Смотреть</button>
            </div>
            <div id="quiz_out"></div>

            <button class="btn btn-success" data-toggle="collapse" data-target="#quiz_builder_wrapper" aria-expanded="false"
                aria-controls="quiz_builder_wrapper"
                onclick="$(this).hide(); quizBuilderLoadQuiz('#quiz_builder_container', {{ $modulelesson->lesson_quiz ?? '' }});">Загрузить
                квиз
            </button>
            @if ($modulelesson->lesson_quiz)
                <button class="btn btn-danger ajaxyesno" data-role="dialog" data-requesttype="post" data-dialog="yesno"
                    data-href="/admin/lessons/update" data-action="ajax"
                    data-data='{"lesson_id": "{{ $modulelesson->lesson_id }}", "lesson_quiz": ""}'
                    data-title="Удалить квиз?" data-message="Квиз делать долго, точно нужно удалить?" data-success="">
                    Удалить
                </button>
            @endif
        </div>
    </main>
@endsection

@push('javascript')

    <script src="/js/quiz_builder.js"></script>
    <script src="/js/quiz.js"></script>
    <script>
        function saveQuiz(container, link) {
            var quizdata = buildQuizData(container);
            console.log('sqving', quizdata);
            var fdata = new FormData();
            fdata.append('lesson_id', '{{ $modulelesson->lesson_id }}');
            fdata.append('lesson_quiz', quizdata);

            fdata = {};
            fdata.lesson_id = '{{ $modulelesson->lesson_id }}';
            fdata.lesson_quiz = quizdata;
            fdata._token = "{{ csrf_token() }}";
            $.post(
                link,
                fdata,
                function(response) {
                    //console.log(response);
                },
                ''
            ).fail(function(resp) {
                console.log(resp)
            });
        }

    </script>
@endpush

@push('css')
    <link href="{{ asset('css/quiz.css') }}" rel="stylesheet">
@endpush
