<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Departments & Sessions - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
.main{margin-left:260px;padding:28px 36px 40px;min-height:100vh}
.topbar{background:#0C447C;border-radius:14px;padding:20px 26px;margin-bottom:24px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 1px 12px rgba(12,68,124,.15)}
.topbar h2{color:#fff;font-size:19px;font-weight:700}
.topbar p{color:#c9dbee;font-size:12px;margin-top:3px}
.badge-role{background:rgba(255,255,255,.15);color:#fff;padding:6px 16px;border-radius:20px;font-size:12px;font-weight:600}
.card-title-icon{width:26px;height:26px;border-radius:7px;background:#0C447C;display:inline-flex;align-items:center;justify-content:center;flex-shrink:0;vertical-align:middle;margin-right:10px}
.card-title-icon svg{width:14px;height:14px}
.grid2{display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:stretch}
.grid2 > div{display:flex;flex-direction:column}
.card{background:#fff;border-radius:14px;padding:24px 26px;box-shadow:0 1px 14px rgba(12,68,124,.08);border:1px solid #e8f0f9;margin-bottom:22px}
.card h3{color:#0C447C;font-size:14.5px;font-weight:700;margin:-24px -26px 18px;padding:14px 26px;border-radius:14px 14px 0 0;background:#eaf1fa;border-bottom:1px solid #d7e6f7;border-left:4px solid #0C447C;display:flex;align-items:center}
.form-row{display:flex;gap:10px;align-items:flex-end}
.form-group{margin-bottom:0;flex:1}
.form-group label{display:block;font-size:11px;font-weight:700;color:#0C447C;text-transform:uppercase;letter-spacing:.6px;margin-bottom:6px}
.form-group input{width:100%;padding:10px 14px;border:1.5px solid #e8f0f9;border-radius:8px;font-size:14px;color:#1e3a5f;font-family:'Segoe UI',sans-serif;outline:none;transition:border .2s}
.form-group input:focus{border-color:#0C447C}
.btn-primary{background:#0C447C;color:#fff;border:none;padding:10px 20px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;white-space:nowrap}
.btn-primary:hover{background:#0a3a6b}
.alert-success{background:#e8f5e9;color:#2e7d32;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;border:1px solid #c8e6c9}
.alert-error{background:#fce4e4;color:#c62828;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;border:1px solid #f5b5b5}
.item-row{display:flex;justify-content:space-between;align-items:center;border:1.5px solid #e8f0f9;border-radius:10px;padding:12px 16px;margin-top:12px}
.item-name{font-size:14px;font-weight:700;color:#0C447C}
.item-code{font-size:12px;color:#8096b0;margin-top:2px}
.item-actions{display:flex;gap:8px;align-items:center}
.badge-active{background:#e8f5e9;color:#2e7d32;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
.badge-inactive{background:#fce4e4;color:#c62828;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
.btn-sm{padding:5px 12px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;border:none}
.btn-toggle{background:#fff3cd;color:#856404;transition:background .15s}
.btn-toggle:hover{background:#ffe8a3}
.btn-delete{background:#fce4e4;color:#c62828;transition:background .15s}
.btn-delete:hover{background:#f9c9c9}
.item-row{transition:border-color .15s}
.item-row:hover{border-color:#c9dbee}
.empty-state{text-align:center;padding:24px;color:#8096b0;font-size:13px}
.list-scroll{max-height:420px;overflow-y:auto;padding-right:8px}
.list-scroll::-webkit-scrollbar{width:8px}
.list-scroll::-webkit-scrollbar-track{background:#f4f8fd;border-radius:10px}
.list-scroll::-webkit-scrollbar-thumb{background:#9db8d9;border-radius:10px}
.list-scroll::-webkit-scrollbar-thumb:hover{background:#a9c3e0}
.list-card{display:flex;flex-direction:column}
@media(max-width:768px){
    .main{margin-left:0;padding:16px}
    .grid2{grid-template-columns:1fr}
    .topbar{flex-direction:column;align-items:flex-start;gap:8px}
    .form-row{flex-direction:column;align-items:stretch}
}
</style>
</head>
<body>
@include('focal.partials.sidebar')
<div class="main">
  <div class="topbar">
    <div><h2>Departments & Sessions</h2><p>Manage departments and academic sessions used across the system</p></div>
    <span class="badge-role">Focal Person</span>
  </div>

  @if(session('success'))
    <div class="alert-success" style="display:flex;align-items:center;gap:8px"><svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="#2e7d32" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert-error" style="display:flex;align-items:center;gap:8px"><svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="#c62828" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>{{ session('error') }}</div>
  @endif

  <div class="grid2">
    <!-- Departments -->
    <div>
      <div class="card">
        <h3><span class="card-title-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg></span>Add Department</h3>
        <form method="POST" action="{{ route('focal.departments-sessions.department.store') }}">
          @csrf
          <div class="form-row">
            <div class="form-group">
              <label>Name</label>
              <input type="text" name="name" placeholder="e.g. Computer Science" required>
            </div>
            <div class="form-group">
              <label>Course Code</label>
              <input type="text" name="code" placeholder="e.g. CS" required>
            </div>
            <button type="submit" class="btn-primary">Add</button>
          </div>
          @error('name')<span style="color:red;font-size:12px;display:block;margin-top:6px">{{ $message }}</span>@enderror
          @error('code')<span style="color:red;font-size:12px;display:block;margin-top:6px">{{ $message }}</span>@enderror
        </form>
      </div>

      <div class="card list-card" style="flex:1">
        <h3><span class="card-title-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/><path d="M9 9v.01"/><path d="M9 12v.01"/><path d="M9 15v.01"/><path d="M9 18v.01"/></svg></span>Departments ({{ $departments->count() }})</h3>
        <div class="list-scroll">
        @forelse($departments as $dept)
          <div class="item-row">
            <div>
              <div class="item-name">{{ $dept->name }}</div>
              <div class="item-code">Course Code: {{ $dept->code }}</div>
            </div>
            <div class="item-actions">
              <span class="{{ $dept->is_active ? 'badge-active' : 'badge-inactive' }}">{{ $dept->is_active ? 'Active' : 'Inactive' }}</span>
              <form method="POST" action="{{ route('focal.departments-sessions.department.toggle', $dept->id) }}" style="display:inline">
                @csrf
                <button type="submit" class="btn-sm btn-toggle">{{ $dept->is_active ? 'Deactivate' : 'Activate' }}</button>
              </form>
              <form method="POST" action="{{ route('focal.departments-sessions.department.delete', $dept->id) }}" style="display:inline" onsubmit="return confirm('Delete this department? This cannot be undone.')">
                @csrf
                <button type="submit" class="btn-sm btn-delete">Delete</button>
              </form>
            </div>
          </div>
        @empty
          <div class="empty-state">No departments added yet.</div>
        @endforelse
        </div>
      </div>
    </div>

    <!-- Sessions -->
    <div>
      <div class="card">
        <h3><span class="card-title-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg></span>Add Session</h3>
        <form method="POST" action="{{ route('focal.departments-sessions.session.store') }}">
          @csrf
          <div class="form-row">
            <div class="form-group">
              <label>Session Name</label>
              <input type="text" name="name" placeholder="e.g. 2021-2025" required>
            </div>
            <button type="submit" class="btn-primary">Add</button>
          </div>
          @error('name')<span style="color:red;font-size:12px;display:block;margin-top:6px">{{ $message }}</span>@enderror
        </form>
      </div>

      <div class="card list-card" style="flex:1">
        <h3><span class="card-title-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg></span>Sessions ({{ $sessions->count() }})</h3>
        <div class="list-scroll">
        @forelse($sessions as $session)
          <div class="item-row">
            <div>
              <div class="item-name">{{ $session->name }}</div>
            </div>
            <div class="item-actions">
              <span class="{{ $session->is_active ? 'badge-active' : 'badge-inactive' }}">{{ $session->is_active ? 'Active' : 'Inactive' }}</span>
              <form method="POST" action="{{ route('focal.departments-sessions.session.toggle', $session->id) }}" style="display:inline">
                @csrf
                <button type="submit" class="btn-sm btn-toggle">{{ $session->is_active ? 'Deactivate' : 'Activate' }}</button>
              </form>
              <form method="POST" action="{{ route('focal.departments-sessions.session.delete', $session->id) }}" style="display:inline" onsubmit="return confirm('Delete this session? This cannot be undone.')">
                @csrf
                <button type="submit" class="btn-sm btn-delete">Delete</button>
              </form>
            </div>
          </div>
        @empty
          <div class="empty-state">No sessions added yet.</div>
        @endforelse
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
