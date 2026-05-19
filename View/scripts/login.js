/* ══════════════════════════════════════════
   WELLUCATION – LOGIN PAGE JAVASCRIPT
   ══════════════════════════════════════════ */

(function () {
  "use strict";

  /* ─────────────────────────────────────────
     NAVBAR – Hamburger & Dropdown
  ───────────────────────────────────────── */
  const hamburger = document.getElementById("hamburger");
  const mobileMenu = document.getElementById("mobileMenu");
  const iconMenu = hamburger?.querySelector(".icon-menu");
  const iconClose = hamburger?.querySelector(".icon-close");

  hamburger?.addEventListener("click", () => {
    const isOpen = mobileMenu.classList.toggle("open");
    hamburger.setAttribute("aria-expanded", String(isOpen));
    iconMenu?.classList.toggle("hidden", isOpen);
    iconClose?.classList.toggle("hidden", !isOpen);
    mobileMenu.setAttribute("aria-hidden", String(!isOpen));
  });

  // "More" dropdown
  const moreBtn = document.getElementById("moreBtn");
  const moreDropdown = document.getElementById("moreDropdown");

  moreBtn?.addEventListener("click", (e) => {
    e.stopPropagation();
    const isOpen = moreDropdown.classList.toggle("open");
    moreBtn.setAttribute("aria-expanded", String(isOpen));
  });

  // Close dropdown on outside click
  document.addEventListener("click", (e) => {
    if (!moreDropdown?.contains(e.target)) {
      moreDropdown?.classList.remove("open");
      moreBtn?.setAttribute("aria-expanded", "false");
    }
  });

  // Close dropdown on Escape
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      moreDropdown?.classList.remove("open");
      moreBtn?.setAttribute("aria-expanded", "false");
      mobileMenu?.classList.remove("open");
      hamburger?.setAttribute("aria-expanded", "false");
      iconMenu?.classList.remove("hidden");
      iconClose?.classList.add("hidden");
    }
  });

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
    e.preventDefault();

    const email = document.getElementById("loginEmail");
    const password = document.getElementById("loginPassword");

    if (!email.value.trim()) {
      setFieldError(email, "Please enter your email address.");
      return;
    }
    if (!isValidEmail(email.value)) {
      setFieldError(email, "Please enter a valid email address.");
      return;
    }
    if (!password.value) {
      setFieldError(password, "Please enter your password.");
      return;
    }
    if (password.value.length < 6) {
      setFieldError(password, "Password must be at least 6 characters.");
      return;
    }

    // Simulate login success
    showToast("✅ Login successful! Welcome back.", "success");
    loginForm.reset();
  });

  /* ─────────────────────────────────────────
     REGISTER FORM
  ───────────────────────────────────────── */
  const registerForm = document.getElementById("registerForm");

  registerForm?.addEventListener("submit", (e) => {
    e.preventDefault();

    const name = document.getElementById("regName");
    const email = document.getElementById("regEmail");
    const password = document.getElementById("regPassword");
    const confirm = document.getElementById("regConfirm");
    const agree = document.getElementById("agreeTerms");

    if (!name.value.trim()) {
      setFieldError(name, "Please enter your full name.");
      return;
    }
    if (!email.value.trim() || !isValidEmail(email.value)) {
      setFieldError(email, "Please enter a valid email address.");
      return;
    }
    if (!password.value || password.value.length < 6) {
      setFieldError(password, "Password must be at least 6 characters.");
      return;
    }
    if (password.value !== confirm.value) {
      setFieldError(confirm, "Passwords do not match!");
      return;
    }
    if (!agree.checked) {
      showToast("Please accept the Terms & Conditions to continue.", "error");
      return;
    }

    // Simulate registration success
    showToast(
      "🎉 Account created successfully! Welcome to Wellucation.",
      "success",
    );
    registerForm.reset();
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
