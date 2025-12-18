function reveal(id){
  const el = document.getElementById(id);
  if (!el) return;

  requestAnimationFrame(() => {
    el.classList.remove('opacity-0', 'translate-y-3');
    el.classList.add('opacity-100', 'translate-y-0');
  });
}

function initScrollReveal() {
  const elements = document.querySelectorAll('.reveal-on-scroll');

  const observer = new IntersectionObserver(
    (entries, obs) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.remove('opacity-0', 'translate-y-6');
          entry.target.classList.add('opacity-100', 'translate-y-0');
          obs.unobserve(entry.target); // animate sekali aja
        }
      });
    },
    {
      threshold: 0.15, // 15% masuk viewport
      rootMargin: '0px 0px -10px 0px' // sedikit offset bawah
    }
  );

  elements.forEach(el => observer.observe(el));
}


async function loadDashboardCharts() {
  const res = await fetch('/chart/dashboardAdmin.php');
  const data = await res.json();

  // Chart 1
  new Chart(document.getElementById('chartAktivitas'), {
    type: 'line',
    data: data.aktivitas_14_hari,
    options: {
      responsive: true,
      interaction: { mode: 'index', intersect: false },
      plugins: { legend: { position: 'top' } }
    }
  });

  reveal('cardAktivitas'); 

  // Chart 2
  new Chart(document.getElementById('chartsesiStatus'), {
    type: 'doughnut',
    data: data.sesiStatus,
    options: {
      responsive: true,
      maintainAspectRatio: false,
      animation: {            
        duration: 900,
        easing: 'easeOutQuart'
      },
      plugins: { legend: { position: 'top' } }
    }
  });

  reveal('cardSesiStatus');
}

document.addEventListener('DOMContentLoaded', () => {
  initScrollReveal();
  loadDashboardCharts(); // chart kamu
});
