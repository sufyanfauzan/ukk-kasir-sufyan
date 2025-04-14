<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = User::all();
        return view('pages.menu.users.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.menu.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);

        $pass = bcrypt($request->password);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $pass,
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('Success', 'Berhasilkan Menambahkan Data');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = User::find($id);
        return view('pages.menu.users.update', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = User::find($id);

        if( $request->password > 0 ){
            $pass = bcrypt($request->password);

            $data->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $pass,
                'role' => $request->role,
            ]);
        } else {
            $data->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ]);
        }

        return redirect()->route('users.index')->with('Success', 'Berhasilkan Mengubah Data');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = User::find($id)->delete();

        return redirect()->back()->with('Success', 'Berhasilkan Menghapus Data');
    }

    public function exportExcel()
    {
        return Excel::download(new UsersExport(), 'users.xlsx');
    }
}
