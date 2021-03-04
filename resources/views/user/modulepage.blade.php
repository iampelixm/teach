@extends('layout.user')

@section('content')
    <main>
        <div class="container">
            <a href="/">Курсы</a> /
            <a href="/course/{{ $coursemodule->course->course_id }}">{{ $coursemodule->course->course_caption }}</a> /
            {{ $coursemodule->module_caption }}
            <h1 class="title text-center mt-2 mb-4">
                {{ $coursemodule->module_caption }}
            </h1>
            <p>{{ $coursemodule->module_presc }}</p>
            <h2 class="title text-center mt-2 mb-4 d-none">Уроки модуля</h2>

            @if (!collect($coursemodule->doneLessons)->isEmpty())
                <div class="py-4">
                    <button class="btn btn-info mb-2" data-toggle="collapse" href="#done_lessons" role="button"
                        aria-expanded="false" aria-controls="done_lessons">Пройденные уроки</button>
                    <div id="done_lessons" class="collapse">
                        <h4>Пройденные уроки</h4>
                        @component('component.table', [
                            'items' => $coursemodule->notDoneLessons,
                            'captions' => ['lesson_caption' => 'Занятие', 'lesson_presc' => 'Описание'],
                            'link' => '/lesson/',
                            'link_item_key' => 'lesson_id',
                            ])
                        @endcomponent
                    </div>
                </div>
            @endif
            @if (!collect($coursemodule->notDoneLessons)->isEmpty())
                <h4>Доступные уроки</h4>
                @component('component.table', [
                    'items' => $coursemodule->notDoneLessons,
                    'captions' => ['lesson_caption' => 'Занятие', 'lesson_presc' => 'Описание'],
                    'link' => '/lesson/',
                    'link_item_key' => 'lesson_id',
                    ])
                @endcomponent
            @else
                @if (!collect($coursemodule->availableLessons)->isEmpty())
                    @component('component.alert', ['type' => 'warning'])
                        Вы прошли все уроки модуля
                    @endcomponent
                @else
                    @component('component.alert', ['type' => 'warning'])
                        Еще не созданы уроки
                    @endcomponent
                @endif
            @endif


        </div>
    </main>
@endsection
