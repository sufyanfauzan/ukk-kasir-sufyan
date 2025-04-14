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

    <div class="grid grid-cols-3 gap-4 mt-8">

        @foreach ($data as $item)
            <div class="card shadow-xl p-4">
                <figure>
                    <img src="{{ URL('storage/products/' . $item->image) }}" class="mask mask-squircle h-30 w-30">
                </figure>
                <div class="card-body">
                    <h2 class="card-title">
                        {{ $item['name'] }}
                    </h2>
                    <p>Stok:
                        {{ $item['stock'] }}
                    </p>
                    <p>Rp.
                        {{ $item['price'] }}
                    </p>

                    <div class="flex items-center space-x-2">
                        <button onclick="updateQuantity({{ $item->id }}, -1, {{ $item->stock }})"
                            class="btn btn-sm btn-outline">-</button>
                        <input type="number" id="qty-{{ $item->id }}" value="0"
                            class="input input-bordered w-12 text-center" readonly>
                        <button onclick="updateQuantity({{ $item->id }}, 1, {{ $item->stock }})"
                            class="btn btn-sm btn-outline">+</button>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
    <form action="{{ route('sales.checkout') }}" id="checkout-form" method="POST">
        @csrf
        <input type="hidden" name="cart_checkout" id="cart_checkout">
        <button type="submit" class="btn btn-success w-full mt-4 text-white">Selanjutnya</button>
    </form>

    </div>

    <script>
        let cart_checkout = [];

        function updateQuantity(id, change, maxStock) {
            var qtyInput = document.getElementById('qty-' + id);
            var qty = parseInt(qtyInput.value) || 0;
            var newQty = qty + change;

            if (newQty < 0) {
                newQty = 0;
            }
            if (newQty > maxStock) {
                newQty = maxStock;
            }

            qtyInput.value = newQty;

            var found = false;
            for (var i = 0; i < cart_checkout.length; i++) {
                if (cart_checkout[i].id === id) {
                    if (newQty === 0) {
                        cart_checkout.splice(i, 1);
                    } else {
                        cart_checkout[i].qty = newQty;
                    }
                    found = true;
                    break;
                }
            }

            if (!found && newQty > 0) {
                cart_checkout.push({
                    id: id,
                    qty: newQty
                });
            }
        }

        document.getElementById('checkout-form').addEventListener('submit', function() {
            document.getElementById('cart_checkout').value = JSON.stringify(cart_checkout);
        });
    </script>
@endsection
