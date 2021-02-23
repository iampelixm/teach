@extends('layout.admin')

@section('content')
<main>
    <div class="container">
        <h1 class="title text-center">Все курсы</h1>        
        @component('component.table', 
            [
                'items'=>$log,
                'captions'=>['log_id'=>'#','log_date'=>'Дата', 'log_message'=>'Сообщение'],
                'link_item_key'=>'log_id'])
        @endcomponent
    </div>
</main>
@endsection