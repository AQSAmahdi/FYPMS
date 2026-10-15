<!DOCTYPE html>
<html lang="en" style="background:linear-gradient(90deg,#0C447C 260px,#f0f4f8 260px)">
<head>
<style>html,body{background:#f0f4f8}</style>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Meetings - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
.main{margin-left:260px;padding:28px 32px;min-height:100vh}
.topbar{background:#fff;border-radius:12px;padding:16px 22px;margin-bottom:24px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9}
.topbar h2{color:#0C447C;font-size:18px;font-weight:700}
.topbar p{color:#8096b0;font-size:12px;margin-top:2px}
.badge-role{background:#e8f0f9;color:#0C447C;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600}
.card-shell{background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9;margin-bottom:20px;transition:box-shadow .25s ease,transform .25s ease}
.card-shell:hover{box-shadow:0 6px 22px rgba(12,68,124,.12);transform:translateY(-2px)}
.card-header{background:linear-gradient(135deg,#378ADD,#0C447C);padding:16px 24px;display:flex;align-items:center;gap:10px}
.icon-badge{width:32px;height:32px;border-radius:9px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.icon-badge svg{width:16px;height:16px;stroke:#fff;fill:none}
.card-title{color:#fff;font-size:14px;font-weight:700}
.card-body{padding:24px}
.attempt-row{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;border:1.5px solid #e8f0f9;border-left:3px solid #378ADD;border-radius:10px;padding:14px 18px;margin-bottom:10px}
.attempt-row:last-child{margin-bottom:0}
.attempt-num{font-size:14px;font-weight:700;color:#1e3a5f;margin-bottom:4px}
.attempt-date{font-size:12px;color:#8096b0}
.attempt-agenda{font-size:12.5px;color:#4a5f7a;margin-top:6px;max-width:520px}
.attempt-right{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.badge-pass{background:#dcfce7;color:#166534;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:700;white-space:nowrap}
.badge-fail{background:#fce4e4;color:#c62828;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:700;white-space:nowrap}
.badge-pending{background:#f4f8fd;color:#8096b0;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600;white-space:nowrap}
.badge-status{background:#e0e7ff;color:#3730a3;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:700;white-space:nowrap;text-transform:capitalize}
.empty{text-align:center;padding:60px;color:#8096b0;font-size:14px;background:#fff;border-radius:12px;border:1px solid #e8f0f9}
.no-group{background:#fff3cd;color:#856404;padding:16px 20px;border-radius:10px;border:1px solid #fde68a;font-size:14px;margin-bottom:20px}
@media(max-width:768px){ .main{margin-left:0;padding:16px} }
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

<div class="main">
  <div class="topbar">
    <div style="flex:1;text-align:center">
      <h2 style="font-size:26px;font-weight:800;color:#0C447C;letter-spacing:-0.3px">My Meetings</h2>
      <p>Meetings scheduled by your supervisor &amp; your attendance record</p>
    </div>
    <span class="badge-role">Student</span>
  </div>

  @if(!$myGroup)
    <div class="no-group">You are not part of an accepted FYP group yet, so no meetings are available.</div>
  @endif

  <div class="card-shell">
    <div class="card-header">
      <span class="icon-badge">
        <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
      </span>
      <span class="card-title">Meeting Schedule</span>
    </div>
    <div class="card-body">
      @forelse($meetings as $meeting)
        <div class="attempt-row">
          <div>
            <div class="attempt-num">
              {{ \Carbon\Carbon::parse($meeting->meeting_date)->format('d M, Y') }}
              @if($meeting->meeting_time)
                &middot; {{ \Carbon\Carbon::parse($meeting->meeting_time)->format('h:i A') }}
              @endif
            </div>
            @if($meeting->agenda)
              <div class="attempt-agenda">{{ $meeting->agenda }}</div>
            @endif
            @if($meeting->my_remarks)
              <div class="attempt-date" style="margin-top:4px">Remarks: {{ $meeting->my_remarks }}</div>
            @endif
          </div>
          <div class="attempt-right">
            @if($meeting->status)
              <span class="badge-status">{{ $meeting->status }}</span>
            @endif
            @if($meeting->my_status === 'present')
              <span class="badge-pass">Present</span>
            @elseif($meeting->my_status === 'absent')
              <span class="badge-fail">Absent</span>
            @else
              <span class="badge-pending">Not Marked</span>
            @endif
          </div>
        </div>
      @empty
        <div class="empty">No meetings scheduled yet.</div>
      @endforelse
    </div>
  </div>
</div>

<script>
function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('hamburger').classList.toggle('open');
    const overlay = document.getElementById('overlay');
    if (overlay) overlay.classList.toggle('show');
}
</script>
</body>
</html>
