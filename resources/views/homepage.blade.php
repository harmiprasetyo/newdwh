@extends('layouts.mainlogin')
@section('container')
 <style>
   .menu-box {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 10px;

    padding: 40px 20px;
    background: rgba(255,255,255,0.9);
    border-radius: 15px;
    border: 2px solid #444;
    text-decoration: none;
    color: #333;
    font-size: 18px;

    transition: all 0.3s ease;
}

/* ICON */
.menu-icon {
    font-size: 40px;
    transition: transform 0.3s ease;
}

/* 🔥 HOVER EFFECT */
.menu-box:hover {
    transform: scale(1.08);
    box-shadow: 0 0 25px rgba(0, 123, 255, 0.6);
    border-color: #0d6efd;
    background: #ffffff;
}

/* ICON ikut animasi */
.menu-box:hover .menu-icon {
    transform: scale(1.2);
}

/* ✨ Optional: glow pulse animation */
.menu-box:hover {
    animation: glowPulse 1.5s infinite alternate;
}

@keyframes glowPulse {
    from {
        box-shadow: 0 0 10px rgba(0, 123, 255, 0.4);
    }
    to {
        box-shadow: 0 0 30px rgba(0, 123, 255, 0.9);
    }
}
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<div class="container-fluid vh-100 d-flex align-items-center justify-content-center">

    <div class="text-center w-100">
        <div class="row justify-content-center g-4">

            <div class="col-md-3">
                <a href="/home" class="menu-box">
                    <i class="bi bi-speedometer2 menu-icon"></i>
                    <div>Dashboard</div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="/datarme" class="menu-box">
                    <i class="bi bi-folder2-open menu-icon"></i>
                    <div>Portal Rekam Medis</div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ asset('elplpo-website.html')  }}" class="menu-box">
                   <i class="bi bi-capsule-pill menu-icon"></i>
                    <div>L.P.L.P.O</div>
                </a>
            </div>

        </div>
    </div>

</div>


@endsection


