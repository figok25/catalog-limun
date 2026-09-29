@extends('layouts.public')
@section('title', $product->name . ' - ' . \App\Models\Setting::get('store_name', 'Limun Jaya Furniture'))
@section('description', Str::limit(strip_tags($product->description ?? $product->name), 155))
@if($product->image_url)@section('og_image', $product->image_url)@endif
@section('content')
<section class="sec"><div class="wrap">
  <div class="crumb"><a href="{{ route('home') }}">Beranda</a> / <a href="{{ route('catalog.index') }}">Katalog</a> / {{ $product->name }}</div>
  <div class="detail">
    <div class="ph">@if($product->image_url)<img src="{{ $product->image_url }}" alt="{{ $product->name }}">@else<x-placeholder />@endif</div>
    <div>
      <a class="chip" href="{{ route('catalog.index', ['kategori' => $product->category->slug]) }}">{{ $product->category->name }}</a>
      <h1 style="margin-top:14px">{{ $product->name }}</h1>
      <div class="price {{ $product->price === null ? 'ask' : '' }}">{{ $product->price_label }}</div>
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
@endsection
