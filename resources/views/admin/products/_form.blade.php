@csrf
@php
  $mode = old('discount_mode', $product->discount_mode);
  $fmt = fn ($v) => $v !== null && $v !== '' ? \App\Support\Rupiah::format($v) : '';
@endphp
<div class="two">
  <div class="form-g"><label for="name">Nama produk</label><input id="name" name="name" value="{{ old('name', $product->name) }}" required maxlength="150">
    @error('name')<div class="err-t">{{ $message }}</div>@enderror</div>
  <div class="form-g"><label for="category_id">Kategori</label>
    <select id="category_id" name="category_id" required><option value="">Pilih kategori</option>
      @foreach($categories as $c)<option value="{{ $c->id }}" @selected(old('category_id', $product->category_id) == $c->id)>{{ $c->name }}</option>@endforeach
    </select>@error('category_id')<div class="err-t">{{ $message }}</div>@enderror</div>
</div>
<div class="two">
  <div class="form-g"><label for="price">Harga (Rp)</label>
    <input id="price" type="text" inputmode="numeric" autocomplete="off" data-rupiah name="price" placeholder="Rp 0" value="{{ old('price') !== null ? $fmt(old('price')) : ($product->price !== null ? $fmt((int) $product->price) : '') }}">
    <small id="price-hint">Kosongkan bila ingin menampilkan "Hubungi kami".</small>@error('price')<div class="err-t">{{ $message }}</div>@enderror</div>
  <div class="form-g"><label>Diskon</label>
    <div style="display:flex;gap:18px;flex-wrap:wrap;margin-bottom:8px">
      <label class="chk"><input type="radio" name="discount_mode" value="none" @checked($mode === 'none')> Tanpa diskon</label>
      <label class="chk"><input type="radio" name="discount_mode" value="percent" @checked($mode === 'percent')> Persentase (%)</label>
      <label class="chk"><input type="radio" name="discount_mode" value="coret" @checked($mode === 'coret')> Harga coret (Rp)</label>
    </div>
    <div id="mode-percent" style="display:none">
      <input id="discount_percent" type="number" min="1" max="99" step="1" name="discount_percent" value="{{ old('discount_percent', $product->discount_percent) }}" placeholder="Contoh: 15">
      <small>Harga di samping dianggap harga normal, lalu dipotong persen ini.</small>
      @error('discount_percent')<div class="err-t">{{ $message }}</div>@enderror
    </div>
    <div id="mode-coret" style="display:none">
      <input id="compare_price" type="text" inputmode="numeric" autocomplete="off" data-rupiah name="compare_price" placeholder="Rp 0" value="{{ old('compare_price') !== null ? $fmt(old('compare_price')) : ($product->compare_price !== null ? $fmt((int) $product->compare_price) : '') }}">
      <small>Harga di samping dianggap harga jual. Harga coret ini tampil dicoret di atasnya.</small>
      @error('compare_price')<div class="err-t">{{ $message }}</div>@enderror
    </div>
    <div id="disc-preview" style="font-size:.82rem;color:var(--green);font-weight:600;margin-top:6px"></div>
  </div>
</div>
<div class="form-g"><label for="description">Deskripsi</label><textarea id="description" name="description" rows="5">{{ old('description', $product->description) }}</textarea>
  @error('description')<div class="err-t">{{ $message }}</div>@enderror</div>
<div class="form-g"><label for="image">Foto utama</label>
  @if($product->image_url)<img src="{{ $product->image_url }}" alt="" style="width:160px;border-radius:8px;margin-bottom:8px">
    <label class="chk" style="margin-bottom:8px"><input type="checkbox" name="remove_image" value="1"> Hapus foto saat ini</label>@endif
  <input id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp"><small>JPG, PNG, atau WebP. Maksimal 2 MB.</small>
  @error('image')<div class="err-t">{{ $message }}</div>@enderror</div>
<div class="form-g"><label for="images">Foto tambahan (galeri)</label>
  @if($product->exists && $product->images->isNotEmpty())
    <div class="gal-adm">
      @foreach($product->images as $img)
        <label class="gi"><img src="{{ $img->url }}" alt=""><span class="chk"><input type="checkbox" name="remove_images[]" value="{{ $img->id }}"> Hapus</span></label>
      @endforeach
    </div>
  @endif
  <input id="images" type="file" name="images[]" multiple accept="image/jpeg,image/png,image/webp">
  <small>Bisa pilih beberapa foto sekaligus. Maksimal 8 foto tambahan per produk, masing-masing 2 MB.</small>
  @error('images')<div class="err-t">{{ $message }}</div>@enderror
  @foreach($errors->get('images.*') as $msgs)<div class="err-t">{{ $msgs[0] }}</div>@endforeach</div>
<div style="display:flex;gap:22px;flex-wrap:wrap;margin-bottom:20px">
  <label class="chk"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))> Produk unggulan</label>
  <label class="chk"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))> Tampilkan di website</label>
</div>
<div class="acts"><button class="btn btn-dark" type="submit">Simpan produk</button><a class="btn btn-out" href="{{ route('admin.products.index') }}">Batal</a></div>
<script>
(function () {
  // ---- Helper format Rupiah: "Rp 4.900.000" (titik pemisah ribuan otomatis saat mengetik) ----
  function digits(s) { return (s || '').replace(/\D/g, '').replace(/^0+(?=\d)/, ''); }
  function fmt(d) { return d ? 'Rp ' + d.replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''; }
  function value(el) { return parseInt(digits(el.value) || '0', 10); }
  function bindRupiah(el) {
    function apply() {
      var pos = el.selectionStart || 0;
      var n = (el.value.slice(0, pos).match(/\d/g) || []).length;
      var d = digits(el.value);
      if (n > d.length) n = d.length;
      var f = fmt(d);
      el.value = f;
      var i = 0, c = 0;
      if (n === 0) { i = f ? 3 : 0; }
      else { for (i = 0; i < f.length && c < n; i++) { if (/\d/.test(f.charAt(i))) c++; } }
      if (el === document.activeElement && el.setSelectionRange) el.setSelectionRange(i, i);
      render();
    }
    el.addEventListener('input', apply);
    el.value = fmt(digits(el.value));
  }

  // ---- Jenis diskon + pratinjau ----
  var price = document.getElementById('price'),
      pct = document.getElementById('discount_percent'),
      cmp = document.getElementById('compare_price'),
      out = document.getElementById('disc-preview'),
      hint = document.getElementById('price-hint'),
      boxP = document.getElementById('mode-percent'),
      boxC = document.getElementById('mode-coret'),
      radios = document.querySelectorAll('input[name="discount_mode"]');

  function mode() {
    for (var i = 0; i < radios.length; i++) if (radios[i].checked) return radios[i].value;
    return 'none';
  }
  function money(n) { return fmt(String(Math.round(n))); }
  function render() {
    var m = mode(), pr = value(price);
    boxP.style.display = m === 'percent' ? '' : 'none';
    boxC.style.display = m === 'coret' ? '' : 'none';
    hint.textContent = m === 'percent' ? 'Harga normal (sebelum diskon).'
      : m === 'coret' ? 'Harga jual (yang dibayar pembeli).'
      : 'Kosongkan bila ingin menampilkan "Hubungi kami".';
    var msg = '';
    if (m !== 'none' && !(pr > 0)) {
      msg = 'Isi harga terlebih dahulu agar diskon berlaku.';
    } else if (m === 'percent') {
      var dc = parseInt(pct.value, 10);
      if (dc > 0 && dc < 100) msg = 'Tampil ' + money(pr * (100 - dc) / 100) + ' dengan ' + money(pr) + ' dicoret (-' + dc + '%)';
    } else if (m === 'coret') {
      var cp = value(cmp);
      if (cp > pr) msg = 'Tampil ' + money(pr) + ' dengan ' + money(cp) + ' dicoret (-' + Math.max(1, Math.round((cp - pr) / cp * 100)) + '%)';
      else if (cp > 0) msg = 'Harga coret harus lebih besar dari harga jual.';
    }
    out.textContent = msg;
  }

  bindRupiah(price);
  bindRupiah(cmp);
  pct.addEventListener('input', render);
  for (var i = 0; i < radios.length; i++) radios[i].addEventListener('change', render);
  render();
})();
</script>
