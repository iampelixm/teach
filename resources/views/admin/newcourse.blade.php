@extends('layout.admin')

@section('content')
<main>
    <div class="container">
    @component('component.formCourse', ['course'=>($course ?? '')])
    @endcomponent
    </div>
</main>
@endsection