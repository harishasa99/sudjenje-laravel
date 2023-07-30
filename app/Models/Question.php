<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'tests_id',
    ];

    public function test()
    {
        return $this->belongsTo(Test::class, 'tests_id', 'id');
    }

    public function answers(){
        return $this->hasMany(Answer::class, 'questions_id', 'id');
    }
}