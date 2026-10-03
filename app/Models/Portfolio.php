<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $fillable = ['title', 'description', 'sort_order', 'is_active'];
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /** Foto proyek. Foto pertama (urutan terkecil) menjadi sampul. */
    public function images()
    {
        return $this->hasMany(PortfolioImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function getCoverUrlAttribute(): ?string
    {
        return $this->images->first()?->url;
    }

    /** Daftar URL semua foto, sesuai urutan. */
    public function getImageUrlsAttribute(): array
    {
        return $this->images->map(fn ($img) => $img->url)->all();
    }
}
