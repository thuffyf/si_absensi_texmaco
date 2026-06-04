/**
 * NFC Admin Dashboard - Main JavaScript
 * Handle navigation, interactions, and real-time updates
 */

document.addEventListener("DOMContentLoaded", function () {
    if (document.querySelectorAll(".nav-item").length > 0) {
        initializeDashboard();
    }
});

function initializeDashboard() {
    // Initialize navigation
    initializeNavigation();

    // Initialize real-time features
    initializeRealtimeMonitoring();

    // Initialize animations
    initializeAnimations();

    // Initialize notifications
    initializeNotifications();
}

/**
 * Initialize sidebar navigation
 */
function initializeNavigation() {
    const navItems = document.querySelectorAll(".nav-item");

    navItems.forEach((item) => {
        item.addEventListener("click", function (e) {
            e.preventDefault();

            // Remove active class from all items
            navItems.forEach((nav) => nav.classList.remove("active"));

            // Add active class to clicked item
            this.classList.add("active");

            // Update page title
            const page = this.dataset.page;
            updatePageTitle(page);

            // Load page content
            loadPageContent(page);
        });
    });
}

/**
 * Update page title based on current page
 */
function updatePageTitle(page) {
    const titles = {
        dashboard: "Dashboard",
        "monitoring-nfc": "📡 NFC Monitor",
        siswa: "👥 Data Siswa",
        guru: "👨‍🏫 Data Guru",
        jadwal: "📅 Jadwal",
        "request-izin": "📋 Izin & Sakit",
        laporan: "📊 Laporan",
        alat: "📡 Alat NFC",
        settings: "⚙️ Pengaturan",
    };

    const pageTitle = document.getElementById("page-title");
    if (pageTitle) {
        pageTitle.textContent = titles[page] || page;
    }
}

/**
 * Load page content dynamically (simulated)
 */
function loadPageContent(page) {
    const contentArea = document.getElementById("content-area");

    // In a real application, this would fetch content via AJAX
    // contentArea.innerHTML = `Loading ${page}...`;
}

/**
 * Initialize real-time monitoring
 */
function initializeRealtimeMonitoring() {
    const eventStream = document.querySelector('[id*="event-stream"]');
    if (!eventStream) return;

    // Simulate real-time events
    setInterval(function () {
        simulateRealtimeEvent();
    }, 5000);
}

/**
 * Simulate real-time scan event
 */
function simulateRealtimeEvent() {
    const eventStream = document.querySelector('[id*="event-stream"]');

    if (eventStream) {
        // This would be connected to a WebSocket in production
    }
}

/**
 * Initialize animations on scroll
 */
function initializeAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px",
    };

    const observer = new IntersectionObserver(function (entries) {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add("animate-fade-in");
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('[class*="animate-"]').forEach((element) => {
        observer.observe(element);
    });
}

/**
 * Initialize notification system
 */
function initializeNotifications() {
    const notificationBtn = document.getElementById("notification-btn");

    if (notificationBtn && notificationBtn.tagName !== "A") {
        notificationBtn.addEventListener("click", function () {
            showNotificationPanel();
        });
    }
}

/**
 * Show notification panel
 */
function showNotificationPanel() {
    // This would show a notification dropdown
}

/**
 * Format time for display
 */
function formatTime(date) {
    const hours = String(date.getHours()).padStart(2, "0");
    const minutes = String(date.getMinutes()).padStart(2, "0");
    const seconds = String(date.getSeconds()).padStart(2, "0");
    return `${hours}:${minutes}:${seconds}`;
}

/**
 * Show toast notification
 */
function showToast(message, type = "info") {
    const toast = document.createElement("div");
    toast.className = `glass-card p-4 rounded-2xl fixed bottom-6 right-6 text-sm font-semibold z-50`;

    const colors = {
        success: "text-emerald-400",
        error: "text-red-400",
        warning: "text-yellow-400",
        info: "text-neon-cyan",
    };

    toast.classList.add(colors[type] || colors["info"]);
    toast.textContent = message;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 3000);
}

/**
 * Export utility functions for global access
 */
window.DashboardUtils = {
    showToast,
    formatTime,
    initializeDashboard,
};
