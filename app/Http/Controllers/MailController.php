<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AuthenticationEmail;


class MailController extends Controller
{
    public function sendVerificationEmail()
{
    $user = auth()->user(); // Pribavite trenutno ulogovanog korisnika
    
    Mail::to($user->email)->send(new AuthenticationEmail($user));
    
    return "Verification email sent successfully.";
}
}
