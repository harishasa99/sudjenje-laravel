<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'answer',
        'questions_id',
        'is_correct'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class, 'questions_id', 'id');
    }

    public function answersUser()
    {
        return $this->hasMany(AnswersUser::class, 'answer_id', 'id');
    }
}