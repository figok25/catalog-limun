@extends('layouts.admin')
@section('title', 'Kategori')
@section('content')
<h1>Kategori</h1>
<div class="panel">
  <strong>{{ $editing ? 'Edit kategori' : 'Tambah kategori' }}</strong>
  <form method="post" action="{{ $editing ? route('admin.categories.update', $editing) : route('admin.categories.store') }}" style="margin-top:14px">
    @csrf @if($editing) @method('PUT') @endif
    <div class="two">
      <div class="form-g"><label for="name">Nama</label><input id="name" name="name" value="{{ old('name', $editing->name ?? '') }}" required maxlength="100">@error('name')<div class="err-t">{{ $message }}</div>@enderror</div>
      <div class="form-g"><label for="description">Keterangan singkat</label><input id="description" name="description" value="{{ old('description', $editing->description ?? '') }}" maxlength="150"></div>
    </div>
    <label class="chk form-g"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $editing->is_active ?? true))> Aktif</label>
    <div class="acts"><button class="btn btn-dark btn-sm" type="submit">Simpan kategori</button>@if($editing)<a class="btn btn-out btn-sm" href="{{ route('admin.categories.index') }}">Batal</a>@endif</div>
  </form>
</div>
<div class="panel tbl-w"><table>
  <thead><tr><th>Nama</th><th>Produk</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
  @forelse($categories as $c)
    <tr><td>{{ $c->name }}</td><td>{{ $c->products_count }}</td>
      <td><span class="tag {{ $c->is_active ? 'on' : 'off' }}">{{ $c->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
      <td><div class="acts"><a class="btn btn-out btn-sm" href="{{ route('admin.categories.edit', $c) }}">Edit</a>
        <form method="post" action="{{ route('admin.categories.destroy', $c) }}" onsubmit="return confirm('Hapus kategori {{ e($c->name) }}?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm" type="submit">Hapus</button></form></div></td></tr>
  @empty
    <tr><td colspan="4" style="text-align:center;color:var(--muted);padding:24px">Belum ada kategori. Tambahkan kategori pertama di atas.</td></tr>
  @endforelse
</tbody></table></div>
@endsection
