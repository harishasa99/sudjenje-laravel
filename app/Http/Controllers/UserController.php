<?php

namespace App\Http\Controllers;

use App\Models\UsersPassword;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    protected $image; 

    public function show(int $teachers_id){
        $user = User::where('jmbg', $teachers_id)->first();
        return view('teacher.show', compact('user'));
    }

    public function index()
    {
        if(!auth()->user()) {
            return redirect('/login');
        }
        $user = auth()->user();
        return view('user.index', compact('user'));
    }

   


    public function changeImage(){
        if(!auth()->user()) {
            return redirect('/login');
        }
        $user = auth()->user();
        return view('user.changeImage', compact('user'));
    }

//     public function updateProfileImage()

// {
//     $user = Auth::user();

//     if (request()->hasFile('image')) {
//         $image = request()->file('image');
//         $user->updateImage($image);
//     } else {
//         Session::flash('error', 'No image uploaded.');
//     }

//     return redirect('/menu');
// }

public function updateImage(Request $request)
{
    $user = auth()->user();

    // Provera da li postoji slika u zahtevu
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        
        // Sačuvaj sliku na serveru u javnom folderu (public/images)
        $storedImage = $image->store('images', 'public');

        // Dobijanje putanje slike u javnom folderu (public/images)
        $imagePath = "storage/{$storedImage}";

        // Ažurirajte putanju slike u bazi podataka za trenutnog korisnika
        $user->image = $imagePath;
        $user->save();
    } else {
        // Ako nema slike, postavi putanju slike na null
        $user->image = null;
        $user->save();
    }

    Session::flash('message', 'Image updated successfully');
    return redirect('/menu');
}






    public function changeName(){
        if(!auth()->user()) {
            return redirect('/login');
        }
        $user = auth()->user();
        $user->name = request('name');
        $user->surname = request('surname');
        $user->save();
        Session::flash('message', 'Name and surname updated successfully');
        return redirect('/menu');
    }

    public function changePassword(){
        if(!auth()->user()) {
            return redirect('/login');
        }
        $user = auth()->user();
        $password = Hash::make(request('password'));

        if (UsersPassword::where('user_id', $user->jmbg)->where('password', $password)->count() > 3) {
            Session::flash('error', 'You have already used this password 3 times, please choose another one');
            return redirect('/menu');
        }
        $user->password = $password;
        $user->save();
        UsersPassword::create([
            'user_id' => $user->jmbg,
            'password' => $password,
        ]);
        Session::flash('message', 'Password updated successfully');
        return redirect('/menu');
    }

    
 
    
    public function destroy()
    {
        if (!auth()->user()) {
            return redirect('/login');
        }
    
        $user = auth()->user();
        $password = request('password');
    
        if (Hash::check($password, $user->password)) {
            if ($user->type == 'predavac' || $user->type == 'admin') {
                $user->active = 0;
                $user->save();
                UsersPassword::where('user_id', $user->jmbg)->delete();
                auth()->logout();
                Session::flash('message', 'Account deleted successfully');
                return redirect('/login');
            } else {
                $user->delete();
                UsersPassword::where('user_id', $user->jmbg)->delete();
                auth()->logout();
                Session::flash('message', 'Account deleted successfully');
                return redirect('/login');
            }
        } else {
            Session::flash('error', 'Wrong password');
            return redirect('/menu');
        }
    }
}