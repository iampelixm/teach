@extends('layout.admin')

@section('content')
    <main>
        <div class="container">
            <a href="/admin">Главная</a> 
            <h1 class="title text-center">Курс {{ $course->course_caption }}</h1>
            <button class="btn btn-info" data-toggle="collapse" data-target="#course_form" aria-expanded="false"
                aria-controls="course_form">Описние</button>
            <button class="btn btn-info ml-2" data-toggle="collapse" data-target="#module_form" aria-expanded="false"
                aria-controls="module_form">Добавить модуль</button>
            <div id="course_form" class="collapse">
                @component('component.formCourse', ['course' => $course ?? ''])
                @endcomponent
            </div>
            <div id="module_form" class="collapse">
                @component('component.formCourseModule', ['coursemodule' => collect(['course_id' => $course->course_id ??
                    ''])])
                @endcomponent
            </div>
            <div class="text-right">
                <button class="btn btn-danger ajaxyesno" data-role="dialog" data-requesttype="post" data-dialog="yesno"
                    data-href="/admin/courses/delete" data-action="ajax"
                    data-data='{"course_id": "{{ $course->course_id }}"}' data-title="Удалить курс?"
                    data-message="Точно удалить этот курс? Это действие необратимо."
                    data-success="">
                    Удалить
                </button>
            </div>
            <h2 class="title text-center mt-3">Модули курса</h2>
            @if (!collect($course->modules)->isEmpty())
                @component('component.table', [
                    'items' => $course->modules,
                    'show_fields' => ['module_caption', 'module_presc'],
                    'captions' => ['module_caption' => 'Название модуля', 'module_presc' => 'Описание модуля', 'module_order'=>'#'],
                    'link' => '/admin/modules/',
                    'link_item_key' => 'module_id',
                    ])
                @endcomponent
            @else
                @component('component.alert', ['type' => 'warning'])
                    Еще не созданы модули
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
                            "module_id": $(el).data('id')
                        });
                    });
                    console.log(order);
                    $.post(
                        '{{ route('admin.courses.setModuleOrder') }}', {
                            '_token': '{{ csrf_token() }}',
                            'course_id': {{ $course->course_id }},
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