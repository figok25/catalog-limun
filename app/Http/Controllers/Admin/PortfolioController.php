<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\PortfolioImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PortfolioController extends Controller
{
    /** Maksimal foto per proyek portofolio. */
    private const MAX_IMAGES = 10;

    public function index()
    {
        $portfolios = Portfolio::with('images')->orderBy('sort_order')->latest('id')->paginate(15);

        return view('admin.portfolios.index', compact('portfolios'));
    }

    public function create()
    {
        return view('admin.portfolios.create', ['portfolio' => new Portfolio(['is_active' => true, 'sort_order' => 0])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, true);
        $portfolio = Portfolio::create($data);
        $this->syncImages($request, $portfolio);

        return redirect()->route('admin.portfolios.index')->with('success', 'Portofolio berhasil ditambahkan.');
    }

    public function edit(Portfolio $portfolio)
    {
        $portfolio->load('images');

        return view('admin.portfolios.edit', compact('portfolio'));
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $data = $this->validated($request, false);

        // Cek jumlah foto sebelum ada perubahan yang disimpan.
        $remove = array_map('intval', (array) $request->input('remove_images', []));
        $keep = PortfolioImage::where('portfolio_id', $portfolio->id)->whereNotIn('id', $remove)->count();
        $incoming = count((array) $request->file('images', []));
        if ($keep + $incoming < 1) {
            throw ValidationException::withMessages(['images' => 'Setiap portofolio minimal punya 1 foto.']);
        }
        if ($keep + $incoming > self::MAX_IMAGES) {
            throw ValidationException::withMessages([
                'images' => 'Maksimal ' . self::MAX_IMAGES . ' foto per portofolio (saat ini ' . $keep . ' foto tersisa).',
            ]);
        }

        $portfolio->update($data);
        $this->syncImages($request, $portfolio);

        return redirect()->route('admin.portfolios.index')->with('success', 'Portofolio berhasil diperbarui.');
    }

    public function destroy(Portfolio $portfolio)
    {
        foreach ($portfolio->images as $img) {
            Storage::disk('public')->delete($img->path);
        }
        $portfolio->delete(); // baris portfolio_images ikut terhapus (cascade)

        return redirect()->route('admin.portfolios.index')->with('success', 'Portofolio berhasil dihapus.');
    }

    /** Hapus foto yang dicentang, simpan foto baru, lalu atur ulang urutan (foto sampul di posisi pertama). */
    private function syncImages(Request $request, Portfolio $portfolio): void
    {
        $remove = array_map('intval', (array) $request->input('remove_images', []));
        if ($remove) {
            foreach (PortfolioImage::where('portfolio_id', $portfolio->id)->whereIn('id', $remove)->get() as $img) {
                Storage::disk('public')->delete($img->path);
                $img->delete();
            }
        }

        $order = (int) PortfolioImage::where('portfolio_id', $portfolio->id)->max('sort_order');
        foreach ((array) $request->file('images', []) as $file) {
            PortfolioImage::create([
                'portfolio_id' => $portfolio->id,
                'path' => $file->store('portfolios', 'public'),
                'sort_order' => ++$order,
            ]);
        }

        $ids = PortfolioImage::where('portfolio_id', $portfolio->id)->orderBy('sort_order')->orderBy('id')->pluck('id')->all();
        $cover = (int) $request->input('cover_image_id', 0);
        if ($cover && in_array($cover, $ids, true)) {
            $ids = array_merge([$cover], array_values(array_diff($ids, [$cover])));
        }
        foreach ($ids as $i => $id) {
            PortfolioImage::whereKey($id)->update(['sort_order' => $i + 1]);
        }
    }

    private function validated(Request $request, bool $creating): array
    {
        $v = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'images' => ($creating ? 'required|min:1' : 'nullable') . '|array|max:' . self::MAX_IMAGES,
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'integer',
            'cover_image_id' => 'nullable|integer',
        ], [
            'title.required' => 'Judul proyek wajib diisi.',
            'description.max' => 'Deskripsi maksimal 1000 karakter.',
            'sort_order.integer' => 'Urutan harus berupa angka bulat.',
            'images.required' => 'Pilih minimal 1 foto.',
            'images.min' => 'Pilih minimal 1 foto.',
            'images.max' => 'Maksimal ' . self::MAX_IMAGES . ' foto per portofolio.',
            'images.*.uploaded' => 'Foto gagal diunggah. Ukurannya mungkin melebihi batas server.',
            'images.*.image' => 'Semua file harus berupa gambar.',
            'images.*.mimes' => 'Format foto harus JPG, PNG, atau WebP.',
            'images.*.max' => 'Ukuran tiap foto maksimal 2 MB.',
        ]);

        return [
            'title' => $v['title'],
            'description' => $v['description'] ?? null,
            'sort_order' => (int) ($v['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active'),
        ];
    }
}
