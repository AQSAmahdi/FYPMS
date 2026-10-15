<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}"><title>Password Reset Requests - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
.main{margin-left:260px;padding:28px 32px;min-height:100vh}
.topbar{background:linear-gradient(135deg,#378ADD,#0C447C);border-radius:12px;padding:22px 28px;margin-bottom:22px;box-shadow:0 4px 16px rgba(12,68,124,.18)}
.topbar h2{color:#fff;font-size:18px;font-weight:700}
.topbar p{color:rgba(255,255,255,.85);font-size:12px;margin-top:2px}
.alert{padding:12px 16px;border-radius:10px;font-size:13px;margin-bottom:18px}
.alert-success{background:#e8f5e9;color:#2e7d32}
.alert-error{background:#fce4e4;color:#c62828}

.stats-row{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px}
.stat-card{background:#fff;border-radius:10px;padding:16px 20px;border:1px solid #e2e8f0;display:flex;align-items:center;gap:14px}
.stat-icon{width:38px;height:38px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.stat-icon.total{background:#eaf2fb;color:#0C447C}
.stat-icon.pending{background:#fdf3e0;color:#a67c00}
.stat-icon.resolved{background:#e6f4ea;color:#2e7d32}
.stat-card .number{font-size:22px;font-weight:700;color:#1e3a5f;line-height:1.1}
.stat-card p{font-size:11px;color:#8096b0;margin-top:2px;font-weight:600;text-transform:uppercase;letter-spacing:.3px}

.section-title{font-size:13px;font-weight:700;color:#1e3a5f;margin-bottom:10px;text-transform:uppercase;letter-spacing:.3px}

.tabs{display:flex;gap:6px;margin-bottom:16px}
.tab-btn{background:#fff;border:1px solid #e2e8f0;color:#5a6b80;padding:7px 16px;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer}
.tab-btn.active{background:#0C447C;color:#fff;border-color:#0C447C}

.card{background:#fff;border-radius:10px;padding:16px 20px;margin-bottom:10px;border:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:14px}
.user-name{font-size:14px;font-weight:700;color:#1e3a5f}
.user-meta{font-size:12px;color:#8096b0;margin-top:2px}
.badge{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:5px;font-size:11px;font-weight:600}
.badge-pending{background:#fdf3e0;color:#a67c00}
.badge-resolved{background:#e6f4ea;color:#2e7d32}
.resolved-pass{font-size:12px;color:#3a5a3f;margin-top:6px;display:flex;align-items:center;gap:8px;background:#f4faf5;border:1px solid #dcefe0;border-radius:6px;padding:6px 10px;width:fit-content}
.resolved-pass strong{color:#1e3a5f;font-family:'Courier New',monospace;letter-spacing:.5px}
.btn-icon{background:transparent;border:1px solid #e2e8f0;color:#5a6b80;width:30px;height:30px;border-radius:6px;cursor:pointer;display:flex;align-items:center;justify-content:center}
.btn-icon:hover{background:#f0f4f8}
.btn-icon.danger:hover{background:#fce4e4;border-color:#f3b8b8;color:#c62828}
.btn-icon.copied{background:#0C447C;border-color:#0C447C;color:#fff}
.btn-resolve{background:#0C447C;color:#fff;border:none;padding:8px 16px;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer}
.btn-resolve:hover{background:#185FA5}
.actions-row{display:flex;align-items:center;gap:10px}
.empty-state{text-align:center;color:#8096b0;font-size:13px;margin-top:20px;background:#fff;border-radius:10px;padding:40px;border:1px solid #e2e8f0}

.pagination-wrap{display:flex;align-items:center;justify-content:center;gap:10px;margin-top:20px}
.page-btn{background:#fff;border:1px solid #e2e8f0;color:#1e3a5f;padding:7px 16px;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;text-decoration:none}
.page-btn:hover{background:#f0f4f8}
.page-btn.disabled{color:#c0ccd8;cursor:default;pointer-events:none}
.page-info{font-size:12px;color:#8096b0;font-weight:600}

.modal-overlay{display:none;position:fixed;inset:0;background:rgba(12,20,30,.45);z-index:999;align-items:center;justify-content:center}
.modal-overlay.active{display:flex}
.modal-box{background:#fff;border-radius:12px;padding:26px 24px;width:360px;max-width:90%;box-shadow:0 10px 40px rgba(12,68,124,.25);text-align:center}
.modal-icon{width:42px;height:42px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px}
.modal-icon.info{background:#eaf2fb;color:#0C447C}
.modal-icon.danger{background:#fce4e4;color:#c62828}
.modal-title{font-size:15px;font-weight:700;color:#1e3a5f;margin-bottom:6px}
.modal-text{font-size:12.5px;color:#6b7f97;line-height:1.5;margin-bottom:16px}
.modal-input{width:100%;padding:9px 12px;border-radius:7px;border:1px solid #e2e8f0;font-size:13px;outline:none;margin-bottom:6px;text-align:left}
.modal-input:focus{border-color:#0C447C}
.modal-error{color:#c62828;font-size:11px;text-align:left;min-height:14px;margin-bottom:10px}
.modal-actions{display:flex;gap:10px;justify-content:center;margin-top:8px}
.btn-modal-cancel{background:#f0f4f8;color:#4a5b70;border:none;padding:9px 18px;border-radius:7px;font-size:12.5px;font-weight:600;cursor:pointer}
.btn-modal-cancel:hover{background:#e2e9f2}
.btn-modal-confirm{background:#0C447C;color:#fff;border:none;padding:9px 18px;border-radius:7px;font-size:12.5px;font-weight:600;cursor:pointer}
.btn-modal-confirm:hover{background:#185FA5}
.btn-modal-danger{background:#c62828;color:#fff;border:none;padding:9px 18px;border-radius:7px;font-size:12.5px;font-weight:600;cursor:pointer}
.btn-modal-danger:hover{background:#a81f1f}
</style>
</head>
<body>
@include('focal.partials.sidebar')
<div class="main">
    <div class="topbar">
        <h2>Password Reset Requests</h2>
        <p>Students and faculty who have requested a password reset</p>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon total"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg></div>
            <div><div class="number">{{ $totalCount }}</div><p>Total</p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon pending"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></div>
            <div><div class="number">{{ $pendingCount }}</div><p>Pending</p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon resolved"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg></div>
            <div><div class="number">{{ $resolvedCount }}</div><p>Resolved</p></div>
        </div>
    </div>

    <div class="section-title">All Requests</div>
    <div class="tabs">
        <button type="button" class="tab-btn active" data-filter="all" onclick="filterRequests('all', this)">All</button>
        <button type="button" class="tab-btn" data-filter="pending" onclick="filterRequests('pending', this)">Pending</button>
        <button type="button" class="tab-btn" data-filter="resolved" onclick="filterRequests('resolved', this)">Resolved</button>
    </div>

    @forelse($requests as $req)
    <div class="card" data-status="{{ $req->status }}">
        <div>
            <div class="user-name">{{ $req->user->name ?? 'Unknown User' }}</div>
            <div class="user-meta">{{ $req->user->email ?? '-' }} &bull; {{ ucfirst($req->user->role ?? '-') }} &bull; Requested {{ $req->created_at->diffForHumans() }}</div>
            @if($req->status === 'resolved')
                <div class="resolved-pass">
                    <span>New password: <strong id="pass-{{ $req->id }}">{{ $req->new_password_plain }}</strong> &bull; by {{ $req->resolvedBy->name ?? '-' }}, {{ $req->resolved_at?->diffForHumans() }}</span>
                    <button type="button" class="btn-icon" title="Copy password" onclick="copyPassword('{{ $req->id }}', this)">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                    </button>
                </div>
            @endif
        </div>
        <div class="actions-row">
            @if($req->status === 'pending')
                <span class="badge badge-pending">Pending</span>
                <form method="POST" action="{{ route('focal.password-resets.resolve', $req->id) }}" class="resolve-form" onsubmit="return false;">
                    @csrf
                    <input type="hidden" name="custom_password" class="custom-password-field">
                    <button type="button" class="btn-resolve" onclick="openConfirmModal(this)">Generate New Password</button>
                </form>
            @else
                <span class="badge badge-resolved">Resolved</span>
            @endif
            <form method="POST" action="{{ route('focal.password-resets.delete', $req->id) }}" class="delete-form" onsubmit="return false;">
                @csrf
                @method('DELETE')
                <button type="button" class="btn-icon danger" title="Delete request" onclick="openDeleteModal(this)">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6"/></svg>
                </button>
            </form>
        </div>
    </div>
    @empty
        <div class="empty-state">No password reset requests yet.</div>
    @endforelse

    @if ($requests->hasPages())
    <div class="pagination-wrap">
        @if ($requests->onFirstPage())
            <span class="page-btn disabled">Prev</span>
        @else
            <a href="{{ $requests->previousPageUrl() }}" class="page-btn">Prev</a>
        @endif
        <span class="page-info">Page {{ $requests->currentPage() }} of {{ $requests->lastPage() }}</span>
        @if ($requests->hasMorePages())
            <a href="{{ $requests->nextPageUrl() }}" class="page-btn">Next</a>
        @else
            <span class="page-btn disabled">Next</span>
        @endif
    </div>
    @endif
</div>

<div class="modal-overlay" id="confirmModal">
    <div class="modal-box">
        <div class="modal-icon info"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></div>
        <div class="modal-title">Generate New Password</div>
        <div class="modal-text">Type a password for this user, or leave blank to auto-generate one.</div>
        <input type="text" id="customPasswordInput" class="modal-input" placeholder="Leave blank to auto-generate (min 6 chars)">
        <div class="modal-error" id="modalError"></div>
        <div class="modal-actions">
            <button type="button" class="btn-modal-cancel" onclick="closeConfirmModal()">Cancel</button>
            <button type="button" class="btn-modal-confirm" onclick="confirmGeneratePassword()">Generate</button>
        </div>
    </div>
</div>

<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <div class="modal-icon danger"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6"/></svg></div>
        <div class="modal-title">Delete This Request?</div>
        <div class="modal-text">This will permanently remove the request record. This cannot be undone.</div>
        <div class="modal-actions">
            <button type="button" class="btn-modal-cancel" onclick="closeDeleteModal()">Cancel</button>
            <button type="button" class="btn-modal-danger" onclick="confirmDelete()">Delete</button>
        </div>
    </div>
</div>

<script>
let pendingForm = null;
let pendingDeleteForm = null;

function openConfirmModal(btn) {
    pendingForm = btn.closest('form');
    document.getElementById('customPasswordInput').value = '';
    document.getElementById('modalError').innerText = '';
    document.getElementById('confirmModal').classList.add('active');
}
function closeConfirmModal() {
    pendingForm = null;
    document.getElementById('confirmModal').classList.remove('active');
}
function confirmGeneratePassword() {
    const input = document.getElementById('customPasswordInput');
    const val = input.value.trim();
    if (val !== '' && val.length < 6) {
        document.getElementById('modalError').innerText = 'Password must be at least 6 characters.';
        return;
    }
    if (pendingForm) {
        pendingForm.querySelector('.custom-password-field').value = val;
        pendingForm.submit();
    }
    closeConfirmModal();
}

function copyPassword(id, btn) {
    const text = document.getElementById('pass-' + id).innerText;
    navigator.clipboard.writeText(text).then(() => {
        const original = btn.innerHTML;
        btn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>';
        btn.classList.add('copied');
        setTimeout(() => {
            btn.innerHTML = original;
            btn.classList.remove('copied');
        }, 1500);
    });
}

function openDeleteModal(btn) {
    pendingDeleteForm = btn.closest('form');
    document.getElementById('deleteModal').classList.add('active');
}
function closeDeleteModal() {
    pendingDeleteForm = null;
    document.getElementById('deleteModal').classList.remove('active');
}
function confirmDelete() {
    if (pendingDeleteForm) pendingDeleteForm.submit();
    closeDeleteModal();
}

function filterRequests(status, btn) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.card[data-status]').forEach(card => {
        if (status === 'all' || card.dataset.status === status) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
</body>
</html>
