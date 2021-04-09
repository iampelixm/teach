@php
if (!isset($quiz)) {
    $quiz = '[]';
}
if (!isset($lesson_id)) {
    return 'Не указан урок';
}

if (!isset($user_id)) {
    $user_id = Auth::user()->id;
}
$quiz = json_decode($quiz);

@endphp
<div class="container">
    <h4 class="title text-center">Ваши ответы</h4>
    @foreach ($quiz as $item)
        <div class="row py-2 border-bottom">
            <div class="col text-right">
                {{ $item->question->title }}
            </div>
            <div class="col text-left">
                @foreach ($item->answered as $answer)
                    <div>{{ $answer->value }}</div>
                @endforeach
            </div>
        </div>
    @endforeach
    <div class="text-center">
        <a class="btn btn-success mt-4" href="{{ route('web.quizresult', [$lesson_id, $user_id]) }}">Результ сдачи</a>
    </div>
</div>
