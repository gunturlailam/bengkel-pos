<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Nota {{ $workOrder->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #111;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #111;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .header p {
            margin: 2px 0 0;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .info td {
            padding: 2px 0;
            vertical-align: top;
        }

        .items th,
        .items td {
            border: 1px solid #444;
            padding: 6px;
        }

        .items th {
            background: #eee;
        }

        .right {
            text-align: right;
        }

        .totals {
            margin-top: 8px;
            margin-left: auto;
            width: 45%;
        }

        .totals td {
            padding: 3px 6px;
        }

        .grand {
            font-weight: bold;
            border-top: 1px solid #111;
        }

        .sign td {
            width: 50%;
            text-align: center;
            font-size: 11px;
            padding-top: 8px;
        }

        .sign .space {
            height: 70px;
        }

        .footer {
            text-align: center;
            margin-top: 16px;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>BENGKEL POS</h1>
        <p>Jl. Contoh Alamat No. 123, Kota Kamu · Telp: 0812-3456-7890</p>
    </div>

    <table class="info">
        <tr>
            <td width="50%">
                <strong>Pelanggan:</strong><br>
                {{ $workOrder->customer->name }}<br>
                {{ $workOrder->customer->phone }}<br>
                {{ $workOrder->customer->address }}
            </td>
            <td width="50%">
                <strong>Kendaraan:</strong><br>
                {{ $workOrder->vehicle->plate_number }} · {{ $workOrder->vehicle->brand }} {{ $workOrder->vehicle->model }}<br>
                <strong>No. Invoice:</strong> {{ $workOrder->invoice_number }}<br>
                <strong>Tanggal:</strong> {{ $workOrder->created_at->format('d/m/Y H:i') }}
            </td>
        </tr>
    </table>

    <br>
    <table class="items">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="45%">Nama Item</th>
                <th width="10%">Qty</th>
                <th width="20%">Harga</th>
                <th width="20%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($workOrder->items as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->qty }}</td>
                <td class="right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td>Subtotal</td>
            <td class="right">Rp {{ number_format($workOrder->subtotal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Diskon</td>
            <td class="right">Rp {{ number_format($workOrder->discount, 0, ',', '.') }}</td>
        </tr>
        <tr class="grand">
            <td>TOTAL</td>
            <td class="right">Rp {{ number_format($workOrder->total, 0, ',', '.') }}</td>
        </tr>
    </table>

    <table class="sign">
        <tr>
            <td>Hormat kami,<div class="space"></div>( ........................ )</td>
            <td>Pelanggan,<div class="space"></div>( {{ $workOrder->customer->name }} )</td>
        </tr>
    </table>

    <div class="footer">Terima kasih telah berkunjung! Selamat berkendara dengan aman 🏍️</div>
</body>

</html>