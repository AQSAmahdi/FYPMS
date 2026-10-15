<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Group Tracking Detail - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
.main{margin-left:260px;padding:28px 36px 40px}
.topbar{background:#0C447C;border-radius:14px;padding:20px 26px;margin-bottom:24px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 1px 12px rgba(12,68,124,.15)}
.topbar h2{color:#fff;font-size:19px;font-weight:700;display:flex;align-items:center;gap:10px}
.topbar-icon{width:36px;height:36px;border-radius:10px;background:rgba(255,255,255,.15);display:inline-flex;align-items:center;justify-content:center;flex-shrink:0}
.topbar-icon svg{width:18px;height:18px}
.card{background:#fff;border-radius:14px;padding:24px 26px;box-shadow:0 1px 14px rgba(12,68,124,.08);border:1px solid #e8f0f9;margin-bottom:22px}
.card-title{color:#0C447C;font-size:15px;font-weight:700;margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid #f0f4f8;display:flex;align-items:center;gap:10px}
.card-icon{width:32px;height:32px;border-radius:8px;background:#0C447C;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.card-icon svg{width:16px;height:16px}
.info-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin-bottom:16px}
.info-box{background:#f8fafc;border-radius:8px;padding:12px 16px}
.info-label{font-size:11px;color:#8096b0;font-weight:600;text-transform:uppercase;margin-bottom:4px}
.info-value{font-size:14px;color:#1e3a5f;font-weight:600}
.attempt-card{border:1.5px solid #e8f0f9;border-radius:10px;padding:16px;margin-bottom:12px}
.attempt-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px}
.attempt-title{font-weight:700;color:#0C447C;font-size:14px}
.badge{padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600}
.badge-pass{background:#dcfce7;color:#166534}
.badge-fail{background:#fee2e2;color:#991b1b}
.badge-pending{background:#fef9c3;color:#854d0e}
.progress-wrap{background:#f0f4f8;border-radius:20px;height:8px;margin:8px 0}
.progress-bar{height:8px;border-radius:20px;background:linear-gradient(90deg,#0C447C,#3b82f6)}
table{width:100%;border-collapse:collapse;font-size:13px}
th{background:#f8fafc;color:#8096b0;font-weight:600;padding:10px 12px;text-align:left;font-size:12px}
td{padding:10px 12px;border-bottom:1px solid #f0f4f8;color:#1e3a5f}
tr:last-child td{border-bottom:none}
.btn-back{background:#f0f4f8;color:#0C447C;border:none;padding:9px 18px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:6px;transition:background .2s}
.btn-back:hover{background:#e2ecf6}
.btn-pdf{background:#0C447C;color:#fff;border:none;padding:9px 20px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:7px;transition:background .2s}
.btn-pdf:hover{background:#185FA5}
.member-tag{display:inline-block;background:#e8f0f9;color:#0C447C;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;margin:3px}
.empty{text-align:center;color:#8096b0;padding:24px;font-size:13px}
@media print{.main{margin-left:0!important}.topbar button,.btn-back,.btn-pdf{display:none!important}body{background:#fff}}
@media(max-width:768px){.main{margin-left:0;padding:16px}}
</style>
</head>
<body>
@include('focal.partials.sidebar')
<div class="main">
  <div class="topbar">
    <div>
      <h2><span class="topbar-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg></span><span id="page-title">Group Tracking Detail</span></h2>
      <p style="color:#c9dbee;font-size:12px;margin-top:3px;margin-left:46px">Complete FYP history for this group</p>
    </div>
    <div style="display:flex;gap:10px">
      <a href="/focal/group-tracking" class="btn-back"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>Back</a>
      <button class="btn-pdf" onclick="window.print()"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>Download PDF</button>
    </div>
  </div>

  <div id="loading" style="text-align:center;padding:40px;color:#8096b0">Loading group data...</div>
  <div id="content" style="display:none">

    <!-- Group Info -->
    <div class="card">
      <div class="card-title">Group Information</div>
      <div class="info-grid">
        <div class="info-box"><div class="info-label">Group Code</div><div class="info-value" id="group-code">-</div></div>
        <div class="info-box"><div class="info-label">Project Title</div><div class="info-value" id="project-title">-</div></div>
        <div class="info-box"><div class="info-label">Supervisor</div><div class="info-value" id="supervisor">-</div></div>
        <div class="info-box"><div class="info-label">Status</div><div class="info-value" id="group-status">-</div></div>
      </div>
      <div><span style="font-size:12px;color:#8096b0;font-weight:600">Members:</span><div id="members" style="margin-top:8px"></div></div>
    </div>

    <!-- Defence History -->
    <div class="card">
      <div class="card-title"><span class="card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></svg></span>Defence History</div>
      <div id="defence-list"><div class="empty">No defence records found</div></div>
    </div>

    <!-- Phase Marks -->
    <div class="card">
      <div class="card-title"><span class="card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6"/><path d="M9 13h6"/><path d="M9 17h6"/></svg></span>Evaluation Phase Marks</div>
      <div id="phase-marks-list"><div class="empty">No phase marks found</div></div>
    </div>

  </div>
</div>

<script>
const groupId = {{ $group->id }};
const token   = localStorage.getItem('api_token');

async function loadDetail() {
    try {
        const res  = await fetch('/api/focal/group-tracking/' + groupId, {
            headers: { 'Authorization': 'Bearer ' + token }
        });
        const data = await res.json();
        if (!data.group) { document.getElementById('loading').textContent = 'Error loading data'; return; }

        document.getElementById('loading').style.display   = 'none';
        document.getElementById('content').style.display   = 'block';
        document.getElementById('page-title').textContent  = data.group.group_code;
        document.getElementById('group-code').textContent  = data.group.group_code;
        document.getElementById('project-title').textContent = data.group.project_title;
        document.getElementById('supervisor').textContent  = data.group.supervisor;
        document.getElementById('group-status').textContent = data.group.status;

        // Members
        document.getElementById('members').innerHTML =
            data.group.members.map(m => `<span class="member-tag">${m.name}</span>`).join('');

        // Defence History
        if (data.defences.length > 0) {
            document.getElementById('defence-list').innerHTML = data.defences.map(d => `
                <div class="attempt-card">
                    <div class="attempt-header">
                        <span class="attempt-title">Attempt #${d.attempt} — ${d.date ?? 'Date not set'}</span>
                        <span class="badge ${d.result==='pass'?'badge-pass':d.result==='fail'?'badge-fail':'badge-pending'}">
                            ${d.result==='pass'?'Pass':d.result==='fail'?'Fail':'Pending'}
                        </span>
                    </div>
                    <div style="font-size:13px;color:#5a7a9a;margin-bottom:8px">
                        Evaluator: ${d.evaluator} &nbsp;|&nbsp; Marks: ${d.total_obtained}/${d.total_max}
                    </div>
                    <div class="progress-wrap"><div class="progress-bar" style="width:${d.percentage}%"></div></div>
                    <div style="font-size:12px;color:#8096b0;margin-top:4px">${d.percentage}%</div>
                    ${d.failure_reason ? `<div style="margin-top:8px;font-size:12px;color:#b91c1c">Failure Reason: ${d.failure_reason}</div>` : ''}
                </div>`).join('');
        }

        // Phase Marks
        if (data.phase_marks.length > 0) {
            document.getElementById('phase-marks-list').innerHTML = data.phase_marks.map(p => `
                <div style="margin-bottom:20px">
                    <div style="font-weight:700;color:#0C447C;font-size:14px;margin-bottom:10px">${p.phase}</div>
                    <table>
                        <thead><tr><th>Student</th><th>Marks Obtained</th><th>Max Marks</th><th>Evaluator</th></tr></thead>
                        <tbody>${p.marks.map(m => `
                            <tr>
                                <td>${m.student}</td>
                                <td>${m.obtained}</td>
                                <td>${m.max}</td>
                                <td>${m.evaluator}</td>
                            </tr>`).join('')}
                        </tbody>
                    </table>
                </div>`).join('');
        }

    } catch(e) {
        document.getElementById('loading').textContent = 'Error loading data';
    }
}

loadDetail();
</script>
</body>
</html>
