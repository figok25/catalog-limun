<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">

    <title>@yield('title', 'Admin') - Limun Jaya Furniture</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap"
        rel="stylesheet"
    >

    {{-- Cache busting agar hosting selalu membaca app.css terbaru --}}
    <link
        rel="stylesheet"
        href="{{ asset('css/app.css') }}?v={{ @filemtime(public_path('css/app.css')) ?: time() }}"
    >

    @include('partials.favicon')

    <style>
        /*
        |--------------------------------------------------------------------------
        | ADMIN LAYOUT SAFETY
        |--------------------------------------------------------------------------
        | CSS ini sengaja di-scope ke .adm agar tidak mengganggu
        | navbar / layout website publik.
        */

        /* =========================================================
           DESKTOP DEFAULT
           ========================================================= */

        .adm {
            display: grid !important;
            grid-template-columns: 230px minmax(0, 1fr) !important;
            width: 100% !important;
            min-height: 100vh !important;
            background: #f5f1e9 !important;
            overflow-x: hidden !important;
        }

        .adm .adm-top,
        .adm .adm-overlay {
            display: none !important;
        }

        .adm .side {
            position: sticky !important;
            top: 0 !important;
            left: auto !important;

            width: 230px !important;
            height: 100vh !important;
            min-height: 100vh !important;

            display: flex !important;
            flex-direction: column !important;
            flex-wrap: nowrap !important;

            padding: 22px 16px !important;
            margin: 0 !important;

            background: var(--green-d) !important;
            color: #fff !important;

            overflow-y: auto !important;
            overflow-x: hidden !important;

            transform: none !important;
            visibility: visible !important;
            box-shadow: none !important;
        }

        .adm .side .logo {
            display: flex !important;
            width: 100% !important;
            margin: 0 0 22px !important;
        }

        .adm .side a,
        .adm .side button {
            display: flex !important;
            align-items: center !important;

            width: 100% !important;
            min-height: 44px !important;

            margin: 0 !important;
            padding: 10px 12px !important;

            border: 0 !important;
            border-radius: 8px !important;

            background: transparent !important;
            color: #d5e0db !important;

            font: inherit !important;
            font-size: .92rem !important;
            text-align: left !important;

            cursor: pointer !important;
        }

        .adm .side a:hover,
        .adm .side a.on,
        .adm .side button:hover {
            background: rgba(255,255,255,.10) !important;
            color: #fff !important;
        }

        .adm .side form {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .adm .main {
            width: 100% !important;
            min-width: 0 !important;
            padding: 28px !important;
            box-sizing: border-box !important;
        }


        /* =========================================================
           MOBILE / TABLET
           <= 900px
           ========================================================= */

        @media screen and (max-width: 900px) {

            .adm {
                display: block !important;
                width: 100% !important;
                min-height: 100vh !important;
                background: #f5f1e9 !important;
                overflow-x: hidden !important;
            }

            /* -----------------------------------------------------
               MOBILE TOPBAR
               ----------------------------------------------------- */

            .adm .adm-top {
                position: sticky !important;
                top: 0 !important;
                z-index: 2000 !important;

                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;

                width: 100% !important;
                min-height: 60px !important;

                gap: 12px !important;

                padding: 9px 12px !important;
                margin: 0 !important;

                background: var(--green-d) !important;
                color: #fff !important;

                border-bottom: 1px solid rgba(255,255,255,.08) !important;

                box-sizing: border-box !important;
            }

            .adm .adm-brand {
                display: inline-flex !important;
                flex-direction: column !important;

                flex: 1 1 auto !important;
                min-width: 0 !important;

                color: #fff !important;
                text-decoration: none !important;
            }

            .adm .adm-brand b {
                display: block !important;

                color: #fff !important;

                font-family: var(--serif) !important;
                font-size: 1rem !important;
                font-weight: 700 !important;

                line-height: 1 !important;
                letter-spacing: .02em !important;
            }

            .adm .adm-brand small {
                display: block !important;

                margin-top: 4px !important;

                color: var(--gold) !important;

                font-size: .55rem !important;
                font-weight: 700 !important;

                line-height: 1 !important;
                letter-spacing: .30em !important;
            }


            /* -----------------------------------------------------
               HAMBURGER
               ----------------------------------------------------- */

            .adm .adm-burger {
                position: relative !important;

                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                flex-direction: column !important;

                width: 42px !important;
                height: 42px !important;
                min-width: 42px !important;
                max-width: 42px !important;
                flex: 0 0 42px !important;

                gap: 5px !important;

                margin: 0 !important;
                padding: 0 !important;

                border: 1px solid rgba(255,255,255,.28) !important;
                border-radius: 8px !important;

                background: transparent !important;
                color: #fff !important;

                cursor: pointer !important;

                appearance: none !important;
                -webkit-appearance: none !important;

                box-sizing: border-box !important;
            }

            .adm .adm-burger span {
                display: block !important;

                width: 20px !important;
                height: 2px !important;

                margin: 0 !important;
                padding: 0 !important;

                background: #fff !important;

                border-radius: 2px !important;

                transition:
                    transform .2s ease,
                    opacity .2s ease !important;
            }

            .adm .adm-burger:hover {
                background: rgba(255,255,255,.08) !important;
            }

            .adm .adm-burger:focus-visible {
                outline: 2px solid var(--gold) !important;
                outline-offset: 2px !important;
            }


            /* -----------------------------------------------------
               HAMBURGER ACTIVE STATE
               ----------------------------------------------------- */

            .adm .adm-burger.is-open span:nth-child(1) {
                transform: translateY(7px) rotate(45deg) !important;
            }

            .adm .adm-burger.is-open span:nth-child(2) {
                opacity: 0 !important;
            }

            .adm .adm-burger.is-open span:nth-child(3) {
                transform: translateY(-7px) rotate(-45deg) !important;
            }


            /* -----------------------------------------------------
               OVERLAY
               ----------------------------------------------------- */

            .adm .adm-overlay {
                position: fixed !important;
                inset: 0 !important;

                z-index: 2100 !important;

                display: block !important;

                width: 100vw !important;
                height: 100vh !important;

                visibility: hidden !important;
                opacity: 0 !important;

                pointer-events: none !important;

                background: rgba(10,20,18,.55) !important;

                transition:
                    opacity .20s ease,
                    visibility .20s ease !important;
            }

            .adm .adm-overlay.show {
                visibility: visible !important;
                opacity: 1 !important;
                pointer-events: auto !important;
            }


            /* -----------------------------------------------------
               MOBILE SIDEBAR
               ----------------------------------------------------- */

            .adm .side {
                position: fixed !important;

                top: 0 !important;
                left: 0 !important;
                bottom: 0 !important;

                z-index: 2200 !important;

                width: min(280px, 84vw) !important;
                height: 100dvh !important;
                min-height: 100dvh !important;

                display: flex !important;
                flex-direction: column !important;
                flex-wrap: nowrap !important;
                align-items: stretch !important;

                gap: 4px !important;

                margin: 0 !important;
                padding: 78px 14px 20px !important;

                background: var(--green-d) !important;
                color: #fff !important;

                overflow-x: hidden !important;
                overflow-y: auto !important;

                transform: translate3d(-105%,0,0) !important;
                visibility: hidden !important;

                box-shadow: 14px 0 35px rgba(0,0,0,.25) !important;

                transition:
                    transform .24s ease,
                    visibility .24s ease !important;

                box-sizing: border-box !important;
            }

            .adm .side.open {
                transform: translate3d(0,0,0) !important;
                visibility: visible !important;
            }

            .adm .side .logo {
                display: flex !important;

                width: 100% !important;

                margin: 0 0 18px !important;
                padding: 12px 10px !important;
            }

            .adm .side .logo b {
                color: #fff !important;
            }

            .adm .side .logo small {
                color: var(--gold) !important;
            }

            .adm .side a,
            .adm .side button {
                display: flex !important;
                align-items: center !important;

                width: 100% !important;
                min-height: 44px !important;
                flex: 0 0 auto !important;

                margin: 0 !important;
                padding: 10px 12px !important;

                border-radius: 8px !important;

                background: transparent !important;
                color: #d5e0db !important;

                box-sizing: border-box !important;
            }

            .adm .side a:hover,
            .adm .side a.on,
            .adm .side button:hover {
                background: rgba(255,255,255,.10) !important;
                color: #fff !important;
            }

            .adm .side form {
                display: block !important;

                width: 100% !important;

                margin: 0 !important;
                padding: 0 !important;
            }


            /* -----------------------------------------------------
               MAIN MOBILE
               ----------------------------------------------------- */

            .adm .main {
                display: block !important;

                width: 100% !important;
                min-width: 0 !important;

                margin: 0 !important;
                padding: 20px 16px !important;

                box-sizing: border-box !important;
            }


            /* -----------------------------------------------------
               STATISTICS
               ----------------------------------------------------- */

            .adm .stats {
                display: grid !important;

                grid-template-columns:
                    repeat(2, minmax(0, 1fr)) !important;

                gap: 12px !important;

                width: 100% !important;
            }

            .adm .stat {
                min-width: 0 !important;
                padding: 16px !important;
                box-sizing: border-box !important;
            }

            .adm .stat b {
                font-size: 1.65rem !important;
            }


            /* -----------------------------------------------------
               HEADER CONTENT
               ----------------------------------------------------- */

            .adm .head-row {
                display: flex !important;
                flex-direction: column !important;
                align-items: stretch !important;

                gap: 12px !important;
            }

            .adm .head-row .btn {
                width: 100% !important;
                justify-content: center !important;
                box-sizing: border-box !important;
            }


            /* -----------------------------------------------------
               FILTER
               ----------------------------------------------------- */

            .adm .filter-form {
                display: grid !important;
                grid-template-columns: 1fr !important;
                gap: 10px !important;
            }


            /* -----------------------------------------------------
               PANEL
               ----------------------------------------------------- */

            .adm .panel {
                width: 100% !important;
                max-width: 100% !important;

                overflow: hidden !important;

                box-sizing: border-box !important;
            }


            /* -----------------------------------------------------
               TABLE
               ----------------------------------------------------- */

            .adm .tbl-w {
                width: 100% !important;

                overflow-x: auto !important;

                -webkit-overflow-scrolling: touch !important;
            }

            .adm .tbl-w table {
                min-width: 650px !important;
            }

            body.adm-lock {
                overflow: hidden !important;
            }
        }


        /* =========================================================
           SMALL PHONE
           ========================================================= */

        @media screen and (max-width: 560px) {

            .adm .adm-top {
                min-height: 58px !important;
                padding: 8px 11px !important;
            }

            .adm .adm-burger {
                width: 40px !important;
                height: 40px !important;
                min-width: 40px !important;
                max-width: 40px !important;
                flex-basis: 40px !important;
            }

            .adm .adm-burger span {
                width: 19px !important;
            }

            .adm .main {
                padding: 16px 12px !important;
            }

            .adm .stats {
                grid-template-columns: 1fr 1fr !important;
                gap: 10px !important;
            }

            .adm .stat {
                padding: 14px !important;
            }

            .adm .stat b {
                font-size: 1.5rem !important;
            }

            .adm .panel {
                padding: 16px !important;
                border-radius: 10px !important;
            }

            .adm .side {
                width: min(270px, 84vw) !important;
            }

            .adm input,
            .adm select,
            .adm textarea {
                font-size: 16px !important;
            }
        }


        /* =========================================================
           REDUCE MOTION
           ========================================================= */

        @media (prefers-reduced-motion: reduce) {

            .adm *,
            .adm *::before,
            .adm *::after {
                transition: none !important;
                animation: none !important;
            }
        }
    </style>
</head>

<body>

<div class="adm">

    {{-- =========================================================
         MOBILE HEADER
         ========================================================= --}}
    <header class="adm-top">

        <a
            href="{{ route('admin.dashboard') }}"
            class="adm-brand"
            aria-label="Dashboard Admin"
        >
            <b>LIMUN JAYA</b>
            <small>ADMIN</small>
        </a>

        <button
            type="button"
            class="adm-burger"
            id="admBurger"
            aria-label="Buka menu admin"
            aria-expanded="false"
            aria-controls="admSide"
        >
            <span></span>
            <span></span>
            <span></span>
        </button>

    </header>


    {{-- =========================================================
         OVERLAY MOBILE
         ========================================================= --}}
    <div
        class="adm-overlay"
        id="admOverlay"
        aria-hidden="true"
    ></div>


    {{-- =========================================================
         SIDEBAR
         ========================================================= --}}
    <aside
        class="side"
        id="admSide"
        aria-label="Navigasi admin"
    >

        <a
            href="{{ route('admin.dashboard') }}"
            class="logo"
            aria-label="Dashboard Admin"
        >
            <span>
                <b>LIMUN JAYA</b>
                <small>ADMIN</small>
            </span>
        </a>


        <a
            href="{{ route('admin.dashboard') }}"
            class="{{ request()->routeIs('admin.dashboard') ? 'on' : '' }}"
        >
            Dashboard
        </a>


        <a
            href="{{ route('admin.products.index') }}"
            class="{{ request()->routeIs('admin.products.*') ? 'on' : '' }}"
        >
            Produk
        </a>


        <a
            href="{{ route('admin.categories.index') }}"
            class="{{ request()->routeIs('admin.categories.*') ? 'on' : '' }}"
        >
            Kategori
        </a>


        <a
            href="{{ route('admin.portfolios.index') }}"
            class="{{ request()->routeIs('admin.portfolios.*') ? 'on' : '' }}"
        >
            Portofolio
        </a>


        <a
            href="{{ route('admin.settings.edit') }}"
            class="{{ request()->routeIs('admin.settings.*') ? 'on' : '' }}"
        >
            Pengaturan Kontak
        </a>


        <a
            href="{{ route('home') }}"
            target="_blank"
            rel="noopener noreferrer"
        >
            Lihat Website
        </a>


        <form
            method="post"
            action="{{ route('logout') }}"
        >
            @csrf

            <button type="submit">
                Logout
            </button>
        </form>

    </aside>


    {{-- =========================================================
         MAIN CONTENT
         ========================================================= --}}
    <main class="main">

        @if(session('success'))
            <div
                class="alert ok"
                role="status"
            >
                {{ session('success') }}
            </div>
        @endif


        @if(session('error'))
            <div
                class="alert err"
                role="alert"
            >
                {{ session('error') }}
            </div>
        @endif


        @yield('content')

    </main>

</div>


<script>
(function () {

    'use strict';

    var side = document.getElementById('admSide');
    var btn = document.getElementById('admBurger');
    var overlay = document.getElementById('admOverlay');

    if (!side || !btn || !overlay) {
        return;
    }


    function isMobile() {
        return window.innerWidth <= 900;
    }


    function setOpen(open) {

        side.classList.toggle('open', open);
        btn.classList.toggle('is-open', open);
        overlay.classList.toggle('show', open);

        document.body.classList.toggle(
            'adm-lock',
            open && isMobile()
        );

        btn.setAttribute(
            'aria-expanded',
            open ? 'true' : 'false'
        );

        btn.setAttribute(
            'aria-label',
            open
                ? 'Tutup menu admin'
                : 'Buka menu admin'
        );

        overlay.setAttribute(
            'aria-hidden',
            open ? 'false' : 'true'
        );
    }


    function closeMenu() {
        setOpen(false);
    }


    /* ---------------------------------------------------------
       HAMBURGER
       --------------------------------------------------------- */

    btn.addEventListener('click', function () {

        if (!isMobile()) {
            return;
        }

        var open =
            side.classList.contains('open');

        setOpen(!open);
    });


    /* ---------------------------------------------------------
       OVERLAY
       --------------------------------------------------------- */

    overlay.addEventListener('click', function () {
        closeMenu();
    });


    /* ---------------------------------------------------------
       MENU LINKS
       --------------------------------------------------------- */

    side.querySelectorAll('a').forEach(function (link) {

        link.addEventListener('click', function () {

            if (isMobile()) {
                closeMenu();
            }

        });

    });


    /* ---------------------------------------------------------
       ESCAPE
       --------------------------------------------------------- */

    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {
            closeMenu();
        }

    });


    /* ---------------------------------------------------------
       RESIZE
       --------------------------------------------------------- */

    var resizeTimer;

    window.addEventListener('resize', function () {

        clearTimeout(resizeTimer);

        resizeTimer = setTimeout(function () {

            if (!isMobile()) {
                closeMenu();
            }

        }, 80);

    });


    /* ---------------------------------------------------------
       INITIAL STATE
       --------------------------------------------------------- */

    closeMenu();

})();
</script>

</body>
</html>