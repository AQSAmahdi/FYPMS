<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Manage Meetings - FYPMS</title>
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
.field input[type=date],.field input[type=time],.field input[type=text]{padding:12px 16px;border:1.5px solid #dfe8f3;border-radius:9px;font-size:13.5px;color:#1e3a5f;background:#f8fafd;min-width:190px;height:44px;transition:border-color .15s}
.field input:focus{outline:none;border-color:#378ADD;background:#fff}

.select-wrap{position:relative;display:inline-block}
.select-wrap select{appearance:none;-webkit-appearance:none;padding:12px 40px 12px 16px;border:1.5px solid #dfe8f3;border-radius:9px;font-size:13.5px;color:#1e3a5f;background:#f8fafd;min-width:260px;height:44px;cursor:pointer;transition:border-color .15s}
.select-wrap select:focus{outline:none;border-color:#378ADD;background:#fff}
.select-wrap::after{content:'';position:absolute;right:16px;top:50%;transform:translateY(-65%) rotate(45deg);width:8px;height:8px;border-right:2px solid #64748b;border-bottom:2px solid #64748b;pointer-events:none}

.btn{padding:12px 24px;border:none;border-radius:9px;font-size:13.5px;font-weight:700;cursor:pointer;height:44px;transition:background-color .15s,box-shadow .15s;display:inline-flex;align-items:center;gap:6px}
.btn-primary{background:#0C447C;color:#fff}
.btn-primary:hover{background:#093862}
.btn-outline{background:#fff;color:#0C447C;border:1.5px solid #cbdcee}
.btn-outline:hover{background:#f4f8fd;border-color:#378ADD}
.btn-danger{background:#fff;color:#c62828;border:1.5px solid #f3d3d3}
.btn-danger:hover{background:#fdf1f1;border-color:#e57373}
.btn-small{padding:9px 16px;font-size:12.5px;height:38px}

.meeting-card{border:1.5px solid #e8f0f9;border-radius:12px;margin-bottom:14px;overflow:hidden;background:#fbfdff}
.meeting-card.st-scheduled{border-left:4px solid #e0a300}
.meeting-card.st-completed{border-left:4px solid #2e7d32}
.meeting-card.st-cancelled{border-left:4px solid #c62828}
.meeting-row{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:18px 22px;flex-wrap:wrap}
.meeting-info .date{font-size:14.5px;font-weight:700;color:#152a45}
.meeting-info .agenda{font-size:12.5px;color:#6b7f96;margin-top:4px}
.meeting-actions{display:flex;gap:10px;align-items:center;flex-wrap:wrap}
.meeting-status{padding:5px 14px;border-radius:20px;font-size:11.5px;font-weight:700;letter-spacing:.3px}
.meeting-status.scheduled{background:#fff4e0;color:#b26a00}
.meeting-status.completed{background:#e8f5e9;color:#2e7d32}
.meeting-status.cancelled{background:#fce4e4;color:#c62828}

.edit-row{display:none;background:#fff;border-top:1.5px solid #e8f0f9;padding:20px 22px;flex-wrap:wrap;gap:16px;align-items:flex-end}
.edit-row.show{display:flex}
.edit-actions{display:flex;gap:10px}

.empty{text-align:center;padding:48px 20px;color:#8096b0;font-size:13.5px;background:#fbfdff;border-radius:12px;border:1.5px dashed #dfe8f3}
@media(max-width:768px){.main{margin-left:0;padding:16px}.meeting-row{flex-direction:column;align-items:flex-start}.select-wrap select,.field input{min-width:100%}}
</style>
</head>
<body>
@include('faculty.partials.sidebar')
<div class="main">
  <div class="topbar">
    <div class="topbar-left">
      <div class="topbar-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
      <div><h2>Manage Meetings</h2><p>Schedule, edit, and cancel meetings for your supervised groups</p></div>
    </div>
    <span class="badge-role">Faculty</span>
  </div>

  <div id="alertBox" class="alert"></div>

  <div class="card">
    <div class="card-title">Select Group</div>
    <form method="GET" action="{{ route('faculty.meetings') }}" class="form-grid">
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
    <div class="card-title">Schedule New Meeting — {{ $selectedGroup->group_code }}</div>
    <form id="scheduleForm" class="form-grid">
      <div class="field">
        <label>Date</label>
        <input type="date" id="meetingDate" value="{{ now()->format('Y-m-d') }}" required>
      </div>
      <div class="field">
        <label>Time (optional)</label>
        <input type="time" id="meetingTime">
      </div>
      <div class="field" style="flex:1;min-width:240px">
        <label>Agenda (optional)</label>
        <input type="text" id="meetingAgenda" placeholder="e.g. Weekly progress review">
      </div>
      <button type="submit" class="btn btn-primary">Schedule Meeting</button>
    </form>
  </div>

  <div class="card">
    <div class="card-title">Meetings</div>
    <div id="meetingsList"><div class="empty">Loading meetings...</div></div>
  </div>
  @endif
</div>

<script>
const GROUP_ID = {{ $selectedGroup ? $selectedGroup->id : 'null' }};

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

async function loadMeetings() {
    if (!GROUP_ID) return;
    try {
        const res = await fetch(`/api/faculty/meetings/${GROUP_ID}`, { headers: authHeaders() });
        const data = await res.json();
        const list = document.getElementById('meetingsList');
        if (!data.success || data.meetings.length === 0) {
            list.innerHTML = '<div class="empty">No meetings scheduled yet for this group.</div>';
            return;
        }
        list.innerHTML = data.meetings.map(m => `
            <div class="meeting-card st-${m.status}">
                <div class="meeting-row">
                    <div class="meeting-info">
                        <div class="date">${m.meeting_date}${m.meeting_time ? ' — ' + m.meeting_time : ''}</div>
                        ${m.agenda ? `<div class="agenda">${m.agenda}</div>` : ''}
                    </div>
                    <div class="meeting-actions">
                        <span class="meeting-status ${m.status}">${m.status.charAt(0).toUpperCase() + m.status.slice(1)}</span>
                        ${m.status === 'scheduled' ? `<button type="button" class="btn btn-outline btn-small" onclick="toggleEdit(${m.id})">Edit</button>` : ''}
                        <button type="button" class="btn btn-danger btn-small" onclick="deleteMeeting(${m.id})">Delete</button>
                    </div>
                </div>
                <div class="edit-row" id="editRow${m.id}">
                    <div class="field">
                        <label>Date</label>
                        <input type="date" id="editDate${m.id}" value="${m.meeting_date}">
                    </div>
                    <div class="field">
                        <label>Time</label>
                        <input type="time" id="editTime${m.id}" value="${m.meeting_time || ''}">
                    </div>
                    <div class="field" style="flex:1;min-width:220px">
                        <label>Agenda</label>
                        <input type="text" id="editAgenda${m.id}" value="${m.agenda || ''}">
                    </div>
                    <div class="field">
                        <label>Status</label>
                        <div class="select-wrap">
                            <select id="editStatus${m.id}">
                                <option value="scheduled" ${m.status==='scheduled'?'selected':''}>Scheduled</option>
                                <option value="completed" ${m.status==='completed'?'selected':''}>Completed</option>
                                <option value="cancelled" ${m.status==='cancelled'?'selected':''}>Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="edit-actions">
                        <button type="button" class="btn btn-primary btn-small" onclick="saveEdit(${m.id})">Save</button>
                        <button type="button" class="btn btn-outline btn-small" onclick="toggleEdit(${m.id})">Cancel</button>
                    </div>
                </div>
            </div>
        `).join('');
    } catch (e) {
        document.getElementById('meetingsList').innerHTML = '<div class="empty">Failed to load meetings.</div>';
    }
}

function toggleEdit(id) {
    const row = document.getElementById('editRow' + id);
    row.classList.toggle('show');
}

async function saveEdit(id) {
    const body = {
        meeting_date: document.getElementById('editDate' + id).value,
        meeting_time: document.getElementById('editTime' + id).value || null,
        agenda: document.getElementById('editAgenda' + id).value || null,
        status: document.getElementById('editStatus' + id).value,
    };
    try {
        const res = await fetch(`/api/faculty/meetings/${id}`, {
            method: 'PUT',
            headers: authHeaders({ 'Content-Type': 'application/json' }),
            body: JSON.stringify(body)
        });
        const data = await res.json();
        if (data.success) {
            showAlert(data.message, 'success');
            loadMeetings();
        } else {
            showAlert(data.message || 'Failed to update meeting.', 'error');
        }
    } catch (e) {
        showAlert('Network error while updating meeting.', 'error');
    }
}

async function deleteMeeting(id) {
    if (!confirm('Is meeting ko delete karna hai? Iski attendance bhi delete ho jayegi.')) return;
    try {
        const res = await fetch(`/api/faculty/meetings/${id}`, {
            method: 'DELETE',
            headers: authHeaders()
        });
        const data = await res.json();
        if (data.success) {
            showAlert(data.message, 'success');
            loadMeetings();
        } else {
            showAlert(data.message || 'Failed to delete meeting.', 'error');
        }
    } catch (e) {
        showAlert('Network error while deleting meeting.', 'error');
    }
}

document.getElementById('scheduleForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const body = {
        group_id: GROUP_ID,
        meeting_date: document.getElementById('meetingDate').value,
        meeting_time: document.getElementById('meetingTime').value || null,
        agenda: document.getElementById('meetingAgenda').value || null,
    };
    try {
        const res = await fetch('/api/faculty/meetings', {
            method: 'POST',
            headers: authHeaders({ 'Content-Type': 'application/json' }),
            body: JSON.stringify(body)
        });
        const data = await res.json();
        if (data.success) {
            showAlert(data.message, 'success');
            document.getElementById('meetingAgenda').value = '';
            loadMeetings();
        } else {
            showAlert(data.message || 'Failed to schedule meeting.', 'error');
        }
    } catch (e) {
        showAlert('Network error while scheduling meeting.', 'error');
    }
});

if (GROUP_ID) loadMeetings();
</script>
</body>
</html>
