@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('teacher.index') }}" class="btn btn-primary my-3">
        < Back to Courses
    </a>

    <div class="d-flex my-3 align-items-center flex-wrap">
        <div class="d-flex gap-3 align-items-center col-6">
            <img src="{{ asset($course->image) }}" alt="course" style="width: 75px; height: 75px; object-fit: cover; border-radius: 50%;">
            <h1>{{ $course->name }}</h1>
        </div>
        <div class="d-flex gap-3 col-6 justify-content-end">
            @if($user->isTeacher($course->id))
            <form action="{{ route('courses.edit', $course->id) }}" method="get">
                @csrf
                <button class="btn btn-primary">Edit</button>
            </form>
            <form action="{{ route('follows.show', $course->id) }}" method="get">
                @csrf
                <button class="btn btn-primary">Show Users</button>
            </form>
            <form action="{{ route('courses.deactivate', $course->id) }}" method="post" class="w-auto">
                @csrf
                @method('post')
                @if($course->status == 1)
                <button class="btn btn-danger">Deactivate</button>
                @else
                <button class="btn btn-success">Activate</button>
                @endif
            </form>
            @endif
        </div>
    </div>

    @if(Session('message'))
    <div class="alert alert-success">
        {{ Session('message') }}
    </div>
    @elseif(Session('error'))
    <div class="alert alert-danger">
        {{ Session('error') }}
    </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <p>{{ $course->description }}</p>
        </div>
        <div class="col-md-7">
            <h3>Course Status: <b class="{{ $course->status == 1 ? 'text-success' : 'text-danger' }}">{{ $course->status == 1 ? 'Active' : 'Closed' }}</b></h3>
        </div>
        <div class="col-md-5 d-flex justify-content-end">
            <a href="{{ route('teacher.show', $course->user->jmbg) }}" class="text-decoration-none text-black">
                <h3 class="fst-italic">By:
                    @if ($course->user && $course->user->image)
                    <img src="{{ asset($course->user->image) }}" alt="course" style="width: 35px; height: 35px; object-fit: cover; border-radius: 50%;">
                    @else
                    <span>No Image</span>
                    @endif
                    {{ $course->user->name }} {{ $course->user->surname }}
                </h3>
            </a>
        </div>
        @if($user->results->whereIn('test_id', $course->test->pluck('id'))->count() > 0)
        <div class="col-md-12">
            <h3 class="text-success">You have passed this course!</h3>
            <a href="{{ route('test.userResults', $user->results->whereIn('test_id', $course->test->pluck('id'))->first()->test_id) }}" class="btn btn-primary mb-3">See results</a>
        </div>
        @endif
    </div>

    @if(!$user->isTeacher($course->id))
    @if(!$user->isFollowing($course->id))
    <form action="{{ route('follows.store', $course->id) }}" method="post">
        @csrf
        <button class="btn btn-primary">Follow this Course</button>
    </form>
    @else
    <form action="{{ route('follows.destroy', $course->id) }}" method="post">
        @csrf
        @method('delete')
        <button class="btn btn-primary">Unfollow this Course</button>
    </form>
    @endif
    @endif
    <hr>

    @if(($follows && $course->status == 1) || $user->isTeacher($course->id))

    <div class="row">
        <div class="col-md-12">
            <div class="border-bottom mb-2">
                <h2 class="fw-bold col-2">Lessons:</h2>
                @if($user->isTeacher($course->id))
                <form action="{{ route('lessons.create', $course->id) }}" method="get">
                    @csrf
                    <button class="btn btn-primary">Add Lesson</button>
                </form>
                @endif
            </div>
            <ul class="mb-3 row">
                @foreach($course->lesson as $lesson)
                <li>
                    <a href="{{ route('lessons.download', $lesson->id) }}" class="col-1">{{ $lesson->name }}</a>
                    @if($user->isTeacher($course->id))
                    <form action="{{ route('lessons.destroy', $lesson->id) }}" method="post">
                        @csrf
                        @method('delete')
                        <button class="btn btn-danger">Delete</button>
                    </form>
                    @endif
                </li>
                @endforeach
            </ul>
            <div class="border-bottom mb-2">
                <h2 class="fw-bold col-2">Tests:</h2>
                @if($user->isTeacher($course->id))
                <form action="{{ route('test.create', $course->id) }}" method="get">
                    @csrf
                    <button class="btn btn-primary">Add Test</button>
                </form>
            </div>
            @endif
            <ul class="row">
                @if($course->test != null)
                @foreach($course->test as $test)
                @if($test->questions->count() > 0)
                <li>
                    <div class="row">
                        <a href="{{ route('test.show', $test->id) }}" class="col-2"> {{$test->hardness == 1 ? 'Easy: ' : ($test->hardness == 2 ? 'Medium: ' : 'Hard: ')}} {{$test->name}}</a>
                        @if($user->isTeacher($course->id))
                        <form action="{{route('test.edit', $test->id)}}" method="get">
                            @csrf
                            <button class="btn btn-primary">Edit</button>
                        </form>
                        <form action="{{route('test.destroy', $test->id)}}" method="post">
                            @csrf
                            @method('delete')
                            <button class="btn btn-danger">Delete</button>
                        </form>
                        @endif
                    </div>
                </li>
                @endif
                @endforeach
                @endif
            </ul>
        </div>
    </div>
    @if(Session('downloadFile'))
    <script>
        window.open("{{ Session('downloadFile') }}", "_blank");
        {
            {
                Session::forget('downloadFile')
            }
        }
    </script>
    @endif
    @endif
</div>
@endsection
