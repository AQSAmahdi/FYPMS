<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Dashboard - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
@keyframes borderFlow{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
.hamburger{display:none;position:fixed;top:12px;left:12px;z-index:1100;background:#0C447C;border:none;border-radius:8px;padding:8px 9px;cursor:pointer;flex-direction:column;gap:5px}
.hamburger span{display:block;width:18px;height:2px;background:#fff;border-radius:2px;transition:all .3s}
.hamburger.open span:nth-child(1){transform:translateY(7px) rotate(45deg)}
.hamburger.open span:nth-child(2){opacity:0}
.hamburger.open span:nth-child(3){transform:translateY(-7px) rotate(-45deg)}
.overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998}
.overlay.show{display:block}
.main{margin-left:260px;padding:24px 28px;min-height:100vh}
.topbar{background:#0C447C;border-radius:14px;padding:20px 26px;margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 4px 14px rgba(12,68,124,.18)}
.topbar h2{color:#fff!important}
.topbar p{color:rgba(255,255,255,.85)!important}
.topbar .badge-role{background:rgba(255,255,255,.18)!important;color:#fff!important}
.topbar h2{color:#0C447C;font-size:18px;font-weight:700}
.topbar p{color:#8096b0;font-size:12px;margin-top:2px}
.badge-role{background:#e8f0f9;color:#0C447C;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600}
.cards{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:20px}
.card-wrap{padding:2px;border-radius:14px;background:linear-gradient(270deg,#0C447C,#1a6bbf,#38bdf8,#0C447C);background-size:300% 300%;animation:borderFlow 2.5s ease infinite}
.card{background:#fff;border-radius:12px;padding:22px 18px;text-align:center}
.card-icon{width:40px;height:40px;background:#e8f0f9;border-radius:10px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px}
.card-icon svg{width:19px;height:19px;stroke:#0C447C;fill:none}
.card h3{color:#8096b0;font-size:11px;font-weight:500;margin-bottom:7px;text-transform:uppercase;letter-spacing:.5px}
.card .number{font-size:34px;font-weight:700;color:#0C447C;line-height:1}
.card p{color:#b0c4d8;font-size:11px;margin-top:4px}
.card-wrap{cursor:pointer;transition:transform .2s,box-shadow .2s}
.card-wrap:hover{transform:translateY(-3px);box-shadow:0 8px 24px rgba(12,68,124,.15)}
.quick-card{background:#fff;border-radius:12px;padding:20px;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9}
.section-title{color:#0C447C;font-size:14px;font-weight:700;margin-bottom:14px}
.actions-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}
.action-wrap{padding:2px;border-radius:12px;background:linear-gradient(270deg,#0C447C,#1a6bbf,#38bdf8,#0C447C);background-size:300% 300%;animation:borderFlow 2.5s ease infinite}
.action-btn{display:flex;align-items:center;gap:12px;padding:16px 18px;background:#fff;border-radius:10px;text-decoration:none;transition:background .2s}
.action-btn:hover{background:#f4f8fd}
.action-icon{width:38px;height:38px;background:#e8f0f9;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:background .2s}
.action-icon svg{width:18px;height:18px;stroke:#0C447C;fill:none}
.action-btn:hover .action-icon{background:#0C447C}
.action-btn:hover .action-icon svg{stroke:#fff}
.action-btn div p{font-size:13px;font-weight:600;color:#0C447C}
.action-btn div span{font-size:11px;color:#8096b0;margin-top:2px;display:block}
@media(max-width:900px){.cards{grid-template-columns:repeat(2,1fr)}}
@media(max-width:768px){
  .hamburger{display:flex}
  .main{margin-left:0;padding:14px;padding-top:56px}
  .cards{grid-template-columns:1fr}
  .actions-grid{grid-template-columns:1fr}
  .topbar{flex-direction:column;align-items:flex-start;gap:8px}
}
</style>
<link rel="stylesheet" href="/css/transitions.css">
</head>
<body>
<button class="hamburger" id="hamburger" onclick="toggleSidebar()">
  <span></span><span></span><span></span>
</button>
<div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

@include('focal.partials.sidebar')

<div class="main">
  <div class="topbar">
    <div>
      <h2>Dashboard</h2>
      <p>Welcome back, {{ auth()->user()->name }}!</p>
    </div>
    <span class="badge-role">Focal Person</span>
  </div>

  <div class="cards">
    <div class="card-wrap" onclick="window.location='/focal/students'" title="View All Students" style="cursor:pointer">
      <div class="card">
        <div class="card-icon"><svg viewBox="0 0 24 24" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
        <h3>Total Students</h3>
        <div class="number" id="stat-students">-</div>
        <p>Registered students</p>
      </div>
    </div>
    <div class="card-wrap" onclick="window.location='/focal/faculty-members'" title="View All Faculty" style="cursor:pointer">
      <div class="card">
        <div class="card-icon"><svg viewBox="0 0 24 24" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/></svg></div>
        <h3>Total Faculty</h3>
        <div class="number" id="stat-supervisors">-</div>
        <p>Registered faculty</p>
      </div>
    </div>
    <div class="card-wrap" onclick="window.location='/focal/groups'" title="View FYP Groups" style="cursor:pointer">
      <div class="card">
        <div class="card-icon"><svg viewBox="0 0 24 24" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></div>
        <h3>FYP Groups</h3>
        <div class="number" id="stat-groups">-</div>
        <p>Total groups</p>
      </div>
    </div>
  </div>

  <div class="quick-card">
    <div class="section-title">Quick Actions</div>
        <div class="actions-grid">
      <div class="action-wrap">
        <a href="/focal/students/register" class="action-btn">
          <div class="action-icon"><svg viewBox="0 0 24 24" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg></div>
          <div><p>Register Student</p><span>Add new student to system</span></div>
        </a>
      </div>
      <div class="action-wrap">
        <a href="/focal/faculty-members/register" class="action-btn">
          <div class="action-icon"><svg viewBox="0 0 24 24" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/></svg></div>
          <div><p>Register Faculty</p><span>Add new faculty</span></div>
        </a>
      </div>
      <div class="action-wrap">
        <a href="/focal/analytics" class="action-btn">
          <div class="action-icon"><svg viewBox="0 0 24 24" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></div>
          <div><p>Analytics</p><span>View performance charts & reports</span></div>
        </a>
      </div>
      <div class="action-wrap">
        <a href="/focal/settings" class="action-btn">
          <div class="action-icon"><svg viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg></div>
          <div><p>Settings</p><span>Manage group formation period</span></div>
        </a>
      </div>
    </div>
  </div>
</div>
</div>

<script>
const token = localStorage.getItem('api_token');

async function loadDashboard() {
    const res  = await fetch('/api/focal/dashboard', {
        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        }
    });
    const data = await res.json();

    if (data.success) {
        document.getElementById('stat-students').textContent    = data.stats.total_students;
        document.getElementById('stat-supervisors').textContent = data.stats.total_faculty_members;
        document.getElementById('stat-groups').textContent      = data.stats.total_groups;
    }
}

function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('hamburger').classList.toggle('open');
    document.getElementById('overlay').classList.toggle('show');
}

loadDashboard();
</script>
</body>
</html>