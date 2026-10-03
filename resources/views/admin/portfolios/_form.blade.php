@csrf
<div class="two">
  <div class="form-g"><label for="title">Judul proyek</label><input id="title" name="title" value="{{ old('title', $portfolio->title) }}" required maxlength="150">
    @error('title')<div class="err-t">{{ $message }}</div>@enderror</div>
  <div class="form-g"><label for="sort_order">Urutan tampil</label><input id="sort_order" type="number" min="0" max="9999" step="1" name="sort_order" value="{{ old('sort_order', $portfolio->sort_order ?? 0) }}">
    <small>Angka lebih kecil tampil lebih dulu. Jika sama, proyek terbaru di depan.</small>
    @error('sort_order')<div class="err-t">{{ $message }}</div>@enderror</div>
</div>
<div class="form-g"><label for="description">Deskripsi singkat</label><textarea id="description" name="description" rows="4" maxlength="1000">{{ old('description', $portfolio->description) }}</textarea>
  <small>Tampil di jendela (modal) saat foto diklik. Maksimal 1000 karakter.</small>
  @error('description')<div class="err-t">{{ $message }}</div>@enderror</div>
<div class="form-g"><label for="images">Foto proyek</label>
  @if($portfolio->exists && $portfolio->images->isNotEmpty())
    <div class="gal-adm">
      @foreach($portfolio->images as $img)
        <div class="gi">
          <img src="{{ $img->url }}" alt="">
          <label class="chk"><input type="radio" name="cover_image_id" value="{{ $img->id }}" @checked((int) old('cover_image_id', $portfolio->images->first()->id) === $img->id)> Sampul</label>
          <label class="chk"><input type="checkbox" name="remove_images[]" value="{{ $img->id }}"> Hapus</label>
        </div>
      @endforeach
    </div>
  @endif
  <input id="images" type="file" name="images[]" multiple accept="image/jpeg,image/png,image/webp">
  <small>Boleh 1 foto atau beberapa foto sekaligus. Maksimal 10 foto per proyek, masing-masing 2 MB (JPG, PNG, atau WebP).{{ $portfolio->exists ? ' Foto baru ditambahkan setelah foto yang sudah ada.' : ' Foto pertama otomatis menjadi sampul.' }}</small>
  @error('images')<div class="err-t">{{ $message }}</div>@enderror
  @foreach($errors->get('images.*') as $msgs)<div class="err-t">{{ $msgs[0] }}</div>@endforeach</div>
<div style="margin-bottom:20px">
  <label class="chk"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $portfolio->is_active ?? true))> Tampilkan di website</label>
</div>
<div class="acts"><button class="btn btn-dark" type="submit">Simpan portofolio</button><a class="btn btn-out" href="{{ route('admin.portfolios.index') }}">Batal</a></div>
