document.addEventListener("DOMContentLoaded", async () => {
  const fetchJSON = async (url) => {
    const res = await fetch(url);
    if (!res.ok) throw new Error(`${res.status} ${url}`);
    return res.json();
  };

  // Biar chart ga numpuk kalau di-render ulang
  const charts = {};

  // Helper: format durasi (kalau datamu menit, hasilnya jadi "1j 20m")
    const formatDuration = (value) => {
    const h = Number(value) || 0;
    return `${h.toFixed(2)} h`;
  };

//helper tanggal ke hari 
  const LABELS_HARI = ["Sen", "Sel", "Rab", "Kam", "Jum", "Sab", "Min"];

  const getDayIndexSenin0 = (yyyy_mm_dd) => {
    const [y, m, d] = String(yyyy_mm_dd).split("-").map(Number);
    const date = new Date(y, m - 1, d);  // local time
    const jsDay = date.getDay();         // 0=Min ... 6=Sab
    return (jsDay + 6) % 7;              // 0=Sen ... 6=Min
  };

  const buildWeekSeries = (data) => {
    const values = Array(7).fill(0);

    for (const x of data) {
      const idx = getDayIndexSenin0(x.hari); // 🔥 ganti kalau field-nya bukan "tanggal"
      values[idx] += Number(x.total_durasi) || 0; // total_durasi sudah JAM
    }

    return { labels: LABELS_HARI, values };
  };



  // Helper: style global Chart.js
  Chart.defaults.font.family = "Poppins, Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial";
  Chart.defaults.color = "#475569"; // slate-600

  const baseOptions = {
    responsive: true,
    maintainAspectRatio: false,
    interaction: { mode: "index", intersect: false },
    plugins: {
      legend: {
        display: true,
        labels: { usePointStyle: true, boxWidth: 8, boxHeight: 8 },
      },
      tooltip: {
        backgroundColor: "rgba(15, 23, 42, 0.92)", // slate-900
        padding: 12,
        cornerRadius: 10,
        titleColor: "#E2E8F0",
        bodyColor: "#E2E8F0",
        displayColors: false,
        callbacks: {
          label: (ctx) => `${ctx.dataset.label}: ${formatDuration(ctx.parsed.y)}`,
        },
      },
    },
    scales: {
      x: {
        grid: { display: false },
        ticks: { maxRotation: 0, autoSkip: true },
      },
      y: {
        beginAtZero: true,
        grid: { color: "rgba(148, 163, 184, 0.25)" }, // slate-400 tipis
        ticks: {
          callback: (val) => formatDuration(val),
        },
      },
    },
  };

  // ========== Chart 1 (Line) ==========
  try {
    const data = await fetchJSON("/api/chart/haribelajar.php");
    const el = document.getElementById("chartMingguan");
    if (!el) return console.error("canvas #chartMingguan tidak ada");

    // destroy kalau sudah ada
    if (charts.chartMingguan) charts.chartMingguan.destroy();

    const { labels, values } = buildWeekSeries(data);


    // gradient fill
    const ctx = el.getContext("2d");
    const gradient = ctx.createLinearGradient(0, 0, 0, el.height || 300);
    gradient.addColorStop(0, "rgba(37, 99, 235, 0.28)"); // blue-600 tipis
    gradient.addColorStop(1, "rgba(37, 99, 235, 0.02)");

    charts.chartMingguan = new Chart(el, {
      type: "line",
      data: {
        labels,
        datasets: [
          {
            label: "Durasi Belajar",
            data: values,
            borderColor: "#2563EB",
            backgroundColor: gradient,
            fill: true,
            tension: 0.4,          // bikin smooth
            borderWidth: 3,
            pointRadius: 4,
            pointHoverRadius: 6,
            pointBackgroundColor: "#FFFFFF",
            pointBorderColor: "#2563EB",
            pointBorderWidth: 2,
          },
        ],
      },
      options: {
        ...baseOptions,
        plugins: {
          ...baseOptions.plugins,
          tooltip: {
            ...baseOptions.plugins.tooltip,
            callbacks: {
              label: (ctx) => `Durasi: ${formatDuration(ctx.parsed.y)}`,
            },
          },
        },
      },
    });
  } catch (e) {
    console.error(e);
  }

  // ========== Chart 2 (Bar) ==========
  try {
    const data = await fetchJSON("/api/chart/matakuliah.php");
    const el = document.getElementById("materiChart");
    if (!el) return console.error("canvas #materiChart tidak ada");

    if (charts.materiChart) charts.materiChart.destroy();

    const labels = data.map((x) => x.nama_materi);
    const values = data.map((x) => Number(x.total_durasi) || 0);

    charts.materiChart = new Chart(el, {
      type: "bar",
      data: {
        labels,
        datasets: [
          {
            label: "Durasi per Materi",
            data: values,
            backgroundColor: "rgba(139, 92, 246, 0.85)", // ungu modern
            hoverBackgroundColor: "rgba(139, 92, 246, 1)",
            borderRadius: 12,
            borderSkipped: false,
            maxBarThickness: 42,
          },
        ],
      },
      options: {
        ...baseOptions,
        scales: {
          ...baseOptions.scales,
          x: {
            ...baseOptions.scales.x,
            ticks: { autoSkip: true, maxRotation: 0 },
          },
          y: {
            ...baseOptions.scales.y,
            ticks: {
              callback: (val) => formatDuration(val),
            },
          },
        },
      },
    });
  } catch (e) {
    console.error(e);
  }
});

const data = fetchJSON("/api/chart/haribelajar.php");
console.log("DATA API:", data);
