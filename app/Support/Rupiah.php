<?php

namespace App\Support;

class Rupiah
{
    /** 4900000 -> "Rp 4.900.000" */
    public static function format(int|float|string|null $n): string
    {
        return 'Rp ' . number_format((float) $n, 0, ',', '.');
    }

    /**
     * Ambil angka dari input berformat: "Rp 4.900.000" -> 4900000.
     * Kosong / tanpa digit -> null.
     */
    public static function parse(mixed $value): ?int
    {
        if ($value === null) {
            return null;
        }
        $digits = preg_replace('/\D/', '', (string) $value);

        return $digits === '' ? null : (int) $digits;
    }
}
