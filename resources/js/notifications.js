class NotificationManager {
    constructor() {
        this.notifications = [];
        this.unreadCount = 0;
        // Salviamo le notifiche mostrate nel sessionStorage per persistere tra i refresh
        this.notificationsShown = JSON.parse(sessionStorage.getItem('notificationsShown') || '[]');
        this.init();
    }

    init() {
        if (window.AUTH_USER_ID) {
            this.subscribeToUserChannel(); // Abilitato per WebSocket real-time
            this.fetchUnreadCount();
            this.requestNotificationPermission();
            
            // Carica le notifiche iniziali
            this.checkForNewNotifications();
            
            // Manteniamo anche il polling come backup
            this.startPolling();
        }
    }
    
    startPolling() {
        setInterval(() => {
            this.checkForNewNotifications();
        }, 10000); // 10 secondi
    }
    
    async checkForNewNotifications() {
        try {
            const response = await axios.get('/notifications?limit=10');
            const allNotifications = response.data.data;
            
            // Filtra solo le notifiche non lette per il dropdown
            this.notifications = allNotifications.filter(n => !n.read_at);
            
            // Aggiorna il dropdown delle notifiche
            this.updateNotificationDropdown();
            
            // Controlla se ci sono nuove notifiche non lette
            allNotifications.forEach(notification => {
                if (!notification.read_at && !this.notificationsShown.includes(notification.id)) {
                    const displayTypes = notification.data.display || ['toast', 'browser'];
                    if (displayTypes.includes('toast')) {
                        this.showToastNotification(notification.data);
                    }
                    if (displayTypes.includes('browser')) {
                        this.showBrowserNotification(notification.data);
                    }
                    this.notificationsShown.push(notification.id);
                    // Salva nel sessionStorage per persistere tra i refresh
                    sessionStorage.setItem('notificationsShown', JSON.stringify(this.notificationsShown));
                }
            });
            
            // Aggiorna il contatore
            this.fetchUnreadCount();
        } catch (error) {
            console.error('Error checking notifications:', error);
        }
    }

    subscribeToUserChannel() {
        window.Echo.private(`App.Models.User.${window.AUTH_USER_ID}`)
            .notification((notification) => {
                this.handleNewNotification(notification);
            });
    }

    handleNewNotification(notification) {
        // Per WebSocket, i dati sono direttamente nell'oggetto notification
        // Correggi il tipo se necessario
        if (notification.notification_type) {
            notification.type = notification.notification_type;
        }
        
        // Creiamo un oggetto fittizio simile a quello del database
        const notificationObj = {
            id: notification.id || Date.now().toString(),
            data: notification,
            created_at: notification.created_at || new Date().toISOString(),
            read_at: null
        };
        
        this.notifications.unshift(notificationObj);
        this.unreadCount++;
        this.updateUnreadCountDisplay();
        this.updateNotificationDropdown();
        
        const displayTypes = notification.display || ['toast', 'browser'];
        if (displayTypes.includes('toast')) {
            this.showToastNotification(notification);
        }
        if (displayTypes.includes('browser')) {
            this.showBrowserNotification(notification);
        }
        
        // Aggiungi alla lista delle notifiche mostrate
        this.notificationsShown.push(notificationObj.id);
        sessionStorage.setItem('notificationsShown', JSON.stringify(this.notificationsShown));
    }

    async fetchUnreadCount() {
        try {
            const response = await axios.get('/notifications/unread-count');
            this.unreadCount = response.data.count;
            this.updateUnreadCountDisplay();
        } catch (error) {
            console.error('Error fetching unread count:', error);
        }
    }

    updateUnreadCountDisplay() {
        const badge = document.getElementById('notification-badge');
        const countElement = document.getElementById('notification-count');
        if (badge && countElement) {
            if (this.unreadCount > 0) {
                countElement.textContent = this.unreadCount > 9 ? '9+' : this.unreadCount.toString();
                badge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
            }
        }
    }
    
    updateNotificationDropdown() {
        const notificationList = document.getElementById('notification-list');
        if (!notificationList) return;
        
        const viewAllButton = `
            <div class="list-group-item text-center">
                <a href="/notifications/list" class="btn btn-primary btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
                        <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
                    </svg>
                    Visualizza tutte le notifiche
                </a>
            </div>
        `;
        
        if (this.notifications.length === 0) {
            notificationList.innerHTML = `
                <div class="text-center p-4 text-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-2 text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
                        <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
                        <line x1="12" y1="3" x2="12" y2="3.01" />
                    </svg>
                    <p class="mb-0">Nessuna notifica non letta</p>
                </div>
                ${viewAllButton}
            `;
            return;
        }
        
        notificationList.innerHTML = this.notifications.map(notification => {
            const data = notification.data;
            const isUnread = !notification.read_at;
            const timeAgo = this.getTimeAgo(notification.created_at);
            
            return `
                <div class="list-group-item ${isUnread ? 'list-group-item-light' : ''}" data-notification-id="${notification.id}" 
                     onclick="window.notificationManager.handleNotificationClick('${notification.id}')" 
                     style="cursor: pointer;">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="badge bg-${data.type || 'info'}">${this.getNotificationIcon(data.type || 'info')}</span>
                        </div>
                        <div class="col">
                            <div class="text-truncate">
                                <strong>${this.escapeHtml(data.title)}</strong>
                            </div>
                            <div class="text-muted">${this.escapeHtml(data.message)}</div>
                            <div class="mt-1">
                                <small class="text-muted">${timeAgo}</small>
                            </div>
                        </div>
                        <div class="col-auto">
                            ${isUnread ? `<button class="btn btn-sm btn-ghost-primary" onclick="event.stopPropagation(); window.notificationManager.markAsRead('${notification.id}')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M5 12l5 5l10 -10" />
                                </svg>
                            </button>` : ''}
                        </div>
                    </div>
                </div>
            `;
        }).join('') + viewAllButton;
    }
    
    getTimeAgo(timestamp) {
        const date = new Date(timestamp);
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);
        
        if (seconds < 60) return 'Ora';
        if (seconds < 3600) return Math.floor(seconds / 60) + ' minuti fa';
        if (seconds < 86400) return Math.floor(seconds / 3600) + ' ore fa';
        return Math.floor(seconds / 86400) + ' giorni fa';
    }

    showToastNotification(notification) {
        // Usa il sistema di notifiche integrato di Tabler se disponibile
        if (window.Toastify) {
            // Sistema Toastify
            Toastify({
                text: `<strong>${this.escapeHtml(notification.title)}</strong><br>${this.escapeHtml(notification.message)}`,
                duration: 5000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: this.getToastColor(notification.type || 'info'),
                escapeMarkup: false
            }).showToast();
        } else {
            // Fallback al nostro sistema custom
            const toastContainer = document.getElementById('toast-container');
            if (!toastContainer) return;

            // Tronca il messaggio se troppo lungo
            const maxLength = 100;
            let truncatedMessage = notification.message;
            if (truncatedMessage.length > maxLength) {
                truncatedMessage = truncatedMessage.substring(0, maxLength) + '...';
            }

            const toast = document.createElement('div');
            toast.className = `toast-notification toast-${notification.type || 'info'}`;
            toast.innerHTML = `
                <div class="toast-header">
                    <strong>${this.escapeHtml(notification.title)}</strong>
                    <button class="toast-close" onclick="this.parentElement.parentElement.remove()">×</button>
                </div>
                <div class="toast-body">
                    ${this.escapeHtml(truncatedMessage)}
                </div>
            `;

            toastContainer.appendChild(toast);

            setTimeout(() => {
                toast.classList.add('show');
            }, 100);

            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }
    }
    
    getToastColor(type) {
        const colors = {
            info: '#0dcaf0',
            success: '#198754',
            warning: '#ffc107',
            error: '#dc3545',
            danger: '#dc3545'
        };
        return colors[type] || colors.info;
    }

    showBrowserNotification(notification) {
        if (Notification.permission === 'granted') {
            // Tronca il messaggio per la notifica browser
            const maxLength = 80;
            let truncatedMessage = notification.message;
            if (truncatedMessage.length > maxLength) {
                truncatedMessage = truncatedMessage.substring(0, maxLength) + '...';
            }
            
            const browserNotification = new Notification(notification.title, {
                body: truncatedMessage,
                icon: '/favicon.ico',
                tag: notification.id
            });

            browserNotification.onclick = () => {
                window.focus();
                // Reindirizza alla pagina delle notifiche invece dell'action URL
                window.location.href = '/notifications/list';
                browserNotification.close();
            };
        }
    }

    requestNotificationPermission() {
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    }

    async markAsRead(notificationId) {
        try {
            await axios.post(`/notifications/mark-read/${notificationId}`);
            this.unreadCount = Math.max(0, this.unreadCount - 1);
            this.updateUnreadCountDisplay();
            // Rimuovi dalla lista delle notifiche mostrate quando viene letta
            this.notificationsShown = this.notificationsShown.filter(id => id !== notificationId);
            sessionStorage.setItem('notificationsShown', JSON.stringify(this.notificationsShown));
            // Aggiorna la lista nel dropdown
            this.checkForNewNotifications();
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }

    async markAllAsRead() {
        try {
            await axios.post('/notifications/mark-all-read');
            this.unreadCount = 0;
            this.updateUnreadCountDisplay();
            // Pulisci anche la lista delle notifiche mostrate
            this.notificationsShown = [];
            sessionStorage.setItem('notificationsShown', JSON.stringify(this.notificationsShown));
            // Aggiorna la lista nel dropdown
            this.checkForNewNotifications();
        } catch (error) {
            console.error('Error marking all notifications as read:', error);
        }
    }

    async deleteNotification(notificationId) {
        try {
            await axios.delete(`/notifications/${notificationId}`);
            this.notifications = this.notifications.filter(n => n.id !== notificationId);
        } catch (error) {
            console.error('Error deleting notification:', error);
        }
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    getNotificationIcon(type) {
        const icons = {
            info: '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12.01" y2="8" /><polyline points="11 12 12 12 12 16 13 16" /></svg>',
            success: '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><path d="M9 12l2 2l4 -4" /></svg>',
            warning: '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4v.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>',
            error: '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="9" y1="9" x2="15" y2="15" /><line x1="15" y1="9" x2="9" y2="15" /></svg>',
            danger: '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="9" y1="9" x2="15" y2="15" /><line x1="15" y1="9" x2="9" y2="15" /></svg>'
        };
        
        return icons[type] || icons.info;
    }
    
    async handleNotificationClick(notificationId) {
        // Reindirizza direttamente alla pagina delle notifiche senza marcare come letta
        window.location.href = '/notifications/list';
    }
}

export default NotificationManager;