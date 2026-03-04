document.addEventListener("DOMContentLoaded", function () {

    if (!PRODUCT_ID) return;

    fetch("/data/products.json")
        .then(res => res.json())
        .then(products => {

            const product = products.find(p => p.id == PRODUCT_ID);
            if (!product) return;

            // Basic Info
            document.getElementById("productName").textContent = product.name;
            document.getElementById("productMRP").textContent = "MRP: " + product.mrp;
            document.getElementById("productDescription").textContent = product.description;

            // Image
            const imgEl = document.getElementById("productImage");
            imgEl.src = "/" + product.image;
            imgEl.alt = product.name;

            // Specs
            const specsEl = document.getElementById("productSpecs");
            specsEl.innerHTML = "";

            if (product.specs) {
                Object.entries(product.specs).forEach(([key, value]) => {
                    const li = document.createElement("li");
                    li.textContent = `${key}: ${value}`;
                    specsEl.appendChild(li);
                });
            }

            // Brochure
            const brochureEl = document.getElementById("productBrochure");
            if (product.brochure) {
                brochureEl.href = "/" + product.brochure;
            } else {
                brochureEl.style.display = "none";
            }

            // Login Logic
            const isLoggedIn = localStorage.getItem("isLoggedIn") === "true";
            const loginBtn = document.getElementById("loginCTA");
            const orderBtn = document.getElementById("orderCTA");

            if (isLoggedIn) {
                loginBtn.style.display = "none";
                orderBtn.style.display = "inline-block";
            } else {
                loginBtn.style.display = "inline-block";
                orderBtn.style.display = "none";
            }

        })
        .catch(err => console.error("Failed to load product", err));

});