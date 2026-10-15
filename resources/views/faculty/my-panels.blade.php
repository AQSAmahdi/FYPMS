<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>My Panel - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
.main{margin-left:260px;padding:28px 32px;min-height:100vh}
.topbar{background:linear-gradient(135deg,#378ADD,#0C447C);border-radius:12px;padding:22px 28px;margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 4px 16px rgba(12,68,124,.18)}
.topbar-left{display:flex;align-items:center;gap:16px}
.topbar-icon{width:46px;height:46px;border-radius:12px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.topbar h2{color:#fff;font-size:18px;font-weight:700}
.topbar p{color:rgba(255,255,255,.85);font-size:12px;margin-top:2px}
.badge-role{background:rgba(255,255,255,.2);color:#fff;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600}
.panel-card{background:#fff;border-radius:12px;padding:22px;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9;margin-bottom:20px}
.panel-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:6px}
.panel-name{font-size:16px;font-weight:700;color:#0C447C}
.panel-desc{font-size:13px;color:#8096b0;margin-bottom:16px}
.badge-active{background:#e8f5e9;color:#2e7d32;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:600}
.badge-inactive{background:#fce4e4;color:#c62828;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:600}
.members-label{font-size:11px;font-weight:700;color:#8096b0;text-transform:uppercase;letter-spacing:.6px;margin-bottom:12px}
.members-grid{display:flex;flex-wrap:wrap;gap:12px}
.member-card{display:flex;align-items:center;gap:12px;background:#f4f8fd;border-radius:10px;padding:12px 16px;border:1.5px solid #e8f0f9;min-width:200px}
.member-card.me{border-color:#0C447C;background:#e8f0f9}
.avatar{width:38px;height:38px;border-radius:50%;background:#0C447C;color:#fff;display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:700;flex-shrink:0}
.member-card.me .avatar{background:#185FA5}
.member-name{font-size:13px;font-weight:600;color:#1e3a5f}
.member-role{font-size:11px;color:#8096b0;margin-top:2px}
.you-badge{background:#0C447C;color:#fff;font-size:9px;font-weight:700;padding:2px 6px;border-radius:10px;margin-left:6px;vertical-align:middle}
.empty{text-align:center;padding:60px;color:#8096b0;font-size:14px;background:#fff;border-radius:12px;border:1px solid #e8f0f9}
@media(max-width:768px){.main{margin-left:0;padding:16px}.members-grid{flex-direction:column}}
</style>
</head>
<body>
@include('faculty.partials.sidebar')
<div class="main">
  <div class="topbar">
    <div class="topbar-left">
      <div class="topbar-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
      <div><h2>My Panel</h2><p>Evaluation panels you are assigned to</p></div>
    </div>
    <span class="badge-role">Faculty</span>
  </div>

  @if($myPanels->count() > 0)
    @foreach($myPanels as $panel)
    <div class="panel-card">
      <div class="panel-header">
        <div class="panel-name">{{ $panel->name }}</div>
        <span class="{{ $panel->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
          {{ ucfirst($panel->status) }}
        </span>
      </div>
      @if($panel->description)
      <div class="panel-desc">{{ $panel->description }}</div>
      @else
      <div style="margin-bottom:16px"></div>
      @endif

      <div class="members-label">Panel Members ({{ $panel->facultys->count() }})</div>
      <div class="members-grid">
        @foreach($panel->facultys as $member)
        <div class="member-card {{ $member->id === auth()->id() ? 'me' : '' }}">
          <div class="avatar">{{ strtoupper(substr($member->name, 0, 1)) }}</div>
          <div>
            <div class="member-name">
              {{ $member->name }}
              @if($member->id === auth()->id())
                <span class="you-badge">YOU</span>
              @endif
            </div>
            <div class="member-role">{{ ucfirst($member->role) }}</div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
    @endforeach
  @else
    <div class="empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="#c0cfe0" stroke-width="1.5" width="48" height="48" style="margin-bottom:12px"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      <p>You are not assigned to any evaluation panel yet.</p>
    </div>
  @endif
</div>
</body>
</html>
