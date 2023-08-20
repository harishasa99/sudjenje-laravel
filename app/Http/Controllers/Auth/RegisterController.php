<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\AuthenticationException;
use CloudinaryLabs\CloudinaryLaravel\CloudinaryServiceProvider;



class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'gender' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date'],
            'phone_number' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'place_of_birth' => ['required', 'string', 'max:255'],
            'jmbg' => ['required', 'string', 'max:255', 'unique:users'],
            'image' => ['required', 'image'],
            'type' => ['required', 'string', 'max:255'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Provera da li postoji slika u zahtevu
        if (isset($data['image'])) {
            // Sačuvaj sliku na serveru u javnom folderu (public/images)
            $storedImage = $data['image']->store('images', 'public');
    
            // Dobijanje putanje slike u javnom folderu (public/images)
            $imagePath = "storage/{$storedImage}";
        } else {
            // Ako nema slike, postavi putanju slike na null
            $imagePath = null;
        }
    
        // Provera tipa korisnika
        if ($data['type'] == 'admin' || $data['type'] == 'korisnik') {
            $verified = true;
        } else {
            $verified = false;
        }
        
        // Postavljanje poruke za sesiju
        Session::flash('message', 'Uspesno ste se registrovali, cekajte admina!');
    
        // Kreiranje novog korisnika sa svim ostalim podacima
        $user = User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'gender' => $data['gender'],
            'date_of_birth' => $data['date_of_birth'],
            'phone_number' => $data['phone_number'],
            'address' => $data['address'],
            'place_of_birth' => $data['place_of_birth'],
            'jmbg' => $data['jmbg'],
            'image' => $imagePath, // Postavljanje putanje slike
            'type' => $data['type'],
            'approved' => $verified,
        ]);
    
        // Provera za događaj za potvrdu email adrese za predavače
        if ($user->type == 'predavac' && $user->email_verified_at == null) {
            event(new Registered($user));
            throw new AuthenticationException();
        }

        

    
        return $user;
    }
    
}