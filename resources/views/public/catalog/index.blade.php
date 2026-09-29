@extends('layouts.public')
@section('title', 'Katalog Produk - ' . \App\Models\Setting::get('store_name', 'Limun Jaya Furniture'))
@section('content')
<div class="page-head"><div class="wrap"><h1>Katalog Produk</h1><p>Temukan furniture sesuai kebutuhan Anda</p></div></div>
<section class="sec"><div class="wrap">
  <form class="search" method="get" action="{{ route('catalog.index') }}">
    @if($slug)<input type="hidden" name="kategori" value="{{ $slug }}">@endif
    <input type="search" name="q" value="{{ $q }}" placeholder="Cari nama produk" aria-label="Cari produk">
    <button class="btn btn-dark" type="submit">Cari</button>
  </form>
  <div class="filters">
    <a class="chip {{ $slug ? '' : 'on' }}" href="{{ route('catalog.index', array_filter(['q' => $q])) }}">Semua</a>
    @foreach($categories as $c)
      <a class="chip {{ $slug === $c->slug ? 'on' : '' }}" href="{{ route('catalog.index', array_filter(['kategori' => $c->slug, 'q' => $q])) }}">{{ $c->name }}</a>
    @endforeach
  </div>
  @if($products->isEmpty())
    <div class="empty">Produk tidak ditemukan. Coba kata kunci atau kategori lain.</div>
  @else
    <div class="grid">@foreach($products as $p)<x-product-card :product="$p" />@endforeach</div>
    @if($products->hasPages())
    <nav class="pager" aria-label="Halaman">
      @if($products->onFirstPage())<span>&laquo;</span>@else<a href="{{ $products->previousPageUrl() }}">&laquo;</a>@endif
      @foreach($products->getUrlRange(max(1, $products->currentPage() - 2), min($products->lastPage(), $products->currentPage() + 2)) as $n => $url)
        @if($n == $products->currentPage())<span class="cur">{{ $n }}</span>@else<a href="{{ $url }}">{{ $n }}</a>@endif
      @endforeach
      @if($products->hasMorePages())<a href="{{ $products->nextPageUrl() }}">&raquo;</a>@else<span>&raquo;</span>@endif
    </nav>
    @endif
  @endif
</div></section>
@endsection
