document.addEventListener('DOMContentLoaded', function () {
  const menuToggle = document.querySelector('[data-menu-toggle]');
  const nav = document.querySelector('.header-nav');
  const productsToggle = document.querySelector('[data-products-toggle]');
  const productsItem = productsToggle?.closest('.has-dropdown') || null;

  if (menuToggle && nav) {
    menuToggle.addEventListener('click', function () {
      const open = !nav.classList.contains('active');
      nav.classList.toggle('active', open);
      menuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');

      if (!open && productsItem) {
        productsItem.classList.remove('is-open');
        productsToggle?.setAttribute('aria-expanded', 'false');
      }
    });
  }

  if (nav && menuToggle) {
    nav.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        if (!nav.classList.contains('active')) return;
        nav.classList.remove('active');
        menuToggle.setAttribute('aria-expanded', 'false');
      });
    });
  }

  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape' && nav?.classList.contains('active')) {
      nav.classList.remove('active');
      if (menuToggle) {
        menuToggle.setAttribute('aria-expanded', 'false');
        menuToggle.focus();
      }
    }
  });

  if (productsToggle && productsItem) {
    function setProductsOpen(open) {
      productsItem.classList.toggle('is-open', open);
      productsToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    setProductsOpen(false);

    productsToggle.addEventListener('click', function (event) {
      event.preventDefault();
      event.stopPropagation();
      setProductsOpen(!productsItem.classList.contains('is-open'));
    });

    document.addEventListener('click', function (event) {
      if (!productsItem.contains(event.target)) {
        setProductsOpen(false);
      }
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') {
        setProductsOpen(false);
      }
    });

    window.addEventListener('resize', function () {
      if (window.innerWidth >= 768) {
        setProductsOpen(false);
      }
    });

    const dropdownMenu = productsItem.querySelector('.products-dropdown');
    if (dropdownMenu) {
      dropdownMenu.addEventListener('click', function (event) {
        event.stopPropagation();
      });
    }
  }

  document.querySelectorAll('[data-modal-close]').forEach(function (button) {
    button.addEventListener('click', function () {
      const targetId = button.getAttribute('data-modal-close');
      const modal = targetId ? document.getElementById(targetId) : null;
      if (modal) {
        modal.classList.add('hidden');
      }
    });
  });

  document.querySelectorAll('[data-open-modal]').forEach(function (button) {
    button.addEventListener('click', function () {
      const targetId = button.getAttribute('data-open-modal');
      const modal = targetId ? document.getElementById(targetId) : null;
      if (modal) {
        modal.classList.remove('hidden');
      }
    });
  });

  document.querySelectorAll('[role="dialog"]').forEach(function (modal) {
    modal.addEventListener('click', function (event) {
      if (event.target === modal) {
        modal.classList.add('hidden');
      }
    });
  });

  loadProductCategories();
});

function loadProductCategories() {
  const list = document.getElementById('productCategories');
  if (!list) return;

  fetch('/data/products.json')
    .then((res) => res.json())
    .then((products) => {
      const categories = [...new Set(products.map((product) => product.category).filter(Boolean))];

      list.innerHTML = '';

      categories.forEach((category) => {
        const li = document.createElement('li');
        const link = document.createElement('a');
        link.href = '/products?category_name=' + encodeURIComponent(category);
        link.textContent = category;
        li.appendChild(link);
        list.appendChild(li);
      });

      if (!categories.length) {
        list.innerHTML = '<li><span class="muted">No categories found.</span></li>';
      }
    })
    .catch((err) => {
      console.error('Failed to load product categories', err);
      list.innerHTML = '<li><span class="muted">Unable to load categories.</span></li>';
    });
}
