<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SupabaseService;
use Carbon\Carbon;

class ItemUnitController extends Controller
{
    /**
     * Hapus 1 unit berdasarkan ID
     */
    public function destroy($id, SupabaseService $supabase)
    {
        try {
            // Cek apakah unit exists terlebih dahulu
            $unit = $supabase->getWithFilter('item_units', ['item_id'], ['id' => $id]);

            if (empty($unit)) {
                return redirect()->back()->with('error', 'Unit tidak ditemukan.');
            }

            $itemId = $unit[0]['item_id'] ?? null;

            // Hapus unit
            $response = $supabase->getClient()->delete("item_units?id=eq.$id");

            // Cek response status
            if ($response->getStatusCode() >= 400) {
                throw new \Exception('Supabase returned error status: ' . $response->getStatusCode());
            }

            // Kalau masih ada item_id, cek apakah masih ada unit lainnya
            if ($itemId) {
                $remaining = $supabase->getWithFilter('item_units', ['id'], ['item_id' => $itemId]);

                if (count($remaining) === 0) {
                    $supabase->getClient()->delete("items?id=eq.$itemId");
                }
            }

            return redirect()->back()->with('success', 'Unit berhasil dihapus.');
        } catch (\Throwable $e) {
            logger()->error('Gagal hapus unit: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus unit.');
        }
    }



    /**
     * (Opsional) Update kondisi banyak unit sekaligus
     */
    public function massUpdate(Request $request, SupabaseService $supabase)
    {
        $unitUpdates = $request->input('unit_updates', []);

        foreach ($unitUpdates as $unitId => $data) {
            $code = $data['code'] ?? null;
            $condition = $data['condition'] ?? null;

            if (!$condition) continue;

            try {
                $supabase->getClient()->patch("item_units?id=eq.$unitId", [
                    'json' => [
                        'code' => $code,
                        'condition' => $condition,
                        'updated_at' => now()->toISOString(),
                    ]
                ]);
            } catch (\Throwable $e) {
                logger()->warning("Gagal update unit $unitId: " . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Perubahan unit berhasil disimpan!');
    }


    /**
     * (Opsional) Insert langsung 1 unit
     */
    public function store(Request $request, SupabaseService $supabase)
    {
        $request->validate([
            'item_id' => 'required|string',
            'code' => 'nullable|string',
            'condition' => 'required|in:Baik,Rusak',
        ]);

        try {
            $supabase->getClient()->post('item_units', [
                'json' => [
                    'item_id' => $request->item_id,
                    'code' => $request->code,
                    'condition' => $request->condition,
                    'created_at' => Carbon::now()->toISOString(),
                    'updated_at' => Carbon::now()->toISOString(),
                ]
            ]);

            return redirect()->back()->with('success', 'Unit berhasil ditambahkan!');
        } catch (\Throwable $e) {
            logger()->error('Gagal tambah unit: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan unit.');
        }
    }
}
