/**
 * admin/js/admin.js
 * Admin Dashboard JavaScript
 */

/* ============================================================
   SIDEBAR TOGGLE
   ============================================================ */
(function() {
  const sidebar = document.getElementById('sidebar');
  const toggle  = document.getElementById('sidebarToggle');
  if (!toggle || !sidebar) return;

  toggle.addEventListener('click', () => {
    sidebar.classList.toggle('open');
  });

  // Close on overlay click (mobile)
  document.addEventListener('click', (e) => {
    if (window.innerWidth <= 768 && sidebar.classList.contains('open')) {
      if (!sidebar.contains(e.target) && e.target !== toggle) {
        sidebar.classList.remove('open');
      }
    }
  });
})();

/* ============================================================
   MODAL SYSTEM
   ============================================================ */
window.openModal = function(id) {
  const overlay = document.getElementById(id);
  if (!overlay) return;
  overlay.classList.add('open');
  document.body.style.overflow = 'hidden';
};

window.closeModal = function(id) {
  const overlay = document.getElementById(id);
  if (!overlay) return;
  overlay.classList.remove('open');
  document.body.style.overflow = '';
};

// Close on overlay click
document.addEventListener('click', (e) => {
  if (e.target.classList.contains('modal-overlay')) {
    e.target.classList.remove('open');
    document.body.style.overflow = '';
  }
});

// Close on Esc key
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    document.querySelectorAll('.modal-overlay.open').forEach(m => {
      m.classList.remove('open');
    });
    document.body.style.overflow = '';
  }
});

/* ============================================================
   TOAST NOTIFICATIONS
   ============================================================ */
window.showToast = function(message, type = 'info', duration = 3500) {
  let toast = document.getElementById('adminToast');
  if (!toast) {
    toast = document.createElement('div');
    toast.id = 'adminToast';
    toast.className = 'admin-toast';
    toast.innerHTML = '<span class="toast-icon"></span><span class="toast-msg"></span>';
    document.body.appendChild(toast);
  }

  const icons = { success: '✅', error: '❌', info: 'ℹ️', warning: '⚠️' };
  toast.className = `admin-toast ${type}`;
  toast.querySelector('.toast-icon').textContent = icons[type] || 'ℹ️';
  toast.querySelector('.toast-msg').textContent = message;

  requestAnimationFrame(() => {
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), duration);
  });
};

/* ============================================================
   CONFIRM DELETE
   ============================================================ */
window.confirmDelete = function(message, formId) {
  const overlay = document.getElementById('confirmModal');
  if (!overlay) return;
  const msg = overlay.querySelector('#confirmMessage');
  const btn = overlay.querySelector('#confirmDeleteBtn');
  if (msg) msg.textContent = message || 'Yakin ingin menghapus data ini?';
  if (btn) {
    btn.onclick = () => {
      const form = document.getElementById(formId);
      if (form) form.submit();
      closeModal('confirmModal');
    };
  }
  openModal('confirmModal');
};

/* ============================================================
   SKILL LEVEL RANGE DISPLAY
   ============================================================ */
document.querySelectorAll('input[type="range"][data-display]').forEach(range => {
  const display = document.getElementById(range.dataset.display);
  const bar     = document.getElementById(range.dataset.bar);
  if (display) display.textContent = range.value + '%';
  if (bar) bar.style.width = range.value + '%';

  range.addEventListener('input', function() {
    if (display) display.textContent = this.value + '%';
    if (bar) bar.style.width = this.value + '%';
  });
});

/* ============================================================
   FILE UPLOAD PREVIEW
   ============================================================ */
document.querySelectorAll('input[type="file"][data-preview]').forEach(input => {
  input.addEventListener('change', function() {
    const previewId = this.dataset.preview;
    const preview   = document.getElementById(previewId);
    if (!preview) return;
    if (this.files && this.files[0]) {
      const reader = new FileReader();
      reader.onload = (e) => {
        if (preview.tagName === 'IMG') {
          preview.src = e.target.result;
          preview.style.display = 'block';
        } else {
          preview.textContent = this.files[0].name;
        }
      };
      reader.readAsDataURL(this.files[0]);
    }
  });
});

/* ============================================================
   TABLE SEARCH FILTER
   ============================================================ */
const tableSearch = document.getElementById('tableSearch');
if (tableSearch) {
  tableSearch.addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    const rows = document.querySelectorAll('[data-searchable] tbody tr');
    rows.forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
  });
}

/* ============================================================
   AUTO-DISMISS ALERTS
   ============================================================ */
document.querySelectorAll('.alert').forEach(alert => {
  setTimeout(() => {
    alert.style.transition = 'opacity 0.4s, transform 0.3s';
    alert.style.opacity    = '0';
    alert.style.transform  = 'translateY(-8px)';
    setTimeout(() => alert.remove(), 400);
  }, 4500);
});

/* ============================================================
   ACTIVE SIDEBAR LINK HIGHLIGHT
   ============================================================ */
(function() {
  const path = window.location.pathname;
  document.querySelectorAll('.sidebar-item').forEach(link => {
    const href = link.getAttribute('href');
    if (href && path.includes(href.replace('/portfolio_redesign',''))) {
      link.classList.add('active');
    }
  });
})();

/* ============================================================
   TOGGLE STATUS (AJAX-free, form-based)
   ============================================================ */
document.querySelectorAll('.toggle-active').forEach(toggle => {
  toggle.addEventListener('change', function() {
    const form = this.closest('form');
    if (form) form.submit();
  });
});
