import '../css/app.css';

/* =========================================================================
   i18n
   ========================================================================= */
const I18N = {
    en: {
        'nav.dashboard': 'Dashboard', 'nav.home': 'Home',
        'nav.wishlist': 'Wishlist', 'nav.cart': 'Cart', 'nav.orders': 'My Orders',
        'nav.history': 'Order History', 'nav.profile': 'Profile', 'nav.addresses': 'Addresses',
        'nav.payments': 'Payment Methods', 'nav.notifications': 'Notifications',
        'nav.settings': 'Settings', 'nav.help': 'Help', 'nav.backhome': 'Back to Home',
        'nav.logout': 'Logout',
        'store.home': 'Home', 'store.products': 'Products', 'store.wishlist': 'Wishlist',
        'store.cart': 'Cart', 'store.account': 'My Account', 'store.login': 'Login',
        'store.signup': 'Sign Up', 'store.logout': 'Logout',
        'set.preferences': 'Preferences',
        'set.prefDesc': 'A general view of saying properties',
        'set.language': 'Language', 'set.currency': 'Currency', 'set.theme': 'Theme',
        'set.notifications': 'Notifications',
        'set.notifDesc': 'Control what updates you\'ll receive in actions',
        'set.otherUpdates': 'Other Updates',
        'set.otherDesc': 'Get notifications about your account and ordering updates',
        'set.promotions': 'Promotions and Discounts',
        'set.promoDesc': 'Receive updates about promotions and exclusive offers',
        'set.productUpdates': 'Product Updates',
        'set.productDesc': 'Get notified when there is something new with your order',
        'set.save': 'Save Changes',
        'wishlist.title': 'Wishlist',
        'wishlist.removeSel': 'Remove Selected', 'wishlist.moveSel': 'Move Selected to Cart',
        'wishlist.allItems': 'All Items',
        'toggle.notify_other_updates': 'Other Updates',
        'toggle.notify_other_updates_desc': 'Get notifications about your account and ordering updates',
        'toggle.notify_promotions': 'Promotions and Discounts',
        'toggle.notify_promotions_desc': 'Receive updates about promotions and exclusive offers',
        'toggle.notify_product_updates': 'Product Updates',
        'toggle.notify_product_updates_desc': 'Get notified when there is something new with your order',
        'dash.quick': 'Quick Statistics', 'dash.recentNotif': 'Recent Notifications',
        'dash.viewAll': 'View all', 'dash.savedPay': 'Saved Payment Methods',
        'dash.manage': 'Manage', 'dash.addPay': '+ Add Payment Method',
    },
    fil: {
        'nav.dashboard': 'Dashboard', 'nav.home': 'Tahanan',
        'nav.wishlist': 'Wishlist', 'nav.cart': 'Cart', 'nav.orders': 'Mga Order',
        'nav.history': 'Kasaysayan', 'nav.profile': 'Profile', 'nav.addresses': 'Mga Address',
        'nav.payments': 'Mga Pamamaraan ng Bayad', 'nav.notifications': 'Mga Notipikasyon',
        'nav.settings': 'Mga Setting', 'nav.help': 'Tulong', 'nav.backhome': 'Bumalik sa Tahanan',
        'nav.logout': 'Mag-logout',
        'store.home': 'Tahanan', 'store.products': 'Mga Produkto', 'store.wishlist': 'Wishlist',
        'store.cart': 'Cart', 'store.account': 'Aking Account', 'store.login': 'Mag-login',
        'store.signup': 'Mag-sign up', 'store.logout': 'Mag-logout',
        'set.preferences': 'Mga Kagustuhan',
        'set.prefDesc': 'Pangkalahatang view ng iyong mga property',
        'set.language': 'Wika', 'set.currency': 'Pera', 'set.theme': 'Tema',
        'set.notifications': 'Mga Notipikasyon',
        'set.notifDesc': 'I-control kung anong updates ang matatanggap mo',
        'set.otherUpdates': 'Iba pang Updates',
        'set.otherDesc': 'Tumanggap ng updates tungkol sa account at order mo',
        'set.promotions': 'Mga Promo at Diskuwento',
        'set.promoDesc': 'Tanggapin ang updates sa mga promo at exclusive offers',
        'set.productUpdates': 'Mga Update sa Produkto',
        'set.productDesc': 'Malaman kung may bago sa iyong order',
        'set.save': 'I-save ang Pagbabago',
        'wishlist.title': 'Wishlist', 'wishlist.moveAll': 'Ilipat Lahat sa Cart',
        'wishlist.removeSel': 'Tanggalin ang Napili', 'wishlist.moveSel': 'Ilipat ang Napili sa Cart',
        'wishlist.allItems': 'Lahat ng Items',
        'toggle.notify_other_updates': 'Iba pang Updates',
        'toggle.notify_other_updates_desc': 'Tumanggap ng updates tungkol sa account at order mo',
        'toggle.notify_promotions': 'Mga Promo at Diskuwento',
        'toggle.notify_promotions_desc': 'Tanggapin ang updates sa mga promo at exclusive offers',
        'toggle.notify_product_updates': 'Mga Update sa Produkto',
        'toggle.notify_product_updates_desc': 'Malaman kung may bago sa iyong order',
        'dash.quick': 'Mabilis na Estadistika', 'dash.recentNotif': 'Mga Recent na Notipikasyon',
        'dash.viewAll': 'Tingnan lahat', 'dash.savedPay': 'Mga Nai-save na Pamamaraan ng Bayad',
        'dash.manage': 'I-manage', 'dash.addPay': '+ Magdagdag ng Pamamaraan',
    },
};

function applyLanguage(lang) {
    const dict = I18N[lang] || I18N.en;
    document.querySelectorAll('[data-i18n]').forEach((el) => {
        const key = el.getAttribute('data-i18n');
        if (dict[key] !== undefined) el.textContent = dict[key];
    });
    window.__lang = lang;
}
window.applyLanguage = applyLanguage;

document.addEventListener('DOMContentLoaded', () => {
    // ---------------------------------------------------------------
    // Toasts (bottom-right with icons)
    // ---------------------------------------------------------------
    const toastContainer = document.getElementById('toastContainer');

    const TOAST_ICONS = {
        success: '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        error: '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        info: '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    };

    const TOAST_BG = { success: 'bg-emerald-500', error: 'bg-red-500', info: 'bg-sky-500' };

    window.showToast = (message, type = 'success') => {
        if (!toastContainer || !message) return;
        const el = document.createElement('div');
        el.className = 'flex items-center gap-3 px-5 py-3 rounded-xl shadow-lg text-sm text-white transition-all duration-300 translate-y-4 opacity-0 max-w-sm';
        el.style.backgroundColor = 'var(--toast-bg, #10b981)';
        const bg = TOAST_BG[type] || TOAST_BG.success;
        el.setAttribute('style', `background: ${bg === 'bg-emerald-500' ? '#10b981' : bg === 'bg-red-500' ? '#ef4444' : '#0ea5e9'};`);
        el.innerHTML = `${TOAST_ICONS[type] || TOAST_ICONS.success}<span>${message}</span>`;
        toastContainer.prepend(el);
        requestAnimationFrame(() => el.classList.remove('translate-y-4', 'opacity-0'));
        setTimeout(() => {
            el.classList.add('opacity-0', 'translate-y-4');
            setTimeout(() => el.remove(), 300);
        }, 3500);
    };

    if (window.__flash) {
        if (window.__flash.success) window.showToast(window.__flash.success, 'success');
        if (window.__flash.error) window.showToast(window.__flash.error, 'error');
        if (window.__flash.info) window.showToast(window.__flash.info, 'info');
    }

    // ---------------------------------------------------------------
    // Generic confirmation modal
    // ---------------------------------------------------------------
    const confirmModal = document.getElementById('confirm-modal');
    const confirmCard = document.getElementById('confirm-modal-card');
    const confirmBackdrop = document.getElementById('confirm-backdrop');
    const confirmOk = document.getElementById('confirm-ok');
    const confirmCancel = document.getElementById('confirm-cancel');
    const confirmTitle = document.getElementById('confirm-title');
    const confirmMessage = document.getElementById('confirm-message');
    const confirmIcon = document.getElementById('confirm-icon');
    let pendingForm = null;

    const setConfirmTheme = (theme) => {
        const isDanger = theme === 'danger';
        confirmIcon.className = `w-10 h-10 rounded-full flex items-center justify-center shrink-0 ${isDanger ? 'bg-red-100' : 'bg-sky-100'}`;
        const iconSvg = confirmIcon.querySelector('svg');
        if (iconSvg) {
            iconSvg.setAttribute('class', `w-5 h-5 ${isDanger ? 'text-red-500' : 'text-sky-500'}`);
            iconSvg.innerHTML = isDanger
                ? '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>'
                : '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
        }
        confirmOk.className = `px-5 py-2.5 rounded-xl text-white transition font-medium text-sm shadow-sm ${isDanger ? 'bg-red-500 hover:bg-red-600' : 'bg-sky-500 hover:bg-sky-600'}`;
    };

    const openConfirm = (form, options = {}) => {
        pendingForm = form;
        confirmTitle.textContent = options.title || 'Confirm Action';
        confirmMessage.textContent = options.message || 'Are you sure you want to proceed?';
        confirmOk.textContent = options.btnText || 'Delete';
        setConfirmTheme(options.theme || 'danger');
        confirmModal.classList.remove('opacity-0', 'pointer-events-none');
        confirmModal.classList.add('opacity-100');
        confirmCard.classList.remove('scale-95');
        confirmCard.classList.add('scale-100');
    };

    const closeConfirm = () => {
        confirmModal.classList.add('opacity-0', 'pointer-events-none');
        confirmModal.classList.remove('opacity-100');
        confirmCard.classList.add('scale-95');
        confirmCard.classList.remove('scale-100');
        pendingForm = null;
    };

    // Handle forms with data-confirm-* attributes
    document.querySelectorAll('form.js-confirm-delete, form.js-confirm').forEach((form) => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            openConfirm(form, {
                title: form.dataset.confirmTitle || 'Confirm Action',
                message: form.dataset.confirmMessage || 'Are you sure you want to proceed?',
                btnText: form.dataset.confirmBtn || 'Delete',
                theme: form.dataset.confirmTheme || 'danger',
            });
        });
    });

    confirmOk?.addEventListener('click', () => { if (pendingForm) pendingForm.submit(); });
    confirmCancel?.addEventListener('click', closeConfirm);
    confirmBackdrop?.addEventListener('click', closeConfirm);
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && pendingForm) closeConfirm(); });

    if (window.__lang) applyLanguage(window.__lang);

    // ---------------------------------------------------------------
    // AJAX helpers
    // ---------------------------------------------------------------
    async function postForm(form) {
        const fd = new FormData(form);
        const res = await fetch(form.action, {
            method: form.method,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            body: fd,
        });
        return res.json().catch(() => ({}));
    }

    function ajaxJson(form, onSuccess) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const data = await postForm(form);
            if (data.message) window.showToast(data.message, data.ok === false ? 'error' : 'success');
            if (onSuccess) onSuccess(data, form);
            updateBadges(data);
        });
    }

    // ---------------------------------------------------------------
    // Cart / wishlist badges
    // ---------------------------------------------------------------
    const updateBadges = (data) => {
        if (data.cartCount !== undefined) {
            const cart = document.querySelector('.js-cart-badge');
            if (cart) {
                cart.textContent = data.cartCount;
                cart.classList.toggle('hidden', data.cartCount === 0);
            }
        }
        if (data.wishlistCount !== undefined) {
            const wish = document.querySelector('.js-wishlist-badge');
            if (wish) {
                wish.textContent = data.wishlistCount;
                wish.classList.toggle('hidden', data.wishlistCount === 0);
            }
        }
    };
    window.updateBadges = updateBadges;

    const ajaxForm = (form, onSuccess) => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const fd = new FormData(form);
            try {
                const res = await fetch(form.action, {
                    method: form.method,
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                    body: fd,
                });
                const data = await res.json().catch(() => ({}));
                if (data.message) window.showToast(data.message, 'success');
                if (onSuccess) onSuccess(data, form);
                updateBadges(data);
            } catch (err) {
                form.submit();
            }
        });
    };

    document.querySelectorAll('.js-add-cart').forEach((f) => ajaxForm(f));
    document.querySelectorAll('.js-add-wishlist').forEach((f) => ajaxForm(f));

    document.querySelectorAll('.js-cart-update').forEach((f) =>
        ajaxForm(f, (data, form) => {
            const row = form.closest('[data-cart-row]');
            if (row) {
                const qty = parseInt(form.querySelector('input[name="qty"]').value, 10) || 1;
                const unit = parseFloat(row.dataset.unitPrice) || 0;
                const line = row.querySelector('[data-line-total]');
                if (line) line.textContent = '₱' + (unit * qty).toFixed(2);
            }
        })
    );

    document.querySelectorAll('.js-cart-remove').forEach((f) =>
        ajaxForm(f, (data, form) => {
            const row = form.closest('[data-cart-row]');
            if (row) row.remove();
            if (data.cartCount === 0) location.reload();
        })
    );

    // ---------------------------------------------------------------
    // Wishlist heart toggle
    // ---------------------------------------------------------------
    document.querySelectorAll('form.js-wish-toggle-form').forEach((f) =>
        ajaxJson(f, (data, form) => {
            const btn = form.querySelector('.js-wish-heart');
            if (!btn) return;
            const img = btn.querySelector('img');
            const added = !!data.added;
            btn.classList.toggle('text-red-500', added);
            btn.classList.toggle('text-sky-500', !added);
            btn.classList.toggle('border-sky-500', !added);
            btn.classList.toggle('border-gray-300', added);
            if (img) {
                img.classList.toggle('fill-red-500', added);
                img.classList.toggle('text-red-500', added);
            }
            btn.dataset.inWishlist = added ? '1' : '0';
            btn.title = added ? 'Remove from wishlist' : 'Add to wishlist';
        })
    );

    // ---------------------------------------------------------------
    // Wishlist page delete / move
    // ---------------------------------------------------------------
    const pruneWishlist = () => {
        const rows = document.querySelectorAll('[data-wish-row]');
        const empty = document.getElementById('wishlist-empty');
        const footer = document.getElementById('wishlist-footer');
        if (rows.length === 0) {
            if (empty) empty.classList.remove('hidden');
            if (footer) footer.classList.add('hidden');
        }
    };
    window.pruneWishlist = pruneWishlist;

    document.querySelectorAll('form.js-wishlist-delete').forEach((f) =>
        ajaxJson(f, (data, form) => {
            const row = form.closest('[data-wish-row]');
            if (row) row.remove();
            pruneWishlist();
        })
    );

    document.querySelectorAll('form.js-wishlist-move').forEach((f) =>
        ajaxJson(f, (data, form) => {
            const row = form.closest('[data-wish-row]');
            if (row) row.remove();
            pruneWishlist();
        })
    );

    const selectedForms = (selector) =>
        [...document.querySelectorAll('[data-wish-row]')]
            .filter((r) => r.querySelector('.js-wish-check')?.checked)
            .map((r) => r.querySelector(selector))
            .filter(Boolean);

    const runSequentially = async (forms) => {
        for (const f of forms) {
            const data = await postForm(f);
            if (data.message) window.showToast(data.message);
            updateBadges(data);
        }
    };

    const selectAllBtn = document.querySelector('.js-select-all');
    if (selectAllBtn) {
        const wishChecks = () => [...document.querySelectorAll('.js-wish-check')];
        const selectAllLabel = selectAllBtn.querySelector('.js-select-all-label');
        const syncSelectAllLabel = () => {
            const all = wishChecks();
            const everyChecked = all.length > 0 && all.every((c) => c.checked);
            if (selectAllLabel) selectAllLabel.textContent = everyChecked ? 'Unselect All' : 'Select All';
        };
        selectAllBtn.addEventListener('click', () => {
            const all = wishChecks();
            const everyChecked = all.length > 0 && all.every((c) => c.checked);
            all.forEach((c) => (c.checked = !everyChecked));
            syncSelectAllLabel();
        });
        wishChecks().forEach((c) => c.addEventListener('change', syncSelectAllLabel));
    }

    document.querySelector('.js-move-selected')?.addEventListener('click', async () => {
        await runSequentially(selectedForms('form.js-wishlist-move'));
        location.reload();
    });

    document.querySelector('.js-remove-selected')?.addEventListener('click', async () => {
        await runSequentially(selectedForms('form.js-wishlist-delete'));
        location.reload();
    });

    // ---------------------------------------------------------------
    // Profile picture live preview
    // ---------------------------------------------------------------
    const pic = document.getElementById('profile_picture');
    if (pic) {
        pic.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (ev) => {
                const preview = document.getElementById('preview');
                if (preview) {
                    preview.outerHTML = `<img id="preview" src="${ev.target.result}" class="w-full h-full object-cover">`;
                }
            };
            reader.readAsDataURL(file);
        });
    }

    // ---------------------------------------------------------------
    // Notification filters
    // ---------------------------------------------------------------
    const filters = document.querySelectorAll('.js-filter');
    filters.forEach((btn) => {
        btn.addEventListener('click', () => {
            const type = btn.dataset.filter;
            filters.forEach((b) => {
                b.classList.remove('bg-blue-900', 'text-white', 'border-blue-900');
                b.classList.add('text-gray-600', 'border-gray-300');
            });
            btn.classList.add('bg-blue-900', 'text-white', 'border-blue-900');
            btn.classList.remove('text-gray-600', 'border-gray-300');
            document.querySelectorAll('.js-notif').forEach((n) => {
                n.style.display = (type === 'all' || n.dataset.type === type) ? '' : 'none';
            });
        });
    });

    // ---------------------------------------------------------------
    // Settings live toggle
    // ---------------------------------------------------------------
    const settingsForm = document.getElementById('settings-form');
    const autoSaveSettings = async () => {
        if (!settingsForm) return;
        const fd = new FormData(settingsForm);
        try {
            await fetch(settingsForm.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                body: fd,
            });
        } catch (e) { /* best-effort */ }
    };

    const themeSel = document.getElementById('theme');
    if (themeSel) {
        themeSel.addEventListener('change', (e) => {
            document.documentElement.classList.toggle('dark', e.target.value === 'Dark');
            autoSaveSettings();
        });
    }

    const langSel = document.getElementById('language');
    if (langSel) {
        langSel.addEventListener('change', (e) => {
            applyLanguage(e.target.value === 'Filipino' ? 'fil' : 'en');
            autoSaveSettings();
        });
    }

    const currencySel = document.getElementById('currency');
    if (currencySel) currencySel.addEventListener('change', autoSaveSettings);

    document.querySelectorAll('#settings-form .js-autosave')
        .forEach((el) => el.addEventListener('change', autoSaveSettings));
});
