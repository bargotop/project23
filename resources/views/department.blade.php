@extends('layouts.app')

@section('content')
    <h1>{{ $department->name }}: Группы и Студенты</h1>

    @foreach ($department->groups as $group)
        <h2>{{ $group->name }}</h2>
        <ul>
            @foreach ($group->students as $student)
                <li>{{ $student->full_name }}</li>
            @endforeach
        </ul>
    @endforeach
@endsection
