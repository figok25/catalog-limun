<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Support\Rupiah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /** Maksimal foto tambahan (galeri) per produk, di luar foto utama. */
    private const MAX_GALLERY = 8;

    public function index(Request $request)
    {
        $q = trim($request->string('q')->toString());
        $products = Product::with('category')
            ->when($q, fn ($x) => $x->where('name', 'like', '%' . $q . '%'))
            ->when($request->filled('kategori'), fn ($x) => $x->where('category_id', (int) $request->kategori))
            ->when($request->status === 'aktif', fn ($x) => $x->where('is_active', true))
            ->when($request->status === 'nonaktif', fn ($x) => $x->where('is_active', false))
            ->latest()->paginate(15)->withQueryString();

        return view('admin.products.index', ['products' => $products, 'categories' => Category::orderBy('name')->get()]);
    }

    public function create()
    {
        return view('admin.products.create', ['product' => new Product(['is_active' => true]), 'categories' => Category::active()->orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = Product::uniqueSlug($data['name']);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        $product = Product::create($data);
        $this->syncGallery($request, $product);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->orWhere('id', $product->category_id)->orderBy('name')->get();
        $product->load('images');
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request);

        // Cek batas galeri sebelum ada perubahan yang disimpan.
        $remove = array_map('intval', (array) $request->input('remove_images', []));
        $keep = ProductImage::where('product_id', $product->id)->whereNotIn('id', $remove)->count();
        $incoming = count((array) $request->file('images', []));
        if ($keep + $incoming > self::MAX_GALLERY) {
            throw ValidationException::withMessages([
                'images' => 'Maksimal ' . self::MAX_GALLERY . ' foto tambahan per produk (saat ini ' . $keep . ' foto tersisa).',
            ]);
        }

        if ($data['name'] !== $product->name) {
            $data['slug'] = Product::uniqueSlug($data['name'], $product->id);
        }
        if ($request->hasFile('image')) {
            $new = $request->file('image')->store('products', 'public');
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $new;
        } elseif ($request->boolean('remove_image') && $product->image) {
            Storage::disk('public')->delete($product->image);
            $data['image'] = null;
        }
        $product->update($data);
        $this->syncGallery($request, $product);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->path);
        }
        $product->delete(); // baris product_images ikut terhapus (cascade)

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    /** Hapus foto galeri yang dicentang, lalu simpan foto tambahan yang baru diunggah. */
    private function syncGallery(Request $request, Product $product): void
    {
        $remove = array_map('intval', (array) $request->input('remove_images', []));
        if ($remove) {
            foreach (ProductImage::where('product_id', $product->id)->whereIn('id', $remove)->get() as $img) {
                Storage::disk('public')->delete($img->path);
                $img->delete();
            }
        }

        $order = (int) ProductImage::where('product_id', $product->id)->max('sort_order');
        foreach ((array) $request->file('images', []) as $file) {
            ProductImage::create([
                'product_id' => $product->id,
                'path' => $file->store('products', 'public'),
                'sort_order' => ++$order,
            ]);
        }
    }

    private function validated(Request $request): array
    {
        // Input harga berformat ("Rp 4.900.000") dibersihkan jadi angka sebelum divalidasi.
        $request->merge([
            'price' => Rupiah::parse($request->input('price')),
            'compare_price' => Rupiah::parse($request->input('compare_price')),
        ]);

        $data = $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'price' => 'nullable|numeric|min:0|max:9999999999',
            'discount_mode' => 'nullable|in:none,percent,coret',
            'discount_percent' => 'nullable|integer|min:1|max:99',
            'compare_price' => 'nullable|numeric|min:0|max:9999999999',
            'description' => 'nullable|string|max:5000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'images' => 'nullable|array|max:' . self::MAX_GALLERY,
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'integer',
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'category_id.required' => 'Pilih kategori produk.',
            'price.numeric' => 'Harga harus berupa angka.',
            'discount_percent.integer' => 'Diskon harus berupa angka bulat (persen).',
            'discount_percent.min' => 'Diskon minimal 1%.',
            'discount_percent.max' => 'Diskon maksimal 99%.',
            'compare_price.numeric' => 'Harga coret harus berupa angka.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format foto harus JPG, PNG, atau WebP.',
            'image.max' => 'Ukuran foto maksimal 2 MB.',
            'images.max' => 'Maksimal ' . self::MAX_GALLERY . ' foto tambahan sekaligus.',
            'images.*.image' => 'Semua foto tambahan harus berupa gambar.',
            'images.*.mimes' => 'Format foto tambahan harus JPG, PNG, atau WebP.',
            'images.*.max' => 'Ukuran tiap foto tambahan maksimal 2 MB.',
        ]);
        unset($data['image'], $data['images'], $data['remove_images']);

        // Diskon hanya bermakna bila harga diisi ("Hubungi kami" tidak bisa didiskon).
        $mode = $data['discount_mode'] ?? 'none';
        unset($data['discount_mode']);
        if (($data['price'] ?? null) === null) {
            $mode = 'none';
        }

        if ($mode === 'percent') {
            if (empty($data['discount_percent'])) {
                throw ValidationException::withMessages(['discount_percent' => 'Isi persentase diskon (1-99).']);
            }
            $data['compare_price'] = null;
        } elseif ($mode === 'coret') {
            if (($data['compare_price'] ?? null) === null) {
                throw ValidationException::withMessages(['compare_price' => 'Isi harga coret.']);
            }
            if ((float) $data['compare_price'] <= (float) $data['price']) {
                throw ValidationException::withMessages(['compare_price' => 'Harga coret harus lebih besar dari harga jual.']);
            }
            $data['discount_percent'] = null;
        } else {
            $data['discount_percent'] = null;
            $data['compare_price'] = null;
        }
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
