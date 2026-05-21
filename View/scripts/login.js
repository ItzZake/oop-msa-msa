/* ══════════════════════════════════════════
   WELLUCATION – LOGIN PAGE JAVASCRIPT
   ══════════════════════════════════════════ */

(function () {
  "use strict";

  /* NOTE: Navbar functionality is now handled by navbar.php */

  /* ─────────────────────────────────────────
     TABS – Login / Register
  ───────────────────────────────────────── */
  const tabBtns = document.querySelectorAll(".tab-btn");
  const tabPanels = document.querySelectorAll(".tab-panel");

  tabBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      const target = btn.dataset.tab;

      // Update buttons
      tabBtns.forEach((b) => b.classList.remove("tab-btn--active"));
      btn.classList.add("tab-btn--active");

      // Update panels
      tabPanels.forEach((panel) => {
        panel.classList.toggle(
          "tab-panel--active",
          panel.id === `panel${capitalize(target)}`,
        );
      });
    });
  });

  function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

  /* ─────────────────────────────────────────
     PASSWORD VISIBILITY TOGGLES
  ───────────────────────────────────────── */
  function makeToggle(btnId, inputId) {
    const btn = document.getElementById(btnId);
    const input = document.getElementById(inputId);
    if (!btn || !input) return;

    btn.addEventListener("click", () => {
      const isText = input.type === "text";
      input.type = isText ? "password" : "text";
      btn.querySelector(".eye-on")?.classList.toggle("hidden", !isText);
      btn.querySelector(".eye-off")?.classList.toggle("hidden", isText);
      btn.setAttribute(
        "aria-label",
        isText ? "Show password" : "Hide password",
      );
    });
  }

  makeToggle("toggleLoginPw", "loginPassword");
  makeToggle("toggleRegPw", "regPassword");

  /* ─────────────────────────────────────────
     TOAST NOTIFICATION
  ───────────────────────────────────────── */
  const toastEl = document.getElementById("toast");
  let toastTimer = null;

  function showToast(message, type = "success") {
    if (!toastEl) return;
    clearTimeout(toastTimer);

    toastEl.textContent = message;
    toastEl.className = `toast toast--${type}`;

    // Force reflow so CSS transition fires
    void toastEl.offsetWidth;
    toastEl.classList.add("toast--show");

    toastTimer = setTimeout(() => {
      toastEl.classList.remove("toast--show");
    }, 3500);
  }

  /* ─────────────────────────────────────────
     FORM VALIDATION HELPERS
  ───────────────────────────────────────── */
  function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.trim());
  }

  function setFieldError(inputEl, message) {
    inputEl.style.borderColor = "#dc2626";
    inputEl.style.boxShadow = "0 0 0 3px rgba(220,38,38,.15)";
    showToast(message, "error");
  }

  function clearFieldError(inputEl) {
    inputEl.style.borderColor = "";
    inputEl.style.boxShadow = "";
  }

  // Auto-clear error styling on input
  document.querySelectorAll(".field__input").forEach((input) => {
    input.addEventListener("input", () => clearFieldError(input));
  });

  /* ─────────────────────────────────────────
     LOGIN FORM
  ───────────────────────────────────────── */
  const loginForm = document.getElementById("loginForm");

  loginForm?.addEventListener("submit", (e) => {
    // Validate but allow submission to controller
    const email = document.getElementById("loginEmail");
    const password = document.getElementById("loginPassword");

    if (!email.value.trim()) {
      e.preventDefault();
      setFieldError(email, "Please enter your email address.");
      return;
    }
    if (!isValidEmail(email.value)) {
      e.preventDefault();
      setFieldError(email, "Please enter a valid email address.");
      return;
    }
    if (!password.value) {
      e.preventDefault();
      setFieldError(password, "Please enter your password.");
      return;
    }
    if (password.value.length < 6) {
      e.preventDefault();
      setFieldError(password, "Password must be at least 6 characters.");
      return;
    }

    // Allow form to submit to server
  });

  /* ─────────────────────────────────────────
     REGISTER FORM
  ───────────────────────────────────────── */
  const registerForm = document.getElementById("registerForm");

  registerForm?.addEventListener("submit", (e) => {
    // Validate but allow submission to controller
    const firstName = document.getElementById("regFirstName");
    const lastName = document.getElementById("regLastName");
    const email = document.getElementById("regEmail");
    const role = document.getElementById("regRole");
    const password = document.getElementById("regPassword");
    const confirm = document.getElementById("regConfirm");
    const agree = document.getElementById("agreeTerms");

    if (!firstName.value.trim()) {
      e.preventDefault();
      setFieldError(firstName, "Please enter your first name.");
      return;
    }
    if (!lastName.value.trim()) {
      e.preventDefault();
      setFieldError(lastName, "Please enter your last name.");
      return;
    }
    if (!email.value.trim() || !isValidEmail(email.value)) {
      e.preventDefault();
      setFieldError(email, "Please enter a valid email address.");
      return;
    }
    if (!role.value) {
      e.preventDefault();
      showToast("Please select an account type.", "error");
      return;
    }
    if (!password.value || password.value.length < 6) {
      e.preventDefault();
      setFieldError(password, "Password must be at least 6 characters.");
      return;
    }
    if (password.value !== confirm.value) {
      e.preventDefault();
      setFieldError(confirm, "Passwords do not match!");
      return;
    }
    if (!agree.checked) {
      e.preventDefault();
      showToast("Please accept the Terms & Conditions to continue.", "error");
      return;
    }

    // Allow form to submit to server
  });

  /* ─────────────────────────────────────────
     SCROLL-REVEAL  (same logic as about page)
  ───────────────────────────────────────── */
  const revealEls = document.querySelectorAll(".reveal");
  if ("IntersectionObserver" in window) {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("visible");
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.15 },
    );
    revealEls.forEach((el) => observer.observe(el));
  } else {
    revealEls.forEach((el) => el.classList.add("visible"));
  }
})();
