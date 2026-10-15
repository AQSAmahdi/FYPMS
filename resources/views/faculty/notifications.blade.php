<!DOCTYPE html>
<html lang="en" style="background:linear-gradient(90deg,#0C447C 260px,#f0f4f8 260px)">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Notifications - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
.main{margin-left:260px;padding:22px 32px 28px;min-height:100vh}
.topbar{background:linear-gradient(135deg,#378ADD,#0C447C);border-radius:12px;padding:18px 26px;margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 1px 12px rgba(12,68,124,.15)}
.topbar h2{color:#fff;font-size:18px;font-weight:700}
.topbar p{color:rgba(255,255,255,.8);font-size:12px;margin-top:3px}
.mark-btn{background:#0C447C;color:#fff;border:1.5px solid rgba(255,255,255,.6);border-radius:8px;padding:8px 16px;font-size:12px;cursor:pointer;font-family:'Segoe UI',sans-serif;transition:background .2s}
.mark-btn:hover{background:#0a3a6b}
.notif-container{background:#fff;border-radius:12px;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9;overflow:hidden}
.notif-item{padding:16px 20px;border-bottom:1px solid #f0f4f8;cursor:pointer;transition:background .2s;display:flex;gap:14px;align-items:flex-start}
.notif-item:last-child{border-bottom:none}
.notif-item:hover{background:#f8fafd}
.notif-item.unread{border-left:4px solid #0C447C}
.notif-item.read{border-left:4px solid transparent;opacity:.75}
.notif-clear-btn{background:none;border:none;color:#b0c0d0;cursor:pointer;font-size:18px;line-height:1;padding:4px 8px;border-radius:6px;flex-shrink:0}
.notif-clear-btn:hover{background:#fef2f2;color:#b91c1c}
.notif-icon{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.notif-icon.info{background:#dbeafe}
.notif-icon.success{background:#dcfce7}
.notif-icon.warning{background:#fef9c3}
.notif-icon svg{width:16px;height:16px;fill:none}
.notif-icon.info svg{stroke:#3b82f6}
.notif-icon.success svg{stroke:#16a34a}
.notif-icon.warning svg{stroke:#ca8a04}
.notif-body{flex:1}
.notif-title{color:#0C447C;font-size:14px;font-weight:600;margin-bottom:4px}
.notif-msg{color:#5a7a9a;font-size:13px;line-height:1.5}
.notif-time{color:#a0b4c8;font-size:11px;margin-top:6px}
.unread-dot{width:8px;height:8px;border-radius:50%;background:#0C447C;flex-shrink:0;margin-top:6px}
.empty{text-align:center;padding:32px;color:#9aabbf;font-size:13px}
.empty svg{width:36px;height:36px;stroke:#c4d4e8;fill:none;margin:0 auto 10px;display:block}
@media(max-width:768px){.main{margin-left:0;padding:16px;padding-top:62px}}
.pagination{display:flex;justify-content:center;align-items:center;gap:14px;padding:18px 0}
.page-btn{background:#fff;border:1px solid #d5e3f0;color:#0C447C;border-radius:8px;padding:7px 16px;font-size:12px;cursor:pointer;font-family:'Segoe UI',sans-serif}
.page-btn:disabled{opacity:.4;cursor:not-allowed}
.page-info{color:#5a7a9a;font-size:12px}
</style>
</head>
<body>
@include('faculty.partials.sidebar')
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
  <div class="notif-container" id="notif-container">
    <div class="empty">
      <p>Loading notifications...</p>
    </div>
  </div>
  <div class="pagination" id="pagination" style="display:none">
    <button class="page-btn" id="prev-btn" onclick="goPage(currentPage-1)">&larr; Previous</button>
    <span class="page-info" id="page-info"></span>
    <button class="page-btn" id="next-btn" onclick="goPage(currentPage+1)">Next&rarr;</button>
  </div>
</div>
<script>
const token = localStorage.getItem('api_token');
let currentPage = 1;

function getIcon(type) {
    if(type === 'success') return '<svg viewBox="0 0 24 24" fill="none" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>';
    if(type === 'warning') return '<svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>';
    return '<svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';
}

async function loadNotifications(page) {
    page = page || 1;
    try {
        const res = await fetch('/api/faculty/notifications?page=' + page, {
            headers: { 'Authorization': 'Bearer ' + token }
        });
        const data = await res.json();
        currentPage = data.current_page || 1;
        const container = document.getElementById('notif-container');
        const pagination = document.getElementById('pagination');

        if(data.notifications.length === 0) {
            container.innerHTML = '<div class="empty"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.5"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg><p>No notifications yet</p></div>';
            pagination.style.display = 'none';
            return;
        }

        container.innerHTML = data.notifications.map(function(n){
            return '<div class="notif-item ' + (n.is_read ? 'read' : 'unread') + '" onclick="readNotif(' + n.id + ', \'' + (n.link||'') + '\')">' +
                '<div class="notif-icon ' + n.type + '">' + getIcon(n.type) + '</div>' +
                '<div class="notif-body">' +
                    '<div class="notif-title">' + n.title + '</div>' +
                    '<div class="notif-msg">' + n.message + '</div>' +
                    '<div class="notif-time">' + new Date(n.created_at).toLocaleDateString('en-PK', {day:'numeric',month:'short',year:'numeric',hour:'2-digit',minute:'2-digit'}) + '</div>' +
                '</div>' +
                (!n.is_read ? '<div class="unread-dot"></div>' : '') +
                '<button type="button" class="notif-clear-btn" onclick="event.stopPropagation(); clearNotif(' + n.id + ')" title="Clear">&times;</button>' +
            '</div>';
        }).join('');

        if (data.last_page > 1) {
            pagination.style.display = 'flex';
            document.getElementById('page-info').textContent = 'Page ' + data.current_page + ' of ' + data.last_page;
            document.getElementById('prev-btn').disabled = data.current_page <=1;
            document.getElementById('next-btn').disabled = data.current_page >=data.last_page;
        } else {
            pagination.style.display = 'none';
        }
    } catch(e) { console.error(e); }
}

function goPage(page) {
    if (page < 1) return;
    loadNotifications(page);
}

async function readNotif(id, link) {
    await fetch('/api/faculty/notifications/' + id + '/read', {
        method: 'POST',
        headers: { 'Authorization': 'Bearer ' + token,'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
    });
    if(link) window.location.href = link;
    else loadNotifications(currentPage);
}

async function clearNotif(id) {
    const csrf = document.querySelector('meta[name=csrf-token]').content;
    await fetch('/api/faculty/notifications/' + id + '/clear', {
        method: 'POST',
        headers: { 'Authorization': 'Bearer ' + token, 'X-CSRF-TOKEN': csrf }
    });
    loadNotifications(currentPage);
}
async function clearRead() {
    if (!confirm('Hide all read notifications from this list?')) return;
    const csrf = document.querySelector('meta[name=csrf-token]').content;
    await fetch('/api/faculty/notifications/clear-read', {
        method: 'POST',
        headers: { 'Authorization': 'Bearer ' + token,'X-CSRF-TOKEN': csrf }
    });
    loadNotifications(1);
}

async function markAllRead() {
    await fetch('/api/faculty/notifications/read-all',{
        method: 'POST',
        headers: { 'Authorization': 'Bearer ' + token,'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
    });
    loadNotifications(currentPage);
}

loadNotifications(1);
</script>
</body>
</html>
