// ── Data ─────────────────────────────────────────────────────────
const conversations = [
  {
    id: 1,
    name: 'Ms. Jennifer Lee',
    role: 'Kindergarten 2 Teacher',
    lastMessage: 'Emma did wonderfully in art class today!',
    time: '10:30 AM',
    unread: 2,
    avatar: '👩‍🏫',
    online: true,
  },
  {
    id: 2,
    name: 'Admin Office',
    role: 'Administration',
    lastMessage: 'Your payment has been received. Thank you!',
    time: 'Yesterday',
    unread: 0,
    avatar: '🏢',
    online: false,
  },
  {
    id: 3,
    name: 'Mr. David Chen',
    role: 'Music Teacher',
    lastMessage: 'Liam is showing great progress with rhythm!',
    time: '2 days ago',
    unread: 1,
    avatar: '👨‍🏫',
    online: true,
  },
  {
    id: 4,
    name: 'Nurse Sarah',
    role: 'School Nurse',
    lastMessage: 'Just a reminder about vaccination records',
    time: '3 days ago',
    unread: 0,
    avatar: '👩‍⚕️',
    online: false,
  },
];

// Seed messages per conversation
const messageStore = {
  1: [
    { id: 1, text: 'Good morning! Just wanted to let you know Emma had a great day today.', time: '9:45 AM', isMe: false },
    { id: 2, text: "That's wonderful to hear! Thank you for the update.", time: '10:15 AM', isMe: true },
    { id: 3, text: 'She participated actively in circle time and made a beautiful painting during art class!', time: '10:20 AM', isMe: false },
    { id: 4, text: "I'd love to see the painting! Can you share a photo?", time: '10:25 AM', isMe: true },
    { id: 5, text: 'Emma did wonderfully in art class today!', time: '10:30 AM', isMe: false },
  ],
  2: [
    { id: 1, text: 'Hello! We wanted to confirm your recent payment.', time: 'Yesterday', isMe: false },
    { id: 2, text: 'Yes, I made the payment earlier today.', time: 'Yesterday', isMe: true },
    { id: 3, text: 'Your payment has been received. Thank you!', time: 'Yesterday', isMe: false },
  ],
  3: [
    { id: 1, text: "Liam is picking up rhythm exercises very quickly.", time: '2 days ago', isMe: false },
    { id: 2, text: 'That is great news! He has been practicing at home too.', time: '2 days ago', isMe: true },
    { id: 3, text: 'Liam is showing great progress with rhythm!', time: '2 days ago', isMe: false },
  ],
  4: [
    { id: 1, text: 'Just a reminder about vaccination records needed before next month.', time: '3 days ago', isMe: false },
    { id: 2, text: 'Thank you for the reminder! I will get them ready.', time: '3 days ago', isMe: true },
  ],
};

// ── State ─────────────────────────────────────────────────────────
let selectedId = 1;
let nextMsgId = 100;
let toastTimer = null;

// ── Helpers ───────────────────────────────────────────────────────
function now() {
  return new Date().toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
}

function escapeHtml(str) {
  return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── Toast ─────────────────────────────────────────────────────────
function showToast(msg) {
  const toast = document.getElementById('toast');
  document.getElementById('toastMsg').textContent = msg;
  toast.classList.remove('hidden', 'hide');
  toast.classList.add('show');
  if (toastTimer) clearTimeout(toastTimer);
  toastTimer = setTimeout(() => {
    toast.classList.remove('show');
    toast.classList.add('hide');
    toast.addEventListener('animationend', () => {
      toast.classList.add('hidden');
      toast.classList.remove('hide');
    }, { once: true });
  }, 3000);
}

// ── Render Conversation List ──────────────────────────────────────
function renderConvList(filter = '') {
  const list = document.getElementById('convList');
  list.innerHTML = '';

  const filtered = conversations.filter(c =>
    c.name.toLowerCase().includes(filter.toLowerCase())
  );

  if (filtered.length === 0) {
    list.innerHTML = '<li style="padding:1.25rem;text-align:center;color:#94a3b8;font-size:.9rem;">No conversations found</li>';
    return;
  }

  filtered.forEach(conv => {
    const li = document.createElement('li');
    li.className = 'conv-item' + (conv.id === selectedId ? ' active' : '');
    li.setAttribute('role', 'option');
    li.setAttribute('aria-selected', conv.id === selectedId ? 'true' : 'false');
    li.innerHTML = `
      <div class="avatar-wrap">
        <span class="avatar-emoji">${conv.avatar}</span>
        <span class="online-dot${conv.online ? '' : ' hidden'}"></span>
      </div>
      <div class="conv-meta">
        <div class="conv-top">
          <span class="conv-name">${escapeHtml(conv.name)}</span>
          <span class="conv-time">${conv.time}</span>
        </div>
        <div class="conv-role">${escapeHtml(conv.role)}</div>
        <div class="conv-last">${escapeHtml(conv.lastMessage)}</div>
      </div>
      ${conv.unread > 0 ? `<span class="unread-badge">${conv.unread}</span>` : ''}
    `;
    li.addEventListener('click', () => selectConversation(conv.id));
    list.appendChild(li);
  });
}

// ── Select Conversation ───────────────────────────────────────────
function selectConversation(id) {
  selectedId = id;
  // Clear unread
  const conv = conversations.find(c => c.id === id);
  if (conv) conv.unread = 0;

  renderConvList(document.getElementById('convSearch').value);
  updateHeader();
  updateMuteLabel();
  renderMessages();
}

// ── Update Chat Header ────────────────────────────────────────────
function updateHeader() {
  const conv = conversations.find(c => c.id === selectedId);
  if (!conv) return;
  document.getElementById('headerEmoji').textContent = conv.avatar;
  document.getElementById('headerName').textContent = conv.name;
  document.getElementById('headerRole').textContent = conv.role;
  const dot = document.getElementById('headerOnline');
  conv.online ? dot.classList.remove('hidden') : dot.classList.add('hidden');
}

// ── Render Messages ───────────────────────────────────────────────
function renderMessages() {
  const area = document.getElementById('messagesArea');
  area.innerHTML = '';
  const msgs = messageStore[selectedId] || [];

  msgs.forEach((msg, i) => {
    area.appendChild(buildBubble(msg, i));
  });

  scrollToBottom();
}

function buildBubble(msg, delay = 0) {
  const row = document.createElement('div');
  row.className = `bubble-row ${msg.isMe ? 'me' : 'them'}`;

  const bubble = document.createElement('div');
  bubble.className = `bubble ${msg.isMe ? 'me' : 'them'}`;
  bubble.style.animationDelay = `${delay * 40}ms`;

  if (msg.imageUrl) {
    bubble.innerHTML = `<img src="${escapeHtml(msg.imageUrl)}" alt="Shared image"><div class="bubble-time">${escapeHtml(msg.time)}</div>`;
  } else if (msg.fileName) {
    bubble.innerHTML = `
      <div class="bubble-file">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        ${escapeHtml(msg.fileName)}
      </div>
      <div class="bubble-time">${escapeHtml(msg.time)}</div>`;
  } else {
    bubble.innerHTML = `<div class="bubble-text">${escapeHtml(msg.text)}</div><div class="bubble-time">${escapeHtml(msg.time)}</div>`;
  }

  row.appendChild(bubble);
  return row;
}

function scrollToBottom() {
  const area = document.getElementById('messagesArea');
  requestAnimationFrame(() => { area.scrollTop = area.scrollHeight; });
}

// ── Send Message ──────────────────────────────────────────────────
function sendMessage() {
  const input = document.getElementById('msgInput');
  const text = input.value.trim();
  if (!text) return;

  const msg = { id: nextMsgId++, text, time: now(), isMe: true };
  if (!messageStore[selectedId]) messageStore[selectedId] = [];
  messageStore[selectedId].push(msg);

  // Update conversation preview
  const conv = conversations.find(c => c.id === selectedId);
  if (conv) { conv.lastMessage = text; conv.time = now(); }

  input.value = '';
  autoResize(input);
  renderConvList(document.getElementById('convSearch').value);

  // Append bubble
  const area = document.getElementById('messagesArea');
  area.appendChild(buildBubble(msg));
  scrollToBottom();
}

// ── Auto-resize textarea ──────────────────────────────────────────
function autoResize(el) {
  el.style.height = 'auto';
  el.style.height = Math.min(el.scrollHeight, 120) + 'px';
}

// ── Mute state ────────────────────────────────────────────────────
const mutedConversations = new Set();

// ── More Menu ─────────────────────────────────────────────────────
function toggleMoreMenu() {
  const menu = document.getElementById('moreMenu');
  const isHidden = menu.classList.contains('hidden');
  menu.classList.toggle('hidden');

  if (isHidden) {
    // Update mute label before showing
    updateMuteLabel();
    // Close on outside click
    const close = (e) => {
      const wrap = document.querySelector('.more-menu-wrap');
      if (!wrap.contains(e.target)) {
        menu.classList.add('hidden');
        document.removeEventListener('click', close);
      }
    };
    setTimeout(() => document.addEventListener('click', close), 0);
  }
}

function updateMuteLabel() {
  const btn = document.getElementById('muteBtn');
  if (!btn) return;
  btn.textContent = mutedConversations.has(selectedId)
    ? 'Unmute Notifications'
    : 'Mute Notifications';
}

function handleMoreOption(option) {
  document.getElementById('moreMenu').classList.add('hidden');

  if (option === 'mute') {
    if (mutedConversations.has(selectedId)) {
      mutedConversations.delete(selectedId);
      showToast('🔔 Notifications unmuted.');
    } else {
      mutedConversations.add(selectedId);
      showToast('🔕 Notifications muted for this conversation.');
    }
  } else if (option === 'clear') {
    messageStore[selectedId] = [];
    renderMessages();
    showToast('Chat cleared.');
  }
}

function handleAttach() {
  document.getElementById('fileInput').click();
}

function handleImage() {
  document.getElementById('imageInput').click();
}

function handleEmoji() {
  const emojis = ['😊', '👍', '❤️', '🎉', '😂', '🙏', '✨', '🌟'];
  const picked = emojis[Math.floor(Math.random() * emojis.length)];
  const input = document.getElementById('msgInput');
  input.value += picked;
  input.focus();
  autoResize(input);
}

// ── File / Image Handlers ─────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('fileInput').addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (!file) return;
    const msg = { id: nextMsgId++, fileName: file.name, time: now(), isMe: true };
    if (!messageStore[selectedId]) messageStore[selectedId] = [];
    messageStore[selectedId].push(msg);
    const conv = conversations.find(c => c.id === selectedId);
    if (conv) { conv.lastMessage = `📎 ${file.name}`; conv.time = now(); }
    renderConvList(document.getElementById('convSearch').value);
    document.getElementById('messagesArea').appendChild(buildBubble(msg));
    scrollToBottom();
    showToast(`File attached: ${file.name}`);
    e.target.value = '';
  });

  document.getElementById('imageInput').addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (ev) => {
      const msg = { id: nextMsgId++, imageUrl: ev.target.result, time: now(), isMe: true };
      if (!messageStore[selectedId]) messageStore[selectedId] = [];
      messageStore[selectedId].push(msg);
      const conv = conversations.find(c => c.id === selectedId);
      if (conv) { conv.lastMessage = '📷 Photo'; conv.time = now(); }
      renderConvList(document.getElementById('convSearch').value);
      document.getElementById('messagesArea').appendChild(buildBubble(msg));
      scrollToBottom();
    };
    reader.readAsDataURL(file);
    e.target.value = '';
  });

  // Send button
  document.getElementById('sendBtn').addEventListener('click', sendMessage);

  // Enter to send (Shift+Enter for newline)
  document.getElementById('msgInput').addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      sendMessage();
    }
  });

  // Auto-resize textarea
  document.getElementById('msgInput').addEventListener('input', function() {
    autoResize(this);
  });

  // Conversation search
  document.getElementById('convSearch').addEventListener('input', (e) => {
    renderConvList(e.target.value);
  });

  // Initial render
  renderConvList();
  updateHeader();
  renderMessages();

  // Entrance animations
  requestAnimationFrame(() => {
    document.querySelectorAll('.animate-in').forEach((el, i) => {
      setTimeout(() => el.classList.add('visible'), i * 120);
    });
  });
});
