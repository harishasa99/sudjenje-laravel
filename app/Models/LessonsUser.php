<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonsUser extends Model
{
    use HasFactory;

    protected $table = 'lessons_user';

    protected $fillable = [
        'user_id',
        'lesson_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lessons::class);
    }
}
