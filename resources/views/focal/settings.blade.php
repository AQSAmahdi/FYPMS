<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Settings - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
@keyframes borderFlow{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
.hamburger{display:none;position:fixed;top:12px;left:12px;z-index:1100;background:#0C447C;border:none;border-radius:8px;padding:8px 9px;cursor:pointer;flex-direction:column;gap:5px}
.hamburger span{display:block;width:18px;height:2px;background:#fff;border-radius:2px;transition:all .3s}
.hamburger.open span:nth-child(1){transform:translateY(7px) rotate(45deg)}
.hamburger.open span:nth-child(2){opacity:0}
.hamburger.open span:nth-child(3){transform:translateY(-7px) rotate(-45deg)}
.overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998}
.overlay.show{display:block}
.main{margin-left:260px;padding:28px 36px 40px;min-height:100vh}
.topbar{background:#0C447C;border-radius:14px;padding:20px 26px;margin-bottom:24px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 1px 12px rgba(12,68,124,.15)}
.topbar h2{color:#fff;font-size:19px;font-weight:700}
.topbar p{color:#c9dbee;font-size:12px;margin-top:3px}
.badge-role{background:rgba(255,255,255,.15);color:#fff;padding:6px 16px;border-radius:20px;font-size:12px;font-weight:600}
.settings-card-title{color:#0C447C;font-size:14.5px;font-weight:700;margin:-24px -24px 20px;padding:14px 24px;border-radius:14px 14px 0 0;background:#eaf1fa;border-bottom:1px solid #d7e6f7;border-left:4px solid #0C447C;display:flex;align-items:center;gap:10px}
.settings-card-icon{width:26px;height:26px;border-radius:7px;background:#0C447C;display:inline-flex;align-items:center;justify-content:center;flex-shrink:0}
.settings-card-icon svg{width:14px;height:14px}
.settings-select{width:100%;padding:10px 14px;border:1.5px solid #e8f0f9;border-radius:8px;font-size:14px;color:#1e3a5f;outline:none;transition:border-color .15s;font-family:'Segoe UI',sans-serif;background:#fff;accent-color:#0C447C}
.settings-select option{background:#fff;color:#1e3a5f;padding:8px}
.settings-select option:checked,.settings-select option:hover{background:#0C447C;color:#fff}
.cdrop{position:relative;width:100%;font-family:'Segoe UI',sans-serif}
.cdrop-selected{width:100%;padding:10px 14px;border:1.5px solid #e8f0f9;border-radius:8px;font-size:14px;color:#1e3a5f;background:#fff;cursor:pointer;display:flex;justify-content:space-between;align-items:center;transition:border-color .15s}
.cdrop-selected:hover,.cdrop.open .cdrop-selected{border-color:#0C447C}
.cdrop-selected svg{width:12px;height:12px;transition:transform .15s}
.cdrop.open .cdrop-selected svg{transform:rotate(180deg)}
.cdrop-list{position:absolute;top:calc(100% + 6px);left:0;right:0;background:#fff;border:1px solid #e8f0f9;border-radius:8px;box-shadow:0 6px 20px rgba(12,68,124,.15);z-index:20;overflow:hidden;display:none}
.cdrop.open .cdrop-list{display:block}
.cdrop-item{padding:10px 14px;font-size:14px;color:#1e3a5f;cursor:pointer;transition:background .12s,color .12s}
.cdrop-item:hover{background:#0C447C;color:#fff}
.cdrop-item.selected{background:#eaf1fa;color:#0C447C;font-weight:600}
.cdrop-item.selected:hover{background:#0C447C;color:#fff}
.settings-select:focus{border-color:#0C447C}
.settings-btn{margin-top:18px;background:#0C447C;color:#fff;border:none;border-radius:8px;padding:11px 26px;font-size:13px;font-weight:600;cursor:pointer;transition:background .2s}
.settings-btn:hover{background:#185FA5}
.settings-label{font-size:11px;font-weight:700;color:#0C447C;text-transform:uppercase;letter-spacing:.6px;display:block;margin-bottom:8px}
.cards{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:20px}
.card-wrap{padding:2px;border-radius:14px;background:linear-gradient(270deg,#0C447C,#1a6bbf,#38bdf8,#0C447C);background-size:300% 300%;animation:borderFlow 2.5s ease infinite}
.card{background:#fff;border-radius:12px;padding:22px 18px;text-align:center}
.card-icon{width:40px;height:40px;background:#e8f0f9;border-radius:10px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px}
.card-icon svg{width:19px;height:19px;stroke:#0C447C;fill:none}
.card h3{color:#8096b0;font-size:11px;font-weight:500;margin-bottom:7px;text-transform:uppercase;letter-spacing:.5px}
.card .number{font-size:34px;font-weight:700;color:#0C447C;line-height:1}
.card p{color:#b0c4d8;font-size:11px;margin-top:4px}
.quick-card{background:#fff;border-radius:12px;padding:20px;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9}
.section-title{color:#0C447C;font-size:14px;font-weight:700;margin-bottom:14px}
.actions-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}
.action-wrap{padding:2px;border-radius:12px;background:linear-gradient(270deg,#0C447C,#1a6bbf,#38bdf8,#0C447C);background-size:300% 300%;animation:borderFlow 2.5s ease infinite}
.action-btn{display:flex;align-items:center;gap:12px;padding:16px 18px;background:#fff;border-radius:10px;text-decoration:none;transition:background .2s}
.action-btn:hover{background:#f4f8fd}
.action-icon{width:38px;height:38px;background:#e8f0f9;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:background .2s}
.action-icon svg{width:18px;height:18px;stroke:#0C447C;fill:none}
.action-btn:hover .action-icon{background:#0C447C}
.action-btn:hover .action-icon svg{stroke:#fff}
.action-btn div p{font-size:13px;font-weight:600;color:#0C447C}
.action-btn div span{font-size:11px;color:#8096b0;margin-top:2px;display:block}
@media(max-width:900px){.cards{grid-template-columns:repeat(2,1fr)}}
@media(max-width:768px){
  .hamburger{display:flex}
  .main{margin-left:0;padding:14px;padding-top:56px}
  .cards{grid-template-columns:1fr}
  .actions-grid{grid-template-columns:1fr}
  .topbar{flex-direction:column;align-items:flex-start;gap:8px}
}
/* Responsive */
@media(max-width:768px){
    .main{margin-left:0;padding:15px}
    .form-grid{grid-template-columns:1fr}
    .topbar{flex-direction:column;align-items:flex-start;gap:10px}
    .btn-row{flex-direction:column}
    .btn{width:100%;text-align:center}
}
</style></head>
<body>
@include('focal.partials.sidebar')

<div class="main">
  <div class="topbar">
    <div><h2>Settings</h2><p>Manage system settings</p></div>
    <span class="badge-role">Focal Person</span>
  </div>

  @if(session("success"))
  <div style="background:#dcfce7;border:1px solid #86efac;border-radius:10px;padding:12px 16px;margin-bottom:16px;color:#166534;font-size:13px;display:flex;align-items:center;gap:8px"><svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="#166534" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>{{ session("success") }}</div>
  @endif

  <div style="background:#fff;border-radius:14px;padding:24px;border:1px solid #e8f0f9;margin-bottom:20px;box-shadow:0 1px 14px rgba(12,68,124,.08)">
    <div class="settings-card-title"><span class="settings-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>Group Formation Settings</div>
    <form method="POST" action="{{ route('focal.settings.update') }}">
      @csrf
        <div style="max-width:320px">
          <label class="settings-label">Group Formation</label>
          @php($gfOpen = isset($settings["group_formation_open"]) && $settings["group_formation_open"]=="1")
          <select name="group_formation_open" id="gfNativeSelect" style="display:none">
            <option value="1" {{ $gfOpen ? "selected" : "" }}>Open</option>
            <option value="0" {{ !$gfOpen ? "selected" : "" }}>Closed</option>
          </select>
          <div class="cdrop" id="gfDropdown">
            <div class="cdrop-selected" id="gfDropdownSelected">
              <span>{{ $gfOpen ? "Open" : "Closed" }}</span>
              <svg viewBox="0 0 24 24" fill="none" stroke="#0C447C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
            <div class="cdrop-list">
              <div class="cdrop-item {{ $gfOpen ? 'selected' : '' }}" data-value="1">Open</div>
              <div class="cdrop-item {{ !$gfOpen ? 'selected' : '' }}" data-value="0">Closed</div>
            </div>
          </div>
          <script>
          (function(){
            var dd = document.getElementById('gfDropdown');
            var sel = document.getElementById('gfDropdownSelected');
            var nativeSel = document.getElementById('gfNativeSelect');
            var items = dd.querySelectorAll('.cdrop-item');
            sel.addEventListener('click', function(e){
              e.stopPropagation();
              dd.classList.toggle('open');
            });
            items.forEach(function(item){
              item.addEventListener('click', function(){
                items.forEach(function(i){ i.classList.remove('selected'); });
                item.classList.add('selected');
                sel.querySelector('span').textContent = item.textContent;
                nativeSel.value = item.getAttribute('data-value');
                dd.classList.remove('open');
              });
            });
            document.addEventListener('click', function(){ dd.classList.remove('open'); });
          })();
          </script>
        </div>
      <button type="submit" class="settings-btn">Save Settings</button>
    </form>
  </div>
</div>
</body>
</html>