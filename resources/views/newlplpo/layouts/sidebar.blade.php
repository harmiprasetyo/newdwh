
@php
    $userGroup = Auth::user()->groupid ?? null;
    $userName  = Auth::user()->namalengkap ?? '';
@endphp

{{-- ==========================================================
     SIDEBAR HEADER / LOGO
========================================================== --}}

<div class="sidebar-header text-center py-3 px-2">

    {{-- Logo LPLPO --}}
    <div class="sidebar-logo mb-2">
        <img
            src="{{ asset('img/sidebarlogo.png') }}"
            alt="LPLPO"
            class="img-fluid"
        >
    </div>

    {{-- Nama Pengguna --}}
    <div class="sidebar-user-name">
        {{ $userName }}
    </div>

</div>

<hr class="sidebar-divider">


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


    {{-- ======================================================
         MASTER DATA
    ======================================================= --}}

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
          <a href="{{ route('newlplpo.kategoriobat.index') }}">
        ➜ Master Kategori Obat
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

        <a href="{{ route('newlplpo.report.monitoring') }}">
            ➜ Absensi Puskesmas
        </a>

        <a href="{{ route('newlplpo.verifikasi.index') }}">
            ➜ Verifikasi LPLPO
        </a>

        <a href="{{ route('newlplpo.pemberian') }}">
            ➜ Pemberian Obat
        </a>

    </div>


    {{-- ======================================================
         MASTER DATA
    ======================================================= --}}

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

          <a href="{{ route('newlplpo.kategoriobat.index') }}">
        ➜ Master Kategori Obat
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
            ➜ Monitoring Stok Obat DOEN
        </a>

    </div>


    {{-- ======================================================
         MASTER DATA
    ======================================================= --}}

    <a href="#">
        🗂️ Master Data
    </a>

    <div class="submenu">

        <a href="{{ route('newlplpo.stok-esensial.index') }}">
            ➜ Stok Min dan Esensial
        </a>

    </div>

@endif

@if((int) auth()->user()->groupid === 3)

    <li class="nav-item">

        <a
            href="{{ route('newlplpo.infokapus.index') }}"
            class="nav-link"
        >

            <i class="bi bi-person-badge me-2"></i>

            <span>
                Info Kepala Puskesmas
            </span>

        </a>

    </li>

@endif


{{-- ==========================================================
     DIVIDER
========================================================== --}}

<hr class="sidebar-divider">


{{-- ==========================================================
     LOGOUT
========================================================== --}}

<a href="{{ route('logout') }}">
    🚪 Logout
</a>

