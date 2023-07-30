<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursesUser extends Model
{
    use HasFactory;

    public $fillable = ['user_jmbg', 'courses_id'];

    public function user(){
        return $this->belongsTo(User::class, 'user_jmbg', 'jmbg');
    }

    public function course(){
        return $this->belongsTo(Courses::class, 'courses_id', 'id');
    }
}
