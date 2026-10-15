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
    <span>Student Panel</span>
    <span style="display:block;color:#fff;font-size:12px;font-weight:600;margin-top:4px">{{ auth()->user()->name ?? 'Student' }}</span>
  </div>
  <div class="notif-bar">
    <button class="notif-btn" onclick="window.location.href='/student/notifications'">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
      <span class="notif-label">Notifications</span>
      <span class="notif-badge" id="notif-badge" style="display:none">0</span>
    </button>
  </div>
  <div class="sidebar-menu">
    <div class="menu-label">Main</div>
    <a href="/student/dashboard" class="menu-item {{ request()->is('student/dashboard') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
      Dashboard
    </a>
    <div class="menu-label">My FYP</div>
    <a href="/student/my-group" class="menu-item {{ request()->is('student/my-group') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      My Group
    </a>

    <a href="/student/phase-documents" class="menu-item {{ request()->is('student/phase-documents') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
      Phase Documents
    </a>
    <a href="/student/my-progress" class="menu-item {{ request()->is('student/my-progress') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
      My Progress
    </a>
    <a href="/student/meetings" class="menu-item {{ request()->is('student/meetings') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
      Meetings
    </a>
    @php
        $__chatMembership = \App\Models\GroupMember::where('user_id', auth()->id())->where('status','accepted')->first();
    @endphp
    @if($__chatMembership)
    <a href="{{ route('chat.index', $__chatMembership->fyp_group_id) }}" class="menu-item {{ request()->is('chat/*') ? 'active' : '' }}" style="position:relative">
      <svg viewBox="0 0 24 24" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      Group Chat
      <span id="chat-badge" style="display:none;background:#e63946;color:#fff;font-size:10px;font-weight:700;padding:2px 7px;border-radius:10px;margin-left:auto">0</span>
    </a>
    @endif
    <script>
    async function loadChatBadge() {
        try {
            const res = await fetch('{{ route("chat.unread-badge") }}');
            const data = await res.json();
            const badge = document.getElementById('chat-badge');
            if (!badge) return;
            if (data.success && data.unread > 0) {
                badge.style.display = 'inline-block';
                badge.textContent = data.unread;
            } else {
                badge.style.display = 'none';
            }
        } catch(e) {}
    }
    document.addEventListener('DOMContentLoaded', loadChatBadge);
    setInterval(loadChatBadge, 10000);
    </script>
    <a href="/student/assistant" class="menu-item {{ request()->is('student/assistant') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      FYP Assistant
    </a>
  </div>
  <div class="sidebar-footer">
    <a href="/logout" onclick="event.preventDefault();confirmLogout();" class="logout-btn" style="cursor:pointer;display:flex;align-items:center;gap:10px;text-decoration:none">
      <svg viewBox="0 0 24 24" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      Logout
    </a>
    <form id="logout-form" action="/logout" method="POST" style="display:none">{{ csrf_field() }}</form>
  </div>
</div>

<script>
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
  document.getElementById('logout-form').submit();
}
</script>
