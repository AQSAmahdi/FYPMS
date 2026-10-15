<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>FYP Phases - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
@keyframes borderFlow{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
.main{margin-left:220px;padding:28px 32px}
.topbar{background:#fff;border-radius:12px;padding:16px 22px;margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9}
.topbar h2{color:#0C447C;font-size:18px;font-weight:700}
.topbar p{color:#8096b0;font-size:12px;margin-top:2px}
.badge-role{background:#e8f0f9;color:#0C447C;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600}
.grid2{display:grid;grid-template-columns:1fr 1fr;gap:20px}
.card{background:#fff;border-radius:12px;padding:22px;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9}
.card h3{color:#0C447C;font-size:15px;font-weight:700;margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid #e8f0f9}
.form-group{margin-bottom:14px}
.form-group label{display:block;font-size:12px;font-weight:700;color:#0C447C;text-transform:uppercase;letter-spacing:.6px;margin-bottom:6px}
.form-group input,.form-group select,.form-group textarea{width:100%;padding:10px 14px;border:1.5px solid #e8f0f9;border-radius:8px;font-size:13px;color:#1e3a5f;font-family:'Segoe UI',sans-serif;outline:none;transition:border .2s}
.form-group input:focus,.form-group select:focus{border-color:#0C447C}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.btn-primary{background:#0C447C;color:#fff;border:none;padding:10px 20px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;width:100%;margin-top:4px}
.btn-primary:hover{background:#185FA5}
.alert-success{background:#dcfce7;color:#166534;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;border:1px solid #bbf7d0}
.alert-error{background:#fef2f2;color:#b91c1c;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px}
.phase-item{border:1.5px solid #e8f0f9;border-radius:10px;padding:16px;margin-bottom:12px;transition:all .2s}
.phase-item:hover{border-color:#0C447C;box-shadow:0 2px 12px rgba(12,68,124,.08)}
.phase-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px}
.phase-name{font-size:15px;font-weight:700;color:#0C447C}
.phase-meta{font-size:12px;color:#8096b0;margin-top:2px}
.badge-active{background:#dcfce7;color:#166534;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
.badge-inactive{background:#fef2f2;color:#b91c1c;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
.marks-info{display:flex;gap:12px;margin-bottom:10px;flex-wrap:wrap}
.marks-box{background:#f4f8fd;border-radius:8px;padding:8px 14px;text-align:center;flex:1;min-width:80px}
.marks-box .num{font-size:20px;font-weight:700;color:#0C447C}
.marks-box .lbl{font-size:10px;color:#8096b0;text-transform:uppercase;letter-spacing:.5px;margin-top:2px}
.arrow-icon{font-size:18px;color:#8096b0;display:flex;align-items:center;padding-top:8px}
.phase-actions{display:flex;gap:8px;flex-wrap:wrap}
.btn-sm{padding:6px 14px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;border:none;transition:all .2s}
.btn-edit{background:#e8f0f9;color:#0C447C}
.btn-edit:hover{background:#0C447C;color:#fff}
.btn-delete{background:#fef2f2;color:#b91c1c}
.btn-delete:hover{background:#b91c1c;color:#fff}
.order-badge{background:#0C447C;color:#fff;width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0}
.empty{text-align:center;padding:40px;color:#8096b0;font-size:14px}
/* Modal */
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center}
.modal-overlay.show{display:flex}
.modal{background:#fff;border-radius:16px;padding:28px;width:100%;max-width:500px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(12,68,124,.2)}
.modal h3{color:#0C447C;font-size:16px;font-weight:700;margin-bottom:20px}
.modal-actions{display:flex;gap:10px;margin-top:16px}
.btn-cancel{flex:1;padding:10px;background:#f4f7fb;color:#5a7a9a;border:1.5px solid #d5e3f0;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer}
.btn-save{flex:2;padding:10px;background:#0C447C;color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer}
@media(max-width:900px){.grid2{grid-template-columns:1fr}}
@media(max-width:768px){
    .main{margin-left:0;padding:16px}
    .topbar{flex-direction:column;align-items:flex-start;gap:8px}
    .form-row{grid-template-columns:1fr}
    .marks-info{flex-direction:column}
}
</style>
</head>
<body>
@include('focal.partials.sidebar')

<div class="main">
  <div class="topbar">
    <div>
      <h2>FYP Phases</h2>
      <p>Manage evaluation phases for this semester</p>
    </div>
    <span class="badge-role">Focal Person</span>
  </div>

  @if(session('success'))
    <div class="alert-success">✓ {{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="alert-error">{{ $errors->first() }}</div>
  @endif

  <div class="grid2">

    <!-- Create Phase Form -->
    <div class="card">
      <h3>➕ Add New Phase</h3>
      <form method="POST" action="{{ route('focal.phases.store') }}">
        @csrf
        <div class="form-group">
          <label>Phase Name</label>
          <input type="text" name="name" placeholder="e.g. Mid Defence, Final Defence" required value="{{ old('name') }}">
        </div>
        <div class="form-group">
          <label>Description (Optional)</label>
          <textarea name="description" rows="2" placeholder="Brief description...">{{ old('description') }}</textarea>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Total Marks</label>
            <input type="number" name="total_marks" placeholder="e.g. 500" min="1" required value="{{ old('total_marks') }}">
          </div>
          <div class="form-group">
            <label>Convert To</label>
            <input type="number" name="convert_to_marks" placeholder="e.g. 200" min="1" required value="{{ old('convert_to_marks') }}">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Session</label>
            <select name="session_id">
              <option value="">All Sessions</option>
              @foreach($sessions as $session)
              <option value="{{ $session->id }}" {{ old('session_id')==$session->id?'selected':'' }}>{{ $session->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Department</label>
            <select name="department_id">
              <option value="">All Departments</option>
              @foreach($departments as $dept)
              <option value="{{ $dept->id }}" {{ old('department_id')==$dept->id?'selected':'' }}>{{ $dept->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Shift</label>
            <select name="shift">
              <option value="">All Shifts</option>
              <option value="Morning" {{ old('shift')=='Morning'?'selected':'' }}>Morning</option>
              <option value="Evening" {{ old('shift')=='Evening'?'selected':'' }}>Evening</option>
            </select>
          </div>
          <div class="form-group">
            <label>Order / Sequence</label>
            <input type="number" name="order" placeholder="e.g. 1" min="1" value="{{ old('order', 1) }}" required>
          </div>
        </div>
        <button type="submit" class="btn-primary">Create Phase</button>
      </form>
    </div>

    <!-- Phases List -->
    <div class="card">
      <h3>📋 Existing Phases ({{ $phases->count() }})</h3>

      @if($phases->count() > 0)
        @foreach($phases as $phase)
        <div class="phase-item">
          <div class="phase-header">
            <div style="display:flex;align-items:center;gap:10px">
              <div class="order-badge">{{ $phase->order }}</div>
              <div>
                <div class="phase-name">{{ $phase->name }}</div>
                <div class="phase-meta">
                  {{ $phase->session->name ?? 'All Sessions' }} &bull;
                  {{ $phase->department->name ?? 'All Depts' }} &bull;
                  {{ $phase->shift ?? 'All Shifts' }}
                </div>
              </div>
            </div>
            <span class="{{ $phase->is_active ? 'badge-active' : 'badge-inactive' }}">
              {{ $phase->is_active ? 'Active' : 'Inactive' }}
            </span>
          </div>

          <div class="marks-info">
            <div class="marks-box">
              <div class="num">{{ $phase->total_marks }}</div>
              <div class="lbl">Total Marks</div>
            </div>
            <div class="arrow-icon">→</div>
            <div class="marks-box">
              <div class="num">{{ $phase->convert_to_marks }}</div>
              <div class="lbl">Converts To</div>
            </div>
          </div>

          @if($phase->description)
          <div style="font-size:12px;color:#5a7a9a;margin-bottom:10px">{{ $phase->description }}</div>
          @endif

          <div class="phase-actions">
            <button class="btn-sm btn-edit" onclick="openEdit({{ $phase->id }}, '{{ addslashes($phase->name) }}', '{{ addslashes($phase->description ?? '') }}', {{ $phase->total_marks }}, {{ $phase->convert_to_marks }}, {{ $phase->order }}, {{ $phase->is_active ? 1 : 0 }}, {{ $phase->session_id ?? 'null' }}, {{ $phase->department_id ?? 'null' }}, '{{ $phase->shift ?? '' }}')">
              ✏️ Edit
            </button>
            <form method="POST" action="{{ route('focal.phases.destroy', $phase->id) }}" onsubmit="return confirm('Delete this phase?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn-sm btn-delete">🗑️ Delete</button>
            </form>
          </div>
        </div>
        @endforeach
      @else
        <div class="empty">No phases added yet. Create your first phase!</div>
      @endif
    </div>

  </div>
</div>

<!-- Edit Modal -->
<div class="modal-overlay" id="editModal">
  <div class="modal">
    <h3>✏️ Edit Phase</h3>
    <form id="editForm" method="POST">
      @csrf @method('PUT')
      <div class="form-group">
        <label>Phase Name</label>
        <input type="text" name="name" id="edit_name" required>
      </div>
      <div class="form-group">
        <label>Description</label>
        <textarea name="description" id="edit_description" rows="2"></textarea>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Total Marks</label>
          <input type="number" name="total_marks" id="edit_total_marks" min="1" required>
        </div>
        <div class="form-group">
          <label>Convert To</label>
          <input type="number" name="convert_to_marks" id="edit_convert_marks" min="1" required>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Order</label>
          <input type="number" name="order" id="edit_order" min="1" required>
        </div>
        <div class="form-group">
          <label>Status</label>
          <select name="is_active" id="edit_status">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
        </div>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn-cancel" onclick="closeEdit()">Cancel</button>
        <button type="submit" class="btn-save">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<script>
function openEdit(id, name, desc, total, convert, order, active, sessionId, deptId, shift) {
    document.getElementById('editForm').action = '/focal/phases/' + id;
    document.getElementById('edit_name').value         = name;
    document.getElementById('edit_description').value  = desc;
    document.getElementById('edit_total_marks').value  = total;
    document.getElementById('edit_convert_marks').value= convert;
    document.getElementById('edit_order').value        = order;
    document.getElementById('edit_status').value       = active;
    document.getElementById('editModal').classList.add('show');
}
function closeEdit() {
    document.getElementById('editModal').classList.remove('show');
}
document.getElementById('editModal').addEventListener('click', function(e){
    if(e.target === this) closeEdit();
});
function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('hamburger')?.classList.toggle('open');
    document.getElementById('overlay')?.classList.toggle('show');
}
</script>
</body>
</html>