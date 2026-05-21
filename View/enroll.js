// ── Data ──────────────────────────────────────────────────────────────
const applications = [
  {
    id: 'APP-2026-001',
    childName: 'Emma Doe',
    program: 'Kindergarten 2',
    submittedDate: '2026-04-15',
    status: 'approved',
    progress: 100,
    documents: [
      { name: 'Birth Certificate', status: 'verified' },
      { name: 'Vaccination Records', status: 'verified' },
      { name: 'Medical Form', status: 'verified' },
    ],
    nextStep: 'Complete enrollment payment',
  },
  {
    id: 'APP-2026-002',
    childName: 'Liam Park',
    program: 'Nursery',
    submittedDate: '2026-05-01',
    status: 'pending',
    progress: 60,
    documents: [
      { name: 'Birth Certificate', status: 'verified' },
      { name: 'Vaccination Records', status: 'pending' },
      { name: 'Medical Form', status: 'missing' },
    ],
    nextStep: 'Upload missing vaccination records',
  },
  {
    id: 'APP-2026-003',
    childName: 'Zara Hassan',
    program: 'Kindergarten 1',
    submittedDate: '2026-05-05',
    status: 'under_review',
    progress: 75,
    documents: [
      { name: 'Birth Certificate', status: 'verified' },
      { name: 'Vaccination Records', status: 'verified' },
      { name: 'Medical Form', status: 'under_review' },
    ],
    nextStep: 'Awaiting document review',
  },
];

// ── Status helpers ────────────────────────────────────────────────────
function getStatusInfo(status) {
  switch (status) {
    case 'approved':     return { color: '#10B981', bg: '#D1FAE5', icon: iconCheckCircle(), label: 'Approved' };
    case 'pending':      return { color: '#F59E0B', bg: '#FEF3C7', icon: iconClock(),        label: 'Pending' };
    case 'under_review': return { color: '#1565C0', bg: '#DBEAFE', icon: iconAlertCircle(),  label: 'Under Review' };
    case 'rejected':     return { color: '#DC2626', bg: '#FEE2E2', icon: iconXCircle(),      label: 'Rejected' };
    default:             return { color: '#6B7280', bg: '#F3F4F6', icon: iconFile(),         label: 'Unknown' };
  }
}

function getDocumentStatusInfo(status) {
  switch (status) {
    case 'verified':     return { color: '#10B981', label: 'Verified',     icon: iconCheckCircle() };
    case 'pending':      return { color: '#F59E0B', label: 'Pending',      icon: iconClock() };
    case 'under_review': return { color: '#1565C0', label: 'Under Review', icon: iconAlertCircle() };
    case 'missing':      return { color: '#DC2626', label: 'Missing',      icon: iconXCircle() };
    default:             return { color: '#6B7280', label: 'Unknown',      icon: iconFile() };
  }
}

// ── SVG Icon helpers ──────────────────────────────────────────────────
function svgIcon(pathData, size) {
  size = size || 16;
  return '<svg xmlns="http://www.w3.org/2000/svg" width="' + size + '" height="' + size + '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' + pathData + '</svg>';
}
function iconCheckCircle(s) { s=s||16; return svgIcon('<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>',s); }
function iconClock(s)        { s=s||16; return svgIcon('<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',s); }
function iconAlertCircle(s)  { s=s||16; return svgIcon('<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>',s); }
function iconXCircle(s)      { s=s||16; return svgIcon('<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>',s); }
function iconFile(s)         { s=s||16; return svgIcon('<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>',s); }
function iconEye(s)          { s=s||16; return svgIcon('<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>',s); }
function iconDownload(s)     { s=s||16; return svgIcon('<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>',s); }
function iconCalendar(s)     { s=s||14; return svgIcon('<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',s); }
function iconUser(s)         { s=s||14; return svgIcon('<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',s); }
function iconUpload(s)       { s=s||16; return svgIcon('<polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>',s); }
function iconCreditCard(s)   { s=s||16; return svgIcon('<rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>',s); }
function iconMail(s)         { s=s||16; return svgIcon('<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>',s); }

// ── Toast ─────────────────────────────────────────────────────────────
var toastTimer = null;
function showToast(message, type) {
  type = type || 'success';
  var toast = document.getElementById('toast');
  var iconHtml = type === 'success'
    ? '<span style="color:#10B981;">' + iconCheckCircle(18) + '</span>'
    : '<span style="color:#F59E0B;">' + iconAlertCircle(18) + '</span>';
  toast.innerHTML = iconHtml + ' ' + message;
  toast.className = 'toast visible';
  clearTimeout(toastTimer);
  toastTimer = setTimeout(function() { toast.className = 'toast hidden'; }, 3500);
}

// ── Modal ─────────────────────────────────────────────────────────────
function openModal(html) {
  var overlay = document.getElementById('modal-overlay');
  document.getElementById('modal-body').innerHTML = html;
  overlay.classList.remove('hidden');
  overlay.classList.add('visible');
  document.body.style.overflow = 'hidden';
}

function closeModal() {
  var overlay = document.getElementById('modal-overlay');
  overlay.classList.remove('visible');
  overlay.classList.add('hidden');
  document.body.style.overflow = '';
}

// ── View Details Modal ────────────────────────────────────────────────
function viewDetails(appId) {
  var app = applications.find(function(a) { return a.id === appId; });
  if (!app) return;
  var statusInfo = getStatusInfo(app.status);

  var docsRows = app.documents.map(function(doc) {
    var ds = getDocumentStatusInfo(doc.status);
    var actionBtn = '';
    if (doc.status === 'missing') {
      actionBtn = '<button class="modal-doc-btn" onclick="triggerUpload(\'' + appId + '\', \'' + doc.name + '\')">' + iconUpload(14) + ' Upload</button>';
    } else if (doc.status === 'pending') {
      actionBtn = '<button class="modal-doc-btn modal-doc-btn--secondary" onclick="showToast(\'' + doc.name + ' is awaiting review.\', \'info\')">' + iconClock(14) + ' Pending</button>';
    } else {
      actionBtn = '<span style="color:' + ds.color + '; font-size:0.8125rem; font-weight:600;">' + ds.icon + ' ' + ds.label + '</span>';
    }
    return '<div class="modal-doc-row"><span class="modal-doc-name">' + iconFile(15) + ' ' + doc.name + '</span>' + actionBtn + '</div>';
  }).join('');

  var timeline = buildTimeline(app);

  openModal(
    '<div class="modal-header">' +
      '<div>' +
        '<h2 class="modal-title">' + app.childName + '</h2>' +
        '<span class="modal-subtitle">' + app.id + ' &bull; ' + app.program + '</span>' +
      '</div>' +
      '<span class="status-badge" style="background:' + statusInfo.bg + '; color:' + statusInfo.color + ';">' +
        statusInfo.icon + ' ' + statusInfo.label +
      '</span>' +
    '</div>' +
    '<div class="modal-grid">' +
      '<div>' +
        '<h3 class="modal-section-title">Application Details</h3>' +
        '<div class="modal-info-list">' +
          '<div class="modal-info-row"><span>Application ID</span><strong>' + app.id + '</strong></div>' +
          '<div class="modal-info-row"><span>Child\'s Name</span><strong>' + app.childName + '</strong></div>' +
          '<div class="modal-info-row"><span>Program</span><strong>' + app.program + '</strong></div>' +
          '<div class="modal-info-row"><span>Submitted</span><strong>' + app.submittedDate + '</strong></div>' +
          '<div class="modal-info-row"><span>Progress</span><strong style="color:#E91E8C;">' + app.progress + '%</strong></div>' +
        '</div>' +
        '<h3 class="modal-section-title" style="margin-top:1.5rem;">Required Documents</h3>' +
        '<div class="modal-docs-list">' + docsRows + '</div>' +
      '</div>' +
      '<div>' +
        '<h3 class="modal-section-title">Application Timeline</h3>' +
        '<div class="modal-timeline">' + timeline + '</div>' +
        '<div class="modal-next-step">' +
          '<div class="next-step-label">Next Step</div>' +
          '<div class="next-step-text">' + app.nextStep + '</div>' +
          '<button class="btn-action" style="margin-top:1rem; width:100%; justify-content:center;" onclick="closeModal(); takeAction(\'' + app.id + '\')">' +
            'Take Action' +
          '</button>' +
        '</div>' +
      '</div>' +
    '</div>'
  );
}

function buildTimeline(app) {
  var steps = [
    { label: 'Application Submitted', done: true,                                                  date: app.submittedDate },
    { label: 'Documents Received',    done: app.progress >= 60 },
    { label: 'Under Review',          done: app.status === 'under_review' || app.status === 'approved' },
    { label: 'Decision Made',         done: app.status === 'approved' || app.status === 'rejected' },
  ];
  return steps.map(function(s, i) {
    return '<div class="timeline-item ' + (s.done ? 'done' : '') + '">' +
      '<div class="timeline-dot">' + (s.done ? iconCheckCircle(12) : '<span>' + (i+1) + '</span>') + '</div>' +
      '<div class="timeline-content">' +
        '<div class="timeline-label">' + s.label + '</div>' +
        (s.date ? '<div class="timeline-date">' + s.date + '</div>' : '') +
      '</div>' +
    '</div>';
  }).join('');
}

// ── Download ──────────────────────────────────────────────────────────
function downloadSummary(appId) {
  var app = applications.find(function(a) { return a.id === appId; });
  if (!app) return;

  var lines = [
    'APPLICATION SUMMARY',
    '===================',
    '',
    'Application ID : ' + app.id,
    'Child\'s Name   : ' + app.childName,
    'Program        : ' + app.program,
    'Submitted      : ' + app.submittedDate,
    'Status         : ' + app.status.replace('_', ' ').toUpperCase(),
    'Progress       : ' + app.progress + '%',
    '',
    'REQUIRED DOCUMENTS',
    '------------------',
  ].concat(app.documents.map(function(d) {
    return '  ' + d.name + '  ' + d.status.replace('_', ' ').toUpperCase();
  })).concat([
    '',
    'NEXT STEP',
    '---------',
    '  ' + app.nextStep,
    '',
    'Generated: ' + new Date().toLocaleString(),
  ]);

  var blob = new Blob([lines.join('\n')], { type: 'text/plain' });
  var url  = URL.createObjectURL(blob);
  var a    = document.createElement('a');
  a.href     = url;
  a.download = app.id + '-summary.txt';
  a.click();
  URL.revokeObjectURL(url);
  showToast('Downloaded summary for ' + app.childName);
}

// ── Take Action (context-aware per status) ────────────────────────────
function takeAction(appId) {
  var app = applications.find(function(a) { return a.id === appId; });
  if (!app) return;
  switch (app.status) {
    case 'approved':     openPaymentModal(app);  break;
    case 'pending':      openUploadModal(app);   break;
    case 'under_review': openContactModal(app);  break;
    case 'rejected':     openContactModal(app);  break;
    default:             showToast('No action required right now.');
  }
}

// ── Payment Modal ─────────────────────────────────────────────────────
function openPaymentModal(app) {
  openModal(
    '<div class="modal-header">' +
      '<div>' +
        '<h2 class="modal-title">' + iconCreditCard(20) + ' Complete Payment</h2>' +
        '<span class="modal-subtitle">' + app.childName + ' &bull; ' + app.program + '</span>' +
      '</div>' +
    '</div>' +
    '<div class="modal-form">' +
      '<div class="modal-amount-box"><span>Enrollment Fee</span><strong style="color:#E91E8C; font-size:1.5rem;">$450.00</strong></div>' +
      '<div class="form-field"><label>Cardholder Name</label><input type="text" placeholder="Jane Doe" class="modal-input" id="pay-name" /></div>' +
      '<div class="form-field"><label>Card Number</label><input type="text" placeholder="1234 5678 9012 3456" maxlength="19" class="modal-input" id="pay-card" oninput="this.value=this.value.replace(/[^0-9]/g,\'\').replace(/(.{4})/g,\'$1 \').trim()" /></div>' +
      '<div class="form-field-row">' +
        '<div class="form-field"><label>Expiry</label><input type="text" placeholder="MM / YY" maxlength="7" class="modal-input" id="pay-exp" oninput="formatExpiry(this)" /></div>' +
        '<div class="form-field"><label>CVV</label><input type="text" placeholder="123" maxlength="3" class="modal-input" id="pay-cvv" oninput="this.value=this.value.replace(/[^0-9]/g,\'\')" /></div>' +
      '</div>' +
      '<button class="btn-action" style="width:100%; justify-content:center; margin-top:0.5rem;" onclick="submitPayment(\'' + app.id + '\')">' + iconCreditCard(16) + ' Pay $450.00</button>' +
      '<p style="text-align:center; font-size:0.8rem; color:#9ca3af; margin-top:0.75rem;">🔒 Secured with 256-bit SSL encryption</p>' +
    '</div>'
  );
}

function formatExpiry(input) {
  var v = input.value.replace(/[^0-9]/g, '');
  if (v.length >= 3) v = v.slice(0,2) + ' / ' + v.slice(2,4);
  input.value = v;
}

function submitPayment(appId) {
  var name = document.getElementById('pay-name').value.trim();
  var card = document.getElementById('pay-card').value.trim();
  var exp  = document.getElementById('pay-exp').value.trim();
  var cvv  = document.getElementById('pay-cvv').value.trim();

  if (!name || card.replace(/\s/g,'').length < 16 || exp.length < 7 || cvv.length < 3) {
    showToast('Please fill in all payment fields correctly.', 'warning');
    return;
  }

  closeModal();
  showToast('Payment successful! Enrollment confirmed. ✅');

  var app = applications.find(function(a) { return a.id === appId; });
  if (app) app.nextStep = 'Enrollment complete — welcome aboard! 🎉';
  rerenderCard(appId);
}

// ── Upload Modal ──────────────────────────────────────────────────────
function openUploadModal(app) {
  var missing = app.documents.filter(function(d) { return d.status === 'missing' || d.status === 'pending'; });

  var fileInputs = missing.map(function(doc) {
    var safeId = doc.name.replace(/\s/g, '-');
    return '<div class="form-field">' +
      '<label>' + doc.name + ' <span style="color:#DC2626; font-size:0.75rem;">(' + doc.status + ')</span></label>' +
      '<div class="file-drop-zone" id="drop-' + safeId + '"' +
        ' onclick="document.getElementById(\'file-' + safeId + '\').click()"' +
        ' ondragover="event.preventDefault(); this.classList.add(\'drag-over\')"' +
        ' ondragleave="this.classList.remove(\'drag-over\')"' +
        ' ondrop="handleDrop(event, \'' + doc.name + '\', \'' + app.id + '\')">' +
        iconUpload(24) +
        '<span>Click or drag file here</span>' +
        '<span style="font-size:0.75rem; color:#9ca3af;">PDF, JPG, PNG — max 5MB</span>' +
      '</div>' +
      '<input type="file" id="file-' + safeId + '" accept=".pdf,.jpg,.jpeg,.png" style="display:none"' +
        ' onchange="handleFileSelect(this, \'' + doc.name + '\', \'' + app.id + '\')" />' +
    '</div>';
  }).join('');

  openModal(
    '<div class="modal-header">' +
      '<div>' +
        '<h2 class="modal-title">' + iconUpload(20) + ' Upload Documents</h2>' +
        '<span class="modal-subtitle">' + app.childName + ' &bull; ' + app.program + '</span>' +
      '</div>' +
    '</div>' +
    '<div class="modal-form">' +
      fileInputs +
      '<button class="btn-action" style="width:100%; justify-content:center; margin-top:0.5rem;" onclick="submitUploads(\'' + app.id + '\')">Submit Documents</button>' +
    '</div>'
  );
}

function triggerUpload(appId, docName) {
  var app = applications.find(function(a) { return a.id === appId; });
  if (app) openUploadModal(app);
}

function handleDrop(event, docName, appId) {
  event.preventDefault();
  var zoneId = 'drop-' + docName.replace(/\s/g, '-');
  var zone = document.getElementById(zoneId);
  zone.classList.remove('drag-over');
  var file = event.dataTransfer.files[0];
  if (file) markFileReady(zone, file, docName);
}

function handleFileSelect(input, docName, appId) {
  var file = input.files[0];
  if (!file) return;
  var zoneId = 'drop-' + docName.replace(/\s/g, '-');
  var zone = document.getElementById(zoneId);
  markFileReady(zone, file, docName);
}

function markFileReady(zone, file, docName) {
  if (file.size > 5 * 1024 * 1024) {
    showToast('File exceeds 5MB limit.', 'warning');
    return;
  }
  zone.classList.add('file-ready');
  zone.innerHTML = iconCheckCircle(22) + ' <span style="font-weight:700;">' + file.name + '</span><span style="font-size:0.75rem; color:#6b7280;">' + (file.size/1024).toFixed(0) + ' KB — ready to upload</span>';
  zone.dataset.ready = 'true';
}

function submitUploads(appId) {
  var zones = document.querySelectorAll('[id^="drop-"]');
  var allReady = Array.prototype.every.call(zones, function(z) { return z.dataset.ready === 'true'; });
  if (!allReady) {
    showToast('Please attach all required files before submitting.', 'warning');
    return;
  }

  var app = applications.find(function(a) { return a.id === appId; });
  if (app) {
    app.documents.forEach(function(d) { if (d.status === 'missing' || d.status === 'pending') d.status = 'under_review'; });
    app.progress = Math.min(app.progress + 20, 90);
    app.nextStep = 'Documents submitted — awaiting review';
  }

  closeModal();
  showToast('Documents uploaded successfully! Under review now.');
  rerenderCard(appId);
}

// ── Contact Modal ─────────────────────────────────────────────────────
function openContactModal(app) {
  var isRejected = app.status === 'rejected';
  var alertBox = isRejected
    ? '<div class="modal-alert">Your application was not approved. You may appeal or ask for more details below.</div>'
    : '<div class="modal-info-note">Your application is currently under review. Send a message if you have questions.</div>';

  openModal(
    '<div class="modal-header">' +
      '<div>' +
        '<h2 class="modal-title">' + iconMail(20) + ' Contact Admissions</h2>' +
        '<span class="modal-subtitle">' + app.childName + ' &bull; ' + app.id + '</span>' +
      '</div>' +
    '</div>' +
    '<div class="modal-form">' +
      alertBox +
      '<div class="form-field"><label>Your Name</label><input type="text" class="modal-input" placeholder="Jane Doe" id="contact-name" /></div>' +
      '<div class="form-field"><label>Email Address</label><input type="email" class="modal-input" placeholder="jane@example.com" id="contact-email" /></div>' +
      '<div class="form-field"><label>Subject</label><input type="text" class="modal-input" id="contact-subject" value="' + (isRejected ? 'Appeal for ' : 'Query regarding ') + app.id + '" /></div>' +
      '<div class="form-field"><label>Message</label><textarea class="modal-input modal-textarea" rows="4" id="contact-message" placeholder="Type your message here..."></textarea></div>' +
      '<button class="btn-action" style="width:100%; justify-content:center;" onclick="submitContact(\'' + app.id + '\')">' + iconMail(16) + ' Send Message</button>' +
    '</div>'
  );
}

function submitContact(appId) {
  var name    = document.getElementById('contact-name').value.trim();
  var email   = document.getElementById('contact-email').value.trim();
  var message = document.getElementById('contact-message').value.trim();

  if (!name || !email || !message) {
    showToast('Please fill in all fields.', 'warning');
    return;
  }
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    showToast('Please enter a valid email address.', 'warning');
    return;
  }

  closeModal();
  showToast('Message sent! Admissions will reply within 1–2 business days.');
}

// ── Re-render a single card after data change ─────────────────────────
function rerenderCard(appId) {
  var list  = document.getElementById('applications-list');
  var cards = list.querySelectorAll('.app-card');
  var idx   = applications.findIndex(function(a) { return a.id === appId; });
  if (idx === -1 || !cards[idx]) return;

  var tmp = document.createElement('div');
  tmp.innerHTML = renderCard(applications[idx]);
  var newCard = tmp.firstElementChild;
  newCard.classList.add('visible');
  list.replaceChild(newCard, cards[idx]);
}

// ── Card renderer ─────────────────────────────────────────────────────
function renderCard(app) {
  var statusInfo = getStatusInfo(app.status);

  var docsHtml = app.documents.map(function(doc) {
    var ds = getDocumentStatusInfo(doc.status);
    return '<div class="doc-item" style="border-color:' + ds.color + '30; background:' + ds.color + '08;">' +
      '<div class="doc-item-header"><span style="color:' + ds.color + ';">' + ds.icon + '</span><span class="doc-name">' + doc.name + '</span></div>' +
      '<span class="doc-status-text" style="color:' + ds.color + ';">' + doc.status.replace('_',' ') + '</span>' +
    '</div>';
  }).join('');

  return '<div class="app-card animate-in">' +
    '<div class="card-header">' +
      '<div>' +
        '<div class="card-title-row">' +
          '<h3 class="child-name">' + app.childName + '</h3>' +
          '<span class="status-badge" style="background:' + statusInfo.bg + '; color:' + statusInfo.color + ';">' + statusInfo.icon + ' ' + statusInfo.label + '</span>' +
        '</div>' +
        '<div class="card-meta">' +
          '<span class="meta-item">' + iconFile(14) + ' <span style="color:#6b7280;">' + app.id + '</span></span>' +
          '<span class="meta-item">' + iconCalendar(14) + ' <span style="color:#6b7280;">Submitted: ' + app.submittedDate + '</span></span>' +
          '<span class="meta-item">' + iconUser(14) + ' <span style="color:#6b7280;">' + app.program + '</span></span>' +
        '</div>' +
      '</div>' +
      '<div class="card-actions">' +
        '<button class="btn-outline" onclick="viewDetails(\'' + app.id + '\')">' + iconEye(16) + ' View Details</button>' +
        '<button class="btn-outline" onclick="downloadSummary(\'' + app.id + '\')">' + iconDownload(16) + ' Download</button>' +
      '</div>' +
    '</div>' +
    '<div class="progress-section">' +
      '<div class="progress-header"><span class="progress-label">Application Progress</span><span class="progress-pct">' + app.progress + '%</span></div>' +
      '<div class="progress-bar-bg"><div class="progress-bar-fill" style="width:' + app.progress + '%;"></div></div>' +
    '</div>' +
    '<div class="documents-section">' +
      '<h4 class="documents-title">Required Documents</h4>' +
      '<div class="documents-grid">' + docsHtml + '</div>' +
    '</div>' +
    '<div class="next-step">' +
      '<div class="next-step-info">' +
        '<span style="color:#E91E8C;">' + iconAlertCircle(20) + '</span>' +
        '<div><div class="next-step-label">Next Step</div><div class="next-step-text">' + app.nextStep + '</div></div>' +
      '</div>' +
      '<button class="btn-action" onclick="takeAction(\'' + app.id + '\')">Take Action</button>' +
    '</div>' +
  '</div>';
}

// ── Intersection Observer for scroll animations ───────────────────────
function observeAnimations() {
  var observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
      if (entry.isIntersecting) { entry.target.classList.add('visible'); observer.unobserve(entry.target); }
    });
  }, { threshold: 0.1 });
  document.querySelectorAll('.animate-in').forEach(function(el) { observer.observe(el); });
}

// ── Init ──────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
  var list = document.getElementById('applications-list');
  list.innerHTML = applications.map(renderCard).join('');
  observeAnimations();

  document.getElementById('modal-close').addEventListener('click', closeModal);
  document.getElementById('modal-overlay').addEventListener('click', function(e) {
    if (e.target === document.getElementById('modal-overlay')) closeModal();
  });
  document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeModal(); });
});