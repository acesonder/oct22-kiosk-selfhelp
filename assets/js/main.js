/**
 * KioskHelp - Main JavaScript
 * Common functions and utilities
 */

// API Base URL
const API_URL = 'api/';

/**
 * Show alert message
 */
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    alertDiv.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; animation: slideIn 0.3s ease-out;';
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => alertDiv.remove(), 300);
    }, 5000);
}

/**
 * Show loading spinner
 */
function showLoading(element = null) {
    const spinner = document.createElement('div');
    spinner.className = 'spinner';
    spinner.id = 'global-spinner';
    
    if (element) {
        element.style.position = 'relative';
        spinner.style.position = 'absolute';
        spinner.style.left = '50%';
        spinner.style.top = '50%';
        spinner.style.transform = 'translate(-50%, -50%)';
        element.appendChild(spinner);
    } else {
        spinner.style.cssText = 'position: fixed; left: 50%; top: 50%; z-index: 9999; transform: translate(-50%, -50%);';
        document.body.appendChild(spinner);
    }
    
    return spinner;
}

/**
 * Hide loading spinner
 */
function hideLoading() {
    const spinner = document.getElementById('global-spinner');
    if (spinner) {
        spinner.remove();
    }
}

/**
 * Modal Manager
 */
class Modal {
    constructor(modalId) {
        this.modal = document.getElementById(modalId);
        this.init();
    }
    
    init() {
        if (!this.modal) return;
        
        // Close button
        const closeBtn = this.modal.querySelector('.modal-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => this.close());
        }
        
        // Click outside to close
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.close();
            }
        });
    }
    
    open() {
        if (this.modal) {
            this.modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }
    
    close() {
        if (this.modal) {
            this.modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }
    
    static create(title, content, buttons = []) {
        const modalId = 'dynamic-modal-' + Date.now();
        const modalHTML = `
            <div id="${modalId}" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">${title}</h3>
                        <button class="modal-close">&times;</button>
                    </div>
                    <div class="modal-body">
                        ${content}
                    </div>
                    <div class="modal-footer">
                        ${buttons.map(btn => `
                            <button class="btn btn-${btn.type || 'primary'}" onclick="${btn.action}">${btn.text}</button>
                        `).join('')}
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        const modal = new Modal(modalId);
        modal.open();
        return modal;
    }
}

/**
 * Form Validation
 */
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    let isValid = true;
    const inputs = form.querySelectorAll('[required]');
    
    inputs.forEach(input => {
        const errorDiv = input.parentElement.querySelector('.form-error');
        
        if (!input.value.trim()) {
            isValid = false;
            input.style.borderColor = 'var(--error-color)';
            if (errorDiv) {
                errorDiv.textContent = 'This field is required';
            }
        } else {
            input.style.borderColor = '';
            if (errorDiv) {
                errorDiv.textContent = '';
            }
        }
        
        // Email validation
        if (input.type === 'email' && input.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(input.value)) {
                isValid = false;
                input.style.borderColor = 'var(--error-color)';
                if (errorDiv) {
                    errorDiv.textContent = 'Please enter a valid email address';
                }
            }
        }
        
        // Password match validation
        if (input.name === 'confirm_password') {
            const password = form.querySelector('[name="password"]');
            if (password && input.value !== password.value) {
                isValid = false;
                input.style.borderColor = 'var(--error-color)';
                if (errorDiv) {
                    errorDiv.textContent = 'Passwords do not match';
                }
            }
        }
    });
    
    return isValid;
}

/**
 * AJAX Request Helper
 */
async function ajaxRequest(url, method = 'GET', data = null) {
    const options = {
        method: method,
        headers: {
            'Content-Type': 'application/json',
        }
    };
    
    if (data && method !== 'GET') {
        options.body = JSON.stringify(data);
    }
    
    try {
        const response = await fetch(url, options);
        return await response.json();
    } catch (error) {
        console.error('AJAX Error:', error);
        return { success: false, message: 'Network error occurred' };
    }
}

/**
 * Format date
 */
function formatDate(dateString, format = 'M d, Y') {
    const date = new Date(dateString);
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    
    const replacements = {
        'Y': date.getFullYear(),
        'y': String(date.getFullYear()).slice(-2),
        'M': months[date.getMonth()],
        'm': String(date.getMonth() + 1).padStart(2, '0'),
        'd': String(date.getDate()).padStart(2, '0'),
        'H': String(date.getHours()).padStart(2, '0'),
        'i': String(date.getMinutes()).padStart(2, '0'),
        's': String(date.getSeconds()).padStart(2, '0'),
    };
    
    return format.replace(/Y|y|M|m|d|H|i|s/g, match => replacements[match]);
}

/**
 * Debounce function
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Theme Manager
 */
const ThemeManager = {
    currentTheme: localStorage.getItem('theme') || 'light',
    
    init() {
        this.apply(this.currentTheme);
        
        // Listen for theme toggle
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('theme-toggle')) {
                this.toggle();
            }
        });
    },
    
    apply(theme) {
        if (theme === 'dark') {
            document.body.classList.add('dark-theme');
        } else {
            document.body.classList.remove('dark-theme');
        }
        this.currentTheme = theme;
        localStorage.setItem('theme', theme);
    },
    
    toggle() {
        const newTheme = this.currentTheme === 'light' ? 'dark' : 'light';
        this.apply(newTheme);
    }
};

/**
 * Initialize on page load
 */
document.addEventListener('DOMContentLoaded', () => {
    // Initialize theme
    ThemeManager.init();
    
    // Auto-hide alerts after 5 seconds
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
    
    // Form validation on submit
    document.querySelectorAll('form[data-validate]').forEach(form => {
        form.addEventListener('submit', (e) => {
            if (!validateForm(form.id)) {
                e.preventDefault();
            }
        });
    });
    
    // Real-time validation on input
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('blur', () => {
            const form = input.closest('form');
            if (form && form.hasAttribute('data-validate')) {
                validateForm(form.id);
            }
        });
    });
});

// Add CSS animations for alerts
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
