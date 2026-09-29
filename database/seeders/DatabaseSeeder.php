<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun admin awal. Ganti password lewat ADMIN_PASSWORD di .env sebelum seed.
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@limunjaya.test')],
            ['name' => 'Admin', 'password' => Hash::make(env('ADMIN_PASSWORD', '123'))]
        );

        $cats = [
            ['Ruang Tamu', 'Sofa, meja tamu, lemari TV'],
            ['Kamar Tidur', 'Tempat tidur, lemari, nakas'],
            ['Ruang Makan', 'Meja makan, kursi, bufet'],
            ['Kantor', 'Meja kerja, kursi, lemari arsip'],
            ['Outdoor', 'Kursi taman, meja teras'],
        ];
        $c = [];
        foreach ($cats as [$name, $desc]) {
            $c[$name] = Category::firstOrCreate(['name' => $name], ['slug' => Category::uniqueSlug($name), 'description' => $desc]);
        }

        // Produk contoh - hapus/ganti dari panel admin dengan produk asli.
        $products = [
            ['Sofa Minimalis Modern', 'Ruang Tamu', 3500000],
            ['Set Kamar Tidur Minimalis', 'Kamar Tidur', 4900000],
            ['Meja Makan 6 Kursi', 'Ruang Makan', 2750000],
            ['Lemari Pakaian 3 Pintu', 'Kamar Tidur', 2150000],
            ['Meja Kerja Minimalis', 'Kantor', 1250000],
        ];
        foreach ($products as [$name, $cat, $price]) {
            Product::firstOrCreate(['name' => $name], [
                'slug' => Product::uniqueSlug($name), 'category_id' => $c[$cat]->id, 'price' => $price,
                'description' => "Contoh deskripsi untuk {$name}. Ganti dengan deskripsi dan foto produk asli dari panel admin.",
                'is_featured' => true,
            ]);
        }

        $defaults = [
            'store_name' => 'Limun Jaya Furniture',
            'tagline' => 'Menyediakan berbagai kebutuhan furniture rumah, kantor, dan pelengkap interior dengan desain modern, kualitas terbaik, dan harga yang bersahabat.',
            'about' => 'Limun Jaya Furniture adalah toko furniture yang berkomitmen menghadirkan produk berkualitas dengan desain modern dan fungsional. Kami percaya bahwa setiap rumah dan ruang kerja berhak mendapatkan furniture terbaik yang nyaman, stylish, dan tahan lama.',
            'years_experience' => '10',
            'whatsapp' => '',
            'address' => '',
            'instagram' => '',
            'hours' => 'Senin - Sabtu, 08.00 - 17.00',
            'maps_url' => '',
        ];
        foreach ($defaults as $k => $v) {
            Setting::firstOrCreate(['key' => $k], ['value' => $v]);
        }
    }
}
