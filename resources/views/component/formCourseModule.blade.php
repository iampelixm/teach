@php
if(!isset($coursemodule)) $coursemodule='';
$form_caption='Изменить модуль';
$form_action='/admin/modules/update';
$submit_caption='Сохранить';
if(empty($coursemodule->module_id))
{
    $form_caption='Создать модуль';
    $form_action='/admin/modules/add';
    $submit_caption='Записать';
}
@endphp
<h2 class="title">{{$form_caption}}</h2>
<x-form action="{!!$form_action!!}">
    <x-form-input :bind="$coursemodule" type="hidden" name="course_id"/>
    <x-form-input :bind="$coursemodule" type="hidden" name="module_id"/>
    <x-form-input :bind="$coursemodule" type="text" name="module_caption" label="*Название модуля"/>
    <x-form-textarea :bind="$coursemodule" name="module_presc" label="*Описание модуля"/>
    <x-form-submit>{{$submit_caption}}</x-form-submit>
</x-form>