<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title>@yield('title', 'Admin') - Limun Jaya Furniture</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="adm">
  <aside class="side">
    <a href="{{ route('admin.dashboard') }}" class="logo"><span><b>LIMUN JAYA</b><small>ADMIN</small></span></a>
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'on' : '' }}">Dashboard</a>
    <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'on' : '' }}">Produk</a>
    <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'on' : '' }}">Kategori</a>
    <a href="{{ route('admin.settings.edit') }}" class="{{ request()->routeIs('admin.settings.*') ? 'on' : '' }}">Pengaturan Kontak</a>
    <a href="{{ route('home') }}" target="_blank">Lihat Website</a>
    <form method="post" action="{{ route('logout') }}">@csrf<button type="submit">Logout</button></form>
  </aside>
  <div class="main">
    @if(session('success'))<div class="alert ok" role="status">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert err" role="alert">{{ session('error') }}</div>@endif
    @yield('content')
  </div>
</div>
</body>
</html>
