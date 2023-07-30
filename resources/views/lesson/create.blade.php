@extends('layouts.app')

@section('content')

<div class="container">

    <button onclick="window.history.back()" class="btn btn-primary my-3">
        < Go Back</button>

            <div class="form-group my-5">
                <h3>Add Files</h3>
            </div>

            @if(Session('message'))
            <div class="alert alert-success">
                {{Session('message')}}
            </div>
            @endif

            <form action="{{ route('lessons.store', $course->id) }}" enctype="multipart/form-data" method="post">
                @csrf
                @method('post')

                <div class="form-group">
                    <label for="fileName">File name</label>
                    <input type="text" name="fileName" id="fileName" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="fileDesc">File description</label>
                    <input type="text" name="fileDesc" id="fileDesc" class="form-control" required>
                </div>

                <!-- add more files of add a new link -->

                <div class="form-group mb-3">
                    <label for="files">Add more Files</label>
                    <input type="file" name="files[]" id="files" class="form-control" multiple>
                </div>



                <button class="btn btn-primary mt-3">Save</button>
            </form>

</div>

@endsection