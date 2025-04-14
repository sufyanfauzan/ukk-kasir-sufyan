<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    // protected $id;

    // public function __construct($id)
    // {
    //     $this->id = $id;
    // }

    public function collection()
    {
        return DB::table('products as s')
            ->select(
                's.name',
                's.stock',
                's.price',
            )
            ->groupBy('s.name', 's.stock', 's.price')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nama', 'Stock', 'Harga',
        ];
    }
}