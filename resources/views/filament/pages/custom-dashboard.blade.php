<x-filament-panels::page>
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">Total Pendapatan</h3>
            <p class="text-2xl font-bold text-green-600">
                Rp {{ number_format($reportData['total_revenue'], 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">Total Customer</h3>
            <p class="text-2xl font-bold text-blue-600">
                {{ $reportData['total_customers'] }}
            </p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">Produk Terjual</h3>
            <p class="text-2xl font-bold text-purple-600">
                {{ array_sum(array_column($reportData['sold_products'], 'total_sold')) }}
            </p>
        </div>
    </div>

    {{-- Chart --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Grafik Pendapatan per Bulan</h3>
        <canvas id="myChart" height="100"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('myChart');

        // Convert PHP data to JavaScript for chart
        const monthlyData = @json($reportData['monthly_revenue']);
        
        // Create array with 12 months, fill with 0 if no data
        const monthlyRevenue = Array.from({ length: 12 }, (_, i) => {
            const monthData = monthlyData.find(item => item.month === i + 1);
            return monthData ? monthData.total : 0;
        });

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Total Pendapatan (Rp)',
                    data: monthlyRevenue,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => 'Rp ' + value.toLocaleString('id-ID')
                        }
                    }
                }
            }
        });
    </script>
</x-filament-panels::page>