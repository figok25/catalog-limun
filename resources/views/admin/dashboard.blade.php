@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
<h1>Dashboard</h1>
<div class="stats">
  <div class="stat"><b>{{ $total }}</b><span>Total produk</span></div>
  <div class="stat"><b>{{ $active }}</b><span>Produk aktif</span></div>
  <div class="stat"><b>{{ $featured }}</b><span>Produk unggulan</span></div>
  <div class="stat"><b>{{ $categories }}</b><span>Kategori</span></div>
</div>
<div class="panel">
  <div style="display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap;margin-bottom:12px"><strong>Produk terbaru</strong>
    <a class="btn btn-dark btn-sm" href="{{ route('admin.products.create') }}">Tambah Produk</a></div>
  @forelse($latest as $p)
    <div style="padding:8px 0;border-top:1px solid var(--line)"><a href="{{ route('admin.products.edit', $p) }}">{{ $p->name }}</a> <span style="color:var(--muted)">- {{ $p->category->name }}</span></div>
  @empty
    <p style="color:var(--muted)">Belum ada produk. Klik "Tambah Produk" untuk memulai.</p>
  @endforelse
</div>
@endsection
