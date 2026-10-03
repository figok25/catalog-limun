<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title>@yield('title', 'Admin') - Limun Jaya Furniture</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
@include('partials.favicon')
</head>
<body>
<div class="adm">
  <header class="adm-top">
    <a href="{{ route('admin.dashboard') }}" class="adm-brand"><b>LIMUN JAYA</b><small>ADMIN</small></a>
    <button type="button" class="adm-burger" id="admBurger" aria-label="Buka menu" aria-expanded="false" aria-controls="admSide"><span></span><span></span><span></span></button>
  </header>
  <div class="adm-overlay" id="admOverlay"></div>
  <aside class="side" id="admSide">
    <a href="{{ route('admin.dashboard') }}" class="logo"><span><b>LIMUN JAYA</b><small>ADMIN</small></span></a>
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'on' : '' }}">Dashboard</a>
    <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'on' : '' }}">Produk</a>
    <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'on' : '' }}">Kategori</a>
    <a href="{{ route('admin.portfolios.index') }}" class="{{ request()->routeIs('admin.portfolios.*') ? 'on' : '' }}">Portofolio</a>
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
<script>
(function(){
  var side=document.getElementById('admSide'),btn=document.getElementById('admBurger'),ov=document.getElementById('admOverlay');
  function set(open){
    side.classList.toggle('open',open);
    ov.classList.toggle('show',open);
    document.body.classList.toggle('adm-lock',open);
    btn.setAttribute('aria-expanded',open);
  }
  btn.addEventListener('click',function(){set(!side.classList.contains('open'));});
  ov.addEventListener('click',function(){set(false);});
  document.addEventListener('keydown',function(e){if(e.key==='Escape')set(false);});
  window.addEventListener('resize',function(){if(window.innerWidth>900)set(false);});
})();
</script>
</body>
</html>
