<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Product::all();
        return view('pages.menu.products.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.menu.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'name' => 'required',
            'stock' => 'required',
            'price' => 'required',
        ]);

        if($request->file('image')){
            $extensionFile = $request->file('image')->getClientOriginalExtension();
            $nameFile = $request->name.'-'.now()->timestamp.'.'.$extensionFile;
            $move = $request->file('image')->storeAs('products', $nameFile, 'public');
        } else {
            $nameFile = null;
        }

        Product::create([
            'name' => $request->name,
            'stock' => $request->stock,
            'price' => $request->price,
            'image' => $nameFile,
        ]);

        return redirect()->route('products.index')->with('Success', 'Berhasilkan Menambahkan Data');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product, $id)
    {
        $data = Product::find($id);
        return view('pages.menu.products.update', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product, $id)
    {
        $data = Product::find($id);

        if($request->file('image')){
            $extensionFile = $request->file('image')->getClientOriginalExtension();
            $nameFile = $request->name.'-'.now()->timestamp.'.'.$extensionFile;
            $move = $request->file('image')->storeAs('products', $nameFile, 'public');

            $data->update([
                'name' => $request->name,
                'stock' => $request->stock,
                'price' => $request->price,
                'image' => $nameFile,
            ]);
        } else {
            $data->update([
                'name' => $request->name,
                'stock' => $request->stock,
                'price' => $request->price,
            ]);
        }

        return redirect()->route('products.index')->with('Success', 'Berhasilkan Mengubah Data');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, $id)
    {
        $data = Product::find($id)->delete();

        return redirect()->back()->with('Success', 'Berhasilkan Menghapus Data');
    }

    public function exportExcel()
    {
        return Excel::download(new ProductExport(), 'products.xlsx');
    }
}
