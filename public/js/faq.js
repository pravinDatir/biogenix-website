document.addEventListener("DOMContentLoaded", async () => {
    
  const container = document.querySelector(".faq-page");
  if (!container) return;

  const categoriesEl = container.querySelector(".faq-categories");
  const accordionEl = container.querySelector("#faqAccordion");

  try {
    const res = await fetch("/data/faq.json");
    const data = await res.json();

    // Category Buttons
    data.forEach(section => {
      const btn = document.createElement("button");
      btn.className = "btn btn-outline-primary";
      btn.textContent = section.title;
      btn.addEventListener("click", () => openCategory(section.id));
      categoriesEl.appendChild(btn);
    });

    // Accordion
    data.forEach(section => {
      const item = document.createElement("div");
      item.className = "accordion-item";

      item.innerHTML = `
        <h2 class="accordion-header" id="heading-${section.id}">
          <button class="accordion-button collapsed" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#collapse-${section.id}"
            aria-expanded="false">
            ${section.title}
          </button>
        </h2>

        <div id="collapse-${section.id}" class="accordion-collapse collapse"
          data-bs-parent="#faqAccordion">

          <div class="accordion-body d-flex flex-wrap gap-3">
            <div class="faq-image flex-shrink-0">
            </div>

            <div class="faq-content">
              <p>${section.description}</p>
              <ul>
                ${section.faqs.map(f =>
                  `<li><strong>${f.question}</strong><br>${f.answer}</li>`
                ).join("")}
              </ul>
            </div>
          </div>

        </div>
      `;

      accordionEl.appendChild(item);
    });

    function openCategory(id) {
      data.forEach(section => {
        const collapseEl = document.getElementById(`collapse-${section.id}`);
        if (!collapseEl) return;

        const instance = bootstrap.Collapse.getOrCreateInstance(collapseEl);

        if (section.id === id) {
          instance.show();
        } else {
          instance.hide();
        }
      });
    }

  } catch (err) {
    console.error("Failed to load FAQ", err);
  }
});