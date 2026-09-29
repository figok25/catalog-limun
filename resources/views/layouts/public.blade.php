@php use App\Models\Setting; $store = Setting::get('store_name', 'Limun Jaya Furniture'); $wa = Setting::wa(); @endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', $store . ' - Furniture Berkualitas')</title>
<meta name="description" content="@yield('description', Str::limit(Setting::get('tagline', ''), 155))">
<meta property="og:title" content="@yield('title', $store)">
<meta property="og:description" content="@yield('description', Str::limit(Setting::get('tagline', ''), 155))">
<meta property="og:type" content="website">
@hasSection('og_image')<meta property="og:image" content="@yield('og_image')">@endif
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<header class="site-head"><div class="wrap">
  <a href="{{ route('home') }}" class="logo" aria-label="{{ $store }}">
    <svg viewBox="0 0 48 48" fill="none" stroke="#c9a45c" stroke-width="3" stroke-linejoin="round"><path d="M5 24 24 7l19 17"/><path d="M11 21v20h26V21"/><path d="M19 41V29h10v12"/></svg>
    <span><b>LIMUN JAYA</b><small>FURNITURE</small></span>
  </a>
  <nav class="nav" id="nav" aria-label="Menu utama">
    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'on' : '' }}">Beranda</a>
    <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'on' : '' }}">Tentang Kami</a>
    <a href="{{ route('catalog.index') }}" class="{{ request()->routeIs('catalog.*') ? 'on' : '' }}">Katalog</a>
    <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'on' : '' }}">Kontak</a>
  </nav>
  @if($wa)<a href="{{ $wa }}" target="_blank" rel="noopener" class="btn btn-dark head-wa">Hubungi Kami</a>@endif
  <button class="menu-btn" aria-label="Buka menu" aria-controls="nav" onclick="document.getElementById('nav').classList.toggle('open')">&#9776;</button>
</div></header>

<main>@yield('content')</main>

<footer class="foot"><div class="wrap">
  <div class="cols">
    <div><h4>{{ $store }}</h4><p>{{ Str::limit(Setting::get('tagline', ''), 160) }}</p></div>
    <div><h4>Navigasi</h4><ul>
      <li><a href="{{ route('home') }}">Beranda</a></li><li><a href="{{ route('about') }}">Tentang Kami</a></li>
      <li><a href="{{ route('catalog.index') }}">Katalog</a></li><li><a href="{{ route('contact') }}">Kontak</a></li></ul></div>
    <div><h4>Kontak</h4><ul>
      @if(Setting::get('address'))<li>{{ Setting::get('address') }}</li>@endif
      @if(Setting::get('hours'))<li>{{ Setting::get('hours') }}</li>@endif
      @if($wa)<li><a href="{{ $wa }}" target="_blank" rel="noopener">WhatsApp: {{ Setting::get('whatsapp') }}</a></li>@endif
      @if(Setting::get('instagram'))<li><a href="{{ Setting::get('instagram') }}" target="_blank" rel="noopener">Instagram</a></li>@endif</ul></div>
  </div>
  <div class="cp">&copy; {{ date('Y') }} {{ $store }}. Semua hak dilindungi.</div>
</div></footer>
@if($wa)<a class="wa-float" href="{{ $wa }}" target="_blank" rel="noopener">Chat WhatsApp</a>@endif
</body>
</html>
