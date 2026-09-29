@extends('layouts.public')
@section('title', $product->name . ' - ' . \App\Models\Setting::get('store_name', 'Limun Jaya Furniture'))
@section('description', Str::limit(strip_tags($product->description ?? $product->name), 155))
@if($product->image_url)@section('og_image', $product->image_url)@endif
@section('content')
@php $gallery = $product->gallery_urls; @endphp
<section class="sec"><div class="wrap">
  <div class="crumb"><a href="{{ route('home') }}">Beranda</a> / <a href="{{ route('catalog.index') }}">Katalog</a> / {{ $product->name }}</div>
  <div class="detail">
    <div id="galBox" class="gal">
      @if(count($gallery))
      <div class="ph zoomable" id="galFrame" role="button" tabindex="0" aria-label="Perbesar foto produk">
        <img id="galMain" src="{{ $gallery[0] }}" alt="{{ $product->name }}">
        <span class="zoom-hint" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5M11 8v6M8 11h6"/></svg></span>
      </div>
      @else
      <div class="ph"><x-placeholder /></div>
      @endif
      @if(count($gallery) > 1)
      <div class="thumbs" role="group" aria-label="Foto produk">
        @foreach($gallery as $i => $url)
          <button type="button" class="th {{ $i === 0 ? 'on' : '' }}" data-src="{{ $url }}" aria-label="Lihat foto {{ $i + 1 }}"><img src="{{ $url }}" alt="" loading="lazy"></button>
        @endforeach
      </div>
      @endif
    </div>
    <div>
      <a class="chip" href="{{ route('catalog.index', ['kategori' => $product->category->slug]) }}">{{ $product->category->name }}</a>
      <h1 style="margin-top:14px">{{ $product->name }}</h1>
      <div class="price {{ $product->price === null ? 'ask' : '' }}"><span class="now">{{ $product->price_label }}</span>@if($product->has_discount)<s class="old">{{ $product->original_price_label }}</s><span class="save">Hemat {{ $product->discount_badge_percent }}%</span>@endif</div>
      @if($product->description)<p class="desc">{{ $product->description }}</p>@endif
      <p style="margin-top:24px">
        @if($product->whatsapp_url)<a class="btn btn-dark" href="{{ $product->whatsapp_url }}" target="_blank" rel="noopener">Tanya via WhatsApp</a>
        @else<a class="btn btn-dark" href="{{ route('contact') }}">Lihat Kontak Toko</a>@endif
      </p>
    </div>
  </div>
  @if($related->isNotEmpty())
  <div style="margin-top:56px"><div class="row-head"><h2>Produk Terkait</h2></div>
    <div class="grid">@foreach($related as $p)<x-product-card :product="$p" />@endforeach</div></div>
  @endif
</div></section>

@if(count($gallery))
<div class="lb" id="lb" hidden role="dialog" aria-modal="true" aria-label="Foto produk diperbesar">
  <button class="lb-x" type="button" aria-label="Tutup">&times;</button>
  @if(count($gallery) > 1)
  <button class="lb-nav prev" type="button" aria-label="Foto sebelumnya">&#8249;</button>
  <button class="lb-nav next" type="button" aria-label="Foto berikutnya">&#8250;</button>
  @endif
  <div class="lb-stage" id="lbStage"><img id="lbImg" alt="{{ $product->name }}" draggable="false"></div>
  <div class="lb-cap"><span id="lbCount"></span> &middot; Klik atau scroll untuk zoom</div>
</div>
<script>
(function () {
  var urls = @json($gallery), n = urls.length;
  var main = document.getElementById('galMain');
  if (!main || !n) return;

  var box = document.getElementById('galBox'), frame = document.getElementById('galFrame'),
      thumbs = document.querySelectorAll('.thumbs .th'),
      lb = document.getElementById('lb'), stage = document.getElementById('lbStage'),
      img = document.getElementById('lbImg'), cnt = document.getElementById('lbCount'),
      btnX = lb.querySelector('.lb-x'), prev = lb.querySelector('.prev'), next = lb.querySelector('.next');
  var idx = 0, DELAY = 4000, timer = null, hovering = false, lbOpen = false;
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  urls.forEach(function (u) { var i = new Image(); i.src = u; }); // preload agar pergantian mulus

  /* ---------- Ganti foto utama ---------- */
  function setMain(i, instant) {
    idx = (i + n) % n;
    thumbs.forEach(function (t, k) { t.classList.toggle('on', k === idx); });
    if (instant) { main.src = urls[idx]; return; }
    main.classList.add('fade');
    setTimeout(function () { main.src = urls[idx]; main.classList.remove('fade'); }, 180);
  }

  /* ---------- Ganti otomatis (jeda saat kursor di atas galeri, lightbox terbuka, atau tab tersembunyi) ---------- */
  function stop() { if (timer) { clearInterval(timer); timer = null; } }
  function refresh() {
    stop();
    if (n > 1 && !reduce && !hovering && !lbOpen && !document.hidden) {
      timer = setInterval(function () { setMain(idx + 1); }, DELAY);
    }
  }
  box.addEventListener('pointerenter', function (e) { if (e.pointerType === 'mouse') { hovering = true; refresh(); } });
  box.addEventListener('pointerleave', function (e) { if (e.pointerType === 'mouse') { hovering = false; refresh(); } });
  document.addEventListener('visibilitychange', refresh);
  thumbs.forEach(function (t, k) { t.addEventListener('click', function () { setMain(k); refresh(); }); });

  /* ---------- Lightbox + zoom ---------- */
  var s = 1, tx = 0, ty = 0, dragging = false, moved = false, sx = 0, sy = 0, stx = 0, sty = 0;
  function apply() {
    img.style.transform = 'translate(' + tx + 'px,' + ty + 'px) scale(' + s + ')';
    stage.classList.toggle('zoomed', s > 1);
  }
  function clamp() {
    var mx = img.offsetWidth * (s - 1) / 2, my = img.offsetHeight * (s - 1) / 2;
    tx = Math.max(-mx, Math.min(mx, tx));
    ty = Math.max(-my, Math.min(my, ty));
  }
  function reset() { s = 1; tx = 0; ty = 0; apply(); }
  function zoomAt(ns, cx, cy) {
    ns = Math.max(1, Math.min(4, ns));
    if (ns === s) return;
    var r = stage.getBoundingClientRect();
    var px = cx - (r.left + r.width / 2 + tx), py = cy - (r.top + r.height / 2 + ty);
    tx += px * (1 - ns / s); ty += py * (1 - ns / s);
    s = ns;
    if (s === 1) { tx = 0; ty = 0; }
    clamp(); apply();
  }
  function lbRender() {
    img.src = urls[idx];
    cnt.textContent = (idx + 1) + ' / ' + n;
    reset();
  }
  function open() {
    lbOpen = true; refresh();
    lb.hidden = false; document.body.style.overflow = 'hidden';
    lbRender(); btnX.focus();
  }
  function close() {
    lbOpen = false; lb.hidden = true; document.body.style.overflow = '';
    refresh(); frame.focus();
  }
  function go(d) { setMain(idx + d, true); lbRender(); }

  frame.addEventListener('click', open);
  frame.addEventListener('keydown', function (e) { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); open(); } });
  btnX.addEventListener('click', close);
  if (prev) prev.addEventListener('click', function () { go(-1); });
  if (next) next.addEventListener('click', function () { go(1); });
  lb.addEventListener('click', function (e) { if ((e.target === lb || e.target === stage) && s === 1) close(); });

  img.addEventListener('click', function (e) {
    e.stopPropagation();
    if (moved) { moved = false; return; }
    if (s > 1) zoomAt(1, e.clientX, e.clientY); else zoomAt(2.5, e.clientX, e.clientY);
  });
  img.addEventListener('pointerdown', function (e) {
    if (s <= 1) return;
    dragging = true; moved = false; sx = e.clientX; sy = e.clientY; stx = tx; sty = ty;
    img.setPointerCapture(e.pointerId); img.classList.add('drag');
  });
  img.addEventListener('pointermove', function (e) {
    if (!dragging) return;
    var dx = e.clientX - sx, dy = e.clientY - sy;
    if (Math.abs(dx) + Math.abs(dy) > 4) moved = true;
    tx = stx + dx; ty = sty + dy; clamp(); apply();
  });
  function endDrag() { dragging = false; img.classList.remove('drag'); }
  img.addEventListener('pointerup', endDrag);
  img.addEventListener('pointercancel', endDrag);
  stage.addEventListener('wheel', function (e) {
    e.preventDefault();
    zoomAt(s * (e.deltaY < 0 ? 1.25 : 0.8), e.clientX, e.clientY);
  }, { passive: false });

  document.addEventListener('keydown', function (e) {
    if (!lbOpen) return;
    if (e.key === 'Escape') close();
    else if (e.key === 'ArrowLeft' && n > 1) go(-1);
    else if (e.key === 'ArrowRight' && n > 1) go(1);
  });

  refresh();
})();
</script>
@endif
@endsection
