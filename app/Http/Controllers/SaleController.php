<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Sale;
// use Barryvdh\DomPDF\PDF;
use App\Models\Product;
use App\Exports\SalesExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        $data = DB::table('sales as s')
            ->leftJoin('members as m', 's.member_id', '=', 'm.id')
            ->select('s.*', 'm.name as member_name')
            ->when($keyword, function ($query, $keyword) {
                return $query->where('m.name', 'LIKE', "%$keyword%");
            })
            ->simplePaginate(6);

        return view('pages.menu.sales.index', compact('data'));
    }

    public function productSale(Request $request)
    {
        $data = Product::all();
        return view('pages.menu.sales.products', compact('data'));
    }

    public function checkout(Request $request)
    {
        // dd($request->all());

        $cart = json_decode($request->cart_checkout, true);
        $checkout = [];

        foreach ($cart as $item) {
            $product = DB::table('products')->where('id', $item['id'])->first();

            $checkout[] = [
                'id' => $product->id,
                'name' => $product->name,
                'qty' => $item['qty'],
                'price' => $product->price,
            ];
        }
        // dd($checkout);

        return view('pages.menu.sales.checkout', compact('checkout'));
    }

    public function paymentTransaction(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'cart' => 'required',
        ]);

        if ($request->is_member == 1) {

            $cart_items = json_decode($request->cart, true);
            $sub_total = $request->sub_total;
            $amount_paid = $request->amount_paid;
            $phone_number = $request->phone_number;

            $member = DB::table('members')->where('phone_number', $request->phone_number)->first();

            $point_get = floor($sub_total / 100);

            if ($member) {
                $member_name = $member->name;
                $point_default = $member->member_point ?? 0;
                $point_total = $point_default + $point_get;
                $transaction_count = DB::table('sales')->where('member_id', $member->id)->count();
            } else {
                $member_name = null;
                $point_total = $point_get;
                $transaction_count = 0;
            }

            $can_use_point = $transaction_count > 0;

            return view('pages.menu.sales.member', compact(
                'cart_items',
                'sub_total',
                'amount_paid',
                'member_name',
                'phone_number',
                'point_total',
                'can_use_point'
            ));
        } else {
            $change = $request->amount_paid - $request->sub_total;

            $saleId = DB::table('sales')->insertGetId([
                'member_id' => null,
                'date' => now(),
                'amount_paid' => $request->amount_paid,
                'change' => $change,
                'point_used' => 0,
                'sub_total' => $request->sub_total,
                'total' => $request->sub_total,
            ]);

            $cartItems = json_decode($request->cart, true);
            foreach ($cartItems as $item) {
                DB::table('sale_details')->insert([
                    'sale_id' => $saleId,
                    'product_id' => $item['id'],
                    'product_price' => $item['price'],
                    'product_qty' => $item['qty'],
                    'total_price' => $item['qty'] * $item['price'],
                ]);

                DB::table('products')
                    ->where('id', $item['id'])
                    ->decrement('stock', $item['qty']);
            }

            return redirect()->route('sales.receipt', $saleId);
        }
    }

    public function memberTransaction(Request $request)
    {
        // dd($request->all());

        $member = DB::table('members')->where('phone_number', $request->phone_number)->first();

        if (!$member) {
            $point_get = floor($request->sub_total / 100);

            $memberId = DB::table('members')->insertGetId([
                'name' => $request->member_name,
                'phone_number' => $request->phone_number,
                'member_point' => $point_get,
                'date' => now(),
            ]);
        } else {
            $memberId = $member->id;
        }

        $sub_total = $request->sub_total;
        $amount_paid = $request->amount_paid;

        if ($request->use_point > 0) {
            DB::table('members')->where('id', $memberId)->update(['member_point' => 0]);

            $point_use = $request->total_point;
            $total = max($sub_total - $point_use, 0);
            $change = ($amount_paid - ($sub_total - $point_use));
        } else {
            DB::table('members')->where('id', $memberId)->update(['member_point' => $request->total_point]);

            $point_use = 0;
            $total = $request->sub_total;
            $change = ($amount_paid - $sub_total);
        }

        // dd($amount_paid, $change, $point_use, $sub_total, $total);

        $saleId = DB::table('sales')->insertGetId([
            'member_id' => $memberId,
            'date' => now(),
            'amount_paid' => $amount_paid,
            'change' => $change,
            'point_used' => $point_use,
            'sub_total' => $sub_total,
            'total' => $total,
        ]);

        $cartItems = json_decode($request->cart, true);
        foreach ($cartItems as $item) {
            DB::table('sale_details')->insert([
                'sale_id' => $saleId,
                'product_id' => $item['id'],
                'product_price' => $item['price'],
                'product_qty' => $item['qty'],
                'total_price' => $item['qty'] * $item['price'],
            ]);

            DB::table('products')
                ->where('id', $item['id'])
                ->decrement('stock', $item['qty']);
        }

        return redirect()->route('sales.receipt', $saleId);
    }

    public function showReceipt($id)
    {
        $sales = DB::table('sales as s')
            ->join('sale_details as d', 's.id', '=', 'd.sale_id')
            ->join('products as p', 'p.id', '=', 'd.product_id')
            ->leftJoin('members as m', 'm.id', '=', 's.member_id')
            ->where('s.id', $id)
            ->select(
                's.id as sale_id',
                's.member_id',
                'm.name as member_name',
                'm.phone_number as member_phone',
                'm.member_point as member_point',
                'm.date as member_date',
                's.date',
                's.amount_paid',
                's.change',
                's.point_used',
                's.total',
                's.sub_total',
                's.created_at',
                's.updated_at',
                'd.product_qty as qty',
                'd.product_price as price',
                'p.name as product_name'
            )
            ->get();

        if ($sales->isEmpty()) {
            return abort(404, 'Transaksi tidak ditemukan.');
        }

        $sale = $sales->first();

        $saleData = [
            'sale_id' => $sale->sale_id,
            'member_id' => $sale->member_id,
            'member_name' => $sale->member_name,
            'member_phone' => $sale->member_phone,
            'member_point' => $sale->member_point,
            'member_date' => $sale->member_date,
            'date' => $sale->date,
            'amount_paid' => $sale->amount_paid,
            'change' => $sale->change,
            'point_used' => $sale->point_used,
            'total' => $sale->total,
            'sub_total' => $sale->sub_total,
            'created_at' => $sale->created_at,
            'updated_at' => $sale->updated_at,
            'products' => $sales->map(function ($item) {
                return [
                    'product_name' => $item->product_name,
                    'qty' => $item->qty,
                    'price' => $item->price
                ];
            })->toArray()
        ];

        // dd($saleData);

        return view('pages.menu.sales.receipt', compact('saleData'));
    }

    public function printPDF(Request $request, $id)
    {
        $sales = DB::table('sales as s')
            ->join('sale_details as d', 's.id', '=', 'd.sale_id')
            ->join('products as p', 'p.id', '=', 'd.product_id')
            ->leftJoin('members as m', 'm.id', '=', 's.member_id')
            ->where('s.id', $id)
            ->select(
                's.id as sale_id',
                's.member_id',
                'm.name as member_name',
                'm.phone_number as member_phone',
                'm.member_point as member_point',
                'm.date as member_date',
                's.date',
                's.amount_paid',
                's.change',
                's.point_used',
                's.total',
                's.sub_total',
                's.created_at',
                's.updated_at',
                'd.product_qty as qty',
                'd.product_price as price',
                'p.name as product_name'
            )
            ->get();

        $sale = $sales->first();

        $saleData = [
            'sale_id' => $sale->sale_id,
            'member_id' => $sale->member_id,
            'member_name' => $sale->member_name,
            'member_phone' => $sale->member_phone,
            'member_point' => $sale->member_point,
            'member_date' => $sale->member_date,
            'date' => $sale->date,
            'amount_paid' => $sale->amount_paid,
            'change' => $sale->change,
            'point_used' => $sale->point_used,
            'total' => $sale->total,
            'sub_total' => $sale->sub_total,
            'created_at' => $sale->created_at,
            'updated_at' => $sale->updated_at,
            'products' => $sales->map(function ($item) {
                return [
                    'product_name' => $item->product_name,
                    'qty' => $item->qty,
                    'price' => $item->price
                ];
            })->toArray()
        ];

        $pdf = PDF::loadView('pages.menu.sales.invoice.invoice', compact('saleData'));
        $pdfFileName = 'Invoice_' . $id . '.pdf';

        return $pdf->download($pdfFileName);
    }

    // public function exportExcel()
    // {
    //     return Excel::download(new SalesExport(), 'sales_report.xlsx');
    // }

    public function exportExcel(Request $request)
    {
        $filter = $request->query('filter', null);
        return Excel::download(new SalesExport($filter), 'sales.xlsx');
    }
}
