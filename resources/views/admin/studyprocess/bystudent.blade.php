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
                                                <div>
                                                    <a href="{{ route('web.lessonPage', $student_module_lesson) }}">
                                                        {{ $student_module_lesson->lesson_caption }}
                                                    </a>
                                                </div>
                                                @if ($student_module_lesson->lesson_task)
                                                    <a href="{{ route('web.lessonTask', $student_module_lesson) }}"
                                                        class="btn btn-sm btn-success">
                                                        Задание
                                                    </a>
                                                @endif

                                                @if ($student_module_lesson->lesson_quiz)
                                                    <a href="{{ route('web.lessonQuiz', $student_module_lesson) }}"
                                                        class="btn btn-sm btn-success">
                                                        Тест
                                                    </a>
                                                @endif
                                            </div>
                                            @if (!$lesson_process->where('lesson_id', $student_module_lesson->lesson_id)->isEmpty())
                                            @if($lesson_process->where('lesson_id', $student_module_lesson->lesson_id)->count() > 1)
                                                @while ($lesson_process->where('lesson_id', $student_module_lesson->lesson_id)->count()>1)
                                                    {{$lesson_process->where('lesson_id', $student_module_lesson->lesson_id)->first()->delete()}}    
                                                @endwhile
                                            @endif
                                                @foreach ($lesson_process->where('lesson_id', $student_module_lesson->lesson_id) as $process)
                                                    <div class="col">
                                                        {{ $process->lesson_status }}
                                                    </div>
                                                    <div class="col">
                                                        {{ $process->updated_at->format('d.m.Y H:s') }}
                                                    </div>
                                                    <div class="col">
                                                        @if($student_module_lesson->userAnswer($student->id)->get()->count() > 1 )
                                                            @while($student_module_lesson->userAnswer($student->id)->get()->count() > 1)
                                                                {{$student_module_lesson->userAnswer($student->id)->first()->delete()}}
                                                            @endwhile
                                                        @endif
                                                        @if (!$student_module_lesson->userAnswer($student->id)->get()->isEmpty())

                                                            @if ($student_module_lesson->userAnswer($student->id)->first()->answer_text)
                                                                <button class="btn btn-outline-success mr-2"
                                                                    data-toggle="modal"
                                                                    href="#lesson_answer_text_{{ $student_module_lesson->userAnswer($student->id)->first()->answer_id }}"
                                                                    role="button" aria-expanded="false"
                                                                    aria-controls="lesson_answer_text_{{ $student_module_lesson->userAnswer($student->id)->first()->answer_id }}">A</button>
                                                                
                                                                @component('component.modal', ['id' => 'lesson_answer_text_' . $student_module_lesson->userAnswer($student->id)->first()->answer_id, 'title' => $student->name])
                                                                    {!! $student_module_lesson->userAnswer($student->id)->first()->answer_text !!}
                                                                @endcomponent
                                                            @endif
                                                            @if ($student_module_lesson->userAnswer($student->id)->first()->answer_quiz && json_decode($student_module_lesson->userAnswer($student->id)->first()->answer_quiz))
                                                                <button class="btn btn-outline-success" data-toggle="modal"
                                                                    href="#lesson_answer_quiz_{{ $student_module_lesson->userAnswer($student->id)->first()->answer_id }}"
                                                                    role="button" aria-expanded="false"
                                                                    aria-controls="lesson_answer_quiz_{{ $student_module_lesson->userAnswer($student->id)->first()->answer_id }}">Q</button>
                                                                
                                                                @component('component.modal', ['id' => 'lesson_answer_quiz_' . $student_module_lesson->userAnswer($student->id)->first()->answer_id, 'title' => $student->name])
                                                                    @component('component.quiz_answer', ['quiz' => $student_module_lesson->userAnswer($student->id)->first()->answer_quiz, 'lesson_id' => $student_module_lesson->lesson_id, 'user_id' => $student->id])
                                                                    @endcomponent
                                                                @endcomponent
                                                            @endif 

                                                        @endif
                                                        
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

                @foreach (App\Models\Course::where('is_access_listed', 0)->get() as $public_course)
                    {{ $public_course->course_caption }}
                @endforeach
            </div>
        @endif
    </main>
@endsection
