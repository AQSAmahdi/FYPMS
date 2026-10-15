<!DOCTYPE html>
<html lang="en" style="background:linear-gradient(90deg,#0C447C 260px,#f0f4f8 260px)">
<head>
<style>html,body{background:#f0f4f8}</style>

<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Phase Documents - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
@keyframes borderFlow{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
.main{margin-left:260px;padding:0px 32px 28px;min-height:100vh}
.topbar{margin-top:-30px}
.topbar{background:#fff;border-radius:12px;padding:18px 26px;margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9}
.topbar h2{color:#0C447C;font-size:18px;font-weight:700}
.topbar p{color:#8096b0;font-size:12px;margin-top:3px}
.badge-student{background:#e8f0f9;color:#0C447C;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600}
.alert{padding:12px 18px;border-radius:9px;margin-bottom:18px;font-size:13px;font-weight:500}
.alert-success{background:#dcfce7;color:#166534;border:1px solid #bbf7d0}
.alert-error{background:#fef2f2;color:#b91c1c;border:1px solid #fecaca}
.section-card{background:#fff;border-radius:16px;padding:0;margin-bottom:20px;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9;overflow:hidden;transition:box-shadow .25s ease,transform .25s ease}
.section-card:hover{box-shadow:0 6px 22px rgba(12,68,124,.12);transform:translateY(-2px)}
.section-card h3{color:#0C447C;font-size:14px;font-weight:700;margin-bottom:16px}
.section-card .card-body{padding:24px}
.card-header{background:linear-gradient(135deg,#378ADD,#0C447C);padding:16px 24px;display:flex;align-items:center;gap:10px}
.card-title{color:#fff;font-size:14px;font-weight:700}
.icon-badge{width:32px;height:32px;border-radius:9px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.icon-badge svg{width:16px;height:16px;stroke:#fff;fill:none}
.phase-row{border:1.5px solid #e8f0f9;border-radius:10px;padding:18px 20px;margin-bottom:14px;transition:border-color .2s}
.phase-row:hover{border-color:#0C447C}
.phase-row-header{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-bottom:10px}
.phase-title{font-size:14px;font-weight:700;color:#0C447C}
.phase-meta{font-size:12px;color:#8096b0;margin-top:3px}
.badge-uploaded{background:#dcfce7;color:#166534;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600}
.badge-pending-upload{background:#fef9c3;color:#854d0e;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600}
.badge-no-upload{background:#f1f5f9;color:#64748b;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600}
.upload-instr{background:#f4f8fd;border-radius:8px;padding:10px 14px;font-size:12.5px;color:#5a7a9a;margin-bottom:12px}
.upload-form{display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-top:10px}
.file-input-wrap{padding:2px;border-radius:9px;background:linear-gradient(270deg,#0C447C,#1a6bbf,#38bdf8,#0C447C);background-size:300% 300%;animation:borderFlow 2.5s ease infinite;flex:1;min-width:200px}
.file-input-wrap input[type=file]{width:100%;padding:8px 12px;border:none;border-radius:7px;font-size:12.5px;background:#f7fafd;outline:none;font-family:'Segoe UI',sans-serif}
.btn-upload{padding:9px 20px;background:#0C447C;color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:'Segoe UI',sans-serif;transition:background .2s;white-space:nowrap}
.btn-upload:hover{background:#185FA5}
.uploaded-info{display:flex;align-items:center;gap:10px;background:#f4f8fd;border-radius:8px;padding:10px 14px;margin-top:10px;flex-wrap:wrap}
.uploaded-filename{font-size:13px;font-weight:600;color:#1a2b3c;flex:1}
.uploaded-meta{font-size:11.5px;color:#8096b0}
.btn-reupload{padding:6px 14px;background:#fef9c3;color:#854d0e;border:1px solid #fde68a;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;font-family:'Segoe UI',sans-serif}
.empty-state{text-align:center;padding:40px;color:#9aabbf;font-size:13px}
@media(max-width:768px){
  .main{margin-left:0;padding:16px;padding-top:62px}
  .topbar{flex-direction:column;align-items:flex-start;gap:8px}
}
</style>
<link rel="stylesheet" href="/css/transitions.css?v=3">
<meta name="view-transition" content="same-origin">
</head>
<body>

<button class="hamburger" id="hamburger" onclick="toggleSidebar()">
  <span></span><span></span><span></span>
</button>
<div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

@include('student.partials.sidebar')
  <div class="sidebar-footer">
    <a href="/logout" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="logout-btn">
      <svg viewBox="0 0 24 24" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      Logout
    </a>
    <form id="logout-form" action="/logout" method="POST" style="display:none">{{ csrf_field() }}</form>
  </div>
</div>

<div class="main">
  <div class="topbar">
    <div><h2>Phase Documents</h2><p>Upload required documents for your FYP phases</p></div>
    <span class="badge-student">Student</span>
  </div>

  @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
  @if($errors->any())<div class="alert alert-error">{{ $errors->first() }}</div>@endif

  @if(!$myGroup)
    <div class="section-card">
      <h3>No Group Found</h3>
      <p style="color:#8096b0;font-size:13px">You need to be in a group first. <a href="/student/my-group" style="color:#0C447C">Join or create a group</a>.</p>
    </div>
  @elseif($schedules->isEmpty())
    <div class="section-card">
      <div class="empty-state">
        <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        No phase schedules assigned to your group yet.
      </div>
    </div>
  @else
    <div class="section-card">
      <div class="card-header">
        <span class="icon-badge">
          <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        </span>
        <span class="card-title">Your Phase Schedules</span>
      </div>
      <div class="card-body">
      @foreach($schedules as $schedule)
        @php
          $phase = $schedule->phase;
          $doc   = $documents->get($phase->id ?? 0);
        @endphp
        <div class="phase-row">
          <div class="phase-row-header">
            <div>
              <div class="phase-title">{{ $phase->name ?? 'Unknown Phase' }}</div>
              <div class="phase-meta">Scheduled: {{ \Carbon\Carbon::parse($schedule->schedule_date)->format('d M Y') }} &nbsp;|&nbsp; Room: {{ $schedule->room ?? '—' }}</div>
            </div>
            @if(!($phase->requires_upload ?? false))
              <span class="badge-no-upload">No Upload Required</span>
            @elseif($doc)
              <span class="badge-uploaded">Uploaded</span>
            @else
              <span class="badge-pending-upload">Upload Pending</span>
            @endif
          </div>

          @if($phase->requires_upload ?? false)
            @if($phase->upload_instructions)
              <div class="upload-instr">{{ $phase->upload_instructions }}</div>
            @endif

            @if($doc)
              <div class="uploaded-info">
                <svg viewBox="0 0 24 24" style="width:18px;height:18px;stroke:#166534;fill:none;stroke-width:2;flex-shrink:0"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <div class="uploaded-filename">{{ $doc->original_filename }}</div>
                <div class="uploaded-meta">{{ number_format($doc->file_size / 1024, 1) }} KB &nbsp;|&nbsp; Uploaded {{ $doc->updated_at->diffForHumans() }}</div>
              </div>
              <div style="margin-top:10px">
                <form method="POST" action="/student/phase-documents/{{ $schedule->id }}" enctype="multipart/form-data" style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
                  @csrf
                  <div class="file-input-wrap"><input type="file" name="document" accept=".pdf,.doc,.docx,.zip,.rar" required></div>
                  <button type="submit" class="btn-reupload">Re-upload</button>
                </form>
              </div>
            @else
              <form method="POST" action="/student/phase-documents/{{ $schedule->id }}" enctype="multipart/form-data" class="upload-form">
                @csrf
                <div class="file-input-wrap"><input type="file" name="document" accept=".pdf,.doc,.docx,.zip,.rar" required></div>
                <button type="submit" class="btn-upload">Upload</button>
              </form>
            @endif
          @endif
        </div>
      @endforeach
      </div>
    </div>
  @endif
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('overlay').classList.toggle('show');
}
</script>
</body>
</html>
