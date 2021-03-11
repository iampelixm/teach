@extends('layout.admin')

@section('content')
    <main>
        <div class="container">
            <h2 class="title text-center">Учебный процесс</h2>
            <x-form method="GET" id="filter">
                <div class="form-group">
                    <x-form-select name="course_id" onchange="$('#filter').submit()">
                        @foreach ($courses as $course)
                            <option value="{{ $course->course_id }}"
                                {{ request()->course_id == $course->course_id ? 'selected' : '' }}>
                                {{ $course->course_caption }}
                            </option>
                        @endforeach
                    </x-form-select>

                    <x-form-select name="module_id" onchange="$('#filter').submit()">
                        @foreach ($modules as $module)
                            <option value="{{ $module->module_id }}"
                                {{ request()->module_id == $module->module_id ? 'selected' : '' }}>
                                {{ $module->module_caption }} / {{ $module->course->course_caption }}
                            </option>
                        @endforeach
                    </x-form-select>
                </div>
            </x-form>
        </div>

        <div class="container">
            <table with="100%" class="data-table">
                <thead>
                    <tr>
                        <th>Кто</th>
                        <th>Когда</th>
                        <th>Урок</th>
                        <th>DO!</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user_answers as $answer)
                        <tr>
                            <td>
                                {{ $students->firstWhere('id', $answer->user_id)->name }}
                            </td>
                            <td>
                                {{ $answer->updated_at ?? $answer->created_at }}
                            </td>
                            <td>

                                {{ $lessons->firstWhere('lesson_id', $answer->lesson_id)->lesson_caption }}
                            </td>
                            <td>
                                @if ($answer->answer_text)
                                    <button class="btn btn-outline-success mr-2" data-toggle="modal"
                                        href="#lesson_answer_text_{{ $answer->answer_id }}" role="button"
                                        aria-expanded="false"
                                        aria-controls="lesson_answer_text_{{ $answer->answer_id }}">A</button>
                                @endif
                                @if ($answer->answer_quiz && json_decode($answer->answer_quiz))
                                    <button class="btn btn-outline-success" data-toggle="modal"
                                        href="#lesson_answer_quiz_{{ $answer->answer_id }}" role="button"
                                        aria-expanded="false"
                                        aria-controls="lesson_answer_quiz_{{ $answer->answer_id }}">Q</button>
                                @endif

                                @component('component.modal', ['id' => 'lesson_answer_text_' . $answer->answer_id, 'title'
                                    => $students->firstWhere('id', $answer->user_id)->name])
                                    {!! $answer->answer_text !!}
                                @endcomponent
                                @component('component.modal', ['id' => 'lesson_answer_quiz_' . $answer->answer_id, 'title'
                                    => $students->firstWhere('id', $answer->user_id)->name])
                                    @component('component.quiz_answer', ['quiz' => $answer->answer_quiz])
                                    @endcomponent
                                @endcomponent
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
@endsection
