<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Group Tracking - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
.main{margin-left:260px;padding:28px 36px 40px}
.topbar{background:#0C447C;border-radius:14px;padding:20px 26px;margin-bottom:24px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 1px 12px rgba(12,68,124,.15)}
.topbar h2{color:#fff;font-size:19px;font-weight:700;display:flex;align-items:center;gap:10px}
.topbar-icon{width:36px;height:36px;border-radius:10px;background:rgba(255,255,255,.15);display:inline-flex;align-items:center;justify-content:center;flex-shrink:0}
.topbar-icon svg{width:18px;height:18px}
.card{background:#fff;border-radius:14px;padding:24px 26px;box-shadow:0 1px 14px rgba(12,68,124,.08);border:1px solid #e8f0f9}
.search-wrap{position:relative;margin-bottom:20px}
.search-wrap svg{position:absolute;left:14px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:#9aabbf;pointer-events:none}
.search-bar{width:100%;padding:11px 16px 11px 40px;border:1.5px solid #e8f0f9;border-radius:9px;font-size:13px;outline:none;font-family:'Segoe UI',sans-serif;transition:border-color .15s}
.search-bar:focus{border-color:#0C447C}
.group-row{display:flex;justify-content:space-between;align-items:center;padding:16px 4px;border-bottom:1px solid #f0f4f8;transition:background .15s;border-radius:8px}
.group-row:last-child{border-bottom:none}
.group-row:hover{background:#f9fbfd}
.group-code{font-weight:700;color:#0C447C;font-size:15px}
.group-meta{font-size:12px;color:#8096b0;margin-top:3px}
.badge{padding:4px 13px;border-radius:20px;font-size:11.5px;font-weight:600;white-space:nowrap}
.badge-open{background:#fef9c3;color:#854d0e}
.badge-supervisor_assigned{background:#dcfce7;color:#166534}
.btn-view{background:#0C447C;color:#fff;border:none;padding:9px 18px;border-radius:8px;font-size:12.5px;font-weight:600;cursor:pointer;text-decoration:none;transition:background .2s;display:inline-block}
.btn-view:hover{background:#185FA5}
.empty{text-align:center;color:#8096b0;padding:32px;font-size:13px}
@media(max-width:768px){.main{margin-left:0;padding:16px}.group-row{flex-direction:column;gap:10px;align-items:flex-start}}
</style>
</head>
<body>
@include('focal.partials.sidebar')
<div class="main">
  <div class="topbar">
    <div><h2><span class="topbar-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg></span>Group Tracking</h2><p style="color:#c9dbee;font-size:12px;margin-top:3px;margin-left:46px">View complete FYP history of all groups</p></div>
    <span style="background:rgba(255,255,255,.15);color:#fff;padding:6px 16px;border-radius:20px;font-size:12px;font-weight:600">Total: <span id="total-count">0</span></span>
  </div>
  <div class="card">
    <div class="search-wrap">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
      <input class="search-bar" type="text" id="search" placeholder="Search by group code or project title...">
    </div>
    <div id="groups-list"><div class="empty">Loading groups...</div></div>
  </div>
</div>

<script>
const token = localStorage.getItem('api_token');
let allGroups = [];

async function loadGroups() {
    try {
        const res  = await fetch('/api/focal/group-tracking', {
            headers: { 'Authorization': 'Bearer ' + token }
        });
        const data = await res.json();
        allGroups  = data.groups || [];
        document.getElementById('total-count').textContent = allGroups.length;
        renderGroups(allGroups);
    } catch(e) {
        document.getElementById('groups-list').innerHTML = '<div class="empty">Error loading groups</div>';
    }
}

function renderGroups(groups) {
    const container = document.getElementById('groups-list');
    if (!groups.length) { container.innerHTML = '<div class="empty">No groups found</div>'; return; }
    container.innerHTML = groups.map(g => `
        <div class="group-row">
            <div>
                <div class="group-code">${g.group_code}</div>
                <div class="group-meta">
                    ${g.project_title} &bull;
                    Supervisor: ${g.supervisor} &bull;
                    Members: ${g.members_count}
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:12px">
                <span class="badge badge-${g.status}">${g.status === 'supervisor_assigned' ? 'Assigned' : 'Open'}</span>
                <a href="/focal/group-tracking/${g.id}" class="btn-view">View History</a>
            </div>
        </div>`).join('');
}

document.getElementById('search').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    renderGroups(allGroups.filter(g =>
        g.group_code.toLowerCase().includes(q) ||
        g.project_title.toLowerCase().includes(q)
    ));
});

loadGroups();
</script>
</body>
</html>
