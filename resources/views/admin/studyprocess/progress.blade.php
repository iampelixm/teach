@extends('layout.admin')

@section('content')
    <main>
        <h1 class="title text-center">Прогресс учеников</h1>

        <div class="container">
            @foreach (App\Models\User::whereis('student')->get() as $student)
                <h6>{{ $student->name }}</h6>
                @foreach ($student->courses as $student_course)
                    <div class="pl-4">
                        Курс:{{ $student_course->course_caption }}
                        <div class="pl-4">
                            @foreach ($student->course_modules($student_course->course_id)->orderBy('module_order', 'ASC')->get() as $course_module)
                                <div class="row pl-4">
                                    <div class="col">
                                        Модуль: {{ $course_module->module_caption }}
                                    </div>
                                    <div class="col">
                                        Пройдено: {{ $course_module->doneLessons($student)->get() }}
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
