<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Marks Management - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
.main{margin-left:260px;padding:28px 32px}
.topbar{background:#0C447C;border-radius:14px;padding:20px 26px;margin-bottom:24px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 4px 14px rgba(12,68,124,.18)}
.topbar h2{color:#fff;font-size:19px;font-weight:700}
.topbar p{color:rgba(255,255,255,.9)!important;font-size:12.5px;margin-top:2px}
.badge-role{background:rgba(255,255,255,.18);color:#fff;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600}
.card{background:#fff;border-radius:12px;padding:0 0 22px 0;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9;margin-bottom:20px;overflow:hidden}
.card h3{color:#fff;background:#0C447C;font-size:15px;font-weight:700;margin-bottom:20px;padding:16px 22px}
.card > form, .card > div.table-wrap{padding-left:22px;padding-right:22px}
.stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:20px}
.stat-box{background:#fff;border-radius:12px;padding:20px;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9;text-align:center;transition:all .2s}
.stat-box:hover{border-color:#c7d8ec;box-shadow:0 3px 14px rgba(12,68,124,.1)}
.stat-box .num{font-size:30px;font-weight:700;color:#0C447C}
.stat-box .lbl{font-size:11.5px;color:#8096b0;margin-top:6px;text-transform:uppercase;letter-spacing:.5px;font-weight:600}
.filters{display:flex;gap:14px;flex-wrap:wrap;margin-bottom:10px}
.filters select{padding:11px 15px;border:1.5px solid #e8f0f9;border-radius:10px;font-size:13.5px;color:#1e3a5f;outline:none;min-width:170px;background:#f7fafd;transition:all .2s}
.filters select:focus{border-color:#0C447C;background:#fff;box-shadow:0 0 0 3px rgba(12,68,124,.08)}
.btn-filter{background:#0C447C;color:#fff;border:none;padding:11px 24px;border-radius:10px;font-size:13.5px;font-weight:600;cursor:pointer;transition:all .2s;box-shadow:0 3px 10px rgba(12,68,124,.25)}
.btn-filter:hover{background:#185FA5;transform:translateY(-1px)}
.alert-success{background:#dcfce7;color:#166534;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;border:1px solid #bbf7d0}
.table-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse;font-size:13px}
thead th{background:#f0f6ff;color:#0C447C;font-weight:700;padding:13px 16px;text-align:left;border-bottom:2px solid #e2ecf9;white-space:nowrap;font-size:11.5px;text-transform:uppercase;letter-spacing:.5px}
tbody td{padding:13px 16px;border-bottom:1px solid #f0f4f8;color:#1e3a5f;vertical-align:middle}
tbody tr:hover{background:#f8fafd}
.badge-submitted{background:#dcfce7;color:#166534;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
.badge-pending{background:#fef9c3;color:#854d0e;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
.badge-adjusted{background:#e0e7ff;color:#3730a3;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
.btn-sm{padding:7px 16px;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;transition:all .2s}
.btn-adjust{background:#fff;color:#0C447C;border:1.5px solid #c7d8ec}
.btn-adjust:hover{background:#0C447C;color:#fff}
.empty{text-align:center;padding:40px;color:#8096b0;font-size:14px}
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center}
.modal-overlay.show{display:flex}
.modal{background:#fff;border-radius:16px;padding:28px;width:100%;max-width:460px;box-shadow:0 20px 60px rgba(12,68,124,.2)}
.modal h3{color:#0C447C;font-size:16px;font-weight:700;margin-bottom:6px}
.modal .sub{font-size:13px;color:#8096b0;margin-bottom:20px}
.form-group{margin-bottom:14px}
.form-group label{display:block;font-size:12px;font-weight:700;color:#0C447C;text-transform:uppercase;letter-spacing:.6px;margin-bottom:6px}
.form-group input,.form-group textarea{width:100%;padding:10px 14px;border:1.5px solid #e8f0f9;border-radius:8px;font-size:13px;color:#1e3a5f;outline:none}
.form-group input:focus{border-color:#0C447C}
.current-mark{background:#f4f8fd;border-radius:8px;padding:12px 16px;margin-bottom:16px;display:flex;justify-content:space-between}
.current-mark .lbl{font-size:12px;color:#8096b0}
.current-mark .val{font-size:20px;font-weight:700;color:#0C447C}
.modal-actions{display:flex;gap:10px;margin-top:16px}
.btn-cancel{flex:1;padding:10px;background:#f4f7fb;color:#5a7a9a;border:1.5px solid #d5e3f0;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer}
.btn-save{flex:2;padding:10px;background:#0C447C;color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer}
@media(max-width:900px){.stats-row{grid-template-columns:1fr 1fr}}
@media(max-width:768px){.main{margin-left:0;padding:16px}.filters{flex-direction:column}}
</style>
</head>
<body>
@include('focal.partials.sidebar')
<div class="main">
  <div class="topbar">
    <div>
      <h2>Marks Management</h2>
      <p>View and adjust student marks per phase</p>
    </div>
    <span class="badge-role">Focal Person</span>
  </div>

  @if(session('success'))
    <div class="alert-success">✓ {{ session('success') }}</div>
  @endif

  <!-- Stats -->
  <div class="stats-row">
    <div class="stat-box">
      <div class="num">{{ $stats['total'] }}</div>
      <div class="lbl">Total Records</div>
    </div>
    <div class="stat-box">
      <div class="num">{{ $stats['submitted'] }}</div>
      <div class="lbl">Submitted</div>
    </div>
    <div class="stat-box">
      <div class="num">{{ $stats['pending'] }}</div>
      <div class="lbl">Pending</div>
    </div>
    <div class="stat-box">
      <div class="num">{{ $stats['adjusted'] }}</div>
      <div class="lbl">Adjusted</div>
    </div>
  </div>

  <!-- Filters -->
  <div class="card">
    <h3>Filter Marks</h3>
    <form method="GET" action="{{ route('focal.marks.index') }}">
      <div class="filters">
        <select name="session_id">
          <option value="">All Sessions</option>
          @foreach($sessions as $session)
          <option value="{{ $session->id }}" {{ request('session_id')==$session->id?'selected':'' }}>{{ $session->name }}</option>
          @endforeach
        </select>
        <select name="department_id">
          <option value="">All Departments</option>
          @foreach($departments as $dept)
          <option value="{{ $dept->id }}" {{ request('department_id')==$dept->id?'selected':'' }}>{{ $dept->name }}</option>
          @endforeach
        </select>
        <select name="phase_id">
          <option value="">All Phases</option>
          @foreach($phases as $phase)
          <option value="{{ $phase->id }}" {{ request('phase_id')==$phase->id?'selected':'' }}>{{ $phase->name }}</option>
          @endforeach
        </select>
        <select name="status">
          <option value="">All Status</option>
          <option value="submitted" {{ request('status')=='submitted'?'selected':'' }}>Submitted</option>
          <option value="pending"   {{ request('status')=='pending'?'selected':'' }}>Pending</option>
          <option value="adjusted"  {{ request('status')=='adjusted'?'selected':'' }}>Adjusted</option>
        </select>
        <button type="submit" class="btn-filter">Apply</button>
      </div>
    </form>
  </div>

  <!-- Table -->
  <div class="card">
    <h3>Marks List</h3>
    <div class="table-wrap">
      @if($marks->count() > 0)
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Student</th>
            <th>Phase</th>
            <th>Evaluator</th>
            <th>Marks</th>
            <th>Converted</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($marks as $i => $mark)
          <tr>
            <td>{{ $i+1 }}</td>
            <td style="font-weight:600">{{ $mark->student->name ?? 'N/A' }}</td>
            <td>{{ $mark->phase->name ?? 'N/A' }}</td>
            <td>{{ $mark->evaluator->name ?? 'N/A' }}</td>
            <td style="font-weight:700;color:#0C447C">{{ $mark->marks_obtained ?? '—' }}</td>
            <td style="font-weight:700;color:#166534">{{ $mark->converted_marks ?? '—' }}</td>
            <td>
              @if($mark->is_adjusted ?? false)
                <span class="badge-adjusted">Adjusted</span>
              @elseif($mark->marks_obtained !== null)
                <span class="badge-submitted">Submitted</span>
              @else
                <span class="badge-pending">Pending</span>
              @endif
            </td>
            <td>
              @if($mark->marks_obtained !== null)
              <button class="btn-sm btn-adjust" onclick="openAdjust({{ $mark->id }},'{{ addslashes($mark->student->name ?? '') }}','{{ addslashes($mark->phase->name ?? '') }}',{{ $mark->marks_obtained }})">✏️ Adjust</button>
              @else
              <span style="color:#8096b0;font-size:12px">—</span>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @else
        <div class="empty">No marks found.</div>
      @endif
    </div>
  </div>
</div>

<!-- Adjust Modal -->
<div class="modal-overlay" id="adjustModal">
  <div class="modal">
    <h3>✏️ Adjust Marks</h3>
    <p class="sub" id="adjust_subtitle"></p>
    <div class="current-mark">
      <div><div class="lbl">Current</div><div class="val" id="adjust_current">—</div></div>
    </div>
    <form id="adjustForm" method="POST">
      @csrf @method('PUT')
      <div class="form-group">
        <label>New Marks</label>
        <input type="number" name="obtained_marks" id="new_marks" min="0" required>
      </div>
      <div class="form-group">
        <label>Reason</label>
        <textarea name="adjustment_reason" rows="2" placeholder="Reason for adjustment..."></textarea>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn-cancel" onclick="closeAdjust()">Cancel</button>
        <button type="submit" class="btn-save">Save</button>
      </div>
    </form>
  </div>
</div>

<script>
function openAdjust(id, student, phase, current) {
    document.getElementById('adjustForm').action = '/focal/marks/' + id;
    document.getElementById('adjust_subtitle').textContent = student + ' — ' + phase;
    document.getElementById('adjust_current').textContent  = current;
    document.getElementById('adjustModal').classList.add('show');
}
function closeAdjust() {
    document.getElementById('adjustModal').classList.remove('show');
}
document.getElementById('adjustModal').addEventListener('click',function(e){
    if(e.target===this) closeAdjust();
});
function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('overlay')?.classList.toggle('show');
}
</script>
</body>
</html>
