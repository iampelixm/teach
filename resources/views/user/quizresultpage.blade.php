@extends('layout.user')
@section('content')
    <main>
        <div class="container">
            <h1 class="title text-center">Результат сдачи теста</h1>
            <h4>Занятие: <a href="{{route('web.lessonPage', $modulelesson->lesson_id)}}">{{ $modulelesson->lesson_caption }}</a></h4>
            @foreach ($quiz_result as $result_i => $result)
                <div class="border rounded mt-4 {!! $result['answer']->correct ? 'border-success' : 'border-warning' !!}">
                    <div>
                        <b>Вопрос:</b> {{ $result['question']->question_title }}
                    </div>
                    <div>
                        {{ $result['question']->question_describe }}
                    </div>
                    <div>
                        <b>Ваш ответ:</b>
                        @foreach ($result['answer']->answered as $answer_i => $answer)
                            <div>
                                {{ $answer->value }}
                            </div>
                        @endforeach
                    </div>
                    <div>
                        <b>Вы ответили:</b>
                        {!! $result['answer']->correct ? 'Правильно' : '<span class="text-danger">Неправильно</span>' !!}
                    </div>
                    @if ($result['question']->question_help ?? '')
                        <div>
                            <b>Подсказка:</b>
                            {{ $result['question']->question_help ?? '' }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </main>
@endsection
