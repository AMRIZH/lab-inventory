<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SupabaseService;

class LabController extends Controller
{
    public function show($slug, SupabaseService $supabase)
    {
        try {
            // Ambil semua lab, pastikan bentuknya array
            $labs = collect($supabase->getAll('labs'));

            if ($labs->isEmpty()) {
                abort(404, 'Tidak ada lab yang ditemukan');
            }

            // Cari lab berdasarkan slug (slug = nama lab yang di-URL-kan)
            $lab = $labs->first(function ($lab) use ($slug) {
                // Pastikan lab memiliki key 'name'
                if (!isset($lab['name'])) {
                    return false;
                }
                return strtolower(str_replace(' ', '-', $lab['name'])) === strtolower($slug);
            });

            if (!$lab) {
                abort(404, 'Lab tidak ditemukan');
            }

            // Pastikan lab memiliki ID
            if (!isset($lab['id'])) {
                abort(500, 'Data lab tidak valid');
            }

            $labId = $lab['id'];

            // Ambil products dengan error handling
            $products = $supabase->getWithFilter('products', ['id', 'product_name', 'description'], [
                'lab_id' => $labId
            ]);

            // Pastikan products adalah array
            if (!is_array($products)) {
                $products = [];
            }

            // Ambil items dengan error handling
            $items = $supabase->getWithFilter('items', [
                'id',
                'name',
                'description',
                'item_units(id,code,condition)'
            ], [
                'lab_id' => $labId
            ]);

            // Pastikan items adalah array dan memiliki struktur yang benar
            if (!is_array($items)) {
                $items = [];
            }

            // Validasi dan normalisasi data items
            $items = array_map(function ($item) {
                // Pastikan item_units selalu array
                if (!isset($item['item_units']) || !is_array($item['item_units'])) {
                    $item['item_units'] = [];
                }

                // Pastikan setiap field yang diperlukan ada
                $item['name'] = $item['name'] ?? '';
                $item['description'] = $item['description'] ?? '';

                return $item;
            }, $items);

            return view('labs.detail', compact('lab', 'products', 'items'));

        } catch (\Throwable $e) {
            logger()->error('Error loading lab data: ' . $e->getMessage(), [
                'slug' => $slug,
                'trace' => $e->getTraceAsString()
            ]);

            abort(500, 'Terjadi kesalahan saat memuat data lab');
        }
    }
}
