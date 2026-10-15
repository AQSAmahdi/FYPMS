<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Manage Phases - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
.main{margin-left:260px;padding:28px 32px}
.topbar{background:#0C447C;border-radius:14px;padding:20px 26px;margin-bottom:24px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 4px 14px rgba(12,68,124,.18)}
.topbar h2{color:#fff;font-size:19px;font-weight:700}
.topbar p{color:rgba(255,255,255,.9)!important;font-size:12.5px;margin-top:2px}
.badge-role{background:rgba(255,255,255,.18);color:#fff;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600}
.grid2{display:grid;grid-template-columns:1fr 1fr;gap:20px}
.card{background:#fff;border-radius:12px;padding:0 0 22px 0;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9;overflow:hidden}
.card h3{color:#fff;background:#0C447C;font-size:15px;font-weight:700;margin-bottom:20px;padding:16px 22px}
.card > form, .card > div#phases-container, .card > div.empty{padding-left:22px;padding-right:22px}
.form-group{margin-bottom:18px}
.form-group label{display:block;font-size:11.5px;font-weight:700;color:#0C447C;text-transform:uppercase;letter-spacing:.7px;margin-bottom:8px}
.form-group input,.form-group textarea,.form-group select{width:100%;padding:11px 15px;border:1.5px solid #e8f0f9;border-radius:10px;font-size:13.5px;color:#1e3a5f;outline:none;transition:all .2s;font-family:'Segoe UI',sans-serif;background:#f7fafd}
.form-group input:focus,.form-group textarea:focus,.form-group select:focus{border-color:#0C447C;background:#fff;box-shadow:0 0 0 3px rgba(12,68,124,.08)}
.form-group input::placeholder,.form-group textarea::placeholder{color:#a8bdd0}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.btn-primary{background:#0C447C;color:#fff;border:none;padding:13px 20px;border-radius:10px;font-size:14px;font-weight:600;cursor:pointer;width:100%;margin-top:6px;transition:all .2s;box-shadow:0 3px 10px rgba(12,68,124,.25)}
.btn-primary:hover{background:#185FA5;box-shadow:0 4px 14px rgba(12,68,124,.32);transform:translateY(-1px)}
.alert-success{background:#dcfce7;color:#166534;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;border:1px solid #bbf7d0}
.alert-error{background:#fef2f2;color:#b91c1c;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px}
.phase-item{border:1.5px solid #e8f0f9;border-radius:12px;padding:18px 20px;margin-bottom:14px;background:#fbfdff;transition:all .2s}
.phase-item:hover{border-color:#c7d8ec;box-shadow:0 3px 14px rgba(12,68,124,.1)}
.phase-name{font-size:15px;font-weight:700;color:#0C447C;margin-bottom:6px;display:flex;align-items:center;gap:8px}
.phase-name::before{content:"";width:8px;height:8px;border-radius:50%;background:#0C447C;display:inline-block;flex-shrink:0}
.phase-desc{font-size:12px;color:#8096b0;margin-bottom:12px}
.marks-row{display:flex;gap:10px;margin-bottom:12px}
.mark-box{background:#f0f6ff;border:1px solid #e2ecf9;border-radius:10px;padding:10px 14px;flex:1;text-align:center}
.mark-box .lbl{font-size:10px;color:#8096b0;text-transform:uppercase;letter-spacing:.5px;margin-bottom:3px}
.mark-box .val{font-size:16px;font-weight:700;color:#0C447C}
.criteria-list{font-size:11px;color:#5a7a9a;margin-bottom:10px;background:#f7fafd;border-radius:6px;padding:8px 10px}
.criteria-list strong{color:#0C447C}
.phase-actions{display:flex;gap:8px}
.phase-actions{padding-top:12px;border-top:1px solid #eef3f9}
.phase-search-wrap{padding:0 22px 14px 22px}
.phase-search{width:100%;padding:10px 14px 10px 38px;border:1.5px solid #e8f0f9;border-radius:9px;font-size:13px;color:#1e3a5f;outline:none;background:#f7fafd url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="%238096b0" stroke-width="2"><circle cx="6.5" cy="6.5" r="5"/><line x1="10.5" y1="10.5" x2="15" y2="15"/></svg>') no-repeat 12px center;font-family:'Segoe UI',sans-serif;transition:all .2s}
.phase-search:focus{border-color:#0C447C;background-color:#fff;box-shadow:0 0 0 3px rgba(12,68,124,.08)}
.phase-item.hidden-by-search{display:none}
.phases-scroll{max-height:520px;overflow-y:auto;padding-right:6px}
.phases-scroll::-webkit-scrollbar{width:6px}
.phases-scroll::-webkit-scrollbar-thumb{background:#c7d8ec;border-radius:10px}
.phases-scroll::-webkit-scrollbar-track{background:transparent}
.btn-sm{padding:7px 16px;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;transition:all .2s}
.btn-edit{background:#fff;color:#0C447C;border:1.5px solid #c7d8ec}
.btn-edit:hover{background:#0C447C;color:#fff}
.btn-delete{background:#fff;color:#b91c1c;border:1.5px solid #f3caca}
.btn-delete:hover{background:#b91c1c;color:#fff}
.empty{text-align:center;padding:40px;color:#8096b0;font-size:14px}
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center;overflow-y:auto;padding:20px 0}
.modal-overlay.show{display:flex}
.modal{background:#fff;border-radius:16px;padding:28px;width:100%;max-width:460px;box-shadow:0 20px 60px rgba(12,68,124,.2);margin:auto}
.modal h3{color:#0C447C;font-size:16px;font-weight:700;margin-bottom:20px}
.modal-actions{display:flex;gap:10px;margin-top:16px}
.btn-cancel{flex:1;padding:10px;background:#f4f7fb;color:#5a7a9a;border:1.5px solid #d5e3f0;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer}
.btn-save{flex:2;padding:10px;background:#0C447C;color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer}
.criteria-wrap{margin-bottom:14px;background:#f7fafd;border-radius:8px;padding:14px;border:1px solid #e8f0f9}
.criteria-row{display:flex;gap:8px;margin-bottom:8px;align-items:center}
.criteria-row input{padding:8px 10px;border:1.5px solid #e8f0f9;border-radius:6px;font-size:13px;font-family:'Segoe UI',sans-serif}
.criteria-row input[name="criteria_name[]"]{flex:2}
.criteria-row input[name="criteria_marks[]"]{flex:1}
.criteria-row button{background:#fef2f2;color:#b91c1c;border:none;border-radius:6px;padding:8px 10px;cursor:pointer;font-weight:700}
.criteria-add-btn{background:#e8f0f9;color:#0C447C;border:none;padding:7px 14px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;margin-top:2px}
.criteria-total{font-size:12px;margin-top:8px;font-weight:600}
@media(max-width:900px){.grid2{grid-template-columns:1fr}}
@media(max-width:768px){.main{margin-left:0;padding:16px}.form-row{grid-template-columns:1fr}}
</style>
</head>
<body>
@include('focal.partials.sidebar')
<script>
function filterPhases(){
    const q = document.getElementById('phaseSearch').value.toLowerCase();
    document.querySelectorAll('#phasesScrollList .phase-item').forEach(item => {
        const name = item.getAttribute('data-phase-name') || '';
        item.classList.toggle('hidden-by-search', !name.includes(q));
    });
}
</script>
<div class="main">
  <div class="topbar">
    <div>
      <h2>Manage Phases</h2>
      <p>Create and manage FYP evaluation phases</p>
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
    <!-- Create Form -->
    <div class="card">
      <h3>Add New Phase</h3>
      <form method="POST" action="{{ route('focal.phases.store') }}">
        @csrf
        <div class="form-group">
          <label>Phase Name</label>
          <input type="text" name="name" required placeholder="e.g. Mid Evaluation" value="{{ old('name') }}">
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Total Marks</label>
            <input type="number" name="total_marks" id="add_total_marks" required min="1" placeholder="e.g. 100" value="{{ old('total_marks') }}" oninput="updateCriteriaTotal('add')">
          </div>
          <div class="form-group">
            <label>Convert To</label>
            <input type="number" name="convert_to_marks" required min="1" placeholder="e.g. 50" value="{{ old('convert_to_marks') }}">
          </div>
        </div>

        <div class="form-group">
          <label style="display:flex;align-items:center;gap:8px;text-transform:none;letter-spacing:0">
            <input type="checkbox" id="criteria_toggle_add" style="width:auto" onchange="document.getElementById('criteria_wrap_add').style.display=this.checked?'block':'none'">
            Break Marks into Criteria (Optional)
          </label>
        </div>
        <div id="criteria_wrap_add" class="criteria-wrap" style="display:none">
          <div id="criteria_rows_add"></div>
          <button type="button" class="criteria-add-btn" onclick="addCriteriaRow('add')">+ Add Criteria</button>
          <div id="criteria_total_add" class="criteria-total"></div>
        </div>

        <div class="form-group">
          <label>Description (Optional)</label>
          <textarea name="description" rows="2" placeholder="Phase description...">{{ old('description') }}</textarea>
        </div>
        <div class="form-group">
          <label>Evaluation Panel (Optional)</label>
          <select name="panel_id" style="width:100%;padding:10px 14px;border:1.5px solid #e8f0f9;border-radius:8px;font-size:13px;color:#1a2b3c;background:#f7fafd;outline:none;font-family:'Segoe UI',sans-serif">
            <option value="">-- No Panel --</option>
            @foreach($panels as $panel)
              <option value="{{ $panel->id }}">{{ $panel->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label style="display:flex;align-items:center;gap:8px;text-transform:none;letter-spacing:0">
            <input type="checkbox" name="requires_upload" value="1" id="requires_upload_add" style="width:auto" onchange="document.getElementById('upload_instr_wrap_add').style.display=this.checked?'block':'none'">
            Requires Document / Code Upload
          </label>
        </div>
        <div class="form-group" id="upload_instr_wrap_add" style="display:none">
          <label>Upload Instructions (Optional)</label>
          <textarea name="upload_instructions" rows="2" placeholder="e.g. Upload SRS document in PDF, or project code as ZIP">{{ old('upload_instructions') }}</textarea>
        </div>
        <button type="submit" class="btn-primary">Add Phase</button>
      </form>
    </div>

    <!-- Phases List -->
    <div class="card">
      <h3>Existing Phases ({{ $phases->count() }})</h3>
      <div class="phase-search-wrap">
        <input type="text" class="phase-search" id="phaseSearch" placeholder="Search phase by name..." oninput="filterPhases()">
      </div>
      <div class="phases-scroll" id="phasesScrollList">
      @if($phases->count() > 0)
        @foreach($phases as $phase)
        <div class="phase-item" data-phase-name="{{ strtolower($phase->name) }}">
          <div class="phase-name">{{ $phase->name }}
            @if($phase->requires_upload)
              <span style="background:#e0e7ff;color:#3730a3;padding:2px 8px;border-radius:20px;font-size:10px;font-weight:600;margin-left:6px;vertical-align:middle">Upload Required</span>
            @endif
            @if($phase->panel)
              <span style="background:#fef9c3;color:#854d0e;padding:2px 8px;border-radius:20px;font-size:10px;font-weight:600;margin-left:4px;vertical-align:middle">Panel: {{ $phase->panel->name }}</span>
            @endif
          </div>
          @if($phase->description)
          <div class="phase-desc">{{ $phase->description }}</div>
          @endif
          <div class="marks-row">
            <div class="mark-box">
              <div class="lbl">Total Marks</div>
              <div class="val">{{ $phase->total_marks }}</div>
            </div>
            <div class="mark-box">
              <div class="lbl">Converts To</div>
              <div class="val">{{ $phase->convert_to_marks }}</div>
            </div>
          </div>
          @if($phase->criteria->count() > 0)
          <div class="criteria-list">
            <strong>Criteria:</strong>
            @foreach($phase->criteria as $c)
              {{ $c->name }} ({{ $c->max_marks }}){{ !$loop->last ? ', ' : '' }}
            @endforeach
          </div>
          @endif
          <div class="phase-actions">
            <button class="btn-sm btn-edit" onclick='openEdit({{ $phase->id }},{{ json_encode($phase->name) }},{{ $phase->total_marks }},{{ $phase->convert_to_marks }},{{ json_encode($phase->description ?? "") }},{{ $phase->requires_upload ? "true" : "false" }},{{ json_encode($phase->upload_instructions ?? "") }},{{ $phase->panel_id ?? "null" }},{{ json_encode($phase->criteria->map(function($c){return ["name"=>$c->name,"max_marks"=>$c->max_marks];})->values()) }})'>Edit</button>
            <form method="POST" action="{{ route('focal.phases.destroy', $phase->id) }}" onsubmit="return confirm('Delete this phase?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn-sm btn-delete">Delete</button>
            </form>
          </div>
        </div>
        @endforeach
      @else
        <div class="empty">No phases created yet.</div>
      @endif
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal-overlay" id="editModal">
  <div class="modal">
    <h3>Edit Phase</h3>
    <form id="editForm" method="POST">
      @csrf @method('PUT')
      <div class="form-group">
        <label>Phase Name</label>
        <input type="text" name="name" id="edit_name" required>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Total Marks</label>
          <input type="number" name="total_marks" id="edit_total" required min="1" oninput="updateCriteriaTotal('edit')">
        </div>
        <div class="form-group">
          <label>Convert To</label>
          <input type="number" name="convert_to_marks" id="edit_convert" required min="1">
        </div>
      </div>

      <div class="form-group">
        <label style="display:flex;align-items:center;gap:8px;text-transform:none;letter-spacing:0">
          <input type="checkbox" id="criteria_toggle_edit" style="width:auto" onchange="document.getElementById('criteria_wrap_edit').style.display=this.checked?'block':'none'">
          Break Marks into Criteria (Optional)
        </label>
      </div>
      <div id="criteria_wrap_edit" class="criteria-wrap" style="display:none">
        <div id="criteria_rows_edit"></div>
        <button type="button" class="criteria-add-btn" onclick="addCriteriaRow('edit')">+ Add Criteria</button>
        <div id="criteria_total_edit" class="criteria-total"></div>
      </div>

      <div class="form-group">
        <label>Description</label>
        <textarea name="description" id="edit_desc" rows="2"></textarea>
      </div>
      <div class="form-group">
        <label>Evaluation Panel (Optional)</label>
        <select name="panel_id" id="edit_panel_id" style="width:100%;padding:10px 14px;border:1.5px solid #e8f0f9;border-radius:8px;font-size:13px;color:#1a2b3c;background:#f7fafd;outline:none;font-family:'Segoe UI',sans-serif">
          <option value="">-- No Panel --</option>
          @foreach($panels as $panel)
            <option value="{{ $panel->id }}">{{ $panel->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label style="display:flex;align-items:center;gap:8px;text-transform:none;letter-spacing:0">
          <input type="checkbox" name="requires_upload" value="1" id="edit_requires_upload" style="width:auto" onchange="document.getElementById('upload_instr_wrap_edit').style.display=this.checked?'block':'none'">
          Requires Document / Code Upload
        </label>
      </div>
      <div class="form-group" id="upload_instr_wrap_edit" style="display:none">
        <label>Upload Instructions (Optional)</label>
        <textarea name="upload_instructions" id="edit_upload_instr" rows="2"></textarea>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn-cancel" onclick="closeEdit()">Cancel</button>
        <button type="submit" class="btn-save">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<script>
function addCriteriaRow(suffix, name, marks) {
    const container = document.getElementById('criteria_rows_' + suffix);
    const row = document.createElement('div');
    row.className = 'criteria-row';
    const nameVal = (name || '').replace(/"/g, '&quot;');
    row.innerHTML =
        '<input type="text" name="criteria_name[]" placeholder="e.g. Presentation" value="' + nameVal + '">' +
        '<input type="number" name="criteria_marks[]" placeholder="Marks" min="1" value="' + (marks || '') + '" oninput="updateCriteriaTotal(\'' + suffix + '\')">' +
        '<button type="button" onclick="this.parentElement.remove();updateCriteriaTotal(\'' + suffix + '\')">&times;</button>';
    container.appendChild(row);
    updateCriteriaTotal(suffix);
}

function clearCriteriaRows(suffix) {
    document.getElementById('criteria_rows_' + suffix).innerHTML = '';
}

function updateCriteriaTotal(suffix) {
    const container = document.getElementById('criteria_rows_' + suffix);
    const totalEl = document.getElementById('criteria_total_' + suffix);
    const totalMarksInput = document.getElementById(suffix === 'add' ? 'add_total_marks' : 'edit_total');
    let sum = 0;
    container.querySelectorAll('input[name="criteria_marks[]"]').forEach(function(inp) {
        sum += parseFloat(inp.value) || 0;
    });
    const target = parseFloat(totalMarksInput ? totalMarksInput.value : 0) || 0;
    if (target > 0 && sum === target) {
        totalEl.innerHTML = 'Sum: ' + sum + ' / ' + target + ' \u2713 matches Total Marks';
        totalEl.style.color = '#166534';
    } else {
        totalEl.innerHTML = 'Sum: ' + sum + (target > 0 ? ' / ' + target + ' \u2014 must equal Total Marks' : '');
        totalEl.style.color = '#b91c1c';
    }
}

function openEdit(id, name, total, convert, desc, requiresUpload, uploadInstr, panelId, criteria) {
    document.getElementById('editForm').action = '/focal/phases/' + id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_total').value = total;
    document.getElementById('edit_convert').value = convert;
    document.getElementById('edit_desc').value = desc;
    document.getElementById('edit_requires_upload').checked = requiresUpload;
    document.getElementById('edit_upload_instr').value = uploadInstr;
    document.getElementById('upload_instr_wrap_edit').style.display = requiresUpload ? 'block' : 'none';
    document.getElementById('edit_panel_id').value = panelId || '';

    clearCriteriaRows('edit');
    if (criteria && criteria.length > 0) {
        criteria.forEach(function(c) { addCriteriaRow('edit', c.name, c.max_marks); });
        document.getElementById('criteria_toggle_edit').checked = true;
        document.getElementById('criteria_wrap_edit').style.display = 'block';
    } else {
        document.getElementById('criteria_toggle_edit').checked = false;
        document.getElementById('criteria_wrap_edit').style.display = 'none';
    }
    updateCriteriaTotal('edit');

    document.getElementById('editModal').classList.add('show');
}
function closeEdit() {
    document.getElementById('editModal').classList.remove('show');
}
document.getElementById('editModal').addEventListener('click', function(e){
    if(e.target===this) closeEdit();
});
function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('overlay')?.classList.toggle('show');
}
</script>
</body>
</html>
