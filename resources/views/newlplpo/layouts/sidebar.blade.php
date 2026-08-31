@php
    $userGroup = Auth::user()->groupid ?? null;
@endphp


<h4 class="text-center mt-3">
    LPLPO
</h4>

<hr>


{{-- ==========================================================
     GROUP 1 / ADMINISTRATOR
     Semua menu
========================================================== --}}

@if($userGroup == 1)

    <a href="{{ route('newlplpo.index') }}">
        🏠 Dashboard
    </a>

    <a href="#">
        📁 Laporan LPLPO
    </a>

    <div class="submenu">

        <a href="{{ route('newlplpo.create') }}">
            ➜ Membuat LPLPO
        </a>

        <a href="{{ route('newlplpo.laporan') }}">
            ➜ Daftar LPLPO Baru
        </a>

        <a href="{{ route('newlplpo.arsip.index') }}">
            ➜ Arsip LPLPO
        </a>

        <a href="{{ route('newlplpo.verifikasi.index') }}">
            ➜ Verifikasi LPLPO
        </a>

        <a href="{{ route('newlplpo.pemberian') }}">
            ➜ Pemberian Obat
        </a>

    </div>

    <a href="#">
    🗂️ Master Data
</a>

<div class="submenu">

    <a href="{{ route('newlplpo.program.index') }}">
        ➜ Master Data Program
    </a>

 <a href="{{ route('newlplpo.masterdataobat.index') }}">
    ➜ Master Data Obat
</a>

</div>


{{-- ==========================================================
     GROUP 2 / DINAS KESEHATAN
========================================================== --}}

@elseif($userGroup == 2)

    <a href="{{ route('newlplpo.index') }}">
        🏠 Dashboard
    </a>

    <a href="#">
        📁 Laporan LPLPO
    </a>

    <div class="submenu">

        <a href="{{ route('newlplpo.arsip.index') }}">
            ➜ Arsip LPLPO
        </a>

        <a href="{{ route('newlplpo.rekap') }}">
    ➜ Rekap LPLPO
</a>

 <a href="{{ route('newlplpo.stokesensial.index') }}">
    ➜ Monitoring Stok Obat DOEN
</a>


        <a href="{{ route('newlplpo.verifikasi.index') }}">
            ➜ Verifikasi LPLPO
        </a>

        <a href="{{ route('newlplpo.pemberian') }}">
            ➜ Pemberian Obat
        </a>

    </div>


    {{-- ==========================================================
     MASTER DATA
=========================================================== --}}

<a href="#">
    🗂️ Master Data
</a>

<div class="submenu">

    <a href="{{ route('newlplpo.program.index') }}">
        ➜ Master Data Program
    </a>

 <a href="{{ route('newlplpo.masterdataobat.index') }}">
    ➜ Master Data Obat
</a>

</div>


{{-- ==========================================================
     GROUP 3 DAN 5
     PUSKESMAS / USER TERKAIT
========================================================== --}}

@elseif($userGroup == 3 || $userGroup == 5)

    <a href="{{ route('newlplpo.index') }}">
        🏠 Dashboard
    </a>

    <a href="#">
        📁 Laporan LPLPO
    </a>

    <div class="submenu">

        <a href="{{ route('newlplpo.create') }}">
            ➜ Membuat LPLPO
        </a>

        <a href="{{ route('newlplpo.laporan') }}">
            ➜ Daftar LPLPO Baru
        </a>
          <a href="{{ route('newlplpo.arsip.index') }}">
            ➜ Arsip LPLPO
        </a>
        <a href="{{ route('newlplpo.rekap') }}">
    ➜ Rekap LPLPO
</a>
 <a href="{{ route('newlplpo.stokesensial.index') }}">
    ➜  Monitoring Stok Obat DOEN </a>

    </div>

    <a href="#">
    🗂️ Master Data
</a>

<div class="submenu">

   <a href="{{ route('newlplpo.stok-esensial.index') }}">

    ➜ Stok Min dan Esensial

</a>

</div>


@endif


<hr>


{{-- Logout --}}

<a href="{{ route('logout') }}">
    🚪 Logout
</a>
