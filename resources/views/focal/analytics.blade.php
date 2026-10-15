<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Analytics - FYPMS</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
.main{margin-left:260px;padding:28px 36px 40px}
.topbar{background:#0C447C;border-radius:14px;padding:20px 26px;margin-bottom:26px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 1px 12px rgba(12,68,124,.15)}
.topbar h2{color:#fff;font-size:19px;font-weight:700}
.topbar p{color:#c9dbee;font-size:12px;margin-top:3px}
.stat-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:18px;margin-bottom:26px}
.stat-card{background:#fff;border-radius:14px;padding:22px 24px;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9;position:relative;overflow:hidden;display:flex;align-items:flex-start;gap:16px}
.stat-card::before{content:'';position:absolute;top:0;left:0;width:4px;height:100%;background:linear-gradient(180deg,#0C447C,#3b82f6)}
.stat-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.stat-icon svg{width:22px;height:22px}
.stat-icon.blue{background:#e8f0f9}
.stat-icon.green{background:#dcfce7}
.stat-icon.purple{background:#ede9fe}
.stat-icon.orange{background:#ffedd5}
.stat-body{flex:1;min-width:0}
.stat-label{font-size:11px;color:#8096b0;font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px}
.stat-value{font-size:32px;font-weight:700;color:#0C447C;opacity:0;transform:translateY(8px);transition:all .6s ease}
.stat-value.show{opacity:1;transform:translateY(0)}
.stat-sub{font-size:12px;color:#5a7a9a;margin-top:4px}
.grid2{display:grid;grid-template-columns:1.2fr 1fr;gap:22px;margin-bottom:22px}
.card{background:#fff;border-radius:14px;padding:24px 26px 22px;box-shadow:0 1px 14px rgba(12,68,124,.08);border:1px solid #e8f0f9}
.card-title{color:#0C447C;font-size:14.5px;font-weight:700;margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid #f0f4f8;display:flex;align-items:center;gap:10px}
.card-icon{width:32px;height:32px;border-radius:8px;background:#0C447C;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.card-icon svg{width:16px;height:16px}
.chart-wrap{position:relative;height:320px}
.empty{text-align:center;color:#8096b0;padding:60px 0;font-size:13px}
.loading-shimmer{height:100%;background:linear-gradient(90deg,#f0f4f8 25%,#e8f0f9 50%,#f0f4f8 75%);background-size:200% 100%;animation:shimmer 1.5s infinite;border-radius:8px}
@keyframes shimmer{0%{background-position:200% 0}100%{background-position:-200% 0}}
@media(max-width:900px){.grid2{grid-template-columns:1fr}}
@media(max-width:768px){.main{margin-left:0;padding:16px}}
</style>
</head>
<body>
@include('focal.partials.sidebar')
<div class="main">
  <div class="topbar">
    <div>
      <h2>Analytics Overview</h2>
      <p>Department-wide insights across groups, defences, and evaluation phases</p>
    </div>
  </div>

  <div class="stat-grid" id="stat-grid" style="opacity:.4">
    <div class="stat-card">
      <div class="stat-icon blue"><svg viewBox="0 0 24 24" fill="none" stroke="#0C447C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
      <div class="stat-body"><div class="stat-label">Total Groups</div><div class="stat-value" id="stat-groups">0</div></div>
    </div>
    <div class="stat-card">
      <div class="stat-icon purple"><svg viewBox="0 0 24 24" fill="none" stroke="#6d28d9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6"/><path d="M9 15l2 2 4-4"/></svg></div>
      <div class="stat-body"><div class="stat-label">Total Defences</div><div class="stat-value" id="stat-defences">0</div></div>
    </div>
    <div class="stat-card">
      <div class="stat-icon green"><svg viewBox="0 0 24 24" fill="none" stroke="#166534" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20V10"/><path d="M18 20V4"/><path d="M6 20v-4"/></svg></div>
      <div class="stat-body"><div class="stat-label">Overall Pass Rate</div><div class="stat-value" id="stat-passrate">0%</div></div>
    </div>
    <div class="stat-card">
      <div class="stat-icon orange"><svg viewBox="0 0 24 24" fill="none" stroke="#c2410c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 16h5v5"/></svg></div>
      <div class="stat-body"><div class="stat-label">Re-Attempts</div><div class="stat-value" id="stat-reattempts">0</div></div>
    </div>
  </div>

  <div class="grid2">
    <div class="card">
      <div class="card-title"><span class="card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></span>Defence Outcomes</div>
      <div class="chart-wrap"><canvas id="passFailChart"></canvas></div>
    </div>
    <div class="card">
      <div class="card-title"><span class="card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg></span>Group Status Distribution</div>
      <div class="chart-wrap"><canvas id="groupStatusChart"></canvas></div>
    </div>
  </div>

  <div class="grid2">
    <div class="card">
      <div class="card-title"><span class="card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M7 12l4-4 4 4 5-5"/></svg></span>Average Marks by Evaluation Phase</div>
      <div class="chart-wrap"><canvas id="phaseChart"></canvas></div>
    </div>
    <div class="card">
      <div class="card-title"><span class="card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg></span>Groups by Academic Session</div>
      <div class="chart-wrap"><canvas id="sessionChart"></canvas></div>
    </div>
  </div>
</div>

<script>
const token = localStorage.getItem('api_token');
const palette = ['#0C447C','#3b82f6','#60a5fa','#93c5fd','#1e3a5f','#7aa6d6'];

function animateNumber(el, end, suffix='') {
    let start = 0;
    const duration = 900;
    const startTime = performance.now();
    el.classList.add('show');
    function step(now) {
        const progress = Math.min((now - startTime) / duration, 1);
        const value = Math.floor(progress * end);
        el.textContent = value + suffix;
        if (progress < 1) requestAnimationFrame(step);
        else el.textContent = end + suffix;
    }
    requestAnimationFrame(step);
}

async function loadAnalytics() {
    try {
        const res = await fetch('/api/focal/analytics', { headers: { 'Authorization': 'Bearer ' + token } });
        const data = await res.json();

        document.getElementById('stat-grid').style.opacity = '1';
        document.getElementById('stat-grid').style.transition = 'opacity .3s ease';

        animateNumber(document.getElementById('stat-groups'), data.total_groups);
        animateNumber(document.getElementById('stat-defences'), data.total_defences);
        const totalDecided = data.pass_fail.pass + data.pass_fail.fail;
        const passRate = totalDecided > 0 ? Math.round((data.pass_fail.pass / totalDecided) * 100) : 0;
        animateNumber(document.getElementById('stat-passrate'), passRate, '%');
        animateNumber(document.getElementById('stat-reattempts'), data.reattempts);

        const pfTotal = data.pass_fail.pass + data.pass_fail.fail + data.pass_fail.pending;


        if (pfTotal === 0) {


            document.getElementById('passFailChart').closest('.chart-wrap').innerHTML = '<div class="empty">No defence outcomes recorded yet.</div>';


        } else {


        new Chart(document.getElementById('passFailChart'), {
            type: 'doughnut',
            data: {
                labels: ['Pass', 'Fail', 'Pending'],
                datasets: [{ data: [data.pass_fail.pass, data.pass_fail.fail, data.pass_fail.pending],
                    backgroundColor: ['#0C447C','#dc2626','#cbd5e1'], borderWidth: 0 }]
            },
            options: { responsive: true, maintainAspectRatio: false, animation: { duration: 1200, easing: 'easeOutQuart' },
                plugins: { legend: { position: 'bottom', labels: { font: { family: 'Segoe UI', size: 12 } } } } }
        });


        }

        new Chart(document.getElementById('groupStatusChart'), {
            type: 'bar',
            data: {
                labels: data.group_status.map(g => g.status),
                datasets: [{ data: data.group_status.map(g => g.count), backgroundColor: '#3b82f6', borderRadius: 6, maxBarThickness: 46 }]
            },
            options: { responsive: true, maintainAspectRatio: false, animation: { duration: 1200, easing: 'easeOutQuart' },
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
        });

        new Chart(document.getElementById('phaseChart'), {
            type: 'bar',
            data: {
                labels: data.phase_averages.map(p => p.phase),
                datasets: [{ label: 'Average %', data: data.phase_averages.map(p => p.average_percentage),
                    backgroundColor: '#0C447C', borderRadius: 6, maxBarThickness: 46 }]
            },
            options: { responsive: true, maintainAspectRatio: false, animation: { duration: 1200, easing: 'easeOutQuart' },
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, max: 100 } } }
        });

        if (!data.session_counts || data.session_counts.length === 0 || data.session_counts.every(s => s.groups === 0)) {


            document.getElementById('sessionChart').closest('.chart-wrap').innerHTML = '<div class="empty">No session data available yet.</div>';


        } else {


        new Chart(document.getElementById('sessionChart'), {
            type: 'pie',
            data: {
                labels: data.session_counts.map(s => s.session),
                datasets: [{ data: data.session_counts.map(s => s.groups), backgroundColor: palette, borderWidth: 0 }]
            },
            options: { responsive: true, maintainAspectRatio: false, animation: { duration: 1200, easing: 'easeOutQuart' },
                plugins: { legend: { position: 'bottom', labels: { font: { family: 'Segoe UI', size: 12 } } } } }
        });


        }

    } catch(e) {
        console.error(e);
    }
}
loadAnalytics();
</script>
</body>
</html>
