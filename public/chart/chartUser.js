document.addEventListener("DOMContentLoaded", async () => {
  const fetchJSON = async (url) => {
    const res = await fetch(url);
    if (!res.ok) throw new Error(`${res.status} ${url}`);
    return res.json();
  };

  // Chart: Durasi Belajar Harian
  try {
    const data = await fetchJSON("/chart/haribelajar.php");
    const namaHari = ["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"];
    const labels = data.map(item => {
      const d = new Date(item.hari);
      return namaHari[d.getDay()] ?? item.hari;
    });
    const values = data.map(item => Number(item.total_durasi) || 0);
    new Chart(document.getElementById("chartMingguan"), {
      type: "line",
      data: {
        labels,
        datasets: [{
          label: "Durasi Belajar Harian",
          data: values,
          borderColor: "#4e73df",
          tension: 0.3
        }]
      },
      options: { responsive: true }
    });
  } catch (e) { console.error("haribelajar error:", e); }

  // Chart: Materi per Mata Kuliah
  try {
    const data = await fetchJSON("/chart/matakuliah.php");
    const labels = data.map(i => i.nama_materi);
    const values = data.map(i => Number(i.total_durasi) || 0);
    new Chart(document.getElementById("materiChart"), {
      type: "bar",
      data: {
        labels,
        datasets: [{
          label: "Total Durasi Belajar (menit)",
          data: values,
          backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
          'rgba(255, 159, 64, 0.2)',
          'rgba(255, 205, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(153, 102, 255, 0.2)',
          'rgba(201, 203, 207, 0.2)'
        ],
        borderColor: [
          'rgb(255, 99, 132)',
          'rgb(255, 159, 64)',
          'rgb(255, 205, 86)',
          'rgb(75, 192, 192)',
          'rgb(54, 162, 235)',
          'rgb(153, 102, 255)',
          'rgb(201, 203, 207)'
        ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
      }
    });
  } catch (e) { console.error("matakuliah error:", e); }

});

