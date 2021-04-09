@extends('layout.admin')

@section('content')
    <main>
        <div class="container">
            <h2 class="title text-center">Прохождение обучения учеником</h2>
            <x-form method="GET" id="filter">
                <div class="form-group">
                    <x-form-select name="student_id" onchange="$('#filter').submit()" label="Студент">
                        <option value="">Не выбран</option>
                        @foreach (App\Models\User::whereis('student')->get() as $student_list)
                            <option value="{{ $student_list->id }}"
                                {{ request()->student_id == $student_list->id ? 'selected' : '' }}>
                                {{ $student_list->name }}
                            </option>
                        @endforeach
                    </x-form-select>
                </div>
            </x-form>
        </div>
        @if ($student)
            <div class="container">
                <h1 class="title text-center">
                    {{ $student->name }}
                </h1>

                Доступные курс/модуль/занятие для студента
                @foreach ($student->courses as $student_course)
                    <div>
                        {{ $student_course->course_caption }}
                        @foreach ($student->course_modules($student_course->course_id)->get() as $student_course_module)
                            <div class="pl-2">
                                {{ $student_course_module->module_caption }}
                                @foreach ($student_course_module->availableLessons($student)->get() as $student_module_lesson)
                                    <div class="pl-2 mt-2 ">
                                        <div class="row">
                                            <div class="col">
                                                {{ $student_module_lesson->lesson_caption }}
                                            </div>
                                            @if(!$lesson_process->where('lesson_id', $student_module_lesson->lesson_id)->isEmpty())
                                            @foreach ($lesson_process->where('lesson_id', $student_module_lesson->lesson_id) as $process)
                                                <div class="col">
                                                    {{ $process->lesson_status }}
                                                </div>
                                                <div class="col">
                                                    {{ $process->updated_at }}
                                                </div>
                                            @endforeach
                                            @else
                                            <div class="col">не открывался</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif
    </main>
@endsection
