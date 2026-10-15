<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Defence Results - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
.main{margin-left:260px;padding:24px 28px;min-height:100vh}
.topbar{background:#fff;border-radius:12px;padding:16px 22px;margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9}
.topbar h2{color:#0C447C;font-size:18px;font-weight:700}
.topbar p{color:#8096b0;font-size:12px;margin-top:2px}
.badge-role{background:#e8f0f9;color:#0C447C;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600}
.card{background:#fff;border-radius:12px;padding:22px;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9;margin-bottom:20px}
.card h3{color:#0C447C;font-size:15px;font-weight:700;margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid #e8f0f9}
.result-item{border:1.5px solid #e8f0f9;border-radius:10px;padding:16px;margin-bottom:14px}
.result-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px}
.group-info h4{font-size:15px;font-weight:700;color:#0C447C}
.group-info p{font-size:12px;color:#5a7a9a;margin-top:2px}
.badge-approved{background:#e8f5e9;color:#2e7d32;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700}
.badge-rejected{background:#fce4e4;color:#c62828;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700}
.badge-pending{background:#fff3cd;color:#856404;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700}
.marks-section{background:#f8fafc;border-radius:8px;padding:12px;margin-bottom:10px}
.marks-row{display:flex;justify-content:space-between;align-items:center;padding:5px 0;border-bottom:1px solid #e8f0f9;font-size:13px}
.marks-row:last-child{border-bottom:none}
.marks-row .criteria-name{color:#1e3a5f;font-weight:500}
.marks-row .marks-val{color:#0C447C;font-weight:700}
.total-row{display:flex;justify-content:space-between;padding:8px 0 0;font-size:14px;font-weight:700;color:#0C447C;border-top:2px solid #e8f0f9;margin-top:4px}
.progress-bar-wrap{background:#e8f0f9;border-radius:20px;height:8px;margin:8px 0;overflow:hidden}
.progress-bar{height:100%;border-radius:20px;background:linear-gradient(90deg,#0C447C,#38bdf8)}
.students-row{display:flex;flex-wrap:wrap;gap:6px;margin-top:8px}
.student-tag{background:#e8f0f9;color:#0C447C;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:500}
.meta-info{display:flex;flex-wrap:wrap;gap:12px;font-size:12px;color:#5a7a9a;margin-bottom:10px}
.empty-state{text-align:center;padding:60px;color:#8096b0;font-size:14px}
.loading{text-align:center;padding:40px;color:#8096b0;font-size:14px}

.supervisor-assign-sec { margin-top: 15px; padding: 12px; background: #f4f8fc; border-radius: 8px; border: 1.5px dashed #0C447C; display: flex; flex-direction: column; gap: 8px; }
.sup-flex-row { display: flex; gap: 10px; align-items: center; }
.sup-select { flex: 1; padding: 8px 12px; border-radius: 6px; border: 1.5px solid #cbd5e1; font-size: 13px; color: #1e293b; background: #fff; }
.sup-btn { background: #0C447C; color: #fff; border: none; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: background 0.2s; }
.sup-btn:hover { background: #0a3561; }
.sup-btn.edit-mode { background: #475569; }
.sup-btn.edit-mode:hover { background: #334155; }
@media(max-width:768px){
    .main{margin-left:0;padding:16px}
    .topbar{flex-direction:column;align-items:flex-start;gap:8px}
    .result-header{flex-direction:column;gap:8px}
}
</style>
</head>
<body>
@include('focal.partials.sidebar')
<div class="main">
  <div class="topbar">
    <div>
      <h2>Defence Results</h2>
      <p id="results-count">Loading...</p>
    </div>
    <span class="badge-role">Focal Person</span>
  </div>

  <div class="card">
    <h3 id="card-title">Completed Defences</h3>
    <div id="results-container">
      <div class="loading">Loading results...</div>
    </div>
  </div>
</div>

<script>
const token = localStorage.getItem('api_token');

async function loadResults() {
    const res  = await fetch('/api/focal/results', {
        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        }
    });
    const data = await res.json(); dataResultsGlobal = data.results || [];
    const container = document.getElementById('results-container');

    if (!data.success || data.results.length === 0) {
        container.innerHTML = '<div class="empty-state">No completed defences yet.</div>';
        document.getElementById('results-count').textContent = '0 completed defences';
        document.getElementById('card-title').textContent = 'Completed Defences (0)';
        return;
    }

    const total = data.results.length;
    document.getElementById('results-count').textContent = total + ' completed defence(s)';
    document.getElementById('card-title').textContent = 'Completed Defences (' + total + ')';

    container.innerHTML = '';

    data.results.forEach(sch => {
        // Auto pass/fail based on percentage
        const autoResult = sch.percentage >= 50 ? 'pass' : 'fail';
        let badgeHtml = '';
        if (autoResult === 'pass') {
            badgeHtml = '<span class="badge-approved">✓ Pass (' + sch.percentage + '%)</span>';
        } else {
            badgeHtml = '<span class="badge-rejected">✗ Fail (' + sch.percentage + '%)</span>';
        }
        let actionHtml = '<p style="margin-top:8px;font-size:12px;color:#8096b0;">' +
            (sch.result_remarks ? 'Remarks: ' + sch.result_remarks : '') + '</p>';
        let criteriaHtml = '';
        sch.criteria.forEach(c => {
            criteriaHtml += `
                <div class="marks-row">
                    <span class="criteria-name">${c.name}</span>
                    <span class="marks-val">${c.obtained} / ${c.max_marks}</span>
                </div>`;
        });

        let membersHtml = '';
        sch.members.forEach(m => {
            membersHtml += `<span class="student-tag">${m.name}</span>`;
        });

        container.innerHTML += `
            <div class="result-item">
                <div class="result-header">
                    <div class="group-info">
                        <h4>${sch.group_code}</h4>
                        <p>${sch.project_title}</p>
                    </div>
                    ${badgeHtml}
                </div>
                <div class="meta-info">
                    <span>Evaluated by: ${sch.faculty}</span>
                    <span>${sch.defence_date}</span>
                    <span>${sch.defence_time}</span>
                    <span>${sch.room}</span>
                </div>
                <div class="marks-section">
                    ${criteriaHtml}
                    <div class="total-row">
                        <span>Total Marks</span>
                        <span>${sch.total_obtained} / ${sch.total_max} (${sch.percentage}%)</span>
                    </div>
                </div>
                <div class="progress-bar-wrap">
                    <div class="progress-bar" style="width:${sch.percentage}%"></div>
                </div>
                <div style="font-size:12px;color:#5a7a9a;margin-bottom:6px">Attempt: #${sch.attempt_number} &nbsp;|&nbsp; Group Members:</div>
                <div class="students-row">${membersHtml}</div>
                ${actionHtml}
                ${autoResult === 'pass' && sch.group_id ? `
                <div class="supervisor-assign-sec">
                    <p style="color:#0C447C; font-size:13px; font-weight:600;"><i class="fas fa-user-tie"></i> ${sch.supervisor_id ? 'Current Supervisor Assigned ✅' : 'Assign Supervisor for this Group'}</p>
                    <div class="sup-flex-row">
                        <select id="sup-select-${sch.group_id}" class="sup-select">
                            <option value="">-- Select Faculty / Search --</option>
                        </select>
                        <button onclick="assignSupervisor(${sch.group_id})" id="sup-btn-${sch.group_id}" class="sup-btn ${sch.supervisor_id ? 'edit-mode' : ''}">
                            ${sch.supervisor_id ? '🔄 Edit Supervisor' : '➕ Assign Supervisor'}
                        </button>
                    </div>
                </div>
                ` : ''}
                ${autoResult === 'fail' ? `
                <div style="margin-top:12px;padding:10px;background:#fef2f2;border-radius:8px;border:1px solid #fecaca;">
                    <p style="color:#b91c1c;font-size:13px;font-weight:600;margin-bottom:6px">⚠️ Group Failed — Allow Re-attempt?</p>
                    <input type="text" id="fail-reason-${sch.id}" placeholder="Reason for failure (optional)" style="width:100%;padding:7px 12px;border:1.5px solid #fecaca;border-radius:8px;font-size:13px;margin-bottom:8px;box-sizing:border-box;">
                    <button onclick="allowReAttempt(${sch.id})" style="background:#dc2626;color:#fff;border:none;padding:8px 18px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;">🔄 Allow Re-attempt</button>
                </div>` : ''}
            </div>`;
    });
}

loadResults().then(() => { populateDropdowns(); });

async function updateResult(id, result) {
    const remarks = document.getElementById('remarks-' + id)?.value || '';
    const token = localStorage.getItem('api_token');
    const res = await fetch('/focal/defence/results/' + id + '/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
        },
        body: JSON.stringify({ result, remarks, _token: '{{ csrf_token() }}' })
    });
    const data = await res.json(); dataResultsGlobal = data.results || [];
    if (data.success) {
        loadResults().then(() => { populateDropdowns(); });
    } else {
        alert('Error updating result');
    }
}

async function allowReAttempt(id) {
    const reason = document.getElementById('fail-reason-' + id)?.value || '';
    const res = await fetch('/focal/defence/results/' + id + '/reattempt', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ reason })
    });
    const data = await res.json(); dataResultsGlobal = data.results || [];
    if (data.success) {
        alert('Re-attempt allowed! New defence schedule created.');
        loadResults().then(() => { populateDropdowns(); });
    } else {
        alert('Error: ' + (data.message || 'Something went wrong'));
    }
}

async function assignSupervisor(groupId) {
    const facultyId = document.getElementById('sup-select-' + groupId).value;
    if(!facultyId) {
        alert("Please select a faculty member first.");
        return;
    }
    
    if(!confirm("Are you sure you want to update the supervisor for this group?")) return;

    try {
        const res = await fetch(`/focal/groups/${groupId}/assign-faculty`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
            },
            body: JSON.stringify({ faculty_id: facultyId, _token: '{{ csrf_token() }}' })
        });
        
        // Handling both redirect response or pure json status response cleanly
        if(res.ok || res.status === 200) {
            alert("Supervisor updated successfully!");
            location.reload();
        } else {
            alert("Something went wrong while processing request.");
        }
    } catch(err) {
        alert("Execution failed. Check connection parameters.");
    }
}

async function populateDropdowns() {
    const res = await fetch('/api/focal/faculty-members', {
        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        }
    });
    const data = await res.json();
    if (!data.success) return;
    const faculty = data.faculty_members || [];

    document.querySelectorAll('select.sup-select').forEach(select => {
        const groupId = select.id.replace('sup-select-', '');
        const sch = (dataResultsGlobal || []).find(s => String(s.group_id) === String(groupId));
        const currentSupervisorId = sch ? sch.supervisor_id : null;

        let optionsHtml = '<option value="">-- Select Faculty / Search --</option>';
        faculty.forEach(f => {
            const selected = (currentSupervisorId && String(currentSupervisorId) === String(f.id)) ? 'selected' : '';
            optionsHtml += `<option value="${f.id}" ${selected}>${f.name}${f.designation ? ' - ' + f.designation : ''}</option>`;
        });
        select.innerHTML = optionsHtml;
    });
}

</script>
</body>
</html>