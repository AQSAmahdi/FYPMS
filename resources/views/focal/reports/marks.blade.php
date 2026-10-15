<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Marks Report - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
.main{margin-left:260px;padding:28px 32px}
.topbar{background:#0C447C;border-radius:14px;padding:20px 26px;margin-bottom:24px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 1px 12px rgba(12,68,124,.15)}
.topbar h2{color:#fff;font-size:19px;font-weight:700}
.topbar p{color:#c9dbee;font-size:12px;margin-top:3px}
.badge-role{background:rgba(255,255,255,.15);color:#fff;padding:6px 16px;border-radius:20px;font-size:12px;font-weight:600}
.card{background:#fff;border-radius:12px;padding:22px;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9;margin-bottom:20px}
.card h3{color:#0C447C;font-size:15px;font-weight:700;margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid #e8f0f9}
.filters{display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end}
.filter-group{display:flex;flex-direction:column;gap:6px}
.filter-group label{font-size:12px;font-weight:700;color:#0C447C;text-transform:uppercase;letter-spacing:.6px}
select{padding:9px 14px;border:1.5px solid #e8f0f9;border-radius:8px;font-size:13px;color:#1e3a5f;outline:none;min-width:160px;background:#fff}
select:focus{border-color:#0C447C}
.btn-filter{background:#0C447C;color:#fff;border:none;padding:10px 20px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer}
.btn-print{background:#0C447C;color:#fff;border:none;padding:10px 20px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:8px;transition:background .2s}
.btn-print:hover{background:#185FA5}
.btn-export{background:#0e7490;color:#fff;border:none;padding:10px 20px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:8px;transition:background .2s}
.btn-export:hover{background:#0891b2}
.top-actions{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;flex-wrap:wrap;gap:10px}
.table-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse;font-size:13px}
thead th{background:#0C447C;color:#fff;font-weight:700;padding:10px 12px;text-align:left;white-space:nowrap}
tbody td{padding:9px 12px;border-bottom:1px solid #f0f4f8;color:#1e3a5f;vertical-align:middle}
tbody tr:hover{background:#f8fafd}
.group-row td{background:#e8f0f9;font-weight:700;color:#0C447C;padding:8px 12px;border-left:4px solid #0C447C}
.loading{text-align:center;padding:40px;color:#8096b0}
.badge-adjusted{background:#e0e7ff;color:#3730a3;padding:2px 8px;border-radius:20px;font-size:11px;font-weight:600}
.badge-submitted{background:#dcfce7;color:#166534;padding:2px 8px;border-radius:20px;font-size:11px;font-weight:600}
.badge-pending{background:#fef9c3;color:#854d0e;padding:2px 8px;border-radius:20px;font-size:11px;font-weight:600}
@media print{
  .no-print{display:none!important}
  .main{margin-left:0!important;padding:10px}
  body{background:#fff}
  .card{box-shadow:none;border:1px solid #ddd}
  thead th{background:#0C447C!important;-webkit-print-color-adjust:exact;print-color-adjust:exact}
  .group-row td{background:#e8f0f9!important;-webkit-print-color-adjust:exact;print-color-adjust:exact}
}
@media(max-width:768px){.main{margin-left:0;padding:16px}}
.print-header{display:none}
@media print{
  .print-header{display:block;margin-bottom:18px;border-bottom:2px solid #0C447C;padding-bottom:12px}
  .ph-top{display:flex;align-items:center;gap:16px;justify-content:center;margin-bottom:8px}
  .ph-logo{width:58px;height:58px;object-fit:contain}
  .ph-uni h1{font-size:17px;color:#0C447C;font-weight:700;letter-spacing:.3px}
  .ph-uni p{font-size:11px;color:#5a7a9a;margin-top:2px}
  .ph-title{font-size:14px;color:#1e3a5f;text-align:center;margin:8px 0 10px;text-transform:uppercase;letter-spacing:1.2px;font-weight:700}
  .ph-meta{display:flex;justify-content:center;gap:18px;font-size:11.5px;color:#1e3a5f;flex-wrap:wrap;row-gap:6px}
  .ph-meta span{background:#f4f8fd;padding:3px 10px;border-radius:6px}
  .ph-meta b{color:#0C447C}
}
</style>
</head>
<body>
@include('focal.partials.sidebar')
<div class="main">
  <div class="print-header" id="print_header">
    <div class="ph-top">
      <img src="/images/bgnu.jfif" class="ph-logo" alt="University Logo">
      <div class="ph-uni">
        <h1>Baba Guru Nanak University Nankana Sahib</h1>
        <p>Final Year Project Management System</p>
      </div>
    </div>
    <div class="ph-title">Marks Report</div>
    <div class="ph-meta" id="ph_meta"></div>
  </div>
  <div class="topbar no-print">
    <div><h2>Marks Report</h2><p>Phase wise marks — session & department filter</p></div>
    <span class="badge-role">Focal Person</span>
  </div>
  <div class="card no-print">
    <h3>Filter</h3>
    <div class="filters">
      <div class="filter-group">
        <label>Session</label>
        <select id="f_session"><option value="">All Sessions</option></select>
      </div>
      <div class="filter-group">
        <label>Department</label>
        <select id="f_dept"><option value="">All Departments</option></select>
      </div>
      <div class="filter-group">
        <label>Phase</label>
        <select id="f_phase"><option value="">All Phases</option></select>
      </div>
      <button class="btn-filter" onclick="loadMarks()">Apply</button>
    </div>
  </div>
  <div class="card">
    <div class="top-actions">
      <h3>Marks — <span id="total_count">0</span> records</h3>
      <button class="btn-print no-print" onclick="printReport()"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>Print / PDF</button>
      <button class="btn-export no-print" onclick="exportExcel()" style="margin-left:8px"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6"/><path d="M8 13h8"/><path d="M8 17h8"/></svg>Export Excel</button>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>#</th><th>Student ID</th><th>Name</th><th>Session</th>
            <th>Department</th><th>Phase</th><th>Marks</th><th>Total</th>
            <th>Converted</th><th>Evaluator</th><th>Status</th>
          </tr>
        </thead>
        <tbody id="marks_body">
          <tr><td colspan="11" class="loading">Loading...</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script>
const token = localStorage.getItem('api_token');

function exportExcel() {
    if (!lastMarks || lastMarks.length === 0) {
        alert('No data to export. Please apply filters first.');
        return;
    }
    const headers = ['#','Student ID','Name','Session','Department','Phase','Marks Obtained','Total Marks','Converted Marks','Evaluator'];
    const rows = lastMarks.map((m, i) => [
        i + 1,
        m.student_id || '',
        m.student_name || '',
        m.session || '',
        m.department || '',
        m.phase || '',
        m.marks_obtained ?? '',
        m.total_marks ?? '',
        m.converted_marks ?? '',
        m.evaluator || ''
    ]);

    let csv = headers.join(',') + '\n';
    rows.forEach(row => {
        csv += row.map(val => '"' + String(val).replace(/"/g, '""') + '"').join(',') + '\n';
    });

    const blob = new Blob(['\uFEFF' + csv], {type: 'text/csv;charset=utf-8;'});
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'marks_report_' + new Date().toISOString().slice(0,10) + '.csv';
    a.click();
    URL.revokeObjectURL(url);
}
let lastMarks = [];
async function loadFilters() {
    const [sr, dr, pr] = await Promise.all([
        fetch('/api/focal/sessions',    {headers:{'Authorization':'Bearer '+token}}),
        fetch('/api/focal/departments', {headers:{'Authorization':'Bearer '+token}}),
        fetch('/api/focal/phases-list', {headers:{'Authorization':'Bearer '+token}})
    ]);
    const sd = await sr.json();
    const dd = await dr.json();
    const pd = await pr.json();
    (sd.sessions||[]).forEach(s => document.getElementById('f_session').innerHTML += `<option value="${s.id}">${s.name}</option>`);
    (dd.departments||[]).forEach(d => document.getElementById('f_dept').innerHTML += `<option value="${d.id}">${d.name}</option>`);
    (pd.phases||[]).forEach(p => document.getElementById('f_phase').innerHTML += `<option value="${p.id}">${p.name}</option>`);
}
async function loadMarks() {
    const session = document.getElementById('f_session').value;
    const dept    = document.getElementById('f_dept').value;
    const phase   = document.getElementById('f_phase').value;
    let url = '/api/focal/marks-report?';
    if(session) url += `session_id=${session}&`;
    if(dept)    url += `department_id=${dept}&`;
    if(phase)   url += `phase_id=${phase}&`;
    document.getElementById('marks_body').innerHTML = '<tr><td colspan="11" class="loading">Loading...</td></tr>';
    const res  = await fetch(url, {headers:{'Authorization':'Bearer '+token}});
    const data = await res.json();
    const marks = data.marks || [];
    lastMarks = marks;
    document.getElementById('total_count').textContent = marks.length;
    const grouped = {};
    marks.forEach(m => {
        const key = (m.session||'Unknown') + ' | ' + (m.department||'Unknown');
        if(!grouped[key]) grouped[key] = [];
        grouped[key].push(m);
    });
    let html = '', sr = 1;
    Object.keys(grouped).sort().forEach(key => {
        html += `<tr class="group-row"><td colspan="11">${key} (${grouped[key].length} records)</td></tr>`;
        grouped[key].forEach(m => {
            const statusBadge = m.is_adjusted
                ? '<span class="badge-adjusted">Adjusted</span>'
                : m.marks_obtained !== null
                    ? '<span class="badge-submitted">Submitted</span>'
                    : '<span class="badge-pending">Pending</span>';
            html += `<tr>
                <td>${sr++}</td>
                <td>${m.student_id||'—'}</td>
                <td style="font-weight:600">${m.student_name}</td>
                <td>${m.session||'—'}</td>
                <td>${m.department||'—'}</td>
                <td>${m.phase||'—'}</td>
                <td style="font-weight:700;color:#0C447C">${m.marks_obtained??'—'}</td>
                <td>${m.total_marks||'—'}</td>
                <td style="font-weight:700;color:#166534">${m.converted_marks??'—'}</td>
                <td>${m.evaluator||'—'}</td>
                <td>${statusBadge}</td>
            </tr>`;
        });
    });
    document.getElementById('marks_body').innerHTML = html || '<tr><td colspan="11" class="loading">No records found.</td></tr>';
}
function getSelectedText(id) {
    const el = document.getElementById(id);
    return (el && el.value && el.options[el.selectedIndex]) ? el.options[el.selectedIndex].text : null;
}
function buildPrintHeader(marks) {
    const deptText    = getSelectedText('f_dept')    || 'All Departments';
    const sessionText = getSelectedText('f_session') || 'All Sessions';
    const phaseText   = getSelectedText('f_phase')   || 'All Phases';
    const today = new Date().toLocaleDateString('en-PK', {day:'numeric',month:'long',year:'numeric'});

    document.getElementById('ph_meta').innerHTML = `
        <span><b>Department:</b> ${deptText}</span>
        <span><b>Session:</b> ${sessionText}</span>
        <span><b>Phase:</b> ${phaseText}</span>
        <span><b>Total Records:</b> ${marks.length}</span>
        <span><b>Date:</b> ${today}</span>
    `;
}
function printReport() {
    buildPrintHeader(lastMarks);
    window.print();
}
function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('overlay')?.classList.toggle('show');
}
loadFilters();
loadMarks();
</script>
</body>
</html>
