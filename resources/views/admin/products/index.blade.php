@extends('layouts.admin')
@section('title', 'Produk')
@section('content')
<div class="head-row"><h1>Produk</h1>
  <a class="btn btn-dark" href="{{ route('admin.products.create') }}">Tambah Produk</a></div>
<div class="panel">
  <form method="get" class="filter-form">
    <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama produk" aria-label="Cari">
    <select name="kategori" aria-label="Kategori"><option value="">Semua kategori</option>
      @foreach($categories as $c)<option value="{{ $c->id }}" @selected(request('kategori') == $c->id)>{{ $c->name }}</option>@endforeach</select>
    <select name="status" aria-label="Status"><option value="">Semua status</option><option value="aktif" @selected(request('status')==='aktif')>Aktif</option><option value="nonaktif" @selected(request('status')==='nonaktif')>Nonaktif</option></select>
    <button class="btn btn-out btn-sm" type="submit">Filter</button>
  </form>
  <div class="tbl-w"><table>
    <thead><tr><th>Foto</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
    @forelse($products as $p)
      <tr>
        <td>@if($p->image_url)<img class="thumb" src="{{ $p->image_url }}" alt="">@else<div class="thumb"></div>@endif</td>
        <td>{{ $p->name }}</td><td>{{ $p->category->name }}</td><td>{{ $p->price_label }}@if($p->has_discount)<br><small style="color:var(--muted)"><s>{{ $p->original_price_label }}</s> &middot; -{{ $p->discount_badge_percent }}%</small>@endif</td>
        <td><span class="tag {{ $p->is_active ? 'on' : 'off' }}">{{ $p->is_active ? 'Aktif' : 'Nonaktif' }}</span> @if($p->is_featured)<span class="tag star">Unggulan</span>@endif</td>
        <td><div class="acts">
          <a class="btn btn-out btn-sm" href="{{ route('admin.products.edit', $p) }}">Edit</a>
          <form method="post" action="{{ route('admin.products.destroy', $p) }}" onsubmit="return confirm('Hapus produk {{ e($p->name) }}? Tindakan ini tidak dapat dibatalkan.')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm" type="submit">Hapus</button></form>
        </div></td>
      </tr>
    @empty
      <tr><td colspan="6" style="text-align:center;color:var(--muted);padding:30px">Belum ada produk yang cocok.</td></tr>
    @endforelse
    </tbody></table></div>
  @if($products->hasPages())<div class="pager">
    @if($products->onFirstPage())<span>&laquo; Sebelumnya</span>@else<a href="{{ $products->previousPageUrl() }}">&laquo; Sebelumnya</a>@endif
    <span class="cur">{{ $products->currentPage() }} / {{ $products->lastPage() }}</span>
    @if($products->hasMorePages())<a href="{{ $products->nextPageUrl() }}">Berikutnya &raquo;</a>@else<span>Berikutnya &raquo;</span>@endif
  </div>@endif
</div>
@endsection
