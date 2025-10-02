<x-filament-panels::page>
    <canvas id="myChart"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('myChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ],
                datasets: [{
                    label: 'Sales',
                    data: [120, 150, 180, 220],
                    borderWidth: 1
                }]
            },
        });
    </script>
</x-filament-panels::page>
