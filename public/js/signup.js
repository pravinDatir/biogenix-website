document.addEventListener("DOMContentLoaded", () => {
  const nextBtn = document.getElementById("nextBtn");
  const backBtn = document.getElementById("backBtn");
  const submitBtn = document.getElementById("signupSubmitBtn");
  const form = document.getElementById("signupForm");
  const stepNodes = Array.from(document.querySelectorAll("[data-signup-step]"));
  const formSteps = Array.from(document.querySelectorAll("[data-signup-panel]"));
  const connectors = Array.from(document.querySelectorAll("[data-signup-connector]"));
  const stateSelect = document.getElementById("state");
  const progressBar = document.getElementById("signupProgressBar");
  const currentStepLabel = document.getElementById("signupCurrentStep");
  const currentStepName = document.getElementById("signupCurrentLabel");
  const stepLabels = ["Personal Details", "Address"];

  if (!form || !nextBtn || !backBtn || !stepNodes.length || !formSteps.length) {
    return;
  }

  if (typeof setupPasswordToggle === "function") {
    setupPasswordToggle("signupPassword", "toggleSignupPassword");
    setupPasswordToggle("confirmPassword", "toggleConfirmPassword");
  }

  nextBtn.addEventListener("click", (event) => {
    event.preventDefault();

    if (!validateStep1()) {
      return;
    }

    toggleStep(2);
  });

  backBtn.addEventListener("click", () => {
    toggleStep(1);
  });

  form.addEventListener("submit", (event) => {
    event.preventDefault();

    if (!validateStep2()) {
      return;
    }

    setHiddenField(
      form,
      "name",
      `${document.getElementById("firstName").value} ${document.getElementById("lastName").value}`.trim()
    );

    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.classList.add("cursor-not-allowed", "opacity-70");
      submitBtn.setAttribute("aria-disabled", "true");
    }

    form.submit();
  });

  function toggleStep(step) {
    formSteps.forEach((node) => {
      node.classList.toggle("hidden", Number(node.dataset.step) !== step);
    });

    stepNodes.forEach((node, index) => {
      const stepNumber = index + 1;
      const isCurrent = stepNumber === step;
      const isActive = stepNumber <= step;
      const circle = node.querySelector("[data-signup-step-circle]");
      const caption = node.querySelector("[data-signup-step-caption]");
      const label = node.querySelector("[data-signup-step-label]");

      node.classList.toggle("border-primary-200", isCurrent);
      node.classList.toggle("bg-primary-50/80", isCurrent);
      node.classList.toggle("shadow-sm", isCurrent);
      node.classList.toggle("border-slate-200", !isCurrent);
      node.classList.toggle("bg-white", !isCurrent);

      if (circle) {
        circle.classList.toggle("border-primary-600", isActive);
        circle.classList.toggle("bg-primary-600", isActive);
        circle.classList.toggle("text-white", isActive);
        circle.classList.toggle("border-slate-300", !isActive);
        circle.classList.toggle("bg-white", !isActive);
        circle.classList.toggle("text-slate-400", !isActive);
      }

      if (caption) {
        caption.classList.toggle("text-primary-600", isActive);
        caption.classList.toggle("text-slate-400", !isActive);
      }

      if (label) {
        label.classList.toggle("text-slate-900", isActive);
        label.classList.toggle("text-slate-500", !isActive);
      }
    });

    connectors.forEach((node, index) => {
      const isActive = index < step - 1;
      node.classList.toggle("bg-primary-600", isActive);
      node.classList.toggle("bg-slate-200", !isActive);
    });

    if (progressBar) {
      progressBar.setAttribute("aria-valuenow", String(step));
    }

    if (currentStepLabel) {
      currentStepLabel.textContent = String(step);
    }

    if (currentStepName) {
      currentStepName.textContent = stepLabels[step - 1] || "";
    }
  }

  function validateStep1() {
    const fields = [
      { id: "firstName", rules: ["required"] },
      { id: "lastName", rules: ["required"] },
      { id: "signupEmail", rules: ["required", "email"] },
      { id: "phone", rules: ["required", "phone"] },
      { id: "signupPassword", rules: ["required"] },
      { id: "confirmPassword", rules: ["required"] },
    ];

    let valid = validateFields(fields);
    const password = document.getElementById("signupPassword").value.trim();
    const confirmPassword = document.getElementById("confirmPassword").value.trim();

    if (password !== "" && confirmPassword !== "" && !validatePasswordMatch("signupPassword", "confirmPassword")) {
      valid = false;
    }

    return valid;
  }

  function validateStep2() {
    return validateFields([
      { id: "addressLine1", rules: ["required"] },
      { id: "pincode", rules: ["required", "pincode"] },
      { id: "city", rules: ["required"] },
      { id: "state", rules: ["required"] },
    ]);
  }

  populateStates();
  toggleStep(Number(form.querySelector("[data-signup-panel]:not(.hidden)")?.dataset.step || 1));

  function populateStates() {
    if (!stateSelect) {
      return;
    }

    const states = [
      "Andhra Pradesh", "Arunachal Pradesh", "Assam", "Bihar", "Chhattisgarh", "Goa",
      "Gujarat", "Haryana", "Himachal Pradesh", "Jharkhand", "Karnataka", "Kerala",
      "Madhya Pradesh", "Maharashtra", "Manipur", "Meghalaya", "Mizoram", "Nagaland",
      "Odisha", "Punjab", "Rajasthan", "Sikkim", "Tamil Nadu", "Telangana", "Tripura",
      "Uttar Pradesh", "Uttarakhand", "West Bengal",
      "Andaman and Nicobar Islands", "Chandigarh", "Dadra and Nagar Haveli and Daman and Diu",
      "Delhi", "Jammu and Kashmir", "Ladakh", "Lakshadweep", "Puducherry",
    ];
    const selectedState = stateSelect.dataset.oldValue || "";

    states.forEach((state) => {
      const option = document.createElement("option");
      option.value = state;
      option.textContent = state;

      if (selectedState === state) {
        option.selected = true;
      }

      stateSelect.appendChild(option);
    });
  }

  function setHiddenField(formEl, name, value) {
    let hiddenField = formEl.querySelector(`input[name="${name}"]`);

    if (!hiddenField) {
      hiddenField = document.createElement("input");
      hiddenField.type = "hidden";
      hiddenField.name = name;
      formEl.appendChild(hiddenField);
    }

    hiddenField.value = value;
  }
});
