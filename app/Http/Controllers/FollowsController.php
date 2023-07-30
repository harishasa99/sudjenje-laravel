<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CoursesUser;
use App\Models\Courses;
use App\Models\Test;
use Illuminate\Database\Eloquent\Collection;
use Termwind\Components\Dd;

class FollowsController extends Controller
{
    //

    public function store(int $course_id)
    {
        try{
            if (auth()->guest() || auth()->user()->isVerified() == false) {
                return redirect()->route('login');
            }
    
            $user = User::findOrFail(auth()->user()->jmbg);
        
    
            CoursesUser::create([
                'user_jmbg' => $user->jmbg,
                'courses_id' => $course_id
            ]);
    
            return redirect()->back()->with('message', 'You are now following this course');
        }
        catch(\Exception $e){
            return redirect()->back()->with('message', 'You are already following this course');
        }
    }

    public function destroy(int $course_id)
    {
        try{
            if (auth()->guest() || auth()->user()->isVerified() == false) {
                return redirect()->route('login');
            }
    
            $user = User::findOrFail(auth()->user()->jmbg);
    
            $follows = CoursesUser::where('user_jmbg', $user->jmbg)->where('courses_id', $course_id)->first();
            $follows->delete();
            return redirect()->back()->with('message', 'You are no longer following this course');
        }
        catch(\Exception $e){
            return redirect()->back()->with('message', 'You are not following this course');
        }
    }

    public function show(int $course_id)
    {
        if (auth()->guest() || auth()->user()->isVerified() == false) {
            return redirect()->route('login');
        }

        $user = User::findOrFail(auth()->user()->jmbg);

        if ($user->isTeacher($course_id) == false) {
            return redirect()->back();
        }

        $courseTests = Test::where('courses_id', $course_id)->get();

        $followers = CoursesUser::where('courses_id', $course_id)->get();

        if ($followers->count() == 0) {
            return redirect()->back()->with('message', 'No one is following this course');
        }
        $courseResults = [];

        $users = new Collection();
        if ($followers->count() == 0) {
            return redirect()->back()->with('message', 'No one is following this course');
        }
        foreach ($followers as $follower) {
            $courseResults[$follower->user->jmbg] = $follower->user->results->whereIn('test_id', $courseTests->pluck('id')->toArray())->first()->score ?? 0;
            if ($courseResults[$follower->user->jmbg] == 0)
                $users->push((object)[
                    'id' => $follower->user->jmbg,
                    'name' => $follower->user->name,
                    'surname' => $follower->user->surname,
                    'email' => $follower->user->email,
                    'status' => "Not finished",
                    'test_id' => null,
                ]);
            else
                $users->push((object)[
                    'id' => $follower->user->jmbg,
                    'name' => $follower->user->name,
                    'surname' => $follower->user->surname,
                    'email' => $follower->user->email,
                    'status' => "Finished",
                    'test_id' => $follower->user->results->whereIn('test_id', $courseTests->pluck('id')->toArray())->first()->test_id,
                ]);
        }

        return view('courses.showUsers', compact('users'));
    }
}