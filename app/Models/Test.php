<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'courses_id',
        'hardness'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'courses_id', 'id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'tests_id', 'id');
    }

    public function results()
    {
        return $this->hasMany(Result::class, 'test_id', 'id');
    }
}