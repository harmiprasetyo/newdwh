@php
    $user = Auth::user();
@endphp


<h4 class="text-center mt-3">
    LPLPO BEKASI
</h4>

<hr>


{{-- ==========================================================
     DATA LPLPO BEKASI
========================================================== --}}

<a href="#">
    📁 Data LPLPO BEKASI
</a>

<div class="submenu">

    {{-- ======================================================
         DATA DARI SIPO
    ======================================================= --}}

    <a href="{{ route('newlplpo.bekasi.index') }}">
        ➜ Data dari Sipo
    </a>


    {{-- ======================================================
         REKAP BULANAN LPLPO

         Route belum ditetapkan, sehingga sementara
         dibuat disabled.
    ======================================================= --}}

    <a href="{{ route('newlplpo.bekasi.rekap.index') }}">
        ➜ Rekap Bulanan LPLPO
    </a>

</div>


<hr>


{{-- ==========================================================
     LOGOUT
========================================================== --}}

<a href="{{ route('logout') }}">
    🚪 Logout
</a>
