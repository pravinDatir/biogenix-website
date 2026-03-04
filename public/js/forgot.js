document.addEventListener("DOMContentLoaded", () => {

  const form = document.getElementById("forgotForm");
  if (!form) return;

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const fields = [
      { id: "forgotEmail", rules: ["required", "email"] }
    ];

    const isValid = validateFields(fields);
    if (!isValid) return;

    document.getElementById("resetStatus").textContent =
      "Reset link sent to your email";
  });

});