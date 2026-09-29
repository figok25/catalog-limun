@props(['product', 'badge' => false])
<a href="{{ route('catalog.show', $product->slug) }}" class="card">
  <div class="ph">
    @if($product->image_url)<img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
    @else<x-placeholder />@endif
    @if($badge && $product->is_featured)<span class="badge">Unggulan</span>@endif
  </div>
  <div class="tx">
    <h3>{{ $product->name }}</h3>
    <span class="cn">{{ $product->category->name ?? '' }}</span>
    <span class="price {{ $product->price === null ? 'ask' : '' }}">{{ $product->price_label }}</span>
  </div>
</a>
