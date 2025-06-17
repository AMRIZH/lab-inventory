<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class LabSheetExport implements WithEvents, WithTitle
{
    protected $lab, $items, $products, $allUnits;

    public function __construct($lab, $items, $products, $allUnits)
    {
        $this->lab = $lab;
        $this->items = $items;
        $this->products = $products;
        $this->allUnits = $allUnits;
    }

    public function title(): string
    {
        return substr($this->lab['name'], 0, 31);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $startCol = 'H';
                $baseCol = ord($startCol);
                $row = 1;

                // Judul
                $sheet->mergeCells("{$startCol}{$row}:" . chr($baseCol + 6) . "{$row}");
                $sheet->setCellValue("{$startCol}{$row}", "DATA INVENTARIS RUANG LABORATORIUM " . strtoupper($this->lab['name']));
                $row++;
                $sheet->mergeCells("{$startCol}{$row}:" . chr($baseCol + 6) . "{$row}");
                $sheet->setCellValue("{$startCol}{$row}", "FAKULTAS KOMUNIKASI & INFORMATIKA");
                $row++;
                $sheet->mergeCells("{$startCol}{$row}:" . chr($baseCol + 6) . "{$row}");
                $sheet->setCellValue("{$startCol}{$row}", "UNIVERSITAS MUHAMMADIYAH SURAKARTA");
                $row += 2;

                // DATA BARANG
                $sheet->setCellValue("{$startCol}{$row}", "DATA BARANG");
                $row++;
                $sheet->fromArray(['NO', 'NAMA BARANG', 'JUMLAH', 'KONDISI BARANG', 'KETERANGAN'], null, "{$startCol}{$row}");
                $row++;
                $itemStartRow = $row;
                $no = 1;
                foreach ($this->items as $item) {
                    $units = collect($this->allUnits)->where('item_id', $item['id']);
                    $jumlah = $units->count();
                    $sheet->setCellValue("{$startCol}{$row}", $no++);
                    $sheet->setCellValue(chr($baseCol + 1) . $row, $item['name']);
                    $sheet->setCellValue(chr($baseCol + 2) . $row, $jumlah ? "{$jumlah} Buah" : '');
                    $sheet->setCellValue(chr($baseCol + 3) . $row, $jumlah > 0 ? 'Baik' : '');
                    $sheet->setCellValue(chr($baseCol + 4) . $row, $item['description']);
                    $row++;
                }

                $row += 2;

                // DATA PRODUK
                $sheet->setCellValue("{$startCol}{$row}", "DATA PRODUK");
                $row++;
                $sheet->fromArray(['NO', 'PRODUK BARANG', 'KETERANGAN'], null, "{$startCol}{$row}");
                $row++;
                $productStartRow = $row;
                $no = 1;
                foreach ($this->products as $product) {
                    $sheet->setCellValue("{$startCol}{$row}", $no++);
                    $sheet->setCellValue(chr($baseCol + 1) . $row, $product['product_name']);
                    $sheet->setCellValue(chr($baseCol + 2) . $row, $product['description']);
                    $row++;
                }

                $row += 2;

                // KODE INVENTARIS PER UNIT
                $sheet->setCellValue("{$startCol}{$row}", "KODE INVENTARIS PER UNIT");
                $row++;
                $sheet->fromArray(['NAMA BARANG', 'KODE UNIT', 'KONDISI'], null, "{$startCol}{$row}");
                $row++;
                $unitStartRow = $row;
                foreach ($this->items as $item) {
                    $units = collect($this->allUnits)->where('item_id', $item['id']);
                    foreach ($units as $unit) {
                        $sheet->setCellValue("{$startCol}{$row}", $item['name']);
                        $sheet->setCellValue(chr($baseCol + 1) . $row, $unit['code'] ?? '-');
                        $sheet->setCellValue(chr($baseCol + 2) . $row, $unit['condition']);
                        $row++;
                    }
                }

                $lastCol = chr($baseCol + 6);

                // Border semua area
                $sheet->getStyle("{$startCol}1:{$lastCol}{$row}")
                    ->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // Autosize kolom
                foreach (range($startCol, $lastCol) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Style judul (bold + center)
                foreach ([1, 2, 3] as $headerRow) {
                    $sheet->getStyle("{$startCol}{$headerRow}")->applyFromArray([
                        'font' => ['bold' => true],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);
                }

                // Bold header tabel
                $sheet->getStyle("{$startCol}6:{$lastCol}6")->getFont()->setBold(true); // Header barang
                $sheet->getStyle("{$startCol}" . ($itemStartRow - 2) . ":{$lastCol}" . ($itemStartRow - 2))->getFont()->setBold(true); // "DATA BARANG"
                $sheet->getStyle("{$startCol}" . ($productStartRow - 2) . ":{$lastCol}" . ($productStartRow - 2))->getFont()->setBold(true); // "DATA PRODUK"
                $sheet->getStyle("{$startCol}" . ($unitStartRow - 2) . ":{$lastCol}" . ($unitStartRow - 2))->getFont()->setBold(true); // "KODE INVENTARIS"

                // NON-bold untuk isi data
                $sheet->getStyle("{$startCol}{$itemStartRow}:{$lastCol}" . ($productStartRow - 4))->getFont()->setBold(false);   // item
                $sheet->getStyle("{$startCol}{$productStartRow}:{$lastCol}" . ($unitStartRow - 4))->getFont()->setBold(false); // produk
                $sheet->getStyle("{$startCol}{$unitStartRow}:{$lastCol}{$row}")->getFont()->setBold(false);                    // unit
            }
        ];
    }
}
