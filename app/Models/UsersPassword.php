<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersPassword extends Model
{
    use HasFactory;

    protected $table = 'users_passwords';
    protected $fillable = ['user_id', 'password'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}