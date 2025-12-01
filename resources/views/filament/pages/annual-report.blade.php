<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Laporan Tahunan' }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background: #f0f0f0; }
        .info-box { margin: 10px 0; padding: 8px; background: #f8f9fa; border-left: 4px solid #007bff; }
    </style>
</head>
<body>
    <h1>{{ $title ?? 'Laporan Tahunan' }} {{ $year }}</h1>

    @if(isset($selectedMonths) && count($selectedMonths) > 0 && count($selectedMonths) < 12)
    <div class="info-box">
        <strong>Periode Laporan:</strong> 
        @php
            $monthNames = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            $firstMonth = $monthNames[min($selectedMonths)];
            $lastMonth = $monthNames[max($selectedMonths)];
        @endphp
        {{ $firstMonth }} - {{ $lastMonth }} {{ $year }}
    </div>
    @endif

    <p><strong>Total Pendapatan:</strong> Rp {{ number_format($data['total_revenue'], 0, ',', '.') }}</p>
    <p><strong>Total Customer:</strong> {{ $data['total_customers'] }}</p>
    <p><strong>Tahun:</strong> {{ $year }}</p>

    <h3>Pendapatan per Bulan</h3>
    <table>
        <tr><th>Bulan</th><th>Total Pendapatan</th></tr>
        @foreach ($data['monthly_revenue'] as $row)
            @php
                $monthNames = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                ];
                $monthName = $monthNames[$row->month] ?? 'Bulan ' . $row->month;
            @endphp
            <tr>
                <td>{{ $monthName }}</td>
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