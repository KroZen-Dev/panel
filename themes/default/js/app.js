import Alpine from "alpinejs";
import axios from "axios";
import Swal from "sweetalert2";
import tippy from "tippy.js";
import "tippy.js/dist/tippy.css";

import "@fortawesome/fontawesome-free/css/all.min.css";

window.Alpine = Alpine;
window.axios = axios;
window.Swal = Swal;
window.Tippy = tippy;

// Axios defaults
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Attach CSRF token from meta tag if present (Laravel)
const tokenMeta = document.head.querySelector('meta[name="csrf-token"]');
if (tokenMeta) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = tokenMeta.content;
}

// Alpine magic helper for currency formatting
Alpine.magic("currency", () => {
    return {
        format: (amount) => {
            return (amount / 1000).toFixed(2);
        },
    };
});

// Theme store
Alpine.store("theme", {
    dark: document.documentElement.dataset.theme === "dark",
    set(theme) {
        window.setTheme(theme);
        this.dark = theme === "dark";
    },
    setSystem() {
        // Get system preference
        const prefersDark = window.matchMedia(
            "(prefers-color-scheme: dark)"
        ).matches;
        const theme = prefersDark ? "dark" : "light";
        this.set(theme);
    },
    toggle() {
        const next = this.dark ? "light" : "dark";
        this.set(next);
    },
});

// SweetAlert2 base styling (kept simple and neutral)
const swalBaseClasses = {
    popup: "rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 shadow-xl",
    title: "text-gray-900 dark:text-white font-semibold",
    htmlContainer: "text-gray-700 dark:text-gray-300",
    confirmButton:
        "bg-accent-600 hover:bg-accent-500 text-white font-semibold px-5 py-2.5 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-accent-500",
    cancelButton:
        "bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold px-5 py-2.5 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500",
    actions: "gap-3",
};

const SwalCustom = Swal.mixin({
    customClass: swalBaseClasses,
    buttonsStyling: false,
});

const SwalToast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3500,
    timerProgressBar: true,
    customClass: {
        popup: "rounded-lg bg-white/95 dark:bg-gray-900/95 border border-gray-200 dark:border-gray-700 shadow-lg",
        title: "text-gray-900 dark:text-white font-semibold text-sm",
        htmlContainer: "text-gray-700 dark:text-gray-300 text-sm",
        timerProgressBar: "bg-gray-200 dark:bg-gray-700 h-1",
    },
    buttonsStyling: false,
});

const toastIconColors = {
    success: "rgb(var(--success))",
    error: "rgb(var(--danger))",
    warning: "rgb(var(--warning))",
    info: "rgb(var(--info))",
};

window.SwalCustom = SwalCustom;
window.SwalToast = SwalToast;

window.flashMessage = (type, message, options = {}) => {
    if (!message) return;
    const icon = type ?? "info";
    const isToast = icon !== "error";
    const baseOptions = {
        icon,
        title: message,
        iconColor: toastIconColors[icon] ?? "rgb(var(--info))",
    };

    if (isToast) {
        return SwalToast.fire({
            ...baseOptions,
            ...options,
        });
    }

    return SwalCustom.fire({
        ...baseOptions,
        text: undefined,
        html: message,
        showConfirmButton: true,
        ...options,
    });
};

// Start Alpine
Alpine.start();

// Initialize plugins when DOM is ready
document.addEventListener("DOMContentLoaded", () => {
    // Initialize Select2 with Tailwind styling
    if (window.$ && typeof window.$.fn.select2 === "function") {
        window.$(".select2").select2({
            theme: "default",
            width: "100%",
            dropdownAutoWidth: true,
        });
    }

    // Initialize Tippy.js tooltips
    if (window.Tippy) {
        window.Tippy("[data-tippy-content], [title]", {
            theme: "dark",
            placement: "top",
            arrow: true,
            animation: "scale",
            allowHTML: true,
            interactive: true,
            maxWidth: "28rem",
        });
    }

    if (window.__FLASH__) {
        Object.entries(window.__FLASH__).forEach(([type, payload]) => {
            if (!payload) return;
            const message = Array.isArray(payload)
                ? payload.join("<br>")
                : payload;
            window.flashMessage(type, message);
        });
    }
});
