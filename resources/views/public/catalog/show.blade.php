@extends('layouts.public')
@section('title', $product->name . ' - ' . \App\Models\Setting::get('store_name', 'Limun Jaya Furniture'))
@section('description', Str::limit(strip_tags($product->description ?? $product->name), 155))
@if($product->image_url)@section('og_image', $product->image_url)@endif
@section('content')
@php $gallery = $product->gallery_urls; @endphp
<section class="sec"><div class="wrap">
  <div class="crumb"><a href="{{ route('home') }}">Beranda</a> / <a href="{{ route('catalog.index') }}">Katalog</a> / {{ $product->name }}</div>
  <div class="detail">
    <div>
      <div class="ph">@if(count($gallery))<img id="galMain" src="{{ $gallery[0] }}" alt="{{ $product->name }}">@else<x-placeholder />@endif</div>
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
<script>
(function () {
  var main = document.getElementById('galMain'), btns = document.querySelectorAll('.thumbs .th');
  if (!main) return;
  btns.forEach(function (b) {
    b.addEventListener('click', function () {
      main.src = b.dataset.src;
      btns.forEach(function (x) { x.classList.remove('on'); });
      b.classList.add('on');
    });
  });
})();
</script>
@endsection
