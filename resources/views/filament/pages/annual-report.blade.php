<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Tahunan {{ $year }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h1>Laporan Tahunan {{ $year }}</h1>

    <p><strong>Total Pendapatan:</strong> Rp {{ number_format($data['total_revenue'], 0, ',', '.') }}</p>
    <p><strong>Total Customer:</strong> {{ $data['total_customers'] }}</p>
    <p><strong>Tahun:</strong> {{ $year }}</p>

    <h3>Pendapatan per Bulan</h3>
    <table>
        <tr><th>Bulan</th><th>Total Pendapatan</th></tr>
        @foreach ($data['monthly_revenue'] as $row)
            <tr>
                <td>{{ \Carbon\Carbon::create()->month($row->month)->translatedFormat('F') }}</td>
                <td>Rp {{ number_format($row->total, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </table>

    <h3>Produk Terjual</h3>
    <table>
        <tr><th>Produk</th><th>Total Terjual</th></tr>
        @foreach ($data['sold_products'] as $prod)
            <tr>
                <td>{{ $prod->name }}</td>
                <td>{{ $prod->total_sold }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>
