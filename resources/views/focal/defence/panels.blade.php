<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Defence Panels - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
.main{margin-left:260px;padding:24px 28px;min-height:100vh}
.topbar{background:#0C447C;border-radius:14px;padding:20px 26px;margin-bottom:24px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 4px 14px rgba(12,68,124,.18)}
.topbar h2{color:#fff;font-size:19px;font-weight:700}
.topbar p{color:rgba(255,255,255,.9)!important;font-size:12.5px;margin-top:2px}
.badge-role{background:rgba(255,255,255,.18);color:#fff;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600}
.card-icon{width:30px;height:30px;background:rgba(255,255,255,.18);border-radius:8px;display:inline-flex;align-items:center;justify-content:center;margin-right:10px;vertical-align:middle;flex-shrink:0}
.card h3{display:flex;align-items:center}
.grid2{display:grid;grid-template-columns:1fr 1fr;gap:20px}
.card{background:#fff;border-radius:12px;padding:0 0 22px 0;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9;overflow:hidden}
.card h3{color:#fff;background:#0C447C;font-size:15px;font-weight:700;margin-bottom:18px;padding:16px 22px;display:flex;align-items:center}
.card > form, .card > div#panels-container, .card > div.loading{padding-left:22px;padding-right:22px}
.form-group{margin-bottom:20px}
.form-group label{display:block;font-size:11.5px;font-weight:700;color:#0C447C;text-transform:uppercase;letter-spacing:.7px;margin-bottom:8px}
.form-group input,.form-group textarea,.form-group select{width:100%;padding:12px 16px;border:1.5px solid #e8f0f9;border-radius:10px;font-size:14px;color:#1e3a5f;font-family:'Segoe UI',sans-serif;outline:none;background:#f7fafd;transition:all .2s}
.form-group input:focus,.form-group textarea:focus{border-color:#0C447C;background:#fff;box-shadow:0 0 0 3px rgba(12,68,124,.08)}
.form-group input::placeholder,.form-group textarea::placeholder{color:#a8bdd0}
.select2-wrap{position:relative}
.select2-input{width:100%;padding:12px 16px;border:1.5px solid #e8f0f9;border-radius:10px;font-size:14px;color:#1e3a5f;font-family:'Segoe UI',sans-serif;outline:none;cursor:pointer;background:#f7fafd;transition:all .2s}
.select2-input:hover{background:#fff;border-color:#c7d8ec}
.select2-input:focus{border-color:#0C447C}
.select2-dropdown{display:none;position:absolute;top:100%;left:0;right:0;background:#fff;border:1.5px solid #0C447C;border-radius:8px;z-index:100;box-shadow:0 4px 20px rgba(12,68,124,.15);overflow:hidden}
.select2-dropdown.open{display:block}
.select2-search{padding:8px 10px;border-bottom:1px solid #e8f0f9}
.select2-search input{width:100%;padding:7px 10px;border:1.5px solid #e8f0f9;border-radius:6px;font-size:13px;outline:none;font-family:'Segoe UI',sans-serif}
.select2-search input:focus{border-color:#0C447C}
.select2-options{max-height:180px;overflow-y:auto}
.select2-option{display:flex;align-items:center;gap:10px;padding:9px 14px;cursor:pointer;font-size:13px;color:#1e3a5f;transition:background .15s}
.select2-option:hover{background:#f0f6ff}
.select2-option input{width:15px;height:15px;accent-color:#0C447C;flex-shrink:0}
.selected-tags{display:flex;flex-wrap:wrap;gap:6px;margin-top:8px}
.sel-tag{background:#e8f0f9;color:#0C447C;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:500;display:flex;align-items:center;gap:6px}
.sel-tag button{background:none;border:none;cursor:pointer;color:#0C447C;font-size:14px;line-height:1;padding:0}
.btn-primary{background:#0C447C;color:#fff;border:none;padding:13px 20px;border-radius:10px;font-size:14px;font-weight:600;cursor:pointer;width:100%;margin-top:6px;transition:all .2s;box-shadow:0 3px 10px rgba(12,68,124,.25)}
.btn-primary:hover{background:#185FA5;box-shadow:0 4px 14px rgba(12,68,124,.32);transform:translateY(-1px)}
.alert-success{background:#e8f5e9;color:#2e7d32;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;border:1px solid #c8e6c9}
.panel-item{border:1.5px solid #e8f0f9;border-radius:12px;padding:18px 20px;margin-bottom:14px;background:#fbfdff;transition:all .2s}
.panel-item:hover{border-color:#c7d8ec;box-shadow:0 3px 12px rgba(12,68,124,.08)}
.panel-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px}
.panel-name{font-size:15px;font-weight:700;color:#0C447C;display:flex;align-items:center;gap:8px}
.panel-name::before{content:"";width:8px;height:8px;border-radius:50%;background:#0C447C;display:inline-block}
.badge-active{background:#e8f5e9;color:#2e7d32;padding:5px 12px;border-radius:20px;font-size:11px;font-weight:700}
.badge-inactive{background:#fce4e4;color:#c62828;padding:5px 12px;border-radius:20px;font-size:11px;font-weight:700}
.panel-facultys{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:10px}
.faculty-tag{background:#e8f0f9;color:#0C447C;padding:5px 12px;border-radius:20px;font-size:11.5px;font-weight:600}
.panel-facultys{margin-bottom:14px}
.panel-actions{display:flex;gap:8px;padding-top:12px;border-top:1px solid #eef3f9}
.btn-sm{padding:7px 16px;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;border:none;transition:all .2s}
.btn-sm:hover{opacity:.85;transform:translateY(-1px)}
.btn-toggle{background:#fff;color:#92600a;border:1.5px solid #f0dca0}
.btn-toggle:hover{background:#fff8e1}
.btn-delete{background:#fff;color:#b91c1c;border:1.5px solid #f3caca}
.btn-delete:hover{background:#fef2f2}
.empty-state{text-align:center;padding:40px;color:#8096b0;font-size:14px}
.loading{text-align:center;padding:40px;color:#8096b0;font-size:14px}
@media(max-width:768px){
    .main{margin-left:0;padding:16px}
    .grid2{grid-template-columns:1fr}
    .topbar{flex-direction:column;align-items:flex-start;gap:8px}
}
</style>
</head>
<body>
@include('focal.partials.sidebar')
<div class="main">
  <div class="topbar">
    <div><h2>Defence Panels</h2><p>Create and manage evaluation panels</p></div>
    <span class="badge-role">Focal Person</span>
  </div>

  @if(session('success'))
    <div class="alert-success">✓ {{ session('success') }}</div>
  @endif
  @if(session('warning'))
    <div style="background:#fff8e1;color:#92600a;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;border:1px solid #ffe082">
      {{ session('warning') }}
    </div>
  @endif

  <div class="grid2">
    <!-- Create Panel Form - Laravel handles this -->
    <div class="card">
      <h3><span class="card-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="#fff" stroke-width="2.5" stroke-linecap="round"/></svg></span> Create New Panel</h3>
      <form method="POST" action="{{ route('focal.defence.panels.store') }}">
        @csrf
        <div class="form-group">
          <label>Panel Name</label>
          <input type="text" name="name" placeholder="e.g. CS Defence Panel 2026" required>
          @error('name')<span style="color:red;font-size:12px">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
          <label>Description (Optional)</label>
          <textarea name="description" rows="2" placeholder="Panel description..."></textarea>
        </div>
        <div class="form-group">
          <label>Select Faculty Members</label>
          <div class="select2-wrap">
            <div class="select2-input" id="selectTrigger" onclick="toggleDropdown()">
              Click to select faculty...
            </div>
            <div class="select2-dropdown" id="facultyDropdown">
              <div class="select2-search">
                <input type="text" id="facultySearch" placeholder="Search faculty..." oninput="filterFacultys()">
              </div>
              <div class="select2-options" id="facultyOptions">
                @foreach($facultys as $faculty)
                <label class="select2-option" id="opt_{{ $faculty->id }}">
                  <input type="checkbox" name="faculty_ids[]" value="{{ $faculty->id }}"
                    onchange="updateTags({{ $faculty->id }}, '{{ $faculty->name }}', this.checked)">
                  {{ $faculty->name }}
                </label>
                @endforeach
              </div>
            </div>
          </div>
          <div class="selected-tags" id="selectedTags"></div>
          @error('faculty_ids')<span style="color:red;font-size:12px">{{ $message }}</span>@enderror
        </div>
        <button type="submit" class="btn-primary">Create Panel</button>
      </form>
    </div>

    <!-- Panels List - API se -->
    <div class="card">
      <h3 id="panels-title-wrap"><span class="card-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm7 3a3.5 3.5 0 0 0 0-7" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span> <span id="panels-title">Existing Panels</span></h3>
      <div id="panels-container">
        <div class="loading">Loading panels...</div>
      </div>
    </div>
  </div>
</div>

<script>
const token = localStorage.getItem('api_token');

async function loadPanels() {
    const res  = await fetch('/api/focal/panels', {
        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        }
    });
    const data = await res.json();
    const container = document.getElementById('panels-container');

    if (!data.success || data.panels.length === 0) {
        container.innerHTML = '<div class="empty-state">No panels created yet.</div>';
        document.getElementById('panels-title').textContent = 'Existing Panels (0)';
        return;
    }

    document.getElementById('panels-title').textContent = 'Existing Panels (' + data.panels.length + ')';
    container.innerHTML = '';

    data.panels.forEach(panel => {
        let facultysHtml = panel.facultys.map(t =>
            `<span class="faculty-tag">${t.name}</span>`
        ).join('');

        const badgeClass = panel.status === 'active' ? 'badge-active' : 'badge-inactive';
        const badgeText  = panel.status === 'active' ? 'Active' : 'Inactive';
        const toggleText = panel.status === 'active' ? 'Deactivate' : 'Activate';

        container.innerHTML += `
            <div class="panel-item">
                <div class="panel-header">
                    <span class="panel-name">${panel.name}</span>
                    <span class="${badgeClass}">${badgeText}</span>
                </div>
                <div class="panel-facultys">${facultysHtml}</div>
                <div class="panel-actions">
                    <form method="POST" action="/focal/defence/panels/${panel.id}/toggle">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]').content}">
                        <button type="submit" class="btn-sm btn-toggle">${toggleText}</button>
                    </form>
                    <form method="POST" action="/focal/defence/panels/${panel.id}/delete" onsubmit="return confirm('Delete this panel?')">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]').content}">
                        <button type="submit" class="btn-sm btn-delete">Delete</button>
                    </form>
                </div>
            </div>`;
    });
}

function toggleDropdown(){
    document.getElementById('facultyDropdown').classList.toggle('open');
}
document.addEventListener('click', function(e){
    if(!e.target.closest('.select2-wrap')){
        document.getElementById('facultyDropdown').classList.remove('open');
    }
});
function filterFacultys(){
    const q = document.getElementById('facultySearch').value.toLowerCase();
    document.querySelectorAll('.select2-option').forEach(opt => {
        opt.style.display = opt.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}
function updateTags(id, name, checked){
    const tags    = document.getElementById('selectedTags');
    const trigger = document.getElementById('selectTrigger');
    if(checked){
        const tag = document.createElement('span');
        tag.className = 'sel-tag';
        tag.id = 'tag_'+id;
        tag.innerHTML = name + '<button type="button" onclick="removeFaculty('+id+')">×</button>';
        tags.appendChild(tag);
    } else {
        const t = document.getElementById('tag_'+id);
        if(t) t.remove();
    }
    const count = document.querySelectorAll('input[name="faculty_ids[]"]:checked').length;
    trigger.textContent = count > 0 ? count+' faculty selected' : 'Click to select faculty...';
}
function removeFaculty(id){
    const cb = document.querySelector('input[name="faculty_ids[]"][value="'+id+'"]');
    if(cb){ cb.checked = false; }
    const t = document.getElementById('tag_'+id);
    if(t) t.remove();
    const count = document.querySelectorAll('input[name="faculty_ids[]"]:checked').length;
    document.getElementById('selectTrigger').textContent = count > 0 ? count+' faculty selected' : 'Click to select faculty...';
}

loadPanels();
</script>
</body>
</html>