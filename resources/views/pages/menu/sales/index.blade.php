@extends('layouts.main')

@section('content')
    <!-- breadcrumbs -->
    <div class="breadcrumbs text-sm">
        <ul>
            <li>
                <a href="{{ route('dashboard.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">

                        <path
                            d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.061l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.061 1.06l8.69-8.689Z" />

                        <path
                            d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H5.625a1.875 1.875 0 0 1-1.875-1.875v-6.198a2.29 2.29 0 0 0 .091-.086L12 5.432Z" />

                    </svg>
                </a>
            </li>
            <li class="font-bold text-gray-400">
                <a>Penjualan</a>
            </li>
        </ul>
    </div>
    <h1 class="text-4xl font-bold mt-2">PENJUALAN</h1>

    <!-- main -->
    <div class="overflow-x-auto mt-8">
        <!-- button -->
        @if (Auth::user()->role === 'employee')
            <div class="flex justify-end mb-4">
                <a href="{{ route('sales.productSale') }}">
                    <button class="btn btn-primary">Tambah Penjualan</button>
                </a>
            </div>
        @endif
        <div class="flex justify-between items-center mb-4">
            {{-- <a href="{{ route('sales.exportInvoice') }}" class="btn btn-success text-white">Export</a> --}}
            
            <form action="{{ route('sales.index') }}" method="GET" class="flex items-center gap-2">
                <label class="input input-bordered flex items-center gap-2">
                    <input type="text" name="keyword" class="grow" placeholder="Cari"
                        value="{{ request('keyword') }}" />
                    <button type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                            class="h-4 w-4 opacity-70">

                            <path fill-rule="evenodd"
                                d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                                clip-rule="evenodd" />

                        </svg>
                    </button>
                </label>
            </form>
            <div class="dropdown dropdown-end">
                <button class="btn btn-success text-white">Export</button>
                <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52 mt-2">
                    <li><a href="{{ route('sales.exportInvoice', ['filter' => 'daily']) }}">Export Harian</a></li>
                    <li><a href="{{ route('sales.exportInvoice', ['filter' => 'weekly']) }}">Export Mingguan</a></li>
                    <li><a href="{{ route('sales.exportInvoice', ['filter' => 'monthly']) }}">Export Bulanan</a></li>
                    <li><a href="{{ route('sales.exportInvoice') }}">Export Semua</a></li>
                </ul>
            </div>
            
        </div>

        <!-- table -->
        <table class="table">
            <thead>
                <tr>
                    <th width="10">No.</th>
                    <th>Nama Pelanggan</th>
                    <th>Tanggal Penjualan</th>
                    <th>Total Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1 @endphp
                @foreach ($data as $key => $item)
                    <tr>
                        <td>{{ $data->firstItem() + $key }}</td>
                        <td>{{ $item->member_id == null ? 'Non-Member' : $item->member_name }}</td>
                        <td>{{ $item->date }}</td>
                        <td>Rp. {{ number_format($item->sub_total, 0, ',', '.') }}</td>
                        <th>
                            <div class="flex gap-2">
                                <form action="{{ route('sales.invoice', $item->id) }}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm text-white">PDF</button>
                                </form>
                                <a href="{{ route('sales.receipt', $item->id) }}" class="btn btn-primary btn-sm text-white">Show</a>
                            </div>
                        </th>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="flex justify-end mt-4">
            <div class="join">
                {{ $data->links() }}
            </div>
        </div>

    </div>
@endsection
