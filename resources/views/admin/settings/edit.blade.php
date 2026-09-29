@extends('layouts.admin')
@section('title', 'Pengaturan Kontak')
@php use App\Models\Setting; @endphp
@section('content')
<h1>Pengaturan Kontak</h1>
<div class="panel"><form method="post" action="{{ route('admin.settings.update') }}">
  @csrf @method('PUT')
  @foreach([
    ['store_name','Nama toko','text',null],
    ['tagline','Deskripsi singkat (tampil di beranda)','textarea',null],
    ['about','Tentang kami','textarea',null],
    ['years_experience','Tahun pengalaman','number','Kosongkan untuk menyembunyikan badge pengalaman.'],
    ['whatsapp','Nomor WhatsApp','text','Format 62..., tanpa + atau spasi. Contoh: 628123456789'],
    ['address','Alamat','textarea',null],
    ['hours','Jam operasional','text',null],
    ['instagram','Link Instagram','text','Contoh: https://instagram.com/namaakun'],
    ['maps_url','Link Google Maps','text',null],
  ] as [$k,$label,$type,$hint])
  <div class="form-g"><label for="{{ $k }}">{{ $label }}</label>
    @if($type==='textarea')<textarea id="{{ $k }}" name="{{ $k }}" rows="3">{{ old($k, Setting::get($k)) }}</textarea>
    @else<input id="{{ $k }}" type="{{ $type }}" name="{{ $k }}" value="{{ old($k, Setting::get($k)) }}">@endif
    @if($hint)<small>{{ $hint }}</small>@endif
    @error($k)<div class="err-t">{{ $message }}</div>@enderror</div>
  @endforeach
  <button class="btn btn-dark" type="submit">Simpan pengaturan</button>
</form></div>
@endsection
