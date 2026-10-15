<!DOCTYPE html><html lang="en" style="background:linear-gradient(90deg,#0C447C 260px,#f0f4f8 260px)">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="csrf-token" content="{{ csrf_token() }}"><title>FYPMS - Login</title>
<style>*{margin:0;padding:0;box-sizing:border-box}
html,body{height:100%}body{  font-family:'Segoe UI',sans-serif;
  background:#f0f4f8;  min-height:100vh;
  min-height:100dvh;
  display:flex;
  align-items:center;
  justify-content:center;
  padding:16px;}

@media (prefers-reduced-motion: reduce){
  *,*::before,*::after{animation-duration:.01ms!important;animation-iteration-count:1!important;transition-duration:.01ms!important}
}

@keyframes borderFlow{
  0%{background-position:0% 50%}
  50%{background-position:100% 50%}
  100%{background-position:0% 50%}
}
@keyframes borderFlowWhite{
  0%{background-position:0% 50%}
  50%{background-position:100% 50%}
  100%{background-position:0% 50%}
}
@keyframes cardEnter{
  from{opacity:0;transform:translateY(14px)}
  to{opacity:1;transform:translateY(0)}
}
@keyframes fadeIn{
  from{opacity:0}
  to{opacity:1}
}

.card{
  display:flex;
  width:100%;
  max-width:900px;
  min-height:540px;
  border-radius:20px;
  overflow:hidden;
  box-shadow:0 20px 60px rgba(12,68,124,.18);
  animation:cardEnter .5s cubic-bezier(.22,.61,.36,1) both;
}

.left{
  background:#0C447C;
  flex:0 0 340px;
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:center;
  padding:40px 32px;
  position:relative;
  overflow:hidden;
}
.left::before{content:'';position:absolute;width:280px;height:280px;border-radius:50%;background:rgba(255,255,255,.04);top:-60px;left:-60px}
.left::after{content:'';position:absolute;width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,.04);bottom:-40px;right:-40px}

.logo-wrap{
  position:relative;
  padding:3px;
  border-radius:50%;
  background:linear-gradient(270deg,rgba(255,255,255,.2),rgba(255,255,255,.9),rgba(255,255,255,.2));
  background-size:300% 300%;
  animation:borderFlowWhite 4s ease-in-out infinite;
  will-change:background-position;
  transform:translateZ(0);
  margin-bottom:22px;
  z-index:1;
  animation-delay:.15s;
}
.logo-wrap img{width:110px;height:110px;border-radius:50%;display:block;object-fit:cover;background:#fff}
.left h2{color:#fff;font-size:clamp(15px,1.6vw,17px);font-weight:700;text-align:center;z-index:1;line-height:1.4;animation:fadeIn .5s ease .2s both}
.left p{color:rgba(255,255,255,.55);font-size:11px;font-weight:600;letter-spacing:2.5px;text-transform:uppercase;margin-top:5px;text-align:center;z-index:1;animation:fadeIn .5s ease .25s both}
.left hr{width:40px;border:none;border-top:1px solid rgba(255,255,255,.2);margin:16px 0;z-index:1}
.fyp-badge{
  position:relative;
  padding:2px;
  border-radius:20px;
  background:linear-gradient(270deg,rgba(255,255,255,.15),rgba(255,255,255,.7),rgba(255,255,255,.15));
  background-size:300% 300%;
  animation:borderFlowWhite 4s ease-in-out infinite;
  will-change:background-position;
  transform:translateZ(0);
  z-index:1;
}
.fyp-badge span{display:block;background:#0C447C;border-radius:18px;padding:7px 20px;color:rgba(255,255,255,.8);font-size:12px;font-weight:600;letter-spacing:.5px}

.right{flex:1;background:#fff;padding:44px 42px;display:flex;flex-direction:column;justify-content:center}
.right h1{color:#0C447C;font-size:clamp(22px,2.4vw,26px);font-weight:700;margin-bottom:6px}
.right .subtitle{color:#8096b0;font-size:13.5px;margin-bottom:28px}

.role-wrap{
  position:relative;
  padding:2px;
  border-radius:10px;
  background:linear-gradient(270deg,#0C447C,#1a6bbf,#38bdf8,#0C447C);
  background-size:300% 300%;
  animation:borderFlow 4s ease-in-out infinite;
  will-change:background-position;
  transform:translateZ(0);
  margin-bottom:26px;
}
.role-btns{display:flex;background:#fff;border-radius:8px;overflow:hidden}
.role-btn{
  flex:1;
  padding:11px 8px;
  border:none;
  background:transparent;
  font-size:13.5px;
  font-weight:600;
  color:#8096b0;
  cursor:pointer;
  font-family:'Segoe UI',sans-serif;
  transition:background .25s ease, color .25s ease, transform .15s ease;
  letter-spacing:.2px;
  white-space:nowrap;
}
.role-btn.active{background:#0C447C;color:#fff;border-radius:6px}
.role-btn:hover:not(.active){background:#f0f4f8;color:#0C447C}
.role-btn:active{transform:scale(.97)}

.form-group{margin-bottom:18px}
.form-group label{display:block;font-size:12.5px;font-weight:600;color:#0C447C;margin-bottom:7px;letter-spacing:.2px}

.input-wrap{
  position:relative;
  padding:2px;
  border-radius:10px;
  background:#dbe4ee;
  transition:background .3s ease, box-shadow .3s ease;
}
.input-wrap:focus-within{
  background:linear-gradient(270deg,#0C447C,#1a6bbf,#38bdf8,#0C447C);
  background-size:300% 300%;
  animation:borderFlow 4s ease-in-out infinite;
  will-change:background-position;
  box-shadow:0 0 0 4px rgba(12,68,124,.08);
}
.input-wrap input{
  width:100%;
  padding:12px 16px;
  border:none;
  border-radius:8px;
  font-size:13.5px;
  color:#1a2b3c;
  background:#f7fafd;
  outline:none;
  font-family:'Segoe UI',sans-serif;
  transition:background .25s ease;
}
.input-wrap input:focus{background:#fff}
.input-wrap input::placeholder{color:#b0c4d8;font-size:13px}

.forgot{text-align:right;margin-top:8px}
.forgot a{font-size:12px;color:#8096b0;text-decoration:none;transition:color .2s ease}
.forgot a:hover{color:#0C447C}

.btn-wrap{
  position:relative;
  padding:2px;
  border-radius:10px;
  background:linear-gradient(270deg,#0C447C,#1a6bbf,#38bdf8,#0C447C);
  background-size:300% 300%;
  animation:borderFlow 4s ease-in-out infinite;
  will-change:background-position;
  transform:translateZ(0);
  margin-top:22px;
}
.btn-submit{
  width:100%;
  padding:14px;
  border:none;
  border-radius:8px;
  background:#0C447C;
  color:#fff;
  font-size:14.5px;
  font-weight:700;
  cursor:pointer;
  font-family:'Segoe UI',sans-serif;
  letter-spacing:.3px;
  transition:background .2s ease, transform .15s ease;
}
.btn-submit:hover{background:#185FA5}
.btn-submit:active{transform:scale(.985)}
.btn-submit:disabled{opacity:.75;cursor:not-allowed}

.footer-note{text-align:center;margin-top:22px;font-size:12px;color:#b0c4d8}

.alert-error{
  background:#fef2f2;
  border:1px solid #fecaca;
  border-radius:9px;
  padding:11px 16px;
  color:#b91c1c;
  font-size:13px;
  margin-bottom:18px;
  animation:fadeIn .25s ease both;
}

@media(max-width:768px){
    .left{flex:0 0 80px;padding:20px 12px}
    .left h2,.left p,.left hr,.fyp-badge{display:none}
    .logo-wrap img{width:54px;height:54px}
    .logo-wrap{margin-bottom:0}
    .right{padding:32px 28px}
}
@media(max-width:520px){
    body{padding:10px;align-items:flex-start;padding-top:24px}
    .card{flex-direction:column;border-radius:16px;min-height:auto}
    .left{display:none}
    .right{padding:32px 22px}
    .right .subtitle{font-size:13px;margin-bottom:22px}
    .role-btn{font-size:12.5px;padding:10px 4px}
    .btn-submit{font-size:14px;padding:13px}
}
@media(max-width:360px){
    .role-btn{font-size:11.5px;padding:9px 2px}
}
</style>
<link rel="stylesheet" href="/css/transitions.css">
<meta name="view-transition" content="same-origin">
</head>
<body>
<div class="card">

  <div class="left">
    <div class="logo-wrap">
      <img src="/images/bgnu.jfif" alt="BGNU Logo">
    </div>
    <h2>Baba Guru Nanak University</h2>
    <p>Nankana Sahib</p>
    <hr>
    <div class="fyp-badge">
      <span>FYP Management System</span>
    </div>
  </div>

  <div class="right">
    <h1>Welcome back</h1>
    <p class="subtitle">Select your role and sign in to continue</p>

    <div class="role-wrap">
      <div class="role-btns">
        <button class="role-btn active" onclick="setRole('focal_person', this)" type="button">Focal Person</button>
        <button class="role-btn" onclick="setRole('faculty', this)" type="button">Faculty</button>
        <button class="role-btn" onclick="setRole('student', this)" type="button">Student</button>
      </div>
    </div>

    <form id="login-form">
      <input type="hidden" id="role_input" value="focal_person">

      <div class="form-group">
        <label for="email">Email address</label>
        <div class="input-wrap">
          <input type="email" id="email" placeholder="Enter your university email" required autofocus>
        </div>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <div class="input-wrap">
          <input type="password" id="password" placeholder="Enter your password" required>
        </div>
        <div class="forgot"><a href="{{ route('password.request.form') }}">Forgot password?</a></div>
      </div>

      <div id="error-box" style="display:none" class="alert-error"></div>

      <div class="btn-wrap">
        <button type="submit" class="btn-submit" id="submit_btn">Sign in as Focal Person</button>
      </div>
    </form>

    <p class="footer-note">For account issues, contact your Focal Person &bull; BGNU &copy; 2026</p>
  </div>
</div>

<script>
function setRole(role, btn) {
    document.getElementById('role_input').value = role;
    document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const labels = {focal_person:'Focal Person', faculty:'Faculty', student:'Student'};
    document.getElementById('submit_btn').textContent = 'Sign in as ' + labels[role];
}

document.getElementById('login-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const email    = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const role     = document.getElementById('role_input').value;
    const errorBox = document.getElementById('error-box');
    const btn      = document.getElementById('submit_btn');

    btn.textContent = 'Signing in...';
    btn.disabled    = true;
    errorBox.style.display = 'none';

    try {
        const res  = await fetch('/api/login', {
            method:  'POST',
            headers: {'Content-Type':'application/json','Accept':'application/json'},
            body:    JSON.stringify({email, password, role})
        });
        const data = await res.json();

        if (data.success) {
            localStorage.setItem('api_token', data.token);
            localStorage.setItem('user_role',  data.role);
            localStorage.setItem('user_name',  data.user.name);

            const csrfToken = document.querySelector('meta[name=csrf-token]').content;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/login';
            form.innerHTML = `
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="email" value="${email}">
                <input type="hidden" name="password" value="${password}">
            `;
            document.body.appendChild(form);
            form.submit();

        } else {
            errorBox.textContent   = data.message || 'Login failed';
            errorBox.style.display = 'block';
            const labels = {focal_person:'Focal Person', faculty:'Faculty', student:'Student'};
            btn.textContent        = 'Sign in as ' + labels[document.getElementById('role_input').value];
            btn.disabled           = false;
        }
    } catch(err) {
        errorBox.textContent   = 'Server error. Please try again.';
        errorBox.style.display = 'block';
        btn.disabled           = false;
    }
});
</script>
</body>
</html>
