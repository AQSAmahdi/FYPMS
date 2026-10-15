<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Phase Results - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
.main{margin-left:260px;padding:28px 32px;min-height:100vh}
.topbar{background:#0C447C;border-radius:14px;padding:20px 26px;margin-bottom:24px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 4px 14px rgba(12,68,124,.18)}
.topbar h2{color:#fff;font-size:19px;font-weight:700}
.topbar p{color:rgba(255,255,255,.9)!important;font-size:12.5px;margin-top:3px}
.badge-role{background:rgba(255,255,255,.18);color:#fff;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600}
.alert{padding:12px 18px;border-radius:9px;margin-bottom:18px;font-size:13px;font-weight:500}
.alert-success{background:#dcfce7;color:#166534;border:1px solid #bbf7d0}
.alert-error{background:#fef2f2;color:#b91c1c;border:1px solid #fecaca}
.result-card{border-radius:12px;padding:20px 22px;margin-bottom:16px;transition:all .2s}
.result-card:hover{border-color:#c7d8ec;box-shadow:0 3px 14px rgba(12,68,124,.1)}
.result-top{display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:10px;margin-bottom:14px}
.result-title{font-size:15px;font-weight:700;color:#0C447C;display:flex;align-items:center;gap:8px}
.result-title::before{content:"";width:8px;height:8px;border-radius:50%;background:#0C447C;display:inline-block;flex-shrink:0}
.result-meta{font-size:12px;color:#8096b0;margin-top:3px}
.badge-pass{background:#dcfce7;color:#166534;padding:4px 14px;border-radius:20px;font-size:12px;font-weight:700}
.badge-fail{background:#fef2f2;color:#b91c1c;padding:4px 14px;border-radius:20px;font-size:12px;font-weight:700}
.marks-box{background:#f0f6ff;border:1px solid #e2ecf9;border-radius:10px;padding:10px 16px;font-size:13px;color:#1a2b3c;margin-bottom:14px;display:inline-block}
.assign-row{display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-top:10px}
.assign-row select{padding:8px 12px;border:1.5px solid #e8f0f9;border-radius:8px;font-size:13px;background:#f7fafd;outline:none;font-family:'Segoe UI',sans-serif;min-width:200px}
.btn-assign{padding:10px 20px;background:#0C447C;color:#fff;border:none;border-radius:9px;font-size:13px;font-weight:600;cursor:pointer;font-family:'Segoe UI',sans-serif;transition:all .2s}
.btn-assign:hover{background:#185FA5}
.btn-retry{padding:10px 20px;background:#fff;color:#92600a;border:1.5px solid #f0dca0;border-radius:9px;font-size:13px;font-weight:600;cursor:pointer;font-family:'Segoe UI',sans-serif;transition:all .2s}
.btn-retry:hover{background:#fff8e1}
.supervisor-tag{background:#e8f5e9;color:#2e7d32;padding:9px 16px;border-radius:9px;font-size:13px;font-weight:600;display:inline-flex;align-items:center;gap:6px}
.empty-state{text-align:center;padding:50px;color:#9aabbf;font-size:13px;background:#fff;border-radius:12px;border:1px solid #e8f0f9}
.group-wrap{background:#fff;border-radius:14px;margin-bottom:14px;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9;overflow:hidden}
.group-wrap-header{background:#dce8f7;color:#0C447C;padding:14px 22px;font-size:14.5px;font-weight:700;display:flex;align-items:center;gap:8px;cursor:pointer;user-select:none;transition:background .15s;border-left:4px solid #0C447C;border-bottom:1px solid #c3d8ef}
.group-wrap-header:hover{background:#eef4fb}
.group-wrap-header .count{background:#e6eef8;color:#0C447C;padding:2px 10px;border-radius:20px;font-size:11px;font-weight:600}
.group-wrap-header .chevron{margin-left:auto;transition:transform .2s ease;font-size:12px;color:#6b8bb3}
.group-wrap.open .chevron{transform:rotate(180deg)}
.group-wrap-body{padding:0 22px;max-height:0;overflow:hidden;transition:max-height .25s ease, padding .25s ease;background:#eef2f7}
.group-wrap.open .group-wrap-body{padding:18px 22px;max-height:3000px}
.result-card{margin-bottom:14px;border-radius:10px;background:#fff;border:1px solid #d7e6f7;box-shadow:0 2px 8px rgba(12,68,124,.08)}
.result-card:last-child{margin-bottom:0}
@media(max-width:768px){.main{margin-left:0;padding:16px;padding-top:62px}}
</style>
</head>
<body>
@include('focal.partials.sidebar')
<div class="main">
  <div class="topbar">
    <div><h2>Phase Results</h2><p>Pass/Fail results for completed evaluations</p></div>
    <span class="badge-role">Focal Person</span>
  </div>

  @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
  @if($errors->any())<div class="alert alert-error">{{ $errors->first() }}</div>@endif

  @forelse($schedules->groupBy('group_id') as $groupId => $groupSchedules)
    @php $group = $groupSchedules->first()->group; @endphp
    <div class="group-wrap" id="group-{{ $groupId }}">
      <div class="group-wrap-header" onclick="toggleGroup('{{ $groupId }}')">
        {{ $group->group_code ?? '—' }}
        <span class="count">{{ $groupSchedules->count() }} phase{{ $groupSchedules->count() > 1 ? 's' : '' }}</span>
        <span class="chevron">▼</span>
      </div>
      <div class="group-wrap-body">
      @foreach($groupSchedules as $schedule)
    <div class="result-card">
      <div class="result-top">
        <div>
          <div class="result-title">{{ $schedule->phase->name ?? 'Phase' }}</div>
          <div class="result-meta">Attempt #{{ $schedule->attempt_number }} &nbsp;|&nbsp; Evaluated on {{ \Carbon\Carbon::parse($schedule->schedule_date)->format('d M Y') }}</div>
        </div>
        @if($schedule->result == 'pass')
          <span class="badge-pass">Pass</span>
        @else
          <span class="badge-fail">Fail</span>
        @endif
      </div>

      <div class="marks-box">Average Score: <strong>{{ $schedule->average_marks }}%</strong></div>

      @if($schedule->result == 'pass')
        @if($group && $group->supervisor_id)
          <div class="assign-row">
            <div class="supervisor-tag">Supervisor: {{ $group->supervisor->name ?? '-' }}</div>
            <button type="button" class="btn-retry" onclick="toggleChange({{ $schedule->id }})">Change Supervisor</button>
          </div>
          <div id="change-{{ $schedule->id }}" style="display:none;margin-top:10px">
            <form method="POST" action="/focal/phase-results/{{ $schedule->id }}/assign-supervisor" class="assign-row">
              @csrf
              <select name="supervisor_id" required>
                <option value="">Select Supervisor</option>
                @foreach($supervisors as $sup)
                  <option value="{{ $sup->id }}" {{ $group->supervisor_id==$sup->id?'selected':'' }}>{{ $sup->name }}</option>
                @endforeach
              </select>
              <button type="submit" class="btn-assign">Update</button>
            </form>
          </div>
        @else
          <form method="POST" action="/focal/phase-results/{{ $schedule->id }}/assign-supervisor" class="assign-row">
            @csrf
            <select name="supervisor_id" required>
              <option value="">Select Supervisor</option>
              @foreach($supervisors as $sup)
                <option value="{{ $sup->id }}">{{ $sup->name }}</option>
              @endforeach
            </select>
            <button type="submit" class="btn-assign">Assign Supervisor</button>
          </form>
        @endif
      @else
        <form method="POST" action="/focal/phase-results/{{ $schedule->id }}/retry" class="assign-row">
          @csrf
          <input type="date" name="schedule_date" required style="padding:8px 12px;border:1.5px solid #e8f0f9;border-radius:8px;font-size:13px">
          <input type="time" name="schedule_time" required style="padding:8px 12px;border:1.5px solid #e8f0f9;border-radius:8px;font-size:13px">
          <input type="text" name="room" placeholder="Room" style="padding:8px 12px;border:1.5px solid #e8f0f9;border-radius:8px;font-size:13px;width:120px">
          <button type="submit" class="btn-retry">Schedule Retry</button>
        </form>
      @endif
    </div>
      @endforeach
      </div>
    </div>
  @empty
    <div class="empty-state">No completed evaluations yet.</div>
  @endforelse
</div>
<script>
function toggleGroup(id) {
  const el = document.getElementById('group-' + id);
  if (el) el.classList.toggle('open');
}
function toggleChange(id) {
  const el = document.getElementById('change-' + id);
  el.style.display = el.style.display === 'none' ? 'block' : 'none';
}
</script>
</body>
</html>
