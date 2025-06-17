<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SupabaseService;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ItemController extends Controller
{
    /**
     * Display the specified item
     */
    public function show($id, SupabaseService $supabase)
    {
        try {
            $item = $supabase->getWithFilter('items', [
                'id',
                'name',
                'description',
                'lab_id',
                'item_units(id,code,condition)'
            ], ['id' => $id]);

            if (empty($item)) {
                abort(404, 'Item tidak ditemukan');
            }

            $item = $item[0];

            // Pastikan item_units selalu array
            if (!isset($item['item_units']) || !is_array($item['item_units'])) {
                $item['item_units'] = [];
            }

            return view('items.show', compact('item'));
        } catch (\Throwable $e) {
            logger()->error('Error loading item: ' . $e->getMessage(), ['item_id' => $id]);
            abort(500, 'Terjadi kesalahan saat memuat data item');
        }
    }

    /**
     * Show the form for editing the specified item
     */
    public function edit($id, SupabaseService $supabase)
    {
        try {
            $item = $supabase->getWithFilter('items', [
                'id',
                'name',
                'description',
                'lab_id'
            ], ['id' => $id]);

            if (empty($item)) {
                abort(404, 'Item tidak ditemukan');
            }

            return view('items.edit', ['item' => $item[0]]);
        } catch (\Throwable $e) {
            logger()->error('Error loading item for edit: ' . $e->getMessage(), ['item_id' => $id]);
            abort(500, 'Terjadi kesalahan saat memuat data item');
        }
    }

    public function store(Request $request, SupabaseService $supabase)
    {
        // ✅ Validasi input
        $request->validate([
            'lab_id' => 'required|string',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'units' => 'required|array|min:1',
            'units.*.condition' => 'required|in:Baik,Rusak',
        ]);

        try {
            $itemId = Str::uuid()->toString();
            $now = Carbon::now()->toISOString();

            // ✅ Simpan item ke Supabase
            $supabase->getClient()->post('items', [
                'json' => [
                    'id' => $itemId,
                    'lab_id' => $request->lab_id,
                    'name' => $request->name,
                    'description' => $request->description,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            ]);

            // ✅ Simpan semua unit
            foreach ($request->units as $unit) {
                $supabase->getClient()->post('item_units', [
                    'json' => [
                        'item_id' => $itemId,
                        'code' => $unit['code'] ?? null,
                        'condition' => $unit['condition'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                ]);
            }

            // ✅ Flash message sukses
            return redirect()->back()->with('success', 'Item berhasil ditambahkan!');
        } catch (\Throwable $e) {
            logger()->error('Gagal tambah item: ' . $e->getMessage());

            // ❌ Flash message error
            return redirect()->back()->with('error', 'Gagal menyimpan item.');
        }
    }

    public function update($id, Request $request, SupabaseService $supabase)
    {
        // ✅ Validasi data input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        try {
            // Cek apakah item exists terlebih dahulu
            $existingItem = $supabase->getWithFilter('items', ['id'], ['id' => $id]);

            if (empty($existingItem)) {
                return redirect()->back()->with('error', 'Item tidak ditemukan.');
            }

            $now = Carbon::now()->toISOString();

            // ✅ Update item ke Supabase
            $response = $supabase->getClient()->patch('items?id=eq.' . $id, [
                'json' => [
                    'name' => $validated['name'],
                    'description' => $validated['description'],
                    'updated_at' => $now
                ]
            ]);

            // Cek response status
            if ($response->getStatusCode() >= 400) {
                throw new \Exception('Supabase returned error status: ' . $response->getStatusCode());
            }

            return redirect()->back()->with('success', 'Item berhasil diubah!');
        } catch (\Throwable $e) {
            logger()->error('Gagal update item: ' . $e->getMessage(), [
                'item_id' => $id,
                'data' => $validated
            ]);

            return redirect()->back()->with('error', 'Gagal mengupdate item: ' . $e->getMessage());
        }
    }

    public function storeUnits($item, Request $request, SupabaseService $supabase)
    {
        $request->validate([
            'units' => 'required|array|min:1',
            'units.*.condition' => 'required|in:Baik,Rusak',
            'units.*.code' => 'nullable|string',
        ]);

        try {
            $now = Carbon::now()->toISOString();

            foreach ($request->units as $unit) {
                $supabase->getClient()->post('item_units', [
                    'json' => [
                        'item_id' => $item, // Use the route parameter instead
                        'code' => $unit['code'] ?? null,
                        'condition' => $unit['condition'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                ]);
            }

            return redirect()->back()->with('success', 'Unit berhasil ditambahkan!');
        } catch (\Throwable $e) {
            logger()->error('Gagal tambah unit: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan unit.');
        }
    }

    public function destroy($id, SupabaseService $supabase)
    {
        try {
            // Hapus item (unit akan ikut kehapus via ON DELETE CASCADE)
            $supabase->getClient()->delete("items?id=eq.$id");

            return redirect()->back()->with('success', 'Item dan semua unit berhasil dihapus.');
        } catch (\Throwable $e) {
            logger()->error('Gagal hapus item: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus item.');
        }
    }
}
