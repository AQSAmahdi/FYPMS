<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Detail - FYPMS</title>
<link rel="stylesheet" href="/css/transitions.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
@keyframes borderFlow{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
.main{margin-left:260px;padding:28px 32px}
.topbar{background:#fff;border-radius:12px;padding:16px 22px;margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9}
.topbar h2{color:#0C447C;font-size:18px;font-weight:700}
.topbar p{color:#8096b0;font-size:12px;margin-top:2px}
.back-btn{display:flex;align-items:center;gap:6px;color:#0C447C;text-decoration:none;font-size:13px;font-weight:600;background:#e8f0f9;padding:8px 16px;border-radius:8px;transition:background .2s}
.back-btn:hover{background:#d0e4f7}
.card-wrap{padding:2px;border-radius:14px;background:linear-gradient(270deg,#0C447C,#1a6bbf,#38bdf8,#0C447C);background-size:300% 300%;animation:borderFlow 2.5s ease infinite;margin-bottom:20px}
.card{background:#fff;border-radius:12px;padding:28px}
.profile-header{display:flex;align-items:center;gap:20px;margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid #e8f0f9}
.avatar{width:72px;height:72px;border-radius:50%;background:#0C447C;color:#fff;display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:700;flex-shrink:0}
.profile-header h3{font-size:20px;font-weight:700;color:#0C447C}
.profile-header p{font-size:13px;color:#8096b0;margin-top:4px}
.badge{display:inline-block;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:600;margin-top:6px;background:#dcfce7;color:#166534}
.info-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:16px}
.info-item label{font-size:11px;font-weight:700;color:#8096b0;text-transform:uppercase;letter-spacing:.8px;display:block;margin-bottom:5px}
.info-item p{font-size:14px;color:#1e3a5f;font-weight:500}
.section-title{font-size:13px;font-weight:700;color:#0C447C;text-transform:uppercase;letter-spacing:.8px;margin-bottom:14px}
.no-profile{background:#fef3c7;border:1px solid #fde68a;border-radius:10px;padding:16px;color:#92400e;font-size:13px}
@media(max-width:768px){.main{margin-left:0;padding:14px;padding-top:56px}.info-grid{grid-template-columns:1fr}}
/* Responsive */
@media(max-width:768px){
    .main{margin-left:0;padding:15px}
    .form-grid{grid-template-columns:1fr}
    .topbar{flex-direction:column;align-items:flex-start;gap:10px}
    .btn-row{flex-direction:column}
    .btn{width:100%;text-align:center}
}
</style>
</head>
<body>
@include('focal.partials.sidebar')

<div class="main">
  <div class="topbar">
    <div>
      <h2>Student Detail</h2>
      <p>{{ $student->name }}</p>
    </div>
    <div style="display:flex;gap:10px;align-items:center">
      <a href="/focal/students/{{ $student->id }}/edit" class="back-btn" style="background:#0C447C;color:#fff">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        Edit
      </a>
      <form method="POST" action="/focal/students/{{ $student->id }}/delete" class="ajax-form" id="delete-form">
        @csrf
        <button type="button" onclick="confirmDelete()" class="back-btn" style="background:#b91c1c;color:#fff;border:none;cursor:pointer">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
          Delete
        </button>
      </form>
      <a href="/focal/students" class="back-btn">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
        Back
      </a>
    </div>
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    </a>
  </div>

  <div class="card-wrap">
    <div class="card">
      <div class="profile-header">
        <div class="avatar">{{ strtoupper(substr($student->name,0,1)) }}</div>
        <div>
          <h3>{{ $student->name }}</h3>
          <p>{{ $student->email }}</p>
          <span class="badge">Student</span>
        </div>
      </div>

      @if($student->studentProfile)
      <div class="section-title">Academic Information</div>
      <div class="info-grid">
        <div class="info-item">
          <label>Roll Number</label>
          <p>{{ $student->studentProfile->student_id ?? '-' }}</p>
        </div>
        <div class="info-item">
          <label>Department</label>
          <p>{{ $student->studentProfile->department->name ?? '-' }}</p>
        </div>
        <div class="info-item">
          <label>Session</label>
          <p>{{ $student->studentProfile->session->name ?? '-' }}</p>
        </div>
        <div class="info-item">
          <label>Shift</label>
          <p>{{ $student->studentProfile->shift ?? '-' }}</p>
        </div>
        <div class="info-item">
          <label>Current Semester</label>
          <p>Sem {{ $student->studentProfile->current_semester ?? '-' }}</p>
        </div>
        <div class="info-item">
          <label>Registered On</label>
          <p>{{ $student->created_at->format('d M Y') }}</p>
        </div>
      </div>
      @else
      <div class="no-profile">No academic profile found for this student.</div>
      @endif
    </div>
  </div>
</div>

<script>
function toggleSidebar(){
  document.getElementById('sidebar').classList.toggle('open');
  document.getElementById('hamburger').classList.toggle('open');
  document.getElementById('overlay').classList.toggle('show');
}
</script>

<script src="/js/ajax.js"></script>
<script>
function confirmDelete(){
  if(confirm('Are you sure you want to delete this student? This cannot be undone.')){
    var form = document.getElementById('delete-form');
    ajaxForm(form, function(){ setTimeout(()=>window.location.href='/focal/students', 800); });
    form.dispatchEvent(new Event('submit'));
  }
}
</script>
</body>
</html>
