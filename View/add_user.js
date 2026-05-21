"use strict";

/* ══════════════════════════════════════════
   ADD USER PAGE — Main Script
══════════════════════════════════════════ */

/* NOTE: Navbar functionality is now handled by navbar.php */

document.addEventListener("DOMContentLoaded", function () {
  initForm();
});

/* ══════════════════════════════════════════
   FORM HANDLING
══════════════════════════════════════════ */

function initForm() {
  const form = document.querySelector("form");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    if (!validateForm()) {
      e.preventDefault();
    }
  });
}

function validateForm() {
  const nameInput = document.querySelector('input[name="name"]');
  const emailInput = document.querySelector('input[name="email"]');
  const roleSelect = document.querySelector('select[name="role"]');

  let isValid = true;

  // Clear previous errors
  clearFormErrors();

  // Validate name
  if (!nameInput.value.trim()) {
    showFieldError(nameInput, "Name is required");
    isValid = false;
  } else if (nameInput.value.trim().length < 2) {
    showFieldError(nameInput, "Name must be at least 2 characters");
    isValid = false;
  }

  // Validate email
  if (!emailInput.value.trim()) {
    showFieldError(emailInput, "Email is required");
    isValid = false;
  } else if (!isValidEmail(emailInput.value)) {
    showFieldError(emailInput, "Please enter a valid email address");
    isValid = false;
  }

  // Validate role
  if (!roleSelect.value) {
    showFieldError(roleSelect, "Please select a role");
    isValid = false;
  }

  return isValid;
}

function isValidEmail(email) {
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return regex.test(email);
}

function showFieldError(input, message) {
  const formGroup = input.closest(".form-group");
  if (!formGroup) return;

  const errorDiv = document.createElement("div");
  errorDiv.className = "form-error";
  errorDiv.textContent = message;
  formGroup.appendChild(errorDiv);

  input.style.borderColor = "var(--brand-red)";
}

function clearFormErrors() {
  const errors = document.querySelectorAll(".form-error");
  errors.forEach((error) => error.remove());

  const inputs = document.querySelectorAll(".form-input, .form-select");
  inputs.forEach((input) => {
    input.style.borderColor = "";
  });
}

/* ══════════════════════════════════════════
   UTILITIES
══════════════════════════════════════════ */

function showToast(message, isError = false) {
  const toast = document.createElement("div");
  toast.className = `alert alert--${isError ? "error" : "success"}`;
  toast.textContent = message;
  document.body.appendChild(toast);

  setTimeout(() => {
    toast.remove();
  }, 4000);
}
