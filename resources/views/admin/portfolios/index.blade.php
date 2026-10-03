@extends('layouts.admin')
@section('title', 'Portofolio')
@section('content')
<div class="head-row"><h1>Portofolio</h1>
  <a class="btn btn-dark" href="{{ route('admin.portfolios.create') }}">Tambah Portofolio</a></div>
<div class="panel">
  <div class="tbl-w"><table>
    <thead><tr><th>Foto</th><th>Judul</th><th>Jumlah foto</th><th>Urutan</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
    @forelse($portfolios as $p)
      <tr>
        <td>@if($p->cover_url)<img class="thumb" src="{{ $p->cover_url }}" alt="">@else<div class="thumb"></div>@endif</td>
        <td>{{ $p->title }}</td>
        <td>{{ $p->images->count() }}</td>
        <td>{{ $p->sort_order }}</td>
        <td><span class="tag {{ $p->is_active ? 'on' : 'off' }}">{{ $p->is_active ? 'Tampil' : 'Disembunyikan' }}</span></td>
        <td><div class="acts">
          <a class="btn btn-out btn-sm" href="{{ route('admin.portfolios.edit', $p) }}">Edit</a>
          <form method="post" action="{{ route('admin.portfolios.destroy', $p) }}" onsubmit="return confirm('Hapus portofolio {{ e($p->title) }} beserta semua fotonya? Tindakan ini tidak dapat dibatalkan.')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm" type="submit">Hapus</button></form>
        </div></td>
      </tr>
    @empty
      <tr><td colspan="6" style="text-align:center;color:var(--muted);padding:30px">Belum ada portofolio. Klik "Tambah Portofolio" untuk mulai.</td></tr>
    @endforelse
    </tbody></table></div>
  @if($portfolios->hasPages())<div class="pager">
    @if($portfolios->onFirstPage())<span>&laquo; Sebelumnya</span>@else<a href="{{ $portfolios->previousPageUrl() }}">&laquo; Sebelumnya</a>@endif
    <span class="cur">{{ $portfolios->currentPage() }} / {{ $portfolios->lastPage() }}</span>
    @if($portfolios->hasMorePages())<a href="{{ $portfolios->nextPageUrl() }}">Berikutnya &raquo;</a>@else<span>Berikutnya &raquo;</span>@endif
  </div>@endif
</div>
@endsection
