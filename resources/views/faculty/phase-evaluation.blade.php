<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Phase Evaluation - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
.main{margin-left:260px;padding:28px 32px}
.topbar{background:linear-gradient(135deg,#378ADD,#0C447C);border-radius:12px;padding:18px 26px;margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 1px 12px rgba(12,68,124,.15)}
.topbar h2{color:#fff;font-size:18px;font-weight:700}
.topbar p{color:rgba(255,255,255,.8);font-size:12px;margin-top:2px}
.badge-role{background:rgba(255,255,255,.2);color:#fff;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600;border:1px solid rgba(255,255,255,.4)}
.card{background:#fff;border-radius:12px;padding:22px;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9;margin-bottom:20px}
.card h3{color:#0C447C;font-size:15px;font-weight:700;margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid #e8f0f9}
.alert-success{background:#dcfce7;color:#166534;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;border:1px solid #bbf7d0}
.alert-error{background:#fef2f2;color:#b91c1c;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px}
.filter-tabs{display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap}
.filter-tab{background:#fff;border:1px solid #d5e3f0;color:#5a7a9a;border-radius:20px;padding:8px 18px;font-size:12.5px;font-weight:600;cursor:pointer;transition:all .15s;font-family:'Segoe UI',sans-serif}
.filter-tab:hover{border-color:#0C447C;color:#0C447C}
.filter-tab.active{background:#0C447C;color:#fff;border-color:#0C447C}
.group-section{margin-bottom:28px}
.group-section-title{display:flex;align-items:center;gap:10px;color:#0C447C;font-size:14px;font-weight:700;margin-bottom:14px;padding-bottom:8px;border-bottom:2px solid #e8f0f9}
.group-section-title::before{content:'';width:4px;height:16px;background:#0C447C;border-radius:3px;display:inline-block}
.group-section-title{cursor:pointer;user-select:none}
.chevron{font-size:12px;color:#0C447C}
.group-count-badge{margin-left:auto;font-size:11px;font-weight:600;color:#8096b0;background:#f4f8fd;padding:3px 10px;border-radius:12px}
.group-body{overflow:hidden}
.search-box-wrap{margin-bottom:16px}
.group-search{width:100%;max-width:340px;padding:10px 16px;border:1px solid #d5e3f0;border-radius:10px;font-size:13px;outline:none;font-family:'Segoe UI',sans-serif;background:#fff;transition:border .15s}
.group-search:focus{border-color:#0C447C}
.phase-card{border:1.5px solid #e8f0f9;border-radius:10px;padding:18px;margin-bottom:16px;transition:all .2s;background:#fff;box-shadow:0 1px 8px rgba(12,68,124,.05)}
.phase-card:hover{border-color:#0C447C;box-shadow:0 2px 12px rgba(12,68,124,.08)}
.phase-card[data-status="open"]{border-left:4px solid #16a34a}
.phase-card[data-status="upcoming"]{border-left:4px solid #ca8a04}
.phase-card[data-status="closed"]{border-left:4px solid #94a3b8}
.phase-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px}
.phase-name{font-size:16px;font-weight:700;color:#0C447C}
.phase-meta{font-size:12px;color:#8096b0;margin-top:3px}
.badge-open{background:#dcfce7;color:#166534;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
.badge-closed{background:#fef2f2;color:#b91c1c;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
.badge-upcoming{background:#fef9c3;color:#854d0e;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
.badge-submitted{background:#e0e7ff;color:#3730a3;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
.date-row{display:flex;gap:12px;margin-bottom:14px;flex-wrap:wrap}
.date-box{background:#f4f8fd;border-radius:8px;padding:8px 14px;flex:1;min-width:130px}
.date-box .lbl{font-size:10px;color:#8096b0;text-transform:uppercase;letter-spacing:.5px;margin-bottom:3px}
.date-box .val{font-size:13px;font-weight:600;color:#0C447C}
.students-table{width:100%;border-collapse:collapse;font-size:13px;margin-top:10px}
.students-table th{background:#f4f8fd;color:#0C447C;font-weight:700;padding:9px 12px;text-align:left;border-bottom:2px solid #e8f0f9}
.students-table td{padding:9px 12px;border-bottom:1px solid #f0f4f8;vertical-align:middle}
.students-table tr:hover td{background:#f8fafd}
.mark-input{width:80px;padding:6px 10px;border:1.5px solid #e8f0f9;border-radius:6px;font-size:13px;text-align:center;outline:none;transition:border .2s}
.mark-input:focus{border-color:#0C447C}
.mark-input:disabled{background:#f4f8fd;color:#8096b0}
.btn-submit{background:#0C447C;color:#fff;border:none;padding:9px 22px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;margin-top:14px}
.btn-submit:hover{background:#185FA5}
.btn-submit:disabled{background:#8096b0;cursor:not-allowed}
.total-badge{display:inline-block;background:#e8f0f9;color:#0C447C;padding:2px 10px;border-radius:20px;font-size:11px;font-weight:600;margin-left:8px}
.empty{text-align:center;padding:30px;color:#8096b0;font-size:14px}
.no-schedule{background:#fef9c3;border:1px solid #fde68a;border-radius:8px;padding:12px 16px;font-size:13px;color:#854d0e;margin-top:10px}
@media(max-width:768px){
  .main{margin-left:0;padding:16px}
  .topbar{flex-direction:column;align-items:flex-start;gap:8px}
  .date-row{flex-direction:column}
  .mark-input{width:70px}
}
</style>
</head>
<body>
@include('faculty.partials.sidebar')

<div class="main">
  <div class="topbar">
    <div>
      <h2>Phase Evaluation</h2>
      <p>Submit marks for your assigned students</p>
    </div>
    <span class="badge-role">Faculty</span>
  </div>

  @if(session('success'))
    <div class="alert-success">✓ {{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="alert-error">{{ $errors->first() }}</div>
  @endif

  @if($schedules->count() > 0)

    @php
      $today = now();
      $schedulesSorted = $schedules->map(function($schedule) use ($today) {
          $scheduledDate = \Carbon\Carbon::parse($schedule->schedule_date)->startOfDay();
          $windowEnd     = $scheduledDate->copy()->addDays(2)->endOfDay();

          if ($schedule->status === 'completed') {
              $computedStatus = 'closed';
          } elseif ($today->lt($scheduledDate)) {
              $computedStatus = 'upcoming';
          } elseif ($today->gt($windowEnd)) {
              $computedStatus = 'closed';
          } else {
              $computedStatus = 'open';
          }
          $schedule->computed_status = $computedStatus;
          return $schedule;
      })->sortBy(function($s){
          return ['open' => 0, 'upcoming' => 1, 'closed' => 2][$s->computed_status] ?? 3;
      });

      $openCount     = $schedulesSorted->where('computed_status','open')->count();
      $upcomingCount = $schedulesSorted->where('computed_status','upcoming')->count();
      $closedCount   = $schedulesSorted->where('computed_status','closed')->count();

      $groupedSchedules = $schedulesSorted->groupBy(function($s){
          return $s->group->group_code ?? ('Group #'.$s->group_id);
      });
    @endphp

    <div class="search-box-wrap">
      <input type="text" id="groupSearch" class="group-search" placeholder="🔍 Search by group..." oninput="searchGroups(this.value)" spellcheck="false" autocomplete="off">
    </div>

    <div class="filter-tabs">
      <button class="filter-tab active" data-filter="all" onclick="filterPhases('all',this)">All ({{ $schedulesSorted->count() }})</button>
      <button class="filter-tab" data-filter="open" onclick="filterPhases('open',this)">🟢 Action Needed ({{ $openCount }})</button>
      <button class="filter-tab" data-filter="upcoming" onclick="filterPhases('upcoming',this)">🟡 Not Started ({{ $upcomingCount }})</button>
      <button class="filter-tab" data-filter="closed" onclick="filterPhases('closed',this)">✅ Completed ({{ $closedCount }})</button>
    </div>

    @foreach($groupedSchedules as $groupCode => $groupSchedules)
    @php $groupHasOpen = $groupSchedules->contains('computed_status','open'); @endphp
    <div class="group-section" data-group-code="{{ strtolower($groupCode) }}">
      <div class="group-section-title" onclick="toggleGroup(this)">
        <span class="chevron">{{ $groupHasOpen ? '▾' : '▸' }}</span>
        {{ $groupCode }}
        <span class="group-count-badge">{{ $groupSchedules->count() }} {{ $groupSchedules->count()==1 ? 'phase' : 'phases' }}</span>
      </div>
      <div class="group-body" style="display:{{ $groupHasOpen ? 'block' : 'none' }}">

      @foreach($groupSchedules as $schedule)
      @php $status = $schedule->computed_status; @endphp

      <div class="phase-card" data-status="{{ $status }}">
      <div class="phase-header">
        <div>
          <div class="phase-name">
            {{ $schedule->phase->name ?? 'N/A' }}
            <span class="total-badge">Total: {{ $schedule->phase->total_marks ?? '—' }} marks</span>
          </div>
          <div class="phase-meta">
            Group: {{ $schedule->group->group_code ?? 'Group '.$schedule->group_id }}
            @if($schedule->group->project_title)
              &bull; {{ $schedule->group->project_title }}
            @endif
            @if($schedule->venue) &bull; 📍 {{ $schedule->venue }} @endif
          </div>
        </div>
        <span class="badge-{{ $status }}">
          @if($status === 'open') 🟢 Action Needed
          @elseif($status === 'upcoming') 🟡 Not Started Yet
          @else ✅ Completed
          @endif
        </span>
      </div>

      <div class="date-row">
        <div class="date-box">
          <div class="lbl">Scheduled</div>
          <div class="val">{{ \Carbon\Carbon::parse($schedule->schedule_date)->format('d M Y') }} {{ $schedule->schedule_time ?? '' }}</div>
        </div>
        @if($schedule->notes)
        <div class="date-box" style="flex:2">
          <div class="lbl">Notes</div>
          <div class="val" style="font-weight:400;color:#5a7a9a">{{ $schedule->notes }}</div>
        </div>
        @endif
      </div>

      @if($status === 'open')
        <form method="POST" action="{{ route('faculty.phase-evaluation.store') }}">
          @csrf
          <input type="hidden" name="phase_id"    value="{{ $schedule->phase_id}}">
          <input type="hidden" name="group_id"    value="{{ $schedule->group_id}}">
          <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">

          <table class="students-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Student Name</th>
                <th>Roll Number</th>
                <th>Marks <span style="font-weight:400;color:#8096b0">(out of {{ $schedule->phase->total_marks ?? '—' }})</span></th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse($schedule->group->members ?? [] as $i => $student)
              @php
                $existingMark = $marks->where('student_id', $student->user_id)
                                      ->where('phase_id', $schedule->phase_id)
                                      ->first();
              @endphp
              <tr>
                <td>{{ $i+1 }}</td>
                <td style="font-weight:600;color:#1e3a5f">{{ $student->user->name ?? "N/A" }}</td>
                <td style="color:#8096b0">{{ $student->user->studentProfile->student_id ?? "—" }}</td>
                <td>
                  <input type="hidden" name="students[{{ $i }}][student_id]" value="{{ $student->user_id }}">
                  <input type="number"
                    class="mark-input"
                    name="students[{{ $i }}][marks]"
                    min="0"
                    max="{{ $schedule->phase->total_marks ?? 100 }}"
                    value="{{ $existingMark->marks_obtained ?? '' }}"
                    placeholder="0"
                    {{ $existingMark ? 'disabled' : '' }}>
                </td>
                <td>
                  @if($existingMark)
                    <span class="badge-submitted">✓ Submitted ({{ $existingMark->obtained_marks }})</span>
                  @else
                    <span style="font-size:12px;color:#8096b0">Pending</span>
                  @endif
                </td>
              </tr>
              @empty
                <tr><td colspan="5" class="empty">No students in this group.</td></tr>
              @endforelse
            </tbody>
          </table>

          @if(($schedule->group->members ?? collect())->count() > 0)
            @php $allSubmitted = true;
              foreach(($schedule->group->members ?? []) as $st) {
                if(!$marks->where('student_id',$st->user_id)->where('phase_id',$schedule->phase_id)->first())
                  $allSubmitted = false;
              }
            @endphp
            @if(!$allSubmitted)
              <button type="submit" class="btn-submit">📤 Submit Marks</button>
            @else
              <button type="button" class="btn-submit" disabled>✓ All Marks Submitted</button>
            @endif
          @endif
        </form>

      @elseif($status === 'upcoming')
        <div class="no-schedule">⏳ Evaluation not started yet. Opens on {{ \Carbon\Carbon::parse($schedule->schedule_date)->format('d M Y') }} {{ $schedule->schedule_time ?? '' }}</div>
      @else
        <div class="no-schedule" style="background:#f4f8fd;border-color:#d5e3f0;color:#5a7a9a">
          ✅ Evaluation completed on {{ \Carbon\Carbon::parse($schedule->schedule_date)->format('d M Y') }}
        </div>
      @endif

      </div>
      @endforeach
      </div>
    </div>
    @endforeach
  @else
    <div class="card">
      <div class="empty">📭 No phase schedules assigned to you yet.</div>
    </div>
  @endif
</div>

<script>
function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('overlay')?.classList.toggle('show');
}

function filterPhases(status, btn){
    document.querySelectorAll('.filter-tab').forEach(function(t){ t.classList.remove('active'); });
    btn.classList.add('active');

    document.querySelectorAll('.group-section').forEach(function(section){
        var anyVisible = false;
        section.querySelectorAll('.phase-card').forEach(function(card){
            var show = (status === 'all' || card.getAttribute('data-status') === status);
            card.style.display = show ? '' : 'none';
            if(show) anyVisible = true;
        });
        section.style.display = anyVisible ? '' : 'none';

        // Every tab switch resets every group back to collapsed,
        // regardless of what was open before. User must click to reopen.
        var body = section.querySelector('.group-body');
        if(body) body.style.display = 'none';
        var chevron = section.querySelector('.chevron');
        if(chevron) chevron.textContent = '▸';
    });
}

function toggleGroup(titleEl){
    var body = titleEl.nextElementSibling;
    var chevron = titleEl.querySelector('.chevron');
    if(body.style.display === 'none'){
        body.style.display = 'block';
        if(chevron) chevron.textContent = '▾';
    } else {
        body.style.display = 'none';
        if(chevron) chevron.textContent = '▸';
    }
}

function searchGroups(query){
    query = query.toLowerCase().trim();
    document.querySelectorAll('.group-section').forEach(function(section){
        var code = section.getAttribute('data-group-code') || '';
        var match = (query === '' || code.indexOf(query) !== -1);
        section.style.display = match ? '' : 'none';
        if(match && query !== ''){
            var body = section.querySelector('.group-body');
            if(body) body.style.display = 'block';
            var chevron = section.querySelector('.chevron');
            if(chevron) chevron.textContent = '▾';
        }
    });
}
</script>
</body>
</html>
