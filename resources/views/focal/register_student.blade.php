<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register Student</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
@keyframes borderFlow{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
.form-card-wrap{padding:2px;border-radius:14px;background:linear-gradient(270deg,#0C447C,#1a6bbf,#38bdf8,#0C447C);background-size:300% 300%;animation:borderFlow 2.5s ease infinite}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh;}
.sidebar{width:250px;background:#0C447C;min-height:100vh;display:flex;flex-direction:column;position:fixed;left:0;top:0}
.sidebar-logo{padding:25px 20px;border-bottom:1px solid rgba(255,255,255,.1);text-align:center}
.sidebar-logo img{width:60px;height:60px;border-radius:50%;border:2px solid rgba(255,255,255,.3);object-fit:cover}
.sidebar-logo p{color:#fff;font-size:13px;font-weight:600;margin-top:8px}
.sidebar-logo span{color:rgba(255,255,255,.5);font-size:11px}
.sidebar-menu{padding:20px 0;flex:1}
.menu-label{color:rgba(255,255,255,.4);font-size:10px;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;padding:0 20px;margin:15px 0 8px}
.menu-item{display:flex;align-items:center;gap:12px;padding:11px 20px;color:rgba(255,255,255,.75);text-decoration:none;font-size:13px;transition:all .2s;border-left:3px solid transparent}
.menu-item:hover{background:rgba(255,255,255,.08);color:#fff;border-left-color:rgba(255,255,255,.3)}
.menu-item.active{background:rgba(255,255,255,.12);color:#fff;border-left-color:#fff}
.menu-item .icon{width:18px;text-align:center;font-size:15px}
.sidebar-footer{padding:15px 20px;border-top:1px solid rgba(255,255,255,.1)}
.logout-btn{display:flex;align-items:center;gap:10px;color:rgba(255,255,255,.6);text-decoration:none;font-size:13px;cursor:pointer;background:none;border:none;width:100%;padding:8px 0}
.logout-btn:hover{color:#fff}
.main{margin-left:260px;flex:1;padding:30px}
.topbar{background:#0C447C;border-radius:14px;padding:20px 26px;margin-bottom:25px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 4px 14px rgba(12,68,124,.18)}
.topbar h2{color:#fff;font-size:20px;font-weight:700}
.topbar p{color:rgba(255,255,255,.85);font-size:13px;margin-top:2px}
.badge-role{background:rgba(255,255,255,.18);color:#fff;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600}
.form-card{background:#fff;border-radius:12px;padding:30px;box-shadow:0 2px 15px rgba(12,68,124,.06)}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px}
.form-group{display:flex;flex-direction:column;gap:7px}
.form-group.full{grid-column:1/-1}
.form-group label{font-size:12px;font-weight:600;color:#0C447C}
.form-group input,.form-group select{padding:11px 14px;border:1.5px solid #c7d8ec;border-radius:9px;font-size:14px;color:#1a2b3c;background:#f7fafd;outline:none;font-family:'Segoe UI',sans-serif;transition:border-color .3s}
.form-group input:focus,.form-group select:focus{border-color:#0C447C;box-shadow:0 0 0 3px rgba(12,68,124,.08);background:#fff}
.form-group input::placeholder{color:#a8bdd0;font-size:13px}
.form-group select{cursor:pointer}
.btn-row{display:flex;gap:12px;margin-top:25px;justify-content:center}
.btn{padding:12px 28px;border:none;border-radius:9px;font-size:14px;font-weight:600;cursor:pointer;font-family:'Segoe UI',sans-serif;transition:all .2s}
.btn-primary{background:#0C447C;color:#fff}
.btn-primary:hover{background:#185FA5}
.btn-secondary{background:#f0f4f8;color:#0C447C;border:1.5px solid #c7d8ec}
.btn-secondary:hover{background:#e2eaf4}
.alert-success{background:#f0fdf4;border:1px solid #bbf7d0;border-radius:9px;padding:12px 16px;color:#15803d;font-size:13px;margin-bottom:20px}
.alert-error{background:#fef2f2;border:1px solid #fecaca;border-radius:9px;padding:12px 16px;color:#b91c1c;font-size:13px;margin-bottom:20px}
.section-title{color:#0C447C;font-size:14px;font-weight:600;margin-bottom:18px;padding-bottom:10px;border-bottom:1.5px solid #e8f0f9}
/* Responsive */
@media(max-width:768px){
    .main{margin-left:0;padding:15px}
    .form-grid{grid-template-columns:1fr}
    .topbar{flex-direction:column;align-items:flex-start;gap:10px}
    .btn-row{flex-direction:column}
    .btn{width:100%;text-align:center}
}
</style>
<link rel="stylesheet" href="/css/transitions.css">
</head>
<body>
@include('focal.partials.sidebar')

<div class="main">
  <div class="topbar">
    <div>
      <h2>Register Student</h2>
      <p>Add a new student to the system</p>
    </div>
    <span class="badge-role">Focal Person</span>
  </div>
<div class="form-card-wrap">
  <div class="form-card">
    @if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
    <div class="alert-error">{{ $errors->first() }}</div>
    @endif
    <div class="section-title">Student Information</div>
    <form method="POST" action="/focal/students/store">
      @csrf
      <div class="form-grid">
        <div class="form-group">
          <label>Full Name</label>
          <input type="text" name="name" placeholder="e.g. Ahmad Ali" value="{{ old('name') }}" required>
        </div>
        <div class="form-group">
          <label>Roll Number</label>
          <input type="text" name="student_id" placeholder="e.g. BSCS-F21-001" value="{{ old('student_id') }}" required>
        </div>
        <div class="form-group">
          <label>Registration Number</label>
          <input type="text" name="reg_no" placeholder="e.g. 2021-CS-001" value="{{ old('reg_no') }}">
        </div>
        <div class="form-group">
          <label>Email Address</label>
          <input type="email" name="email" placeholder="e.g. student@bgnu.edu.pk" value="{{ old('email') }}" required>
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" placeholder="Set login password" required>
        </div>
        <div class="form-group">
          <label>Contact Number</label>
          <input type="text" name="contact" placeholder="e.g. 03001234567" value="{{ old('contact') }}">
        </div>
        <div class="form-group">
          <label>Department</label>
          <select name="department_id" required>
            <option value="">-- Select Department --</option>
            @foreach($departments as $dept)
            <option value="{{ $dept->id }}" {{ old('department_id')==$dept->id?'selected':'' }}>{{ $dept->name }} ({{ $dept->code }})</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label>Academic Session</label>
          <select name="session_id" required>
            <option value="">-- Select Session --</option>
            @foreach($sessions as $session)
            <option value="{{ $session->id }}" {{ old('session_id')==$session->id?'selected':'' }}>{{ $session->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label>Shift</label>
          <select name="shift" required>
            <option value="">-- Select Shift --</option>
            <option value="Morning" {{ old('shift')=='Morning'?'selected':'' }}>Morning</option>
            <option value="Evening" {{ old('shift')=='Evening'?'selected':'' }}>Evening</option>
          </select>
        </div>
        <div class="form-group">
          <label>Current Semester</label>
          <select name="current_semester" required>
            <option value="">-- Select Semester --</option>
            @for($i=1;$i<=8;$i++)
            <option value="{{ $i }}" {{ old('current_semester')==$i?'selected':'' }}>Semester {{ $i }}</option>
            @endfor
          </select>
        </div>
      </div>
      <div class="btn-row">
        <button type="submit" class="btn btn-primary">Register Student</button>
        <a href="/focal/dashboard" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
  </div>
</div>
</body>
</html>
