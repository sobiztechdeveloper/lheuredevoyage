(function ($) {
    'use strict';

    const wrapper = document.querySelector('.admin-wrapper');
    const storageKey = 'ldv_admin_sidebar_collapsed';

    function initSidebar() {
        const toggleBtn = document.getElementById('adminSidebarToggle');
        const collapseBtn = document.getElementById('adminSidebarCollapse');
        const backdrop = document.getElementById('adminSidebarBackdrop');

        if (localStorage.getItem(storageKey) === '1' && window.innerWidth >= 992) {
            wrapper?.classList.add('sidebar-collapsed');
        }

        toggleBtn?.addEventListener('click', () => {
            wrapper?.classList.toggle('sidebar-open');
        });

        backdrop?.addEventListener('click', () => {
            wrapper?.classList.remove('sidebar-open');
        });

        collapseBtn?.addEventListener('click', () => {
            if (window.innerWidth < 992) return;
            wrapper?.classList.toggle('sidebar-collapsed');
            localStorage.setItem(storageKey, wrapper?.classList.contains('sidebar-collapsed') ? '1' : '0');
        });

        document.querySelectorAll('.admin-nav .nav-link-item').forEach((link) => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) {
                    wrapper?.classList.remove('sidebar-open');
                }
            });
        });
    }

    function initNavSections() {
        document.querySelectorAll('[data-nav-section]').forEach((btn) => {
            const target = document.querySelector(btn.getAttribute('data-bs-target'));
            if (!target) return;

            const id = target.id;
            const stored = localStorage.getItem('ldv_nav_' + id);
            if (stored === 'closed') {
                $(target).collapse('hide');
            }

            target.addEventListener('hidden.bs.collapse', () => localStorage.setItem('ldv_nav_' + id, 'closed'));
            target.addEventListener('shown.bs.collapse', () => localStorage.setItem('ldv_nav_' + id, 'open'));
        });
    }

    function showToast(message, type) {
        const container = document.getElementById('adminToastContainer');
        if (!container || !message) return;

        const item = document.createElement('div');
        item.className = 'admin-toast-item ' + (type || 'success');
        item.innerHTML =
            '<i class="far ' + (type === 'error' ? 'fa-circle-xmark' : 'fa-circle-check') + '"></i>' +
            '<div class="flex-grow-1"><div class="small fw-semibold">' + (type === 'error' ? 'Error' : 'Success') + '</div>' +
            '<div class="small text-muted">' + message + '</div></div>' +
            '<button type="button" class="btn-close btn-close-sm ms-1" aria-label="Close"></button>';

        container.appendChild(item);
        item.querySelector('.btn-close')?.addEventListener('click', () => item.remove());
        setTimeout(() => item.remove(), 5000);
    }

    function initToasts() {
        const flash = document.getElementById('adminFlashSuccess');
        if (flash?.dataset.message) {
            showToast(flash.dataset.message, 'success');
        }

        const flashErr = document.getElementById('adminFlashError');
        if (flashErr?.dataset.message) {
            showToast(flashErr.dataset.message, 'error');
        }
    }

    function initSelect2() {
        if (!$.fn.select2) return;

        $('.master-data-multiselect, .admin-select2').each(function () {
            const $el = $(this);
            if ($el.hasClass('select2-hidden-accessible')) return;

            $el.select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: $el.data('placeholder') || 'Select options',
                allowClear: true,
                closeOnSelect: false,
            });
        });
    }

    function initImageUploads() {
        document.querySelectorAll('[data-featured-upload]').forEach((zone) => {
            const input = zone.querySelector('input[type="file"]');
            const preview = zone.querySelector('[data-preview]');
            const previewImg = preview?.querySelector('img');
            const removeBtn = zone.querySelector('[data-remove-preview]');

            zone.addEventListener('click', (e) => {
                if (e.target === removeBtn || e.target.closest('[data-remove-preview]')) return;
                input?.click();
            });

            input?.addEventListener('change', () => {
                const file = input.files?.[0];
                if (!file || !preview || !previewImg) return;
                previewImg.src = URL.createObjectURL(file);
                preview.classList.remove('d-none');
            });

            removeBtn?.addEventListener('click', (e) => {
                e.stopPropagation();
                if (input) input.value = '';
                preview?.classList.add('d-none');
                if (previewImg) previewImg.src = '';
            });
        });

        document.querySelectorAll('[data-gallery-upload]').forEach((zone) => {
            const input = zone.querySelector('input[type="file"]');
            const grid = zone.querySelector('[data-gallery-grid]');

            zone.addEventListener('click', (e) => {
                if (e.target.closest('[data-remove-gallery]')) return;
                input?.click();
            });

            input?.addEventListener('change', () => {
                if (!grid) return;
                grid.querySelectorAll('[data-new-preview]').forEach((el) => el.remove());

                Array.from(input.files || []).forEach((file) => {
                    const item = document.createElement('div');
                    item.className = 'admin-gallery-item';
                    item.dataset.newPreview = '1';
                    item.innerHTML = '<img src="' + URL.createObjectURL(file) + '" alt="">';
                    grid.appendChild(item);
                });
            });
        });
    }

    function initInlineErrors() {
        document.querySelectorAll('.admin-content .is-invalid').forEach((el) => {
            const msg = el.parentElement?.querySelector('.invalid-feedback, .admin-field-error');
            if (msg) msg.style.display = 'block';
        });
    }

    $(function () {
        initSidebar();
        initNavSections();
        initToasts();
        initSelect2();
        initImageUploads();
        initInlineErrors();
    });

    window.LdvAdmin = { showToast, initSelect2 };
})(jQuery);
