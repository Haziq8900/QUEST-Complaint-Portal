/* ==========================================================================
   QUEST COMPLAINT PORTAL — DASHBOARD SCRIPT
   Animated stat counters, card entrance animation, live table search,
   status filtering, status badges, and a custom confirmation modal.
   Loaded on student_dashboard.php, admin_dashboard.php, admin_complaints.php,
   complaint_history.php and admin_complaint_details.php.
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
    animateStatCounters();
    animateCardEntrance();
    applyStatusBadges();
    enhanceComplaintTables();
    guardStatusUpdateForm();
    guardLogoutLink();
});

/* --------------------------------------------------------------------------
   1. ANIMATED STATISTICS COUNTERS
   Counts each .stat-number up from 0 to its PHP-rendered value.
   -------------------------------------------------------------------------- */
function animateStatCounters() {
    const counters = document.querySelectorAll('.stat-number');
    if (!counters.length) return;

    const animate = (el) => {
        const target = parseInt(el.textContent.replace(/[^\d-]/g, ''), 10);
        if (Number.isNaN(target)) return;

        const duration = 900;
        const start = performance.now();
        const from = 0;

        const step = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3); // ease-out cubic
            const value = Math.round(from + (target - from) * eased);
            el.textContent = value.toLocaleString();

            if (progress < 1) {
                requestAnimationFrame(step);
            } else {
                el.textContent = target.toLocaleString();
            }
        };

        requestAnimationFrame(step);
    };

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        animate(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.4 }
        );
        counters.forEach((el) => observer.observe(el));
    } else {
        counters.forEach(animate);
    }
}

/* --------------------------------------------------------------------------
   2. DASHBOARD CARD ENTRANCE ANIMATION
   Staggers stat cards and information cards in on load.
   -------------------------------------------------------------------------- */
function animateCardEntrance() {
    const cards = document.querySelectorAll(
        '.stat-card, .complaint-information-card, .quick-action-content a, .action-buttons a'
    );

    cards.forEach((card, index) => {
        card.classList.add('qcp-card-init');
        setTimeout(() => {
            card.classList.add('qcp-card-in');
        }, index * 70);
    });
}

/* --------------------------------------------------------------------------
   3. STATUS BADGES
   Wraps the raw status text PHP echoes into <td> cells with a styled
   badge span, so "Pending" / "In Progress" / "Resolved" read as tags
   instead of plain text — without editing the PHP templates.
   -------------------------------------------------------------------------- */
function applyStatusBadges() {
    const statusMap = {
        pending: 'status-pending',
        'in progress': 'status-in-progress',
        resolved: 'status-resolved',
    };

    document.querySelectorAll('table tbody tr').forEach((row) => {
        row.querySelectorAll('td').forEach((cell) => {
            const text = cell.textContent.trim();
            const key = text.toLowerCase();

            if (statusMap[key] && !cell.querySelector('.status-badge')) {
                cell.innerHTML = `<span class="status-badge ${statusMap[key]}">${text}</span>`;
                cell.dataset.status = key;
            }
        });
    });
}

/* --------------------------------------------------------------------------
   4. COMPLAINT TABLE SEARCH + STATUS FILTERING
   Injects a toolbar (search box + status filter chips) above any
   complaint table and filters visible rows client-side.
   -------------------------------------------------------------------------- */
function enhanceComplaintTables() {
    const tableWrappers = document.querySelectorAll(
        '.complaint-history-table, .complaint-table, .complaints-table'
    );

    tableWrappers.forEach((wrapper) => {
        const table = wrapper.querySelector('table');
        const tbody = table?.querySelector('tbody');
        if (!table || !tbody) return;

        const rows = Array.from(tbody.querySelectorAll('tr'));
        if (!rows.length) return;

        const toolbar = buildTableToolbar();
        wrapper.parentElement.insertBefore(toolbar, wrapper);

        const searchInput = toolbar.querySelector('.qcp-search-input');
        const filterButtons = toolbar.querySelectorAll('.qcp-filter-btn');
        let activeStatus = 'all';

        const applyFilters = () => {
            const query = searchInput.value.trim().toLowerCase();
            let visibleCount = 0;

            rows.forEach((row) => {
                const matchesQuery = !query || row.textContent.toLowerCase().includes(query);
                const rowStatus = row.querySelector('[data-status]')?.dataset.status || '';
                const matchesStatus = activeStatus === 'all' || rowStatus === activeStatus;
                const visible = matchesQuery && matchesStatus;

                row.style.display = visible ? '' : 'none';
                if (visible) visibleCount += 1;
            });

            toggleEmptyState(tbody, rows, visibleCount);
        };

        searchInput.addEventListener('input', applyFilters);

        filterButtons.forEach((btn) => {
            btn.addEventListener('click', () => {
                filterButtons.forEach((b) => b.classList.remove('qcp-filter-active'));
                btn.classList.add('qcp-filter-active');
                activeStatus = btn.dataset.status;
                applyFilters();
            });
        });
    });
}

function buildTableToolbar() {
    const toolbar = document.createElement('div');
    toolbar.className = 'qcp-table-toolbar';
    toolbar.innerHTML = `
    <div class="qcp-search-wrap">
      <i class="fa-solid fa-magnifying-glass"></i>
      <input type="text" class="qcp-search-input" placeholder="Search complaints..." aria-label="Search complaints">
    </div>
    <div class="qcp-filter-group">
      <button type="button" class="qcp-filter-btn qcp-filter-active" data-status="all">All</button>
      <button type="button" class="qcp-filter-btn" data-status="pending">Pending</button>
      <button type="button" class="qcp-filter-btn" data-status="in progress">In Progress</button>
      <button type="button" class="qcp-filter-btn" data-status="resolved">Resolved</button>
    </div>
  `;
    return toolbar;
}

function toggleEmptyState(tbody, rows, visibleCount) {
    let emptyRow = tbody.querySelector('.qcp-empty-row');

    if (visibleCount === 0) {
        if (!emptyRow) {
            const colCount = rows[0]?.children.length || 1;
            emptyRow = document.createElement('tr');
            emptyRow.className = 'qcp-empty-row';
            emptyRow.innerHTML = `<td colspan="${colCount}">No complaints match your search.</td>`;
            tbody.appendChild(emptyRow);
        }
        emptyRow.style.display = '';
    } else if (emptyRow) {
        emptyRow.style.display = 'none';
    }
}

/* --------------------------------------------------------------------------
   5. CONFIRMATION MODAL
   Reusable custom confirm dialog. Usage:
     confirmAction({
       title: 'Update complaint?',
       message: 'This will notify the student of the new status.',
       confirmLabel: 'Update',
       danger: false,
       onConfirm: () => form.submit()
     });
   -------------------------------------------------------------------------- */
function confirmAction({ title, message, confirmLabel = 'Confirm', danger = false, onConfirm }) {
    let overlay = document.querySelector('.qcp-modal-overlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.className = 'qcp-modal-overlay';
        overlay.innerHTML = `
      <div class="qcp-modal-box" role="dialog" aria-modal="true">
        <h3 class="qcp-modal-title"></h3>
        <p class="qcp-modal-message"></p>
        <div class="qcp-modal-actions">
          <button type="button" class="qcp-modal-cancel">Cancel</button>
          <button type="button" class="qcp-modal-confirm">Confirm</button>
        </div>
      </div>
    `;
        document.body.appendChild(overlay);
    }

    overlay.querySelector('.qcp-modal-title').textContent = title;
    overlay.querySelector('.qcp-modal-message').textContent = message;

    const confirmBtn = overlay.querySelector('.qcp-modal-confirm');
    confirmBtn.textContent = confirmLabel;
    confirmBtn.classList.toggle('qcp-modal-danger', !!danger);

    const close = () => overlay.classList.remove('qcp-modal-open');
    const cancelBtn = overlay.querySelector('.qcp-modal-cancel');

    const onConfirmClick = () => {
        close();
        onConfirm?.();
        cleanup();
    };
    const onCancelClick = () => {
        close();
        cleanup();
    };
    const onOverlayClick = (e) => {
        if (e.target === overlay) onCancelClick();
    };
    const onKeydown = (e) => {
        if (e.key === 'Escape') onCancelClick();
    };

    function cleanup() {
        confirmBtn.removeEventListener('click', onConfirmClick);
        cancelBtn.removeEventListener('click', onCancelClick);
        overlay.removeEventListener('click', onOverlayClick);
        document.removeEventListener('keydown', onKeydown);
    }

    confirmBtn.addEventListener('click', onConfirmClick);
    cancelBtn.addEventListener('click', onCancelClick);
    overlay.addEventListener('click', onOverlayClick);
    document.addEventListener('keydown', onKeydown);

    requestAnimationFrame(() => overlay.classList.add('qcp-modal-open'));
}

window.confirmAction = confirmAction;

/* --------------------------------------------------------------------------
   6. GUARD: complaint status update form (admin)
   Intercepts submit to confirm before writing an admin response / status.
   -------------------------------------------------------------------------- */
function guardStatusUpdateForm() {
    const form = document.querySelector('.update-status-section form');
    if (!form) return;

    form.addEventListener('submit', (event) => {
        if (form.dataset.confirmed === 'true') return;

        event.preventDefault();
        const statusValue = form.querySelector('#status')?.value || 'this status';

        confirmAction({
            title: 'Update complaint status?',
            message: `The student will see this complaint marked as "${statusValue}" along with your response.`,
            confirmLabel: 'Update complaint',
            onConfirm: () => {
                form.dataset.confirmed = 'true';
                form.submit();
            },
        });
    });
}

/* --------------------------------------------------------------------------
   7. GUARD: logout link
   Confirms before ending the session.
   -------------------------------------------------------------------------- */
function guardLogoutLink() {
    const logoutLink = document.querySelector('a[href*="logout.php"]');
    if (!logoutLink) return;

    logoutLink.addEventListener('click', (event) => {
        event.preventDefault();
        confirmAction({
            title: 'Log out?',
            message: 'You will need to sign in again to access your dashboard.',
            confirmLabel: 'Log out',
            danger: true,
            onConfirm: () => {
                window.location.href = logoutLink.href;
            },
        });
    });
}