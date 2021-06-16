@extends('layout.admin')

@section('content')
    <main>
        <h1 class="title text-center">Прогресс учеников</h1>

        <div class="container">
            @foreach (App\Models\User::whereis('student')->get() as $student)
                <h3 class="title text-center mt-4">{{ $student->name }}</h3>
                <div>Курсы, к которым у ученика предотавлен доступ</div>
                <a href="{{route('admin.studyprocess.studentprogress', $student)}}">Подробный отчет</a>
                @foreach ($student->courses as $student_course)
                    <div class="pl-4 mt-2">
                        <h4>{{ $student_course->course_caption }}</h4>
                        <div class="pl-4">
                            @foreach ($student->course_modules($student_course->course_id)->orderBy('module_order', 'ASC')->get()
        as $course_module)
                                <div class="row pl-4 mt-2 ">
                                    <div class="col">
                                        {{ $course_module->module_caption }}
                                    </div>
                                    <div class="col">
                                        Пройдено: {{ $course_module->doneLessons($student)->get()->count() }}
                                    </div>
                                    <div class="col">
                                        НЕ пройдено: {{ $course_module->notDoneLessons($student)->get()->count() }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </main>
@endsection
