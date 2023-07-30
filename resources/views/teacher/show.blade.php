@extends('layouts.app')

@section('content')
<div class="container">


    <button onclick="window.history.back()" class="btn btn-primary my-3"> < Go Back</button>


    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Profile</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-center">
                        <img src="{{$user->image}}" alt="profile" style="width: 150px; height:150px; object-fit:cover; border-radius: 50%;">
                    </div>
                    <div class="d-flex justify-content-center">
                        <h3>{{$user->name}}</h3>
                    </div>
                    <div class="d-flex justify-content-center">
                        <h5>{{$user->email}}</h5>
                    </div>
                    <div class="d-flex justify-content-center">
                        <h5>Teacher since: {{explode(" ", $user->created_at)[0]}} </h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Courses</h3>
                </div>
                <div class="card-body">
                    <div class="row mh-100 d-flex flex-wrap overflow-auto p-auto" style="height:70vh">
                        @foreach($user->courses as $course)
                        <a href="{{route('courses.show', $course->id)}}" class="text-decoration-none text-black col-6 mb-2">
                            <div class="col-md-4 d-flex w-100">
                                <div class="card w-100">
                                    <div class="card-header">
                                        <h3 class="text-center">{{$course->name}}</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-center">
                                            <img src="{{$course->image}}" alt="course" style="width: 150px; height:150px; object-fit:cover; border-radius: 50%;">
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <p>{{$course->description}}</p>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <h5>{{explode(" ", $course->created_at)[0]}}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>                        
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection