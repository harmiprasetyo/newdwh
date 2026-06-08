@extends('layouts.mainlogin')
@section('container')

<style>
.menu-box {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 12px;

    padding: 40px 20px;
    background: rgba(255,255,255,0.95);
    border-radius: 18px;
    border: 1px solid #ddd;
    text-decoration: none;
    color: #333;
    font-size: 18px;
    font-weight: 500;

    transition: all 0.3s ease;
}

/* ICON */
.menu-icon {
    font-size: 42px;
    transition: all 0.3s ease;
}

/* 🔥 HOVER EFFECT */
.menu-box:hover {
    transform: translateY(-8px) scale(1.05);
    box-shadow: 0 10px 30px rgba(0, 123, 255, 0.3);
    border-color: #0d6efd;
    background: #ffffff;
}

/* ICON animasi */
.menu-box:hover .menu-icon {
    transform: scale(1.2);
    color: #0d6efd;
}

/* Glow halus */
.menu-box:hover {
    animation: glowPulse 1.5s infinite alternate;
}

@keyframes glowPulse {
    from {
        box-shadow: 0 0 10px rgba(0, 123, 255, 0.3);
    }
    to {
        box-shadow: 0 0 25px rgba(0, 123, 255, 0.7);
    }
}
</style>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<div class="container-fluid vh-100 d-flex align-items-center justify-content-center">

    <div class="text-center w-100">
        <div class="row justify-content-center g-4">

            {{-- Dashboard --}}
            <div class="col-md-3">
                <a href="/dashboard" class="menu-box">
                    <i class="bi bi-speedometer2 menu-icon"></i>
                    <div>Dashboard</div>
                </a>
            </div>

            {{-- Rekam Medis --}}
            <div class="col-md-3">
                <a href="/datarme" class="menu-box">
                    <i class="bi bi-file-medical menu-icon"></i>
                    <div>Portal Rekam Medis</div>
                </a>
            </div>

            {{-- LPLPO --}}
            <div class="col-md-3">
                <a href="/lplpo/dashboard" class="menu-box">
                    <i class="bi bi-capsule menu-icon"></i>
                    <div>L.P.L.P.O</div>
                </a>
            </div>

            {{-- Admin Panel --}}
            <div class="col-md-3">
                <a href="/adminpanel" class="menu-box">
                    <i class="bi bi-gear-fill menu-icon"></i>
                    <div>Admin Panel</div>
                </a>
            </div>

        </div>
    </div>

</div>

@endsection
