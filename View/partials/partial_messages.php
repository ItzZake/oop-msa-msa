<section class="page-hero">
  <div class="page-hero__content">
    <span class="page-badge">💬 Messages</span>
    <h1 class="page-hero__title">Stay Connected</h1>
    <p class="page-hero__subtitle">Chat with teachers and staff in real-time.</p>
  </div>
</section>

<section class="section section--gray">
  <div class="container">
    <div class="chat-layout">
      <aside class="chat-sidebar">
        <div class="chat-sidebar-header">
          <div class="search-wrap">
            <span class="search-icon">🔍</span>
            <input placeholder="Search conversations..." class="form-input chat-search" oninput="filterChats(this.value)">
          </div>
        </div>
        <div class="chat-list" id="chatList">
          <div class="chat-item active" onclick="selectChat(this,'Ms. Jennifer Lee','👩‍🏫','Kindergarten 2 Teacher',true)">
            <div class="chat-item-avatar">👩‍🏫<span class="online-indicator"></span></div>
            <div class="chat-item-body">
              <div class="chat-item-head"><span class="chat-item-name">Ms. Jennifer Lee</span><span class="chat-item-time">10:30 AM</span></div>
              <div class="chat-item-role">Kindergarten 2 Teacher</div>
              <div class="chat-item-snippet">Emma did wonderfully in art class!</div>
            </div>
            <span class="chat-item-badge">2</span>
          </div>
          <div class="chat-item" onclick="selectChat(this,'Admin Office','🏢','Administration',false)">
            <div class="chat-item-avatar">🏢</div>
            <div class="chat-item-body">
              <div class="chat-item-head"><span class="chat-item-name">Admin Office</span><span class="chat-item-time">Yesterday</span></div>
              <div class="chat-item-role">Administration</div>
              <div class="chat-item-snippet">Your payment has been received.</div>
            </div>
          </div>
          <div class="chat-item" onclick="selectChat(this,'Mr. David Chen','👨‍🏫','Music Teacher',true)">
            <div class="chat-item-avatar">👨‍🏫<span class="online-indicator"></span></div>
            <div class="chat-item-body">
              <div class="chat-item-head"><span class="chat-item-name">Mr. David Chen</span><span class="chat-item-time">2 days ago</span></div>
              <div class="chat-item-role">Music Teacher</div>
              <div class="chat-item-snippet">Liam is showing great progress!</div>
            </div>
            <span class="chat-item-badge">1</span>
          </div>
          <div class="chat-item" onclick="selectChat(this,'Nurse Sarah','👩‍⚕️','School Nurse',false)">
            <div class="chat-item-avatar">👩‍⚕️</div>
            <div class="chat-item-body">
              <div class="chat-item-head"><span class="chat-item-name">Nurse Sarah</span><span class="chat-item-time">3 days ago</span></div>
              <div class="chat-item-role">School Nurse</div>
              <div class="chat-item-snippet">Reminder about vaccination records</div>
            </div>
          </div>
        </div>
      </aside>

      <main class="chat-main">
        <div class="chat-header">
          <div class="chat-header-left">
            <div id="chatHeaderAvatar" class="chat-header-avatar">👩‍🏫<span class="online-indicator small"></span></div>
            <div class="chat-header-meta"><div id="chatHeaderName" class="chat-header-name">Ms. Jennifer Lee</div><div id="chatHeaderRole" class="chat-header-role">Kindergarten 2 Teacher</div></div>
          </div>
          <div class="chat-header-actions">
            <button class="icon-btn" title="Call">📞</button>
            <button class="icon-btn" title="Video">📹</button>
            <button class="icon-btn" title="More">⋯</button>
          </div>
        </div>

        <div class="chat-messages" id="chatMessages">
          <div class="message them"><div class="message-bubble">Good morning! Just wanted to let you know Emma had a great day today.</div><div class="message-time">9:45 AM</div></div>
          <div class="message me"><div class="message-bubble">That's wonderful to hear! Thank you for the update. 😊</div><div class="message-time">10:15 AM</div></div>
          <div class="message them"><div class="message-bubble">She participated actively in circle time and made a beautiful painting during art class!</div><div class="message-time">10:20 AM</div></div>
          <div class="message me"><div class="message-bubble">I'd love to see the painting! Can you share a photo?</div><div class="message-time">10:25 AM</div></div>
          <div class="message them"><div class="message-bubble">Emma did wonderfully in art class today! 🎨 She has a real talent for creative expression.</div><div class="message-time">10:30 AM</div></div>
        </div>

        <div class="chat-input-area">
          <form onsubmit="sendMessage(event)" class="chat-input-form">
            <button type="button" class="icon-btn" title="Attach">📎</button>
            <button type="button" class="icon-btn" title="Image">🖼️</button>
            <input id="msgInput" class="form-input" placeholder="Type your message...">
            <button type="button" class="icon-btn" title="Emoji">😊</button>
            <button type="submit" class="btn btn-primary chat-send">➤</button>
          </form>
        </div>
      </main>
    </div>
  </div>
</section>
