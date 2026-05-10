/**
 * NFC Admin Dashboard - Main JavaScript
 * Handle navigation, interactions, and real-time updates
 */

document.addEventListener("DOMContentLoaded", function () {
    initializeDashboard();
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

    console.log(`Loading page: ${page}`);

    // In a real application, this would fetch content via AJAX
    // contentArea.innerHTML = `Loading ${page}...`;
}

/**
 * Initialize real-time monitoring
 */
function initializeRealtimeMonitoring() {
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
        console.log("Real-time monitoring active");
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

    if (notificationBtn) {
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
    console.log("Showing notifications...");
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
 * Handle button actions
 */
document.addEventListener("click", function (e) {
    // Accept button for requests
    if (
        e.target.classList.contains("btn-success") &&
        e.target.textContent.includes("Terima")
    ) {
        handleAcceptRequest(e.target);
    }

    // Reject button for requests
    if (
        e.target.classList.contains("btn-danger") &&
        e.target.textContent.includes("Tolak")
    ) {
        handleRejectRequest(e.target);
    }
});

/**
 * Handle accept request
 */
function handleAcceptRequest(button) {
    showToast("✓ Pengajuan berhasil diterima", "success");
    button.closest(".glass-card").style.opacity = "0.5";
    setTimeout(() => {
        button.closest(".glass-card").remove();
    }, 500);
}

/**
 * Handle reject request
 */
function handleRejectRequest(button) {
    showToast("✕ Pengajuan berhasil ditolak", "info");
    button.closest(".glass-card").style.opacity = "0.5";
    setTimeout(() => {
        button.closest(".glass-card").remove();
    }, 500);
}

/**
 * Export utility functions for global access
 */
window.DashboardUtils = {
    showToast,
    formatTime,
    initializeDashboard,
};
