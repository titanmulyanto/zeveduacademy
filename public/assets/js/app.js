/**
 * ZevedU Academy - Student Dashboard JavaScript
 * Handles: Tab switching, Module accordion, Chat scroll, etc.
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('ZevedU Academy loaded');

    // Initialize all functionality
    initTabSwitching();
    initModuleAccordion();
    initChatScroll();
    initMobileMenu();
});

/**
 * Tab Switching for Class Detail Page
 */
function initTabSwitching() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    if (tabButtons.length === 0) return;

    tabButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const tabName = this.id.replace('tab-', '');

            // Update button states
            tabButtons.forEach(b => {
                b.classList.remove('active', 'bg-white', 'shadow-sm', 'text-blue-600');
                b.classList.add('text-slate-600');
            });
            this.classList.add('active', 'bg-white', 'shadow-sm', 'text-blue-600');
            this.classList.remove('text-slate-600');

            // Update content visibility
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });

            const targetContent = document.getElementById('content-' + tabName);
            if (targetContent) {
                targetContent.classList.remove('hidden');
            }
        });
    });
}

/**
 * Module Accordion for Video List
 */
function initModuleAccordion() {
    const moduleButtons = document.querySelectorAll('[onclick^="toggleModule"]');

    if (moduleButtons.length === 0) return;

    moduleButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Extract module index from onclick attribute
            const match = this.getAttribute('onclick').match(/toggleModule\((\d+)\)/);
            if (!match) return;

            const moduleIndex = match[1];
            const content = document.getElementById('module-content-' + moduleIndex);
            const arrow = document.getElementById('arrow-' + moduleIndex);

            if (!content) return;

            if (content.classList.contains('hidden')) {
                // Close all other modules first
                document.querySelectorAll('.module-content, [id^="module-content-"]').forEach(c => {
                    if (c !== content) c.classList.add('hidden');
                });
                document.querySelectorAll('.module-arrow').forEach(a => {
                    if (a !== arrow) a.style.transform = 'rotate(0deg)';
                });

                // Open this module
                content.classList.remove('hidden');
                if (arrow) arrow.style.transform = 'rotate(180deg)';
            } else {
                // Close this module
                content.classList.add('hidden');
                if (arrow) arrow.style.transform = 'rotate(0deg)';
            }
        });
    });
}

/**
 * Auto-scroll chat container to bottom
 */
function initChatScroll() {
    const chatContainer = document.getElementById('chatContainer');
    if (chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
}

/**
 * Mobile Menu Toggle
 */
function initMobileMenu() {
    const mobileMenuBtn = document.querySelector('[onclick*="mobile-menu"]');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
}

/**
 * Global Tab Switch Function (called from HTML onclick)
 */
function switchTab(tabName) {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    // Update button states
    tabButtons.forEach(btn => {
        const btnTabName = btn.id.replace('tab-', '');
        if (btnTabName === tabName) {
            btn.classList.add('active', 'bg-white', 'shadow-sm', 'text-blue-600');
            btn.classList.remove('text-slate-600');
        } else {
            btn.classList.remove('active', 'bg-white', 'shadow-sm', 'text-blue-600');
            btn.classList.add('text-slate-600');
        }
    });

    // Update content visibility
    tabContents.forEach(content => {
        content.classList.add('hidden');
    });

    const targetContent = document.getElementById('content-' + tabName);
    if (targetContent) {
        targetContent.classList.remove('hidden');
    }
}

/**
 * Global Module Toggle Function (called from HTML onclick)
 */
function toggleModule(moduleIndex) {
    const content = document.getElementById('module-content-' + moduleIndex);
    const arrow = document.getElementById('arrow-' + moduleIndex);

    if (!content) return;

    if (content.classList.contains('hidden')) {
        // Close all other modules
        document.querySelectorAll('[id^="module-content-"]').forEach(c => {
            if (c !== content) c.classList.add('hidden');
        });
        document.querySelectorAll('.module-arrow').forEach(a => {
            if (a !== arrow) a.style.transform = 'rotate(0deg)';
        });

        // Open this module
        content.classList.remove('hidden');
        if (arrow) arrow.style.transform = 'rotate(180deg)';
    } else {
        // Close this module
        content.classList.add('hidden');
        if (arrow) arrow.style.transform = 'rotate(0deg)';
    }
}

/**
 * Confirm form submission
 */
function confirmAction(message) {
    return confirm(message || 'Apakah Anda yakin?');
}

/**
 * Show loading state on button
 */
function setButtonLoading(button) {
    if (!button) return;
    button.disabled = true;
    button.classList.add('loading');
    const originalText = button.innerHTML;
    button.innerHTML = '<span class="loading loading-spinner"></span> Loading...';
    button.dataset.originalText = originalText;
}

/**
 * Reset button state
 */
function resetButton(button) {
    if (!button) return;
    button.disabled = false;
    button.classList.remove('loading');
    if (button.dataset.originalText) {
        button.innerHTML = button.dataset.originalText;
    }
}