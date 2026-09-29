@extends('layouts.public')
@php use App\Models\Setting; @endphp
@section('title', 'Tentang Kami - ' . Setting::get('store_name', 'Limun Jaya Furniture'))
@section('content')
<div class="page-head"><div class="wrap"><h1>Tentang Kami</h1><p>Mengenal {{ Setting::get('store_name') }}</p></div></div>
<section class="sec"><div class="wrap" style="max-width:820px">
  <p style="font-size:1.05rem;white-space:pre-line">{{ Setting::get('about') }}</p>
  @if(Setting::get('years_experience'))<p><strong>{{ Setting::get('years_experience') }}+ tahun</strong> pengalaman melayani pelanggan.</p>@endif
  <p style="margin-top:26px"><a href="{{ route('catalog.index') }}" class="btn btn-dark">Lihat Katalog</a></p>
</div></section>
@endsection
