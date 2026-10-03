@extends('layouts.admin')
@section('title', 'Tambah Portofolio')
@section('content')
<h1>Tambah Portofolio</h1>
<div class="panel"><form method="post" action="{{ route('admin.portfolios.store') }}" enctype="multipart/form-data">@include('admin.portfolios._form')</form></div>
@endsection
