<?php

namespace App\Http\Controllers;

use App\Services\SupabaseService;

class DashboardController extends Controller
{
    public function index(SupabaseService $supabase)
    {
        $labs = $supabase->getAll('labs');

        $labsWithCounts = [];

        foreach ($labs as $lab) {
            $itemCount = $supabase->countRows('items', ['lab_id' => $lab['id']]);
            $productCount = $supabase->countRows('products', ['lab_id' => $lab['id']]);

            $labsWithCounts[] = [
                'name' => $lab['name'],
                'location' => $lab['location'],
                'item_count' => $itemCount,
                'product_count' => $productCount,
            ];
        }

        return view('dashboard', compact('labsWithCounts'));
    }
}
