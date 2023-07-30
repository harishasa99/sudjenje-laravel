@extends('layouts.app')

@section('content')
<div class="container">

    <form action="{{ route('courses.update', $course->id) }}" enctype="multipart/form-data" method="post">
        @csrf
        @method('put')
        <div class="form-group my-5">
            <h3>Course Info</h3>
        </div>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" maxlength="20">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <input type="text" name="description" id="description" class="form-control" maxlength="150">
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>
        <div class="text-end">
            <button class="btn btn-primary col-1 m-3">Edit</button>
        </div>
    </form>
</div>
@endsection