@csrf
<div class="two">
  <div class="form-g"><label for="name">Nama produk</label><input id="name" name="name" value="{{ old('name', $product->name) }}" required maxlength="150">
    @error('name')<div class="err-t">{{ $message }}</div>@enderror</div>
  <div class="form-g"><label for="category_id">Kategori</label>
    <select id="category_id" name="category_id" required><option value="">Pilih kategori</option>
      @foreach($categories as $c)<option value="{{ $c->id }}" @selected(old('category_id', $product->category_id) == $c->id)>{{ $c->name }}</option>@endforeach
    </select>@error('category_id')<div class="err-t">{{ $message }}</div>@enderror</div>
</div>
<div class="form-g"><label for="price">Harga (Rp)</label><input id="price" type="number" min="0" step="1" name="price" value="{{ old('price', $product->price !== null ? (int) $product->price : '') }}">
  <small>Kosongkan bila ingin menampilkan "Hubungi kami".</small>@error('price')<div class="err-t">{{ $message }}</div>@enderror</div>
<div class="form-g"><label for="description">Deskripsi</label><textarea id="description" name="description" rows="5">{{ old('description', $product->description) }}</textarea>
  @error('description')<div class="err-t">{{ $message }}</div>@enderror</div>
<div class="form-g"><label for="image">Foto utama</label>
  @if($product->image_url)<img src="{{ $product->image_url }}" alt="" style="width:160px;border-radius:8px;margin-bottom:8px">
    <label class="chk" style="margin-bottom:8px"><input type="checkbox" name="remove_image" value="1"> Hapus foto saat ini</label>@endif
  <input id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp"><small>JPG, PNG, atau WebP. Maksimal 2 MB.</small>
  @error('image')<div class="err-t">{{ $message }}</div>@enderror</div>
<div style="display:flex;gap:22px;flex-wrap:wrap;margin-bottom:20px">
  <label class="chk"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))> Produk unggulan</label>
  <label class="chk"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))> Tampilkan di website</label>
</div>
<div class="acts"><button class="btn btn-dark" type="submit">Simpan produk</button><a class="btn btn-out" href="{{ route('admin.products.index') }}">Batal</a></div>
