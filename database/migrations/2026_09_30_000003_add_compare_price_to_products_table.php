<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $t) {
            // Harga coret yang diisi langsung (rupiah). NULL = tidak dipakai.
            $t->decimal('compare_price', 12, 2)->nullable()->after('discount_percent');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $t) {
            $t->dropColumn('compare_price');
        });
    }
};
