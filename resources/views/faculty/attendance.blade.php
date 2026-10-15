<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Mark Attendance - FYPMS</title>
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

.alert{padding:13px 20px;border-radius:10px;font-size:13px;font-weight:600;margin-bottom:18px;display:none;align-items:center;gap:10px}
.alert-success{background:#e8f5e9;color:#2e7d32;border:1px solid #c8e6c9}
.alert-error{background:#fce4e4;color:#c62828;border:1px solid #f5c6cb}

.card{background:#fff;border-radius:14px;padding:26px 28px;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9;margin-bottom:22px}
.card-title{font-size:13px;font-weight:700;color:#0C447C;margin-bottom:18px;text-transform:uppercase;letter-spacing:.6px;display:flex;align-items:center;gap:8px}
.card-title::before{content:'';width:4px;height:14px;background:#0C447C;border-radius:2px;display:inline-block}

.form-grid{display:flex;gap:18px;flex-wrap:wrap;align-items:flex-end}
.field{display:flex;flex-direction:column;gap:7px}
.field label{font-size:12px;font-weight:600;color:#4a6178}

.select-wrap{position:relative;display:inline-block}
.select-wrap select{appearance:none;-webkit-appearance:none;padding:12px 40px 12px 16px;border:1.5px solid #dfe8f3;border-radius:9px;font-size:13.5px;color:#1e3a5f;background:#f8fafd;min-width:260px;height:44px;cursor:pointer;transition:border-color .15s}
.select-wrap select:focus{outline:none;border-color:#378ADD;background:#fff}
.select-wrap::after{content:'';position:absolute;right:16px;top:50%;transform:translateY(-65%) rotate(45deg);width:8px;height:8px;border-right:2px solid #64748b;border-bottom:2px solid #64748b;pointer-events:none}

.btn{padding:12px 24px;border:none;border-radius:9px;font-size:13.5px;font-weight:700;cursor:pointer;height:44px;transition:background-color .15s;display:inline-flex;align-items:center;gap:6px}
.btn-primary{background:#0C447C;color:#fff}
.btn-primary:hover{background:#093862}
.btn-outline{background:#fff;color:#0C447C;border:1.5px solid #cbdcee}
.btn-outline:hover{background:#f4f8fd;border-color:#378ADD}
.btn-small{padding:9px 16px;font-size:12.5px;height:38px}

.meeting-card{border:1.5px solid #e8f0f9;border-radius:12px;margin-bottom:12px;padding:18px 22px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;background:#fbfdff}
.meeting-card.st-scheduled{border-left:4px solid #e0a300}
.meeting-card.st-completed{border-left:4px solid #2e7d32}
.meeting-card.st-cancelled{border-left:4px solid #c62828}
.meeting-info .date{font-size:14.5px;font-weight:700;color:#152a45}
.meeting-info .agenda{font-size:12.5px;color:#6b7f96;margin-top:4px}
.meeting-status{padding:5px 14px;border-radius:20px;font-size:11.5px;font-weight:700;letter-spacing:.3px;margin-right:12px}
.meeting-status.scheduled{background:#fff4e0;color:#b26a00}
.meeting-status.completed{background:#e8f5e9;color:#2e7d32}
.meeting-status.cancelled{background:#fce4e4;color:#c62828}

.student-row{display:flex;align-items:center;gap:18px;background:#fbfdff;border:1.5px solid #e8f0f9;border-radius:12px;padding:18px 22px;margin-bottom:14px;flex-wrap:wrap}
.avatar{width:42px;height:42px;border-radius:50%;background:#0C447C;color:#fff;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;flex-shrink:0}
.student-name{font-size:13.5px;font-weight:600;color:#1e3a5f;min-width:150px}

.status-toggle{display:inline-flex;background:#eef3f9;border-radius:9px;padding:4px;gap:2px}
.status-toggle label{position:relative;display:flex;align-items:center;gap:6px;font-size:12.5px;font-weight:600;color:#64748b;cursor:pointer;padding:8px 16px;border-radius:7px;transition:background-color .15s,color .15s}
.status-toggle input{position:absolute;opacity:0;pointer-events:none}
.status-toggle input:checked + span{color:inherit}
.status-toggle label.opt-present:has(input:checked){background:#2e7d32;color:#fff}
.status-toggle label.opt-late:has(input:checked){background:#b26a00;color:#fff}
.status-toggle label.opt-absent:has(input:checked){background:#c62828;color:#fff}

.remarks-input{flex:1;min-width:200px;padding:11px 16px;border:1.5px solid #dfe8f3;border-radius:9px;font-size:13px;color:#1e3a5f;background:#f8fafd;height:44px;transition:border-color .15s}
.remarks-input:focus{outline:none;border-color:#378ADD;background:#fff}

.empty{text-align:center;padding:48px 20px;color:#8096b0;font-size:13.5px;background:#fbfdff;border-radius:12px;border:1.5px dashed #dfe8f3}
.hidden{display:none}
@media(max-width:768px){.main{margin-left:0;padding:16px}.student-row{flex-direction:column;align-items:flex-start}.meeting-card{flex-direction:column;align-items:flex-start}.select-wrap select{min-width:100%}}
</style>
</head>
<body>
@include('faculty.partials.sidebar')
<div class="main">
  <div class="topbar">
    <div class="topbar-left">
      <div class="topbar-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></div>
      <div><h2>Mark Attendance</h2><p>Mark and review attendance for your supervised groups</p></div>
    </div>
    <span class="badge-role">Faculty</span>
  </div>

  <div id="alertBox" class="alert"></div>

  <div class="card">
    <div class="card-title">Select Group</div>
    <form method="GET" action="{{ route('faculty.attendance') }}" class="form-grid">
      <div class="field">
        <label>FYP Group</label>
        <div class="select-wrap">
          <select name="group_id" onchange="this.form.submit()">
            <option value="">-- Select a group --</option>
            @foreach($groups as $g)
              <option value="{{ $g->id }}" {{ $selectedGroup && $selectedGroup->id === $g->id ? 'selected' : '' }}>
                {{ $g->group_code }} — {{ $g->project_title }}
              </option>
            @endforeach
          </select>
        </div>
      </div>
    </form>
  </div>

  @if($selectedGroup)
  <div class="card">
    <div class="card-title">Meetings</div>
    <div id="meetingsList"><div class="empty">Loading meetings...</div></div>
  </div>

  <div class="card hidden" id="attendanceCard">
    <div class="card-title" id="attendanceCardTitle">Mark Attendance</div>
    <div id="studentsList"></div>
    <button type="button" class="btn btn-primary" id="saveAttendanceBtn" style="margin-top:6px">Save Attendance</button>
  </div>
  @endif
</div>

<script>
const GROUP_ID = {{ $selectedGroup ? $selectedGroup->id : 'null' }};
const GROUP_MEMBERS = {!! $selectedGroup ? $selectedGroup->members->map(fn($m) => ['id' => $m->user?->id, 'name' => $m->user?->name])->filter(fn($m) => $m['id'])->values()->toJson() : '[]' !!};

function apiToken() { return localStorage.getItem('api_token'); }
function authHeaders(extra = {}) {
    return Object.assign({ 'Authorization': 'Bearer ' + apiToken(), 'Accept': 'application/json' }, extra);
}
function showAlert(message, type) {
    const box = document.getElementById('alertBox');
    box.textContent = message;
    box.className = 'alert alert-' + type;
    box.style.display = 'flex';
    setTimeout(() => { box.style.display = 'none'; }, 4000);
}

let currentMeetingId = null;

async function loadMeetings() {
    if (!GROUP_ID) return;
    try {
        const res = await fetch(`/api/faculty/meetings/${GROUP_ID}`, { headers: authHeaders() });
        const data = await res.json();
        const list = document.getElementById('meetingsList');
        if (!data.success || data.meetings.length === 0) {
            list.innerHTML = '<div class="empty">No meetings scheduled yet for this group. Go to Manage Meetings to schedule one.</div>';
            return;
        }
        list.innerHTML = data.meetings.map(m => `
            <div class="meeting-card st-${m.status}">
                <div class="meeting-info">
                    <div class="date">${m.meeting_date}${m.meeting_time ? ' — ' + m.meeting_time : ''}</div>
                    ${m.agenda ? `<div class="agenda">${m.agenda}</div>` : ''}
                </div>
                <div>
                    <span class="meeting-status ${m.status}">${m.status.charAt(0).toUpperCase() + m.status.slice(1)}</span>
                    <button type="button" class="btn btn-outline btn-small" onclick="openAttendance(${m.id}, '${m.meeting_date}')">Mark / View Attendance</button>
                </div>
            </div>
        `).join('');
    } catch (e) {
        document.getElementById('meetingsList').innerHTML = '<div class="empty">Failed to load meetings.</div>';
    }
}

async function openAttendance(meetingId, meetingDate) {
    currentMeetingId = meetingId;
    document.getElementById('attendanceCardTitle').textContent = 'Mark Attendance — ' + meetingDate;
    const card = document.getElementById('attendanceCard');
    card.classList.remove('hidden');
    card.scrollIntoView({ behavior: 'smooth' });

    let existing = {};
    try {
        const res = await fetch(`/api/faculty/meetings/${meetingId}/attendance`, { headers: authHeaders() });
        const data = await res.json();
        if (data.success) {
            data.attendance.forEach(a => { existing[a.student_id] = a; });
        }
    } catch (e) {}

    const list = document.getElementById('studentsList');
    list.innerHTML = GROUP_MEMBERS.map(s => {
        const prev = existing[s.id];
        const status = prev ? prev.status : 'present';
        const remarks = prev ? (prev.remarks || '') : '';
        return `
        <div class="student-row">
            <div class="avatar">${s.name.charAt(0).toUpperCase()}</div>
            <div class="student-name">${s.name}</div>
            <div class="status-toggle">
                <label class="opt-present"><input type="radio" name="status_${s.id}" value="present" ${status==='present'?'checked':''}><span>Present</span></label>
                <label class="opt-late"><input type="radio" name="status_${s.id}" value="late" ${status==='late'?'checked':''}><span>Late</span></label>
                <label class="opt-absent"><input type="radio" name="status_${s.id}" value="absent" ${status==='absent'?'checked':''}><span>Absent</span></label>
            </div>
            <input type="text" class="remarks-input" id="remarks_${s.id}" placeholder="Remarks (optional)" value="${remarks}">
        </div>`;
    }).join('');
}

document.getElementById('saveAttendanceBtn')?.addEventListener('click', async function() {
    if (!currentMeetingId) return;
    const attendance = {};
    GROUP_MEMBERS.forEach(s => {
        const status = document.querySelector(`input[name="status_${s.id}"]:checked`)?.value || 'present';
        const remarks = document.getElementById(`remarks_${s.id}`)?.value || null;
        attendance[s.id] = { student_id: s.id, status, remarks };
    });
    const body = {
        group_id: GROUP_ID,
        meeting_id: currentMeetingId,
        attendance: Object.values(attendance),
    };
    try {
        const res = await fetch('/api/faculty/attendance', {
            method: 'POST',
            headers: authHeaders({ 'Content-Type': 'application/json' }),
            body: JSON.stringify(body)
        });
        const data = await res.json();
        if (data.success) {
            showAlert(data.message, 'success');
            loadMeetings();
        } else {
            showAlert(data.message || 'Failed to save attendance.', 'error');
        }
    } catch (e) {
        showAlert('Network error while saving attendance.', 'error');
    }
});

if (GROUP_ID) loadMeetings();
</script>
</body>
</html>
