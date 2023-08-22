@extends('layouts.app')

@section('content')

<section id="hero" class="d-flex align-items-center min-vh-100">

    <div class="container">
        <div class="row">
            <div class="col-lg-6 d-flex flex-column justify-content-center pt-4 pt-lg-0 order-2 order-lg-1">
                <h1>FIBA</h1>
                <h2>Basketbal referee</h2>
            </div>
            <div class="col-lg-6 order-1 order-lg-2 hero-img aos-init aos-animate" data-aos="zoom-in" data-aos-delay="200">
                <img src="{{asset('images/fiba-logo.jpg')}}" class="img2" alt="">
            </div>
        </div>
    </div>

</section>



<!-- notification section -->

<section id="notifications">
    <?php
        $notifications = DB::table('notifications')->get();
        $courses = DB::table('courses')->get();
    ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2>News</h2>
                <hr>
            </div>
            @foreach ($notifications as $notification)
                <div class="card p-4">
                    <h3 class="border-bottom">Title: {{ $notification->title }}</h3>
                    <p class="card-text">Date: {{ $notification->created_at }} by: Admin</p>
                    <p class="card-text">Message: {{ $notification->message }}</p>
                    
                </div>
            @endforeach 
        </div>
    </div>
</section>

<section id="cta">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 text-center text-lg-start">
                <h3>Do you want to become a referee?</h3>
                <p>Let's begin</p>
            </div>
            <div class="col-lg-3 cta-btn-container text-center">
                @if(Auth::check())
                    <a class="cta-btn btn btn-primary" href="{{route('courses.index')}}">Get Started</a>
                @else
                <a class="cta-btn btn btn-primary" href="{{route('register')}}">Get Started</a>
                @endif
            </div>
        </div>
    </div>
</section>

<section id="features">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="card h-100 d-flex">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <i class="bi bi-laptop"></i>
                        <h3 class="card-title">Referee 1</h3>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100 d-flex">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <i class="bi bi-award"></i>
                        <h3 class="card-title">Referee 2</h3>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100 d-flex">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <i class="bi bi-chat-dots"></i>
                        <h3 class="card-title">Referee 3</h3>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


</div>

<div class="footer bg-black text-white p-4 d-flex justify-content-center align-items-center">
        <a href="#" class="logo d-flex align-items-center gap-3 text-decoration-none">
            <img src="{{asset('images/fiba-logo.jpg')}}" alt="" width="100px" height="100px">
            <h3 class="text-white mb-0">FIBA</h3>
        </a>

        <div class="col-lg-4 col-md-12 footer-contact">
            <h4 class="text-white">Contact Us</h4>
            <p class="text-white mb-0">
                FIBA - International Basketball Federation <br>
                Route Suisse 5<br>
                1295 Mies - Switzerland <br><br>
                <strong>Phone:</strong> +41 22 545 00 00<br>
                <strong>Email:</strong> info@fiba.basketball<br>
            </p>

            
            <div class="social-icons mt-4">
                <a href="#" class="text-white mx-2"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="text-white mx-2"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-white mx-2"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-white mx-2"><i class="fab fa-linkedin"></i></a>
            </div>
        </div>
    </div>

    
   




        


@endsection