@extends('layout.admin')

@section('content')
    <main>
        <div class="container">
            <h2 class="title text-center">Учебный процесс</h2>
            <x-form method="GET">
                <div class="form-group">
                    <x-form-select name="course_id">
                        @foreach ($courses as $course)
                            <option value="{{ $course->course_id }}">
                                {{ $course->course_caption }}
                            </option>
                        @endforeach
                    </x-form-select>
                </div>
            </x-form>
        </div>
    </main>
@endsection
