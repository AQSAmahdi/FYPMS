<!DOCTYPE html>
<html lang="en">
<head>
<style>html,body{background:#f0f4f8}</style>

<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Phase Documents - FYPMS</title>
<link rel="stylesheet" href="/css/transitions.css?v=3">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
.main{margin-left:260px;padding:28px 32px;min-height:100vh}
.topbar{background:linear-gradient(135deg,#378ADD,#0C447C);border-radius:12px;padding:22px 28px;margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 4px 16px rgba(12,68,124,.18)}
.topbar-left{display:flex;align-items:center;gap:16px}
.topbar-icon{width:46px;height:46px;border-radius:12px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0}
.topbar h2{color:#fff;font-size:18px;font-weight:700}
.topbar p{color:rgba(255,255,255,.85);font-size:12px;margin-top:2px}
.badge-role{background:rgba(255,255,255,.2);color:#fff;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600}
.filter-card{background:#fff;border-radius:12px;padding:18px 22px;margin-bottom:18px;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9;display:flex;gap:14px;flex-wrap:wrap;align-items:flex-end}
.filter-group{display:flex;flex-direction:column;gap:5px;min-width:160px}
.filter-group label{font-size:11px;font-weight:600;color:#0C447C;text-transform:uppercase;letter-spacing:.5px}
.filter-group select{padding:8px 12px;border:1.5px solid #e8f0f9;border-radius:8px;font-size:13px;color:#1a2b3c;background:#f7fafd;outline:none;font-family:'Segoe UI',sans-serif}
.filter-group select:focus{border-color:#0C447C}
.section-card{background:#fff;border-radius:12px;padding:24px;margin-bottom:20px;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9}
.section-card h3{color:#0C447C;font-size:14px;font-weight:700;margin-bottom:16px}
table{width:100%;border-collapse:collapse}
th{background:#f4f8fd;color:#0C447C;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;padding:10px 14px;text-align:left;border-bottom:2px solid #e8f0f9}
td{padding:11px 14px;font-size:13px;color:#1a2b3c;border-bottom:1px solid #f0f4f8;vertical-align:middle}
tr:last-child td{border-bottom:none}
tr:hover td{background:#f9fbfd}
.btn-download{padding:6px 14px;background:#e8f0f9;color:#0C447C;border:none;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;text-decoration:none;display:inline-block;transition:background .2s}
.btn-download:hover{background:#0C447C;color:#fff}
.filesize{color:#8096b0;font-size:11.5px}
.empty-state{text-align:center;padding:40px;color:#9aabbf;font-size:13px}
@media(max-width:768px){
  .main{margin-left:0;padding:16px;padding-top:62px}
  .filter-card{flex-direction:column}
}
</style>
</head>
<body>
<button class="hamburger" id="hamburger" onclick="toggleSidebar()">
  <span></span><span></span><span></span>
</button>
<div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

@include('faculty.partials.sidebar')

<div class="main">
  <div class="topbar">
    <div class="topbar-left">
      <div class="topbar-icon">📄</div>
      <div><h2>Phase Documents</h2><p>Documents uploaded by your assigned groups</p></div>
    </div>
    <span class="badge-role">Faculty</span>
  </div>

  <div class="filter-card">
    <div class="filter-group">
      <label>Phase</label>
      <select id="f_phase" onchange="applyFilter()">
        <option value="">All Phases</option>
        @foreach($phases as $phase)
          <option value="{{ $phase->id }}">{{ $phase->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="filter-group">
      <label>Group</label>
      <select id="f_group" onchange="applyFilter()">
        <option value="">All Groups</option>
        @foreach($groups as $group)
          <option value="{{ $group->id }}">{{ $group->group_code }}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="section-card">
    <h3>Uploaded Documents (<span id="doc_count">{{ $documents->count() }}</span>)</h3>
    @if($documents->isEmpty())
      <div class="empty-state">No documents uploaded by your groups yet.</div>
    @else
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Group</th>
          <th>Phase</th>
          <th>File Name</th>
          <th>Size</th>
          <th>Uploaded By</th>
          <th>Uploaded At</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="doc_body">
        @foreach($documents as $i => $doc)
        <tr data-phase="{{ $doc->phase_id }}" data-group="{{ $doc->group_id }}">
          <td>{{ $i + 1 }}</td>
          <td style="font-weight:600;color:#0C447C">{{ $doc->group->group_code ?? '—' }}</td>
          <td>{{ $doc->phase->name ?? '—' }}</td>
          <td>{{ $doc->original_filename }}</td>
          <td class="filesize">{{ number_format($doc->file_size / 1024, 1) }} KB</td>
          <td>{{ $doc->uploader->name ?? '—' }}</td>
          <td>{{ $doc->updated_at->format('d M Y, h:i A') }}</td>
          <td>
            <a href="/faculty/phase-documents/{{ $doc->id }}/download" class="btn-download">Download</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    @endif
  </div>
</div>

<script>
function applyFilter() {
    const phase = document.getElementById('f_phase').value;
    const group = document.getElementById('f_group').value;
    const rows  = document.querySelectorAll('#doc_body tr[data-phase]');
    let count = 0;
    rows.forEach(row => {
        const matchPhase = !phase || row.dataset.phase === phase;
        const matchGroup = !group || row.dataset.group === group;
        const show = matchPhase && matchGroup;
        row.style.display = show ? '' : 'none';
        if (show) count++;
    });
    document.getElementById('doc_count').textContent = count;
}
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('hamburger').classList.toggle('open');
    document.getElementById('overlay').classList.toggle('show');
}
</script>
</body>
</html>
