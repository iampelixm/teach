@php
if(!isset($items)) $items=[];
if(!isset($link_class)) $link_class="nav-link";
if(!isset($wrap)) $wrap='1';
if(!$items) return '';
@endphp

@foreach($items as $nav_i=>$nav_item)
@if(isset($nav_item['childrens']) && sizeof($nav_item['childrens'])>0)
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="{{$nav_item['link']}}" id="dropdownId_{{$nav_i}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{$nav_item['caption']}}</a>
    <div class="dropdown-menu" aria-labelledby="dropdownId_{{$nav_i}}">
        @component('component.navbaritem', ['items'=>$nav_item['childrens'], 'wrap'=>''])
        @endcomponent
    </div>
</li>
@else
@if($wrap)
<li class="nav-item">
    <a class="nav-link" href="{{$nav_item['link']}}">{{$nav_item['caption']}}</a>
</li>
@else
<a class="dropdown-item" href="{{$nav_ite['link']}}">{{$nav_item['caption']}}</a>
@endif
@endif
@endforeach