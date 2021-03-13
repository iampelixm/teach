@extends('layout.user')

@section('content')
    <main>
        <div class="container">
            <h1 class="title text-center mt-2 mb-4">Редактирование профиля</h1>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('Profile') }} {{ $user->name }}</div>

                        <div class="card-body">
                            @include('component.formUserProfile')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
