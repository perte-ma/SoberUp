let serataChartInstance = null;

function getThemeColor(varName) {
    return getComputedStyle(document.documentElement).getPropertyValue(varName).trim();
}

function renderSerataChart(serataId, canvasId = 'serataChart') {
    fetch(`utils/chart.php?idserata=${serataId}`)
        .then(res => res.json())
        .then(points => {
            const canvas = document.getElementById(canvasId);

            if (points.length === 0) {
                canvas.parentElement.innerHTML = '<p class="text-muted">No data available for this session.</p>';
                return;
            }

            const primaryColor = getThemeColor('--color-primary');
            const textColor = getThemeColor('--text-secondary');
            const gridColor = getThemeColor('--bg-surface');

            if (serataChartInstance) {
                serataChartInstance.destroy();
            }

            serataChartInstance = new Chart(canvas, {
                type: 'line',
                data: {
                    labels: points.map(p => p.time),
                    datasets: [{
                        label: 'Blood Alcohol Content (g/L)',
                        data: points.map(p => p.bac),
                        borderColor: primaryColor,
                        backgroundColor: primaryColor + '1A', // colore + trasparenza ~10%
                        tension: 0.3,
                        fill: true,
                        pointRadius: 2
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'g/L', color: textColor },
                            ticks: { color: textColor },
                            grid: { color: gridColor }
                        },
                        x: {
                            title: { display: true, text: 'Time', color: textColor },
                            ticks: { color: textColor },
                            grid: { color: gridColor }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        })
        .catch(err => console.error('Error loading chart data:', err));
}