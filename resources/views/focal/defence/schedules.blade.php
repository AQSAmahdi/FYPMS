<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Schedule Defence - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
.main{margin-left:260px;padding:24px 28px;min-height:100vh}
.topbar{background:#fff;border-radius:12px;padding:16px 22px;margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9}
.topbar h2{color:#0C447C;font-size:18px;font-weight:700}
.topbar p{color:#8096b0;font-size:12px;margin-top:2px}
.badge-role{background:#e8f0f9;color:#0C447C;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600}
.grid2{display:grid;grid-template-columns:1fr 1fr;gap:20px}
.card{background:#fff;border-radius:12px;padding:22px;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9}
.card h3{color:#0C447C;font-size:15px;font-weight:700;margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid #e8f0f9}
.form-group{margin-bottom:14px}
.form-group label{display:block;font-size:12px;font-weight:700;color:#0C447C;text-transform:uppercase;letter-spacing:.6px;margin-bottom:6px}
.form-group input,.form-group select{width:100%;padding:10px 14px;border:1.5px solid #e8f0f9;border-radius:8px;font-size:14px;color:#1e3a5f;font-family:'Segoe UI',sans-serif;outline:none;transition:border .2s}
.form-group input:focus,.form-group select:focus{border-color:#0C447C}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.criteria-wrap{border:1.5px solid #e8f0f9;border-radius:8px;padding:12px;margin-bottom:14px}
.criteria-item{display:grid;grid-template-columns:1fr auto auto;gap:8px;align-items:center;margin-bottom:8px}
.criteria-item input{padding:8px 12px;border:1.5px solid #e8f0f9;border-radius:6px;font-size:13px;color:#1e3a5f;font-family:'Segoe UI',sans-serif;outline:none}
.criteria-item input:focus{border-color:#0C447C}
.criteria-item .marks-input{width:80px}
.btn-remove{background:#fce4e4;color:#c62828;border:none;border-radius:6px;padding:6px 10px;cursor:pointer;font-size:12px;font-weight:600}
.btn-add-criteria{background:#e8f0f9;color:#0C447C;border:none;border-radius:6px;padding:8px 14px;cursor:pointer;font-size:13px;font-weight:600;margin-bottom:4px}
.btn-primary{background:#0C447C;color:#fff;border:none;padding:10px 20px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;width:100%;margin-top:4px}
.btn-primary:hover{background:#0a3a6b}
.alert-success{background:#e8f5e9;color:#2e7d32;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;border:1px solid #c8e6c9}
.schedule-item{border:1.5px solid #e8f0f9;border-radius:10px;padding:14px 16px;margin-bottom:12px}
.schedule-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:8px}
.group-code{font-size:14px;font-weight:700;color:#0C447C}
.badge-scheduled{background:#e8f0f9;color:#0C447C;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
.badge-completed{background:#e8f5e9;color:#2e7d32;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
.schedule-info{font-size:12px;color:#5a7a9a;margin-bottom:8px;display:flex;flex-wrap:wrap;gap:10px}
.criteria-tags{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:10px}
.criteria-tag{background:#f0f4f8;color:#1e3a5f;padding:3px 10px;border-radius:20px;font-size:11px}
.schedule-actions{display:flex;gap:8px}
.btn-sm{padding:5px 12px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;border:none}
.btn-complete{background:#e8f5e9;color:#2e7d32}
.btn-delete{background:#fce4e4;color:#c62828}
.empty-state{text-align:center;padding:40px;color:#8096b0;font-size:14px}
.loading{text-align:center;padding:40px;color:#8096b0;font-size:14px}
.total-marks{font-size:12px;font-weight:700;color:#0C447C;margin-top:4px}
@media(max-width:768px){
    .main{margin-left:0;padding:16px}
    .grid2{grid-template-columns:1fr}
    .form-row{grid-template-columns:1fr}
    .topbar{flex-direction:column;align-items:flex-start;gap:8px}
}
</style>
</head>
<body>
@include('focal.partials.sidebar')
<div class="main">
  <div class="topbar">
    <div><h2>Schedule Defence</h2><p>Assign groups to facultys with date, time & room</p></div>
    <span class="badge-role">Focal Person</span>
  </div>

  @if(session('success'))
    <div class="alert-success">✓ {{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="alert-error" style="background:#fef2f2;color:#b91c1c;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;border:1px solid #fecaca;display:flex;align-items:center;gap:8px">
      <span>⚠</span> {{ $errors->first() }}
    </div>
  @endif

  <div class="grid2">
    <!-- Schedule Form - Laravel handles -->
    <div class="card">
      <h3>📅 New Defence Schedule</h3>
      <form method="POST" action="{{ route('focal.defence.schedules.store') }}" onsubmit="return validateForm()">
        @csrf
        <div class="form-group">
          <label>Select Group</label>
          <select name="fyp_group_id" id="groupSelect" required>
            <option value="">-- Loading Groups --</option>
          </select>
        </div>
        <div class="form-group">
          <label>Select Panel</label>
          <select name="defence_panel_id" id="panelSelect" required onchange="loadFacultys(this)">
            <option value="">-- Select Panel --</option>
            @foreach($panels as $panel)
            <option value="{{ $panel->id }}" data-facultys="{{ $panel->facultys->toJson() }}">
              {{ $panel->name }}
            </option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label>Assign Faculty</label>
          <select name="assigned_teacher_id" id="facultySelect" required>
            <option value="">-- Select Faculty --</option>
            @foreach($facultys as $faculty)
            <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Defence Date</label>
            <input type="date" name="defence_date" required>
          </div>
          <div class="form-group">
            <label>Time</label>
            <input type="time" name="defence_time" required>
          </div>
        </div>
        <div class="form-group">
          <label>Room / Venue</label>
          <input type="text" name="room" placeholder="e.g. Room 301, Lab-2" required>
        </div>
        <div class="form-group">
          <label>Evaluation Criteria & Marks</label>
          <div class="criteria-wrap" id="criteriaWrap">
            <div class="criteria-item">
              <input type="text" name="criteria_name[]" placeholder="e.g. Presentation" required>
              <input type="number" name="max_marks[]" placeholder="Marks" class="marks-input" min="1" required>
              <button type="button" class="btn-remove" onclick="removeCriteria(this)">✕</button>
            </div>
          </div>
          <button type="button" class="btn-add-criteria" onclick="addCriteria()">+ Add Criteria</button>
          <div class="total-marks" id="totalMarks">Total: 0 marks</div>
        </div>
        <button type="submit" class="btn-primary">Schedule Defence</button>
      </form>
    </div>

    <!-- Schedules List - API se -->
    <div class="card">
      <h3 id="schedules-title">Scheduled Defences</h3>
      <div id="schedules-container">
        <div class="loading">Loading schedules...</div>
      </div>
    </div>
  </div>
</div>

<script>
const token = localStorage.getItem('api_token');

function validateForm() {
    const group = document.getElementById('groupSelect').value;
    const panel = document.getElementById('panelSelect').value;
    const faculty = document.getElementById('facultySelect').value;
    if (!group) { alert('Please select a group!'); return false; }
    if (!panel) { alert('Please select a panel!'); return false; }
    if (!faculty) { alert('Please select a faculty!'); return false; }
    return true;
}

async function loadGroups() {
    const res = await fetch('/api/focal/groups', {
        headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
    });
    const data = await res.json();
    const sel = document.getElementById('groupSelect');
    sel.innerHTML = '<option value="">-- Select Group --</option>';
    if (data.success && data.groups.length > 0) {
        data.groups
            .filter(g => g.proposal_status === 'pending' || g.proposal_status === 'approved')
            .forEach(g => {
                const opt = document.createElement('option');
                opt.value = g.id;
                opt.textContent = g.group_code + ' — ' + (g.project_title || g.group_code);
                sel.appendChild(opt);
            });
    } else {
        sel.innerHTML = '<option value="">No groups available</option>';
    }
}
// Wait for page to fully load before calling API
document.addEventListener('DOMContentLoaded', function() {
    if (localStorage.getItem('api_token')) {
        window.onload = function(){ setTimeout(loadGroups, 300); };
    } else {
        setTimeout(loadGroups, 500);
    }
});
const csrfToken = document.querySelector('meta[name=csrf-token]').content;

async function loadSchedules() {
    const res  = await fetch('/api/focal/schedules', {
        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        }
    });
    const data = await res.json();
    const container = document.getElementById('schedules-container');

    if (!data.success || data.schedules.length === 0) {
        container.innerHTML = '<div class="empty-state">No schedules yet.</div>';
        document.getElementById('schedules-title').textContent = 'Scheduled Defences (0)';
        return;
    }

    document.getElementById('schedules-title').textContent = 'Scheduled Defences (' + data.schedules.length + ')';
    container.innerHTML = '';

    data.schedules.forEach(sch => {
        const badgeClass = sch.status === 'completed' ? 'badge-completed' : 'badge-scheduled';
        const badgeText  = sch.status === 'completed' ? 'Completed' : 'Scheduled';

        let criteriaHtml = sch.criteria.map(c =>
            `<span class="criteria-tag">${c.name}: ${c.max_marks}m</span>`
        ).join('');

        let actionsHtml = '';
        if (sch.status === 'scheduled') {
            actionsHtml += `
                <form method="POST" action="/focal/defence/schedules/${sch.id}/complete">
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <button type="submit" class="btn-sm btn-complete">Mark Complete</button>
                </form>`;
        }
        actionsHtml += `
            <form method="POST" action="/focal/defence/schedules/${sch.id}/delete" onsubmit="return confirm('Delete this schedule?')">
                <input type="hidden" name="_token" value="${csrfToken}">
                <button type="submit" class="btn-sm btn-delete">Delete</button>
            </form>`;

        container.innerHTML += `
            <div class="schedule-item">
                <div class="schedule-header">
                    <span class="group-code">${sch.group_code}</span>
                    <span class="${badgeClass}">${badgeText}</span>
                </div>
                <p style="font-size:12px;color:#1e3a5f;font-weight:600;margin-bottom:6px">${sch.project_title}</p>
                <div class="schedule-info">
                    <span>👤 ${sch.faculty}</span>
                    <span>📅 ${sch.defence_date}</span>
                    <span>🕐 ${sch.defence_time}</span>
                    <span>🏫 ${sch.room}</span>
                </div>
                <div class="criteria-tags">${criteriaHtml}</div>
                <div class="schedule-actions">${actionsHtml}</div>
            </div>`;
    });
}

function loadFacultys(sel) {
    const opt      = sel.options[sel.selectedIndex];
    const facultys = opt.dataset.facultys ? JSON.parse(opt.dataset.facultys) : [];
    const ts       = document.getElementById('facultySelect');
    ts.innerHTML   = '<option value="">-- Select Faculty --</option>';
    facultys.forEach(t => {
        ts.innerHTML += `<option value="${t.id}">${t.name}</option>`;
    });
}

function addCriteria() {
    const wrap = document.getElementById('criteriaWrap');
    const div  = document.createElement('div');
    div.className = 'criteria-item';
    div.innerHTML = `
        <input type="text" name="criteria_name[]" placeholder="e.g. Viva" required>
        <input type="number" name="max_marks[]" placeholder="Marks" class="marks-input" min="1" required onchange="updateTotal()">
        <button type="button" class="btn-remove" onclick="removeCriteria(this)">✕</button>`;
    wrap.appendChild(div);
}

function removeCriteria(btn) {
    const items = document.querySelectorAll('.criteria-item');
    if(items.length > 1) { btn.parentElement.remove(); updateTotal(); }
}

function updateTotal() {
    const inputs = document.querySelectorAll('input[name="max_marks[]"]');
    let total = 0;
    inputs.forEach(i => total += parseInt(i.value||0));
    document.getElementById('totalMarks').textContent = 'Total: ' + total + ' marks';
}

document.getElementById('criteriaWrap').addEventListener('input', updateTotal);

loadSchedules();
</script>
</body>
</html>