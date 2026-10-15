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

@media (prefers-reduced-motion: reduce){
  *,*::before,*::after{animation-duration:.01ms!important;animation-iteration-count:1!important;transition-duration:.01ms!important}
}

@keyframes borderFlow{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
@keyframes fadeInUp{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
@keyframes logoPop{0%{opacity:0;transform:scale(.85)}100%{opacity:1;transform:scale(1)}}

.hamburger{display:none;position:fixed;top:14px;left:14px;z-index:1100;background:#0C447C;border:none;border-radius:8px;padding:9px 10px;cursor:pointer;flex-direction:column;gap:5px}
.hamburger span{display:block;width:20px;height:2px;background:#fff;border-radius:2px;transition:all .3s}
.hamburger.open span:nth-child(1){transform:translateY(7px) rotate(45deg)}
.hamburger.open span:nth-child(2){opacity:0}
.hamburger.open span:nth-child(3){transform:translateY(-7px) rotate(-45deg)}
.overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998}
.overlay.show{display:block}
div.sidebar{width:260px;background:linear-gradient(180deg,#0C447C,#082f57);min-height:100vh;display:flex;flex-direction:column;position:fixed;left:0;top:0;z-index:999;transition:transform .3s ease}
.sidebar-logo{padding:28px 20px 22px;border-bottom:1px solid rgba(255,255,255,.1);text-align:center;perspective:400px}
@keyframes logoGentleSpin{0%{transform:rotateY(0deg)}100%{transform:rotateY(360deg)}}
.sidebar-logo img{width:80px;height:80px;border-radius:50%;border:2.5px solid rgba(255,255,255,.3);object-fit:cover;animation:logoGentleSpin 10s linear infinite}
.sidebar-logo p{color:#fff;font-size:14.5px;font-weight:700;margin-top:10px;letter-spacing:.3px}
.sidebar-logo span{color:rgba(255,255,255,.5);font-size:11px}
.sidebar-menu{padding:18px 0;flex:1;overflow-y:auto}
.menu-label{color:rgba(255,255,255,.45);font-size:10.5px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;padding:0 26px;margin:20px 0 6px}
.menu-item{display:flex;align-items:center;gap:11px;padding:11px 16px;margin:2px 14px;border-radius:10px;color:rgba(255,255,255,.75);text-decoration:none;font-size:13.5px;font-weight:500;letter-spacing:.2px;transition:background .2s ease,color .2s ease,box-shadow .2s ease}
.menu-item svg{width:16px;height:16px;flex-shrink:0;stroke:rgba(255,255,255,.7);fill:none;transition:stroke .25s ease}
.menu-item:hover{background:rgba(255,255,255,.1);color:#fff}
.menu-item:hover svg{stroke:#fff}
.menu-item.active{background:#fff;color:#0C447C;font-weight:700;box-shadow:0 3px 10px rgba(0,0,0,.18)}
.menu-item.active svg{stroke:#0C447C}
.sidebar-footer{padding:16px 22px;border-top:1px solid rgba(255,255,255,.1)}
.logout-btn{display:flex;align-items:center;gap:10px;color:rgba(255,255,255,.55);font-size:13px;cursor:pointer;background:none;border:none;width:100%;padding:8px 0;transition:color .2s ease;font-family:'Segoe UI',sans-serif}
.logout-btn svg{width:16px;height:16px;stroke:rgba(255,255,255,.55);fill:none;transition:stroke .2s ease}
.logout-btn:hover{color:#fff}
.logout-btn:hover svg{stroke:#fff}
.main{margin-left:260px;padding:28px 32px;min-height:100vh}
.topbar{background:#fff;border-radius:12px;padding:18px 26px;margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9;animation:fadeInUp .45s cubic-bezier(.22,.61,.36,1) both}
.topbar-left{display:flex;align-items:center;gap:14px}
.avatar-circle{width:46px;height:46px;border-radius:50%;background:linear-gradient(135deg,#0C447C,#1a6bbf);color:#fff;display:flex;align-items:center;justify-content:center;font-size:17px;font-weight:700;flex-shrink:0;box-shadow:0 3px 8px rgba(12,68,124,.25)}
.topbar h2{color:#0C447C;font-size:18px;font-weight:700}
.topbar p{color:#8096b0;font-size:12px;margin-top:3px}
.badge{padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600}
.badge-student{background:#e8f0f9;color:#0C447C}
.badge-open{background:#dcfce7;color:#166534}
.badge-locked{background:#fef3c7;color:#92400e}
.badge-submitted{background:#ede9fe;color:#6d28d9}
.badge-assigned{background:#dcfce7;color:#166534}
.alert{padding:12px 18px;border-radius:9px;margin-bottom:18px;font-size:13px;font-weight:500;animation:fadeIn .3s ease both}
.alert-success{background:#dcfce7;color:#166534;border:1px solid #bbf7d0}
.alert-error{background:#fef2f2;color:#b91c1c;border:1px solid #fecaca}
.section-card{background:#fff;border-radius:16px;padding:0;margin-bottom:20px;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9;animation:fadeInUp .5s cubic-bezier(.22,.61,.36,1) both;transition:box-shadow .25s ease,transform .25s ease;position:relative;overflow:hidden}
.section-card:hover{box-shadow:0 6px 22px rgba(12,68,124,.12);transform:translateY(-2px)}
.card-header{background:linear-gradient(135deg,#378ADD,#0C447C);padding:16px 24px;display:flex;align-items:center;gap:10px}
.card-title{color:#fff;font-size:14px;font-weight:700}
.card-body{padding:24px}
.icon-badge{width:32px;height:32px;border-radius:9px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.icon-badge svg{width:16px;height:16px;stroke:#fff;fill:none}
.animated-border{padding:2px;border-radius:12px;background:linear-gradient(270deg,#0C447C,#1a6bbf,#38bdf8,#0C447C);background-size:300% 300%;animation:borderFlow 4s ease-in-out infinite;will-change:background-position;transform:translateZ(0);margin-bottom:18px}
.animated-border-inner{background:#fff;border-radius:10px;padding:20px}
.group-info-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:16px}
.info-box{background:#f4f8fd;border-radius:9px;padding:14px 16px;text-align:center}
.info-box .label{font-size:11px;color:#8096b0;text-transform:uppercase;letter-spacing:.6px;margin-bottom:5px}
.info-box .value{font-size:16px;font-weight:700;color:#0C447C}
.members-list{display:flex;flex-direction:column;gap:8px}
.member-row{display:flex;align-items:center;gap:12px;padding:10px 14px;background:#f4f8fd;border-radius:8px;transition:background .2s ease}
.member-row:hover{background:#eaf1fa}
.member-avatar{width:34px;height:34px;background:#0C447C;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:13px;font-weight:700;flex-shrink:0}
.member-name{font-size:13px;font-weight:600;color:#1a2b3c}
.member-roll{font-size:11.5px;color:#8096b0}
.member-badge{margin-left:auto;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600}
.creator-badge{background:#e8f0f9;color:#0C447C}
.member-badge-plain{background:#f0f4f8;color:#5a7a9a}
.btn-primary{padding:10px 20px;background:#0C447C;color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:'Segoe UI',sans-serif;transition:background .2s ease,transform .15s ease;text-decoration:none;display:inline-block}
.btn-primary:hover{background:#185FA5}
.btn-primary:active{transform:scale(.98)}
.btn-danger{padding:8px 16px;background:#fef2f2;color:#b91c1c;border:1px solid #fecaca;border-radius:7px;font-size:12.5px;font-weight:600;cursor:pointer;font-family:'Segoe UI',sans-serif;transition:background .2s ease}
.btn-success-sm{padding:6px 14px;background:#dcfce7;color:#166534;border:1px solid #bbf7d0;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;font-family:'Segoe UI',sans-serif;transition:background .2s ease}
.btn-reject-sm{padding:6px 14px;background:#fef2f2;color:#b91c1c;border:1px solid #fecaca;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;font-family:'Segoe UI',sans-serif;transition:background .2s ease}
.open-groups-grid{display:flex;flex-direction:column;gap:12px}
.group-card-wrap{padding:2px;border-radius:11px;background:linear-gradient(270deg,#0C447C,#1a6bbf,#38bdf8,#0C447C);background-size:300% 300%;animation:borderFlow 4s ease-in-out infinite;will-change:background-position;transform:translateZ(0)}
.group-card{background:#fff;border-radius:9px;padding:16px 18px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
.group-card-info .code{font-size:13px;font-weight:700;color:#0C447C}
.group-card-info .creator{font-size:12px;color:#8096b0;margin-top:3px}
.group-card-info .slots{font-size:12px;color:#5a7a9a;margin-top:3px}
.form-group{margin-bottom:16px}
.form-group label{display:block;font-size:12px;font-weight:600;color:#0C447C;margin-bottom:6px;text-transform:uppercase;letter-spacing:.5px}
.input-wrap{padding:2px;border-radius:9px;background:#dbe4ee;transition:background .3s ease,box-shadow .3s ease}
.input-wrap:focus-within{background:linear-gradient(270deg,#0C447C,#1a6bbf,#38bdf8,#0C447C);background-size:300% 300%;animation:borderFlow 4s ease-in-out infinite;will-change:background-position;box-shadow:0 0 0 4px rgba(12,68,124,.08)}
.input-wrap input,.input-wrap textarea{width:100%;padding:10px 14px;border:none;border-radius:7px;font-size:13px;color:#1a2b3c;background:#f7fafd;outline:none;font-family:'Segoe UI',sans-serif;transition:background .2s ease}
.input-wrap input:focus,.input-wrap textarea:focus{background:#fff}
.input-wrap textarea{height:90px;resize:vertical}
.request-row{display:flex;align-items:center;gap:12px;padding:10px 14px;background:#f4f8fd;border-radius:8px;margin-bottom:8px;flex-wrap:wrap;transition:background .2s ease}
.request-row:hover{background:#eaf1fa}
.empty-state{text-align:center;padding:32px;color:#9aabbf;font-size:13px}
.empty-state svg{width:36px;height:36px;stroke:#c4d4e8;fill:none;margin:0 auto 10px;display:block}
.no-group-actions{display:flex;gap:12px;flex-wrap:wrap}
.deadline-banner{display:flex;align-items:center;gap:14px;padding:14px 18px;border-radius:10px;margin-bottom:10px;transition:box-shadow .2s ease}
.deadline-banner:hover{box-shadow:0 3px 14px rgba(0,0,0,.06)}
.deadline-icon{flex-shrink:0;width:22px;height:22px}
.deadline-icon svg{width:22px;height:22px;fill:none;stroke-width:2}
.profile-row{display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid #f0f4f8}
.profile-row:last-child{border-bottom:none}
.profile-row .p-icon{width:36px;height:36px;border-radius:11px;background:linear-gradient(135deg,#378ADD,#0C447C);display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 3px 8px rgba(12,68,124,.3)}
.profile-row .p-icon svg{width:16px;height:16px;stroke:#fff;fill:none}
.profile-row .p-icon i{width:17px;height:17px;color:#fff}
.profile-row .p-label{font-size:11px;color:#8096b0;text-transform:uppercase;letter-spacing:.4px}
.profile-row .p-value{font-size:13.5px;font-weight:600;color:#1a2b3c;margin-top:1px;display:flex;align-items:center;gap:6px}
.status-dot{width:7px;height:7px;border-radius:50%;flex-shrink:0;display:inline-block}
.view-details-btn{display:inline-flex;align-items:center;gap:6px;color:#0C447C;font-size:13px;font-weight:600;text-decoration:none;padding:8px 14px;background:#f4f8fd;border-radius:7px;transition:background .2s ease,gap .2s ease}
.view-details-btn:hover{background:#e8f0f9;gap:9px}
.view-details-btn svg{width:14px;height:14px;stroke:#0C447C;fill:none;transition:transform .2s ease}
@media(max-width:768px){
  .hamburger{display:flex}
  div.sidebar{transform:translateX(-260px)}
  div.sidebar.open{transform:translateX(0)}
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

<div class="main">
  <div class="topbar">
    <div class="topbar-left">
      <div class="avatar-circle">{{ strtoupper(substr($student->name ?? 'S', 0, 1)) }}</div>
      <div>
        <h2>Student Dashboard</h2>
        <p>Welcome, {{ $student->name }}! @if($profile)&bull; {{ $profile->department->name ?? '' }} &bull; Session {{ $profile->session->name ?? '' }} &bull; {{ $profile->shift }}@endif</p>
      </div>
    </div>
    <span class="badge badge-student">Student</span>
  </div>
  @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
  @if(session('error'))<div class="alert alert-error">{{ session('error') }}</div>@endif

  @if(isset($upcomingDeadlines) && $upcomingDeadlines->count() > 0)
  <div style="margin-bottom:18px">
    @foreach($upcomingDeadlines as $dl)
    @php
      $deadline = \Carbon\Carbon::parse($dl->upload_deadline)->endOfDay();
      $isOverdue = now()->gt($deadline);
      $daysLeft = now()->diffInDays($deadline, false);
    @endphp
    <div class="deadline-banner" style="
      {{ $isOverdue ? 'background:#fef2f2;border:1.5px solid #fecaca;' : ($daysLeft <= 1 ? 'background:#fff7ed;border:1.5px solid #fed7aa;' : 'background:#fefce8;border:1.5px solid #fde68a;') }}">
      <span class="deadline-icon" style="color:{{ $isOverdue ? '#b91c1c' : ($daysLeft <= 1 ? '#9a3412' : '#854d0e') }}">
        @if($isOverdue)
          <svg viewBox="0 0 24 24" stroke="currentColor"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        @elseif($daysLeft <= 1)
          <svg viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        @else
          <svg viewBox="0 0 24 24" stroke="currentColor"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        @endif
      </span>
      <div style="flex:1">
        <div style="font-size:13px;font-weight:700;color:{{ $isOverdue ? '#b91c1c' : ($daysLeft <= 1 ? '#9a3412' : '#854d0e') }}">
          {{ $isOverdue ? 'Deadline Passed!' : ($daysLeft == 0 ? 'Deadline is TODAY!' : 'Upcoming Deadline') }}
        </div>
        <div style="font-size:12px;color:{{ $isOverdue ? '#b91c1c' : '#78350f' }};margin-top:2px">
          <strong>{{ $dl->phase->name ?? 'Phase' }}</strong> document upload deadline:
          <strong>{{ $deadline->format('d M Y') }}</strong>
          @if(!$isOverdue)
            — <strong>{{ $daysLeft == 0 ? 'Today' : $daysLeft . ' day' . ($daysLeft > 1 ? 's' : '') . ' left'}}</strong>
          @else
            — Contact your Focal Person for late upload permission.
          @endif
        </div>
      </div>
      <a href="/student/phase-documents" style="background:{{ $isOverdue ? '#b91c1c' : '#0C447C' }};color:#fff;padding:7px 14px;border-radius:8px;font-size:12px;font-weight:600;text-decoration:none;white-space:nowrap">
        {{ $isOverdue ? 'Request Permission' : 'Upload Now' }}
      </a>
    </div>
    @endforeach
  </div>
  @endif

  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:18px;margin-bottom:24px;align-items:start">

    <!-- Group Card -->
    <div class="section-card" style="margin-bottom:0">
      <div class="card-header">
        <span class="icon-badge">
          <svg viewBox="0 0 24 24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10 12 5 2 10l10 5 10-5z"/><path d="M6 12v5c0 1.1 3 3 6 3s6-1.9 6-3v-5"/><path d="M22 10v6"/></svg>
        </span>
        <span class="card-title">My Group</span>
      </div>
      <div class="card-body">
      @if($myGroup)
        <div style="font-size:16px;font-weight:700;color:#0C447C;margin-bottom:4px">{{ $myGroup->group_code }}</div>
        <div style="font-size:13px;color:#5a7a9a;margin-bottom:6px">{{ $myGroup->project_title ?? 'No title yet' }}</div>

        <div class="profile-row">
          <span class="p-icon"><i data-lucide="users"></i></span>
          <div>
            <div class="p-label">Members</div>
            <div class="p-value">{{ $myGroup->members->count() }}/{{ $myGroup->max_members }}</div>
          </div>
        </div>
        <div class="profile-row">
          <span class="p-icon"><i data-lucide="user-check"></i></span>
          <div>
            <div class="p-label">Supervisor</div>
            <div class="p-value">{{ $myGroup->supervisor->name ?? 'Not Assigned' }}</div>
          </div>
        </div>
        <div class="profile-row" style="border-bottom:none">
          <span class="p-icon"><i data-lucide="activity"></i></span>
          <div>
            <div class="p-label">Status</div>
            <div class="p-value">
              @if($myGroup->status=='open')
                <span class="status-dot" style="background:#16a34a"></span>Open
              @elseif($myGroup->status=='locked')
                <span class="status-dot" style="background:#d97706"></span>Locked
              @elseif($myGroup->status=='proposal_submitted')
                <span class="status-dot" style="background:#7c3aed"></span>Proposal Submitted
              @elseif($myGroup->status=='supervisor_assigned')
                <span class="status-dot" style="background:#16a34a"></span>Supervisor Assigned
              @endif
            </div>
          </div>
        </div>

        <div style="margin-top:16px">
          <a href="/student/my-group" class="view-details-btn">
            View Details
            <svg viewBox="0 0 24 24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
        </div>
      @else
        <p style="color:#8096b0;font-size:13px;margin-bottom:12px">You are not in any group yet.</p>
        <a href="/student/my-group" style="background:#0C447C;color:#fff;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none">Join or Create Group</a>
      @endif
      </div>
    </div>

    <!-- Academic Profile Card -->
    <div class="section-card" style="margin-bottom:0">
      <div class="card-header">
        <span class="icon-badge">
          <svg viewBox="0 0 24 24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </span>
        <span class="card-title">Academic Profile</span>
      </div>
      <div class="card-body">
      <div class="profile-row">
        <span class="p-icon"><svg viewBox="0 0 24 24" stroke="currentColor"><path d="M22 10 12 5 2 10l10 5 10-5z"/><path d="M6 12v5c0 1.1 3 3 6 3s6-1.9 6-3v-5"/></svg></span>
        <div>
          <div class="p-label">Department</div>
          <div class="p-value">{{ $profile->department->name ?? 'N/A' }}</div>
        </div>
      </div>
      <div class="profile-row">
        <span class="p-icon"><svg viewBox="0 0 24 24" stroke="currentColor"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>
        <div>
          <div class="p-label">Session</div>
          <div class="p-value">{{ $profile->session->name ?? 'N/A' }}</div>
        </div>
      </div>
      <div class="profile-row" style="border-bottom:none">
        <span class="p-icon"><svg viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span>
        <div>
          <div class="p-label">Shift</div>
          <div class="p-value">{{ $profile->shift ?? 'N/A' }}</div>
        </div>
      </div>
      </div>
    </div>

  </div>
</div>
<style>
.notif-bar{padding:8px 12px;border-bottom:1px solid rgba(255,255,255,.1);display:block;width:100%}
.notif-btn{display:flex;align-items:center;gap:8px;background:rgba(255,255,255,.08);border:none;border-radius:8px;padding:8px 12px;color:rgba(255,255,255,.8);font-size:12px;cursor:pointer;width:100%;font-family:"Segoe UI",sans-serif;transition:background .2s ease}
.notif-btn:hover{background:rgba(255,255,255,.15);color:#fff}
.notif-btn svg{width:15px;height:15px;min-width:15px;stroke:rgba(255,255,255,.8)}
.notif-label{flex:1;text-align:left}
.notif-badge{background:#ef4444;color:#fff;font-size:10px;font-weight:700;padding:2px 6px;border-radius:10px;min-width:18px;text-align:center}
.notif-panel{max-height:0;overflow:hidden;transition:max-height .3s ease;background:#0a3a6b;border-bottom:1px solid rgba(255,255,255,.1)}
.notif-panel.open{max-height:320px}
.notif-panel-header{display:flex;justify-content:space-between;align-items:center;padding:10px 14px;border-bottom:1px solid rgba(255,255,255,.08)}
.notif-panel-header span{color:#fff;font-size:12px;font-weight:600}
.mark-all-btn{background:none;border:1px solid rgba(255,255,255,.25);color:rgba(255,255,255,.7);font-size:10px;padding:3px 8px;border-radius:5px;cursor:pointer;font-family:"Segoe UI",sans-serif;transition:background .2s ease,color .2s ease}
.mark-all-btn:hover{background:rgba(255,255,255,.1);color:#fff}
.notif-list{max-height:260px;overflow-y:auto;scrollbar-width:none}
.notif-list::-webkit-scrollbar{display:none}
.notif-item{padding:10px 14px;border-bottom:1px solid rgba(255,255,255,.06);cursor:pointer;transition:background .2s ease}
.notif-item:hover{background:rgba(255,255,255,.06)}
.notif-item.unread{border-left:3px solid #3b82f6}
.notif-item.read{border-left:3px solid transparent;opacity:.7}
.notif-item-title{color:#fff;font-size:12px;font-weight:600;margin-bottom:3px}
.notif-item-msg{color:rgba(255,255,255,.6);font-size:11px;line-height:1.4}
.notif-item-time{color:rgba(255,255,255,.35);font-size:10px;margin-top:4px}
.notif-empty{color:rgba(255,255,255,.4);font-size:12px;text-align:center;padding:24px 0}
</style>
<script src="https://unpkg.com/lucide@latest"></script>
<script src="/js/ajax.js"></script>
<script>
function toggleNotifications() {
    const panel = document.getElementById('notif-panel');
    panel.classList.toggle('open');
    if (panel.classList.contains('open')) loadNotifications();
}

async function loadNotifications() {
    const token = localStorage.getItem('api_token');
    try {
        const res = await fetch('/api/student/notifications', {
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
    await fetch('/api/student/notifications/' + id + '/read', {
        method: 'POST',
        headers: { 'Authorization': 'Bearer ' + token,'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]') ? document.querySelector('meta[name=csrf-token]').content : '' }
    });
    if (link) window.location.href = link;
    else loadNotifications();
}

async function markAllRead() {
    const token = localStorage.getItem('api_token');
    await fetch('/api/student/notifications/read-all',{
        method: 'POST',
        headers: { 'Authorization': 'Bearer ' + token,'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]') ? document.querySelector('meta[name=csrf-token]').content : '' }
    });
    loadNotifications();
}

document.addEventListener('DOMContentLoaded', async function() {
    if (typeof lucide !== 'undefined') lucide.createIcons();
    const token = localStorage.getItem('api_token');
    if (!token) return;
    try {
        const res = await fetch('/api/student/notifications', {
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
</script>
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
