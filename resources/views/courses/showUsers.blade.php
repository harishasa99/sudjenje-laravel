@extends('layouts.app')

@section('content')

<div class="container">

    <button onclick="window.history.back()" class="btn btn-primary my-3">
        < Go Back</button>
            @if(Session('message'))
            <div class="alert alert-success">
                {{Session('message')}}
            </div>
            @endif

            <h1>Users</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Surname</th>
                        <th scope="col">Email</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{$user->name}}</td>
                        <td>{{$user->surname}}</td>
                        <td>{{$user->email}}</td>
                        @if($user->status == 'Finished')
                        <td>
                            <a href="{{ route('test.showResults', [$user->id, $user->test_id]) }}" class="text-decoration-none text-black">
                                {{$user->status}}
                            </a>
                        </td>
                        @else
                        <td>
                            {{$user->status}}
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>

</div>

@endsection
