// settings.js - Dedicated settings modal handling
document.addEventListener('DOMContentLoaded', () => {
    class SettingsModalSystem {
        constructor() {
            this.modalMap = {
                logoModal: {
                    open: document.querySelector('[data-settings-modal="logoModal"]'),
                    save: document.getElementById('saveLogoSettings')
                },
                themesModal: {
                    open: document.querySelector('[data-settings-modal="themesModal"]'),
                    save: document.getElementById('saveThemeSettings'),
                    select: document.getElementById('themeSelect')
                },
                lightspeedModal: {
                    open: document.querySelector('[data-settings-modal="lightspeedModal"]'),
                    save: document.getElementById('saveLightspeedSettings')
                }
            };

            this.init();
            this.initTheme();
        }

        init() {
            // Event delegation for settings modals
            document.body.addEventListener('click', (e) => {
                // Handle modal opens
                const openTrigger = e.target.closest('[data-settings-modal]');
                if (openTrigger) {
                    const modalId = openTrigger.dataset.settingsModal;
                    this.openModal(modalId);
                }

                // Handle modal closures
                if (e.target.closest('[data-settings-modal-close]') || 
                    e.target.classList.contains('settings-modal-backdrop')) {
                    this.closeAllModals();
                }
            });

            // Initialize save handlers
            Object.entries(this.modalMap).forEach(([modalId, { save }]) => {
                if (save) {
                    save.addEventListener('click', () => this.handleSave(modalId));
                }
            });

            // Escape key handler
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') this.closeAllModals();
            });
        }

        // In the initTheme method - Line 45
initTheme() {
    // Change the default from 'light' to undefined (true default)
    const savedTheme = localStorage.getItem('theme'); // Remove || 'light'
    this.applyTheme(savedTheme || 'light'); // Pass light as default but don't store it
    
    // Initialize theme selector
    if (this.modalMap.themesModal.select) {
        this.modalMap.themesModal.select.value = savedTheme || 'light';
    }
}

// In the applyTheme method - Line 57
// In your applyTheme method
applyTheme(theme) {
    const html = document.documentElement;
    const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    // Remove existing classes
    html.classList.remove('dark', 'light');

    // Apply conditional classes
    if (theme === 'system') {
        html.classList.toggle('dark', systemDark);
    } else if (theme === 'dark') {
        html.classList.add('dark');
    }
    // Light mode: no class needed (default)

    // Store preference
    if (theme === 'light') {
        localStorage.removeItem('theme');
    } else {
        localStorage.setItem('theme', theme);
    }
}


        openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;
            
            // Preselect current theme when opening theme modal
            if (modalId === 'themesModal' && this.modalMap.themesModal.select) {
                const currentTheme = localStorage.getItem('theme') || 'light';
                this.modalMap.themesModal.select.value = currentTheme;
            }
            
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        closeAllModals() {
            Object.keys(this.modalMap).forEach(modalId => this.closeModal(modalId));
        }

        handleSave(modalId) {
            const formData = new FormData(document.querySelector(`#${modalId} form`));
            
            if (modalId === 'themesModal') {
                const selectedTheme = formData.get('theme');
                this.applyTheme(selectedTheme);
            }

            console.log(`Saving ${modalId}`, Object.fromEntries(formData));
            this.closeModal(modalId);
        }
    }

    // Initialize only on settings page
    if (document.querySelector('[data-page="settings"]')) {
        new SettingsModalSystem();
    }
});