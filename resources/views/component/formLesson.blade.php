@php
if(!isset($lesson)) $lesson='';
$form_caption='Изменить урок';
$form_action='/admin/lessons/update';
$submit_caption='Сохранить';
if(empty($lesson->lesson_id))
{
    $form_caption='Создать урок';
    $form_action='/admin/lessons/add';
    $submit_caption='Записать';
}
@endphp

<h2 class="title">{{$form_caption}}</h2>
<x-form action="{!!$form_action!!}">
    <x-form-input :bind="$lesson" type="hidden" name="lesson_id"/>
    <x-form-input :bind="$lesson" type="hidden" name="module_id"/>
    <x-form-input :bind="$lesson" type="text" name="lesson_caption" label="Название урока"/>
    <x-form-textarea :bind="$lesson" name="lesson_presc" label="Короткое описание урока"/>
    <x-form-textarea :bind="$lesson" id="editor" class="ckeditor" name="lesson_text" label="Контент урока"/>
    <x-form-textarea :bind="$lesson" id="editor1" class="ckeditor" name="lesson_task" label="Задание урока"/>
    <x-form-submit>{{$submit_caption}}</x-form-submit>
    @if(empty($lesson->lesson_id))
        @component('component.alert',['type'=>'warning'])
        Нужно сохранить урок перед добавлением файлов
        @endcomponent
    @endif        
</x-form>

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