<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SupabaseService;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProductController extends Controller
{
    /**
     * Display the specified product
     */
    public function show($id, SupabaseService $supabase)
    {
        try {
            $product = $supabase->getWithFilter('products', [
                'id',
                'product_name',
                'description',
                'lab_id'
            ], ['id' => $id]);

            if (empty($product)) {
                abort(404, 'Produk tidak ditemukan');
            }

            return view('products.show', ['product' => $product[0]]);
        } catch (\Throwable $e) {
            logger()->error('Error loading product: ' . $e->getMessage(), ['product_id' => $id]);
            abort(500, 'Terjadi kesalahan saat memuat data produk');
        }
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit($id, SupabaseService $supabase)
    {
        try {
            $product = $supabase->getWithFilter('products', [
                'id',
                'product_name',
                'description',
                'lab_id'
            ], ['id' => $id]);

            if (empty($product)) {
                abort(404, 'Produk tidak ditemukan');
            }

            return view('products.edit', ['product' => $product[0]]);
        } catch (\Throwable $e) {
            logger()->error('Error loading product for edit: ' . $e->getMessage(), ['product_id' => $id]);
            abort(500, 'Terjadi kesalahan saat memuat data produk');
        }
    }

    public function store(Request $request, SupabaseService $supabase)
    {
        // Validasi input
        $request->validate([
            'lab_id' => 'required|string',
            'product_name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        try {
            $now = Carbon::now()->toISOString();
            $id = Str::uuid()->toString();

            // Kirim data ke tabel "products"
            $supabase->getClient()->post('products', [
                'json' => [
                    'id' => $id,
                    'lab_id' => $request->lab_id,
                    'product_name' => $request->product_name,
                    'description' => $request->description,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            ]);

            // Flash message sukses
            return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
        } catch (\Throwable $e) {
            logger()->error('Gagal tambah produk: ' . $e->getMessage());

            // Flash message error
            return redirect()->back()->with('error', 'Gagal menyimpan produk.');
        }
    }

    public function update($id, Request $request, SupabaseService $supabase)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            // Cek apakah produk exists terlebih dahulu
            $existingProduct = $supabase->getWithFilter('products', ['id'], ['id' => $id]);

            if (empty($existingProduct)) {
                return redirect()->back()->with('error', 'Produk tidak ditemukan.');
            }

            $response = $supabase->getClient()->patch("products?id=eq.{$id}", [
                'json' => [
                    'product_name' => $validated['product_name'],
                    'description' => $validated['description'],
                    'updated_at' => Carbon::now()->toISOString(),
                ]
            ]);

            // Cek response status
            if ($response->getStatusCode() >= 400) {
                throw new \Exception('Supabase returned error status: ' . $response->getStatusCode());
            }

            return redirect()->back()->with('success', 'Produk berhasil diubah.');
        } catch (\Throwable $e) {
            logger()->error('Gagal update produk: ' . $e->getMessage(), [
                'product_id' => $id,
                'data' => $validated
            ]);
            return redirect()->back()->with('error', 'Gagal mengubah produk: ' . $e->getMessage());
        }
    }

    public function destroy($id, SupabaseService $supabase)
    {
        try {
            // Cek apakah produk exists terlebih dahulu
            $existingProduct = $supabase->getWithFilter('products', ['id'], ['id' => $id]);

            if (empty($existingProduct)) {
                return redirect()->back()->with('error', 'Produk tidak ditemukan.');
            }

            $response = $supabase->getClient()->delete("products?id=eq.$id");

            // Cek response status
            if ($response->getStatusCode() >= 400) {
                throw new \Exception('Supabase returned error status: ' . $response->getStatusCode());
            }

            return redirect()->back()->with('success', 'Produk berhasil dihapus.');
        } catch (\Throwable $e) {
            logger()->error('Gagal hapus produk: ' . $e->getMessage(), ['product_id' => $id]);
            return redirect()->back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}
