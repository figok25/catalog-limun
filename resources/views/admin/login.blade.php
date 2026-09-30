<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow"><title>Login Admin - Limun Jaya Furniture</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
@include('partials.favicon')
</head>
<body>
<div class="login"><div class="panel">
  <h1 style="font-size:1.6rem;color:var(--green-d);margin-bottom:6px">Login Admin</h1>
  <p style="color:var(--muted);margin:0 0 20px">Limun Jaya Furniture</p>
  <form method="post" action="{{ url('/admin/login') }}">
    @csrf
    <div class="form-g"><label for="email">Email</label><input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
      @error('email')<div class="err-t">{{ $message }}</div>@enderror</div>
    <div class="form-g"><label for="password">Password</label><input id="password" type="password" name="password" required></div>
    <label class="chk form-g"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
    <button class="btn btn-dark" style="width:100%;justify-content:center" type="submit">Masuk</button>
  </form>
</div></div>
</body>
</html>
