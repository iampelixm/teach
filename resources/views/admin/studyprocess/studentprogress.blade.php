@extends('layout.admin')

@section('content')
    <main>
        <h1 class="title text-center">Прогресс {{ $student->name }}</h1>

        <div class="container">

            @foreach (App\Models\Course::all() as $student_course)
                @if ($student->hasCourseAccess($student_course))

                    <div class="pl-4 pt-4">
                        <h3 class="title">{{ $student_course->course_caption }}</h3>
                        <div class="pl-4">
                            @foreach ($student_course->modules as $course_module)
                                <div class="row pl-4">
                                    <div class="col">
                                        <h4>{{ $course_module->module_caption }}</h4>
                                        Всего: {{$course_module->lessons->count()}}
                                        Начато: {{ $student->moduleLessonsStatus($course_module)->where('lesson_status','opened')->get()->count() }}
                                        Пройдено: {{ $student->moduleLessonsStatus($course_module)->where('lesson_status','done')->get()->count() }}
                                        НЕ пройдено: {{ $student->moduleLessonsStatus($course_module)->where('lesson_status','<>', 'done')->get()->count() }}
                                    </div>
                                    <div class="container pt-2">
                                        @foreach ($course_module->lessons as $lesson)
                                            <div class="row pl-4">
                                                <div class="col">
                                                    {{ $lesson->lesson_caption }}
                                                </div>
                                                <div class="col">
                                                    {{ $lesson->status($student)->lesson_status }}
                                                </div>
                                                <div class="col">
                                                    {{ $lesson->status($student)->updated_at }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </main>
@endsection
