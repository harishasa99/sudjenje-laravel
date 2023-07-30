@extends('layouts.app')

@section('content')

<div class="container">

    <div class="row d-flex">
        <h1>{{ $test->name }}</h1>
        <hr>
        <h3>Hardness: {{ $test->hardness == 1 ? "Easy" : ($test->hardness == 2 ? "Medium" : "Hard") }}</h3>
        <h3>Number of questions: {{ $test->questions()->count() }}</h3>
    </div>
    <hr>
    <div class="row">
        <form action="{{ route('test.check', $test->id) }}" method="post">
            @csrf
            @method('post')
            <input type="number" class="d-none" name="helpNumber" value="0">
            @for($i = 0; $i < $test->questions->count(); $i++)
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h3>{{ $i + 1 }}:  {{ $test->questions[$i]->question }}</h3>
                    </div>
                    <div class="card-body d-flex flex-column" id="section{{$i}}">
                        @for($j = 0; $j < $test->questions[$i]->answers()->count(); $j++)
                        <div class="d-flex align-items-center">
                            <input type="radio" name="answers[{{$i}}]" value="{{ $test->questions[$i]->answers[$j]->id }}" id="answer{{$i}}{{$j}}" class="m-2 {{$test->questions[$i]->answers[$j]->is_correct == true ? true : false}}" required>
                            <label for="answer{{$i}}{{$j}}">{{ $test->questions[$i]->answers[$j]->answer }}</label>
                        </div>
                        @endfor
                        <button class="btn btn-danger col-2 mt-3" type="button" onclick="help50({{$i}})" id="pomoc{{$i}}">Pomoc: 50:50</button>
                    </div>
                </div>
            </div>
            @endfor
            <button class="btn btn-primary">Submit</button>
        </form>
    </div>

</div>

@endsection


@section('scripts')

    <script>
        function help50(row) {
            let answers = document.querySelectorAll(`#section${row} input`);
            let pomoc = document.querySelector(`#pomoc${row}`);
            let answersArray = Array.from(answers);
            let correctAnswers = answersArray.filter(answer => answer.classList.contains(1));
            let wrongAnswers = answersArray.filter(answer => !answer.classList.contains(1));
            let random1 = Math.floor(Math.random() * 3);
            let random2 = Math.floor(Math.random() * 2);
            wrongAnswers[random1].disabled = true;
            wrongAnswers.splice(random1, 1);
            wrongAnswers[random2].disabled = true;
            wrongAnswers.splice(random2, 1);

            pomoc.disabled = true;
            let helpNumber = document.querySelector(`input[name="helpNumber"]`);
            helpNumber.value = parseInt(helpNumber.value) + 1;
        }
    </script>

@endsection