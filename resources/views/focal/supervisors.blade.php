<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>All Faculty</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh;}
.main{margin-left:260px;flex:1;padding:30px}
.topbar{background:#0C447C;border-radius:14px;padding:20px 26px;margin-bottom:24px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 4px 14px rgba(12,68,124,.18)}
.topbar h2{color:#fff;font-size:20px;font-weight:700}
.topbar p{color:rgba(255,255,255,.9)!important;font-size:13px;margin-top:2px}
.badge-role{background:#e8f0f9;color:#0C447C;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600}
.table-card{background:#fff;border-radius:12px;padding:25px;box-shadow:0 2px 15px rgba(12,68,124,.06)}
.table-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px}
.table-header h3{color:#0C447C;font-size:15px;font-weight:600}
.btn-add{background:#0C447C;color:#fff;padding:9px 18px;border-radius:8px;text-decoration:none;font-size:13px;font-weight:600}
.btn-add:hover{background:#185FA5}
.search-input{padding:8px 14px;border:1.5px solid #d0e2f5;border-radius:8px;font-size:13px;color:#1a2b3c;outline:none;font-family:'Segoe UI',sans-serif;width:220px}
.search-input:focus{border-color:#0C447C}
table{width:100%;border-collapse:collapse}
thead tr{background:#f0f4f8}
thead th{padding:12px 15px;text-align:left;font-size:12px;font-weight:600;color:#0C447C;text-transform:uppercase;letter-spacing:.5px}
tbody tr{border-bottom:1px solid #f0f4f8;transition:background .15s}
tbody tr:hover{background:#f7fafd}
tbody td{padding:13px 15px;font-size:13px;color:#374151}
.badge-dept{background:#e8f0f9;color:#0C447C;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600}
.empty{text-align:center;padding:40px;color:#9aabbf;font-size:14px}
.loading{text-align:center;padding:40px;color:#9aabbf;font-size:14px}
.total-badge{background:#0C447C;color:#fff;padding:3px 12px;border-radius:12px;font-size:12px;font-weight:600;margin-left:10px}
@media(max-width:768px){
    .main{margin-left:0;padding:15px}
    .topbar{flex-direction:column;align-items:flex-start;gap:10px}
    .table-card{padding:15px;overflow-x:auto}
    table{min-width:600px}
    .table-header{flex-direction:column;align-items:flex-start;gap:10px}
    .btn-add{width:100%;text-align:center;display:block}
    .search-input{width:100%}
    thead th{padding:10px 10px;font-size:11px}
    tbody td{padding:10px 10px;font-size:12px}
}
</style>
<link rel="stylesheet" href="/css/transitions.css">
</head>
<body>
@include('focal.partials.sidebar')

<div class="main">
  <div class="topbar">
    <div>
      <h2>All Faculty</h2>
      <p id="faculty-count">Loading...</p>
    </div>
    <span class="badge-role">Focal Person</span>
  </div>

  <div class="table-card">
    <div class="table-header">
      <h3>Faculty List <span class="total-badge" id="total-badge">-</span></h3>
      <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
        <input type="text" class="search-input" placeholder="Search by name..." oninput="searchTable(this.value)">
        <a href="/focal/faculty-members/register" class="btn-add">➕ Register New</a>
      </div>
    </div>

    <div style="overflow-x:auto">
      <table id="faculty-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Employee ID</th>
            <th>Email</th>
            <th>Department</th>
            <th>Designation</th>
          </tr>
        </thead>
        <tbody id="faculty-tbody">
          <tr><td colspan="6" class="loading">Loading faculty...</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
const token = localStorage.getItem('api_token');

const perPage = 10;
let currentPage = 1;
let allFaculty = [];
let filtered = [];

async function loadFaculty() {
    const res  = await fetch('/api/focal/faculty-members', {
        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        }
    });
    const data = await res.json();
    const tbody = document.getElementById('faculty-tbody');

    if (!data.success || data.faculty_members.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="empty">No faculty registered yet.</td></tr>';
        document.getElementById('faculty-count').textContent = '0 faculty member(s) registered';
        document.getElementById('total-badge').textContent = '0';
        return;
    }

    const total = data.faculty_members.length;
    document.getElementById('faculty-count').textContent = total + ' faculty member(s) registered';
    document.getElementById('total-badge').textContent = total;

    allFaculty = [...data.faculty_members];
    filtered = [...allFaculty];
    renderTable();

    renderTable();
}
function renderTable() {
    const tbody = document.getElementById('faculty-tbody');
    const start = (currentPage - 1) * perPage;
    const pageData = filtered.slice(start, start + perPage);
    tbody.innerHTML = '';
    pageData.forEach((s, i) => {
        tbody.innerHTML += `
        <tr data-name="${(s.name||'').toLowerCase()}">
            <td>${start + i + 1}</td>
            <td>${s.name}</td>
            <td>${s.employee_id || '-'}</td>
            <td>${s.email}</td>
            <td><span class="badge-dept">${s.department || '-'}</span></td>
            <td>${s.designation || '-'}</td>
        </tr>`;
    });
    renderPagination();
}
function renderPagination() {
    const totalPages = Math.ceil(filtered.length / perPage);
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
        btn.onclick = () => { currentPage = p; renderTable(); };
        bar.appendChild(btn);
    }
    document.getElementById('faculty-tbody').closest('table').after(bar);
}
function searchTable(query) {
    const q = query.toLowerCase().trim();
    filtered = allFaculty.filter(s => !q || (s.name||'').toLowerCase().includes(q));
    currentPage = 1;
    renderTable();
}
loadFaculty();
</script>
</body>
</html>