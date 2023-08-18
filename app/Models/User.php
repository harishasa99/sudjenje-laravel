<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\URL;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>          
     */

    protected $primaryKey = 'jmbg';
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'gender',
        'date_of_birth',
        'phone_number',
        'address',
        'place_of_birth',
        'jmbg',
        'image',
        'type',
        'approved',
        'active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isActive(){
        $user = User::find(auth()->user()->jmbg);
        if ($user->active == 1){
            return true;
        }
        return false;
    }

    public function isVerified(){
        $user = User::find(auth()->user()->jmbg);
        if ($user->approved == 1 && $user->active == 1 && $user->email_verified_at != null){
            return true;
        }
        return false;
    }

    public function isPredavac()
    {
        $user = User::find(auth()->user()->jmbg);
        if ($user->type == "predavac"){
            return true;
        }
        return false;
    }

    public function isType($role)
    {
        return $this->type == $role;
    }

    public function courses()
    {
        return $this->hasMany(Courses::class, 'user_id', 'jmbg');
    }

    public function lessons()
    {
        return $this->belongsToMany(Lessons::class, 'lessons_user', 'user_id', 'lesson_id');
    }

    public function isTeacher($courseId)
    {
        $user = User::find(auth()->user()->jmbg);
        if ($user->courses->contains($courseId)) {
            return true;
        }
        return false;
    }

    public function isFollowing($course_id)
    {
        $user = User::find(auth()->user()->jmbg);
        $follows = CoursesUser::where('user_jmbg', $user->jmbg)->where('courses_id', $course_id)->first();
        if ($follows) {
            return true;
        }
        return false;
    }

    public function isUser(){
        $user = User::find(auth()->user()->jmbg);
        if ($user->type == "korisnik"){
            return true;
        }
        return false;
    }

    public function following(){
        return $this->hasMany(CoursesUser::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class, 'user_id', 'jmbg');
    }

    public function answers()
    {
        return $this->hasMany(AnswersUser::class, 'user_id', 'jmbg');
    }


    protected $appends = ['verification_url'];

    public function getVerificationUrlAttribute()
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'id' => $this->getKey(),
                'hash' => sha1($this->getEmailForVerification()),
            ]
        );
    }

}
