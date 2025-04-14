<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ProfitExport;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index()
    {
         $bar_data = DB::table('sales')
         ->selectRaw('date, COUNT(*) as total_sales')
         ->groupBy('date')
         ->orderBy('date', 'ASC')
         ->get();

        $dates = $bar_data->pluck('date');
        $totals = $bar_data->pluck('total_sales');

        // pie chart
        $pie_data = DB::table('sale_details as sd')
            ->join('products as p', 'sd.product_id', '=', 'p.id')
            ->selectRaw('p.name, SUM(sd.product_qty) as total_sold')
            ->groupBy('p.name')
            ->get();

        $product_name = $pie_data->pluck('name');
        $product_qty = $pie_data->pluck('total_sold');

        $today_sale = DB::table('sales as s')
            ->where('s.date', '=', Carbon::today())
            ->count();

        $profit_today = DB::table('sales as s')
            ->join('sale_details as sd', 's.id', '=', 'sd.sale_id')
            ->where('s.date', '=', Carbon::today())
            ->selectRaw('SUM(sd.product_price * sd.product_qty) as total_profit')
            ->first();
        $profittoday = $profit_today->total_profit;

        $profit_week = DB::table('sales as s')
            ->join('sale_details as sd', 's.id', '=', 'sd.sale_id')
            ->where('s.date', '>=', Carbon::now()->subDays(7))
            ->selectRaw('SUM(sd.product_price * sd.product_qty) as total_profit')
            ->first();
        $profitweek = $profit_week->total_profit;

        $profit_month = DB::table('sales as s')
            ->join('sale_details as sd', 's.id', '=', 'sd.sale_id')
            ->where('s.date', '>=', Carbon::now()->subDays(30))
            ->selectRaw('SUM(sd.product_price * sd.product_qty) as total_profit')
            ->first();
        $profitmonth = $profit_month->total_profit;

        $profit_year = DB::table('sales as s')
            ->join('sale_details as sd', 's.id', '=', 'sd.sale_id')
            ->where('s.date', '>=', Carbon::now()->subDays(365))
            ->selectRaw('SUM(sd.product_price * sd.product_qty) as total_profit')
            ->first();
        $profityear = $profit_year->total_profit;

        return view('pages.menu.dashboard.index', compact('dates', 'totals', 'product_name', 'product_qty', 'today_sale', 'profittoday', 'profitweek', 'profitmonth', 'profityear'));

        // return view('pages.menu.dashboard.index');
    }

    public function exportExcel()
    {
        return Excel::download(new ProfitExport(), 'profit.xlsx');
    }
}
