<!DOCTYPE html>
<html lang="en" style="background:linear-gradient(90deg,#0C447C 260px,#f0f4f8 260px)">
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Dashboard - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
@keyframes borderFlow{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
.main{margin-left:260px;padding:28px 32px;min-height:100vh}
.topbar{background:#fff;border-radius:12px;padding:18px 26px;margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9}
.topbar h2{color:#0C447C;font-size:18px;font-weight:700}
.topbar p{color:#8096b0;font-size:12px;margin-top:3px}
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
<link rel="stylesheet" href="/css/transitions.css">
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
    <div><h2>My Proposal</h2><p>Submit and track your FYP proposal</p></div>
    <span class="badge badge-student">Student</span>
  </div>
  @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
  @if(session('error'))<div class="alert alert-error">{{ session('error') }}</div>@endif

  @if($myGroup)
    @if($myGroup->status=="locked" && !$myGroup->proposal)
    <div class="section-card">
      <h3>Submit FYP Proposal</h3>
      <form method="POST" class="ajax-form" action="/student/group/proposal">@csrf
        <div class="form-group"><label>Project Title</label><div class="input-wrap"><input type="text" name="title" placeholder="Enter your project title" required></div></div>
        <div class="form-group"><label>Description</label><div class="input-wrap"><textarea name="description" placeholder="Describe your project..." required></textarea></div></div>
        <div class="form-group"><label>Technology Stack</label><div class="input-wrap"><input type="text" name="technology_stack" placeholder="e.g. Laravel, React, MySQL"></div></div>
        <button type="submit" class="btn-primary">Submit Proposal</button>
      </form>
    </div>
    @elseif($myGroup->proposal)
    <div class="section-card">
      <h3>Submitted Proposal</h3>
      <div style="font-size:16px;font-weight:700;color:#0C447C;margin-bottom:8px">{{ $myGroup->proposal->title }}</div>
      <div style="font-size:13px;color:#5a7a9a;margin-bottom:12px">{{ $myGroup->proposal->description }}</div>
      <div style="font-size:13px;margin-bottom:8px">Status:
        @if($myGroup->proposal->status=="pending")<span style="color:#92400e;font-weight:600">Pending Review</span>
        @elseif($myGroup->proposal->status=="approved")<span style="color:#166534;font-weight:600">Approved</span>
        @else<span style="color:#b91c1c;font-weight:600">Rejected</span>@endif
      </div>
      @if($myGroup->proposal->focal_remarks)
      <div style="background:#f4f8fd;border-radius:8px;padding:12px;font-size:13px;color:#5a7a9a;margin-top:10px"><strong>Remarks:</strong> {{ $myGroup->proposal->focal_remarks }}</div>
      @endif
    </div>
    @else
    <div class="section-card">
      <h3>Proposal Not Available</h3>
      <p style="color:#8096b0;font-size:13px">Your group needs to be locked first. Go to <a href="/student/my-group" style="color:#0C447C">My Group</a> to lock your group.</p>
    </div>
    @endif
  @else
  <div class="section-card">
    <h3>No Group Found</h3>
    <p style="color:#8096b0;font-size:13px">You need to be in a group first. <a href="/student/my-group" style="color:#0C447C">Join or create a group</a>.</p>
  </div>
  @endif
</div>
<script src="/js/ajax.js"></script>
<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    sidebar.classList.toggle('open');
    if (overlay) overlay.classList.toggle('active');
}
</script>
<script>
if (typeof apiLogout === "undefined") {
    function apiLogout() {
        localStorage.removeItem("api_token");
        window.location.href = "/login";
    }
}
</script>
</body></html>