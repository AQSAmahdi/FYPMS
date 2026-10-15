<!DOCTYPE html>
<html lang="en" style="background:linear-gradient(90deg,#0C447C 260px,#f0f4f8 260px)">
<head>
<style>html,body{background:#f0f4f8}</style>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>FYP Assistant - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
.main{margin-left:260px;padding:28px 32px;min-height:100vh;display:flex;flex-direction:column}
.topbar{background:#fff;border-radius:12px;padding:16px 22px;margin-bottom:24px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 1px 12px rgba(12,68,124,.07);border:1px solid #e8f0f9}
.topbar h2{color:#0C447C;font-size:18px;font-weight:700}
.topbar p{color:#8096b0;font-size:12px;margin-top:2px}
.badge-role{background:#e8f0f9;color:#0C447C;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600}
.chat-container{background:#fff;border-radius:16px;border:1px solid #e8f0f9;box-shadow:0 1px 12px rgba(12,68,124,.07);display:flex;flex-direction:column;flex:1;min-height:500px;overflow:hidden}
.chat-messages{flex:1;overflow-y:auto;padding:24px;display:flex;flex-direction:column;gap:16px}
.msg{display:flex;gap:12px;align-items:flex-start;max-width:80%}
.msg.user{align-self:flex-end;flex-direction:row-reverse}
.msg-avatar{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;flex-shrink:0}
.msg.bot .msg-avatar{background:#0C447C;color:#fff}
.msg.user .msg-avatar{background:#e8f0f9;color:#0C447C}
.msg-bubble{padding:12px 16px;border-radius:16px;font-size:14px;line-height:1.6}
.msg.bot .msg-bubble{background:#f4f8fd;color:#1e3a5f;border-bottom-left-radius:4px}
.msg.user .msg-bubble{background:#0C447C;color:#fff;border-bottom-right-radius:4px}
.chat-input-area{padding:16px 20px;border-top:1px solid #e8f0f9;display:flex;gap:10px;align-items:center}
.chat-input{flex:1;padding:12px 16px;border:1.5px solid #e8f0f9;border-radius:25px;font-size:14px;font-family:'Segoe UI',sans-serif;outline:none;transition:border .2s}
.chat-input:focus{border-color:#0C447C}
.send-btn{background:#0C447C;color:#fff;border:none;border-radius:50%;width:44px;height:44px;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:background .2s}
.send-btn:hover{background:#185FA5}
.send-btn svg{width:18px;height:18px;stroke:#fff;fill:none}
.typing{display:flex;gap:4px;align-items:center;padding:8px 0}
.typing span{width:8px;height:8px;background:#8096b0;border-radius:50%;animation:bounce 1.2s infinite}
.typing span:nth-child(2){animation-delay:.2s}
.typing span:nth-child(3){animation-delay:.4s}
@keyframes bounce{0%,60%,100%{transform:translateY(0)}30%{transform:translateY(-8px)}}
.welcome-card{background:linear-gradient(135deg,#0C447C,#185FA5);border-radius:12px;padding:20px;color:#fff;margin-bottom:16px}
.welcome-card h3{font-size:16px;font-weight:700;margin-bottom:8px}
.welcome-card p{font-size:13px;opacity:.85;line-height:1.5}
.quick-btns{display:flex;flex-wrap:wrap;gap:8px;margin-top:12px}
.quick-btn{background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);color:#fff;padding:6px 14px;border-radius:20px;font-size:12px;cursor:pointer;transition:background .2s;font-family:'Segoe UI',sans-serif}
.quick-btn:hover{background:rgba(255,255,255,.25)}
@media(max-width:768px){
    .main{margin-left:0;padding:16px}
    .msg{max-width:95%}
}
</style>
<link rel="stylesheet" href="/css/transitions.css?v=3">
<meta name="view-transition" content="same-origin">
</head>
<body>
<button class="hamburger" id="hamburger" onclick="toggleSidebar()">
  <span></span><span></span><span></span>
</button>
<div class="overlay" id="overlay" onclick="toggleSidebar()"></div>
@include('student.partials.sidebar')
<div class="main">
  <div class="topbar">
    <div>
      <h2 style="display:flex;align-items:center;gap:8px"><svg viewBox="0 0 24 24" width="18" height="18" stroke="#0C447C" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg> FYP Assistant</h2>
      <p>Ask anything about your FYP — powered by AI</p>
    </div>
    <button onclick="clearChat()" style="background:#fce4e4;color:#c62828;border:none;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px"><svg viewBox="0 0 24 24" width="14" height="14" stroke="#c62828" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg> Clear Chat</button>
  </div>

  <div class="chat-container">
    <div class="chat-messages" id="chatMessages">
      <div class="welcome-card">
        <h3>Welcome, {{ $student->name }}!</h3>
        <p>I am your FYP Assistant. You can ask me anything about your project — proposal status, phase schedule, document uploads, or any other questions!</p>
        <div class="quick-btns">
          <button class="quick-btn" onclick="sendQuick('Is my proposal approved?')">Proposal status?</button>
          <button class="quick-btn" onclick="sendQuick('When is my next phase?')">Next phase?</button>
          <button class="quick-btn" onclick="sendQuick('Have I uploaded my documents?')">Documents status?</button>
          <button class="quick-btn" onclick="sendQuick('Who is my supervisor?')">My supervisor?</button>
          <button class="quick-btn" onclick="sendQuick('What is my group info?')">Group info?</button>
        </div>
      </div>

      @foreach($chatHistory as $msg)
      <div class="msg {{ $msg->role === 'user' ? 'user' : 'bot' }}">
        <div class="msg-avatar">{{ $msg->role === 'user' ? substr($student->name, 0, 1) : 'AI' }}</div>
        <div class="msg-bubble">{{ $msg->message }}</div>
      </div>
      @endforeach
    </div>
    <div class="chat-input-area">
      <input type="text" class="chat-input" id="chatInput" placeholder="Ask anything about your FYP..." onkeypress="if(event.key==='Enter') sendMessage()">
      <button class="send-btn" onclick="sendMessage()">
        <svg viewBox="0 0 24 24" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
      </button>
    </div>
  </div>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('hamburger').classList.toggle('open');
    const overlay = document.getElementById('overlay');
    if(overlay) overlay.classList.toggle('show');
}

function sendQuick(msg){
    document.getElementById('chatInput').value = msg;
    sendMessage();
}

function addMessage(text, isUser){
    const container = document.getElementById('chatMessages');
    const div = document.createElement('div');
    div.className = 'msg ' + (isUser ? 'user' : 'bot');
    const initial = isUser ? '{{ substr($student->name, 0, 1) }}' : 'AI';
    div.innerHTML = `
        <div class="msg-avatar">${initial}</div>
        <div class="msg-bubble">${text.replace(/\n/g, '<br>')}</div>
    `;
    container.appendChild(div);
    container.scrollTop = container.scrollHeight;
}

function addTyping(){
    const container = document.getElementById('chatMessages');
    const div = document.createElement('div');
    div.className = 'msg bot';
    div.id = 'typingIndicator';
    div.innerHTML = `
        <div class="msg-avatar">AI</div>
        <div class="msg-bubble">
            <div class="typing">
                <span></span><span></span><span></span>
            </div>
        </div>
    `;
    container.appendChild(div);
    container.scrollTop = container.scrollHeight;
}

function removeTyping(){
    const t = document.getElementById('typingIndicator');
    if(t) t.remove();
}

async function clearChat(){
    if(!confirm('Clear all chat history?')) return;
    const res = await fetch('/student/assistant/clear', {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json'}
    });
    const data = await res.json();
    if(data.success){
        const container = document.getElementById('chatMessages');
        // Remove all messages except welcome card
        const msgs = container.querySelectorAll('.msg');
        msgs.forEach(m => m.remove());
    }
}

async function sendMessage(){
    const input = document.getElementById('chatInput');
    const msg = input.value.trim();
    if(!msg) return;

    addMessage(msg, true);
    input.value = '';
    addTyping();

    try {
        const res = await fetch('/student/assistant', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({message: msg})
        });
        const data = await res.json();
        removeTyping();
        addMessage(data.reply || 'Sorry, no response received. Please try again.', false);
    } catch(e) {
        removeTyping();
        addMessage('Connection error. Please try again.', false);
    }
}
</script>
</body>
</html>
