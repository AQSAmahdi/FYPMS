<!DOCTYPE html>
<html lang="en" style="background:linear-gradient(90deg,#0C447C 260px,#f0f4f8 260px)">
<head>
<style>html,body{background:#f0f4f8}</style>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Progress - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
.main{margin-left:260px;padding:28px 32px;min-height:100vh}
.topbar{background:#fff;border-radius:12px;padding:16px 22px;margin-bottom:24px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9}
.topbar h2{color:#0C447C;font-size:18px;font-weight:700}
.topbar p{color:#8096b0;font-size:12px;margin-top:2px}
.badge-role{background:#e8f0f9;color:#0C447C;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600}
.phase-block{background:#fff;border-radius:12px;padding:22px;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9;margin-bottom:20px;transition:box-shadow .25s ease,transform .25s ease}
.phase-block:hover{box-shadow:0 6px 22px rgba(12,68,124,.12);transform:translateY(-2px)}
.phase-title{font-size:15px;font-weight:700;color:#0C447C;margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid #e8f0f9;display:flex;align-items:center;gap:10px}
.card-header{background:linear-gradient(135deg,#378ADD,#0C447C);padding:16px 24px;display:flex;align-items:center;gap:10px}
.card-header-light{background:#f4f8fc;padding:16px 24px;display:flex;align-items:center;gap:10px;border-bottom:1px solid #e8f0f9}
.icon-badge-light{width:32px;height:32px;border-radius:9px;background:#e3edf9;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.icon-badge-light svg{width:16px;height:16px;stroke:#0C447C;fill:none}
.card-title-light{color:#0C447C;font-size:14px;font-weight:700}
.card-title{color:#fff;font-size:14px;font-weight:700}
.icon-badge{width:32px;height:32px;border-radius:9px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.icon-badge svg{width:16px;height:16px;stroke:#fff;fill:none}
.card-shell{background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9;margin-bottom:20px;transition:box-shadow .25s ease,transform .25s ease}
.card-shell:hover{box-shadow:0 6px 22px rgba(12,68,124,.12);transform:translateY(-2px)}
.card-shell .card-body{padding:24px}
.attempt-row{display:flex;justify-content:space-between;align-items:center;border:1.5px solid #e8f0f9;border-left:3px solid #378ADD;border-radius:10px;padding:14px 18px;margin-bottom:10px}
.attempt-row:last-child{margin-bottom:0}
.attempt-left{}
.attempt-num{font-size:13px;font-weight:700;color:#1e3a5f;margin-bottom:4px}
.attempt-date{font-size:12px;color:#8096b0}
.attempt-right{display:flex;align-items:center;gap:12px}
.badge-pass{background:#dcfce7;color:#166534;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:700}
.badge-fail{background:#fce4e4;color:#c62828;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:700}
.badge-pending{background:#f4f8fd;color:#8096b0;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600}
.badge-latest{background:#e0e7ff;color:#3730a3;padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700}
.avg-box{text-align:right}
.avg-val{font-size:18px;font-weight:700;color:#0C447C}
.avg-lbl{font-size:10px;color:#8096b0;text-transform:uppercase;letter-spacing:.5px}
.empty{text-align:center;padding:60px;color:#8096b0;font-size:14px;background:#fff;border-radius:12px;border:1px solid #e8f0f9}
.no-group{background:#fff3cd;color:#856404;padding:16px 20px;border-radius:10px;border:1px solid #fde68a;font-size:14px;margin-bottom:20px}
@media(max-width:768px){
    .main{margin-left:0;padding:16px}
}
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
      <h2 style="font-size:26px;font-weight:800;color:#0C447C;letter-spacing:-0.3px">My Progress</h2>
      <p>Track your FYP phase attempts and results</p>
    </div>
    <span class="badge-role">Student</span>
  </div>

  {{-- PROGRESS TIMELINE --}}
  @if($myGroup)
  <div class="card-shell">
    <div class="card-header">
      <span class="icon-badge">
        <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
      </span>
      <span class="card-title">FYP Journey</span>
    </div>
    <div class="card-body">
    <div style="display:flex;align-items:center;gap:0;overflow-x:auto;padding-bottom:8px">

      {{-- Step 1: Group Formed --}}
      @php $groupOk = true; @endphp
      <div style="display:flex;flex-direction:column;align-items:center;min-width:90px">
        <div style="width:38px;height:38px;border-radius:50%;background:{{ $groupOk ? '#0C447C' : '#e8f0f9' }};display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;font-weight:700;border:3px solid {{ $groupOk ? '#0C447C' : '#c0cfe0' }}">✓</div>
        <div style="font-size:11px;font-weight:600;color:{{ $groupOk ? '#0C447C' : '#8096b0' }};margin-top:8px;text-align:center">Group<br>Formed</div>
      </div>

      {{-- Connector --}}
      <div style="flex:1;height:3px;background:{{ isset($proposal) ? '#0C447C' : '#e8f0f9' }};min-width:30px;margin-bottom:18px"></div>

      {{-- Step 2: Proposal --}}
      @php
        $propStatus = $proposal->status ?? null;
        $propDone = $propStatus === 'approved';
        $propPending = $propStatus === 'pending';
        $propColor = $propDone ? '#0C447C' : ($propPending ? '#92400e' : '#e8f0f9');
        $propBg = $propDone ? '#0C447C' : ($propPending ? '#fef9c3' : '#e8f0f9');
        $propIcon = $propDone ? '✓' : ($propPending ? '⏳' : '○');
        $propTextColor = $propDone ? '#0C447C' : ($propPending ? '#92400e' : '#8096b0');
      @endphp
      <div style="display:flex;flex-direction:column;align-items:center;min-width:90px">
        <div style="width:38px;height:38px;border-radius:50%;background:{{ $propBg }};display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:700;border:3px solid {{ $propColor }}">{{ $propIcon }}</div>
        <div style="font-size:11px;font-weight:600;color:{{ $propTextColor }};margin-top:8px;text-align:center">Proposal<br>{{ $propDone ? 'Approved' : ($propPending ? 'Pending' : 'Not Submitted') }}</div>
      </div>

      {{-- Phase steps --}}
      @foreach($schedules as $phaseId => $attempts)
      @php
        $phase = $attempts->first()->phase;
        $lastAttempt = $attempts->last();
        $phaseDone = $lastAttempt->result === 'pass';
        $phaseFail = $lastAttempt->result === 'fail';
        $phaseColor = $phaseDone ? '#166534' : ($phaseFail ? '#c62828' : '#0C447C');
        $phaseBg = $phaseDone ? '#dcfce7' : ($phaseFail ? '#fce4e4' : '#e8f0f9');
        $phaseIcon = $phaseDone ? '✓' : ($phaseFail ? '✗' : '…');
        $phaseTextColor = $phaseDone ? '#166534' : ($phaseFail ? '#c62828' : '#8096b0');
        $connectorColor = $phaseDone ? '#166534' : '#e8f0f9';
      @endphp
      <div style="flex:1;height:3px;background:{{ $connectorColor }};min-width:30px;margin-bottom:18px"></div>
      <div style="display:flex;flex-direction:column;align-items:center;min-width:90px">
        <div style="width:38px;height:38px;border-radius:50%;background:{{ $phaseBg }};display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:700;border:3px solid {{ $phaseColor }};color:{{ $phaseColor }}">{{ $phaseIcon }}</div>
        <div style="font-size:11px;font-weight:600;color:{{ $phaseTextColor }};margin-top:8px;text-align:center">{{ $phase->name ?? 'Phase' }}<br>{{ $phaseDone ? 'Passed' : ($phaseFail ? 'Failed' : 'Scheduled') }}</div>
      </div>
      @endforeach

    </div>
    </div>
  </div>
  @endif
  {{-- END TIMELINE --}}

  @if($proposal)
  <div class="card-shell">
    <div class="card-header-light">
      <span class="icon-badge-light">
        <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      </span>
      <span class="card-title-light">My Proposal</span>
    </div>
    <div class="card-body" style="display:flex;justify-content:space-between;align-items:center;padding-bottom:26px">
    <div>
      <div style="font-size:16px;font-weight:700;color:#1e3a5f">{{ $proposal->title }}</div>
      @if($proposal->description)
      <div style="font-size:12px;color:#8096b0;margin-top:3px">{{ $proposal->description }}</div>
      @endif
    </div>
    <div style="text-align:right">
      @if($proposal->status=='approved')
        <span style="background:#dcfce7;color:#166534;padding:6px 16px;border-radius:20px;font-size:12px;font-weight:700">✓ Approved</span>
      @elseif($proposal->status=='rejected')
        <span style="background:#fce4e4;color:#c62828;padding:6px 16px;border-radius:20px;font-size:12px;font-weight:700">✗ Rejected</span>
      @else
        <span style="background:#fef9c3;color:#92400e;padding:6px 16px;border-radius:20px;font-size:12px;font-weight:700">⏳ Pending Review</span>
      @endif
      @if($proposal->file_path)
      <div style="margin-top:8px"><a href="/student/proposal/document/{{ $proposal->id }}" style="font-size:12px;color:#0C447C;font-weight:600;text-decoration:underline">📎 Download</a></div>
      @endif
    </div>
  </div>
  </div>
  @endif

  @if(!$myGroup)
    <div class="no-group">⚠ You are not part of any FYP group yet. Join or create a group to track your progress.</div>
  @elseif($schedules->isEmpty())
    <div class="empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="#c0cfe0" stroke-width="1.5" width="48" height="48" style="margin-bottom:12px"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
      <p style="font-weight:600;margin-bottom:6px">No phase evaluations yet</p>
      <p style="font-size:13px">Your phase attempt history will appear here once evaluations are scheduled.</p>
    </div>
  @else
    @foreach($schedules as $phaseId => $attempts)
    @php $phase = $attempts->first()->phase; @endphp
    <div class="phase-block" style="padding:0;overflow:hidden">
      <div class="card-header-light">
        <span class="icon-badge-light">
          <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        </span>
        <span class="card-title-light">{{ $phase->name ?? 'Phase' }}</span>
        <span style="font-size:12px;color:#5a8fc7;font-weight:400;margin-left:auto">({{ $attempts->count() }} attempt{{ $attempts->count() > 1 ? 's' : '' }})</span>
      </div>
      <div style="padding:22px">

      @foreach($attempts as $attempt)
      <div class="attempt-row" style="{{ $loop->last && $attempts->count() > 1 ? 'border-color:#0C447C' : '' }}">
        <div class="attempt-left">
          <div class="attempt-num">
            Attempt #{{ $attempt->attempt_number }}
            @if($loop->last && $attempts->count() > 1)
              <span class="badge-latest">Latest</span>
            @endif
          </div>
          <div class="attempt-date">
            {{ \Carbon\Carbon::parse($attempt->schedule_date)->format('d M Y') }}
            @if($attempt->room)
              &bull; Room: {{ $attempt->room }}
            @endif
          </div>
          @if($attempt->result_remarks && $attempt->result !== 'pending')
          <div style="font-size:12px;color:#5a7a9a;margin-top:4px">{{ $attempt->result_remarks }}</div>
          @endif
        </div>
        <div class="attempt-right">
          @if($attempt->average_marks !== null)
          <div class="avg-box">
            <div class="avg-val">{{ number_format($attempt->average_marks, 1) }}%</div>
            <div class="avg-lbl">Average</div>
          </div>
          @endif
          @if($attempt->result === 'pass')
            <span class="badge-pass">✓ Pass</span>
          @elseif($attempt->result === 'fail')
            <span class="badge-fail">✗ Fail</span>
          @else
            <span class="badge-pending">Pending</span>
          @endif
        </div>
      </div>
      @endforeach
    </div>
    </div>
    @endforeach
  @endif
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
