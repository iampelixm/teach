<div class="card {{$class}}">
    <div class="card-header">
        @if(!empty($link))
        <a href="{{$link}}">
        @endif
            {{$title}}
        @if(!empty($link))
        </a>
        @endif
    </div>
    <div class="card-body">
      <h5 class="card-title d-none"></h5>
      <p class="card-text">{{$body}}</p>
    </div>
  </div>