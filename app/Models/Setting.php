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

    /**
     * Nilai kolom "Link Google Maps" dibersihkan: admin boleh menempel link biasa,
     * URL embed, atau kode <iframe> utuh dari Google Maps (diambil bagian src-nya).
     */
    private static function mapsValue(): string
    {
        $raw = trim((string) static::get('maps_url'));
        if (preg_match('/src\s*=\s*["\']([^"\']+)["\']/i', $raw, $m)) {
            $raw = html_entity_decode($m[1]);
        }

        return str_starts_with($raw, 'https://') ? $raw : '';
    }

    /** Teks pencarian: nama toko + alamat, supaya Google menampilkan nama toko (bukan hanya alamat). */
    private static function mapsQuery(): string
    {
        return trim(static::get('store_name', 'Limun Jaya Furniture') . ' ' . static::get('address', ''));
    }

    /** Sumber iframe peta. Prioritas: URL embed dari admin, lalu pencarian nama toko + alamat. */
    public static function mapEmbedSrc(): ?string
    {
        $v = static::mapsValue();
        if (str_starts_with($v, 'https://www.google.com/maps/embed')) {
            return $v;
        }
        if (! static::get('address') && $v === '') {
            return null;
        }

        return 'https://www.google.com/maps?q=' . urlencode(static::mapsQuery()) . '&z=17&output=embed';
    }

    /** Username Instagram, diambil dari link (https://instagram.com/namaakun) atau teks "@namaakun" / "namaakun". Null bila tidak bisa dibaca. */
    public static function instagramHandle(): ?string
    {
        $raw = trim((string) static::get('instagram'));
        if ($raw === '') {
            return null;
        }
        if (preg_match('#instagram\.com/([^/?\#\s]+)#i', $raw, $m)) {
            return ltrim($m[1], '@') ?: null;
        }
        if (preg_match('/^@?([A-Za-z0-9._]+)$/', $raw, $m)) {
            return $m[1];
        }

        return null;
    }

    /** Link profil Instagram: pakai isian admin bila sudah berupa URL, selain itu dibentuk dari username. */
    public static function instagramLink(): ?string
    {
        $raw = trim((string) static::get('instagram'));
        if ($raw === '') {
            return null;
        }
        if (preg_match('#^https?://#i', $raw)) {
            return $raw;
        }
        $h = static::instagramHandle();

        return $h ? 'https://instagram.com/' . $h : null;
    }

    /** Ubah teks heksadesimal (bisa lebih dari 64 bit) menjadi angka desimal berbentuk string, tanpa butuh ekstensi bcmath/gmp. */
    private static function hexToDec(string $hex): string
    {
        $dec = '0';
        foreach (str_split(ltrim(strtolower($hex), '0') ?: '0') as $ch) {
            $carry = hexdec($ch);
            $out = '';
            for ($i = strlen($dec) - 1; $i >= 0; $i--) {
                $n = (int) $dec[$i] * 16 + $carry;
                $out = ($n % 10) . $out;
                $carry = intdiv($n, 10);
            }
            while ($carry > 0) {
                $out = ($carry % 10) . $out;
                $carry = intdiv($carry, 10);
            }
            $dec = $out;
        }

        return ltrim($dec, '0') ?: '0';
    }

    /**
     * Dari URL embed Google Maps (hasil tempel kode iframe), buat link yang membuka tempat/titik yang sama.
     * Utama: ID tempat (cid) supaya nama toko ikut tampil. Cadangan: koordinat pin.
     */
    private static function pinLinkFromEmbed(string $embed): ?string
    {
        $embed = urldecode($embed);
        if (preg_match('/!1s0x[0-9a-f]+:0x([0-9a-f]+)/i', $embed, $m)) {
            return 'https://www.google.com/maps?cid=' . static::hexToDec($m[1]);
        }
        if (preg_match('/!2d(-?\d+(?:\.\d+)?)/', $embed, $lng) && preg_match('/!3d(-?\d+(?:\.\d+)?)/', $embed, $lat)) {
            return 'https://www.google.com/maps/search/?api=1&query=' . $lat[1] . ',' . $lng[1];
        }

        return null;
    }

    /** Link "Buka di Google Maps": link tempat dari admin bila ada, selain itu pencarian nama toko + alamat. */
    public static function mapLink(): ?string
    {
        $v = static::mapsValue();
        if ($v !== '' && str_starts_with($v, 'https://www.google.com/maps/embed') && ($pin = static::pinLinkFromEmbed($v))) {
            return $pin;
        }
        if ($v !== '' && ! str_starts_with($v, 'https://www.google.com/maps/embed')) {
            return $v;
        }
        if (! static::get('address')) {
            return null;
        }

        return 'https://www.google.com/maps/search/?api=1&query=' . urlencode(static::mapsQuery());
    }
}
