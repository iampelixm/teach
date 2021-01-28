@php
$form_caption='Изменить курс';
$form_action='/admin/courses/update';
$submit_caption='Сохранить';
if(empty($course->course_id))
{
    $form_caption='Создать курс';
    $form_action='/admin/courses/add';
    $submit_caption='Записать';
}
@endphp

<h2 class="title">{{$form_caption}}</h2>
<x-form action="{!!$form_action!!}" >
    <x-form-input :bind="$course" type="hidden" name="course_id"/>
    <x-form-input :bind="$course" type="text" name="course_caption" label="Название курса"/>
    <x-form-textarea :bind="$course" name="course_presc" label="Описание курса"/>
    <x-form-group>
        <x-form-checkbox :bind="$course" name="is_access_listed" label="Контролировать доступ"/>
    </x-form-group>
    <x-form-submit>{{$submit_caption}}</x-form-submit>
</x-form>
