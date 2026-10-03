@extends('layouts.admin')
@section('title', 'Edit Portofolio')
@section('content')
<h1>Edit Portofolio</h1>
<div class="panel"><form method="post" action="{{ route('admin.portfolios.update', $portfolio) }}" enctype="multipart/form-data">@method('PUT')@include('admin.portfolios._form')</form></div>
@endsection
