@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Admin Panel</h1>
    <hr>

    @if(Session::has('approved'))
    <div class="alert alert-success" role="alert">
        {{ Session::get('approved') }}
    </div>
    @endif

    @if(Session::has('deleted'))
    <div class="alert alert-danger" role="alert">
        {{ Session::get('deleted') }}
    </div>
    @endif

    <h2 data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="true" aria-controls="collapseExample">
        Users
    </h2>
    <div class="collapse.show" id="collapseExample">

        <form action="{{ route('admin.search') }}" class="col-md-4 mb-3" method="get">
            @csrf
            @method('get')
            <div class="input-group">
                <input type="text" name="search" placeholder="Enter user name" class="form-control">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>

        <div class="row">
            @forelse($users as $user)
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{asset ($user->image) }}" alt="Profile Picture"
                                style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;" class="mr-2">
                            <h4 class="mb-0">{{ $user->name }} {{ $user->surname }}</h4>
                        </div>
                        <p class="mb-1">JMBG: {{ $user->jmbg }}</p>
                        <p class="mb-1">Email: {{ $user->email }}</p>
                        <p class="mb-1">Type: {{ $user->type }}</p>
                        <img src="{{asset ($user->image) }}" alt="User Image" width="100px" height="100px" class="my-3">
                        <form action="{{ route('admin.destroy', $user->jmbg) }}" method="POST" class="d-inline-block mr-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                        @if($user->approved == 0)
                        <form action="{{ route('admin.update', $user->jmbg) }}" method="POST" class="d-inline-block">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-primary">Approve</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col">
                <p>No users found.</p>
            </div>
            @endforelse
        </div>

    </div>
    <hr>
    <h2>
        Notifications
        <button class="btn btn-success" data-bs-toggle="collapse" data-bs-target="#collapseCreateNotification"
            aria-expanded="false" aria-controls="collapseCreateNotification">Create Notification</button>
    </h2>

    <div class="collapse" id="collapseCreateNotification">
        <form action="{{ route('admin.notification') }}" method="POST" class="mb-4">
            @csrf
            @method('POST')
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea name="message" id="content" cols="30" rows="5" class="form-control" required></textarea>
            </div>
            <button class="btn btn-primary" type="submit">Create</button>
        </form>
    </div>


<!-- Kraj HTML koda za Toast notifikacije -->









@endsection
