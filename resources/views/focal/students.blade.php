<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>All Students</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
@keyframes borderFlow{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
.main{margin-left:260px;flex:1;padding:28px 32px}
.topbar{background:#0C447C;border-radius:14px;padding:20px 26px;margin-bottom:24px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 4px 14px rgba(12,68,124,.18)}
.topbar h2{color:#fff;font-size:19px;font-weight:700}
.topbar p{color:rgba(255,255,255,.9)!important}
.topbar .badge-role{background:rgba(255,255,255,.18);color:#fff}
.topbar p{color:#8096b0;font-size:12.5px;margin-top:3px}
.badge-role{background:#e8f0f9;color:#0C447C;padding:5px 15px;border-radius:20px;font-size:12px;font-weight:600}
.filter-card{background:#fff;border-radius:12px;padding:20px 24px;margin-bottom:20px;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9}
.filter-card h3{color:#0C447C;font-size:12px;font-weight:700;margin-bottom:14px;text-transform:uppercase;letter-spacing:1px}
.filter-row{display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap}
.filter-group{display:flex;flex-direction:column;gap:6px;flex:1;min-width:160px}
.filter-group label{font-size:11px;font-weight:600;color:#5a7a9a;text-transform:uppercase;letter-spacing:.6px}
.input-animate{position:relative;border-radius:9px;padding:2px;background:linear-gradient(270deg,#0C447C,#1a6bbf,#38bdf8,#0C447C);background-size:300% 300%;animation:borderFlow 2.5s ease infinite}
.input-animate select{width:100%;padding:10px 13px;border:none;border-radius:7px;font-size:13px;color:#1a2b3c;background:#fff;outline:none;font-family:'Segoe UI',sans-serif}
.btn-apply{padding:11px 22px;background:#0C447C;color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:'Segoe UI',sans-serif;transition:background .2s}
.btn-apply:hover{background:#185FA5}
.btn-reset{padding:11px 16px;background:#f4f7fb;color:#5a7a9a;border:1.5px solid #d5e3f0;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;font-family:'Segoe UI',sans-serif;text-decoration:none;transition:all .2s}
.btn-reset:hover{background:#e8f0f9;color:#0C447C}
.stats-bar{border-radius:12px;padding:12px 20px;margin-bottom:22px;display:flex;gap:0;align-items:center;background:#0C447C}
.stat-item{text-align:center;flex:1}
.stat-item+.stat-item{border-left:1px solid rgba(255,255,255,.15)}
.stat-num{font-size:22px;font-weight:700;color:#fff;line-height:1}
.stat-label{font-size:11px;color:rgba(255,255,255,.55);margin-top:4px;text-transform:uppercase;letter-spacing:.8px}
.add-btn{background:rgba(255,255,255,.13);color:#fff;padding:9px 18px;border-radius:8px;text-decoration:none;font-size:12.5px;font-weight:600;margin-left:auto;border:1px solid rgba(255,255,255,.2);transition:all .2s;white-space:nowrap}
.add-btn:hover{background:rgba(255,255,255,.23)}
.table-card{background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9;margin-bottom:20px}
.table-header{padding:16px 20px;border-bottom:1px solid #e8f0f9;display:flex;justify-content:space-between;align-items:center}
.table-header h3{color:#0C447C;font-size:14px;font-weight:600}
.search-input{padding:8px 14px;border:1.5px solid #d0e2f5;border-radius:8px;font-size:13px;color:#1a2b3c;outline:none;font-family:'Segoe UI',sans-serif;width:220px}
.search-input:focus{border-color:#0C447C}
table{width:100%;border-collapse:collapse}
thead tr{background:#f7fafd}
thead th{padding:11px 16px;text-align:left;font-size:11px;font-weight:600;color:#7a96b0;text-transform:uppercase;letter-spacing:.6px}
tbody tr{border-top:1px solid #f0f5fb;transition:background .15s}
tbody tr:hover{background:#f4f8fd}
tbody td{padding:12px 16px;font-size:13px;color:#2d3f52}
.btn-view{background:#e8f0f9;color:#0C447C;padding:5px 13px;border-radius:6px;text-decoration:none;font-size:12px;font-weight:600;transition:all .2s;border:1px solid #d0e2f5}
.btn-view:hover{background:#0C447C;color:#fff}
.empty{text-align:center;padding:40px;color:#9aabbf;font-size:13.5px}
.loading{text-align:center;padding:40px;color:#9aabbf;font-size:13.5px}
.badge-dept{background:#e8f0f9;color:#0C447C;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600}
.shift-morning{background:#dcfce7;color:#166534;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600}
.shift-evening{background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600}
/* Responsive */
@media(max-width:768px){
    .main{margin-left:0;padding:15px}
    .topbar{flex-direction:column;align-items:flex-start;gap:10px}
    .filter-row{flex-direction:column;gap:10px}
    .filter-group{min-width:100%;width:100%}
    .btn-apply,.btn-reset{width:100%;text-align:center}
    .stats-bar{flex-direction:column;gap:12px;padding:16px}
    .stat-item+.stat-item{border-left:none;border-top:1px solid rgba(255,255,255,.15);padding-top:12px}
    .add-btn{width:100%;text-align:center;margin-left:0;margin-top:8px}
    .table-card{overflow-x:auto}
    table{min-width:550px}
    .search-input{width:100%}
    .table-header{flex-direction:column;gap:10px;align-items:flex-start}
}
</style>
</head>
<body>
@include('focal.partials.sidebar')

<div class="main">
  <div class="topbar">
    <div>
      <h2>All Students</h2>
      <p>Categorized by Department, Session &amp; Shift</p>
    </div>
    <span class="badge-role">Focal Person</span>
  </div>

  <!-- Filters -->
  <div class="filter-card">
    <h3>Filter Students</h3>
    <div class="filter-row">
      <div class="filter-group">
        <label>Department</label>
        <div class="input-animate">
          <select id="filter-dept">
            <option value="">All Departments</option>
            @foreach($departments as $dept)
            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="filter-group">
        <label>Session</label>
        <div class="input-animate">
          <select id="filter-session">
            <option value="">All Sessions</option>
            @foreach($sessions as $sess)
            <option value="{{ $sess->id }}">{{ $sess->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="filter-group">
        <label>Shift</label>
        <div class="input-animate">
          <select id="filter-shift">
            <option value="">All Shifts</option>
            <option value="Morning">Morning</option>
            <option value="Evening">Evening</option>
          </select>
        </div>
      </div>
      <div class="filter-group">
        <label>Semester</label>
        <div class="input-animate">
          <select id="filter-semester">
            <option value="">All Semesters</option>
            @for($i=1;$i<=8;$i++)
            <option value="{{ $i }}">Semester {{ $i }}</option>
            @endfor
          </select>
        </div>
      </div>
      <button class="btn-apply" onclick="loadStudents()">Apply Filter</button>
      <button class="btn-reset" onclick="resetFilters()">Reset</button>
    </div>
  </div>

  <!-- Stats Bar -->
  <div class="stats-bar">
    <div class="stat-item">
      <div class="stat-num" id="stat-total">-</div>
      <div class="stat-label">Total Students</div>
    </div>
    <div class="stat-item">
      <div class="stat-num" id="stat-morning">-</div>
      <div class="stat-label">Morning</div>
    </div>
    <div class="stat-item">
      <div class="stat-num" id="stat-evening">-</div>
      <div class="stat-label">Evening</div>
    </div>
    <a href="/focal/students/register" class="add-btn">+ Register New Student</a>
  </div>

  <!-- Table -->
  <div class="table-card">
    <div class="table-header">
      <h3 id="table-title">All Students</h3>
      <input type="text" class="search-input" placeholder="Search by name or roll..." oninput="searchTable(this.value)">
    </div>
    <div style="overflow-x:auto">
      <table id="students-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Roll Number</th>
            <th>Email</th>
            <th>Department</th>
            <th>Semester</th>
            <th>Shift</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="students-tbody">
          <tr><td colspan="8" class="loading">Loading students...</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
const token = localStorage.getItem('api_token');

// Department names map from Blade
const deptMap = {
    @foreach($departments as $dept)
    "{{ $dept->id }}": "{{ $dept->name }}",
    @endforeach
};

async function loadStudents() {
    const dept     = document.getElementById('filter-dept').value;
    const session  = document.getElementById('filter-session').value;
    const shift    = document.getElementById('filter-shift').value;
    const semester = document.getElementById('filter-semester').value;

    const tbody = document.getElementById('students-tbody');
    tbody.innerHTML = '<tr><td colspan="8" class="loading">Loading...</td></tr>';

    let url = '/api/focal/students?';
    if (dept)     url += `department_id=${dept}&`;
    if (session)  url += `session_id=${session}&`;
    if (shift)    url += `shift=${shift}&`;
    if (semester) url += `semester=${semester}&`;

    const res  = await fetch(url, {
        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        }
    });
    const data = await res.json();

    if (!data.success || data.students.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="empty">No students found.</td></tr>';
        updateStats([]);
        return;
    }

    updateStats(data.students);
    renderTable(data.students);
}

let allStudents = [];
let filteredStudents = [];
let currentPage = 1;
const perPage = 10;

function renderTable(students) {
    allStudents = [...students];
    filteredStudents = [...students];
    currentPage = 1;
    renderPage();
}

function renderPage() {
    const tbody = document.getElementById('students-tbody');
    tbody.innerHTML = '';
    const start = (currentPage - 1) * perPage;
    const pageData = filteredStudents.slice(start, start + perPage);
    pageData.forEach((s, i) => {
        const deptName = deptMap[s.department] || s.department || '-';
        const shiftBadge = s.shift === 'Morning'
            ? '<span class="shift-morning">Morning</span>'
            : s.shift === 'Evening'
            ? '<span class="shift-evening">Evening</span>'
            : '-';
        tbody.innerHTML += `<tr data-name="${(s.name||'').toLowerCase()}" data-roll="${(s.student_id||'').toLowerCase()}">
                <td>${start + i + 1}</td>
                <td>${s.name}</td>
                <td>${s.student_id || '-'}</td>
                <td>${s.email}</td>
                <td><span class="badge-dept">${deptName}</span></td>
                <td>Sem ${s.semester || '-'}</td>
                <td>${shiftBadge}</td>
                <td><a href="/focal/students/${s.id}" class="btn-view">View</a></td>
            </tr>`;
    });
    renderPagination();
}

function renderPagination() {
    const totalPages = Math.ceil(filteredStudents.length / perPage);
    let existing = document.getElementById('pagination-bar');
    if (existing) existing.remove();
    if (totalPages <= 1) return;
    const bar = document.createElement('div');
    bar.id = 'pagination-bar';
    bar.style = 'display:flex;gap:6px;justify-content:center;margin-top:16px;flex-wrap:wrap;';
    for (let p = 1; p <= totalPages; p++) {
        const btn = document.createElement('button');
        btn.textContent = p;
        btn.style = `padding:6px 12px;border-radius:6px;border:1px solid #1e3a5f;cursor:pointer;font-size:13px;background:${p===currentPage?'#1e3a5f':'#fff'};color:${p===currentPage?'#fff':'#1e3a5f'};`;
        btn.onclick = () => { currentPage = p; renderPage(); };
        bar.appendChild(btn);
    }
    document.getElementById('students-table').after(bar);
}

function updateStats(students) {
    const total   = students.length;
    const morning = students.filter(s => s.shift === 'Morning').length;
    const evening = students.filter(s => s.shift === 'Evening').length;

    document.getElementById('stat-total').textContent   = total;
    document.getElementById('stat-morning').textContent = morning;
    document.getElementById('stat-evening').textContent = evening;
}

function searchTable(query) {
    const q = query.toLowerCase().trim();
    filteredStudents = allStudents.filter(s => {
        const name = (s.name || '').toLowerCase();
        const roll = (s.student_id || '').toLowerCase();
        return !q || name.includes(q) || roll.includes(q);
    });
    currentPage = 1;
    renderPage();
}

function resetFilters() {
    document.getElementById('filter-dept').value     = '';
    document.getElementById('filter-session').value  = '';
    document.getElementById('filter-shift').value    = '';
    document.getElementById('filter-semester').value = '';
    loadStudents();
}

// Load on page open
loadStudents();
</script>
</body>
</html>