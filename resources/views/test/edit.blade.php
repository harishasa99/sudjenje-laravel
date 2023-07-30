@extends('layouts.app')

@section('content')

<div class="container">


        <a href="{{ route('courses.show', $test->courses_id) }}" class="btn btn-primary my-3">
        < Go Back
        </a>

            @if(Session('message'))
            <div class="alert alert-success">
                {{Session('message')}}
            </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <h1>Test: {{ $test->name }}</h1>
                </div>
                <div class="col-md-12">
                    <h1>Hardness: {{ $test->hardness == 1 ? "Easy" : ($test->hardness == 2 ? "Medium" : "Hard") }}</h1>
                </div>
                <div class="col-md-12 mb-3">
                    <h1>Questions:</h1>
                    <button class="btn btn-primary" onclick="addQuestion()">Add a Question</button>

                    @if($test->questions->count() == 1)
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Question: {{ $test->questions[0]->question }}</h3>
                        </div>
                        <div class="col-md-12">
                            <h3>Answers:</h3>
                            <ul>
                                @foreach($test->questions[0]->answers as $answer)
                                <li>{{ $answer->answer }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-md-12 mb-3">
                            <form action="{{ route('question.destroy', $test->questions[0]->id) }}" method="post">
                                @csrf
                                @method('delete')
                                <button class="btn btn-danger mb-3">Delete Question</button>
                            </form>
                        </div>
                        <hr>
                    </div>
                    @else
                    @foreach($test->questions as $question)
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Question: {{ $question->question }}</h3>
                        </div>
                        <div class="col-md-12">
                            <h3>Answers:</h3>
                            <ul>
                                @foreach($question->answers as $answer)
                                <li>{{ $answer->answer }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <form action="{{ route('question.destroy', $question->id) }}" method="post">
                                @csrf
                                @method('delete')
                                <button class="btn btn-danger mb-3">Delete Question</button>
                            </form>
                        </div>
                        <hr>
                    </div>
                    @endforeach
                    @endif
                    <form action="{{ route('question.store', $test->id) }}" method="post" id="form">
                        @csrf
                        @method('post')
                    </form>
                </div>
            </div>
</div>

@endsection

@section('scripts')

<script>
    function addQuestion() {
        let form = document.getElementById('form');
        let question = document.createElement('div');
        question.classList.add('question');
        question.innerHTML = `
        <div class="row">
            <label for="question">Question</label>
            <input type="text" name="questions[]" id="question" class="form-control" required maxlength="80">
        </div>
        <div class="row">
            <label for="answer">Answer 1</label>
            <input type="text" name="answers[]" id="answer" class="form-control" required maxlength="60">
        </div>
        <div class="row">
            <label for="answer">Answer 2</label>
            <input type="text" name="answers[]" id="answer" class="form-control" required maxlength="60">
        </div>
        <div class="row">
            <label for="answer">Answer 3</label>
            <input type="text" name="answers[]" id="answer" class="form-control" required maxlength="60">
        </div>
        <div class="row">
            <label for="answer">Answer 4</label>
            <input type="text" name="answers[]" id="answer" class="form-control" required maxlength="2600"> 
        </div>
        <div class="row">
        <label for="correct">Correct Answer</label>
            <select name="correct[]" id="correct">
                <option value="0">Answer 1</option>
                <option value="1">Answer 2</option>
                <option value="2">Answer 3</option>
                <option value="3">Answer 4</option>
            </select>
        </div>
        <button class="btn btn-danger mt-3" onclick="removeQuestion(this)">Remove Question</button>
        <button class="btn btn-primary mt-3">Save</button>
        <hr>
        `;
        form.appendChild(question);
    }

    function removeQuestion(button) {
        button.parentElement.remove();
    }
</script>

@endsection