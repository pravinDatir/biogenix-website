document.addEventListener('DOMContentLoaded', async () => {
  const container = document.querySelector('.faq-page');
  if (!container) return;

  const categoriesEl = container.querySelector('.faq-categories');
  const accordionEl = container.querySelector('#faqAccordion');

  try {
    const res = await fetch('/data/faq.json');
    const data = await res.json();

    if (!Array.isArray(data) || !data.length) {
      accordionEl.innerHTML = '<p class="muted">No FAQ data available.</p>';
      return;
    }

    let activeCategoryId = data[0].id;

    data.forEach((section) => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'faq-filter-btn';
      btn.textContent = section.title;
      btn.dataset.categoryId = String(section.id);
      btn.addEventListener('click', () => {
        activeCategoryId = section.id;
        renderAccordion();
        updateFilterStates();
      });
      categoriesEl.appendChild(btn);
    });

    function updateFilterStates() {
      categoriesEl.querySelectorAll('.faq-filter-btn').forEach((button) => {
        const isActive = Number(button.dataset.categoryId) === Number(activeCategoryId);
        button.classList.toggle('is-active', isActive);
      });
    }

    function renderAccordion() {
      accordionEl.innerHTML = '';

      const selected = data.find((section) => Number(section.id) === Number(activeCategoryId));
      if (!selected) return;

      const item = document.createElement('div');
      item.className = 'accordion-item is-open';

      item.innerHTML = `
        <button type="button" class="accordion-header-btn" aria-expanded="true">
          <span>${selected.title}</span>
          <span class="accordion-indicator">-</span>
        </button>
        <div class="accordion-body">
          <p>${selected.description || ''}</p>
          <ul>
            ${(selected.faqs || []).map((faq) => `<li><strong>${faq.question}</strong><br>${faq.answer}</li>`).join('')}
          </ul>
        </div>
      `;

      const toggleButton = item.querySelector('.accordion-header-btn');
      const indicator = item.querySelector('.accordion-indicator');

      toggleButton.addEventListener('click', () => {
        const opened = item.classList.toggle('is-open');
        toggleButton.setAttribute('aria-expanded', opened ? 'true' : 'false');
        indicator.textContent = opened ? '-' : '+';
      });

      accordionEl.appendChild(item);
    }

    updateFilterStates();
    renderAccordion();
  } catch (err) {
    console.error('Failed to load FAQ', err);
    accordionEl.innerHTML = '<p class="muted">Unable to load FAQ right now.</p>';
  }
});
