@php
if(!isset($type)) $type='warning';
if(!isset($class)) $class='';
@endphp
<div class="alert alert-{{$type}} $class">
    {{$slot}}
</div>