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

<div class="sidebar" id="sidebar">
  <div class="sidebar-logo">
    <img src="/images/bgnu.jfif" alt="BGNU">
    <p>FYPMS</p>
    <span>Faculty Panel</span>
    <span style="display:block;color:#fff;font-size:12px;font-weight:600;margin-top:4px">{{ auth()->user()->name ?? 'Faculty' }}</span>
  </div>
  <div class="notif-bar">
    <button class="notif-btn" onclick="window.location.href='/faculty/notifications'">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 00 1-3.46 0"/></svg>
      <span class="notif-label">Notifications</span>
      <span class="notif-badge" id="notif-badge" style="display:none">0</span>
    </button>
  </div>
  <div class="sidebar-menu">
    <div class="menu-label">Main</div>
    <a href="{{ route('faculty.dashboard') }}" class="menu-item {{ request()->routeIs('faculty.dashboard') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
      Dashboard
    </a>
    <div class="menu-label">Attendance</div>
    <a href="{{ route('faculty.attendance') }}" class="menu-item {{ request()->routeIs('faculty.attendance*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
      Mark Attendance
    </a>
    <a href="{{ route('faculty.meetings') }}" class="menu-item {{ request()->routeIs('faculty.meetings*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
      Manage Meetings
    </a>
    <div class="menu-label">Evaluation</div>
    <a href="{{ route('faculty.phase-evaluation') }}" class="menu-item {{ request()->routeIs('faculty.phase-evaluation') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
      Phase Evaluation
    </a>
    <a href="{{ route('faculty.phase-documents.index') }}" class="menu-item {{ request()->routeIs('faculty.phase-documents*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
      Phase Documents
    </a>
    <a href="/faculty/defence" class="menu-item {{ request()->is('faculty/defence*') && !request()->is('faculty/defence/*/evaluate') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
      Defence Schedules
    </a>
    <a href="/faculty/my-panels" class="menu-item {{ request()->is('faculty/my-panels') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      My Panel
    </a>
  </div>
  <div class="sidebar-footer">
    <form method="POST" action="{{ route('logout') }}" id="facultyLogoutForm">
      @csrf
      <button type="button" class="logout-btn" onclick="confirmLogout()">
        <svg viewBox="0 0 24 24" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        Logout
      </button>
    </form>
  </div>
</div>
<script>
function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('open');
    const hamburger = document.getElementById('hamburger');
    if(hamburger) hamburger.classList.toggle('open');
    const overlay = document.getElementById('overlay');
    if(overlay) overlay.classList.toggle('show');
}
async function loadNotifBadge() {
    const token = localStorage.getItem('api_token');
    if (!token) return;
    try {
        const res = await fetch('/api/faculty/notifications', {
            headers: { 'Authorization': 'Bearer ' + token }
        });
        const data = await res.json();
        const badge = document.getElementById('notif-badge');
        if (data.unread_count > 0) {
            badge.style.display = 'inline-block';
            badge.textContent = data.unread_count;
        }
    } catch(e) {}
}
document.addEventListener('DOMContentLoaded', loadNotifBadge);

// Keep the sidebar logo spin continuous across page loads
(function(){
    var logo = document.querySelector('.sidebar-logo img');
    if(!logo) return;
    var duration = 4000; // must match CSS animation duration (4s)
    var offset = Date.now() % duration;
    logo.style.animationDelay = '-' + offset + 'ms';
})();

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
  document.getElementById('facultyLogoutForm').submit();
}
</script>
