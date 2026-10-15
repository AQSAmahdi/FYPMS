<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Group Chat - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
.main{margin-left:260px;padding:28px 32px;min-height:100vh}
.topbar{background:linear-gradient(135deg,#378ADD,#0C447C);border-radius:12px;padding:22px 28px;margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 4px 16px rgba(12,68,124,.18)}
.topbar-left{display:flex;align-items:center;gap:16px}
.topbar h2{color:#fff;font-size:18px;font-weight:700}
.topbar p{color:rgba(255,255,255,.85);font-size:12px;margin-top:2px}
.btn-clear{background:rgba(255,255,255,.18);color:#fff;border:none;padding:8px 16px;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;margin-left:8px}
.btn-clear:hover{background:rgba(255,255,255,.3)}
.chat-box{background:#fff;border-radius:12px;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9;height:65vh;display:flex;flex-direction:column}
.messages{flex:1;overflow-y:auto;padding:20px;display:flex;flex-direction:column;gap:10px}
.msg-row{display:flex;align-items:flex-end;gap:8px;max-width:75%}
.msg-row.mine{align-self:flex-end;flex-direction:row-reverse}
.msg-row.theirs{align-self:flex-start}
.msg-avatar{width:30px;height:30px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff;flex-shrink:0}
.msg{padding:10px 14px;border-radius:14px;font-size:13px;line-height:1.4;box-shadow:0 1px 4px rgba(12,68,124,.08)}
.msg.mine{background:linear-gradient(135deg,#378ADD,#0C447C);color:#fff;border-bottom-right-radius:3px}
.msg.theirs{color:#1e3a5f;border-bottom-left-radius:3px}
.msg-sender{font-size:11px;font-weight:700;margin-bottom:3px;opacity:.85}
.msg-time{font-size:10px;opacity:.6;margin-top:4px}
.msg.mine .msg-time{text-align:right}
.input-row{display:flex;gap:10px;padding:16px 20px;border-top:1px solid #e8f0f9}
.input-row input{flex:1;padding:12px 16px;border-radius:10px;border:1.5px solid #e8f0f9;font-size:13px;outline:none}
.input-row input:focus{border-color:#0C447C}
.btn-send{background:#0C447C;color:#fff;border:none;padding:12px 22px;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer}
.btn-send:hover{background:#185FA5}
.empty-state{text-align:center;color:#8096b0;font-size:13px;margin-top:40px}
.msg-wrap{position:relative}
.msg-menu-btn{position:absolute;top:-8px;right:-8px;width:22px;height:22px;border-radius:50%;background:#fff;border:1px solid #e8f0f9;color:#8096b0;font-size:14px;line-height:1;cursor:pointer;display:none;align-items:center;justify-content:center;box-shadow:0 1px 4px rgba(12,68,124,.15)}
.msg-row.mine .msg-menu-btn{left:-8px;right:auto}
.msg-wrap:hover .msg-menu-btn{display:flex}
.msg-dropdown{position:absolute;top:16px;right:-8px;background:#fff;border:1px solid #e8f0f9;border-radius:8px;box-shadow:0 4px 16px rgba(12,68,124,.18);z-index:10;min-width:150px;display:none}
.msg-row.mine .msg-dropdown{right:auto;left:-8px}
.msg-dropdown.show{display:block}
.msg-dropdown button{display:block;width:100%;text-align:left;padding:9px 14px;background:none;border:none;font-size:12px;color:#1e3a5f;cursor:pointer}
.msg-dropdown button:hover{background:#f4f8fd}
</style>
</head>
<body>

@php
    $role = auth()->user()->role;
@endphp

@if($role === 'faculty' || $role === 'supervisor')
    @include('faculty.partials.sidebar')
@else
    @include('student.partials.sidebar')
@endif

<div class="main">
    <div class="topbar">
        <div class="topbar-left">
            <div>
                <h2>{{ $group->group_code }} — Group Chat</h2>
                <p>{{ $group->project_title }}</p>
            </div>
        </div>
        <div>
            <button class="btn-clear" onclick="clearForMe()">Clear for me</button>
            <button class="btn-clear" onclick="clearForEveryone()">Clear for everyone</button>
        </div>
    </div>

    <div class="chat-box">
        <div class="messages" id="messagesList">
            <div class="empty-state">Loading messages...</div>
        </div>
        <div class="input-row">
            <input type="text" id="messageInput" placeholder="Type your message..." onkeypress="if(event.key==='Enter') sendMessage()">
            <button class="btn-send" onclick="sendMessage()">Send</button>
        </div>
    </div>
</div>

<script>
const groupId = {{ $group->id }};
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
let pollTimer = null;

const avatarPalette = ['#e07a5f','#3d5a80','#81b29a','#f2cc8f','#9b5de5','#00bbf9','#f15bb5','#43aa8b'];
function colorForSender(id) {
    let hash = 0;
    const str = String(id);
    for (let i = 0; i < str.length; i++) hash = str.charCodeAt(i) + ((hash << 5) - hash);
    return avatarPalette[Math.abs(hash) % avatarPalette.length];
}
function initials(name) {
    if (!name) return '?';
    const parts = name.trim().split(' ');
    return (parts[0][0] + (parts[1] ? parts[1][0] : '')).toUpperCase();
}

function renderMessages(messages) {
    const list = document.getElementById('messagesList');
    if (messages.length === 0) {
        list.innerHTML = '<div class="empty-state">No messages yet. Say hello!</div>';
        return;
    }
    list.innerHTML = messages.map(m => {
        const color = colorForSender(m.sender_id);
        return `
        <div class="msg-row ${m.is_mine ? 'mine' : 'theirs'}">
            <div class="msg-avatar" style="background:${m.is_mine ? '#0C447C' : color}">${initials(m.sender_name)}</div>
            <div class="msg-wrap">
                <div class="msg ${m.is_mine ? 'mine' : 'theirs'}" style="${!m.is_mine ? `background:${color}22;border:1.5px solid ${color}55` : ''}">
                    ${!m.is_mine ? `<div class="msg-sender" style="color:${color}">${m.sender_name}</div>` : ''}
                    <div>${m.message.replace(/</g,'&lt;').replace(/>/g,'&gt;')}</div>
                    <div class="msg-time">${m.created_at}</div>
                </div>
                <button class="msg-menu-btn" onclick="toggleMsgMenu(event, ${m.id})">&#8942;</button>
                <div class="msg-dropdown" id="msgMenu-${m.id}">
                    <button onclick="deleteMessage(${m.id}, 'me')">Delete for me</button>
                    ${m.is_mine ? `<button onclick="deleteMessage(${m.id}, 'everyone')">Delete for everyone</button>` : ''}
                </div>
            </div>
        </div>`;
    }).join('');
    list.scrollTop = list.scrollHeight;
}

function toggleMsgMenu(event, messageId) {
    event.stopPropagation();
    document.querySelectorAll('.msg-dropdown.show').forEach(el => {
        if (el.id !== 'msgMenu-' + messageId) el.classList.remove('show');
    });
    document.getElementById('msgMenu-' + messageId).classList.toggle('show');
}

document.addEventListener('click', () => {
    document.querySelectorAll('.msg-dropdown.show').forEach(el => el.classList.remove('show'));
});

async function deleteMessage(messageId, mode) {
    const endpoint = mode === 'me'
        ? `/chat/message/${messageId}/delete-for-me`
        : `/chat/message/${messageId}/delete-for-everyone`;

    if (mode === 'everyone' && !confirm('Delete this message for everyone?')) return;

    try {
        await fetch(endpoint, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken }
        });
        fetchMessages();
    } catch (e) { console.error(e); }
}

async function fetchMessages() {
    try {
        const res = await fetch(`/chat/${groupId}/messages`);
        const data = await res.json();
        if (data.success) renderMessages(data.messages);
    } catch (e) { console.error(e); }
}

async function sendMessage() {
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    if (!message) return;
    input.value = '';
    try {
        await fetch(`/chat/${groupId}/send`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ message })
        });
        fetchMessages();
    } catch (e) { console.error(e); }
}

async function clearForMe() {
    if (!confirm('Clear chat for you only? Others will still see the messages.')) return;
    await fetch(`/chat/${groupId}/clear-for-me`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken }
    });
    fetchMessages();
}

async function clearForEveryone() {
    if (!confirm('This will delete the chat for EVERYONE in the group. Continue?')) return;
    await fetch(`/chat/${groupId}/clear-for-everyone`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken }
    });
    fetchMessages();
}

fetchMessages();
pollTimer = setInterval(fetchMessages, 5000);
</script>

</body>
</html>
