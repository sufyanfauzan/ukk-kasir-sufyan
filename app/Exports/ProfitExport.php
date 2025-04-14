<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Carbon;

class ProfitExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $profit_today = DB::table('sales as s')
            ->join('sale_details as sd', 's.id', '=', 'sd.sale_id')
            ->where('s.date', '=', Carbon::today())
            ->selectRaw('SUM(sd.product_price * sd.product_qty) as total_profit')
            ->first();
        $profittoday = $profit_today ? $profit_today->total_profit : 0;

        $profit_week = DB::table('sales as s')
            ->join('sale_details as sd', 's.id', '=', 'sd.sale_id')
            ->where('s.date', '>=', Carbon::now()->subDays(7))
            ->selectRaw('SUM(sd.product_price * sd.product_qty) as total_profit')
            ->first();
        $profitweek = $profit_week ? $profit_week->total_profit : 0;

        $profit_month = DB::table('sales as s')
            ->join('sale_details as sd', 's.id', '=', 'sd.sale_id')
            ->where('s.date', '>=', Carbon::now()->subDays(30))
            ->selectRaw('SUM(sd.product_price * sd.product_qty) as total_profit')
            ->first();
        $profitmonth = $profit_month ? $profit_month->total_profit : 0;

        $profit_year = DB::table('sales as s')
            ->join('sale_details as sd', 's.id', '=', 'sd.sale_id')
            ->where('s.date', '>=', Carbon::now()->subDays(365))
            ->selectRaw('SUM(sd.product_price * sd.product_qty) as total_profit')
            ->first();
        $profityear = $profit_year ? $profit_year->total_profit : 0;

        $products = DB::table('products as s')
            ->select('s.name', 's.stock', 's.price')
            ->groupBy('s.name', 's.stock', 's.price')
            ->get();

        $data = collect([
            ['Today', $profittoday],
            ['This Week', $profitweek],
            ['This Month', $profitmonth],
            ['This Year', $profityear],
        ]);

        foreach ($products as $product) {
            $data->push([
                'Product Name' => $product->name,
                'Stock' => $product->stock,
                'Price' => $product->price,
            ]);
        }

        return $data;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Period', // Column for the profit period (e.g., Today, This Week)
            'Profit',  // Column for the profit value (e.g., Today's profit)
            'Product Name', // Product column heading
            'Stock', // Stock column heading
            'Price', // Price column heading
        ];
    }
}
