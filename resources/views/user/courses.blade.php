@extends('layout.user')

@section('content')
    <main>
        <div class="container">
            <h1 class="title text-center mt-2 mb-4">Обучение SeVen Realty</h1>
            <div class="row">
                @if (!collect($courses)->isEmpty())
                    @foreach ($courses as $course)
                        <div class="col-lg-6 p-2">
                            @component('component.card', [
                                'title' => $course->course_caption,
                                'body' => $course->course_presc,
                                'link' => '/course/' . $course->course_id,
                                'class' => 'p-0',
                                ])
                            @endcomponent
                        </div>
                    @endforeach
            
                @else
                    <div class="alert">
                        У вас пока нет доступных курсов обучения.
                    </div>
                    @endif
            </div>
            {{-- @component('component.table', [
    'items' => $courses,
    'captions' => ['course_caption' => 'Название курса', 'course_presc' => 'Описание курса'],
    'link' => '/course/',
    'link_item_key' => 'course_id',
])
        @endcomponent --}}
        </div>
    </main>
@endsection
