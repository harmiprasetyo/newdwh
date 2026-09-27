<aside class="rme-sidebar">

    {{-- Brand --}}
    <div class="rme-sidebar-brand">

        <div class="rme-brand-icon">
            <i class="bi bi-heart-pulse"></i>
        </div>

        <div class="rme-brand-text">
            <div class="rme-brand-title">
                MNCH
            </div>

            <div class="rme-brand-subtitle">
                Dashboard RME
            </div>
        </div>

    </div>


    {{-- Navigation --}}
    <nav class="rme-sidebar-nav">

        <div class="rme-menu-label">
            MENU UTAMA
        </div>


        <a
            href="/homepage"
            id="home"
            class="rme-sidebar-link"
        >
            <span class="rme-sidebar-icon">
                <i class="bi bi-house"></i>
            </span>

            <span class="rme-sidebar-text">
                Beranda
            </span>
        </a>


        <a
            href="/dashboard"
            id="anak"
            class="rme-sidebar-link"
        >
            <span class="rme-sidebar-icon">
                <i class="bi bi-grid"></i>
            </span>

            <span class="rme-sidebar-text">
                Dashboard
            </span>
        </a>


        <a
            href="/datarme"
            id="rme"
            class="rme-sidebar-link active"
        >
            <span class="rme-sidebar-icon">
                <i class="bi bi-file-medical"></i>
            </span>

            <span class="rme-sidebar-text">
                Lihat Data RME
            </span>
        </a>

    </nav>


    {{-- Logout --}}
    <div class="rme-sidebar-footer">

        <button
            type="button"
            id="btnlogout"
            class="rme-sidebar-link rme-logout"
        >

            <span class="rme-sidebar-icon">
                <i class="bi bi-box-arrow-right"></i>
            </span>

            <span class="rme-sidebar-text">
                Logout
            </span>

        </button>

    </div>

</aside>
