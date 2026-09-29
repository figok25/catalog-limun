@extends('layouts.admin')
@section('title', 'Tambah Produk')
@section('content')
<h1>Tambah Produk</h1>
<div class="panel"><form method="post" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">@include('admin.products._form')</form></div>
@endsection
