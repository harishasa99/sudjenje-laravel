<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lessons extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'course_id',
        'file',
    ];

    public function course(){
        return $this->belongsTo(Courses::class);
    }

    public function users(){
        return $this->belongsToMany(User::class, 'lessons_user', 'lesson_id', 'user_id');
    }
}