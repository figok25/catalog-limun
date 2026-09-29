@extends('layouts.admin')
@section('title', 'Edit Produk')
@section('content')
<h1>Edit Produk</h1>
<div class="panel"><form method="post" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">@method('PUT')@include('admin.products._form')</form></div>
@endsection
