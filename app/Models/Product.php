<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = ['category_id', 'name', 'slug', 'price', 'description', 'image', 'is_featured', 'is_active'];
    protected $casts = ['is_featured' => 'boolean', 'is_active' => 'boolean', 'price' => 'decimal:2'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::disk('public')->url($this->image) : null;
    }

    public function getPriceLabelAttribute(): string
    {
        return $this->price !== null ? 'Rp ' . number_format((float) $this->price, 0, ',', '.') : 'Hubungi kami';
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
