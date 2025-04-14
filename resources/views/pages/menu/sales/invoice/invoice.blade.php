<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Kasir</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            max-width: 800px;
            margin: auto;
        }

        .logo {
            text-align: right;
        }

        .logo img {
            width: 150px;
        }

        h1 {
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        tfoot th {
            text-align: right;
        }

        .notes {
            margin-top: 20px;
            font-size: 14px;
        }

        address {
            margin-top: 20px;
            font-size: 14px;
            font-style: normal;
        }
    </style>
</head>

<body>
    <address>
        MoyyStore<br>
        Alamat: SMK Wikrama Bogor<br>
        Email: moyybusiness@gmail.com</a>
    </address>

    <h1>Invoice - {{ $saleData['sale_id'] }}</h1>
    @if ($saleData['member_id'] !== null)
        <p>Member Status : {{ $saleData['member_name'] }}</p>
        <p>No. HP : {{ $saleData['member_phone'] }}</p>
        <p>Bergabung Sejak : {{ $saleData['member_date'] }}</p>
        <p>Point Member : {{ $saleData['member_point'] }}</p>
    @else
        <p>Member Status : Bukan Member</p>
        <p>No. HP : -</p>
        <p>Bergabung Sejak : -</p>
        <p>Point Member : -</p>
    @endif
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Sub total</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($saleData['products'] as $item)
                <tr>
                    <td> {{ $item['product_name'] }}</td>
                    <td style="text-align: right;"> Rp. {{ number_format($item['price'], 0, ',', '.') }}</td>
                    <td> {{ $item['qty'] }}</td>
                    <td style="text-align: right;"> Rp.{{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Poin digunakan</th>
                <td>{{ $saleData['point_used'] }}</td>
            </tr>
            <tr>
                <th colspan="3">Tunai</th>
                <td style="text-align: right;">Rp. {{ number_format($saleData['amount_paid'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th colspan="3">Kembalian</th>
                <td style="text-align: right;">Rp. {{ number_format($saleData['change'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th colspan="3">Total</th>
                <td style="text-align: right;">Rp. {{ number_format($saleData['total'], 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
    <div class="notes">
        Terima kasih atas pembelian Anda.
    </div>
</body>

</html>
