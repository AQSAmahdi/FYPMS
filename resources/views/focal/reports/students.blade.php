<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Students Report - FYPMS</title>
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
.top-actions{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;flex-wrap:wrap;gap:10px}
.table-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse;font-size:13px}
thead th{background:#0C447C;color:#fff;font-weight:700;padding:10px 12px;text-align:left;white-space:nowrap}
tbody td{padding:9px 12px;border-bottom:1px solid #f0f4f8;color:#1e3a5f}
tbody tr:hover{background:#f8fafd}
.group-row td{background:#e8f0f9;font-weight:700;color:#0C447C;font-size:13px;padding:8px 12px;border-left:4px solid #0C447C}
.loading{text-align:center;padding:40px;color:#8096b0}
.stats-bar{display:flex;gap:12px;margin-bottom:16px;flex-wrap:wrap}
.stat{background:#f4f8fd;border-radius:8px;padding:8px 16px;font-size:13px;color:#0C447C;font-weight:600}
.stat span{color:#8096b0;font-weight:400;margin-right:4px}
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
    <div class="ph-title">Students Report</div>
    <div class="ph-meta" id="ph_meta"></div>
  </div>
  <div class="topbar no-print">
    <div><h2>Students Report</h2><p>Session & Department wise student list</p></div>
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
        <label>Shift</label>
        <select id="f_shift">
          <option value="">All Shifts</option>
          <option value="Morning">Morning</option>
          <option value="Evening">Evening</option>
        </select>
      </div>
      <button class="btn-filter" onclick="loadStudents()">Apply</button>
    </div>
  </div>
  <div class="card">
    <div class="top-actions">
      <h3>Students — <span id="total_count">0</span></h3>
      <button class="btn-print no-print" onclick="printReport()"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>Print / PDF</button>
    </div>
    <div class="stats-bar" id="stats_bar"></div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>#</th><th>Student ID</th><th>Reg No</th><th>Name</th>
            <th>Session</th><th>Department</th><th>Semester</th><th>Shift</th>
            <th>Group</th><th>Supervisor</th><th>Status</th>
          </tr>
        </thead>
        <tbody id="students_body">
          <tr><td colspan="11" class="loading">Loading...</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script>
const token = localStorage.getItem('api_token');
let lastStudents = [];
async function loadFilters() {
    const [sr, dr] = await Promise.all([
        fetch('/api/focal/sessions',    {headers:{'Authorization':'Bearer '+token}}),
        fetch('/api/focal/departments', {headers:{'Authorization':'Bearer '+token}})
    ]);
    const sd = await sr.json();
    const dd = await dr.json();
    const ss = document.getElementById('f_session');
    const ds = document.getElementById('f_dept');
    (sd.sessions||[]).forEach(s => ss.innerHTML += `<option value="${s.id}">${s.name}</option>`);
    (dd.departments||[]).forEach(d => ds.innerHTML += `<option value="${d.id}">${d.name}</option>`);
}
async function loadStudents() {
    const session = document.getElementById('f_session').value;
    const dept    = document.getElementById('f_dept').value;
    const shift   = document.getElementById('f_shift').value;
    let url = '/api/focal/students?';
    if(session) url += `session_id=${session}&`;
    if(dept)    url += `department_id=${dept}&`;
    if(shift)   url += `shift=${shift}&`;
    document.getElementById('students_body').innerHTML = '<tr><td colspan="11" class="loading">Loading...</td></tr>';
    const res  = await fetch(url, {headers:{'Authorization':'Bearer '+token}});
    const data = await res.json();
    const students = data.students || [];
    lastStudents = students;
    document.getElementById('total_count').textContent = students.length + ' students';
    const grouped = {};
    students.forEach(s => {
        const key = (s.session||'Unknown') + ' | ' + (s.department||'Unknown Dept');
        if(!grouped[key]) grouped[key] = [];
        grouped[key].push(s);
    });
    const sessions = [...new Set(students.map(s=>s.session))].filter(Boolean);
    const depts    = [...new Set(students.map(s=>s.department))].filter(Boolean);
    document.getElementById('stats_bar').innerHTML =
        `<div class="stat"><span>Sessions:</span>${sessions.length}</div>
         <div class="stat"><span>Departments:</span>${depts.length}</div>
         <div class="stat"><span>Total:</span>${students.length}</div>`;
    let html = '', sr = 1;
    Object.keys(grouped).sort().forEach(key => {
        html += `<tr class="group-row"><td colspan="11">${key} (${grouped[key].length} students)</td></tr>`;
        grouped[key].forEach(s => {
            const statusColors = {
                'Completed':   ['#dcfce7', '#166534'],
                'In Progress': ['#fef9c3', '#854d0e'],
                'No Group':    ['#fef2f2', '#b91c1c'],
            };
            const [sc, st] = statusColors[s.status] || ['#f1f5f9', '#64748b'];
            html += `<tr>
                <td>${sr++}</td><td>${s.student_id||'—'}</td><td>${s.reg_no||'—'}</td>
                <td style="font-weight:600">${s.name}</td><td>${s.session||'—'}</td>
                <td>${s.department||'—'}</td><td>${s.semester||'—'}</td><td>${s.shift||'—'}</td>
                <td>${s.group_code||'—'}</td><td>${s.supervisor||'—'}</td>
                <td><span style="background:${sc};color:${st};padding:2px 8px;border-radius:20px;font-size:11px;font-weight:600">${s.status||'—'}</span></td>
            </tr>`;
        });
    });
    document.getElementById('students_body').innerHTML = html || '<tr><td colspan="11" class="loading">No students found.</td></tr>';
}
function getSelectedText(id) {
    const el = document.getElementById(id);
    return (el && el.value && el.options[el.selectedIndex]) ? el.options[el.selectedIndex].text : null;
}
function buildPrintHeader(students) {
    const deptText    = getSelectedText('f_dept')    || 'All Departments';
    const sessionText = getSelectedText('f_session') || 'All Sessions';
    const shiftEl     = document.getElementById('f_shift');
    const shiftText   = shiftEl.value || 'All Shifts';
    const semesters   = [...new Set(students.map(s => s.semester))].filter(Boolean);
    const semText     = semesters.length === 1 ? ('Semester ' + semesters[0]) : 'Multiple Semesters';
    const today = new Date().toLocaleDateString('en-PK', {day:'numeric',month:'long',year:'numeric'});

    document.getElementById('ph_meta').innerHTML = `
        <span><b>Program / Department:</b> ${deptText}</span>
        <span><b>Session:</b> ${sessionText}</span>
        <span><b>${semText}</b></span>
        <span><b>Shift:</b> ${shiftText}</span>
        <span><b>Total Students:</b> ${students.length}</span>
        <span><b>Date:</b> ${today}</span>
    `;
}
function printReport() {
    buildPrintHeader(lastStudents);
    window.print();
}
function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('overlay')?.classList.toggle('show');
}
loadFilters();
loadStudents();
</script>
</body>
</html>
