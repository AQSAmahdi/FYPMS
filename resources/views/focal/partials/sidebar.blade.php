<link rel="stylesheet" href="/css/sidebar.css?v=9">
<style>
div.sidebar{height:100vh!important;overflow:hidden!important;min-height:0!important}
.sidebar-menu{overflow-y:auto!important;min-height:0!important;flex:1 1 auto!important}
.sidebar-logo img{animation:logoGentleSpin 10s linear infinite!important}
@keyframes logoGentleSpin{0%{transform:rotateY(0deg)}100%{transform:rotateY(360deg)}}
.sidebar-menu::-webkit-scrollbar{width:6px}
.sidebar-menu::-webkit-scrollbar-track{background:transparent}
.sidebar-menu::-webkit-scrollbar-thumb{background:rgba(255,255,255,.25);border-radius:10px}
.sidebar-menu::-webkit-scrollbar-thumb:hover{background:rgba(255,255,255,.4)}
.sidebar-menu{scrollbar-width:thin;scrollbar-color:rgba(255,255,255,.25) transparent}
</style>

<style>
.phase-block{}
.phase-header{display:flex;align-items:center;justify-content:space-between;padding:11px 16px;margin:2px 14px;border-radius:10px;cursor:pointer;color:rgba(255,255,255,.85);font-size:13.5px;font-weight:500;transition:background .2s ease}
.phase-header:hover{background:rgba(255,255,255,.1)}
.phase-header-left{display:flex;align-items:center;gap:11px;font-weight:600}
.phase-header-left svg{width:16px;height:16px;min-width:16px;stroke:rgba(255,255,255,.7);fill:none}
.phase-arrow{width:13px;height:13px;stroke:rgba(255,255,255,.4);fill:none;transition:transform .25s}
.phase-disabled{opacity:.45;cursor:default}
.phase-items{max-height:0;overflow:hidden;transition:max-height .3s ease}
.phase-block.open .phase-items{max-height:300px}
.phase-block.open .phase-arrow{transform:rotate(180deg)}
.phase-block.open .phase-header{background:rgba(255,255,255,.1);color:#fff}
.phase-block.open .phase-header-left svg{stroke:#fff}
.menu-item.sub{padding-left:38px}
.notif-panel{max-height:0;overflow:hidden;transition:max-height .3s ease;background:#082f57;border-bottom:1px solid rgba(255,255,255,.1)}
.notif-panel.open{max-height:320px}
.notif-panel-header{display:flex;justify-content:space-between;align-items:center;padding:10px 14px;border-bottom:1px solid rgba(255,255,255,.08)}
.notif-panel-header span{color:#fff;font-size:12px;font-weight:600}
.mark-all-btn{background:none;border:1px solid rgba(255,255,255,.25);color:rgba(255,255,255,.7);font-size:10px;padding:3px 8px;border-radius:5px;cursor:pointer;font-family:"Segoe UI",sans-serif}
.mark-all-btn:hover{background:rgba(255,255,255,.1);color:#fff}
.notif-list{max-height:260px;overflow-y:auto;scrollbar-width:none}
.notif-list::-webkit-scrollbar{display:none}
.notif-item{padding:10px 14px;border-bottom:1px solid rgba(255,255,255,.06);cursor:pointer;transition:background .2s}
.notif-item:hover{background:rgba(255,255,255,.06)}
.notif-item.unread{border-left:3px solid #3b82f6}
.notif-item.read{border-left:3px solid transparent;opacity:.7}
.notif-item-title{color:#fff;font-size:12px;font-weight:600;margin-bottom:3px}
.notif-item-msg{color:rgba(255,255,255,.6);font-size:11px;line-height:1.4}
.notif-item-time{color:rgba(255,255,255,.35);font-size:10px;margin-top:4px}
.notif-empty{color:rgba(255,255,255,.4);font-size:12px;text-align:center;padding:24px 0}
</style>
<button class="hamburger" id="hamburger" onclick="toggleSidebar()">
  <span></span><span></span><span></span>
</button>
<div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

<div class="sidebar" id="sidebar">
  <div class="sidebar-logo">
    <img src="/images/bgnu.jfif" alt="BGNU">
    <p>FYPMS</p>
    <span>Focal Person Panel</span>
  </div>

  <div class="sidebar-menu">
    <span class="menu-label">Main</span>
    <a href="/focal/dashboard" class="menu-item {{ request()->is('focal/dashboard') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
      Dashboard
    </a>
    @php $__pwResetPendingCount = \App\Models\PasswordResetRequest::where('status', 'pending')->count(); @endphp
    <a href="{{ route('focal.password-resets') }}" class="menu-item {{ request()->is('focal/password-reset-requests') ? 'active' : '' }}" style="display:flex;align-items:center">
      <svg viewBox="0 0 24 24" stroke-width="2" fill="none" stroke="currentColor"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
      Password Resets
      @if($__pwResetPendingCount > 0)
        <span class="sidebar-count-badge">{{ $__pwResetPendingCount }}</span>
      @endif
    </a>
    <span class="menu-label">Students</span>
    <a href="/focal/students/register" class="menu-item {{ request()->is('focal/students/register') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
      Register Student
    </a>
    <a href="/focal/students" class="menu-item {{ request()->is('focal/students') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      All Students
    </a>
    <span class="menu-label">Faculty</span>
    <a href="/focal/faculty-members/register" class="menu-item {{ request()->is('focal/faculty-members/register') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
      Register Faculty
    </a>
    <a href="/focal/faculty-members" class="menu-item {{ request()->is('focal/faculty-members') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      All Faculty
    </a>
    <span class="menu-label">FYP</span>
    <a href="/focal/groups" class="menu-item {{ request()->is('focal/groups') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      FYP Groups
    </a>
    <span class="menu-label">FYP Phases</span>

    <a href="/focal/defence/panels" class="menu-item {{ request()->is('focal/defence/panels') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      Create Panels
    </a>

    @php $phaseActive = request()->is('focal/phases*') || request()->is('focal/phase-schedules*') || request()->is('focal/marks*'); @endphp
    <div class="phase-block {{ $phaseActive ? 'open' : '' }}" id="phase2">
      <div class="phase-header" onclick="togglePhase('phase2')">
        <div class="phase-header-left">
          <svg viewBox="0 0 24 24" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
          <span>Phase Management</span>
        </div>
        <svg class="phase-arrow" viewBox="0 0 24 24" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
      <div class="phase-items">
        <a href="/focal/phases" class="menu-item sub {{ request()->is('focal/phases*') ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          Add Phase
        </a>
        <a href="/focal/phase-schedules" class="menu-item sub {{ request()->is('focal/phase-schedules*') ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          Assign Phase to Group
        </a>
        <a href="/focal/marks" class="menu-item sub {{ request()->is('focal/marks*') ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
          Manage Marks
        </a>
        <a href="/focal/phase-results" class="menu-item sub {{ request()->is('focal/phase-results*') ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          Phase Results
        </a>
        <a href="/focal/phase-documents" class="menu-item sub {{ request()->is('focal/phase-documents*') ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
          Phase Documents
        </a>
      </div>
    </div>

    <span class="menu-label">Tracking</span>
    <a href="/focal/analytics" class="menu-item {{ request()->is('focal/analytics*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        Analytics
    </a>
    <a href="/focal/group-tracking" class="menu-item {{ request()->is('focal/group-tracking*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" stroke-width="2"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
      Group Tracking
    </a>
    <a href="/focal/attendance-summary" class="menu-item {{ request()->is('focal/attendance-summary') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
      Attendance Summary
    </a>
    <span class="menu-label">Reports</span>
    <a href="/focal/reports/students" class="menu-item {{ request()->is('focal/reports/students') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      Students Report
    </a>
    <a href="/focal/reports/marks" class="menu-item {{ request()->is('focal/reports/marks') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
      Marks Report
    </a>
    <span class="menu-label">System</span>
      <a href="/focal/departments-sessions" class="menu-item {{ request()->is('focal/departments-sessions') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" stroke-width="2"><path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/></svg>
        Departments & Sessions
      </a>
    <a href="/focal/settings" class="menu-item {{ request()->is('focal/settings') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
      Settings
    </a>
  </div>

  <div class="sidebar-footer">
    <button class="logout-btn" onclick="confirmLogout()">
      <svg viewBox="0 0 24 24" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      Logout
    </button>
  </div>
</div>

<style>
.notif-bar{padding:8px 12px;border-bottom:1px solid rgba(255,255,255,.1)}
.notif-btn{display:flex;align-items:center;gap:8px;background:rgba(255,255,255,.08);border:none;border-radius:8px;padding:8px 12px;color:rgba(255,255,255,.8);font-size:12px;cursor:pointer;width:100%;font-family:"Segoe UI",sans-serif;transition:background .2s}
.notif-btn:hover{background:rgba(255,255,255,.15);color:#fff}
.notif-btn svg{width:15px;height:15px;min-width:15px;stroke:rgba(255,255,255,.8)}
.notif-label{flex:1;text-align:left}
.notif-badge{background:#ef4444;color:#fff;font-size:10px;font-weight:700;padding:2px 6px;border-radius:10px;min-width:18px;text-align:center}
.sidebar-count-badge{background:#ef4444!important;color:#fff!important;font-size:10px;font-weight:700;padding:2px 7px;border-radius:10px;margin-left:auto;line-height:1.4}
.notif-panel{max-height:0;overflow:hidden;transition:max-height .3s ease;background:#0a3a6b;border-bottom:1px solid rgba(255,255,255,.1)}
.notif-panel.open{max-height:320px}
.notif-panel-header{display:flex;justify-content:space-between;align-items:center;padding:10px 14px;border-bottom:1px solid rgba(255,255,255,.08)}
.notif-panel-header span{color:#fff;font-size:12px;font-weight:600}
.mark-all-btn{background:none;border:1px solid rgba(255,255,255,.25);color:rgba(255,255,255,.7);font-size:10px;padding:3px 8px;border-radius:5px;cursor:pointer;font-family:"Segoe UI",sans-serif}
.mark-all-btn:hover{background:rgba(255,255,255,.1);color:#fff}
.notif-list{max-height:260px;overflow-y:auto;scrollbar-width:none}
.notif-list::-webkit-scrollbar{display:none}
.notif-item{padding:10px 14px;border-bottom:1px solid rgba(255,255,255,.06);cursor:pointer;transition:background .2s}
.notif-item:hover{background:rgba(255,255,255,.06)}
.notif-item.unread{border-left:3px solid #3b82f6}
.notif-item.read{border-left:3px solid transparent;opacity:.7}
.notif-item-title{color:#fff;font-size:12px;font-weight:600;margin-bottom:3px}
.notif-item-msg{color:rgba(255,255,255,.6);font-size:11px;line-height:1.4}
.notif-item-time{color:rgba(255,255,255,.35);font-size:10px;margin-top:4px}
.notif-empty{color:rgba(255,255,255,.4);font-size:12px;text-align:center;padding:24px 0}
</style>
<script>
function togglePhase(id){
  const el = document.getElementById(id);
  if(el.classList.contains('phase-disabled')) return;
  el.classList.toggle('open');
}

// Save and restore sidebar scroll position across page loads
document.addEventListener('DOMContentLoaded', function() {
    const menu = document.querySelector('.sidebar-menu');
    if (!menu) return;

    // Restore saved scroll position
    const saved = localStorage.getItem('sidebarScroll');
    if (saved) menu.scrollTop = parseInt(saved);

    // Save scroll position on scroll
    menu.addEventListener('scroll', function() {
        localStorage.setItem('sidebarScroll', menu.scrollTop);
    });
});

// Save and restore sidebar scroll position across page loads
document.addEventListener('DOMContentLoaded', function() {
    const menu = document.querySelector('.sidebar-menu');
    if (!menu) return;
    const saved = localStorage.getItem('sidebarScroll');
    if (saved) menu.scrollTop = parseInt(saved);
    menu.addEventListener('scroll', function() {
        localStorage.setItem('sidebarScroll', menu.scrollTop);
    });
});

function toggleSidebar(){
  document.getElementById('sidebar').classList.toggle('open');
  document.getElementById('hamburger').classList.toggle('open');
  document.getElementById('overlay').classList.toggle('show');
}

if (!localStorage.getItem('api_token')) {
    window.location.href = '/logout-web';
}

function toggleNotifications() {
    const panel = document.getElementById('notif-panel');
    panel.classList.toggle('open');
    if (panel.classList.contains('open')) loadNotifications();
}

async function loadNotifications() {
    const token = localStorage.getItem('api_token');
    try {
        const res = await fetch('/api/focal/notifications', {
            headers: { 'Authorization': 'Bearer ' + token }
        });
        const data = await res.json();
        const badge = document.getElementById('notif-badge');
        const list = document.getElementById('notif-list');

        if (data.unread_count > 0) {
            badge.style.display = 'inline-block';
            badge.textContent = data.unread_count;
        } else {
            badge.style.display = 'none';
        }

        if (data.notifications.length === 0) {
            list.innerHTML = '<div class="notif-empty">No notifications</div>';
            return;
        }

        list.innerHTML = data.notifications.map(n => `
            <div class="notif-item ${n.is_read ? 'read' : 'unread'}" onclick="readNotif(${n.id}, '${n.link || ''}')">
                <div class="notif-item-title">${n.title}</div>
                <div class="notif-item-msg">${n.message}</div>
                <div class="notif-item-time">${new Date(n.created_at).toLocaleDateString('en-PK', {day:'numeric',month:'short',hour:'2-digit',minute:'2-digit'})}</div>
            </div>
        `).join('');
    } catch(e) { console.error(e); }
}

async function readNotif(id, link) {
    const token = localStorage.getItem('api_token');
    await fetch('/api/focal/notifications/' + id + '/read', {
        method: 'POST',
        headers: { 'Authorization': 'Bearer ' + token, 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
    });
    if (link) window.location.href = link;
    else loadNotifications();
}

async function markAllRead() {
    const token = localStorage.getItem('api_token');
    await fetch('/api/focal/notifications/read-all', {
        method: 'POST',
        headers: { 'Authorization': 'Bearer ' + token, 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
    });
    loadNotifications();
}

// Auto-load badge count on page load
document.addEventListener('DOMContentLoaded', async function() {
    const token = localStorage.getItem('api_token');
    if (!token) return;
    try {
        const res = await fetch('/api/focal/notifications', {
            headers: { 'Authorization': 'Bearer ' + token }
        });
        const data = await res.json();
        const badge = document.getElementById('notif-badge');
        if (data.unread_count > 0) {
            badge.style.display = 'inline-block';
            badge.textContent = data.unread_count;
        }
    } catch(e) {}
});

async function doApiLogout() {
    const token = localStorage.getItem('api_token');
    try {
        await fetch('/api/logout', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        });
    } catch(e) {}
    localStorage.clear();
    sessionStorage.clear();
    window.location.href = '/logout-web';
}

// Detect real touch usage and disable CSS hover effects reliably
(function(){
    function enableTouchMode(){
        document.documentElement.classList.add('touch-device');
        window.removeEventListener('touchstart', enableTouchMode);
    }
    window.addEventListener('touchstart', enableTouchMode, {once:true});
})();
</script>
<div id="logoutConfirmOverlay" style="display:none;position:fixed;inset:0;background:rgba(12,68,124,.35);z-index:9999;align-items:center;justify-content:center;font-family:'Segoe UI',sans-serif">
  <div style="background:#fff;border-radius:14px;padding:28px 26px;max-width:340px;width:90%;box-shadow:0 10px 40px rgba(12,68,124,.25);text-align:center">
    <div style="width:50px;height:50px;border-radius:50%;background:#e8f0f9;display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
      <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="#0C447C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
    </div>
    <h3 style="color:#0C447C;font-size:16px;font-weight:700;margin-bottom:8px">Logout Confirmation</h3>
    <p style="color:#64748b;font-size:13px;margin-bottom:22px">Are you sure you want to logout?</p>
    <div style="display:flex;gap:10px;justify-content:center">
      <button onclick="closeLogoutModal()" style="flex:1;padding:10px 16px;border:1.5px solid #e8f0f9;background:#fff;color:#0C447C;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer">Cancel</button>
      <button onclick="proceedLogout()" style="flex:1;padding:10px 16px;border:none;background:#0C447C;color:#fff;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer">Logout</button>
    </div>
  </div>
</div>
<script>
function confirmLogout(){
  document.getElementById('logoutConfirmOverlay').style.display='flex';
}
function closeLogoutModal(){
  document.getElementById('logoutConfirmOverlay').style.display='none';
}
function proceedLogout(){
  doApiLogout();
}
</script>
