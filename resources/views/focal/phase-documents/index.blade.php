<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Phase Documents - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
.main{margin-left:260px;padding:28px 36px 40px;min-height:100vh}
.topbar{background:#0C447C;border-radius:12px;padding:20px 26px;margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 1px 12px rgba(12,68,124,.15)}
.topbar h2{color:#fff;font-size:19px;font-weight:700}
.topbar p{color:#c9dbee;font-size:12px;margin-top:3px}
.icon-badge{width:38px;height:38px;border-radius:10px;background:#0C447C;display:inline-flex;align-items:center;justify-content:center;flex-shrink:0}
.icon-badge svg{width:19px;height:19px}
.section-head{display:flex;align-items:center;gap:12px;margin-bottom:20px}
.section-head h3{margin-bottom:0}
.section-head.danger .icon-badge{background:#dc2626}
.filter-card{background:#fff;border-radius:14px;padding:20px 24px;margin-bottom:24px;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9;display:flex;gap:14px;flex-wrap:wrap;align-items:flex-end}
.filter-group{display:flex;flex-direction:column;gap:5px;min-width:160px}
.filter-group label{font-size:11px;font-weight:600;color:#0C447C;text-transform:uppercase;letter-spacing:.5px}
.filter-group select{padding:8px 12px;border:1.5px solid #e8f0f9;border-radius:8px;font-size:13px;color:#1a2b3c;background:#f7fafd;outline:none;font-family:'Segoe UI',sans-serif}
.filter-group select:focus{border-color:#0C447C}
.btn-filter{padding:9px 20px;background:#0C447C;color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:'Segoe UI',sans-serif}
.btn-filter:hover{background:#185FA5}
.section-card{background:#fff;border-radius:14px;padding:26px 26px 22px;margin-bottom:26px;box-shadow:0 1px 14px rgba(12,68,124,.08);border:1px solid #e8f0f9}
.section-card h3{color:#0C447C;font-size:14px;font-weight:700;margin-bottom:16px}
table{width:100%;border-collapse:collapse}
th{background:#f4f8fd;color:#0C447C;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;padding:10px 14px;text-align:left;border-bottom:2px solid #e8f0f9}
td{padding:11px 14px;font-size:13px;color:#1a2b3c;border-bottom:1px solid #f0f4f8;vertical-align:middle}
tr:last-child td{border-bottom:none}
tr:hover td{background:#f9fbfd}
.badge-uploaded{background:#dcfce7;color:#166534;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600}
.btn-download{padding:6px 14px;background:#e8f0f9;color:#0C447C;border:none;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;text-decoration:none;display:inline-block;transition:background .2s}
.btn-download:hover{background:#0C447C;color:#fff}
.empty-state{text-align:center;padding:40px;color:#9aabbf;font-size:13px}
.filesize{color:#8096b0;font-size:11.5px}
.table-wrap{border-radius:12px;overflow:hidden;border:1px solid #eef2f7;margin-top:2px}
table{border-radius:10px}
tbody tr:nth-child(even) td{background:#fafcfe}
tbody tr:nth-child(even):hover td{background:#f4f8fd}
.group-pill{display:inline-block;background:#e8f0f9;color:#0C447C;font-weight:600;font-size:12px;padding:3px 10px;border-radius:20px}
.phase-chip{display:inline-block;background:#f4f8fd;color:#4a6580;font-size:12px;padding:3px 10px;border-radius:6px;border:1px solid #e8f0f9}
.badge-pending{background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600}
.badge-allowed{background:#dcfce7;color:#166534;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600;display:inline-flex;align-items:center}
.badge-ontime{background:#dcfce7;color:#166534;padding:2px 9px;border-radius:12px;font-size:10.5px;font-weight:600;margin-left:6px}
.badge-late{background:#fee2e2;color:#b91c1c;padding:2px 9px;border-radius:12px;font-size:10.5px;font-weight:600;margin-left:6px}
.btn-allow{padding:7px 16px;background:#0C447C;color:#fff;border:none;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;font-family:'Segoe UI',sans-serif;transition:background .2s}
.btn-allow:hover{background:#185FA5}
.btn-allow:focus{outline:none}
@media(max-width:768px){.main{margin-left:0;padding:16px;padding-top:62px}.filter-card{flex-direction:column}}
</style>
</head>
<body>
@include('focal.partials.sidebar')
<div class="main">
  <div class="topbar">
    <div><h2>Phase Documents</h2><p>View and download documents uploaded by student groups</p></div>
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

  @if($pendingSchedules->count() > 0)
  <div class="section-card" style="border:1.5px solid #fca5a5">
    <div class="section-head danger">
      <span class="icon-badge">
        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4"/><path d="M12 17h.01"/><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/></svg>
      </span>
      <h3 style="color:#b91c1c">Missed Upload Deadline ({{ $pendingSchedules->count() }})</h3>
    </div>
    <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th><th>Group</th><th>Phase</th><th>Schedule Date</th><th>Deadline</th><th>Status</th><th>Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach($pendingSchedules as $i => $ps)
        <tr>
          <td>{{ $i + 1 }}</td>
          <td><span class="group-pill">{{ $ps->group->group_code ?? "&mdash;" }}</span></td>
          <td><span class="phase-chip">{{ $ps->phase->name ?? "&mdash;" }}</span></td>
          <td>{{ \Carbon\Carbon::parse($ps->schedule_date)->format("d M Y") }}</td>
          <td>{{ $ps->upload_deadline ? \Carbon\Carbon::parse($ps->upload_deadline)->format("d M Y") : \Carbon\Carbon::parse($ps->schedule_date)->format("d M Y") }}</td>
          <td>
            @if($ps->late_upload_allowed)
              <span class="badge-allowed"><svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-1px;margin-right:3px"><polyline points="20 6 9 17 4 12"/></svg>Permission Granted</span>
            @else
              <span class="badge-pending">Upload Pending</span>
            @endif
          </td>
          <td>
            @if(!$ps->late_upload_allowed)
            <form method="POST" action="{{ route("focal.phase-schedules.allow-late-upload", $ps->id) }}">
              @csrf
              <button type="submit" class="btn-allow">Allow Late Upload</button>
            </form>
            @else
              <span style="font-size:12px;color:#8096b0">Granted {{ $ps->late_upload_allowed_at ? \Carbon\Carbon::parse($ps->late_upload_allowed_at)->format("d M Y") : "" }}</span>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    </div>
  </div>
  @endif

  <div class="section-card">
    <div class="section-head">
      <span class="icon-badge">
        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6"/></svg>
      </span>
      <h3>Uploaded Documents (<span id="doc_count">{{ $documents->count() }}</span>)</h3>
    </div>
    <div class="table-wrap">
    <table id="doc_table">
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
        @forelse($documents as $i => $doc)
        <tr data-phase="{{ $doc->phase_id }}" data-group="{{ $doc->group_id }}">
          <td>{{ $i + 1 }}</td>
          <td><span class="group-pill">{{ $doc->group->group_code ?? '—' }}</span></td>
          <td><span class="phase-chip">{{ $doc->phase->name ?? '—' }}</span></td>
          <td>{{ $doc->original_filename }}</td>
          <td class="filesize">{{ number_format($doc->file_size / 1024, 1) }} KB</td>
          <td>{{ $doc->uploader->name ?? '—' }}</td>
            <td>
              {{ $doc->uploaded_at ? \Carbon\Carbon::parse($doc->uploaded_at)->format("d M Y, h:i A") : $doc->updated_at->format("d M Y, h:i A") }}
              @if($doc->is_late) <span class="badge-late">Late</span>
              @else <span class="badge-ontime">On Time</span>
              @endif
            </td>
          <td>
            <a href="/focal/phase-documents/{{ $doc->id }}/download" class="btn-download">Download</a>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" class="empty-state">No documents uploaded yet.</td></tr>
        @endforelse
      </tbody>
    </table>
    </div>
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
</script>
</body>
</html>
