@extends('layouts.public')
@php use App\Models\Setting; $store = Setting::get('store_name', 'Limun Jaya Furniture'); $wa = Setting::wa(); $hasHero = file_exists(public_path('images/hero.jpg')); @endphp
@section('content')
<section class="hero {{ $hasHero ? 'has-img' : '' }}" @if($hasHero) style="--hero:url('{{ asset('images/hero.jpg') }}')" @endif>
  <div class="wrap">
    <div class="hero-copy">
      <small class="k">FURNITURE BERKUALITAS</small>
      <h1>Wujudkan Rumah Impian Anda <em>Bersama {{ $store }}</em></h1>
      <p>{{ Setting::get('tagline') }}</p>
      <div class="cta">
        <a href="{{ route('catalog.index') }}" class="btn btn-gold">Lihat Katalog</a>
        @if($wa)<a href="{{ $wa }}" target="_blank" rel="noopener" class="btn btn-line">Konsultasi via WhatsApp</a>@endif
      </div>
    </div>
    <div class="perks">
      <div><svg viewBox="0 0 24 24"><path d="M2 6h12v10H2zM14 9h4l4 4v3h-8"/><circle cx="7" cy="18" r="2"/><circle cx="18" cy="18" r="2"/></svg><span><b>Pengiriman</b>Seluruh Indonesia</span></div>
      <div><svg viewBox="0 0 24 24"><path d="M12 3 4 6v6c0 5 3.5 8 8 9 4.5-1 8-4 8-9V6z"/><path d="m9 12 2 2 4-4"/></svg><span><b>Kualitas</b>Terbaik</span></div>
      <div><svg viewBox="0 0 24 24"><path d="M3 12V4h8l10 10-8 8z"/><circle cx="7.5" cy="8.5" r="1.2"/></svg><span><b>Harga</b>Bersaing</span></div>
      <div><svg viewBox="0 0 24 24"><path d="M4 14v-2a8 8 0 0 1 16 0v2"/><rect x="3" y="14" width="4" height="6" rx="1"/><rect x="17" y="14" width="4" height="6" rx="1"/></svg><span><b>Layanan</b>Konsultasi Gratis</span></div>
    </div>
  </div>
</section>

<section class="sec"><div class="wrap">
  <div class="sec-head"><h2>Kategori Produk</h2><i></i><p>Temukan berbagai pilihan furniture sesuai kebutuhan Anda</p></div>
  <div class="cats">
    @foreach($categories as $c)
    <a class="cat" href="{{ route('catalog.index', ['kategori' => $c->slug]) }}">
      <div class="ph">@if($c->cover)<img src="{{ $c->cover }}" alt="Kategori {{ $c->name }}" loading="lazy">@else<x-placeholder />@endif</div>
      <div class="tx"><h3>{{ $c->name }}</h3><p>{{ $c->description }}</p><span class="go" aria-hidden="true">&rarr;</span></div>
    </a>
    @endforeach
  </div>
</div></section>

<section class="sec" style="padding-top:8px"><div class="wrap">
  <div class="row-head">
    <div><h2>Produk Unggulan</h2><p>Pilihan furniture terbaik dengan desain modern dan kualitas terjamin</p></div>
    <a href="{{ route('catalog.index') }}" class="btn btn-out btn-sm">Lihat Semua Produk</a>
  </div>
  @if($featured->isEmpty())
    <div class="empty">Belum ada produk unggulan. Tandai produk sebagai unggulan dari panel admin.</div>
  @else
    <div class="grid g5">@foreach($featured as $p)<x-product-card :product="$p" :badge="true" />@endforeach</div>
  @endif
</div></section>

<section class="sec" style="padding-bottom:0">
  <div class="about">
    <div class="pic">
      @if(Setting::get('years_experience'))
      <div class="years"><b>{{ Setting::get('years_experience') }}+</b><span><strong style="color:#fff">Tahun Pengalaman</strong>Melayani pelanggan di seluruh Indonesia</span></div>
      @endif
    </div>
    <div class="tx">
      <span class="k">TENTANG KAMI</span>
      <h2>{{ $store }}</h2>
      <p>{{ Setting::get('about') }}</p>
      <div class="vals">
        <div><b>Desain Modern</b><span>Sesuai tren dan kebutuhan</span></div>
        <div><b>Material Berkualitas</b><span>Tahan lama dan kuat</span></div>
        <div><b>Layanan Profesional</b><span>Konsultasi hingga after sales</span></div>
      </div>
      <a href="{{ route('about') }}" class="btn btn-out">Selengkapnya Tentang Kami</a>
    </div>
  </div>
</section>

<section class="band"><div class="wrap">
  <div><h2>Ingin Furniture Sesuai Keinginan Anda?</h2><p>Konsultasikan kebutuhan furniture Anda sekarang juga. Tim kami siap membantu memberikan solusi terbaik.</p></div>
  @if($wa)<a href="{{ $wa }}" target="_blank" rel="noopener" class="btn btn-gold">Konsultasi Sekarang</a>
  @else<a href="{{ route('contact') }}" class="btn btn-gold">Lihat Kontak Kami</a>@endif
</div></section>
@endsection
