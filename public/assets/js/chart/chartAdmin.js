async function loadDashboardCharts() {
    const res = await fetch("/api/chart/chartDashboardAdmin.php");
    if (!res.ok) {
        console.error("Gagal fetch chart data");
        return;
    }

    const data = await res.json();

    // LINE CHART
    const ctx1 = document.getElementById("chartAktivitas");
    if (ctx1 && data.aktivitas_14_hari) {
        new Chart(ctx1, {
            type: "line",
            data: data.aktivitas_14_hari,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                elements: {
                    line: { tension: 0.4 }
                },
                plugins: {
                    legend: { position: "top" }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // DOUGHNUT CHART
    const ctx2 = document.getElementById("chartsesiStatus");
    if (ctx2 && data.sesiStatus) {
        new Chart(ctx2, {
            type: "doughnut",
            data: data.sesiStatus,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: "65%",
                plugins: {
                    legend: { position: "bottom" }
                }
            }
        });
    }
}

document.addEventListener("DOMContentLoaded", loadDashboardCharts);
