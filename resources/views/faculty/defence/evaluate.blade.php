<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Evaluate Defence - FYPMS</title>
<link rel="stylesheet" href="/css/transitions.css?v=3">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
.main{margin-left:260px;padding:28px 32px}
.topbar{background:#fff;border-radius:12px;padding:16px 22px;margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9}
.topbar h2{color:#0C447C;font-size:18px;font-weight:700}
.card{background:#fff;border-radius:12px;padding:22px;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9;margin-bottom:20px}
.card-title{color:#0C447C;font-size:15px;font-weight:700;margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid #f0f4f8}
.info-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin-bottom:16px}
.info-box{background:#f8fafc;border-radius:8px;padding:12px 16px}
.info-label{font-size:11px;color:#8096b0;font-weight:600;text-transform:uppercase;margin-bottom:4px}
.info-value{font-size:14px;color:#1e3a5f;font-weight:600}
.criteria-row{display:flex;justify-content:space-between;align-items:center;padding:14px 0;border-bottom:1px solid #f0f4f8}
.criteria-row:last-child{border-bottom:none}
.criteria-name{font-size:14px;color:#1e3a5f;font-weight:600}
.criteria-max{font-size:12px;color:#8096b0;margin-top:3px}
.marks-input{width:100px;padding:8px 12px;border:1.5px solid #e8f0f9;border-radius:8px;font-size:14px;color:#1e3a5f;outline:none;text-align:center}
.marks-input:focus{border-color:#0C447C}
.btn-submit{background:#0C447C;color:#fff;border:none;padding:12px 28px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;width:100%}
.btn-submit:hover{background:#185FA5}
.btn-back{background:#f0f4f8;color:#0C447C;border:none;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;text-decoration:none}
.member-tag{display:inline-block;background:#e8f0f9;color:#0C447C;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;margin:3px}
.alert-success{background:#dcfce7;color:#166534;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px}
@media(max-width:768px){.main{margin-left:0;padding:16px;padding-top:62px}}
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
    <div><h2>Evaluate Defence</h2><p>{{ $schedule->group->group_code ?? '-' }} — {{ $schedule->defence_date }}</p></div>
    <a href="/faculty/dashboard" class="btn-back">← Back</a>
  </div>

  @if(session('success'))<div class="alert-success">{{ session('success') }}</div>@endif

  <div class="card">
    <div class="card-title">Group Information</div>
    <div class="info-grid">
      <div class="info-box"><div class="info-label">Group Code</div><div class="info-value">{{ $schedule->group->group_code ?? '-' }}</div></div>
      <div class="info-box"><div class="info-label">Department</div><div class="info-value">{{ $schedule->group->department->name ?? '-' }}</div></div>
      <div class="info-box"><div class="info-label">Date</div><div class="info-value">{{ $schedule->defence_date }}</div></div>
      <div class="info-box"><div class="info-label">Time</div><div class="info-value">{{ $schedule->defence_time }}</div></div>
      <div class="info-box"><div class="info-label">Room</div><div class="info-value">{{ $schedule->room ?? 'Not set' }}</div></div>
      <div class="info-box"><div class="info-label">Panel</div><div class="info-value">{{ $schedule->panel->name ?? 'Not set' }}</div></div>
    </div>
    <div>
      @foreach($schedule->group->members as $m)
        <span class="member-tag">{{ $m->user->name ?? '-' }}</span>
      @endforeach
    </div>
  </div>

  <div class="card">
    <div class="card-title">Evaluation Criteria</div>
    @if($schedule->criteria->count() > 0)
    <form method="POST" action="/faculty/defence/{{ $schedule->id }}/submit">
      @csrf
      @foreach($schedule->criteria as $c)
      <div class="criteria-row">
        <div>
          <div class="criteria-name">{{ $c->criteria_name }}</div>
          <div class="criteria-max">Max Marks: {{ $c->max_marks }}</div>
        </div>
        <input type="number" name="marks[{{ $c->id }}]" class="marks-input"
          min="0" max="{{ $c->max_marks }}"
          value="{{ $existingEvals[$c->id] ?? '' }}"
          placeholder="0" required>
      </div>
      @endforeach
      <div style="margin-top:20px">
        <button type="submit" class="btn-submit">Submit Evaluation</button>
      </div>
    </form>
    @else
    <p style="color:#8096b0;font-size:13px;padding:20px 0">No evaluation criteria defined for this defence.</p>
    @endif
  </div>
</div>

</body>
</html>
