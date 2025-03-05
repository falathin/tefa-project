<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Show Transaksi Sparepart</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>

<body>

    <h1>Show Transaksi Sparepart</h1>

    <h2>Data Transaksi dan Sparepart yang Digunakan</h2>
    <p>{{ json_encode($transaction->transactionSpareparts, JSON_PRETTY_PRINT) }}</p>

    <h2>Total Harga dari Jumlah Sparepart yang Digunakan</h2>
    <p>{{ number_format($totalPrice) }}</p>

    <h3>Tabel Nama Sparepart yang Digunakan</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Sparepart</th>
                <th>Spesifikasi</th>
                <th>Jumlah</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Keuntungan</th>
                <th>Subtotal</th>
                <th>Jurusan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaction->transactionSpareparts as $sparepart)
                <tr>
                    <td>{{ $sparepart->sparepart->id_sparepart }}</td>
                    <td>{{ $sparepart->sparepart->nama_sparepart }}</td>
                    <td>{{ $sparepart->sparepart->spek }}</td>
                    <td id="quantity">{{ $sparepart->quantity }}</td>
                    <td>{{ number_format($sparepart->sparepart->harga_beli) }}</td>
                    <td class="harga_jual">{{ number_format($sparepart->sparepart->harga_jual) }}</td>
                    <td>{{ number_format($sparepart->sparepart->keuntungan) }}</td>
                    <td>subtotal</td>
                    <td>{{ $sparepart->sparepart->jurusan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log(document.getElementById('quantity'));
        });
    </script> --}}
</body>

</html>
