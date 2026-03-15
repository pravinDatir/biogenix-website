document.addEventListener("DOMContentLoaded", () => {
  const nextBtn = document.getElementById("nextBtn");
  const backBtn = document.getElementById("backBtn");
  const form = document.getElementById("signupForm");
  const stepNodes = Array.from(document.querySelectorAll(".step"));
  const formSteps = Array.from(document.querySelectorAll(".form-step"));
  const stateSelect = document.getElementById("state");
  const progressFill = document.getElementById("signupProgressFill");
  const progressBar = document.getElementById("signupProgressBar");
  const currentStepLabel = document.getElementById("signupCurrentStep");
  const currentStepName = document.getElementById("signupCurrentLabel");
  const stepLabels = ["Personal Details", "Address"];

  if (!form || !nextBtn || !backBtn || !stepNodes.length || !formSteps.length) {
    return;
  }

  setupPasswordToggle("signupPassword", "toggleSignupPassword");
  setupPasswordToggle("confirmPassword", "toggleConfirmPassword");

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

    form.submit();
  });

  function toggleStep(step) {
    formSteps.forEach((node) => {
      node.classList.toggle("active", Number(node.dataset.step) === step);
    });

    stepNodes.forEach((node, index) => {
      node.classList.toggle("active", index + 1 === step);
      node.classList.toggle("is-complete", index + 1 < step);
    });

    if (progressFill) {
      progressFill.style.width = `${(step / formSteps.length) * 100}%`;
    }

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
  toggleStep(1);

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
