@extends('layout.user')
@section('content')
<main>
    <div class="container">
        <a href="/">Курсы</a> / 
        <a href="/course/{{$modulelesson->module->course->course_id}}">{{$modulelesson->module->course->course_caption}}</a> /
        <a href="/module/{{$modulelesson->module->module_id}}">{{$modulelesson->module->module_caption}}</a>
        <h1 class="title text-center mt-2 mb-4">
            {{$modulelesson->lesson_caption}}. Задание
        </h1>
        
        <div class="lesson_task my-4">
            {!!$modulelesson->lesson_task!!}
        </div>
        @if(!collect($documents)->isEmpty())
        <div id="lesson_documents">
            <h3 class="title">Дополнительные материалы</h3>
            @foreach($documents as $file_i=>$file)
            <div class="border mt-1">
                <a href="/file/download?file={{$file}}">{{collect(explode('/',$file))->last()}}</a>
            </div>
            @endforeach      
        </div>
        @endif
        @if(collect($user_answer)->isEmpty())
        <h2 class="title">Выполнение задания</h2>
        <x-form action="/userlessonanswer" enctype="multipart/form-data">
            <x-form-input value="{{collect($user_answer)->isEmpty() ? '' : $user_answer->answer_id}}" type="hidden" name="answer_id"/>
            <x-form-input value="{{collect($user_answer)->isEmpty() ? Auth::user()->id : $user_answer->user_id}}" type="hidden" name="user_id"/>
            <x-form-input value="{{$modulelesson->lesson_id}}" type="hidden" name="lesson_id"/>
            <x-form-textarea :bind="$user_answer" id="editor" name="answer_text" label="Ответ на задание"/>
            <x-form-input type="file" name="file" label="Прикрепить файлы" multiple/>
            <x-form-submit>Сохранить</x-form-submit>
        </x-form>
        @else
            <h2 class="title">Вы уже выполнили задание этого занятия</h2>
            <div>Ваш ответ:</div>
            <div>{!!$user_answer->answer_text!!}</div>
            @if($answer_files)
                <div>Приложенные файлы</div>
                @foreach($answer_files as $file_i=>$file)
                    <div class="border mt-1">
                        <a href="/file/download?file={{$file}}">{{collect(explode('/',$file))->last()}}</a>
                    </div>                    
                @endforeach
            @endif
        @endif
    </div>
</main>
@endsection

@push('javascript')
<script src="/js/ckeditor.js"></script>

<script>
	ClassicEditor
		.create( document.querySelector( '#editor' ), {
			// toolbar: [ 'heading', '|', 'bold', 'italic', 'link' ]
		} )
		.then( editor => {
			window.editor1 = editor;
		} )
		.catch( err => {
			console.error( err.stack );
        } );
        
	ClassicEditor
		.create( document.querySelector( '#editor1' ), {
			// toolbar: [ 'heading', '|', 'bold', 'italic', 'link' ]
		} )
		.then( editor => {
			window.editor2 = editor;
		} )
		.catch( err => {
			console.error( err.stack );
		} );        
</script>
@endpush