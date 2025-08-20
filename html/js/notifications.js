/**
 * Desktop Notification System for Department Status Changes
 * This script handles real-time notifications when patients are assigned to departments
 */

class DepartmentNotifications {
    constructor(department) {
        this.department = department;
        this.lastCheck = new Date().toISOString().slice(0, 19).replace('T', ' ');
        this.isActive = true;
        this.checkInterval = 15000; // Check every 15 seconds (increased for better stability)
        this.intervalId = null;
        this.debugMode = true; // Enable debug logging
        
        this.init();
    }
    
    log(message, type = 'info') {
        if (this.debugMode) {
            const timestamp = new Date().toLocaleTimeString();
            console.log(`[${timestamp}] [${type.toUpperCase()}] [${this.department}] ${message}`);
        }
    }
    
    async init() {
        this.log('Initializing notification system...');
        
        // Request notification permission
        await this.requestNotificationPermission();
        
        // Start checking for notifications
        this.startNotificationCheck();
        
        // Handle page visibility changes
        this.handleVisibilityChange();
        
        this.log('Notification system initialized successfully');
    }
    
    async requestNotificationPermission() {
        if ('Notification' in window) {
            if (Notification.permission === 'default') {
                try {
                    const permission = await Notification.requestPermission();
                    if (permission === 'granted') {
                        console.log('Notification permission granted');
                        this.showWelcomeNotification();
                    } else {
                        console.log('Notification permission denied');
                    }
                } catch (error) {
                    console.error('Error requesting notification permission:', error);
                }
            }
        } else {
            console.log('This browser does not support notifications');
        }
    }
    
    showWelcomeNotification() {
        new Notification(`${this.department.toUpperCase()} Department`, {
            body: 'Desktop notifications are now enabled for new patient assignments',
            icon: '../assets/img/favicon.ico', // Update path as needed
            tag: 'welcome'
        });
    }
    
    startNotificationCheck() {
        this.checkForNotifications();
        this.intervalId = setInterval(() => {
            if (this.isActive) {
                this.checkForNotifications();
            }
        }, this.checkInterval);
    }
    
    async checkForNotifications() {
        if (!this.isActive) return;
        
        try {
            const url = `check_notifications.php?department=${this.department}&last_check=${encodeURIComponent(this.lastCheck)}`;
            this.log(`Checking for notifications: ${url}`);
            
            const response = await fetch(url);
            this.log(`Response status: ${response.status}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            this.log(`Received data: ${JSON.stringify(data)}`);
            
            if (data.success && data.notifications && data.notifications.length > 0) {
                this.log(`Found ${data.notifications.length} new notifications`);
                this.processNotifications(data.notifications);
                this.lastCheck = data.last_check;
            } else {
                this.log('No new notifications found');
            }
        } catch (error) {
            this.log(`Error checking notifications: ${error.message}`, 'error');
            console.error('Notification check error:', error);
        }
    }
    
    processNotifications(notifications) {
        notifications.forEach(notification => {
            this.showNotification(notification);
            this.playNotificationSound();
            this.updateUI(notification);
        });
    }
    
    showNotification(notification) {
        if (Notification.permission === 'granted' && !document.hasFocus()) {
            const notif = new Notification(`New Patient - ${this.department.toUpperCase()}`, {
                body: notification.message,
                icon: '../assets/img/favicon.ico', // Update path as needed
                tag: `patient-${notification.pid}`,
                requireInteraction: true,
                actions: [
                    {
                        action: 'view',
                        title: 'View Patient'
                    }
                ]
            });
            
            notif.onclick = () => {
                window.focus();
                this.focusOnPatient(notification.pid);
                notif.close();
            };
            
            // Auto close after 10 seconds
            setTimeout(() => notif.close(), 10000);
        }
    }
    
    playNotificationSound() {
        // Create a subtle notification sound
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
        oscillator.frequency.setValueAtTime(600, audioContext.currentTime + 0.1);
        
        gainNode.gain.setValueAtTime(0, audioContext.currentTime);
        gainNode.gain.linearRampToValueAtTime(0.1, audioContext.currentTime + 0.01);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.3);
    }
    
    updateUI(notification) {
        // Show in-page notification
        this.showInPageNotification(notification);
        
        // Update patient list if function exists
        if (typeof loadPatientQueue === 'function') {
            loadPatientQueue();
        }
        
        // Refresh page data if function exists
        if (typeof refreshPageData === 'function') {
            refreshPageData();
        }
    }
    
    showInPageNotification(notification) {
        // Create notification toast
        const toast = document.createElement('div');
        toast.className = 'notification-toast';
        toast.innerHTML = `
            <div class="toast-header">
                <strong class="text-primary">New Patient Assignment</strong>
                <button type="button" class="btn-close" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
            <div class="toast-body">
                <strong>${notification.patient_name}</strong> (PID: ${notification.pid})<br>
                Status: ${notification.status}
                ${notification.room ? `<br>Room: ${notification.room}` : ''}
            </div>
        `;
        
        // Add CSS if not already added
        this.addToastCSS();
        
        // Add to page
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'toast-container';
            document.body.appendChild(toastContainer);
        }
        
        toastContainer.appendChild(toast);
        
        // Auto remove after 8 seconds
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 8000);
    }
    
    addToastCSS() {
        if (document.getElementById('notification-toast-css')) return;
        
        const style = document.createElement('style');
        style.id = 'notification-toast-css';
        style.textContent = `
            .toast-container {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                pointer-events: none;
            }
            
            .notification-toast {
                background: white;
                border: 1px solid #dee2e6;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                margin-bottom: 10px;
                max-width: 350px;
                pointer-events: all;
                animation: slideInRight 0.3s ease-out;
            }
            
            .toast-header {
                background: #f8f9fa;
                border-bottom: 1px solid #dee2e6;
                border-radius: 8px 8px 0 0;
                padding: 10px 15px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .toast-body {
                padding: 15px;
                color: #333;
            }
            
            .btn-close {
                background: none;
                border: none;
                font-size: 16px;
                cursor: pointer;
                padding: 0;
                width: 20px;
                height: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .btn-close:before {
                content: "×";
                font-size: 18px;
                color: #999;
            }
            
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    focusOnPatient(pid) {
        // Try to find and highlight the patient row
        const patientRows = document.querySelectorAll('tr');
        patientRows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length > 1 && cells[1].textContent.trim() === pid) {
                row.scrollIntoView({ behavior: 'smooth', block: 'center' });
                row.style.backgroundColor = '#fff3cd';
                setTimeout(() => {
                    row.style.backgroundColor = '';
                }, 3000);
            }
        });
    }
    
    handleVisibilityChange() {
        document.addEventListener('visibilitychange', () => {
            this.isActive = !document.hidden;
            if (this.isActive) {
                console.log(`Notifications resumed for ${this.department}`);
            } else {
                console.log(`Notifications paused for ${this.department}`);
            }
        });
    }
    
    destroy() {
        if (this.intervalId) {
            clearInterval(this.intervalId);
        }
        this.isActive = false;
        console.log(`Notification system destroyed for ${this.department}`);
    }
}

// Auto-initialize based on current page
document.addEventListener('DOMContentLoaded', function() {
    const currentPage = window.location.pathname;
    let department = '';
    
    if (currentPage.includes('nursing')) {
        department = 'nursing';
    } else if (currentPage.includes('medical')) {
        department = 'medical';
    } else if (currentPage.includes('dental')) {
        department = 'dental';
    } else if (currentPage.includes('pharmacy')) {
        department = 'pharmacy';
    } else if (currentPage.includes('reception')) {
        department = 'reception';
    } else if (currentPage.includes('office')) {
        department = 'office';
    }
    
    if (department) {
        window.departmentNotifications = new DepartmentNotifications(department);
    }
});

// Export for manual initialization
window.DepartmentNotifications = DepartmentNotifications;
