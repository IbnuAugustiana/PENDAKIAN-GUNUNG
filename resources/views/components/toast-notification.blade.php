<!-- Komponen Toast Notification Modern -->
<div id="toast-container" class="toast-container">
    <!-- Toast template akan di-generate oleh JavaScript -->
</div>

<style>
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    pointer-events: none;
}

.toast {
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
    padding: 16px 20px;
    margin-bottom: 12px;
    min-width: 320px;
    max-width: 400px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    border-left: 4px solid;
    backdrop-filter: blur(10px);
    pointer-events: auto;
    transform: translateX(400px);
    opacity: 0;
    transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.toast.show {
    transform: translateX(0);
    opacity: 1;
}

.toast.hide {
    transform: translateX(400px);
    opacity: 0;
}

.toast.success {
    border-left-color: #28a745;
    background: linear-gradient(135deg, #f8fff9 0%, #ffffff 100%);
}

.toast.error {
    border-left-color: #dc3545;
    background: linear-gradient(135deg, #fff8f8 0%, #ffffff 100%);
}

.toast.warning {
    border-left-color: #ffc107;
    background: linear-gradient(135deg, #fffef8 0%, #ffffff 100%);
}

.toast.info {
    border-left-color: #17a2b8;
    background: linear-gradient(135deg, #f8ffff 0%, #ffffff 100%);
}

.toast-icon {
    font-size: 20px;
    flex-shrink: 0;
    margin-top: 2px;
}

.toast.success .toast-icon { color: #28a745; }
.toast.error .toast-icon { color: #dc3545; }
.toast.warning .toast-icon { color: #ffc107; }
.toast.info .toast-icon { color: #17a2b8; }

.toast-content {
    flex: 1;
    min-width: 0;
}

.toast-title {
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 4px;
    color: #2c3e50;
}

.toast-message {
    font-size: 13px;
    color: #6c757d;
    line-height: 1.4;
    word-wrap: break-word;
}

.toast-close {
    background: none;
    border: none;
    font-size: 18px;
    color: #adb5bd;
    cursor: pointer;
    padding: 0;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.toast-close:hover {
    background: rgba(0, 0, 0, 0.05);
    color: #495057;
}

.toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    background: currentColor;
    border-radius: 0 0 12px 12px;
    width: 100%;
    transform-origin: left;
    animation: progress 5s linear forwards;
}

.toast.success .toast-progress { color: #28a745; }
.toast.error .toast-progress { color: #dc3545; }
.toast.warning .toast-progress { color: #ffc107; }
.toast.info .toast-progress { color: #17a2b8; }

@keyframes progress {
    from { transform: scaleX(1); }
    to { transform: scaleX(0); }
}

/* Responsive */
@media (max-width: 480px) {
    .toast-container {
        left: 10px;
        right: 10px;
        top: 10px;
    }

    .toast {
        min-width: auto;
        max-width: none;
        margin-bottom: 8px;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .toast {
        background: #2d3748;
        color: #e2e8f0;
    }

    .toast-title {
        color: #f7fafc;
    }

    .toast-message {
        color: #a0aec0;
    }

    .toast-close:hover {
        background: rgba(255, 255, 255, 0.1);
    }
}
</style>

<script>
// Toast Notification System
class ToastNotification {
    constructor() {
        this.container = document.getElementById('toast-container');
        this.toasts = [];
        this.init();
    }

    init() {
        // Auto-trigger toasts from session messages
        this.triggerFromSession();
    }

    show(message, type = 'info', title = '', duration = 5000) {
        const toast = this.createToast(message, type, title, duration);
        this.container.appendChild(toast);

        // Trigger animation
        setTimeout(() => toast.classList.add('show'), 10);

        // Auto hide
        const hideTimeout = setTimeout(() => {
            this.hide(toast);
        }, duration);

        // Manual close
        const closeBtn = toast.querySelector('.toast-close');
        closeBtn.addEventListener('click', () => {
            clearTimeout(hideTimeout);
            this.hide(toast);
        });

        this.toasts.push(toast);
        return toast;
    }

    createToast(message, type, title, duration) {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;

        const icon = this.getIcon(type);
        const titleText = title || this.getDefaultTitle(type);

        toast.innerHTML = `
            <div class="toast-icon">${icon}</div>
            <div class="toast-content">
                <div class="toast-title">${titleText}</div>
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close">&times;</button>
            <div class="toast-progress"></div>
        `;

        return toast;
    }

    hide(toast) {
        toast.classList.add('hide');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
            this.toasts = this.toasts.filter(t => t !== toast);
        }, 300);
    }

    getIcon(type) {
        const icons = {
            success: '✅',
            error: '❌',
            warning: '⚠️',
            info: 'ℹ️'
        };
        return icons[type] || icons.info;
    }

    getDefaultTitle(type) {
        const titles = {
            success: 'Berhasil',
            error: 'Error',
            warning: 'Peringatan',
            info: 'Informasi'
        };
        return titles[type] || titles.info;
    }

    triggerFromSession() {
        // Check for Laravel session messages
        const successMsg = '{{ session("success") }}';
        const errorMsg = '{{ session("error") }}';
        const warningMsg = '{{ session("warning") }}';
        const infoMsg = '{{ session("info") }}';

        if (successMsg && successMsg.trim() !== '') {
            this.show(successMsg, 'success');
        }
        if (errorMsg && errorMsg.trim() !== '') {
            this.show(errorMsg, 'error');
        }
        if (warningMsg && warningMsg.trim() !== '') {
            this.show(warningMsg, 'warning');
        }
        if (infoMsg && infoMsg.trim() !== '') {
            this.show(infoMsg, 'info');
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.toast = new ToastNotification();
});

// Global function for manual triggering
function showToast(message, type = 'info', title = '', duration = 5000) {
    if (window.toast) {
        return window.toast.show(message, type, title, duration);
    }
}
</script>
