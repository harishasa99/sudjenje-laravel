@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Change picture</h3>
    <hr>
    <form action="{{ route('user.updateImage') }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('post')
        <div class="row">
            <label for="image">Enter new Image:</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>
        <div class="text-end">
            <button class="btn btn-primary mt-3 text-right col-1">Change</button>
        </div>
    </form>
</div>

@endsection