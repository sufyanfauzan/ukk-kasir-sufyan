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
            <li class="font-bold text-gray-400"><a>Penjualan</a></li>
        </ul>
    </div>
    <h1 class="text-4xl font-bold mt-2">PENJUALAN</h1>

    <!-- main -->
    <div class="p-6 shadow-lg rounded-lg">
        <div class="flex justify-start mt-4 space-x-2 mb-5">
            <form action="{{ route('sales.invoice', $saleData['sale_id']) }}" method="post">
                @csrf
                <button type="submit" class="btn btn-warning px-5 py-2 text-white">Unduh</button>
            </form>
            <button class="bg-gray-500 text-white px-5 py-2 rounded-lg"><a
                    href="{{ route('sales.index') }}">Kembali</a></button>
        </div>

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Invoice - #{{ $saleData['sale_id'] }}</h2>
            <span class="text-sm">{{ \Carbon\Carbon::parse($saleData['date'])->format('d F Y') }}</span>
        </div>

        @if ($saleData['member_id'] !== null)
            <div class="border border-gray-300 p-4 rounded-lg mb-6">
                <p class="font-semibold">
                    MEMBER SEJAK :

                    {{ isset($saleData['member_date']) ? \Carbon\Carbon::parse($saleData['member_date'])->format('d F Y') : '-' }}
                </p>
                <p class="font-semibold">
                    MEMBER POIN : {{ number_format($saleData['member_point'], 0, ',', '.') ?? '-' }}
                </p>
            </div>
        @endif

        <div>
            <table class="w-full text-left border-collapse border border-gray-200">
                <thead class="border-b">
                    <tr>
                        <th class="py-3 px-4">Produk</th>
                        <th class="py-3 px-4">Harga</th>
                        <th class="py-3 px-4">Jumlah</th>
                        <th class="py-3 px-4">Sub Total</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($saleData['products'] as $item)
                        <tr class="border-b">
                            <td class="py-2 px-4">
                                {{ $item['product_name'] }}
                            </td>
                            <td class="py-2 px-4">
                                Rp. {{ number_format($item['price'], 0, ',', '.') }}
                            </td>
                            <td class="py-2 px-4">
                                {{ $item['qty'] }}
                            </td>
                            <td class="py-2 px-4">

                                Rp.{{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        <div class="mt-5 grid grid-cols-4 bg-gray-200 rounded-t-lg">
            <div class="text-left p-4">
                <p class="text-sm text-gray-500">POIN DIGUNAKAN</p>
                <p class="text-xl font-semibold text-gray-700">{{ $saleData['point_used'] }}</p>
            </div>
            <div class="text-left p-4">
                <p class="text-sm text-gray-500">TUNAI</p>
                <p class="text-xl font-semibold text-gray-700">Rp.
                    {{ number_format($saleData['amount_paid'], 0, ',', '.') }}</p>
            </div>
            <div class="text-left p-4">
                <p class="text-sm text-gray-500">KEMBALIAN</p>
                <p class="text-xl font-semibold text-gray-700">Rp.
                    {{ number_format($saleData['change'], 0, ',', '.') }}</p>
            </div>

            @if ($saleData['point_used'] > 0)
                <div class="bg-gray-800 text-white p-4 rounded-b-lg flex justify-between items-center">
                    <p class="text-sm">TOTAL</p>
                    <div class="text-right">
                        <p class="text-2xl font-semibold line-through opacity-50">Rp.
                            {{ number_format($saleData['sub_total'], 0, ',', '.') }}</p>
                        <p class="text-2xl font-semibold">Rp.
                            {{ number_format($saleData['total'], 0, ',', '.') }}</p>
                    </div>
                </div>
            @else
                <div class="bg-gray-800 text-white p-4 rounded-b-lg flex justify-between items-center">
                    <p class="text-sm">TOTAL</p>
                    <p class="text-2xl font-semibold">Rp. {{ number_format($saleData['total'], 0, ',', '.') }}
                    </p>
                </div>
            @endif

        </div>
    @endsection
