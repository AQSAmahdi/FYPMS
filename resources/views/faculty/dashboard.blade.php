@extends('layouts.faculty')

@section('title', 'Faculty Dashboard - FYPMS')

@section('styles')
.main{margin-left:260px;padding:28px 32px}
.topbar{background:linear-gradient(135deg,#378ADD,#0C447C);border-radius:12px;padding:18px 26px;margin-bottom:24px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 1px 12px rgba(12,68,124,.15)}
.topbar h2{color:#fff;font-size:19px;font-weight:700}
.topbar p{color:rgba(255,255,255,.8);font-size:12px;margin-top:3px}
.badge-role{background:rgba(255,255,255,.2);color:#fff;padding:6px 16px;border-radius:20px;font-size:12px;font-weight:600;border:1px solid rgba(255,255,255,.4)}

.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-bottom:28px}
.stat{background:#fff;border-radius:14px;padding:24px 20px;text-align:center;border:1px solid #c3daf0;box-shadow:0 1px 12px rgba(12,68,124,.08);transition:transform .18s ease,box-shadow .18s ease}
.stat:hover{transform:translateY(-3px);box-shadow:0 6px 20px rgba(12,68,124,.12)}
.stat-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;background:#0C447C}
.stat-icon svg{width:20px;height:20px;fill:none;stroke:#fff}
.stat h3{color:#8096b0;font-size:11px;font-weight:600;margin-bottom:8px;text-transform:uppercase;letter-spacing:.6px}
.stat .number{font-size:32px;font-weight:700;color:#0C447C;line-height:1}
.stat p{color:#a8bcd0;font-size:11.5px;margin-top:5px}

.section-title{display:flex;align-items:center;gap:10px;color:#0C447C;font-size:15px;font-weight:700;margin-bottom:16px}
.section-title::before{content:'';width:4px;height:18px;background:#0C447C;border-radius:3px;display:inline-block}

.group-card{background:#fff;border-radius:14px;padding:22px 24px;margin-bottom:16px;border:1px solid #c3daf0;box-shadow:0 1px 12px rgba(12,68,124,.08);transition:box-shadow .15s}
.group-card:hover{box-shadow:0 4px 16px rgba(12,68,124,.12)}
.group-top{display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:10px}
.group-code{font-size:16px;font-weight:700;color:#0C447C}
.group-meta{font-size:12px;color:#8096b0;margin-top:4px}
.badge{padding:5px 14px;border-radius:20px;font-size:11px;font-weight:700;letter-spacing:.2px}
.badge-open{background:#dbeafe;color:#1e40af}
.badge-locked{background:#fef3c7;color:#92400e}
.badge-submitted{background:#ede9fe;color:#5b21b6}
.badge-assigned{background:#dcfce7;color:#166534}
.badge-muted{background:#f4f8fd;color:#8096b0}
.subheading{font-size:11px;font-weight:700;color:#8096b0;text-transform:uppercase;letter-spacing:.7px;margin-bottom:10px}
.summary-row{display:flex;align-items:center;gap:18px;margin-top:14px;flex-wrap:wrap}
.summary-chip{display:flex;align-items:center;gap:8px;font-size:12.5px;color:#4a6a8a}
.summary-chip svg{width:15px;height:15px;stroke:#0C447C;fill:none;flex-shrink:0}
.view-btn{margin-left:auto;background:#0C447C;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:12px;font-weight:600;cursor:pointer;transition:opacity .15s}
.view-btn:hover{opacity:.88}
.members-row{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:14px}
.member-chip{display:flex;align-items:center;gap:10px;background:#f4f8fd;border-radius:10px;padding:10px 14px;border:1px solid #e8f0f9;transition:background .15s}
.member-chip:hover{background:#eaf2fb}
.member-avatar{width:34px;height:34px;border-radius:50%;background:#0C447C;color:#fff;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0}
.member-name{font-size:13px;font-weight:600;color:#1e3a5f}
.member-roll{font-size:11px;color:#8096b0}
.divider{border:none;border-top:1px solid #e8f0f9;margin:16px 0}
.phase-row{display:flex;justify-content:space-between;align-items:center;background:#f4f8fd;border-radius:10px;padding:12px 16px;margin-bottom:8px;border:1px solid #eef3f9}
.proposal-box{background:#f8fafc;border-radius:12px;padding:18px;border:1px solid #e8f0f9;border-left:4px solid #0C447C}
.proposal-title{font-size:14px;font-weight:700;color:#0C447C;margin-bottom:6px}
.proposal-desc{font-size:13px;color:#4a6a8a;line-height:1.6}
.proposal-tech{font-size:12px;color:#8096b0;margin-top:8px}
.download-link{display:inline-flex;align-items:center;gap:6px;font-size:12px;color:#0C447C;font-weight:600;text-decoration:none;margin-top:10px;background:#e8f0f9;padding:7px 14px;border-radius:8px;transition:background .15s}
.download-link:hover{background:#d5e3f0}
.empty{background:#fff;border-radius:14px;padding:48px;text-align:center;color:#8096b0;font-size:13.5px;border:1px solid #c3daf0;box-shadow:0 1px 12px rgba(12,68,124,.06)}
.empty svg{width:38px;height:38px;stroke:#c4d4e8;fill:none;margin:0 auto 12px;display:block}
.action-btn{border:none;border-radius:8px;padding:8px 16px;font-size:12px;font-weight:600;cursor:pointer;text-decoration:none;display:inline-block;transition:opacity .15s}
.action-btn:hover{opacity:.88}
.btn-view{background:#0C447C;color:#fff}
.btn-evaluate{background:#166534;color:#fff}
.detail-box{background:#f4f8fd;border-radius:10px;padding:12px}
.detail-label{font-size:10px;font-weight:700;color:#8096b0;text-transform:uppercase;margin-bottom:4px}
.detail-value{font-size:13px;font-weight:600;color:#1e3a5f}
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:9999;align-items:center;justify-content:center}
.modal-box{background:#fff;border-radius:16px;padding:28px;width:600px;max-width:95vw;max-height:85vh;overflow-y:auto;position:relative}
.modal-close{position:absolute;top:14px;right:16px;background:none;border:none;font-size:20px;cursor:pointer;color:#8096b0}
.modal-title{font-size:17px;font-weight:700;color:#0C447C;margin-bottom:6px}
.modal-meta{font-size:12px;color:#8096b0;margin-bottom:18px}
@media(max-width:768px){
  .main{margin-left:0;padding:14px;padding-top:62px}
  .stats{grid-template-columns:1fr}
  .topbar{flex-direction:column;align-items:flex-start;gap:8px}
  .group-top{flex-direction:column}
  .view-btn{margin-left:0}
}
@endsection

@section('content')
<div class="topbar">
    <div>
      <h2>Dashboard</h2>
      <p>Welcome, {{ $supervisor->name }}!</p>
    </div>
    <span class="badge-role">Faculty</span>
  </div>

  <div class="stats">
    <div class="stat">
      <div class="stat-icon"><svg viewBox="0 0 24 24" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
      <h3>My Groups</h3>
      <div class="number">{{ $groups->count() }}</div>
      <p>Assigned groups</p>
    </div>
    <div class="stat">
      <div class="stat-icon"><svg viewBox="0 0 24 24" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>
      <h3>My Students</h3>
      <div class="number">{{ $totalStudents }}</div>
      <p>Total students</p>
    </div>
    <div class="stat">
      <div class="stat-icon"><svg viewBox="0 0 24 24" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></div>
      <h3>Proposals</h3>
      <div class="number">{{ $groups->filter(fn($g)=>$g->proposal)->count() }}</div>
      <p>Submitted proposals</p>
    </div>
  </div>

  <div class="section-title">My Assigned Groups</div>

  @if($groups->count() > 0)
    @foreach($groups as $group)
    @php $memberCount = $group->members->filter(fn($m)=>$m->user)->count(); @endphp
    <div class="group-card">
      <div class="group-top">
        <div>
          <div class="group-code">{{ $group->group_code }}</div>
          <div class="group-meta">{{ $group->department->name ?? '-' }} &bull; Session {{ $group->session->name ?? '-' }} &bull; {{ $group->shift }}</div>
        </div>
        @if($group->status=='open') <span class="badge badge-open">Open</span>
        @elseif($group->status=='locked') <span class="badge badge-locked">Locked</span>
        @elseif($group->status=='proposal_submitted') <span class="badge badge-submitted">Proposal Submitted</span>
        @elseif($group->status=='supervisor_assigned') <span class="badge badge-assigned">Supervisor Assigned ✓</span>
        @endif
      </div>

      <div class="summary-row">
        <div class="summary-chip">
          <svg viewBox="0 0 24 24" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
          {{ $memberCount }} {{ $memberCount == 1 ? 'member' : 'members' }}
        </div>
        <div class="summary-chip">
          <svg viewBox="0 0 24 24" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
          @if($group->proposal) Proposal submitted @else No proposal yet @endif
        </div>
        <button class="view-btn" onclick="openModal('group-{{ $group->id }}')">View Details</button>
        <a href="{{ route('chat.index', $group->id) }}" class="view-btn chat-link-btn" data-group-id="{{ $group->id }}" style="text-decoration:none;display:inline-block;position:relative">
            💬 Chat
            <span class="chat-group-badge" data-badge-for="{{ $group->id }}" style="display:none;background:#e63946;color:#fff;font-size:10px;font-weight:700;padding:2px 6px;border-radius:10px;margin-left:6px">0</span>
        </a>
      </div>
    </div>

    <div class="modal-overlay" id="modal-group-{{ $group->id }}">
      <div class="modal-box">
        <button class="modal-close" onclick="closeModal('group-{{ $group->id }}')">&times;</button>
        <div class="modal-title">{{ $group->group_code }}</div>
        <div class="modal-meta">{{ $group->department->name ?? '-' }} &bull; Session {{ $group->session->name ?? '-' }} &bull; {{ $group->shift }}</div>

        <div class="subheading">Members</div>
        <div class="members-row">
          @foreach($group->members->filter(fn($m)=>$m->user) as $m)
          <div class="member-chip">
            <div class="member-avatar">{{ strtoupper(substr($m->user->name,0,1)) }}</div>
            <div>
              <div class="member-name">{{ $m->user->name }}</div>
              <div class="member-roll">{{ $m->user->studentProfile->student_id ?? '-' }}</div>
            </div>
          </div>
      {{-- Phase Results - sirf average dikhao, marks confidential --}}
      @if(isset($phaseResults[$group->id]) && $phaseResults[$group->id]->count() > 0)
      <div style="margin-top:14px;border-top:1px solid #e8f0f9;padding-top:14px">
        <div class="subheading">Phase Results</div>
        @foreach($phaseResults[$group->id] as $ps)
        <div class="phase-row">
          <div>
            <div style="font-size:13px;font-weight:600;color:#1e3a5f">{{ $ps->phase->name ?? "&mdash;" }}</div>
            <div style="font-size:11px;color:#8096b0;margin-top:2px">Attempt #{{ $ps->attempt_number }} &bull; {{ \Carbon\Carbon::parse($ps->updated_at)->format("d M Y") }}</div>
          </div>
          <div style="text-align:right">
            <div style="font-size:16px;font-weight:700;color:#0C447C">{{ $ps->average_marks }}%</div>
            @if($ps->result === "pass")
              <span class="badge badge-assigned">Pass</span>
            @elseif($ps->result === "fail")
              <span style="background:#fce4e4;color:#c62828;padding:5px 14px;border-radius:20px;font-size:11px;font-weight:700">Fail</span>
            @else
              <span class="badge badge-muted">Pending</span>
            @endif
          </div>
        </div>
        @endforeach
      </div>
      @endif
          @endforeach
        </div>

        @if($group->proposal)
        <div class="divider"></div>
        <div class="subheading">Proposal</div>
        <div class="proposal-box">
          <div class="proposal-title">{{ $group->proposal->title }}</div>
          <div class="proposal-desc">{{ $group->proposal->description }}</div>
          @if($group->proposal->technology_stack)
          <div class="proposal-tech">Tech Stack: {{ $group->proposal->technology_stack }}</div>
          @endif
          @if($group->proposal->file_path)
          <div><a href="/student/proposal/document/{{ $group->proposal->id }}" class="download-link">📎 Download Proposal Document</a></div>
          @endif
          <div style="margin-top:10px;font-size:12px">
            Status:
            @if($group->proposal->status=='pending') <span style="color:#92400e;font-weight:600">Pending Review</span>
            @elseif($group->proposal->status=='approved') <span style="color:#166534;font-weight:600">Approved ✓</span>
            @else <span style="color:#b91c1c;font-weight:600">Rejected</span>
            @endif
          </div>
        </div>
        @endif
      </div>
    </div>
    @endforeach
  @else
    <div class="empty">
      <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      No groups assigned to you yet.
    </div>
  @endif

  <div class="section-title" style="margin-top:32px">My Defence Schedules</div>
  @if($defences->count() > 0)
    @foreach($defences as $defence)
    <div class="group-card">
      <div class="group-top">
        <div>
          <div class="group-code">{{ $defence->group->group_code ?? '-' }}</div>
          <div class="group-meta">{{ $defence->defence_date }} &bull; {{ $defence->defence_time }}</div>
        </div>
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
          <span class="badge badge-assigned">Defence Scheduled</span>
          <button onclick="openModal('defence-{{ $defence->id }}')" class="action-btn btn-view">View Details</button>
          <a href="/faculty/defence/{{ $defence->id }}/evaluate" class="action-btn btn-evaluate">Evaluate</a>
        </div>
      </div>
    </div>

    <div class="modal-overlay" id="modal-defence-{{ $defence->id }}">
      <div class="modal-box">
        <button class="modal-close" onclick="closeModal('defence-{{ $defence->id }}')">&times;</button>
        <div class="modal-title">{{ $defence->group->group_code ?? '-' }} — Defence Details</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px">
          <div class="detail-box">
            <div class="detail-label">Date</div>
            <div class="detail-value">{{ $defence->defence_date }}</div>
          </div>
          <div class="detail-box">
            <div class="detail-label">Time</div>
            <div class="detail-value">{{ $defence->defence_time }}</div>
          </div>
          <div class="detail-box">
            <div class="detail-label">Room</div>
            <div class="detail-value">{{ $defence->room ?? 'Not set' }}</div>
          </div>
          <div class="detail-box">
            <div class="detail-label">Panel</div>
            <div class="detail-value">{{ $defence->panel->name ?? 'Not set' }}</div>
          </div>
        </div>
        <div class="subheading">Students</div>
        <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:16px">
          @foreach($defence->group->members->filter(fn($m)=>$m->user) as $m)
          <div class="member-chip">
            <div class="member-avatar">{{ strtoupper(substr($m->user->name,0,1)) }}</div>
            <div>
              <div class="member-name">{{ $m->user->name }}</div>
              <div class="member-roll">{{ $m->user->studentProfile->student_id ?? '-' }}</div>
            </div>
          </div>
          @endforeach
        </div>
        @if($defence->criteria->count() > 0)
        <div class="subheading">Evaluation Criteria</div>
        <div class="detail-box">
          @foreach($defence->criteria as $c)
          <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid #e8f0f9">
            <span style="font-size:13px;color:#1e3a5f">{{ $c->criteria_name }}</span>
            <span style="font-size:12px;font-weight:600;color:#0C447C">{{ $c->max_marks }} marks</span>
          </div>
          @endforeach
        </div>
        @endif
      </div>
    </div>
    @endforeach
  @else
    <div class="empty">
      <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M8 2v4M16 2v4M3 10h18M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/></svg>
      No defence schedules assigned yet.
    </div>
  @endif
@endsection

<script>
async function loadFacultyChatBadges() {
    try {
        const res = await fetch('{{ route("chat.unread-by-group") }}');
        const data = await res.json();
        if (!data.success) return;
        document.querySelectorAll('.chat-group-badge').forEach(badge => {
            const gid = badge.getAttribute('data-badge-for');
            const count = data.unread_by_group[gid] || 0;
            if (count > 0) {
                badge.style.display = 'inline-block';
                badge.textContent = count;
            } else {
                badge.style.display = 'none';
            }
        });
    } catch(e) {}
}
document.addEventListener('DOMContentLoaded', loadFacultyChatBadges);
setInterval(loadFacultyChatBadges, 10000);
</script>
