/* ==========================================================================
   QUEST COMPLAINT PORTAL — CORE SCRIPT
   Mobile nav, smooth scroll, password toggle, form validation,
   alert/toast system, scroll-reveal animations.
   No build tools — plain ES2017, runs after DOMContentLoaded.
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
    initMobileNav();
    initSmoothScroll();
    initPasswordToggle();
    initFormValidation();
    initScrollReveal();
});

/* --------------------------------------------------------------------------
   1. MOBILE NAVBAR TOGGLE
   -------------------------------------------------------------------------- */
function initMobileNav() {
    const toggleBtn = document.querySelector('.mobile-menu-btn');
    const navMenu = document.querySelector('.nav-menu');

    if (!toggleBtn || !navMenu) return;

    toggleBtn.setAttribute('aria-expanded', 'false');

    toggleBtn.addEventListener('click', () => {
        const isOpen = navMenu.classList.toggle('nav-menu-open');
        toggleBtn.setAttribute('aria-expanded', String(isOpen));
        toggleBtn.innerHTML = isOpen
            ? '<i class="fa-solid fa-xmark"></i>'
            : '<i class="fa-solid fa-bars"></i>';
    });

    // Close the menu when a link is tapped (mobile UX)
    navMenu.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            navMenu.classList.remove('nav-menu-open');
            toggleBtn.setAttribute('aria-expanded', 'false');
            toggleBtn.innerHTML = '<i class="fa-solid fa-bars"></i>';
        });
    });

    // Close the menu on outside click
    document.addEventListener('click', (event) => {
        const clickedInsideNav = navMenu.contains(event.target) || toggleBtn.contains(event.target);
        if (!clickedInsideNav && navMenu.classList.contains('nav-menu-open')) {
            navMenu.classList.remove('nav-menu-open');
            toggleBtn.setAttribute('aria-expanded', 'false');
            toggleBtn.innerHTML = '<i class="fa-solid fa-bars"></i>';
        }
    });
}

/* --------------------------------------------------------------------------
   2. SMOOTH SCROLLING for in-page anchor links (e.g. "#section")
   -------------------------------------------------------------------------- */
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener('click', (event) => {
            const targetId = anchor.getAttribute('href');
            if (!targetId || targetId === '#') return;

            const target = document.querySelector(targetId);
            if (!target) return;

            event.preventDefault();
            const headerOffset = document.querySelector('header')?.offsetHeight || 0;
            const top = target.getBoundingClientRect().top + window.scrollY - headerOffset - 16;

            window.scrollTo({ top, behavior: 'smooth' });
        });
    });
}

/* --------------------------------------------------------------------------
   3. PASSWORD SHOW / HIDE
   Injects a toggle icon next to any input[type="password"] without
   requiring changes to the existing PHP markup.
   -------------------------------------------------------------------------- */
function initPasswordToggle() {
    document.querySelectorAll('input[type="password"]').forEach((input) => {
        const wrapper = input.parentElement;
        if (!wrapper) return;

        wrapper.style.position = wrapper.style.position || 'relative';

        const toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.className = 'password-toggle-btn';
        toggle.setAttribute('aria-label', 'Show password');
        toggle.innerHTML = '<i class="fa-solid fa-eye"></i>';

        toggle.addEventListener('click', () => {
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            toggle.innerHTML = isHidden
                ? '<i class="fa-solid fa-eye-slash"></i>'
                : '<i class="fa-solid fa-eye"></i>';
            toggle.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
        });

        wrapper.appendChild(toggle);
    });
}

/* --------------------------------------------------------------------------
   4. FORM VALIDATION
   Validates login and complaint forms before submit; shows inline errors
   and a toast summary. Does not block native `required` — reinforces it.
   -------------------------------------------------------------------------- */
function initFormValidation() {
    document.querySelectorAll('form').forEach((form) => {
        form.addEventListener('submit', (event) => {
            const fields = form.querySelectorAll('input[required], select[required], textarea[required]');
            let firstInvalid = null;
            let errorCount = 0;

            fields.forEach((field) => clearFieldError(field));

            fields.forEach((field) => {
                const valid = validateField(field);
                if (!valid) {
                    errorCount += 1;
                    if (!firstInvalid) firstInvalid = field;
                }
            });

            // Radio groups (e.g. account-type) validate as a set
            const radioGroups = new Set();
            form.querySelectorAll('input[type="radio"][required]').forEach((r) => radioGroups.add(r.name));
            radioGroups.forEach((name) => {
                const group = form.querySelectorAll(`input[type="radio"][name="${name}"]`);
                const checked = Array.from(group).some((r) => r.checked);
                if (!checked) {
                    errorCount += 1;
                    if (!firstInvalid) firstInvalid = group[0];
                }
            });

            if (errorCount > 0) {
                event.preventDefault();
                firstInvalid?.focus();
                showAlert(`Please fix ${errorCount} field${errorCount > 1 ? 's' : ''} before continuing.`, 'error');
            }
        });

        // Clear error styling as the person corrects a field
        form.querySelectorAll('input, select, textarea').forEach((field) => {
            field.addEventListener('input', () => clearFieldError(field));
            field.addEventListener('change', () => clearFieldError(field));
        });
    });
}

function validateField(field) {
    let valid = field.checkValidity();
    const value = field.value.trim();

    // Extra domain rule: university email should look like a real address
    if (valid && field.type === 'email' && value) {
        valid = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(value);
    }

    // Extra rule: complaint description needs a meaningful minimum length
    if (valid && field.id === 'complaint-description' && value.length < 15) {
        valid = false;
    }

    if (!valid) markFieldInvalid(field);
    return valid;
}

function markFieldInvalid(field) {
    field.classList.add('input-invalid');
    field.setAttribute('aria-invalid', 'true');

    let message = 'This field needs your attention.';
    if (field.validity && field.validity.valueMissing) message = 'This field is required.';
    else if (field.type === 'email') message = 'Enter a valid email address.';
    else if (field.id === 'complaint-description') message = 'Add a bit more detail (15+ characters).';

    let hint = field.parentElement.querySelector('.field-error');
    if (!hint) {
        hint = document.createElement('span');
        hint.className = 'field-error';
        field.insertAdjacentElement('afterend', hint);
    }
    hint.textContent = message;
}

function clearFieldError(field) {
    field.classList.remove('input-invalid');
    field.removeAttribute('aria-invalid');
    const hint = field.parentElement.querySelector('.field-error');
    if (hint) hint.remove();
}

/* --------------------------------------------------------------------------
   5. ALERT / TOAST SYSTEM
   Usage: showAlert('Complaint submitted.', 'success')
   Types: success | error | warning | info (default)
   -------------------------------------------------------------------------- */
function getAlertStack() {
    let stack = document.querySelector('.qcp-alert-stack');
    if (!stack) {
        stack = document.createElement('div');
        stack.className = 'qcp-alert-stack';
        stack.setAttribute('role', 'status');
        stack.setAttribute('aria-live', 'polite');
        document.body.appendChild(stack);
    }
    return stack;
}

function showAlert(message, type = 'info', duration = 4200) {
    const stack = getAlertStack();

    const icons = {
        success: 'fa-circle-check',
        error: 'fa-circle-exclamation',
        warning: 'fa-triangle-exclamation',
        info: 'fa-circle-info',
    };

    const alertEl = document.createElement('div');
    alertEl.className = `qcp-alert qcp-alert-${type}`;
    alertEl.innerHTML = `
    <i class="fa-solid ${icons[type] || icons.info}"></i>
    <span>${escapeHtml(message)}</span>
    <button type="button" class="qcp-alert-close" aria-label="Dismiss notification">
      <i class="fa-solid fa-xmark"></i>
    </button>
  `;

    stack.appendChild(alertEl);
    requestAnimationFrame(() => alertEl.classList.add('qcp-alert-show'));

    const dismiss = () => {
        alertEl.classList.remove('qcp-alert-show');
        setTimeout(() => alertEl.remove(), 350);
    };

    alertEl.querySelector('.qcp-alert-close').addEventListener('click', dismiss);
    const timer = setTimeout(dismiss, duration);
    alertEl.addEventListener('mouseenter', () => clearTimeout(timer));
}

function escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

// Expose globally so dashboard.js and inline handlers can reuse it
window.showAlert = showAlert;

/* --------------------------------------------------------------------------
   6. SCROLL REVEAL ANIMATIONS
   Applies to key content blocks without requiring markup changes.
   -------------------------------------------------------------------------- */
function initScrollReveal() {
    const selectors = [
        '.stat-card',
        '.complaint-information-card',
        '.quick-action-content a',
        '.action-buttons a',
        '.login-form-content',
        '.complaint-form-wrapper',
        '.complaint-history-table',
        '.complaint-table',
        '.complaints-table',
        '.update-status-section',
    ];

    const elements = document.querySelectorAll(selectors.join(', '));
    if (!elements.length) return;

    if (!('IntersectionObserver' in window)) {
        elements.forEach((el) => el.classList.add('reveal-visible'));
        return;
    }

    elements.forEach((el, index) => {
        el.classList.add('reveal-init');
        el.style.transitionDelay = `${Math.min(index * 60, 360)}ms`;
    });

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('reveal-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
    );

    elements.forEach((el) => observer.observe(el));
}