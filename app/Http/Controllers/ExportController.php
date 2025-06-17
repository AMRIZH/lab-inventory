<?php

namespace App\Http\Controllers;

use App\Exports\InventarisPerLabExport;
use App\Services\SupabaseService;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function export(SupabaseService $supabase)
    {
        $labs = $supabase->getAll('labs');
        $items = $supabase->getAll('items');
        $products = $supabase->getAll('products');
        $itemUnits = $supabase->getAll('item_units');

        return Excel::download(
            new InventarisPerLabExport($labs, $items, $products, $itemUnits),
            'inventaris.xlsx'
        );
    }
}
