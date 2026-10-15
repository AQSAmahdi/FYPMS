<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>FYP Groups - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
@keyframes borderFlow{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
.main{margin-left:260px;padding:28px 32px}
.topbar{background:#0C447C;border-radius:14px;padding:20px 26px;margin-bottom:24px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 4px 14px rgba(12,68,124,.18)}
.topbar h2{color:#fff;font-size:19px;font-weight:700}
.topbar p{color:rgba(255,255,255,.9)!important;font-size:12.5px;margin-top:2px}
.badge-role{background:#e8f0f9;color:#0C447C;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600}
.stats-bar{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px}
.stat-item{background:#fff;border-radius:12px;padding:16px 18px;text-align:center;border:1px solid #e8f0f9;box-shadow:0 1px 8px rgba(12,68,124,.06)}
.stat-num{font-size:28px;font-weight:700;color:#0C447C}
.stat-label{font-size:11px;color:#8096b0;margin-top:4px;text-transform:uppercase;letter-spacing:.5px}
.group-wrap{padding:2px;border-radius:14px;background:linear-gradient(270deg,#0C447C,#1a6bbf,#38bdf8,#0C447C);background-size:300% 300%;animation:borderFlow 2.5s ease infinite;margin-bottom:18px}
.group-card{background:#fff;border-radius:12px;padding:22px}
.group-top{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px}
.group-code{font-size:16px;font-weight:700;color:#0C447C}
.group-meta{font-size:12px;color:#8096b0;margin-top:3px}
.badge{padding:4px 12px;border-radius:20px;font-size:11px;font-weight:600}
.badge-open{background:#dbeafe;color:#1e40af}
.badge-locked{background:#fef3c7;color:#92400e}
.badge-submitted{background:#ede9fe;color:#5b21b6}
.badge-assigned{background:#dcfce7;color:#166534}
.badge-approved{background:#dcfce7;color:#166534}
.members-row{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:12px}
.member-chip{display:flex;align-items:center;gap:8px;background:#f4f8fd;border-radius:8px;padding:8px 12px;border:1px solid #e8f0f9}
.member-avatar{width:32px;height:32px;border-radius:50%;background:#0C447C;color:#fff;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0}
.member-chip-name{font-size:13px;font-weight:600;color:#1e3a5f}
.member-chip-roll{font-size:11px;color:#8096b0}
.divider{border:none;border-top:1px solid #e8f0f9;margin:14px 0}
.proposal-box{background:#f8fafc;border-radius:10px;padding:16px;border:1px solid #e8f0f9}
.proposal-title{font-size:14px;font-weight:700;color:#0C447C;margin-bottom:6px}
.proposal-desc{font-size:13px;color:#4a6a8a;line-height:1.5}
.review-form{display:grid;grid-template-columns:1fr auto;gap:10px;margin-top:12px;align-items:start}
.review-form textarea{width:100%;padding:10px 12px;border:1px solid #e8f0f9;border-radius:8px;font-size:13px;font-family:'Segoe UI',sans-serif;resize:vertical;min-height:70px;outline:none}
.btn-approve{background:#166534;color:#fff;border:none;padding:9px 18px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;width:100%}
.btn-reject{background:#b91c1c;color:#fff;border:none;padding:9px 18px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;width:100%}
.assign-form{display:flex;gap:10px;align-items:center;margin-top:8px}
.input-wrap{flex:1}
.input-wrap select{width:100%;padding:9px 12px;border:1px solid #e8f0f9;border-radius:8px;font-size:13px;font-family:'Segoe UI',sans-serif;outline:none;background:#fff;color:#1e3a5f}
.btn-assign{background:#0C447C;color:#fff;border:none;padding:9px 20px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;white-space:nowrap}
.btn-change{background:#e8f0f9;color:#0C447C;border:none;padding:6px 14px;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer}
.btn-delete{background:#dc2626;color:#fff;border:none;padding:7px 16px;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer}
.empty{background:#fff;border-radius:12px;padding:40px;text-align:center;color:#8096b0;font-size:14px;border:1px solid #e8f0f9}
.alert{padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px}
.alert-success{background:#dcfce7;color:#166534;border:1px solid #bbf7d0}
.supervisor-assigned-box{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px}
.supervisor-name{font-size:12.5px;color:#166534;font-weight:600}
@media(max-width:900px){.stats-bar{grid-template-columns:repeat(2,1fr)}}
@media(max-width:768px){
    .main{margin-left:0;padding:14px;padding-top:56px}
    .stats-bar{grid-template-columns:repeat(2,1fr)}
    .review-form{grid-template-columns:1fr}
    .topbar{flex-direction:column;align-items:flex-start;gap:8px}
    .assign-form{flex-direction:column}
    .btn-assign{width:100%}
    .supervisor-assigned-box{flex-direction:column;align-items:flex-start}
}
</style>
<link rel="stylesheet" href="/css/transitions.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container{width:100%!important}
.select2-container--default .select2-selection--single{height:38px;border:1px solid #e8f0f9;border-radius:8px;padding:4px 12px;font-size:13px;color:#1e3a5f}
.select2-container--default .select2-selection--single .select2-selection__arrow{top:6px}
.accordion-arrow{display:inline-block;margin-right:6px;font-size:12px;color:#0C447C;transition:transform .2s ease}
.accordion-arrow.open{transform:rotate(90deg)}
.group-top:hover{background:#f7fafd}
</style>
</head>
<body>
@include('focal.partials.sidebar')

<div class="main">
  <div class="topbar">
    <div>
      <h2>FYP Groups</h2>
      <p>Review proposals and assign supervisors</p>
    </div>
    <span class="badge-role">Focal Person</span>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <!-- Stats from API -->
  <div class="stats-bar">
    <div class="stat-item">
      <div class="stat-num" id="stat-total">-</div>
      <div class="stat-label">Total Groups</div>
    </div>
    <div class="stat-item">
      <div class="stat-num" id="stat-pending">-</div>
      <div class="stat-label">Pending Proposals</div>
    </div>
    <div class="stat-item">
      <div class="stat-num" id="stat-assigned">-</div>
      <div class="stat-label">Assigned</div>
    </div>
    <div class="stat-item">
      <div class="stat-num" id="stat-open">-</div>
      <div class="stat-label">Open</div>
    </div>
  </div>

  <!-- Groups list -->
  @if($groups->count() > 0)
    @foreach($groups as $group)
    <div class="group-wrap">
      <div class="group-card">

        <!-- Group Header -->
        <div class="group-top" onclick="toggleGroupBody({{ $group->id }})" style="cursor:pointer">
          <div>
            <div class="group-code"><span class="accordion-arrow" id="arrow-{{ $group->id }}">▸</span> {{ $group->group_code }}</div>
            <div class="group-meta">{{ $group->department->name ?? '-' }} &bull; Session {{ $group->session->name ?? '-' }} &bull; {{ $group->shift }}</div>
          </div>
          @if($group->status=='open') <span class="badge badge-open">Open</span>
          @elseif($group->status=='locked') <span class="badge badge-locked">Locked</span>
          @elseif($group->status=='proposal_submitted') <span class="badge badge-submitted">Proposal Submitted</span>
          @elseif($group->status=='supervisor_assigned') <span class="badge badge-assigned">Supervisor Assigned ✓</span>
          @elseif($group->status=='approved') <span class="badge badge-approved">Approved ✓</span>
          @endif
        </div>

        <div class="group-body" id="body-{{ $group->id }}" style="display:none">

        <!-- Members -->
        <div style="font-size:11px;font-weight:600;color:#8096b0;text-transform:uppercase;letter-spacing:.6px;margin-bottom:8px">Members</div>
        <div class="members-row">
          @foreach($group->members->filter(fn($m)=>$m->user) as $m)
          <div class="member-chip">
            <div class="member-avatar">{{ strtoupper(substr($m->user->name,0,1)) }}</div>
            <div>
              <div class="member-chip-name">{{ $m->user->name }}</div>
              <div class="member-chip-roll">{{ $m->user->studentProfile->student_id ?? '-' }}</div>
            </div>
          </div>
          @endforeach
        </div>

        <!-- Proposal -->
        @if($group->proposal)
        <hr class="divider">
        <div style="font-size:11px;font-weight:600;color:#8096b0;text-transform:uppercase;letter-spacing:.6px;margin-bottom:8px">Proposal</div>
        <div class="proposal-box">
          <div class="proposal-title">{{ $group->proposal->title }}</div>
          <div class="proposal-desc">{{ $group->proposal->description }}</div>
          @if($group->proposal->technology_stack)
          <div style="font-size:12px;color:#8096b0;margin-top:6px">Tech Stack: {{ $group->proposal->technology_stack }}</div>
          @endif
          @if($group->proposal->file_path)
          <div style="margin-top:8px"><a href="/student/proposal/document/{{ $group->proposal->id }}" style="font-size:12px;color:#0C447C;font-weight:600;text-decoration:underline">📎 Download Proposal Document</a></div>
          @endif
          <div style="margin-top:8px;font-size:12px">
            Status:
            @if($group->proposal->status=='pending') <span style="color:#92400e;font-weight:600">Pending Review</span>
            @elseif($group->proposal->status=='approved') <span style="color:#166534;font-weight:600">Approved ✓</span>
            @else <span style="color:#b91c1c;font-weight:600">Rejected</span>
            @endif
          </div>
          @if($group->proposal->focal_remarks)
          <div style="font-size:12px;color:#5a7a9a;margin-top:4px">Remarks: {{ $group->proposal->focal_remarks }}</div>
          @endif
          @if(isset($group->proposal) && $group->proposal->status=='pending')
          <form method="POST" action="/focal/proposals/{{ $group->proposal->id }}/review" style="margin-top:10px">
            @csrf
            <div class="review-form">
              <textarea name="remarks" placeholder="Remarks (optional)"></textarea>
              <div style="display:flex;flex-direction:column;gap:6px">
                <button type="submit" name="action" value="approve" class="btn-approve">&#10003; Approve</button>
                <button type="submit" name="action" value="reject" style="background:#b91c1c;color:#fff;border:none;padding:9px 18px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer">&#10005; Reject</button>
              </div>
            </div>
          </form>
          @endif
        </div>
        @endif

        <form method="POST" action="/focal/groups/{{ $group->id }}/delete" style="margin-top:8px" onsubmit="return confirm('Are you sure?')">
          @csrf
          <button type="submit" class="btn-delete">Delete Group</button>
        </form>

        <!-- Supervisor already assigned -->
        @if($group->status == 'supervisor_assigned')
        <hr class="divider">
        <div class="supervisor-assigned-box">
          <div class="supervisor-name">✓ Supervisor: {{ $group->supervisor->name ?? '-' }}</div>
          <button class="btn-change" onclick="toggleEdit({{ $group->id }})">✏️ Change Supervisor</button>
        </div>

        <!-- Change form - hidden by default -->
        <div id="edit-{{ $group->id }}" style="display:none;margin-top:12px">
          <form method="POST" class="ajax-form" action="/focal/groups/{{ $group->id }}/assign-faculty">
            @csrf
            <div class="assign-form">
              <div class="input-wrap">
                <select name="supervisor_id">
                  <option value="">Select Supervisor</option>
                  @foreach($supervisors as $sup)
                  <option value="{{ $sup->id }}" {{ $group->supervisor_id==$sup->id?'selected':'' }}>{{ $sup->name }}</option>
                  @endforeach
                </select>
              </div>
              <button type="submit" class="btn-assign">Update</button>
            </div>
          </form>
        </div>

        <form method="POST" action="/focal/groups/{{ $group->id }}/delete" style="margin-top:8px" onsubmit="return confirm('Are you sure?')">
          @csrf
          <button type="submit" class="btn-delete">Delete Group</button>
        </form>
        @endif

        </div>

      </div>
    </div>
    @endforeach
  @else
    <div class="empty">No FYP groups formed yet.</div>
  @endif
</div>

<script>
function toggleGroupBody(id){
    const body = document.getElementById('body-' + id);
    const arrow = document.getElementById('arrow-' + id);
    if (body.style.display === 'none') {
        body.style.display = 'block';
        arrow.classList.add('open');
    } else {
        body.style.display = 'none';
        arrow.classList.remove('open');
    }
}

const token = localStorage.getItem('api_token');

async function loadStats() {
    const res  = await fetch('/api/focal/groups', {
        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        }
    });
    const data = await res.json();
    if (!data.success) return;

    const groups   = data.groups;
    const total    = groups.length;
    const pending  = groups.filter(g => g.status === 'proposal_submitted').length;
    const assigned = groups.filter(g => g.status === 'supervisor_assigned').length;
    const open     = groups.filter(g => g.status === 'open').length;

    document.getElementById('stat-total').textContent    = total;
    document.getElementById('stat-pending').textContent  = pending;
    document.getElementById('stat-assigned').textContent = assigned;
    document.getElementById('stat-open').textContent     = open;
}

function toggleEdit(groupId) {
    const el = document.getElementById('edit-' + groupId);
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}

function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('hamburger').classList.toggle('open');
    document.getElementById('overlay').classList.toggle('show');
}

loadStats();
</script>

<script src="/js/ajax.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('select[name="supervisor_id"]').select2({
        placeholder: "Search Supervisor...",
        allowClear: true
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('form.ajax-form').forEach(function(form){
        ajaxForm(form, function(data){
            if(data.reload) setTimeout(()=>location.reload(), 800);
        });
    });
});
</script>
</body>
</html>