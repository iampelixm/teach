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
if(!empty($lesson->lesson_id))
{
    $upload_url=route('admin.lesson.ckeditor-image', $lesson);
}
else {
    $lesson->lesson_id='';
    $upload_url='';
}
@endphp

<h2 class="title">{{$form_caption}}</h2>
<x-form action="{{empty($lesson->lesson_id) ? route('admin.lesson.add') : route('admin.lesson.update')}}">
    <x-form-input :bind="$lesson" type="hidden" name="lesson_id"/>
    <x-form-input :bind="$lesson" type="hidden" name="module_id"/>
    <x-form-select :bind="$lesson" name="module_id" label="Находится в модуле">
        @foreach(App\Models\CourseModule::all() as $module)
            <option value="{{$module->module_id}}" {{$lesson->toArray()['module_id'] == $module->module_id ? 'selected' : ''}}>{{$module->module_caption}}</option>
        @endforeach
    </x-form-select>
    <x-form-input :bind="$lesson" type="text" name="lesson_caption" label="*Название урока" required/>
    <x-form-textarea :bind="$lesson" name="lesson_presc" label="*Короткое описание урока" required/>
    <x-form-textarea :bind="$lesson" data-upload_url="{{$upload_url}}" class="ckeditor" name="lesson_text" label="*Контент урока"/>
    <x-form-textarea :bind="$lesson" data-upload_url="{{$upload_url}}" class="ckeditor" name="lesson_task" label="Задание урока"/>
    <x-form-submit>{{$submit_caption}}</x-form-submit>
    @if(empty($lesson->lesson_id))
        @component('component.alert',['type'=>'warning'])
        Нужно сохранить урок перед добавлением файлов
        @endcomponent
    @endif        
</x-form>
