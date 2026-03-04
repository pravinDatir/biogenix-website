document.addEventListener("DOMContentLoaded", () => {

  const form = document.getElementById("loginForm");
  if (!form) return;

  setupPasswordToggle("loginPassword", "togglePassword");

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const fields = [
      { id: "loginEmail", rules: ["required", "email"] },
      { id: "loginPassword", rules: ["required"] }
    ];

    const isValid = validateFields(fields);
    if (!isValid) return;

    const email = document.getElementById("loginEmail").value;
    const password = document.getElementById("loginPassword").value;

    try {
      const res = await fetch("/data/login.json");
      const users = await res.json();

      const user = users.find(
        u => u.username === email && u.password === password
      );

      if (!user) {
        showAuthError("Invalid email or password");
        return;
      }

      // Success
      localStorage.setItem("isLoggedIn", "true");
      localStorage.setItem("userRole", user.role);
       localStorage.setItem("userAccountType", user.accountType);
      localStorage.setItem("username", user.username);

      if (user.accountType === "admin") {
        window.location.href = "/dashboard/admin";
      } else {
        window.location.href = "/dashboard/customer";
      }

    } catch (err) {
      console.error("Login failed:", err);
    }
  });

});

/* Show authentication error under password only */
function showAuthError(message) {
  const passwordInput = document.getElementById("loginPassword");

  passwordInput.classList.add("error-border");

  const group = passwordInput.closest(".form-group");
  const errorEl = group.querySelector(".error");
  errorEl.textContent = message;
}