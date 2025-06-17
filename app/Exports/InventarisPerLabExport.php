<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class InventarisPerLabExport implements WithMultipleSheets
{
    protected $labs, $items, $products, $itemUnits;

    public function __construct($labs, $items, $products, $itemUnits)
    {
        $this->labs = $labs;
        $this->items = $items;
        $this->products = $products;
        $this->itemUnits = $itemUnits;
    }

    public function sheets(): array
    {
        $sheets = [];
        foreach ($this->labs as $lab) {
            $labItems = collect($this->items)->where('lab_id', $lab['id']);
            $labProducts = collect($this->products)->where('lab_id', $lab['id']);
            $sheets[] = new LabSheetExport($lab, $labItems, $labProducts, $this->itemUnits);
        }

        return $sheets;
    }
}
