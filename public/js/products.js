document.addEventListener("DOMContentLoaded", function () {

    const grid = document.getElementById("productGrid");
    if (!grid) return;

    const searchInput = document.getElementById("productSearch");

    const urlParams = new URLSearchParams(window.location.search);
    const categoryFilter = urlParams.get("category");

    fetch("/data/products.json")
        .then(res => res.json())
        .then(products => {

            let filteredProducts = products;

            if (categoryFilter) {
                filteredProducts = products.filter(p => p.category === categoryFilter);
                document.getElementById("currentCategory").textContent = categoryFilter;
            } else {
                document.getElementById("currentCategory").textContent = "All Products & Solutions";
            }

            renderProducts(filteredProducts);

            searchInput.addEventListener("input", function () {
                const searchText = searchInput.value.toLowerCase();

                const searched = filteredProducts.filter(p =>
                    p.name.toLowerCase().includes(searchText)
                );

                renderProducts(searched);
            });

        })
        .catch(err => console.error("Failed to load products", err));

});


function renderProducts(products) {

    const grid = document.getElementById("productGrid");
    grid.innerHTML = "";

    if (!products.length) {
        grid.innerHTML = `<p class="no-products">No products found.</p>`;
        return;
    }

    products.forEach(p => {

        const card = document.createElement("div");
        card.className = "product-card";

        card.innerHTML = `
            <div class="product-image">
                <img src="/${p.image}" alt="${p.name}">
            </div>

            <div class="product-body">
                <h3 class="product-title">${p.name}</h3>
                <p class="product-desc">${p.description}</p>

                <div class="product-footer">
                    <span class="product-price">${p.mrp}</span>
                    <a href="/products/${p.id}" class="btn btn-outline-primary">
                        View Details
                    </a>
                </div>
            </div>
        `;

        grid.appendChild(card);
    });

}