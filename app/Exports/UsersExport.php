<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
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
        return DB::table('users as s')
            ->select(
                's.name',
                's.email',
                's.role',
            )
            ->groupBy('s.name', 's.email', 's.role')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nama', 'Email', 'Role',
        ];
    }
}