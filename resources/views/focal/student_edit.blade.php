<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Student - FYPMS</title>
<link rel="stylesheet" href="/css/transitions.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
@keyframes borderFlow{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
.main{margin-left:260px;padding:28px 32px}
.topbar{background:#fff;border-radius:12px;padding:16px 22px;margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9}
.topbar h2{color:#0C447C;font-size:18px;font-weight:700}
.topbar p{color:#8096b0;font-size:12px;margin-top:2px}
.back-btn{display:flex;align-items:center;gap:6px;color:#0C447C;text-decoration:none;font-size:13px;font-weight:600;background:#e8f0f9;padding:8px 16px;border-radius:8px}
.card-wrap{padding:2px;border-radius:14px;background:linear-gradient(270deg,#0C447C,#1a6bbf,#38bdf8,#0C447C);background-size:300% 300%;animation:borderFlow 2.5s ease infinite}
.card{background:#fff;border-radius:12px;padding:28px}
.form-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:18px}
.form-group{display:flex;flex-direction:column;gap:6px}
.form-group.full{grid-column:1/-1}
.form-group label{font-size:12px;font-weight:700;color:#0C447C;text-transform:uppercase;letter-spacing:.6px}
.form-group input,.form-group select{padding:10px 14px;border:1.5px solid #e8f0f9;border-radius:8px;font-size:14px;font-family:'Segoe UI',sans-serif;color:#1e3a5f;outline:none;transition:border .2s}
.form-group input:focus,.form-group select:focus{border-color:#0C447C}
.btn-save{background:#0C447C;color:#fff;border:none;padding:12px 32px;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;margin-top:8px}
.btn-save:hover{background:#1a6bbf}
.error{color:#b91c1c;font-size:12px;margin-top:3px}
@media(max-width:768px){.main{margin-left:0;padding:14px;padding-top:56px}.form-grid{grid-template-columns:1fr}}
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
      <h2>Edit Student</h2>
      <p>{{ $student->name }}</p>
    </div>
    <a href="/focal/students" class="back-btn">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
      Back
    </a>
  </div>

  <div class="card-wrap">
    <div class="card">
      <form method="POST" action="/focal/students/{{ $student->id }}/update" class="ajax-form">
        @csrf
        <div class="form-grid">
          <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" value="{{ $student->name }}" required>
            @error('name')<span class="error">{{ $message }}</span>@enderror
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ $student->email }}" required>
            @error('email')<span class="error">{{ $message }}</span>@enderror
          </div>
          <div class="form-group">
            <label>Department</label>
            <select name="department_id" required>
              @foreach($departments as $d)
              <option value="{{ $d->id }}" {{ $student->studentProfile->department_id==$d->id?'selected':'' }}>{{ $d->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Session</label>
            <select name="session_id" required>
              @foreach($sessions as $s)
              <option value="{{ $s->id }}" {{ $student->studentProfile->session_id==$s->id?'selected':'' }}>{{ $s->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Shift</label>
            <select name="shift" required>
              <option value="Morning" {{ $student->studentProfile->shift=='Morning'?'selected':'' }}>Morning</option>
              <option value="Evening" {{ $student->studentProfile->shift=='Evening'?'selected':'' }}>Evening</option>
            </select>
          </div>
          <div class="form-group">
            <label>Current Semester</label>
            <select name="current_semester" required>
              @for($i=1;$i<=8;$i++)
              <option value="{{ $i }}" {{ $student->studentProfile->current_semester==$i?'selected':'' }}>Sem {{ $i }}</option>
              @endfor
            </select>
          </div>
          <div class="form-group full">
            <button type="submit" class="btn-save">Save Changes</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="/js/ajax.js"></script>
<script>
function toggleSidebar(){
  document.getElementById('sidebar').classList.toggle('open');
  document.getElementById('hamburger').classList.toggle('open');
  document.getElementById('overlay').classList.toggle('show');
}
document.addEventListener('DOMContentLoaded',function(){
  document.querySelectorAll('form.ajax-form').forEach(function(form){
    ajaxForm(form, function(data){
      if(data.reload) setTimeout(()=>location.reload(),800);
    });
  });
});
</script>
</body>
</html>
