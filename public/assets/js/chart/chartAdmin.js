async function loadDashboardCharts() {
  const res = await fetch("/api/chart/chartDashboardAdmin.php");
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  const data = await res.json();

  // line
  const c1 = document.getElementById("chartAktivitas");
  if (c1) {
    new Chart(c1, {
      type: "line",
      data: data.aktivitas_14_hari,
      options: { responsive: true }
    });
  }

  // doughnut
  const c2 = document.getElementById("chartsesiStatus");
  if (c2) {
    // warna biar jelas
    if (data.sesiStatus?.datasets?.[0] && !data.sesiStatus.datasets[0].backgroundColor) {
      data.sesiStatus.datasets[0].backgroundColor = ["rgba(255,99,132,0.35)", "rgba(75,192,192,0.35)"];
      data.sesiStatus.datasets[0].borderColor = ["rgb(255,99,132)", "rgb(75,192,192)"];
      data.sesiStatus.datasets[0].borderWidth = 1;
    }

    new Chart(c2, {
      type: "doughnut",
      data: data.sesiStatus,
      options: { responsive: true, maintainAspectRatio: false }
    });
  }
}

document.addEventListener("DOMContentLoaded", () => {
  loadDashboardCharts();
});
