document.addEventListener("DOMContentLoaded", () => {

  let currentStep = 1;

  const nextBtn = document.getElementById("nextBtn");
  const backBtn = document.getElementById("backBtn");
  const form = document.getElementById("signupForm");

  setupPasswordToggle("signupPassword", "toggleSignupPassword");
  setupPasswordToggle("confirmPassword", "toggleConfirmPassword");

  /* ================= STEP CONTROL ================= */

  nextBtn.addEventListener("click", () => {
    if (!validateStep1()) return;
    toggleStep(2);
  });

  backBtn.addEventListener("click", () => {
    toggleStep(1);
  });

  function toggleStep(step) {
    currentStep = step;

    document.querySelectorAll(".form-step").forEach(s =>
      s.classList.remove("active")
    );

    document.querySelector(`[data-step="${step}"]`).classList.add("active");

    document.querySelectorAll(".step").forEach((s, i) =>
      s.classList.toggle("active", i + 1 === step)
    );
  }

  /* ================= ACCOUNT TYPE ================= */

  document.querySelectorAll('input[name="accountType"]').forEach(radio => {
    radio.addEventListener("change", handleAccountTypeChange);
  });

  function handleAccountTypeChange() {
    const selected = document.querySelector('input[name="accountType"]:checked').value;
    const businessFields = document.getElementById("businessFields");
    const addressLabel = document.getElementById("addressLabel");

    if (selected === "business") {
      businessFields.style.display = "block";
      addressLabel.textContent = "Office / Building";
    } else {
      businessFields.style.display = "none";
      addressLabel.textContent = "Flat / House / Building";
    }
  }

  /* ================= STEP 1 VALIDATION ================= */

  function validateStep1() {
    const selected = document.querySelector('input[name="accountType"]:checked').value;

    let fields = [
      { id: "firstName", rules: ["required"] },
      { id: "lastName", rules: ["required"] },
      { id: "signupEmail", rules: ["required", "email"] },
      { id: "signupPassword", rules: ["required"] },
      { id: "confirmPassword", rules: ["required"] },
      { id: "phone", rules: ["required", "phone"] }
    ];

    if (selected === "business") {
      fields.push(
        { id: "businessType", rules: ["required"] },
        { id: "companyName", rules: ["required"] }
      );
    }

    let valid = validateFields(fields);
 const pass = document.getElementById("signupPassword").value.trim();
  const confirm = document.getElementById("confirmPassword").value.trim();
  
     // ONLY check match if both fields are filled
  if (pass !== "" && confirm !== "") {
    if (!validatePasswordMatch("signupPassword", "confirmPassword")) {
      valid = false;
    }
  }

    return valid;
  }

  /* ================= FINAL SUBMIT ================= */

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const fields = [
      { id: "addressLine1", rules: ["required"] },
      { id: "addressLine2", rules: ["required"] },
      { id: "landmark", rules: ["required"] },
      { id: "pincode", rules: ["required", "pincode"] },
      { id: "city", rules: ["required"] },
      { id: "state", rules: ["required"] },
    ];

    if (!validateFields(fields)) return;

    const accountType = document.querySelector('input[name="accountType"]:checked').value;
    const businessTypeRaw = document.getElementById("businessType").value.trim().toLowerCase();
    const businessTypeMap = {
      dealer: "dealer",
      distributor: "distributor",
      labs: "lab",
      lab: "lab",
      hospital: "hospital",
    };

    setHiddenField(form, "name", `${document.getElementById("firstName").value} ${document.getElementById("lastName").value}`.trim());
    setHiddenField(form, "user_type", accountType === "business" ? "b2b" : "b2c");
    setHiddenField(form, "company_name", document.getElementById("companyName").value.trim());
    setHiddenField(form, "b2b_type", businessTypeMap[businessTypeRaw] || "");

    form.submit();
  });

  /* ================= COLLECT DATA ================= */

  function collectSignupData() {
    const accountType = document.querySelector('input[name="accountType"]:checked').value;

    return {
      accountType: accountType,
      firstName: firstName.value,
      lastName: lastName.value,
      email: signupEmail.value,
      phone: phone.value,
       businessType: accountType === "business"
      ? businessType.value
      : null,
     companyName: accountType === "business"
      ? companyName.value
      : null,

      address: {
        line1: addressLine1.value,
        line2: addressLine2.value,
        landmark: landmark.value,
        pincode: pincode.value,
        city: city.value,
        state: state.value
      }
    };
  }

  /* ================= STATES ================= */

  const states = [
  "Andhra Pradesh","Arunachal Pradesh","Assam","Bihar","Chhattisgarh","Goa",
  "Gujarat","Haryana","Himachal Pradesh","Jharkhand","Karnataka","Kerala",
  "Madhya Pradesh","Maharashtra","Manipur","Meghalaya","Mizoram","Nagaland",
  "Odisha","Punjab","Rajasthan","Sikkim","Tamil Nadu","Telangana","Tripura",
  "Uttar Pradesh","Uttarakhand","West Bengal",
  "Andaman and Nicobar Islands","Chandigarh","Dadra and Nagar Haveli and Daman and Diu",
  "Delhi","Jammu and Kashmir","Ladakh","Lakshadweep","Puducherry"
];

  const stateSelect = document.getElementById("state");

  states.forEach(s => {
    const opt = document.createElement("option");
    opt.value = opt.textContent = s;
    stateSelect.appendChild(opt);
  });

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
