@extends('layouts.app')

@section('content')

<div class="container">

    @if (session('message'))
        <div class="alert alert-success" role="alert">
            {{ session('message') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">

        <div class="col-md-4">

            <div class="card">
                <div class="card-body">
                <div class="text-center">
                <img src="{{ asset($user->image) }}" alt="Profile Picture" class="rounded-circle img-thumbnail" style="width: 200px; height: 200px; object-fit: cover;">

</div>

                    <div class="text-center mt-3">
                        <a href="{{ route('user.changeImage', $user->id) }}" class="btn btn-primary">Change Picture</a>
                    </div>
                    <div class="text-center mt-3">
                        <h4>{{ $user->name }} {{ $user->surname }}</h4>
                        <h5>{{ $user->email }}</h5>
                        <h5>Role: {{ $user->type }}</h5>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-8 overflow-auto" style="max-height: 450px;">

            <div class="card">
                <div class="card-body">
                    <h3 class="text-center">User Courses</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Description</th>
                                <th scope="col">Image</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!$user->following->isEmpty())
                                @foreach ($user->following as $follow)
                                    <tr>
                                        <td class="align-middle">{{ $follow->course->name }}</td>
                                        <td class="align-middle">{{ $follow->course->description }}</td>
                                        <td class="align-middle">
                                            <img src="{{ $follow->course->image }}" alt="Course Image" class="rounded-circle img-thumbnail" style="width: 75px; height: 75px; object-fit: cover;">
                                        </td>
                                        <td class="align-middle">
                                            {{ $user->results->whereIn('test_id', $follow->course->test->pluck('id'))->count() > 0 ? 'Completed' : 'In progress' }}
                                        </td>
                                        <td class="align-middle">
                                            <a href="{{ route('courses.show', $follow->course->id) }}" class="btn btn-primary">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center">No courses</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

    <form action="{{ route('user.updateName') }}" class="mt-3" method="post">
        @csrf
        @method('post')
        <div class="row m-3">
            <label for="name" class="col-form-label col-md-2">{{ __('Name') }}</label>
            <div class="col-md-4">
                <input id="name" type="text" class="form-control" name="name" required>
            </div>
        </div>
        <div class="row m-3">
            <label for="surname" class="col-form-label col-md-2">{{ __('Surname') }}</label>
            <div class="col-md-4">
                <input id="surname" type="text" class="form-control" name="surname" required>
            </div>
        </div>
        <div class="row m-3 text-md-end">
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary">
                    {{ __('Change Name') }}
                </button>
            </div>
        </div>
    </form>

    <form action="{{ route('user.updatePassword') }}" class="mt-3" method="post">
        @csrf
        @method('post')
        <div class="row m-3">
            <label for="password" class="col-form-label col-md-2">{{ __('Password') }}</label>
            <div class="col-md-4">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="row m-3">
            <label for="password-confirm" class="col-form-label col-md-2">{{ __('Confirm Password') }}</label>
            <div class="col-md-4">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
            </div>
        </div>
        <div class="row m-3 text-md-end">
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary">
                    {{ __('Change Password') }}
                </button>
            </div>
        </div>
    </form>

    <form action="{{ route('user.destroy') }}" method="post">
        @csrf
        @method('delete')
        <div class="row m-3">
            <label for="password" class="col-form-label col-md-2">{{ __('Password') }}</label>
            <div class="col-md-4">
                <input id="password" type="password" class="form-control" name="password" required>
            </div>
        </div>
        <div class="row m-3 text-md-end">
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary">
                    {{ __('Delete Account') }}
                </button>
            </div>
        </div>
    </form>

</div>

@endsection
