<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class SalesExport implements FromCollection, WithHeadings
{
    protected $filter;

    /**
     *
     * @param string|null
     */
    public function __construct($filter = null)
    {
        $this->filter = $filter;
    }

    /**
     *
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = DB::table('sales as s')
            ->join('sale_details as d', 's.id', '=', 'd.sale_id')
            ->join('products as p', 'p.id', '=', 'd.product_id')
            ->leftJoin('members as m', 'm.id', '=', 's.member_id')
            ->select(
                's.date as purchase_date',
                'm.name as member_name',
                'm.phone_number as member_phone',
                'm.member_point as member_points',
                'm.date as member_join_date',
                DB::raw("GROUP_CONCAT(CONCAT(p.name, ' ( ', d.product_qty, ' pcs : Rp. ', d.product_price, ' ) ') SEPARATOR ' , ') as product_details"),
                's.total as total_amount',
                's.amount_paid as cash_paid',
                's.point_used',
                's.change as change_amount',
                DB::raw("SUM((d.product_price) * d.product_qty) as profit")
            )
            ->groupBy(
                's.id',
                's.date',
                'm.name',
                'm.phone_number',
                'm.member_point',
                'm.date',
                's.total',
                's.amount_paid',
                's.point_used',
                's.change'
            );

        if ($this->filter === 'daily') {
            $query->whereDate('s.date', Carbon::today());
        } elseif ($this->filter === 'weekly') {
            $query->whereBetween('s.date', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ]);
        } elseif ($this->filter === 'monthly') {
            $query->whereMonth('s.date', Carbon::now()->month);
        }

        $data = $query->orderBy('s.date', 'desc')->get();

        $profitQuery = DB::table('sales as s')
            ->join('sale_details as d', 's.id', '=', 'd.sale_id')
            ->join('products as p', 'p.id', '=', 'd.product_id');

        if ($this->filter === 'daily') {
            $profitQuery->whereDate('s.date', Carbon::today());
        } elseif ($this->filter === 'weekly') {
            $profitQuery->whereBetween('s.date', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ]);
        } elseif ($this->filter === 'monthly') {
            $profitQuery->whereMonth('s.date', Carbon::now()->month);
        }

        $totalProfit = $profitQuery
            ->select(DB::raw('SUM(d.product_price * d.product_qty) as total_profit'))
            ->value('total_profit');

        $data->push([
            'purchase_date' => '',
            'member_name' => '',
            'member_phone' => '',
            'member_points' => '',
            'member_join_date' => '',
            'product_details' => '',
            'total_amount' => '',
            'cash_paid' => '',
            'point_used' => '',
            'change_amount' => 'Total Profit:',
            'profit' => $totalProfit ?? 0,
        ]);

        return $data;
    }

    public function headings(): array
    {
        return [
            'Tanggal Pembelian',
            'Nama Pelanggan',
            'No HP Pelanggan',
            'Poin Pelanggan',
            'Tanggal Bergabung',
            'Detail Produk',
            'Total Pembayaran',
            'Pembayaran Tunai',
            'Poin Digunakan',
            'Kembalian',
            'Profit'
        ];
    }
}
