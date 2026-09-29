<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.categories.index', [
            'categories' => Category::withCount('products')->orderBy('name')->get(),
            'editing' => $request->filled('edit') ? Category::find($request->edit) : null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = Category::uniqueSlug($data['name']);
        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return redirect()->route('admin.categories.index', ['edit' => $category->id]);
    }

    public function update(Request $request, Category $category)
    {
        $data = $this->validated($request);
        if ($data['name'] !== $category->name) {
            $data['slug'] = Category::uniqueSlug($data['name'], $category->id);
        }
        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'Kategori masih memiliki produk. Pindahkan atau hapus produknya terlebih dahulu.');
        }
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:150',
        ], ['name.required' => 'Nama kategori wajib diisi.']);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
