<!DOCTYPE html>
<html lang="en">
<head>
<style>html,body{background:#f0f4f8}</style>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'FYPMS')</title>
<link rel="stylesheet" href="/css/transitions.css?v=3">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
@yield('styles')
</style>
</head>
<body>
<button class="hamburger" id="hamburger" onclick="toggleSidebar()">
  <span></span><span></span><span></span>
</button>
<div class="overlay" id="overlay" onclick="toggleSidebar()"></div>
@include('faculty.partials.sidebar')
<div class="main">
@yield('content')
</div>
<script>
function openModal(id){ var el = document.getElementById('modal-'+id); if(el) el.style.display='flex'; }
function closeModal(id){ var el = document.getElementById('modal-'+id); if(el) el.style.display='none'; }
function toggleSidebar(){
  document.getElementById('sidebar').classList.toggle('open');
  document.getElementById('hamburger').classList.toggle('open');
  document.getElementById('overlay').classList.toggle('show');
}
if (!localStorage.getItem('api_token')) {
    window.location.href = '/logout-web';
}
async function apiLogout() {
    const token = localStorage.getItem('api_token');
    try {
        await fetch('/api/logout', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        });
    } catch(e) {}
    localStorage.clear();
    sessionStorage.clear();
    window.location.href = '/logout-web';
}
</script>
@yield('scripts')
</body>
</html>
