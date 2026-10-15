<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Attendance Summary - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
@keyframes borderFlow{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
.main{margin-left:260px;flex:1;padding:28px 32px}
.topbar{background:#0C447C;border-radius:14px;padding:20px 26px;margin-bottom:24px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 4px 14px rgba(12,68,124,.18)}
.topbar h2{color:#fff;font-size:19px;font-weight:700}
.topbar p{color:rgba(255,255,255,.75);font-size:12.5px;margin-top:3px}
.badge-role{background:rgba(255,255,255,.18);color:#fff;padding:5px 15px;border-radius:20px;font-size:12px;font-weight:600}

.filter-card{background:#fff;border-radius:12px;padding:20px 24px;margin-bottom:20px;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9}
.filter-card h3{color:#0C447C;font-size:12px;font-weight:700;margin-bottom:14px;text-transform:uppercase;letter-spacing:1px}
.filter-row{display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap}
.filter-group{display:flex;flex-direction:column;gap:6px;flex:1;min-width:160px}
.filter-group label{font-size:11px;font-weight:600;color:#5a7a9a;text-transform:uppercase;letter-spacing:.6px}
.input-animate{position:relative;border-radius:9px;padding:2px;background:linear-gradient(270deg,#0C447C,#1a6bbf,#38bdf8,#0C447C);background-size:300% 300%;animation:borderFlow 2.5s ease infinite}
.input-animate select,.input-animate input{width:100%;padding:10px 13px;border:none;border-radius:7px;font-size:13px;color:#1a2b3c;background:#fff;outline:none;font-family:'Segoe UI',sans-serif}
.btn-apply{padding:11px 22px;background:#0C447C;color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:'Segoe UI',sans-serif}
.btn-apply:hover{background:#185FA5}
.btn-reset{padding:11px 16px;background:#f4f7fb;color:#5a7a9a;border:1.5px solid #d5e3f0;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;font-family:'Segoe UI',sans-serif;text-decoration:none}
.btn-reset:hover{background:#e8f0f9;color:#0C447C}

.stats-bar{border-radius:12px;padding:12px 20px;margin-bottom:22px;display:flex;background:#0C447C}
.stat-item{text-align:center;flex:1}
.stat-item+.stat-item{border-left:1px solid rgba(255,255,255,.15)}
.stat-num{font-size:22px;font-weight:700;color:#fff;line-height:1}
.stat-label{font-size:11px;color:rgba(255,255,255,.55);margin-top:4px;text-transform:uppercase;letter-spacing:.8px}

.group-card{background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9;margin-bottom:18px}
.group-head{padding:16px 20px;background:#f7fafd;border-bottom:1px solid #e8f0f9;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px}
.group-head-left b{color:#0C447C;font-size:14.5px}
.group-head-left span{display:block;color:#7a96b0;font-size:12px;margin-top:2px}
.group-head-right{display:flex;gap:8px;flex-wrap:wrap}
.chip{background:#e8f0f9;color:#0C447C;padding:4px 12px;border-radius:14px;font-size:11px;font-weight:600}

table{width:100%;border-collapse:collapse}
thead th{padding:10px 16px;text-align:left;font-size:11px;font-weight:600;color:#7a96b0;text-transform:uppercase;letter-spacing:.5px;background:#fff;border-bottom:1px solid #f0f5fb}
tbody td{padding:12px 16px;font-size:13px;color:#1a2b3c;border-bottom:1px solid #f4f8fb}
.stud-name{font-weight:600;color:#1e3a5f}
.stud-email{font-size:11.5px;color:#8096b0}
.pct{padding:4px 12px;border-radius:14px;font-size:12px;font-weight:700;display:inline-block}
.pct-good{background:#dcfce7;color:#166534}
.pct-mid{background:#fef9c3;color:#854d0e}
.pct-low{background:#fce4e4;color:#c62828}
.pct-none{background:#f4f8fd;color:#8096b0}
.btn-edit{background:#e8f0f9;color:#0C447C;border:none;padding:6px 14px;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer}
.btn-edit:hover{background:#d5e3f0}

.meeting-panel{display:none;background:#f7fafd;padding:14px 20px}
.meeting-panel.show{display:block}
.meeting-row{display:flex;align-items:center;gap:10px;background:#fff;border:1px solid #e8f0f9;border-radius:8px;padding:8px 12px;margin-bottom:8px;flex-wrap:wrap}
.meeting-row:last-child{margin-bottom:0}
.meeting-date{font-size:12.5px;font-weight:600;color:#1e3a5f;min-width:130px}
.meeting-row select{padding:6px 10px;border:1px solid #d5e3f0;border-radius:6px;font-size:12.5px;color:#1a2b3c}
.meeting-row input[type=text]{flex:1;min-width:140px;padding:6px 10px;border:1px solid #d5e3f0;border-radius:6px;font-size:12.5px}
.btn-save{background:#0C447C;color:#fff;border:none;padding:6px 14px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer}
.btn-save:hover{background:#185FA5}
.save-msg{font-size:11.5px;font-weight:600;margin-left:6px}

.empty{text-align:center;padding:60px;color:#8096b0;font-size:14px;background:#fff;border-radius:12px;border:1px solid #e8f0f9}

.pagination-wrap{display:flex;justify-content:center;gap:6px;margin-top:20px;flex-wrap:wrap}
.pagination-wrap a,.pagination-wrap span{padding:8px 14px;border-radius:7px;font-size:13px;text-decoration:none;color:#0C447C;background:#fff;border:1px solid #e8f0f9}
.pagination-wrap a:hover{background:#e8f0f9}
.pagination-wrap .active-page{background:#0C447C;color:#fff;border-color:#0C447C}
.pagination-wrap .disabled{color:#c3d2e0}

@media(max-width:768px){ .main{margin-left:0;padding:16px} }
</style>
<link rel="stylesheet" href="/css/transitions.css?v=3">
</head>
<body>
<button class="hamburger" id="hamburger" onclick="toggleSidebar()">
  <span></span><span></span><span></span>
</button>
<div class="overlay" id="overlay" onclick="toggleSidebar()"></div>
@include('focal.partials.sidebar')

<div class="main">
  <div class="topbar">
    <div>
      <h2>Attendance Summary</h2>
      <p>Groupwise, studentwise meeting attendance across all classes</p>
    </div>
    <span class="badge-role">Focal Person</span>
  </div>

  {{-- FILTERS --}}
  <form method="GET" action="{{ route('focal.attendance.summary') }}" class="filter-card">
    <h3>Filters</h3>
    <div class="filter-row">
      <div class="filter-group">
        <label>Department</label>
        <div class="input-animate">
          <select name="department_id">
            <option value="">All Departments</option>
            @foreach($departments as $d)
              <option value="{{ $d->id }}" {{ request('department_id')==$d->id ? 'selected' : '' }}>{{ $d->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="filter-group">
        <label>Session</label>
        <div class="input-animate">
          <select name="session_id">
            <option value="">All Sessions</option>
            @foreach($sessions as $s)
              <option value="{{ $s->id }}" {{ request('session_id')==$s->id ? 'selected' : '' }}>{{ $s->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="filter-group">
        <label>Shift</label>
        <div class="input-animate">
          <select name="shift">
            <option value="">All Shifts</option>
            <option value="Morning" {{ request('shift')=='Morning' ? 'selected' : '' }}>Morning</option>
            <option value="Evening" {{ request('shift')=='Evening' ? 'selected' : '' }}>Evening</option>
          </select>
        </div>
      </div>
      <div class="filter-group">
        <label>Search (Group / Student)</label>
        <div class="input-animate">
          <input type="text" name="search" value="{{ request('search') }}" placeholder="e.g. BSCSF22MG01 or student name">
        </div>
      </div>
      <button type="submit" class="btn-apply">Apply Filters</button>
      <a href="{{ route('focal.attendance.summary') }}" class="btn-reset">Reset</a>
    </div>
  </form>

  {{-- STATS --}}
  @php
    $pageStudentCount = $groups->sum(fn($g) => $g->studentsData->count());
    $allPct = $groups->flatMap(fn($g) => $g->studentsData->pluck('percentage'))->filter(fn($p) => $p !== null);
    $avgPct = $allPct->count() ? round($allPct->avg()) : 0;
  @endphp
  <div class="stats-bar">
    <div class="stat-item"><div class="stat-num">{{ $groups->total() }}</div><div class="stat-label">Total Groups</div></div>
    <div class="stat-item"><div class="stat-num">{{ $pageStudentCount }}</div><div class="stat-label">Students (This Page)</div></div>
    <div class="stat-item"><div class="stat-num">{{ $avgPct }}%</div><div class="stat-label">Avg Attendance (This Page)</div></div>
  </div>

  {{-- GROUPS --}}
  @forelse($groups as $group)
    <div class="group-card">
      <div class="group-head">
        <div class="group-head-left">
          <b>{{ $group->group_code }}</b>
          <span>{{ $group->project_title ?? 'No title yet' }}</span>
        </div>
        <div class="group-head-right">
          <span class="chip">{{ $group->department->name ?? '-' }}</span>
          <span class="chip">{{ $group->session->name ?? '-' }}</span>
          <span class="chip">{{ $group->shift ?? '-' }}</span>
          <span class="chip">{{ $group->totalMeetings }} Meeting(s)</span>
        </div>
      </div>
      <table>
        <thead>
          <tr>
            <th>Student</th>
            <th>Present</th>
            <th>Absent</th>
            <th>Total</th>
            <th>Attendance %</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse($group->studentsData as $sd)
            @php
              $pctClass = $sd->total == 0 ? 'pct-none' : ($sd->percentage >= 75 ? 'pct-good' : ($sd->percentage >= 50 ? 'pct-mid' : 'pct-low'));
            @endphp
            <tr>
              <td>
                <div class="stud-name">{{ $sd->user->name }}</div>
                <div class="stud-email">{{ $sd->user->email }}</div>
              </td>
              <td>{{ $sd->present }}</td>
              <td>{{ $sd->absent }}</td>
              <td>{{ $sd->total }}</td>
              <td><span class="pct {{ $pctClass }}">{{ $sd->total == 0 ? 'N/A' : $sd->percentage.'%' }}</span></td>
              <td>
                @if($sd->total > 0)
                  <button class="btn-edit" onclick="togglePanel(this)">Edit</button>
                @endif
              </td>
            </tr>
            @if($sd->total > 0)
            <tr>
              <td colspan="6" style="padding:0;border:none;">
                <div class="meeting-panel">
                  @foreach($sd->meetingRows as $mr)
                    <div class="meeting-row" data-meeting-id="{{ $mr->meeting_id }}" data-student-id="{{ $sd->user->id }}" data-group-id="{{ $group->id }}">
                      <div class="meeting-date">{{ \Carbon\Carbon::parse($mr->date)->format('d M, Y') }}</div>
                      <select class="status-select">
                        <option value="" {{ $mr->status === null ? 'selected' : '' }}>Not Marked</option>
                        <option value="present" {{ $mr->status === 'present' ? 'selected' : '' }}>Present</option>
                        <option value="absent" {{ $mr->status === 'absent' ? 'selected' : '' }}>Absent</option>
                      </select>
                      <input type="text" class="remarks-input" placeholder="Remarks (optional)" value="{{ $mr->remarks }}">
                      <button class="btn-save" onclick="saveAttendance(this)">Save</button>
                      <span class="save-msg"></span>
                    </div>
                  @endforeach
                </div>
              </td>
            </tr>
            @endif
          @empty
            <tr><td colspan="6" style="text-align:center;color:#8096b0;padding:20px;">No accepted members in this group.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  @empty
    <div class="empty">No groups found for the selected filters.</div>
  @endforelse

  {{-- PAGINATION --}}
  @if($groups->hasPages())
    <div class="pagination-wrap">
      @if($groups->onFirstPage())
        <span class="disabled">&laquo; Prev</span>
      @else
        <a href="{{ $groups->previousPageUrl() }}">&laquo; Prev</a>
      @endif

      @for($i = 1; $i <= $groups->lastPage(); $i++)
        @if($i == $groups->currentPage())
          <span class="active-page">{{ $i }}</span>
        @else
          <a href="{{ $groups->url($i) }}">{{ $i }}</a>
        @endif
      @endfor

      @if($groups->hasMorePages())
        <a href="{{ $groups->nextPageUrl() }}">Next &raquo;</a>
      @else
        <span class="disabled">Next &raquo;</span>
      @endif
    </div>
  @endif
</div>

<script>
function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('hamburger').classList.toggle('open');
    const overlay = document.getElementById('overlay');
    if (overlay) overlay.classList.toggle('show');
}

function togglePanel(btn){
    const row = btn.closest('tr');
    const panelRow = row.nextElementSibling;
    const panel = panelRow.querySelector('.meeting-panel');
    panel.classList.toggle('show');
    btn.textContent = panel.classList.contains('show') ? 'Close' : 'Edit';
}

function saveAttendance(btn){
    const row = btn.closest('.meeting-row');
    const meetingId = row.dataset.meetingId;
    const studentId = row.dataset.studentId;
    const groupId = row.dataset.groupId;
    const status = row.querySelector('.status-select').value;
    const remarks = row.querySelector('.remarks-input').value;
    const msg = row.querySelector('.save-msg');

    if(!status){
        msg.textContent = 'Select a status';
        msg.style.color = '#c62828';
        return;
    }

    btn.disabled = true;
    msg.textContent = 'Saving...';
    msg.style.color = '#8096b0';

    fetch("{{ route('focal.attendance.update') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            meeting_id: meetingId,
            student_id: studentId,
            group_id: groupId,
            status: status,
            remarks: remarks
        })
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        if(data.success){
            msg.textContent = 'Saved ✓';
            msg.style.color = '#166534';
        } else {
            msg.textContent = 'Failed';
            msg.style.color = '#c62828';
        }
    })
    .catch(() => {
        btn.disabled = false;
        msg.textContent = 'Error';
        msg.style.color = '#c62828';
    });
}
</script>
</body>
</html>
