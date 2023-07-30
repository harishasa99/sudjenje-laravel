@extends('layouts.app')

@section('content')

<div class="container">

    <a href="{{ route('courses.show', $course->id) }}" class="btn btn-primary my-3">
        < Go Back
    </a>

    <h1>Create a Test</h1>

    <div class="alert alert-success">
        This test will not be shown to users if it does not have at least 1 question.
        The maximum number of questions this test can have is 10.
    </div>

    <form action="{{ route('test.store', $course->id) }}" method="post" id="form">
        @csrf
        @method('post')

        <div class="row mb-3">
            <label for="name" class="col-sm-2 col-form-label">Test name</label>
            <div class="col-sm-10">
                <input type="text" name="name" id="name" class="form-control" required maxlength="20">
            </div>
        </div>

        <div class="row mb-3">
            <label for="hardness" class="col-sm-2 col-form-label">Hardness</label>
            <div class="col-sm-10">
                <select name="hardness" id="hardness" class="form-control">
                    <option value="1" selected>Easy</option>
                    <option value="2">Medium</option>
                    <option value="3">Hard</option>
                </select>
            </div>
        </div>

        <div id="questions-container">
            <!-- Questions will be dynamically added here -->
        </div>

        <button class="btn btn-primary mt-3" onclick="addQuestion()" type="button">Add a Question</button>
        <button class="btn btn-primary mt-3 mx-3">Create</button>
    </form>
</div>

@endsection

@section('scripts')

<script>
    let questionCount = 0;

    function addQuestion() {
        if (questionCount >= 10) {
            alert("You can't add more than 10 questions!");
            return;
        }

        const questionsContainer = document.getElementById('questions-container');

        const questionDiv = document.createElement('div');
        questionDiv.classList.add('question', 'mb-3', 'border', 'p-3');

        questionDiv.innerHTML = `
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Question</label>
                <div class="col-sm-10">
                    <input type="text" name="questions[]" class="form-control" required maxlength="80">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Answer 1</label>
                <div class="col-sm-10">
                    <input type="text" name="answers[]" class="form-control" required maxlength="60">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Answer 2</label>
                <div class="col-sm-10">
                    <input type="text" name="answers[]" class="form-control" required maxlength="60">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Answer 3</label>
                <div class="col-sm-10">
                    <input type="text" name="answers[]" class="form-control" required maxlength="60">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Answer 4</label>
                <div class="col-sm-10">
                    <input type="text" name="answers[]" class="form-control" required maxlength="60">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Correct Answer</label>
                <div class="col-sm-10">
                    <select name="correct[]" class="form-control">
                        <option value="0">Answer 1</option>
                        <option value="1">Answer 2</option>
                        <option value="2">Answer 3</option>
                        <option value="3">Answer 4</option>
                    </select>
                </div>
            </div>
            <button class="btn btn-danger" onclick="removeQuestion(this)" type="button">Remove Question</button>
        `;

        questionsContainer.appendChild(questionDiv);
        questionCount++;
    }

    function removeQuestion(button) {
        const questionDiv = button.parentElement;
        questionDiv.remove();
        questionCount--;
    }
</script>

@endsection
