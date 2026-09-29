<?php

namespace App\Models;

use App\Support\Rupiah;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = ['category_id', 'name', 'slug', 'price', 'discount_percent', 'compare_price', 'description', 'image', 'is_featured', 'is_active'];
    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'discount_percent' => 'integer',
        'compare_price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /** Foto tambahan (galeri). Foto utama tetap di kolom `image`. */
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::disk('public')->url($this->image) : null;
    }

    /** Daftar URL semua foto: foto utama dulu, lalu foto tambahan. */
    public function getGalleryUrlsAttribute(): array
    {
        $urls = [];
        if ($this->image_url) {
            $urls[] = $this->image_url;
        }
        foreach ($this->images as $img) {
            $urls[] = $img->url;
        }

        return $urls;
    }

    /**
     * Jenis diskon:
     *  - 'percent' : `price` = harga normal, dipotong `discount_percent`.
     *  - 'coret'   : `price` = harga jual, `compare_price` = harga coret yang diisi langsung.
     *  - 'none'    : tanpa diskon.
     */
    public function getDiscountModeAttribute(): string
    {
        if ((int) $this->discount_percent > 0) {
            return 'percent';
        }
        if ($this->compare_price !== null) {
            return 'coret';
        }

        return 'none';
    }

    public function getHasDiscountAttribute(): bool
    {
        if ($this->price === null) {
            return false;
        }
        if ((int) $this->discount_percent > 0) {
            return true;
        }

        return $this->compare_price !== null && (float) $this->compare_price > (float) $this->price;
    }

    /** Harga yang harus dibayar pembeli. */
    public function getFinalPriceAttribute(): ?float
    {
        if ($this->price === null) {
            return null;
        }
        $price = (float) $this->price;

        return (int) $this->discount_percent > 0
            ? round($price * (100 - (int) $this->discount_percent) / 100)
            : $price;
    }

    /** Harga sebelum diskon (yang dicoret). Null bila tidak ada diskon. */
    public function getOriginalPriceAttribute(): ?float
    {
        if (! $this->has_discount) {
            return null;
        }

        return (int) $this->discount_percent > 0 ? (float) $this->price : (float) $this->compare_price;
    }

    /** Persentase untuk badge/label; pada mode harga coret dihitung otomatis. */
    public function getDiscountBadgePercentAttribute(): int
    {
        if ((int) $this->discount_percent > 0) {
            return (int) $this->discount_percent;
        }
        if (! $this->has_discount) {
            return 0;
        }
        $orig = (float) $this->compare_price;

        return max(1, (int) round(($orig - (float) $this->price) / $orig * 100));
    }

    /** Harga yang ditampilkan (sudah dipotong diskon bila ada). */
    public function getPriceLabelAttribute(): string
    {
        return $this->final_price !== null ? Rupiah::format($this->final_price) : 'Hubungi kami';
    }

    /** Harga asli yang ditampilkan dicoret. */
    public function getOriginalPriceLabelAttribute(): ?string
    {
        return $this->original_price !== null ? Rupiah::format($this->original_price) : null;
    }

    public function getWhatsappUrlAttribute(): ?string
    {
        $store = Setting::get('store_name', 'Limun Jaya Furniture');
        return Setting::wa("Halo {$store}, saya tertarik dengan produk {$this->name}. Mohon informasi lebih lanjut.\n" . route('catalog.show', $this->slug));
    }

    public static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'produk';
        $slug = $base;
        $i = 2;
        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
