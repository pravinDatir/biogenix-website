document.addEventListener("DOMContentLoaded", function () {

  /* ===============================
     MOBILE MENU
  =============================== */
  const menuToggle = document.querySelector("[data-menu-toggle]");
  const nav = document.querySelector(".header-nav");

  if (menuToggle && nav) {
    menuToggle.addEventListener("click", function () {
      nav.classList.toggle("active");
    });
  }

  /* ===============================
     LOAD PRODUCT CATEGORIES
  =============================== */
  loadProductCategories();

  /* ===============================
     LOGIN STATE
  =============================== */
  updateHeaderLoginState();

  document.getElementById("logoutBtn")?.addEventListener("click", () => {
    localStorage.removeItem("isLoggedIn");
    localStorage.removeItem("userRole");
    localStorage.removeItem("username");

    updateHeaderLoginState();
    window.location.href = "/";
  });

});


/* ===============================
   PRODUCT DROPDOWN
=============================== */
function loadProductCategories() {
  const list = document.getElementById("productCategories");
  if (!list) return;

  fetch("/data/products.json")
    .then(res => res.json())
    .then(products => {

      const categories = [...new Set(products.map(p => p.category))];

      list.innerHTML = "";

      categories.forEach(cat => {
        const li = document.createElement("li");

        const link = document.createElement("a");
        link.href = "/products?category=" + encodeURIComponent(cat);
        link.textContent = cat;

        li.appendChild(link);
        list.appendChild(li);
      });

    })
    .catch(err => {
      console.error("Failed to load product categories", err);
    });
}


/* ===============================
   LOGIN STATE UI
=============================== */
function updateHeaderLoginState() {
  const isLoggedIn = localStorage.getItem("isLoggedIn") === "true";
  const loginBtn = document.getElementById("loginBtn");
  const logoutBtn = document.getElementById("logoutBtn");

  if (!loginBtn || !logoutBtn) return;

  if (isLoggedIn) {
    loginBtn.style.display = "none";
    logoutBtn.style.display = "inline-block";
  } else {
    loginBtn.style.display = "inline-block";
    logoutBtn.style.display = "none";
  }
}