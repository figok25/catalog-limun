@extends('layouts.public')
@php use App\Models\Setting; $wa = Setting::wa(); @endphp
@section('title', 'Kontak - ' . Setting::get('store_name', 'Limun Jaya Furniture'))
@section('content')
<div class="page-head"><div class="wrap"><h1>Kontak</h1><p>Hubungi kami untuk konsultasi dan pemesanan</p></div></div>
<section class="sec"><div class="wrap" style="max-width:820px">
  <div class="info"><dl>
    @if(Setting::get('address'))<dt>Alamat</dt><dd>{{ Setting::get('address') }}</dd>@endif
    @if(Setting::get('hours'))<dt>Jam operasional</dt><dd>{{ Setting::get('hours') }}</dd>@endif
    @if($wa)<dt>WhatsApp</dt><dd><a href="{{ $wa }}" target="_blank" rel="noopener">{{ Setting::get('whatsapp') }}</a></dd>@endif
    @if(Setting::get('instagram'))<dt>Instagram</dt><dd><a href="{{ Setting::get('instagram') }}" target="_blank" rel="noopener">{{ Setting::get('instagram') }}</a></dd>@endif
    @unless(Setting::get('address') || $wa || Setting::get('instagram'))<dd>Informasi kontak belum diisi.</dd>@endunless
  </dl></div>
  <p style="margin-top:22px;display:flex;gap:10px;flex-wrap:wrap">
    @if($wa)<a href="{{ $wa }}" target="_blank" rel="noopener" class="btn btn-dark">Chat via WhatsApp</a>@endif
    @if(Setting::mapLink())<a href="{{ Setting::mapLink() }}" target="_blank" rel="noopener" class="btn btn-out">Buka di Google Maps</a>@endif
  </p>
</div></section>
@endsection
