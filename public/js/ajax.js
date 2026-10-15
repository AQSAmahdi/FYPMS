// Global AJAX helper
function showToast(message, type='success'){
  let existing = document.getElementById('toast-container');
  if(!existing){
    existing = document.createElement('div');
    existing.id = 'toast-container';
    existing.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:10px';
    document.body.appendChild(existing);
  }
  const toast = document.createElement('div');
  toast.style.cssText = `
    padding:12px 20px;border-radius:10px;font-size:13px;font-weight:600;
    font-family:'Segoe UI',sans-serif;min-width:260px;max-width:360px;
    box-shadow:0 4px 20px rgba(0,0,0,.15);
    display:flex;align-items:center;gap:10px;
    transform:translateX(120%);transition:transform .35s cubic-bezier(.34,1.56,.64,1);
    ${type==='success'
      ? 'background:#dcfce7;color:#166534;border:1px solid #bbf7d0'
      : type==='error'
      ? 'background:#fef2f2;color:#b91c1c;border:1px solid #fecaca'
      : 'background:#fef3c7;color:#92400e;border:1px solid #fde68a'}
  `;
  toast.innerHTML = `
    <span style="font-size:16px">${type==='success'?'✓':type==='error'?'✕':'!'}</span>
    <span>${message}</span>
  `;
  existing.appendChild(toast);
  requestAnimationFrame(()=>{ toast.style.transform='translateX(0)'; });
  setTimeout(()=>{
    toast.style.transform='translateX(120%)';
    setTimeout(()=>toast.remove(), 400);
  }, 3500);
}

function ajaxForm(form, onSuccess){
  form.addEventListener('submit', async function(e){
    e.preventDefault();
    const btn = form.querySelector('button[type=submit]');
    const originalText = btn ? btn.innerHTML : '';
    if(btn){ btn.disabled=true; btn.innerHTML='<span style="opacity:.6">...</span>'; }

    try {
      const res = await fetch(form.action, {
        method: form.method || 'POST',
        body: new FormData(form),
        headers: {'X-Requested-With':'XMLHttpRequest'}
      });
      const data = await res.json();
      if(data.success){
        showToast(data.success, 'success');
        if(onSuccess) onSuccess(data);
      } else if(data.error){
        showToast(data.error, 'error');
      } else if(data.errors){
        const msgs = Object.values(data.errors).flat();
        msgs.forEach(m => showToast(m, 'error'));
      }
    } catch(err){
      showToast('Something went wrong. Please try again.', 'error');
    }
    if(btn){ btn.disabled=false; btn.innerHTML=originalText; }
  });
}

function apiLogout() {
    const token = localStorage.getItem('api_token');
    fetch('/api/logout', {
        method: 'POST',
        headers: {
            'Authorization': 'Bearer ' + token,
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
        }
    }).finally(() => {
        localStorage.removeItem('api_token');
        window.location.href = '/login';
    });
}
