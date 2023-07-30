<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Courses;
use App\Models\CoursesUser;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Models\Lessons;
use App\Models\LessonsUser;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

use function Termwind\render;

class CoursesController extends Controller
{
    //

    public function create()
    {
        if (auth()->guest() || auth()->user()->isVerified() == false) {
            return redirect()->route('login');
        }
        return view('courses.create');
    }

    public function index()
    {
        $courses = Courses::all();
        return view('courses', compact('courses'));
    }

    public function search(){
        if(!request('search')){
            return redirect('/courses');
        }
        $courses = Courses::where('name', 'like', '%' . request('search') . '%')->get();
        return view('courses', compact('courses'));
    }

    public function show()
    {
        $course = Courses::find(request('id'));
        $user = auth()->user();
        
        if ($user) {
            if (CoursesUser::where('user_jmbg', $user->jmbg)->where('courses_id', $course->id)->first() != null) {
                $follows = true;
                return view('courses.show', compact('course', 'user', 'follows'));
            }
            else{
                $follows = false;
                return view('courses.show', compact('course', 'user', 'follows'));
            }
        } else {
            return redirect('/login');
        }
    }

    public function edit()
    {
        $course = Courses::find(request('id'));
        return view('courses.edit', compact('course'));
    }


public function update()
{
    $data = request()->validate([
        'name' => 'required',
        'description' => 'required',
        'image' => 'image',
    ]);

    $course = Courses::find(request('id'));

    if (auth()->user()->jmbg !== (int) $course->user_id) {
        return redirect('/');
    }

    $course->name = $data['name'] ?? $course->name;
    $course->description = $data['description'] ?? $course->description;

    if (request('image')) {
        $imagePath = request('image')->store('uploads', 'public');
        // Optionally, you can remove the existing image file here if needed
        // unlink(public_path("storage/{$course->image}"));

        // Store the new image path in the database
        $course->image = $imagePath;
    }

    $course->save();

    Session::flash('message', 'Course updated successfully');

    return redirect('/teacher/courses/' . $course->id);
}


    public function createLesson()
    {
        $course = Courses::find(request('id'));
        $user = auth()->user();
        if (auth()->user()->type !== 'predavac' && auth()->user()->jmbg !== (int)$course->user_id) {
            return redirect('/');
        }
        return view('lesson.create', compact('course', 'user'));
    }

    

public function storeLesson()
{
    $data = request()->validate([
        'fileName' => 'required',
        'fileDesc' => 'required',
    ]);

    $course = Courses::find(request('id'));
    if (auth()->user()->type !== 'predavac' && auth()->user()->jmbg !== (int)$course->user_id) {
        return redirect('/');
    }

    if (request('files') && $data['fileName'] && $data['fileDesc']) {
        foreach (request('files') as $file) {
            $lesson = new Lessons();
            $lesson->name = $data['fileName'];
            $lesson->description = $data['fileDesc'];
            $lesson->course_id = $course->id;
            $lessonPath = $file->store('uploads', 'public'); // Čuvanje fajla u javni direktorijum
            $lesson->file = '/storage/' . $lessonPath; // Čuvanje putanje fajla u bazi podataka
            $lesson->save();
        }
    } else if (request('link') && $data['fileName'] && $data['fileDesc']) {
        $lesson = new Lessons();
        $lesson->name = $data['fileName'];
        $lesson->description = $data['fileDesc'];
        $lesson->course_id = $course->id;
        $lesson->file = request('link');
        $lesson->save();
    } else {
        return redirect()->back()->with('message', 'Lesson not uploaded');
    }

    return redirect('/teacher/courses/' . $course->id)->with('message', 'Lesson uploaded successfully');
}


    public function store()
    {
        $data = request()->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'required|image',
        ]);
    
        $imagePath = request('image')->store('uploads', 'public');
    
        $image_uploaded = Storage::url($imagePath);
    
        auth()->user()->courses()->create([
            'name' => $data['name'],
            'description' => $data['description'],
            'image' => $image_uploaded,
        ]);
    
        return redirect('/teacher');
    }
    


    
    public function destroy()
    {
        $course = Courses::find(request('id'));
    
        if (auth()->user()->jmbg !== (int) $course->user_id) {
            return redirect('/');
        }
    
        // Prvo izbrišite povezane lekcije
        Lessons::where('course_id', $course->id)->delete();
    
        // Sada možete izbrisati kurs
        $course->delete();
    
        return redirect('/')->with('message', 'Kurs uspešno izbrisan');
    }
    

    public function downloadLesson(int $lesson_id)
    {
        $lesson = Lessons::find($lesson_id);

        if (!LessonsUser::where('lesson_id', $lesson_id)->where('user_id', auth()->user()->jmbg)->exists()) {
            LessonsUser::create([
                'lesson_id' => $lesson_id,
                'user_id' => auth()->user()->jmbg,
            ]);
        }

        Session::flash('downloadFile', $lesson->file);
        return redirect()->away($lesson->file);
    }

    public function deactivate()
    {
        $course = Courses::find(request('id'));
        if (auth()->user()->jmbg !== (int)$course->user_id) {
            return redirect('/');
        }

        $pomocna = $course->status;
        $course->status = !$pomocna;
        $course->save();

        if ($pomocna == true)
            return redirect()->back()->with('message', 'Course dectivated successfully');

        return redirect()->back()->with('message', 'Course activated successfully');
    }
}