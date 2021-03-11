@php
if (!isset($quiz)) {
    $quiz = '[]';
}

$quiz = json_decode($quiz);

@endphp
<div class="container">
    @foreach ($quiz as $something)
        <div class="row py-2">
            <div class="col">
                {{ $something->question->title }}
            </div>
            <div class="col">
                @foreach ($something->answered as $answer)
                    <div>{{ $answer->value }}</div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
