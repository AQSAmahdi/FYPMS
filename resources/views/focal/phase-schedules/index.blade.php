<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Phase Schedules - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
.main{margin-left:260px;padding:28px 32px}
.topbar{background:#0C447C;border-radius:14px;padding:20px 26px;margin-bottom:24px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 4px 14px rgba(12,68,124,.18)}
.topbar h2{color:#fff;font-size:19px;font-weight:700}
.topbar p{color:rgba(255,255,255,.9)!important;font-size:12.5px;margin-top:2px}
.badge-role{background:rgba(255,255,255,.18);color:#fff;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600}
.grid2{display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-top:4px;align-items:stretch}
.card{background:#fff;border-radius:12px;padding:0 0 22px 0;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9;overflow:hidden;display:flex;flex-direction:column}
.card h3{color:#fff;background:#0C447C;font-size:15px;font-weight:700;margin-bottom:20px;padding:16px 22px}
.card > form, .card > div#schedules-container, .card > div.empty, .card > div.schedules-scroll, .card > div.schedule-search-wrap{padding-left:22px;padding-right:22px}
#bulkSection{padding:22px !important}
#bulkSection.card{padding:22px !important}
.form-group{margin-bottom:30px}
.form-group label{display:block;font-size:12.5px;font-weight:700;color:#0C447C;text-transform:uppercase;letter-spacing:.7px;margin-bottom:10px}
.form-group input,.form-group select,.form-group textarea{width:100%;padding:15px 18px;border:1.5px solid #e8f0f9;border-radius:10px;font-size:14.5px;color:#1e3a5f;font-family:'Segoe UI',sans-serif;outline:none;background:#f7fafd;transition:all .2s}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{border-color:#0C447C;background:#fff;box-shadow:0 0 0 3px rgba(12,68,124,.08)}
.form-group input::placeholder{color:#a8bdd0}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:24px}
.btn-primary{background:#0C447C;color:#fff;border:none;padding:16px 20px;border-radius:10px;font-size:14.5px;font-weight:600;cursor:pointer;width:100%;margin-top:6px;transition:all .2s;box-shadow:0 3px 10px rgba(12,68,124,.25)}
.btn-primary:hover{background:#185FA5;box-shadow:0 4px 14px rgba(12,68,124,.32);transform:translateY(-1px)}
.alert-success{background:#dcfce7;color:#166534;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;border:1px solid #bbf7d0}
.alert-error{background:#fef2f2;color:#b91c1c;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px}
.schedule-item{border:1.5px solid #e8f0f9;border-radius:12px;padding:18px 20px;margin-bottom:14px;background:#fbfdff;transition:all .2s}
.schedule-item:hover{border-color:#c7d8ec;box-shadow:0 3px 14px rgba(12,68,124,.1)}
.schedule-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px}
.schedule-name{font-size:15px;font-weight:700;color:#0C447C;display:flex;align-items:center;gap:8px}
.schedule-name::before{content:"";width:8px;height:8px;border-radius:50%;background:#0C447C;display:inline-block;flex-shrink:0}
.schedule-meta{font-size:12px;color:#8096b0;margin-top:2px}
.badge-pending{background:#fef9c3;color:#854d0e;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
.badge-completed{background:#dcfce7;color:#166534;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
.badge-active{background:#dbeafe;color:#1e40af;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
.info-row{display:flex;gap:10px;margin-bottom:10px;flex-wrap:wrap}
.info-box{background:#f0f6ff;border:1px solid #e2ecf9;border-radius:10px;padding:10px 14px;flex:1;min-width:120px}
.info-box .lbl{font-size:10px;color:#8096b0;text-transform:uppercase;letter-spacing:.5px;margin-bottom:3px}
.info-box .val{font-size:13px;font-weight:600;color:#0C447C}
.schedule-actions{display:flex;gap:8px;flex-wrap:wrap;padding-top:12px;border-top:1px solid #eef3f9}
.btn-sm{padding:7px 16px;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;transition:all .2s}
.btn-edit{background:#fff;color:#0C447C;border:1.5px solid #c7d8ec}
.btn-edit:hover{background:#0C447C;color:#fff}
.btn-delete{background:#fff;color:#b91c1c;border:1.5px solid #f3caca}
.btn-delete:hover{background:#b91c1c;color:#fff}
.empty{text-align:center;padding:40px;color:#8096b0;font-size:14px}
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center}
.modal-overlay.show{display:flex}
.modal{background:#fff;border-radius:16px;padding:28px;width:100%;max-width:500px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(12,68,124,.2)}
.modal h3{color:#0C447C;font-size:16px;font-weight:700;margin-bottom:20px}
.modal-actions{display:flex;gap:10px;margin-top:16px}
.btn-cancel{flex:1;padding:10px;background:#f4f7fb;color:#5a7a9a;border:1.5px solid #d5e3f0;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer}
.btn-save{flex:2;padding:10px;background:#0C447C;color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer}
.schedule-search-wrap{padding:0 22px 14px 22px}
.schedule-search{width:100%;padding:10px 14px 10px 38px;border:1.5px solid #e8f0f9;border-radius:9px;font-size:13px;color:#1e3a5f;outline:none;background:#f7fafd url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="%238096b0" stroke-width="2"><circle cx="6.5" cy="6.5" r="5"/><line x1="10.5" y1="10.5" x2="15" y2="15"/></svg>') no-repeat 12px center;font-family:'Segoe UI',sans-serif;transition:all .2s}
.schedule-search:focus{border-color:#0C447C;background-color:#fff;box-shadow:0 0 0 3px rgba(12,68,124,.08)}
.schedule-item.hidden-by-search{display:none}
.schedules-scroll{max-height:520px;overflow-y:auto;padding-right:6px}
.schedules-scroll::-webkit-scrollbar{width:6px}
.schedules-scroll::-webkit-scrollbar-thumb{background:#c7d8ec;border-radius:10px}
.schedules-scroll::-webkit-scrollbar-track{background:transparent}
@media(max-width:900px){.grid2{grid-template-columns:1fr}}
@media(max-width:768px){.main{margin-left:0;padding:16px}.form-row{grid-template-columns:1fr}}
</style>
</head>
<body>
@include('focal.partials.sidebar')
<script>
function filterSchedules(){
    const q = document.getElementById('scheduleSearch').value.toLowerCase();
    document.querySelectorAll('#schedulesScrollList .schedule-item').forEach(item => {
        const s = item.getAttribute('data-search') || '';
        item.classList.toggle('hidden-by-search', !s.includes(q));
    });
}
</script>
<div class="main">
  <div class="topbar">
    <div>
      <h2>Phase Schedules</h2>
      <p>Assign evaluation schedules to groups</p>
    </div>
    <span class="badge-role">Focal Person</span>
  </div>

  @if(session('success'))
    <div class="alert-success">✓ {{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="alert-error">{{ $errors->first() }}</div>
  @endif

  <!-- Bulk Assign Trigger Button -->
  <div style="margin-bottom:20px">
    <button onclick="openBulkModal()" id="bulkToggleBtn" style="display:flex;align-items:center;gap:10px;background:#0C447C;color:#fff;border:none;border-radius:10px;padding:11px 20px;font-size:13px;font-weight:600;cursor:pointer;font-family:'Segoe UI',sans-serif">
      <span>Bulk Assign Phase to Multiple Groups</span>
    </button>
  </div>

  <!-- Bulk Assign Modal -->
  <div class="modal-overlay" id="bulkModal" onclick="if(event.target===this) closeBulkModal()">
  <div class="modal" style="max-width:640px">
    <div style="display:flex;justify-content:space-between;align-items:center">
      <h3 style="margin-bottom:0">Bulk Assign Phase to Multiple Groups</h3>
      <button type="button" onclick="closeBulkModal()" style="background:none;border:none;font-size:20px;color:#8096b0;cursor:pointer;line-height:1;padding:0 4px">&times;</button>
    </div>
    <p style="font-size:12px;color:#8096b0;margin:-12px 0 16px 0">Select a phase, filter by session/department, then assign to multiple groups at once</p>
    <div class="form-row" style="margin-bottom:14px">
      <div class="form-group">
        <label>Select Phase</label>
        <select id="bulk-phase">
          <option value="">-- Select Phase --</option>
          @foreach($phases as $phase)
          <option value="{{ $phase->id }}">{{ $phase->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label>Filter by Session</label>
        <select id="bulk-session">
          <option value="">All Sessions</option>
          @foreach($sessions as $session)
          <option value="{{ $session->id }}">{{ $session->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label>Filter by Department</label>
        <select id="bulk-dept">
          <option value="">All Departments</option>
          @foreach($departments as $dept)
          <option value="{{ $dept->id }}">{{ $dept->name }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="form-row" style="margin-bottom:14px">
      <div class="form-group">
        <label>Schedule Date</label>
        <input type="date" id="bulk-date">
      </div>
      <div class="form-group">
        <label>Schedule Time</label>
        <input type="time" id="bulk-time">
      </div>
      <div class="form-group">
        <label>Room (Optional)</label>
        <input type="text" id="bulk-room" placeholder="e.g. Room 301">
      </div>
    </div>
    <div style="margin-bottom:14px">
      <button type="button" onclick="loadBulkGroups()" style="background:#e8f0f9;color:#0C447C;border:none;padding:10px 20px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer">Load Groups</button>
    </div>
    <div id="bulk-groups-wrap" style="display:none;margin-bottom:14px">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
        <label style="font-size:12px;font-weight:700;color:#0C447C;text-transform:uppercase;letter-spacing:.6px">Select Groups</label>
        <button type="button" onclick="toggleSelectAll()" id="select-all-btn" style="background:#e8f0f9;color:#0C447C;border:none;padding:5px 14px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer">Select All</button>
      </div>
      <div id="bulk-groups-list" style="max-height:220px;overflow-y:auto;border:1.5px solid #e8f0f9;border-radius:8px;padding:8px"></div>
    </div>
    <div style="display:flex;gap:10px">
      <button type="button" onclick="closeBulkModal()" style="flex:1;padding:10px;background:#f4f7fb;color:#5a7a9a;border:1.5px solid #d5e3f0;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer">Cancel</button>
      <button type="button" onclick="bulkAssign()" style="flex:2;background:#0C447C;color:#fff;border:none;padding:10px 24px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer">Assign to Selected Groups</button>
    </div>
    <div id="bulk-msg" style="margin-top:10px;font-size:13px"></div>
  </div>

  </div>
  <div class="grid2">
    <!-- Create Form -->
    <div class="card">
      <h3>Add Schedule</h3>
      <form method="POST" action="{{ route('focal.phase-schedules.store') }}" style="flex:1;display:flex;flex-direction:column;padding-left:22px;padding-right:22px">
        @csrf
        <div class="form-group">
          <label>Select Phase</label>
          <select name="phase_id" required>
            <option value="">-- Select Phase --</option>
            @foreach($phases as $phase)
            <option value="{{ $phase->id }}" {{ old('phase_id')==$phase->id?'selected':'' }}>
              {{ $phase->name }}
            </option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label>Select Group</label>
          <select name="group_id" required>
            <option value="">-- Select Group --</option>
            @foreach($groups as $group)
            <option value="{{ $group->id }}" {{ old('group_id')==$group->id?'selected':'' }}>
              {{ $group->group_code }} — {{ $group->project_title ?? 'No Title' }}
            </option>
            @endforeach
          </select>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Schedule Date</label>
            <input type="date" name="schedule_date" required value="{{ old('schedule_date') }}">
          </div>
          <div class="form-group">
            <label>Schedule Time</label>
            <input type="time" name="schedule_time" required value="{{ old('schedule_time') }}">
          </div>
        </div>
        <div class="form-group">
          <label>Room (Optional)</label>
          <input type="text" name="room" placeholder="e.g. Room 301" value="{{ old('room') }}">
        </div>
        <div style="flex:1"></div>
        <button type="submit" class="btn-primary">Add Schedule</button>
      </form>
    </div>

    <!-- Schedules List -->
    <div class="card">
      <h3>Existing Schedules ({{ $schedules->count() }})</h3>
      <div class="schedule-search-wrap">
        <input type="text" class="schedule-search" id="scheduleSearch" placeholder="Search by phase or group..." oninput="filterSchedules()">
      </div>
      <div class="schedules-scroll" id="schedulesScrollList">
      @if($schedules->count() > 0)
        @foreach($schedules as $schedule)
        <div class="schedule-item" data-search="{{ strtolower(($schedule->phase->name ?? '').' '.($schedule->group->group_code ?? '')) }}">
          <div class="schedule-header">
            <div>
              <div class="schedule-name">{{ $schedule->phase->name ?? 'N/A' }}</div>
              <div class="schedule-meta">{{ $schedule->group->group_code ?? 'N/A' }}</div>
            </div>
            <span class="badge-{{ $schedule->status }}">{{ ucfirst($schedule->status) }}</span>
          </div>
          <div class="info-row">
            <div class="info-box">
              <div class="lbl">Date</div>
              <div class="val">{{ \Carbon\Carbon::parse($schedule->schedule_date)->format('d M Y') }}</div>
            </div>
            <div class="info-box">
              <div class="lbl">Time</div>
              <div class="val">{{ \Carbon\Carbon::parse($schedule->schedule_time)->format('h:i A') }}</div>
            </div>
            @if($schedule->room)
            <div class="info-box">
              <div class="lbl">Room</div>
              <div class="val">{{ $schedule->room }}</div>
            </div>
            @endif
          </div>
          <div class="schedule-actions">
            <button class="btn-sm btn-edit" onclick="openEdit({{ $schedule->id }},{{ $schedule->phase_id }},{{ $schedule->group_id }},'{{ $schedule->schedule_date }}','{{ $schedule->schedule_time }}','{{ addslashes($schedule->room ?? '') }}')">Edit</button>
            <form method="POST" action="{{ route('focal.phase-schedules.destroy', $schedule->id) }}" onsubmit="return confirm('Delete?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn-sm btn-delete">Delete</button>
            </form>
          </div>
        </div>
        @endforeach
      @else
        <div class="empty">No schedules added yet.</div>
      @endif
      </div>
    </div>
  </div>
</div>
<!-- Edit Modal -->
<div class="modal-overlay" id="editModal">
  <div class="modal">
    <h3>Edit Schedule</h3>
    <form id="editForm" method="POST">
      @csrf @method('PUT')
      <div class="form-group">
        <label>Phase</label>
        <select name="phase_id" id="edit_phase" required>
          @foreach($phases as $phase)
          <option value="{{ $phase->id }}">{{ $phase->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label>Group</label>
        <select name="group_id" id="edit_group" required>
          @foreach($groups as $group)
          <option value="{{ $group->id }}">{{ $group->group_code }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Date</label>
          <input type="date" name="schedule_date" id="edit_date" required>
        </div>
        <div class="form-group">
          <label>Time</label>
          <input type="time" name="schedule_time" id="edit_time" required>
        </div>
      </div>
      <div class="form-group">
        <label>Room</label>
        <input type="text" name="room" id="edit_room">
      </div>
      <div class="modal-actions">
        <button type="button" class="btn-cancel" onclick="closeEdit()">Cancel</button>
        <button type="submit" class="btn-save">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<script>
// Toggle bulk assign section visibility
function openBulkModal() {
    document.getElementById('bulkModal').classList.add('show');
}
function closeBulkModal() {
    document.getElementById('bulkModal').classList.remove('show');
}
function openEdit(id, phaseId, groupId, date, time, room) {
    document.getElementById('editForm').action = '/focal/phase-schedules/' + id;
    document.getElementById('edit_phase').value = phaseId;
    document.getElementById('edit_group').value = groupId;
    document.getElementById('edit_date').value  = date;
    document.getElementById('edit_time').value  = time;
    document.getElementById('edit_room').value  = room;
    document.getElementById('editModal').classList.add('show');
}
function closeEdit() {
    document.getElementById('editModal').classList.remove('show');
}
document.getElementById('editModal').addEventListener('click',function(e){
    if(e.target===this) closeEdit();
});
function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('overlay')?.classList.toggle('show');
}
</script>

<script>
async function loadBulkGroups() {
    const phase = document.getElementById('bulk-phase').value;
    const session = document.getElementById('bulk-session').value;
    const dept = document.getElementById('bulk-dept').value;
    if (!phase) { document.getElementById('bulk-groups-wrap').style.display='none'; return; }
    const token = localStorage.getItem('api_token');
    let url = '/api/focal/groups-for-bulk?phase_id='+phase;
    if (session) url += '&session_id='+session;
    if (dept) url += '&department_id='+dept;
    const res = await fetch(url, { headers: { 'Authorization': 'Bearer '+token } });
    const data = await res.json();
    const list = document.getElementById('bulk-groups-list');
    if (!data.groups || data.groups.length === 0) {
        list.innerHTML = '<p style="color:#8096b0;padding:10px;font-size:13px">No groups found</p>';
    } else {
        list.innerHTML = data.groups.map(g => `
            <label style="display:flex;align-items:center;gap:10px;padding:8px 10px;border-bottom:1px solid #f0f4f8;cursor:pointer;font-size:13px;color:#1e3a5f">
                <input type="checkbox" class="bulk-group-cb" value="${g.id}" style="width:16px;height:16px;accent-color:#0C447C">
                <span><strong>${g.group_code}</strong> — ${g.project_title}</span>
            </label>`).join('');
    }
    document.getElementById('bulk-groups-wrap').style.display = 'block';
}

function toggleSelectAll() {
    const cbs = document.querySelectorAll('.bulk-group-cb');
    const allChecked = [...cbs].every(c => c.checked);
    cbs.forEach(c => c.checked = !allChecked);
    document.getElementById('select-all-btn').textContent = allChecked ? 'Select All' : 'Deselect All';
}

async function bulkAssign() {
    const phase    = document.getElementById('bulk-phase').value;
    const date     = document.getElementById('bulk-date').value;
    const time     = document.getElementById('bulk-time').value;
    const room     = document.getElementById('bulk-room').value;
    const selected = [...document.querySelectorAll('.bulk-group-cb:checked')].map(c => c.value);
    const msg      = document.getElementById('bulk-msg');

    if (!phase) { msg.innerHTML = '<span style="color:#b91c1c">Please select a phase</span>'; return; }
    if (!date)  { msg.innerHTML = '<span style="color:#b91c1c">Please select a date</span>'; return; }
    if (!time)  { msg.innerHTML = '<span style="color:#b91c1c">Please select a time</span>'; return; }
    if (selected.length === 0) { msg.innerHTML = '<span style="color:#b91c1c">Please select at least one group</span>'; return; }

    const res = await fetch('/api/focal/bulk-assign-phase', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('api_token'),
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
        },
        body: JSON.stringify({ phase_id: phase, group_ids: selected, schedule_date: date, schedule_time: time, room })
    });
    const data = await res.json();
    if (data.success) {
        msg.innerHTML = '<span style="color:#166534">✓ Assigned to '+data.count+' groups successfully!</span>';
        setTimeout(() => location.reload(), 1500);
    } else {
        msg.innerHTML = '<span style="color:#b91c1c">Error: '+(data.message||'Something went wrong')+'</span>';
    }
}
</script>
</body>
</html>
