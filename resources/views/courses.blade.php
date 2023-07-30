@extends('layouts.app')

@section('content')

<div class="container">

    <header class="bg-light text-center py-5">
        <div class="container">
            <h1 class="mb-3 text-primary">Referee Courses</h1>
            <p class="lead">We offer a wide variety of courses to help you achieve your goals</p>
        </div>
    </header>
    <h1>All Courses</h1>
    <hr>
    <div class="row">
        <div class="col-md-8 offset-md-2 mb-4">
            <form action="{{ route('courses.search') }}" method="POST">
                @csrf
                @method('POST')
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search for courses" aria-label="Search for courses" aria-describedby="button-addon2">
                    <button class="btn btn-primary" type="submit" id="button-addon2">Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach ($courses as $course)
        <div class="col">
            <div class="card h-100">
                <img src="{{ $course->image }}" class="card-img-top" alt="Course Image">
                <div class="card-body">
                    <h5 class="card-title">{{ $course->name }}</h5>
                    <p class="card-text">{{ $course->description }}</p>
                    <p class="card-text fst-italic">By: <img src="{{ $course->user->image }}" alt="Instructor" width="40" height="40" class="rounded-circle"> {{ $course->user->name }} {{ $course->user->surname }}</p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('courses.show', $course->id) }}" class="btn btn-primary">Learn More</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection
