@extends('layout.user')
@section('content')
    <main>
        <div class="container">
            <h1 class="title text-center">Результат сдачи теста</h1>
            <h4>Занятие: {{$modulelesson->lesson_caption}}</h4>
            @foreach ($quiz_result as $result_i => $result)
                <div>
                    <div>
                        <b>Вопрос:</b> {{ $result['question']->question_title }}
                    </div>
                    <div>
                        {{ $result['question']->question_describe }}
                    </div>
                    <div>
                        <b>Ваш ответ:</b>
                        @foreach ($result['answer']->answered as $answer_i => $answer)
                            <div><b>{{ $answer_i }}:</b>
                                {{ $answer->value }}
                            </div>
                        @endforeach
                    </div>
                    <div>
                        <b>Вы ответили:</b>
                        {{ $result['answer']->correct ? 'Правильно' : 'Неправильно' }}
                    </div>
                    <div>
                        <b>Подсказка:</b>
                        {{ $result['question']->question_help }}
                    </div>
                </div>
            @endforeach
        </div>
    </main>
@endsection
