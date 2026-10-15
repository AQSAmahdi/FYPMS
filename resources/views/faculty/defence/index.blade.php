<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Defence Evaluation - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
.main{margin-left:260px;padding:28px 32px}
.topbar{background:linear-gradient(135deg,#378ADD,#0C447C);border-radius:12px;padding:22px 28px;margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 4px 16px rgba(12,68,124,.18)}
.topbar-left{display:flex;align-items:center;gap:16px}
.topbar-icon{width:46px;height:46px;border-radius:12px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0}
.topbar h2{color:#fff;font-size:18px;font-weight:700}
.topbar p{color:rgba(255,255,255,.85);font-size:12px;margin-top:2px}
.badge-role{background:rgba(255,255,255,.2);color:#fff;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600}
.card{background:#fff;border-radius:12px;padding:22px;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9;margin-bottom:20px}
.card h3{color:#0C447C;font-size:15px;font-weight:700;margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid #e8f0f9}
.schedule-item{border:1.5px solid #e8f0f9;border-radius:10px;padding:18px;margin-bottom:14px;transition:all .2s}
.schedule-item:hover{border-color:#0C447C;box-shadow:0 2px 12px rgba(12,68,124,.08)}
.schedule-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px}
.group-name{font-size:15px;font-weight:700;color:#0C447C}
.group-meta{font-size:12px;color:#8096b0;margin-top:3px}
.badge-scheduled{background:#fef9c3;color:#854d0e;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
.badge-completed{background:#dcfce7;color:#166534;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
.badge-cancelled{background:#fef2f2;color:#b91c1c;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
.info-row{display:flex;gap:12px;flex-wrap:wrap;margin-bottom:12px}
.info-box{background:#f4f8fd;border-radius:8px;padding:8px 14px;flex:1;min-width:120px}
.info-box .lbl{font-size:10px;color:#8096b0;text-transform:uppercase;letter-spacing:.5px;margin-bottom:3px}
.info-box .val{font-size:13px;font-weight:600;color:#0C447C}
.students-list{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:12px}
.student-tag{background:#e8f0f9;color:#0C447C;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:500}
.btn-evaluate{background:#0C447C;color:#fff;border:none;padding:9px 22px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;text-decoration:none;display:inline-block}
.btn-evaluate:hover{background:#185FA5}
.btn-done{background:#dcfce7;color:#166534;border:none;padding:9px 22px;border-radius:8px;font-size:13px;font-weight:600;cursor:default;display:inline-block}
.empty{text-align:center;padding:40px;color:#8096b0;font-size:14px}
.alert-success{background:#dcfce7;color:#166534;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;border:1px solid #bbf7d0}
@media(max-width:768px){
  .main{margin-left:0;padding:16px}
  .topbar{flex-direction:column;align-items:flex-start;gap:8px}
  .info-row{flex-direction:column}
}
</style>
</head>
<body>
@include('faculty.partials.sidebar')

<div class="main">
  <div class="topbar">
    <div class="topbar-left">
      <div class="topbar-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 1.5 2.5 3 6 3s6-1.5 6-3v-5"/></svg></div>
      <div>
        <h2>Defence Evaluation</h2>
        <p>Your assigned defence schedules</p>
      </div>
    </div>
    <span class="badge-role">Faculty</span>
  </div>

  @if(session('success'))
    <div class="alert-success">✓ {{ session('success') }}</div>
  @endif

  <div class="card">
    <h3 style="display:flex;align-items:center;gap:8px"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#0C447C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 1.5 2.5 3 6 3s6-1.5 6-3v-5"/></svg>Assigned Defences ({{ $schedules->count() }})</h3>

    @if($schedules->count() > 0)
      @foreach($schedules as $schedule)
      <div class="schedule-item">
        <div class="schedule-header">
          <div>
            <div class="group-name">{{ $schedule->group->group_code ?? 'N/A' }}</div>
            <div class="group-meta">
              {{ $schedule->group->department->name ?? 'N/A' }}
              @if($schedule->group->project_title)
                &bull; {{ Str::limit($schedule->group->project_title, 50) }}
              @endif
            </div>
          </div>
          <span class="badge-{{ $schedule->status }}">{{ ucfirst($schedule->status) }}</span>
        </div>

        <div class="info-row">
          <div class="info-box">
            <div class="lbl">Date</div>
            <div class="val">{{ \Carbon\Carbon::parse($schedule->defence_date)->format('d M Y') }}</div>
          </div>
          <div class="info-box">
            <div class="lbl">Time</div>
            <div class="val">{{ \Carbon\Carbon::parse($schedule->defence_time)->format('h:i A') }}</div>
          </div>
          <div class="info-box">
            <div class="lbl">Room</div>
            <div class="val">{{ $schedule->room ?? '—' }}</div>
          </div>
          <div class="info-box">
            <div class="lbl">Panel</div>
            <div class="val">{{ $schedule->panel->name ?? '—' }}</div>
          </div>
        </div>

        <div class="students-list">
          @foreach($schedule->group->members ?? [] as $member)
            <span class="student-tag">👤 {{ $member->user->name ?? 'N/A' }}</span>
          @endforeach
        </div>

        @if($schedule->status === 'completed')
          <span class="btn-done">✓ Evaluation Done</span>
        @else
          <a href="{{ route('faculty.defence.evaluate', $schedule->id) }}" class="btn-evaluate">
            📝 Evaluate
          </a>
        @endif
      </div>
      @endforeach
    @else
      <div class="empty" style="display:flex;align-items:center;justify-content:center;gap:8px"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9aabbf" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 8l9 6 9-6"/></svg>No defence schedules assigned to you yet.</div>
    @endif
  </div>
</div>

<script>
function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('overlay')?.classList.toggle('show');
}
</script>
</body>
</html>
