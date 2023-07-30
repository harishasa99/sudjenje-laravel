<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'user_id',
        'status',
        'updated_at',
    ];

    public function lesson(){
        return $this->hasMany(Lessons::class, 'course_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'jmbg');
    }

    public function test(){
        return $this->hasMany(Test::class);
    }

    public function following(){
        return $this->hasMany(CoursesUser::class);
    }

}
