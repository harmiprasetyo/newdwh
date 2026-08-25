@php
    $groupId = (int) auth()->user()->groupid;
@endphp

<div class="sidebar p-3">

    <h4>⚡ Admin</h4>
    <hr>


    {{-- ========================================================= --}}
    {{-- BERANDA                                                   --}}
    {{-- ========================================================= --}}

    <a href="/homepage" class="menu-toggle">
        <i class="fa fa-home"></i>
        Beranda
    </a>


    {{-- ========================================================= --}}
    {{-- GROUP 1                                                   --}}
    {{-- ========================================================= --}}

    @if ($groupId === 1)

        {{-- ===================================================== --}}
        {{-- USER MANAGEMENT                                      --}}
        {{-- ===================================================== --}}

        <a href="#"
           class="menu-toggle
           {{ request()->is('adminpanel/userpanel/*') ? 'active' : '' }}">

            <i class="fa fa-users"></i>
            User Management

        </a>

        <div class="submenu
             {{ request()->is('adminpanel/userpanel/*') ? 'd-block' : '' }}">

            <a href="/adminpanel/userpanel/users"
               class="{{ request()->is('adminpanel/userpanel/users') ? 'active' : '' }}">

                Daftar User

            </a>

            <a href="/adminpanel/userpanel/groups"
               class="{{ request()->is('adminpanel/userpanel/groups') ? 'active' : '' }}">

                Group User

            </a>

            <a href="/adminpanel/userpanel/roles"
               class="{{ request()->is('adminpanel/userpanel/roles') ? 'active' : '' }}">

                User Role

            </a>

        </div>


        {{-- ===================================================== --}}
        {{-- DATA WILAYAH                                         --}}
        {{-- ===================================================== --}}

        <a href="#"
           class="menu-toggle
           {{ request()->is(
                'adminpanel/provinsi*',
                'adminpanel/kota*',
                'adminpanel/kecamatan*',
                'adminpanel/desa*'
           ) ? 'active' : '' }}">

            <i class="fa fa-map"></i>
            Data Wilayah

        </a>

        <div class="submenu
             {{ request()->is(
                'adminpanel/provinsi*',
                'adminpanel/kota*',
                'adminpanel/kecamatan*',
                'adminpanel/desa*'
             ) ? 'd-block' : '' }}">

            <a href="/adminpanel/provinsi"
               class="{{ request()->is('adminpanel/provinsi*') ? 'active' : '' }}">

                Propinsi

            </a>

            <a href="/adminpanel/kota"
               class="{{ request()->is('adminpanel/kota*') ? 'active' : '' }}">

                Kota

            </a>

            <a href="/adminpanel/kecamatan"
               class="{{ request()->is('adminpanel/kecamatan*') ? 'active' : '' }}">

                Kecamatan

            </a>

            <a href="/adminpanel/desa"
               class="{{ request()->is('adminpanel/desa*') ? 'active' : '' }}">

                Desa

            </a>

        </div>


        {{-- ===================================================== --}}
        {{-- DATA FASKES                                           --}}
        {{-- ===================================================== --}}

        <a href="#"
           class="menu-toggle
           {{ request()->is(
                'adminpanel/typefaskes*',
                'adminpanel/master/faskes*',
                'adminpanel/posyandu*'
           ) ? 'active' : '' }}">

            <i class="fa fa-hospital"></i>
            Data Faskes

        </a>

        <div class="submenu
             {{ request()->is(
                'adminpanel/typefaskes*',
                'adminpanel/master/faskes*',
                'adminpanel/posyandu*'
             ) ? 'd-block' : '' }}">

            <a href="/adminpanel/typefaskes"
               class="{{ request()->is('adminpanel/typefaskes*') ? 'active' : '' }}">

                Type Faskes

            </a>

            <a href="/adminpanel/master/faskes"
               class="{{ request()->is('adminpanel/master/faskes*') ? 'active' : '' }}">

                Master Faskes

            </a>

            <a href="/adminpanel/posyandu"
               class="{{ request()->is('adminpanel/posyandu*') ? 'active' : '' }}">

                Master Posyandu

            </a>

        </div>


        {{-- ===================================================== --}}
        {{-- WILAYAH KERJA                                         --}}
        {{-- ===================================================== --}}

        <a href="#"
           class="menu-toggle
           {{ request()->is(
                'adminpanel/posyandu/wilayah-kerja*',
                'adminpanel/wilayahkerja/puskesmas*'
           ) ? 'active' : '' }}">

            <i class="fa fa-map"></i>
            Wilayah Kerja

        </a>

        <div class="submenu
             {{ request()->is(
                'adminpanel/posyandu/wilayah-kerja*',
                'adminpanel/wilayahkerja/puskesmas*'
             ) ? 'd-block' : '' }}">

            <a href="/adminpanel/posyandu/wilayah-kerja"
               class="{{ request()->is('adminpanel/posyandu/wilayah-kerja*') ? 'active' : '' }}">

                Wilayah Kerja Posyandu

            </a>

            <a href="/adminpanel/wilayahkerja/puskesmas"
               class="{{ request()->is('adminpanel/wilayahkerja/puskesmas*') ? 'active' : '' }}">

                Wilayah Kerja Puskesmas

            </a>

        </div>


        {{-- ===================================================== --}}
        {{-- DATA TARGET                                           --}}
        {{-- ===================================================== --}}





        {{-- ===================================================== --}}
        {{-- ACTIVITY LOG                                          --}}
        {{-- ===================================================== --}}

        <a href="{{ route('activity-log.index') }}">

            <i class="fas fa-history me-2"></i>

            Activity Log

        </a>


    {{-- ========================================================= --}}
    {{-- GROUP 3                                                   --}}
    {{-- ========================================================= --}}

    @elseif ($groupId === 3)

        {{-- ===================================================== --}}
        {{-- USER MANAGEMENT                                      --}}
        {{-- HANYA DAFTAR USER                                    --}}
        {{-- ===================================================== --}}

        <a href="#"
           class="menu-toggle
           {{ request()->is('adminpanel/userpanel/users*') ? 'active' : '' }}">

            <i class="fa fa-users"></i>
            User Management

        </a>

        <div class="submenu
             {{ request()->is('adminpanel/userpanel/users*') ? 'd-block' : '' }}">

            <a href="/adminpanel/userpanel/users"
               class="{{ request()->is('adminpanel/userpanel/users*') ? 'active' : '' }}">

                Daftar User

            </a>

        </div>


        {{-- ===================================================== --}}
        {{-- DATA FASKES                                          --}}
        {{-- HANYA MASTER POSYANDU                                --}}
        {{-- ===================================================== --}}

        <a href="#"
           class="menu-toggle
           {{ request()->is('adminpanel/posyandu') ? 'active' : '' }}">

            <i class="fa fa-hospital"></i>
            Data Faskes

        </a>

        <div class="submenu
             {{ request()->is('adminpanel/posyandu') ? 'd-block' : '' }}">

            <a href="/adminpanel/posyandu"
               class="{{ request()->is('adminpanel/posyandu') ? 'active' : '' }}">

                Master Posyandu

            </a>

        </div>


        {{-- ===================================================== --}}
        {{-- WILAYAH KERJA                                        --}}
        {{-- ===================================================== --}}

        <a href="#"
           class="menu-toggle
           {{ request()->is(
                'adminpanel/posyandu/wilayah-kerja*',
                'adminpanel/wilayahkerja/puskesmas*'
           ) ? 'active' : '' }}">

            <i class="fa fa-map"></i>
            Wilayah Kerja

        </a>

        <div class="submenu
             {{ request()->is(
                'adminpanel/posyandu/wilayah-kerja*',
                'adminpanel/wilayahkerja/puskesmas*'
             ) ? 'd-block' : '' }}">

            <a href="/adminpanel/posyandu/wilayah-kerja"
               class="{{ request()->is('adminpanel/posyandu/wilayah-kerja*') ? 'active' : '' }}">

                Wilayah Kerja Posyandu

            </a>

            <a href="/adminpanel/wilayahkerja/puskesmas"
               class="{{ request()->is('adminpanel/wilayahkerja/puskesmas*') ? 'active' : '' }}">

                Wilayah Kerja Puskesmas

            </a>

        </div>


        {{-- ===================================================== --}}
        {{-- DATA TARGET                                          --}}
        {{-- ===================================================== --}}

        <a href="#"
           class="menu-toggle
           {{ request()->is('adminpanel/master/target*') ? 'active' : '' }}">

            <i class="fa fa-map"></i>
            Data Target

        </a>

        <div class="submenu
             {{ request()->is('adminpanel/master/target*') ? 'd-block' : '' }}">

            <a href="{{ route('master.target-sasaran.index') }}"
               class="{{ request()->is('adminpanel/master/target*') ? 'active' : '' }}">

                Target Sasaran

            </a>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- LOGOUT                                                    --}}
    {{-- GROUP 1 DAN GROUP 3                                      --}}
    {{-- ========================================================= --}}

    @if (in_array($groupId, [1, 3]))

        <a href="/logout">

            <i class="fa fa-lock"></i>
            Logout

        </a>

    @endif

</div>
