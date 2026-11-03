/**
 * Sidebar State Controller — Mobile Drawer & Desktop PIN Support
 */

// Run BEFORE DOM to prevent desktop flicker
(function () {
    if (window.innerWidth >= 992) {
        var pinned = localStorage.getItem('sidebarPinned');
        if (pinned === null) {
            localStorage.setItem('sidebarPinned', 'true');
            pinned = 'true';
        }
        if (pinned === 'false') {
            document.documentElement.classList.add('sidebar-collapse-pending');
        }
    }
})();

document.addEventListener('DOMContentLoaded', function () {

    function isMobile() {
        return window.innerWidth < 992;
    }

    function isPinned() {
        return localStorage.getItem('sidebarPinned') !== 'false';
    }

    function updateIcons() {
        var arrowIcon = document.getElementById('sidebar-arrow-icon');
        if (arrowIcon) {
            if (isPinned()) {
                arrowIcon.classList.remove('fa-chevron-circle-right');
                arrowIcon.classList.add('fa-chevron-circle-left');
                arrowIcon.setAttribute('title', 'Unpin sidebar');
            } else {
                arrowIcon.classList.remove('fa-chevron-circle-left');
                arrowIcon.classList.add('fa-chevron-circle-right');
                arrowIcon.setAttribute('title', 'Pin sidebar');
            }
        }
    }

    function forceDesktopSidebarOpen() {
        document.body.classList.remove('sidebar-collapse');
        document.body.classList.remove('sidebar-closed');
        document.body.classList.remove('sidebar-hover-expanded');
    }

    function forceDesktopSidebarClose() {
        document.body.classList.add('sidebar-collapse');
        document.body.classList.remove('sidebar-hover-expanded');
    }

    function ensureMobileOverlay() {
        var overlay = document.getElementById('sidebar-overlay');
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.id = 'sidebar-overlay';
            document.querySelector('.wrapper')?.appendChild(overlay);
        }
        return overlay;
    }

    function toggleMobileSidebar() {
        if (document.body.classList.contains('sidebar-open')) {
            document.body.classList.remove('sidebar-open');
        } else {
            document.body.classList.remove('sidebar-closed');
            document.body.classList.remove('sidebar-collapse');
            document.body.classList.add('sidebar-open');
            ensureMobileOverlay();
        }
    }

    function closeMobileSidebar() {
        document.body.classList.remove('sidebar-open');
    }

    // ── Apply on load ──────────────────────────────────────────────────
    setTimeout(function () {
        // Disable AdminLTE's internal pushmenu listener to avoid double-events
        $(document).off('click.lte.pushmenu');
        $('[data-widget="pushmenu"]').off('click');

        if (!isMobile()) {
            if (isPinned()) {
                forceDesktopSidebarOpen();
            } else {
                forceDesktopSidebarClose();
            }
        } else {
            document.body.classList.remove('sidebar-open');
            document.body.classList.remove('sidebar-collapse');
        }

        document.documentElement.classList.remove('sidebar-collapse-pending');
        updateIcons();

        // MutationObserver for desktop pinned state only
        var observer = new MutationObserver(function () {
            if (!isMobile() && isPinned() && document.body.classList.contains('sidebar-collapse')) {
                observer.disconnect();
                forceDesktopSidebarOpen();
                observer.observe(document.body, { attributes: true, attributeFilter: ['class'] });
            }
        });
        observer.observe(document.body, { attributes: true, attributeFilter: ['class'] });

    }, 100);

    // ── Single-Click Sidebar Toggle Handler ─────────────────────────────
    $(document).on('click', '#sidebar-pin-btn', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        e.stopPropagation();

        if (isMobile()) {
            toggleMobileSidebar();
        } else {
            if (isPinned()) {
                localStorage.setItem('sidebarPinned', 'false');
                forceDesktopSidebarClose();
            } else {
                localStorage.setItem('sidebarPinned', 'true');
                forceDesktopSidebarOpen();
            }
            updateIcons();
        }
        return false;
    });

    // ── Close mobile sidebar when clicking backdrop overlay ────────────
    $(document).on('click', '#sidebar-overlay', function (e) {
        e.preventDefault();
        closeMobileSidebar();
    });

    // ── Close mobile sidebar when clicking a nav link ──────────────────
    $(document).on('click', '.main-sidebar .nav-link', function () {
        if (isMobile() && !$(this).hasClass('dropdown-toggle') && $(this).attr('href') && $(this).attr('href') !== '#') {
            closeMobileSidebar();
        }
    });

    // ── Handle window resize ───────────────────────────────────────────
    window.addEventListener('resize', function () {
        if (!isMobile()) {
            closeMobileSidebar();
            if (isPinned()) {
                forceDesktopSidebarOpen();
            } else {
                forceDesktopSidebarClose();
            }
        }
    });
});


