<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    protected static ?array $cache = null;

    public static function get(string $key, $default = null)
    {
        if (static::$cache === null) {
            try {
                static::$cache = static::pluck('value', 'key')->all();
            } catch (\Throwable $e) {
                static::$cache = [];
            }
        }
        $v = static::$cache[$key] ?? null;
        return ($v === null || $v === '') ? $default : $v;
    }

    public static function put(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        static::$cache = null;
    }

    /** Link WhatsApp; null bila nomor belum diisi. */
    public static function wa(?string $text = null): ?string
    {
        $num = preg_replace('/\D/', '', (string) static::get('whatsapp'));
        if (! $num) {
            return null;
        }
        $text ??= 'Halo ' . static::get('store_name', 'Limun Jaya Furniture') . ', saya ingin konsultasi mengenai furniture.';
        return 'https://wa.me/' . $num . '?text=' . rawurlencode($text);
    }
}
