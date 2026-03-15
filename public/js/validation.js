/* ===============================
   VALIDATION MESSAGES
   =============================== */

const ValidationMessages = {
  required: "This field is required",
  email: "Enter a valid email address",
  phone: "Enter a valid 10-digit phone number",
  pincode: "Enter a valid 6-digit pincode"
};

/* ===============================
   HELPERS
   =============================== */

function isRequired(value) {
  return value.trim().length > 0;
}

function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function isValidPhone(value) {
  return /^\d{10}$/.test(value); // exactly 10 digits
}

function isValidPincode(value) {
  return /^\d{6}$/.test(value); // exactly 6 digits
}

function showError(input, messageKey) {
  const group = input.closest(".form-group");
  const errorEl = group?.querySelector(".error");

  input.classList.add("error-border");
  if (errorEl) {
    errorEl.textContent = ValidationMessages[messageKey] || "";
  }
}

function clearError(input) {
  const group = input.closest(".form-group");
  const errorEl = group?.querySelector(".error");

  input.classList.remove("error-border");
  if (errorEl) errorEl.textContent = "";
}

/**
 * Generic validator
 * fields = [{ id, rules: ['required', 'email'] }]
 */
function validateFields(fields) {
  let valid = true;

  fields.forEach(field => {
    const input = document.getElementById(field.id);
    if (!input) return;

    const value = input.value.trim();
    clearError(input);

    if (field.rules.includes("required") && !isRequired(value)) {
      showError(input, "required");
      valid = false;
      return;
    }

    if (field.rules.includes("email") && !isValidEmail(value)) {
      showError(input, "email");
      valid = false;
    }

     if (field.rules.includes("phone") && !isValidPhone(value)) {
      showError(input, "phone");
      valid = false;
      return;
    }

    if (field.rules.includes("pincode") && !isValidPincode(value)) {
  showError(input, "pincode");
  valid = false;
  return;
}
  });

  return valid;
}

/* ===============================
   LIVE VALIDATION (ALL FORMS)
   =============================== */
document.addEventListener("input", (e) => {
  const input = e.target;
  if (!input.closest("form")) return;

  const value = input.value.trim();

  if (!value) {
    showError(input, "required");
    return;
  }

  if (input.type === "email" && !isValidEmail(value)) {
    showError(input, "email");
    return;
  }

  if (input.id === "phone") {
    // Remove non-digits
    input.value = value.replace(/\D/g, "").slice(0, 10);

    if (!isValidPhone(input.value)) {
      showError(input, "phone");
      return;
    }
  }
  
  if (input.id === "pincode") {
  // Remove non-digits
  input.value = value.replace(/\D/g, "").slice(0, 6);

  if (!isValidPincode(input.value)) {
    showError(input, "pincode");
    return;
  }
}
  clearError(input);
});


/* ===============================
   PASSWORD MATCH VALIDATION
   =============================== */

/**
 * Validates that two password fields match
 * @param {string} passId - Password input ID
 * @param {string} confirmId - Confirm password input ID
 * @returns {boolean} true if match, false if not
 */
function validatePasswordMatch(passId, confirmId) {
  const pass = document.getElementById(passId);
  const confirm = document.getElementById(confirmId);

  if (!pass || !confirm) return false;

  clearError(pass);
  clearError(confirm);

  if (pass.value !== confirm.value) {
    const group = confirm.closest(".form-group");
    const errorEl = group?.querySelector(".error");

    confirm.classList.add("error-border");
    if (errorEl) errorEl.textContent = "Passwords do not match";

    return false;
  }

  return true;
}


/**
 * Toggle password visibility for any password field
 * @param {string} inputId - ID of the password input
 * @param {string} toggleId - ID of the toggle button
 */
function setupPasswordToggle(inputId, toggleId) {
  const toggleBtn = document.getElementById(toggleId);
  if (!toggleBtn) return;

  toggleBtn.addEventListener("click", () => {
    const input = document.getElementById(inputId);

    if (input.type === "password") {
      input.type = "text";
      input.classList.add("is-password-visible");
      toggleBtn.setAttribute("aria-label", "Hide password");
    } else {
      input.type = "password";
      input.classList.remove("is-password-visible");
      toggleBtn.setAttribute("aria-label", "Show password");
    }
  });
}
