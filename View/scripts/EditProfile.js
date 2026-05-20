/* ──────────────────────────────────────────────────────────────
   EditProfile.js – Profile management with dynamic data loading
   ────────────────────────────────────────────────────────────── */

// User data passed from PHP
// const userData is defined in EditProfile.php

let originalData = { ...userData };

// ── Initialize form on page load ──
document.addEventListener('DOMContentLoaded', () => {
  initializeProfile();
  setupEventListeners();
  loadProfileData();
});

// ── Initialize profile display ──
function initializeProfile() {
  // Update sidebar with user data
  document.getElementById('sidebarName').textContent = userData.displayName;
  
  // Set role dropdown
  const roleSelect = document.getElementById('role');
  if (roleSelect) {
    roleSelect.value = userData.role.toLowerCase();
    roleSelect.disabled = true; // Prevent role changes
  }
}

// ── Load additional profile data ──
function loadProfileData() {
  // Fetch user data from controller
  fetch('../Controller/EditProfileController.php?action=get', {
    method: 'GET',
    credentials: 'include'
  })
  .then(res => res.json())
  .then(data => {
    if (data.success && data.data) {
      // Update userData with fetched data
      const fetchedData = data.data;
      userData.userID = fetchedData.userID || userData.userID;
      userData.firstName = fetchedData.firstname || fetchedData.firstName || '';
      userData.lastName = fetchedData.Lastname || fetchedData.lastName || '';
      userData.displayName = (userData.firstName + ' ' + userData.lastName).trim();
      userData.email = fetchedData.email || '';
      userData.phoneNumber = fetchedData.phone || '';
      userData.experience = fetchedData.exprience || fetchedData.experience || 0;
      userData.qualifications = fetchedData.qualifications || '';
      userData.specialization = fetchedData.specialization || '';
      userData.childrenCount = fetchedData.childrenCount || 0;
      
      // Update form fields with fetched data
      document.getElementById('firstName').value = userData.firstName || '';
      document.getElementById('lastName').value = userData.lastName || '';
      document.getElementById('displayName').value = userData.displayName || '';
      document.getElementById('email').value = userData.email || '';
      
      // Update sidebar
      document.getElementById('sidebarName').textContent = userData.displayName || 'User';
      document.getElementById('sidebarRole').textContent = userData.role;
      document.getElementById('sidebarEmail').textContent = '✉️ ' + (userData.email || 'No email');
      
      // Role-specific fields
      if (userData.role === 'Teacher') {
        const phoneField = document.getElementById('phone');
        const classField = document.getElementById('classField');
        const bioField = document.getElementById('bio');
        const experienceField = document.getElementById('experience');
        
        if (phoneField) {
          phoneField.value = userData.phoneNumber || '';
        }
        if (classField) {
          classField.value = userData.specialization || '';
        }
        if (bioField) {
          bioField.value = userData.qualifications || '';
        }
        if (experienceField) {
          experienceField.value = userData.experience || 0;
        }
        
        // Update sidebar extra info
        document.getElementById('sidebarExtra').textContent = '🏆 ' + (userData.experience || 0) + ' Years Experience';
      } else if (userData.role === 'Parent') {
        document.getElementById('sidebarExtra').textContent = '👨‍👩‍👧 ' + (userData.childrenCount || 0) + ' Children';
      }
      
      // Store original for reset
      originalData = { ...userData };
    } else {
      console.error('Failed to load profile data:', data.message);
      showToast('❌ Failed to load profile data', false);
    }
  })
  .catch(err => {
    console.error('Error loading profile:', err);
    showToast('❌ Failed to load profile data', false);
  });
}

// ── Setup event listeners ──
function setupEventListeners() {
  // Tab switching
  const tabButtons = document.querySelectorAll('.ep-tab');
  tabButtons.forEach(btn => {
    btn.addEventListener('click', () => switchTab(btn.dataset.tab));
  });
  
  // Avatar upload
  const avatarUpload = document.getElementById('avatarUpload');
  if (avatarUpload) {
    avatarUpload.addEventListener('change', handleAvatarUpload);
  }
  
  // Delete account button
  const deleteBtn = document.getElementById('deleteAccountBtn');
  if (deleteBtn) {
    deleteBtn.addEventListener('click', handleDeleteAccount);
  }
  
  // Mobile menu
  const hamburger = document.getElementById('hamburger');
  const mobileMenu = document.getElementById('mobileMenu');
  if (hamburger && mobileMenu) {
    hamburger.addEventListener('click', () => {
      mobileMenu.classList.toggle('active');
      hamburger.setAttribute('aria-expanded', 
        hamburger.getAttribute('aria-expanded') === 'true' ? 'false' : 'true');
    });
  }
  
  // More dropdown
  const moreBtn = document.getElementById('moreBtn');
  const moreMenu = document.getElementById('moreMenu');
  if (moreBtn && moreMenu) {
    moreBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      moreMenu.classList.toggle('active');
      moreBtn.setAttribute('aria-expanded',
        moreBtn.getAttribute('aria-expanded') === 'true' ? 'false' : 'true');
    });
    
    document.addEventListener('click', () => {
      moreMenu.classList.remove('active');
      moreBtn.setAttribute('aria-expanded', 'false');
    });
  }
}

// ── Tab switching ──
function switchTab(tabName) {
  // Hide all panels
  document.querySelectorAll('.ep-panel').forEach(panel => {
    panel.classList.remove('active');
  });
  
  // Remove active class from all tabs
  document.querySelectorAll('.ep-tab').forEach(tab => {
    tab.classList.remove('active');
  });
  
  // Show selected panel
  const panel = document.getElementById('panel-' + tabName);
  if (panel) {
    panel.classList.add('active');
  }
  
  // Mark tab as active
  event.target.classList.add('active');
}

// ── Save personal info ──
function savePersonal() {
  const formData = {
    firstName: document.getElementById('firstName')?.value || '',
    lastName: document.getElementById('lastName')?.value || '',
    displayName: document.getElementById('displayName')?.value || '',
    email: document.getElementById('email')?.value || '',
    specialization: document.getElementById('classField')?.value || '',
    qualifications: document.getElementById('bio')?.value || ''
  };
  
  // Add teacher-specific fields if applicable
  if (userData.role === 'Teacher') {
    formData.phoneNumber = document.getElementById('phone')?.value || '';
    formData.experience = document.getElementById('experience')?.value || 0;
  }
  
  // Validate required fields
  if (!formData.firstName || !formData.lastName || !formData.email) {
    showToast('❌ Please fill in all required fields', false);
    return;
  }
  
  // Send to controller
  fetch('../Controller/EditProfileController.php?action=update', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(formData),
    credentials: 'include'
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      showToast('✅ Profile updated successfully!', true);
      // Update userData
      userData.firstName = formData.firstName;
      userData.lastName = formData.lastName;
      userData.displayName = formData.displayName;
      userData.email = formData.email;
      userData.specialization = formData.specialization;
      userData.qualifications = formData.qualifications;
      
      if (userData.role === 'Teacher') {
        userData.phoneNumber = formData.phoneNumber;
        userData.experience = formData.experience;
      }
      
      // Update sidebar
      document.getElementById('sidebarName').textContent = userData.displayName;
      
      // Store original for reset
      originalData = { ...userData };
    } else {
      showToast('❌ ' + (data.message || 'Failed to update profile'), false);
    }
  })
  .catch(err => {
    console.error(err);
    showToast('❌ Network error', false);
  });
}

// ── Reset personal form ──
function resetPersonal() {
  document.getElementById('firstName').value = originalData.firstName || '';
  document.getElementById('lastName').value = originalData.lastName || '';
  document.getElementById('displayName').value = originalData.displayName || '';
  document.getElementById('email').value = originalData.email || '';
  document.getElementById('classField').value = originalData.specialization || '';
  document.getElementById('bio').value = originalData.qualifications || '';
  
  if (originalData.role === 'Teacher') {
    const phoneField = document.getElementById('phone');
    const experienceField = document.getElementById('experience');
    if (phoneField) phoneField.value = originalData.phoneNumber || '';
    if (experienceField) experienceField.value = originalData.experience || 0;
  }
  
  showToast('↩️ Changes discarded', true);
}

// ── Handle avatar upload ──
function handleAvatarUpload(e) {
  const file = e.target.files[0];
  if (!file) return;
  
  // Check file size (max 5MB)
  if (file.size > 5 * 1024 * 1024) {
    showToast('❌ File size must be less than 5MB', false);
    return;
  }
  
  // Check file type
  if (!file.type.startsWith('image/')) {
    showToast('❌ File must be an image', false);
    return;
  }
  
  // Read and display preview
  const reader = new FileReader();
  reader.onload = (event) => {
    const img = document.getElementById('avatarImg');
    if (img) {
      img.src = event.target.result;
      showToast('✅ Avatar updated', true);
    }
  };
  reader.readAsDataURL(file);
}

// ── Delete account ──
function handleDeleteAccount() {
  const confirmed = confirm(
    '⚠️ Are you absolutely sure?\n\n' +
    'This will permanently delete your account and all associated data.\n' +
    'This action CANNOT be undone.\n\n' +
    'Type "DELETE" to confirm:'
  );
  
  if (!confirmed) return;
  
  // Additional confirmation
  const userInput = prompt('Type DELETE to confirm account deletion:');
  if (userInput !== 'DELETE') {
    showToast('❌ Deletion cancelled', false);
    return;
  }
  
  // Send deletion request
  fetch('../Controller/EditProfileController.php?action=delete', {
    method: 'POST',
    credentials: 'include'
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      showToast('✅ Account deleted. Redirecting...', true);
      setTimeout(() => {
        window.location.href = '../../Controller/Login.php';
      }, 2000);
    } else {
      showToast('❌ ' + (data.message || 'Failed to delete account'), false);
    }
  })
  .catch(err => {
    console.error(err);
    showToast('❌ Network error', false);
  });
}

// ── Toast notification ──
function showToast(message, success = true) {
  const toast = document.getElementById('toast');
  if (!toast) {
    // Create toast if it doesn't exist
    const newToast = document.createElement('div');
    newToast.id = 'toast';
    newToast.className = 'ep-toast';
    document.body.appendChild(newToast);
  }
  
  const toastEl = document.getElementById('toast');
  toastEl.textContent = message;
  toastEl.className = success ? 'ep-toast ep-toast--success' : 'ep-toast ep-toast--error';
  toastEl.style.display = 'block';
  
  setTimeout(() => {
    toastEl.style.display = 'none';
  }, 4000);
}
