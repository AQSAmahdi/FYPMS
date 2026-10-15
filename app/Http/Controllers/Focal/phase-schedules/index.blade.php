<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Phase Schedules - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
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
.schedule-item{border:1.5px solid #e8f0f9;border-radius:10px;padding:16px;margin-bottom:12px;transition:all .2s}
.schedule-item:hover{border-color:#0C447C;box-shadow:0 2px 12px rgba(12,68,124,.08)}
.schedule-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px}
.schedule-name{font-size:15px;font-weight:700;color:#0C447C}
.schedule-meta{font-size:12px;color:#8096b0;margin-top:2px}
.badge-open{background:#dcfce7;color:#166534;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
.badge-closed{background:#fef2f2;color:#b91c1c;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
.badge-upcoming{background:#fef9c3;color:#854d0e;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
.date-info{display:flex;gap:12px;margin-bottom:10px;flex-wrap:wrap}
.date-box{background:#f4f8fd;border-radius:8px;padding:8px 14px;flex:1;min-width:120px}
.date-box .lbl{font-size:10px;color:#8096b0;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px}
.date-box .val{font-size:13px;font-weight:600;color:#0C447C}
.schedule-actions{display:flex;gap:8px;flex-wrap:wrap}
.btn-sm{padding:6px 14px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;border:none;transition:all .2s}
.btn-edit{background:#e8f0f9;color:#0C447C}
.btn-edit:hover{background:#0C447C;color:#fff}
.btn-delete{background:#fef2f2;color:#b91c1c}
.btn-delete:hover{background:#b91c1c;color:#fff}
.empty{text-align:center;padding:40px;color:#8096b0;font-size:14px}
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center}
.modal-overlay.show{display:flex}
.modal{background:#fff;border-radius:16px;padding:28px;width:100%;max-width:500px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(12,68,124,.2)}
.modal h3{color:#0C447C;font-size:16px;font-weight:700;margin-bottom:20px}
.modal-actions{display:flex;gap:10px;margin-top:16px}
.btn-cancel{flex:1;padding:10px;background:#f4f7fb;color:#5a7a9a;border:1.5px solid #d5e3f0;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer}
.btn-save{flex:2;padding:10px;background:#0C447C;color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer}
@media(max-width:900px){.grid2{grid-template-columns:1fr}}
@media(max-width:768px){.main{margin-left:0;padding:16px}.topbar{flex-direction:column;align-items:flex-start;gap:8px}.form-row{grid-template-columns:1fr}}
</style>
</head>
<body>
@include('focal.partials.sidebar')

<div class="main">
  <div class="topbar">
    <div>
      <h2>Phase Schedules</h2>
      <p>Set start & end dates for each evaluation phase</p>
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

    <!-- Create Schedule Form -->
    <div class="card">
      <h3>➕ Add Schedule</h3>
      <form method="POST" action="{{ route('focal.phase-schedules.store') }}">
        @csrf
        <div class="form-group">
          <label>Select Phase</label>
          <select name="phase_id" required>
            <option value="">-- Select Phase --</option>
            @foreach($phases as $phase)
            <option value="{{ $phase->id }}" {{ old('phase_id')==$phase->id?'selected':'' }}>
              {{ $phase->name }}
            </option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label>Select Group</label>
          <select name="group_id" required>
            <option value="">-- Select Group --</option>
            @foreach($groups as $group)
            <option value="{{ $group->id }}" {{ old('group_id')==$group->id?'selected':'' }}>
              {{ $group->name ?? 'Group '.$group->id }}
            </option>
            @endforeach
          </select>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Start Date</label>
            <input type="datetime-local" name="start_date" required value="{{ old('start_date') }}">
          </div>
          <div class="form-group">
            <label>End Date</label>
            <input type="datetime-local" name="end_date" required value="{{ old('end_date') }}">
          </div>
        </div>
        <div class="form-group">
          <label>Venue (Optional)</label>
          <input type="text" name="venue" placeholder="e.g. Room 301, Lab A" value="{{ old('venue') }}">
        </div>
        <div class="form-group">
          <label>Notes (Optional)</label>
          <textarea name="notes" rows="2" placeholder="Any special instructions...">{{ old('notes') }}</textarea>
        </div>
        <button type="submit" class="btn-primary">Add Schedule</button>
      </form>
    </div>

    <!-- Schedules List -->
    <div class="card">
      <h3>📅 Existing Schedules ({{ $schedules->count() }})</h3>

      @if($schedules->count() > 0)
        @foreach($schedules as $schedule)
        @php
          $now = now();
          $start = \Carbon\Carbon::parse($schedule->start_date);
          $end   = \Carbon\Carbon::parse($schedule->end_date);
          if($now->lt($start)) $status = 'upcoming';
          elseif($now->between($start,$end)) $status = 'open';
          else $status = 'closed';
        @endphp
        <div class="schedule-item">
          <div class="schedule-header">
            <div>
              <div class="schedule-name">{{ $schedule->phase->name ?? 'N/A' }}</div>
              <div class="schedule-meta">
                Group: {{ $schedule->group->name ?? 'Group '.$schedule->group_id }}
                @if($schedule->venue) &bull; {{ $schedule->venue }} @endif
              </div>
            </div>
            <span class="badge-{{ $status }}">
              {{ ucfirst($status) }}
            </span>
          </div>

          <div class="date-info">
            <div class="date-box">
              <div class="lbl">Start</div>
              <div class="val">{{ $start->format('d M Y, h:i A') }}</div>
            </div>
            <div class="date-box">
              <div class="lbl">End</div>
              <div class="val">{{ $end->format('d M Y, h:i A') }}</div>
            </div>
          </div>

          @if($schedule->notes)
          <div style="font-size:12px;color:#5a7a9a;margin-bottom:10px">📝 {{ $schedule->notes }}</div>
          @endif

          <div class="schedule-actions">
            <button class="btn-sm btn-edit"
              onclick="openEdit(
                {{ $schedule->id }},
                {{ $schedule->phase_id }},
                {{ $schedule->group_id }},
                '{{ $schedule->start_date }}',
                '{{ $schedule->end_date }}',
                '{{ addslashes($schedule->venue ?? '') }}',
                '{{ addslashes($schedule->notes ?? '') }}'
              )">✏️ Edit</button>
            <form method="POST" action="{{ route('focal.phase-schedules.destroy', $schedule->id) }}"
              onsubmit="return confirm('Delete this schedule?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn-sm btn-delete">🗑️ Delete</button>
            </form>
          </div>
        </div>
        @endforeach
      @else
        <div class="empty">No schedules added yet.</div>
      @endif
    </div>

  </div>
</div>

<!-- Edit Modal -->
<div class="modal-overlay" id="editModal">
  <div class="modal">
    <h3>✏️ Edit Schedule</h3>
    <form id="editForm" method="POST">
      @csrf @method('PUT')
      <div class="form-group">
        <label>Phase</label>
        <select name="phase_id" id="edit_phase" required>
          @foreach($phases as $phase)
          <option value="{{ $phase->id }}">{{ $phase->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label>Group</label>
        <select name="group_id" id="edit_group" required>
          @foreach($groups as $group)
          <option value="{{ $group->id }}">{{ $group->name ?? 'Group '.$group->id }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Start Date</label>
          <input type="datetime-local" name="start_date" id="edit_start" required>
        </div>
        <div class="form-group">
          <label>End Date</label>
          <input type="datetime-local" name="end_date" id="edit_end" required>
        </div>
      </div>
      <div class="form-group">
        <label>Venue</label>
        <input type="text" name="venue" id="edit_venue">
      </div>
      <div class="form-group">
        <label>Notes</label>
        <textarea name="notes" id="edit_notes" rows="2"></textarea>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn-cancel" onclick="closeEdit()">Cancel</button>
        <button type="submit" class="btn-save">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<script>
function openEdit(id, phaseId, groupId, start, end, venue, notes) {
    document.getElementById('editForm').action = '/focal/phase-schedules/' + id;
    document.getElementById('edit_phase').value  = phaseId;
    document.getElementById('edit_group').value  = groupId;
    document.getElementById('edit_start').value  = start.replace(' ','T').substring(0,16);
    document.getElementById('edit_end').value    = end.replace(' ','T').substring(0,16);
    document.getElementById('edit_venue').value  = venue;
    document.getElementById('edit_notes').value  = notes;
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
    document.getElementById('overlay')?.classList.toggle('show');
}
</script>
</body>
</html>