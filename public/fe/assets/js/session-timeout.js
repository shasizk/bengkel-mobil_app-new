/**
 * Session Timeout Warning Handler
 * Displays a warning modal when the session is about to expire
 */

(function() {
    'use strict';

    // Configuration
    const config = {
        sessionLifetime: 120 * 60 * 1000, // 120 minutes in milliseconds
        warningTime: 10 * 60 * 1000, // Show warning 10 minutes before expiry
    };

    let lastActivity = Date.now();
    let sessionWarningShown = false;
    let sessionExtendedCount = 0;

    // Update last activity on user interaction
    function updateActivity() {
        lastActivity = Date.now();
        sessionWarningShown = false;
    }

    // Events that count as user activity
    const activityEvents = ['mousedown', 'keydown', 'scroll', 'touchstart', 'click'];
    
    activityEvents.forEach(event => {
        document.addEventListener(event, updateActivity, true);
    });

    // Check session timeout periodically
    function checkSessionTimeout() {
        const elapsedTime = Date.now() - lastActivity;
        const timeRemaining = config.sessionLifetime - elapsedTime;

        // Show warning when time remaining equals warning time
        if (timeRemaining <= config.warningTime && !sessionWarningShown) {
            showSessionWarning(timeRemaining);
            sessionWarningShown = true;
        }

        // Auto logout when session expires
        if (timeRemaining <= 0) {
            logoutSession();
        }
    }

    // Show warning modal
    function showSessionWarning(timeRemaining) {
        const minutes = Math.floor(timeRemaining / 60000);
        const seconds = Math.floor((timeRemaining % 60000) / 1000);
        
        const warningHtml = `
            <div id="sessionTimeoutModal" class="modal" style="display: block; background-color: rgba(0,0,0,0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header" style="background: linear-gradient(135deg, #fb923c, #f97316); color: white; border: none;">
                            <h5 class="modal-title">⏱️ Peringatan: Sesi Akan Berakhir</h5>
                            <button type="button" class="btn-close btn-close-white" aria-label="Close" onclick="document.getElementById('sessionTimeoutModal').remove()"></button>
                        </div>
                        <div class="modal-body">
                            <p>Sesi Anda akan berakhir dalam <strong id="timeRemaining">${minutes}m ${seconds}s</strong>.</p>
                            <p>Klik tombol di bawah untuk melanjutkan sesi atau Anda akan dialihkan ke halaman login.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="window.logoutSession()">Logout Sekarang</button>
                            <button type="button" class="btn btn-primary" onclick="window.extendSession()">Lanjutkan Sesi</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Remove existing modal if any
        const existingModal = document.getElementById('sessionTimeoutModal');
        if (existingModal) {
            existingModal.remove();
        }
        
        document.body.insertAdjacentHTML('beforeend', warningHtml);
        
        // Update countdown
        const countdownInterval = setInterval(() => {
            const newElapsedTime = Date.now() - lastActivity;
            const newTimeRemaining = config.sessionLifetime - newElapsedTime;
            const newMinutes = Math.floor(newTimeRemaining / 60000);
            const newSeconds = Math.floor((newTimeRemaining % 60000) / 1000);
            
            const timeElement = document.getElementById('timeRemaining');
            if (timeElement) {
                timeElement.textContent = `${newMinutes}m ${newSeconds}s`;
            } else {
                clearInterval(countdownInterval);
            }
        }, 1000);
    }

    // Extend session by making a POST request
    window.extendSession = function() {
        sessionExtendedCount++;
        
        // Get CSRF token from meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        if (!csrfToken) {
            console.warn('CSRF token not found');
            return;
        }
        
        // Make a request to refresh the session
        // Try to find the refresh endpoint - fallback to different routes based on current page
        const refreshUrl = '/session/refresh';
        
        fetch(refreshUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                // Reset activity timer
                lastActivity = Date.now();
                sessionWarningShown = false;
                
                // Close modal if exists
                const modal = document.getElementById('sessionTimeoutModal');
                if (modal) {
                    modal.remove();
                }
                
                // Show success message
                showNotification('Sesi Anda telah diperpanjang.', 'success');
            } else {
                showNotification('Gagal memperpanjang sesi. Silakan login kembali.', 'error');
                setTimeout(logoutSession, 2000);
            }
        }).catch(error => {
            console.error('Error extending session:', error);
            showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
        });
    };

    // Logout
    window.logoutSession = function() {
        // Find and submit the logout form
        const logoutForm = document.querySelector('form[action*="logout"]');
        if (logoutForm) {
            logoutForm.submit();
        } else {
            // Fallback: redirect based on current URL
            if (window.location.href.includes('/client/')) {
                window.location.href = '/client/logout';
            } else if (window.location.href.includes('/admin/')) {
                window.location.href = '/admin/logout';
            } else {
                window.location.href = '/logout';
            }
        }
    };

    // Show notification
    function showNotification(message, type = 'info') {
        const alertClass = type === 'success' ? 'alert-success' : (type === 'error' ? 'alert-danger' : 'alert-info');
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9998; min-width: 300px;">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', alertHtml);
        
        setTimeout(() => {
            const alert = document.querySelector('.alert-dismissible');
            if (alert) {
                alert.remove();
            }
        }, 3000);
    }

    // Check session timeout every 30 seconds
    setInterval(checkSessionTimeout, 30000);
    
    // Initial check after 5 seconds
    setTimeout(checkSessionTimeout, 5000);
})();
