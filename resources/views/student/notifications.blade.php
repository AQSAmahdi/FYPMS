<!DOCTYPE html>
<html lang="en" style="background:linear-gradient(90deg,#0C447C 260px,#f0f4f8 260px)">
<head>
<style>html,body{background:#f0f4f8}</style>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Notifications - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
@keyframes borderFlow{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
.main{margin-left:260px;padding:6px 32px 28px;min-height:100vh}
.topbar{background:linear-gradient(135deg,#378ADD,#0C447C);border-radius:12px;padding:18px 26px;margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 1px 12px rgba(12,68,124,.15)}
.topbar h2{color:#fff;font-size:18px;font-weight:700}
.topbar p{color:rgba(255,255,255,.8);font-size:12px;margin-top:3px}
.badge{padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600}
.badge-student{background:#e8f0f9;color:#0C447C}
.badge-open{background:#dcfce7;color:#166534}
.badge-locked{background:#fef3c7;color:#92400e}
.badge-submitted{background:#ede9fe;color:#6d28d9}
.badge-assigned{background:#dcfce7;color:#166534}
.alert{padding:12px 18px;border-radius:9px;margin-bottom:18px;font-size:13px;font-weight:500}
.alert-success{background:#dcfce7;color:#166534;border:1px solid #bbf7d0}
.alert-error{background:#fef2f2;color:#b91c1c;border:1px solid #fecaca}
.section-card{background:#fff;border-radius:12px;padding:24px;margin-bottom:20px;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9}
.section-card h3{color:#0C447C;font-size:14px;font-weight:700;margin-bottom:16px;display:flex;align-items:center;gap:8px}
.section-card h3 svg{width:16px;height:16px;stroke:#0C447C;fill:none}
.animated-border{padding:2px;border-radius:12px;background:linear-gradient(270deg,#0C447C,#1a6bbf,#38bdf8,#0C447C);background-size:300% 300%;animation:borderFlow 2.5s ease infinite;margin-bottom:18px}
.animated-border-inner{background:#fff;border-radius:10px;padding:20px}
.group-info-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:16px}
.info-box{background:#f4f8fd;border-radius:9px;padding:14px 16px;text-align:center}
.info-box .label{font-size:11px;color:#8096b0;text-transform:uppercase;letter-spacing:.6px;margin-bottom:5px}
.info-box .value{font-size:16px;font-weight:700;color:#0C447C}
.members-list{display:flex;flex-direction:column;gap:8px}
.member-row{display:flex;align-items:center;gap:12px;padding:10px 14px;background:#f4f8fd;border-radius:8px}
.member-avatar{width:34px;height:34px;background:#0C447C;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:13px;font-weight:700;flex-shrink:0}
.member-name{font-size:13px;font-weight:600;color:#1a2b3c}
.member-roll{font-size:11.5px;color:#8096b0}
.member-badge{margin-left:auto;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600}
.creator-badge{background:#e8f0f9;color:#0C447C}
.member-badge-plain{background:#f0f4f8;color:#5a7a9a}
.btn-primary{padding:10px 20px;background:#0C447C;color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:'Segoe UI',sans-serif;transition:background .2s;text-decoration:none;display:inline-block}
.btn-primary:hover{background:#185FA5}
.btn-danger{padding:8px 16px;background:#fef2f2;color:#b91c1c;border:1px solid #fecaca;border-radius:7px;font-size:12.5px;font-weight:600;cursor:pointer;font-family:'Segoe UI',sans-serif}
.btn-success-sm{padding:6px 14px;background:#dcfce7;color:#166534;border:1px solid #bbf7d0;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;font-family:'Segoe UI',sans-serif}
.btn-reject-sm{padding:6px 14px;background:#fef2f2;color:#b91c1c;border:1px solid #fecaca;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;font-family:'Segoe UI',sans-serif}
.open-groups-grid{display:flex;flex-direction:column;gap:12px}
.group-card-wrap{padding:2px;border-radius:11px;background:linear-gradient(270deg,#0C447C,#1a6bbf,#38bdf8,#0C447C);background-size:300% 300%;animation:borderFlow 2.5s ease infinite}
.group-card{background:#fff;border-radius:9px;padding:16px 18px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
.group-card-info .code{font-size:13px;font-weight:700;color:#0C447C}
.group-card-info .creator{font-size:12px;color:#8096b0;margin-top:3px}
.group-card-info .slots{font-size:12px;color:#5a7a9a;margin-top:3px}
.form-group{margin-bottom:16px}
.form-group label{display:block;font-size:12px;font-weight:600;color:#0C447C;margin-bottom:6px;text-transform:uppercase;letter-spacing:.5px}
.input-wrap{padding:2px;border-radius:9px;background:linear-gradient(270deg,#0C447C,#1a6bbf,#38bdf8,#0C447C);background-size:300% 300%;animation:borderFlow 2.5s ease infinite}
.input-wrap input,.input-wrap textarea{width:100%;padding:10px 14px;border:none;border-radius:7px;font-size:13px;color:#1a2b3c;background:#f7fafd;outline:none;font-family:'Segoe UI',sans-serif}
.input-wrap textarea{height:90px;resize:vertical}
.request-row{display:flex;align-items:center;gap:12px;padding:10px 14px;background:#f4f8fd;border-radius:8px;margin-bottom:8px;flex-wrap:wrap}
.empty-state{text-align:center;padding:32px;color:#9aabbf;font-size:13px}
.empty-state svg{width:36px;height:36px;stroke:#c4d4e8;fill:none;margin:0 auto 10px;display:block}
.no-group-actions{display:flex;gap:12px;flex-wrap:wrap}
@media(max-width:768px){
  .main{margin-left:0;padding:16px;padding-top:62px}
  .group-info-grid{grid-template-columns:repeat(2,1fr)}
  .topbar{flex-direction:column;align-items:flex-start;gap:8px}
  .group-card{flex-direction:column;align-items:flex-start}
}
@media(max-width:480px){
  .group-info-grid{grid-template-columns:1fr}
  .no-group-actions{flex-direction:column}
}
</style>
<style>
.notif-page-wrap{background:#fff;border-radius:12px;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9;overflow:hidden}
.notif-page-item{padding:16px 20px;border-bottom:1px solid #f0f4f8;cursor:pointer;transition:background .2s;display:flex;gap:14px;align-items:flex-start}
.notif-page-item:last-child{border-bottom:none}
.notif-page-item:hover{background:#f8fafd}
.notif-page-item.unread{border-left:4px solid #0C447C}
.notif-page-item.read{border-left:4px solid transparent;opacity:.75}
.notif-clear-btn{background:none;border:none;color:#b0c0d0;cursor:pointer;font-size:18px;line-height:1;padding:4px 8px;border-radius:6px;flex-shrink:0}
.notif-clear-btn:hover{background:#fef2f2;color:#b91c1c}
.notif-icon{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.notif-icon.info{background:#dbeafe}.notif-icon.success{background:#dcfce7}.notif-icon.warning{background:#fef9c3}
.notif-icon svg{width:16px;height:16px;fill:none}
.notif-icon.info svg{stroke:#3b82f6}.notif-icon.success svg{stroke:#16a34a}.notif-icon.warning svg{stroke:#ca8a04}
.notif-body{flex:1}
.notif-title{color:#0C447C;font-size:14px;font-weight:600;margin-bottom:4px}
.notif-msg{color:#5a7a9a;font-size:13px;line-height:1.5}
.notif-time{color:#a0b4c8;font-size:11px;margin-top:6px}
.unread-dot{width:8px;height:8px;border-radius:50%;background:#0C447C;flex-shrink:0;margin-top:6px}
.page-empty{text-align:center;padding:32px;color:#9aabbf;font-size:13px}
.page-empty svg{width:36px;height:36px;stroke:#c4d4e8;fill:none;margin:0 auto 10px;display:block}
.mark-btn{background:#0C447C;color:#fff;border:1.5px solid rgba(255,255,255,.6);border-radius:8px;padding:8px 16px;font-size:12px;cursor:pointer;font-family:"Segoe UI",sans-serif}
.mark-btn:hover{background:#0a3a6b}
.pagination{display:flex;justify-content:center;align-items:center;gap:14px;padding:18px 0}
.page-btn{background:#fff;border:1px solid #d5e3f0;color:#0C447C;border-radius:8px;padding:7px 16px;font-size:12px;cursor:pointer;font-family:'Segoe UI',sans-serif}
.page-btn:disabled{opacity:.4;cursor:not-allowed}
.page-info{color:#5a7a9a;font-size:12px}
</style>
<link rel="stylesheet" href="/css/transitions.css?v=3">
<meta name="view-transition" content="same-origin">
</head>
<body>
<button class="hamburger" id="hamburger" onclick="toggleSidebar()">
  <span></span><span></span><span></span>
</button>
<div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

@include('student.partials.sidebar')
  <div class="sidebar-footer">
    <a href="/logout" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="logout-btn" style="cursor:pointer;display:flex;align-items:center;gap:10px;text-decoration:none">
      <svg viewBox="0 0 24 24" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      Logout
    </a>
    <form id="logout-form" action="/logout" method="POST" style="display:none">{{ csrf_field() }}</form>
  </div>
</div>


<div class="main">
  <div class="topbar">
    <div>
      <h2>Notifications</h2>
      <p>Your recent updates and alerts</p>
    </div>
    <div style="display:flex;gap:10px">
    <button class="mark-btn" onclick="markAllRead()">Mark all as read</button>
    <button class="mark-btn" onclick="clearRead()" style="background:#f0f4f8;color:#5a7a9a;border:1px solid #d5e3f0">Clear read</button>
  </div>
  </div>
  <div class="notif-page-wrap" id="notif-container">
    <div class="page-empty">Loading...</div>
  </div>
  <div class="pagination" id="pagination" style="display:none">
    <button class="page-btn" id="prev-btn" onclick="goPage(currentPage-1)">&larr; Previous</button>
    <span class="page-info" id="page-info"></span>
    <button class="page-btn" id="next-btn" onclick="goPage(currentPage+1)">Next &rarr;</button>
  </div>
</div>
<script>
const token = localStorage.getItem('api_token');
let currentPage = 1;
function getIcon(type) {
    if(type==='success') return '<svg viewBox="0 0 24 24" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>';
    if(type==='warning') return '<svg viewBox="0 0 24 24" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>';
    return '<svg viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';
}
async function loadNotifications(page) {
    page = page || 1;
    try {
        const res = await fetch('/api/student/notifications?page=' + page, { headers: { 'Authorization': 'Bearer ' + token} });
        const data = await res.json();
        currentPage = data.current_page || 1;
        const c = document.getElementById('notif-container');
        const pagination = document.getElementById('pagination');
        if(data.notifications.length === 0) {
            c.innerHTML = '<div class="page-empty"><svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>No notifications yet</div>';
            if(pagination) pagination.style.display = 'none';
            return;
        }
        c.innerHTML = data.notifications.map(function(n){
            return '<div class="notif-page-item ' + (n.is_read?'read':'unread') + '" onclick="readNotif(' + n.id + ',\''+(n.link||'')+'\')">' +
                '<div class="notif-icon ' + n.type + '">' + getIcon(n.type) + '</div>' +
                '<div class="notif-body">' +
                    '<div class="notif-title">' + n.title + '</div>' +
                    '<div class="notif-msg">' + n.message + '</div>' +
                    '<div class="notif-time">' + new Date(n.created_at).toLocaleDateString('en-PK',{day:'numeric',month:'short',year:'numeric',hour:'2-digit',minute:'2-digit'}) + '</div>' +
                '</div>' +
                (!n.is_read ? '<div class="unread-dot"></div>' : '') +
                '<button type="button" class="notif-clear-btn" onclick="event.stopPropagation(); clearNotif(' + n.id + ')" title="Clear">&times;</button>' +
            '</div>';
        }).join('');

        if (pagination) {
            if (data.last_page > 1) {
                pagination.style.display = 'flex';
                document.getElementById('page-info').textContent = 'Page ' + data.current_page + ' of ' + data.last_page;
                document.getElementById('prev-btn').disabled = data.current_page <= 1;
                document.getElementById('next-btn').disabled = data.current_page >= data.last_page;
            } else {
                pagination.style.display = 'none';
            }
        }
    } catch(e) { console.error(e); }
}
function goPage(page) {
    if (page < 1) return;
    loadNotifications(page);
}
async function readNotif(id, link) {
    const csrf = document.querySelector('meta[name=csrf-token]').content;
    await fetch('/api/student/notifications/'+id+'/read', { method:'POST', headers:{'Authorization':'Bearer '+token,'X-CSRF-TOKEN':csrf} });
    if(link) window.location.href = link;
    else loadNotifications(currentPage);
}
async function clearNotif(id) {
    const csrf = document.querySelector('meta[name=csrf-token]').content;
    await fetch('/api/student/notifications/' + id + '/clear', {
        method: 'POST',
        headers: { 'Authorization': 'Bearer ' + token, 'X-CSRF-TOKEN': csrf }
    });
    loadNotifications(currentPage);
}
async function clearRead() {
    if (!confirm('Hide all read notifications from this list?')) return;
    const csrf = document.querySelector('meta[name=csrf-token]').content;
    await fetch('/api/student/notifications/clear-read', {
        method: 'POST',
        headers: { 'Authorization': 'Bearer ' + token,'X-CSRF-TOKEN': csrf }
    });
    loadNotifications(1);
}

async function markAllRead() {
    const csrf = document.querySelector('meta[name=csrf-token]').content;
    await fetch('/api/student/notifications/read-all',{ method:'POST', headers:{'Authorization':'Bearer '+token,'X-CSRF-TOKEN':csrf} });
    loadNotifications(currentPage);
}
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    sidebar.classList.toggle('open');
    if(overlay) overlay.classList.toggle('active');
}
loadNotifications(1);
</script>
</body>
</html>