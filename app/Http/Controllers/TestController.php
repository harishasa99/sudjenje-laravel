<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;
use Illuminate\Support\Facades\Session;
use App\Models\Courses;
use App\Models\Question;
use App\Models\Answer;
use App\Models\AnswersUser;
use App\Models\LessonsUser;
use App\Models\Result;
use Termwind\Components\Dd;

class TestController extends Controller
{
    //

    public function create(int $course_id)
    {
        if (auth()->guest() || auth()->user()->isVerified() == false) {
            return redirect()->route('login');
        }
        $course = Courses::find($course_id);
        return view('test.create', compact('course'));
    }

    public function storeQuestion(int $test_id){
        if (auth()->guest() || auth()->user()->isVerified() == false) {
            return redirect()->route('login');
        }

        if (request()->has('questions') && request()->has('answers') && request()->has('correct')) {

            for ($i = 0; $i < count(request('questions')); $i++) {
                $question = Question::create([
                    'question' => request('questions')[$i],
                    'tests_id' => $test_id
                ]);
                for ($j = 0; $j < count(request('answers')); $j++) {

                    Answer::create([
                        'answer' => request('answers')[$j],
                        'is_correct' => request('correct')[$i] == $j ? true : false,
                        'questions_id' => $question->id
                    ]);
                }
            }
            Session::flash('message', 'Test is successfully created!');
        } else {
            Session::flash('message', 'Test is not created!');
        }

        //return back with message
        return redirect()->back()->with('message', 'Question is successfully created!');
    }

    public function store(int $course_id)
    {
        if (auth()->guest() || auth()->user()->isVerified() == false) {
            return redirect()->route('login');
        }

        if (request()->has('questions') && request()->has('answers') && request()->has('correct')) {
            $data = request()->validate([
                'name' => 'required|string',
                'hardness' => 'required|integer',
            ]);

            Test::create([
                'name' => $data['name'],
                'hardness' => $data['hardness'],
                'courses_id' => $course_id
            ]);
            $test = Test::where('name', $data['name'])->first();
            $answerNumber = 4;
            for ($i = 0; $i < count(request('questions')); $i++) {
                $question = Question::create([
                    'question' => request('questions')[$i],
                    'tests_id' => $test->id
                ]);
                for ($j = $answerNumber - 4; $j < $answerNumber; $j++) {
                    Answer::create([
                        'answer' => request('answers')[$j],
                        'is_correct' => request('correct')[$i] == $j ? true : false,
                        'questions_id' => $question->id
                    ]);
                }
                $answerNumber += 4;
            }
            Session::flash('message', 'Test is successfully created!');
        } else {
            Session::flash('message', 'Test is not created!');
        }

        return redirect()->route('courses.show', $course_id);
    }

    public function show(int $test_id)
    {
        if (auth()->guest() || auth()->user()->isVerified() == false) {
            return redirect()->route('login');
        }
        $test = Test::find($test_id);
        $course = Courses::find($test->courses_id);
        $courseLessons = $course->lesson->pluck('id');
        $downloadedLessons = LessonsUser::where('user_id', auth()->user()->jmbg)->get();
        $downloadedLessons = $downloadedLessons->whereIn('lesson_id', $courseLessons);
        $coureTests = $course->test->pluck('id');
        $userTests = Result::where('user_id', auth()->user()->jmbg)->get();
        if($userTests->whereIn('test_id', $coureTests)->count() > 0){
            $course_id = Test::find(request('id'))->courses_id;
            Session::flash('message', 'You already answered a test from this course!');
            return redirect()->route('courses.show', $course_id);
        }
        if($downloadedLessons->count() != $courseLessons->count()){
            $course_id = Test::find(request('id'))->courses_id;
            Session::flash('message', 'You did not download all lessons on this course!');
            return redirect()->route('courses.show', $course_id);
        }
        Result::create([
            'user_id' => auth()->user()->jmbg,
            'test_id' => request('id'),
            'score' => 0,
            'helps' => 0,
        ]);
        return view('test.show', compact('test'));
    }

    public function edit(int $test_id)
    {
        $test = Test::find($test_id);
        if (auth()->guest() || auth()->user()->isVerified() == false || auth()->user()->isTeacher($test->courses_id) == false) {
            return redirect()->route('login');
        }
        return view('test.edit', compact('test'));
    }

    public function destroyQuestions(int $question_id){
        $question = Question::find($question_id);
        $question->delete();
        return redirect()->back()->with('message', 'Question is successfully deleted!');
    }

    public function destroy(int $test_id)
    {
        $test = Test::find($test_id);
        $test->delete();
        return redirect()->back()->with('message', 'Test is successfully deleted!');
    }

    public function check(){

        $test = Test::findOrfail(request('id'));
        $questions = $test->questions;
        $correctAnswers = $questions->map(function($question){
            return $question->answers->where('is_correct', true)->first()->id;
        });

        $userAnswers = collect(request('answers'));

        $helpNumber = request('helpNumber');

        $answers = $test->questions->map(function($question){
            return $question->answers;
        });
        
        $correct = 0;
        $wrong = 0;
        $notAnswered = 0;
        for($i = 0; $i < $correctAnswers->count(); $i++){
            AnswersUser::create([
                'user_id' => auth()->user()->jmbg,
                'answer_id' => $userAnswers[$i]??false,
            ]);
            if($correctAnswers[$i] == ($userAnswers[$i]??false)){
                $correct++;
            }else if($userAnswers[$i]??false){
                $notAnswered++;
            }else{
                $wrong++;
            }
        }

        $overallScore = ($correct * 2) - $helpNumber;

        $result = Result::where('user_id', auth()->user()->jmbg)->where('test_id', request('id'))->first();
        $result->score = $overallScore;
        $result->helps = $helpNumber;
        $result->save();
        return view('test.result', compact('test', 'questions', 'userAnswers', 'correctAnswers', 'result'));

    }

    public function showResults(int $user_id, int $test_id){
        try{
            $test = Test::findOrfail($test_id);
            $questions = $test->questions;
            $questions = $questions->filter(function($question) use ($user_id){
                return $question->created_at < Result::where('user_id', $user_id)->where('test_id', $question->tests_id)->first()->created_at;
            });
            $answers = $questions->map(function($question){
                return $question->answers;
            });
            $userAnswers = AnswersUser::whereIn('answer_id', $answers->flatten()->pluck('id'))->where('user_id', $user_id)->get()->pluck('answer_id');
            $correctAnswers = $answers->map(function($answer){
                return $answer->where('is_correct', true)->first()->id;
            });
            $result = Result::where('user_id', $user_id)->where('test_id', $test_id)->first();
            return view('test.result', compact('test', 'questions', 'userAnswers', 'correctAnswers', 'result'));
        }
        catch(\Exception $e){
            return redirect()->back()->with('message', 'Test or some of the questions are deleted!');
        }
    }

    public function userResults(int $test_id){
        try{
            $test = Test::findOrfail($test_id);
            $questions = $test->questions;
            $questions = $questions->filter(function($question){
                return $question->created_at < Result::where('user_id', auth()->user()->jmbg)->where('test_id', $question->tests_id)->first()->created_at;
            });
            $answers = $questions->map(function($question){
                return $question->answers;
            });
            $userAnswers = AnswersUser::where('user_id', auth()->user()->jmbg)->get()->pluck('answer_id');
            $userAnswers = $userAnswers->filter(function($answer) use ($answers){
                return $answers->flatten()->pluck('id')->contains($answer);
            });
            if($userAnswers->count() == 0){
                return redirect()->back()->with('error', 'You did not answer on any of the questions! You score is 0!');
            }
            $correctAnswers = $answers->map(function($answer){
                return $answer->where('is_correct', true)->first()->id;
            });
            $result = Result::where('user_id', auth()->user()->jmbg)->where('test_id', $test_id)->first();
            return view('test.result', compact('test', 'questions', 'userAnswers', 'correctAnswers', 'result'));
        }
        catch(\Exception $e){
            return redirect()->back()->with('message', 'Test or some of the questions are deleted!');
        }
    }

    public function result(int $test_id){
        $test = Test::find($test_id);
        $questions = $test->questions;
        $userAnswers = collect(request('answers'));
        $correctAnswers = $questions->map(function($question){
            return $question->answers->where('is_correct', true)->first()->id;
        });
        return view('test.result', compact('test', 'questions', 'userAnswers', 'correctAnswers'));
    }
}