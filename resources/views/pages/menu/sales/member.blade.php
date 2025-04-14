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
                <a>Sale</a>
            </li>
        </ul>
    </div>
    <h1 class="text-4xl font-bold mt-2">SALE</h1>

    <!-- main -->
    <div class="mx-auto p-5 rounded-xl shadow-md mt-8">

        <div class="grid grid-cols-2 gap-8">
            <!-- Product Table -->
            <div class="border p-4 shadow-md">
                <h2 class="text-2xl font-bold">
                    Produk yang dipilih
                </h2>
                <table class="table w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left">Nama Produk</th>
                            <th class="text-left">Harga</th>
                            <th class="text-left"></th>
                            <th class="text-left">Jumlah</th>
                            <th class="text-left">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($cart_items as $item)
                            <tr class="border-b">
                                <td>{{ $item['name'] }}</td>
                                <td>Rp. {{ number_format($item['price'], 0, ',', '.') }}
                                </td>
                                <td>x</td>
                                <td>{{ $item['qty'] }}</td>
                                <td>Rp.
                                    {{ number_format($item['qty'] * $item['price'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>

                <div class="mt-4 font-semibold text-lg text-right">
                    <p>Total Harga : Rp. {{ number_format($sub_total, 0, ',', '.') }}</p>
                    <p>Tunai : Rp. {{ number_format($amount_paid, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Member Form -->
            <div>
                <form id="member-form" action="{{ route('sales.memberpayment') }}" method="POST">
                    @csrf
                    <label class="block">Nama Member</label>
                    <input type="text" name="member_name" value="{{ $member_name ?? '' }}"
                        class="input input-bordered w-full" {{ $member_name ? 'readonly' : '' }}>

                    <label class="block mt-4">Total Points</label>
                    <input type="text" value="{{ $point_total }}" disabled
                        class="input input-bordered w-full bg-gray-200">


                    <div class="mt-3 flex items-center">
                        <input type="checkbox" id="use_point" class="checkbox checkbox-primary mr-2"
                            {{ $can_use_point ? '' : 'disabled' }}>
                        <label for="use_point" class="text-gray-700">
                            Gunakan Point
                            @if (!$can_use_point)
                                <span class="text-red-500">Poin tidak dapat digunakan pada pembelanjaan pertama.</span>
                            @endif
                        </label>
                        <input type="hidden" name="use_point" id="usePointsHidden" value="0">
                    </div>

                    <input type="hidden" name="total_point" value="{{ $point_total }}">
                    <input type="hidden" name="cart" id="cart-input">
                    <input type="hidden" name="sub_total" value="{{ $sub_total }}">
                    <input type="hidden" name="amount_paid" value="{{ $amount_paid }}">
                    <input type="hidden" name="phone_number" value="{{ $phone_number }}">

                    <button type="submit" class="mt-4 btn btn-primary w-full">Next</button>
                </form>
            </div>
        </div>
    </div>


    <script>
        var cart = @json($cart_items);

        // Mengisi input hidden sebelum submit form
        document.getElementById('member-form').addEventListener('submit', function(event) {
            document.getElementById('cart-input').value = JSON.stringify(cart);
            document.getElementById('usePointsHidden').value = document.getElementById('use_point').checked ? "1" :
                "0";
        });
    </script>
@endsection
