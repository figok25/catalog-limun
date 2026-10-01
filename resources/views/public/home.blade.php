@extends('layouts.public')

@php
    use App\Models\Setting;

    $store = Setting::get('store_name', 'Limun Jaya Furniture');
    $wa = Setting::wa();

    $heroImage = public_path('images/home/livingroom.png');
    $showroomImage = public_path('images/home/showroom.png');
    $bedroomImage = public_path('images/home/kamar.png');
@endphp

@section('content')

<style>
    /* =========================================================
       HOME VISUAL SHOWCASE
       ========================================================= */

    .home-visuals {
        padding-top: 10px;
    }

    .home-visual-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.22fr) minmax(0, 0.78fr);
        gap: 22px;
        align-items: stretch;
    }

    .home-visual-card {
        position: relative;
        overflow: hidden;
        min-height: 280px;
        border-radius: 22px;
        background: #eee;
        box-shadow: 0 18px 45px rgba(21, 31, 27, .10);
    }

    .home-visual-card.large {
        min-height: 460px;
    }

    .home-visual-card img {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
        transition: transform .6s ease;
    }

    .home-visual-card:hover img {
        transform: scale(1.035);
    }

    .home-visual-overlay {
        position: absolute;
        inset: auto 18px 18px 18px;
        padding: 18px 20px;
        color: #fff;
        border-radius: 16px;
        background: linear-gradient(
            180deg,
            rgba(18, 23, 20, 0),
            rgba(18, 23, 20, .78)
        );
    }

    .home-visual-overlay small {
        display: block;
        margin-bottom: 5px;
        font-weight: 700;
        letter-spacing: .08em;
        opacity: .88;
    }

    .home-visual-overlay strong {
        display: block;
        font-size: clamp(1.05rem, 2vw, 1.45rem);
        line-height: 1.2;
    }

    .home-visual-stack {
        display: grid;
        grid-template-rows: 1fr 1fr;
        gap: 22px;
    }

    .home-visual-card.compact {
        min-height: 219px;
    }

    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 900px) {
        .home-visual-grid {
            grid-template-columns: 1fr;
        }

        .home-visual-card.large {
            min-height: 360px;
        }

        .home-visual-stack {
            grid-template-rows: repeat(2, minmax(220px, 1fr));
        }
    }

    @media (max-width: 600px) {
        .home-visual-card.large,
        .home-visual-card.compact {
            min-height: 250px;
            border-radius: 16px;
        }

        .home-visual-overlay {
            inset: auto 12px 12px 12px;
            padding: 14px 15px;
            border-radius: 13px;
        }

        .home-visual-overlay strong {
            font-size: 1rem;
        }
    }
</style>


{{-- =========================================================
     HERO
     ========================================================= --}}

<section
    class="hero {{ file_exists($heroImage) ? 'has-img' : '' }}"
    @if(file_exists($heroImage))
        style="--hero:url('{{ asset('images/home/livingroom.png') }}')"
    @endif
>
    <div class="wrap">

        <div class="hero-copy">

            <small class="k">
                FURNITURE BERKUALITAS
            </small>

            <h1>
                Wujudkan Rumah Impian Anda
                <em>Bersama {{ $store }}</em>
            </h1>

            <p>
                {{ Setting::get('tagline') }}
            </p>

            <div class="cta">

                <a
                    href="{{ route('catalog.index') }}"
                    class="btn btn-gold"
                >
                    Lihat Katalog
                </a>

                @if($wa)

                    <a
                        href="{{ $wa }}"
                        target="_blank"
                        rel="noopener"
                        class="btn btn-line"
                    >
                        Konsultasi via WhatsApp
                    </a>

                @endif

            </div>

        </div>


        {{-- HERO PERKS --}}

        <div class="perks">

            <div>

                <svg viewBox="0 0 24 24">
                    <path d="M2 6h12v10H2zM14 9h4l4 4v3h-8"/>
                    <circle cx="7" cy="18" r="2"/>
                    <circle cx="18" cy="18" r="2"/>
                </svg>

                <span>
                    <b>Pengiriman</b>
                    Seluruh Indonesia
                </span>

            </div>


            <div>

                <svg viewBox="0 0 24 24">
                    <path d="M12 3 4 6v6c0 5 3.5 8 8 9 4.5-1 8-4 8-9V6z"/>
                    <path d="m9 12 2 2 4-4"/>
                </svg>

                <span>
                    <b>Kualitas</b>
                    Terbaik
                </span>

            </div>


            <div>

                <svg viewBox="0 0 24 24">
                    <path d="M3 12V4h8l10 10-8 8z"/>
                    <circle cx="7.5" cy="8.5" r="1.2"/>
                </svg>

                <span>
                    <b>Harga</b>
                    Bersaing
                </span>

            </div>


            <div>

                <svg viewBox="0 0 24 24">
                    <path d="M4 14v-2a8 8 0 0 1 16 0v2"/>
                    <rect x="3" y="14" width="4" height="6" rx="1"/>
                    <rect x="17" y="14" width="4" height="6" rx="1"/>
                </svg>

                <span>
                    <b>Layanan</b>
                    Konsultasi Gratis
                </span>

            </div>

        </div>

    </div>
</section>


{{-- =========================================================
     VISUAL SHOWCASE
     ========================================================= --}}

<section
    class="sec home-visuals"
    aria-label="Inspirasi ruang furniture"
>

    <div class="wrap">

        <div class="sec-head">

            <h2>
                Inspirasi Ruang untuk Rumah Anda
            </h2>

            <i></i>

            <p>
                Lihat bagaimana furniture dapat menyatu dengan
                ruang keluarga, showroom, dan kamar tidur.
            </p>

        </div>


        <div class="home-visual-grid">


            {{-- SHOWROOM --}}

            @if(file_exists($showroomImage))

                <figure class="home-visual-card large">

                    <img
                        src="{{ asset('images/home/showroom.png') }}"
                        alt="Interior showroom furniture modern"
                        loading="lazy"
                    >

                    <figcaption class="home-visual-overlay">

                        <small>
                            SHOWROOM
                        </small>

                        <strong>
                            Ruang yang tertata untuk menemukan
                            furniture yang tepat
                        </strong>

                    </figcaption>

                </figure>

            @endif


            {{-- RIGHT STACK --}}

            <div class="home-visual-stack">


                {{-- LIVING ROOM --}}

                @if(file_exists($heroImage))

                    <figure class="home-visual-card compact">

                        <img
                            src="{{ asset('images/home/livingroom.png') }}"
                            alt="Inspirasi ruang keluarga dengan furniture kayu"
                            loading="lazy"
                        >

                        <figcaption class="home-visual-overlay">

                            <small>
                                RUANG KELUARGA
                            </small>

                            <strong>
                                Hangat, nyaman, dan elegan
                            </strong>

                        </figcaption>

                    </figure>

                @endif


                {{-- BEDROOM --}}

                @if(file_exists($bedroomImage))

                    <figure class="home-visual-card compact">

                        <img
                            src="{{ asset('images/home/kamar.png') }}"
                            alt="Inspirasi kamar tidur dengan furniture kayu"
                            loading="lazy"
                        >

                        <figcaption class="home-visual-overlay">

                            <small>
                                KAMAR TIDUR
                            </small>

                            <strong>
                                Furniture yang mendukung
                                kenyamanan istirahat
                            </strong>

                        </figcaption>

                    </figure>

                @endif

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
     KATEGORI PRODUK
     ========================================================= --}}

<section class="sec">

    <div class="wrap">

        <div class="sec-head">

            <h2>
                Kategori Produk
            </h2>

            <i></i>

            <p>
                Temukan berbagai pilihan furniture sesuai kebutuhan Anda
            </p>

        </div>


        <div class="cats">

            @foreach($categories as $c)

                <a
                    class="cat"
                    href="{{ route('catalog.index', ['kategori' => $c->slug]) }}"
                >

                    <div class="ph">

                        @if($c->cover)

                            <img
                                src="{{ $c->cover }}"
                                alt="Kategori {{ $c->name }}"
                                loading="lazy"
                            >

                        @else

                            <x-placeholder />

                        @endif

                    </div>


                    <div class="tx">

                        <h3>
                            {{ $c->name }}
                        </h3>

                        <p>
                            {{ $c->description }}
                        </p>

                        <span
                            class="go"
                            aria-hidden="true"
                        >
                            &rarr;
                        </span>

                    </div>

                </a>

            @endforeach

        </div>

    </div>

</section>


{{-- =========================================================
     PRODUK UNGGULAN
     ========================================================= --}}

<section
    class="sec"
    style="padding-top:8px"
>

    <div class="wrap">

        <div class="row-head">

            <div>

                <h2>
                    Produk Unggulan
                </h2>

                <p>
                    Pilihan furniture terbaik dengan desain modern
                    dan kualitas terjamin
                </p>

            </div>


            <a
                href="{{ route('catalog.index') }}"
                class="btn btn-out btn-sm"
            >
                Lihat Semua Produk
            </a>

        </div>


        @if($featured->isEmpty())

            <div class="empty">

                Belum ada produk unggulan.
                Tandai produk sebagai unggulan
                dari panel admin.

            </div>

        @else

            <div class="grid g5">

                @foreach($featured as $p)

                    <x-product-card
                        :product="$p"
                        :badge="true"
                    />

                @endforeach

            </div>

        @endif

    </div>

</section>


{{-- =========================================================
     TENTANG KAMI
     ========================================================= --}}

<section
    class="sec"
    style="padding-bottom:0"
>

    <div class="about">


        {{-- IMAGE / EXPERIENCE --}}

        <div
    class="pic"
    style="background-image: url('{{ asset('images/home/kamar.png') }}');"
>
    @if(Setting::get('years_experience'))
        <div class="years">
            <b>{{ Setting::get('years_experience') }}+</b>
            <span>
                <strong style="color:#fff">Tahun Pengalaman</strong>
                Melayani pelanggan di seluruh Indonesia
            </span>
        </div>
    @endif
</div>


        {{-- CONTENT --}}

        <div class="tx">

            <span class="k">
                TENTANG KAMI
            </span>

            <h2>
                {{ $store }}
            </h2>

            <p>
                {{ Setting::get('about') }}
            </p>


            <div class="vals">


                {{-- VALUE 1 --}}

                <div class="val">

                    <svg
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >

                        <path d="M12 20h9"/>

                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>

                    </svg>

                    <div>

                        <b>
                            Desain Modern
                        </b>

                        <span>
                            Sesuai tren dan kebutuhan
                        </span>

                    </div>

                </div>


                {{-- VALUE 2 --}}

                <div class="val">

                    <svg
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >

                        <path d="M6 3h12l4 6-10 12L2 9z"/>

                        <path d="M2 9h20"/>

                    </svg>

                    <div>

                        <b>
                            Material Berkualitas
                        </b>

                        <span>
                            Tahan lama dan kuat
                        </span>

                    </div>

                </div>


                {{-- VALUE 3 --}}

                <div class="val">

                    <svg
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >

                        <circle
                            cx="12"
                            cy="8"
                            r="5"
                        />

                        <path
                            d="M8.2 12.5 7 21l5-3 5 3-1.2-8.5"
                        />

                    </svg>

                    <div>

                        <b>
                            Layanan Profesional
                        </b>

                        <span>
                            Konsultasi hingga after sales
                        </span>

                    </div>

                </div>

            </div>


            <a
                href="{{ route('about') }}"
                class="btn btn-out"
            >
                Selengkapnya Tentang Kami
            </a>

        </div>

    </div>

</section>


{{-- =========================================================
     CTA
     ========================================================= --}}

<section class="band">

    <div class="wrap">

        <div>

            <h2>
                Ingin Furniture Sesuai Keinginan Anda?
            </h2>

            <p>
                Konsultasikan kebutuhan furniture Anda sekarang juga.
                Tim kami siap membantu memberikan solusi terbaik.
            </p>

        </div>


        @if($wa)

            <a
                href="{{ $wa }}"
                target="_blank"
                rel="noopener"
                class="btn btn-gold"
            >

                <x-wa-icon :size="20" />

                Konsultasi Sekarang

            </a>

        @else

            <a
                href="{{ route('contact') }}"
                class="btn btn-gold"
            >
                Lihat Kontak Kami
            </a>

        @endif

    </div>

</section>

@endsection