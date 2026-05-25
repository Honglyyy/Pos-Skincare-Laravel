<!DOCTYPE html>
<html>
<head>
    <title>Receipt</title>

    <style>
        /* ===== THERMAL PAPER SIZE ===== */
        @page {
            size:80mm;
            margin: 0;
        }

        body {
            width: 80mm;
            font-family: monospace;
            font-size: 11px;
            margin: 0;
            padding: 5px;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        /* HEADER */
        .header {
            text-align: center;
        }

        .logo {
            width: 80px;
            margin-bottom: 5px;
        }

        /* TABLE STYLE (THERMAL FRIENDLY) */
        table {
            width: 100%;
            font-size: 11px;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            border-bottom: 1px dashed #000;
            padding-bottom: 3px;
        }

        td {
            padding: 2px 0;
        }

        .right {
            text-align: right;
        }

        /* TOTAL SECTION */
        .total-row {
            display: flex;
            justify-content: space-between;
        }

        .footer {
            text-align: center;
            margin-top: 8px;
        }
    </style>
</head>

<body onload="window.print()">

<!-- HEADER -->
<div class="header">
    <img src="{{ asset('image/logo-nobg.png') }}" class="logo">

    <div class="bold">Ly's Skincare</div>
    <div>Invoice #{{ $order->id }}</div>
    <div>{{ now()->format('d/m/Y H:i') }}</div>
    <div>Status: {{ $order->payment_status }}</div>
    <div>Cashier: {{ $order->created_by }}</div>
</div>

<div class="line"></div>

<!-- ITEMS -->
<table>
    <thead>
        <tr>
            <th>Item</th>
            <th>Qty</th>
            <th class="right">Price</th>
            <th class="right">Total</th>
        </tr>
    </thead>

    <tbody>
        @foreach($order->orderDetails as $item)
        <tr>
            <td>{{ $item->product->name }}</td>
            <td>{{ $item->quantity }}</td>
            <td class="right">{{ $item->product->price }}</td>
            <td class="right">{{ $item->subtotal }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="line"></div>

<!-- TOTALS -->
<div class="total-row">
    <div>Total</div>
    <div>{{ $order->total_price }}</div>
</div>

<div class="total-row">
    <div>Discount</div>
    <div>{{ $order->discount }}%</div>
</div>

<div class="total-row bold">
    <div>Final</div>
    <div>{{ $order->total_payment }}</div>
</div>

<div class="line"></div>

<!-- FOOTER -->
<div class="footer">
    Thank you for your purchase!
</div>

</body>
</html>