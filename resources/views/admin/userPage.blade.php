@extends('layout.admin')

@section('content')
<main>
    <div class="container">
        <h1 class="title text-center my-4">
            Пользователь {{$user->name}}
        </h1>
        {{var_dump($user->abilities)}}
    </div>
</main>

@endsection