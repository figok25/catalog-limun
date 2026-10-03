@extends('layouts.public')
@section('title', 'Portofolio - ' . \App\Models\Setting::get('store_name', 'Limun Jaya Furniture'))
@section('description', 'Lihat hasil karya dan proyek furniture yang telah dikerjakan ' . \App\Models\Setting::get('store_name', 'Limun Jaya Furniture') . '.')
@section('content')
@php
  $data = $items->map(fn ($p) => ['title' => $p->title, 'description' => $p->description, 'images' => $p->image_urls])->values();
@endphp
<div class="page-head"><div class="wrap"><h1>Portofolio</h1><p>Hasil karya dan proyek furniture yang telah kami kerjakan</p></div></div>
<section class="sec"><div class="wrap">
  @if($items->isEmpty())
    <div class="empty">Portofolio belum tersedia. Silakan kembali lagi nanti.</div>
  @else
    <div class="pf-grid">
      @foreach($items as $i => $p)
        <button type="button" class="pf-card" data-i="{{ $i }}" aria-haspopup="dialog" aria-label="Lihat proyek {{ $p->title }}">
          <span class="pf-ph"><img src="{{ $p->cover_url }}" alt="" loading="lazy"></span>
          @if($p->images->count() > 1)<span class="pf-n">{{ $p->images->count() }} foto</span>@endif
          <span class="pf-cap"><b>{{ $p->title }}</b>@if($p->description)<small>{{ Str::limit($p->description, 80) }}</small>@endif</span>
        </button>
      @endforeach
    </div>
    @if($items->hasPages())
    <nav class="pager" aria-label="Halaman">
      @if($items->onFirstPage())<span>&laquo;</span>@else<a href="{{ $items->previousPageUrl() }}">&laquo;</a>@endif
      @foreach($items->getUrlRange(max(1, $items->currentPage() - 2), min($items->lastPage(), $items->currentPage() + 2)) as $n => $url)
        @if($n == $items->currentPage())<span class="cur">{{ $n }}</span>@else<a href="{{ $url }}">{{ $n }}</a>@endif
      @endforeach
      @if($items->hasMorePages())<a href="{{ $items->nextPageUrl() }}">&raquo;</a>@else<span>&raquo;</span>@endif
    </nav>
    @endif
  @endif
</div></section>

@if($items->isNotEmpty())
<div class="pf-modal" id="pfModal" hidden role="dialog" aria-modal="true" aria-labelledby="pfTitle">
  <div class="pf-box" id="pfBox">
    <button type="button" class="pf-x" id="pfClose" aria-label="Tutup">&times;</button>
    <div class="pf-media">
      <div class="pf-track" id="pfTrack" tabindex="0" aria-label="Foto proyek. Geser untuk melihat foto lainnya"></div>
      <button type="button" class="pf-nav prev" id="pfPrev" aria-label="Foto sebelumnya">&#8249;</button>
      <button type="button" class="pf-nav next" id="pfNext" aria-label="Foto berikutnya">&#8250;</button>
      <span class="pf-count" id="pfCount" aria-live="polite"></span>
    </div>
    <div class="pf-dots" id="pfDots"></div>
    <div class="pf-info"><h2 id="pfTitle"></h2><p id="pfDesc"></p></div>
  </div>
</div>
<script>
(function () {
  var data = @json($data);
  var modal = document.getElementById('pfModal'), box = document.getElementById('pfBox'),
      track = document.getElementById('pfTrack'), prev = document.getElementById('pfPrev'),
      next = document.getElementById('pfNext'), count = document.getElementById('pfCount'),
      dots = document.getElementById('pfDots'), title = document.getElementById('pfTitle'),
      desc = document.getElementById('pfDesc'), closeBtn = document.getElementById('pfClose');
  var n = 0, idx = 0, lastFocus = null, raf = 0;
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function width() { return track.clientWidth || 1; }

  function build(item) {
    track.innerHTML = ''; dots.innerHTML = '';
    item.images.forEach(function (u, k) {
      var slide = document.createElement('div'); slide.className = 'pf-slide';
      var im = document.createElement('img');
      im.src = u; im.alt = item.title + ' - foto ' + (k + 1); im.draggable = false;
      if (k > 0) im.loading = 'lazy';
      slide.appendChild(im); track.appendChild(slide);
      var d = document.createElement('button'); d.type = 'button'; d.className = 'pf-dot';
      d.setAttribute('aria-label', 'Lihat foto ' + (k + 1));
      d.addEventListener('click', function () { goTo(k); });
      dots.appendChild(d);
    });
  }

  function update() {
    idx = Math.max(0, Math.min(n - 1, Math.round(track.scrollLeft / width())));
    count.textContent = (idx + 1) + ' / ' + n;
    Array.prototype.forEach.call(dots.children, function (d, k) { d.classList.toggle('on', k === idx); });
  }

  function scrollToIndex(k) {
    track.scrollTo({ left: k * width(), behavior: reduce ? 'auto' : 'smooth' });
  }
  function goTo(k) { scrollToIndex((k + n) % n); }

  function open(i) {
    var item = data[i];
    if (!item) return;
    lastFocus = document.activeElement;
    n = item.images.length;
    build(item);
    title.textContent = item.title;
    desc.textContent = item.description || '';
    desc.hidden = !item.description;
    box.classList.toggle('single', n < 2);
    modal.hidden = false;
    document.body.style.overflow = 'hidden';
    box.scrollTop = 0; track.scrollLeft = 0;
    update();
    closeBtn.focus();
  }
  function close() {
    modal.hidden = true;
    document.body.style.overflow = '';
    track.innerHTML = '';
    if (lastFocus && lastFocus.focus) lastFocus.focus();
  }

  Array.prototype.forEach.call(document.querySelectorAll('.pf-card'), function (c) {
    c.addEventListener('click', function () { open(parseInt(c.getAttribute('data-i'), 10)); });
  });
  closeBtn.addEventListener('click', close);
  modal.addEventListener('click', function (e) { if (e.target === modal) close(); });
  prev.addEventListener('click', function () { goTo(idx - 1); });
  next.addEventListener('click', function () { goTo(idx + 1); });
  track.addEventListener('scroll', function () {
    if (raf) cancelAnimationFrame(raf);
    raf = requestAnimationFrame(update);
  }, { passive: true });
  window.addEventListener('resize', function () { if (!modal.hidden) scrollToIndex(idx); });

  document.addEventListener('keydown', function (e) {
    if (modal.hidden) return;
    if (e.key === 'Escape') close();
    else if (e.key === 'ArrowLeft' && n > 1) goTo(idx - 1);
    else if (e.key === 'ArrowRight' && n > 1) goTo(idx + 1);
  });

  /* Geser dengan mouse di desktop (sentuhan di HP sudah otomatis lewat scroll-snap) */
  var drag = false, sx = 0, startIdx = 0, startLeft = 0;
  track.addEventListener('pointerdown', function (e) {
    if (e.pointerType !== 'mouse' || e.button !== 0 || n < 2) return;
    drag = true; sx = e.clientX; startLeft = track.scrollLeft; startIdx = idx;
    track.style.scrollSnapType = 'none';
    track.classList.add('drag');
    track.setPointerCapture(e.pointerId);
  });
  track.addEventListener('pointermove', function (e) {
    if (drag) track.scrollLeft = startLeft - (e.clientX - sx);
  });
  function endDrag(e) {
    if (!drag) return;
    drag = false;
    track.classList.remove('drag');
    var dx = e.clientX - sx, t = startIdx;
    if (dx < -50) t = startIdx + 1; else if (dx > 50) t = startIdx - 1;
    t = Math.max(0, Math.min(n - 1, t));
    track.style.scrollSnapType = '';
    scrollToIndex(t);
  }
  track.addEventListener('pointerup', endDrag);
  track.addEventListener('pointercancel', endDrag);
})();
</script>
@endif
@endsection
