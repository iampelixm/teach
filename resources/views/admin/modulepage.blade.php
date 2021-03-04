@extends('layout.admin')

@section('content')
    <main>
        <div class="container">
            <a href="/admin">Главная</a> /
            <a
                href="/admin/courses/{{ $coursemodule->course->course_id }}">{{ $coursemodule->course->course_caption }}</a>
            /
            {{ $coursemodule->module_caption ?? '' }}
            <h1 class="title text-center">
                Модуль {{ $coursemodule->module_caption }}
            </h1>
            <button class="btn btn-info" data-toggle="collapse" data-target="#coursemodule_form" aria-expanded="false"
                aria-controls="coursemodule_form">Описние</button>
            <button class="btn btn-info ml-2" data-toggle="collapse" data-target="#lesson_form" aria-expanded="false"
                aria-controls="lesson_form">Добавить урок</button>
            <div id="coursemodule_form" class="collapse">
                @component('component.formCourseModule', ['coursemodule' => $coursemodule ?? ''])
                @endcomponent
            </div>
            <div id="lesson_form" class="collapse">
                @component('component.formLesson', ['lesson' => collect(['module_id' => $coursemodule->module_id ?? ''])])
                @endcomponent
            </div>
            <div class="text-right">
                <button class="btn btn-danger ajaxyesno" data-role="dialog" data-requesttype="post" data-dialog="yesno"
                    data-href="/admin/modules/delete" data-action="ajax"
                    data-data='{"module_id": "{{ $coursemodule->module_id }}"}' data-title="Удалить модуль?"
                    data-message="Точно удалить этот модуль? Это действие необратимо и приведет к удалению всех занятий модуля"
                    data-success="">
                    Удалить
                </button>
            </div>
            <h2 class="title text-center mt-3">Уроки модуля</h2>
            @if (!collect($coursemodule->lessons)->isEmpty())
                @component('component.table', [
                    'items' => $coursemodule->lessons,
                    'captions' => ['lesson_order' => 'Порядок', 'lesson_caption' => 'Занятие', 'lesson_presc' => 'Описание'],
                    'link' => '/admin/lessons/',
                    'link_item_key' => 'lesson_id',
                    ])
                @endcomponent
            @else
                @component('component.alert', ['type' => 'warning'])
                    Еще не созданы уроки
                @endcomponent
            @endif

        </div>
    </main>
@endsection

@push('javascript')
    <script>
        appjs.addEventListener('load', function(e) {
            $("tbody").sortable({
                items: "> tr",
                appendTo: "parent",
                helper: "clone",
                update: function(event, ui) {
                    var order = [];
                    $(this).find('tr').each(function(i, el) {
                        order.push({
                            "lesson_id": $(el).data('id')
                        });
                    });
                    console.log(order);
                    $.post(
                        '{{ route('admin.modules.setLessonOrder') }}', {
                            '_token': '{{ csrf_token() }}',
                            'module_id': {{ $coursemodule->module_id }},
                            'order': order
                        },
                        function(response) {
                            console.log(response);
                        }
                    ).
                    fail(function(response) {
                        console.log('fail');
                    })
                }
            });
        }, false);

    </script>
@endpush
