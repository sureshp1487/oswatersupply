/**
 * Admin Real-Time Notifications & PWA Sync Client
 */
(function() {
    let unreadCount = 0;
    let lastCheckedTime = null;
    let pollInterval = null;
    let isDropdownOpen = false;
    let notificationAudio = null;

    // Initialize subtle audio beep for order arrival
    function initAudio() {
        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (AudioContext) {
                notificationAudio = new AudioContext();
            }
        } catch (e) {}
    }

    function playNotificationSound() {
        try {
            if (!notificationAudio) {
                initAudio();
            }
            if (notificationAudio && notificationAudio.state === 'suspended') {
                notificationAudio.resume();
            }
            if (notificationAudio) {
                const osc = notificationAudio.createOscillator();
                const gain = notificationAudio.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(587.33, notificationAudio.currentTime); // D5
                osc.frequency.exponentialRampToValueAtTime(880, notificationAudio.currentTime + 0.15); // A5
                gain.gain.setValueAtTime(0.12, notificationAudio.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, notificationAudio.currentTime + 0.3);
                osc.connect(gain);
                gain.connect(notificationAudio.destination);
                osc.start();
                osc.stop(notificationAudio.currentTime + 0.32);
            }
        } catch (e) {}
    }

    // Set badges across browser tab, DOM, and PWA Badging API
    function updateBadges(count) {
        unreadCount = parseInt(count, 10) || 0;

        const badgeEl = document.getElementById('adminNotificationBadge');
        const textCountEl = document.getElementById('adminNotificationUnreadCountText');

        if (badgeEl) {
            if (unreadCount > 0) {
                badgeEl.textContent = unreadCount > 99 ? '99+' : unreadCount;
                badgeEl.classList.remove('hidden');
            } else {
                badgeEl.classList.add('hidden');
            }
        }

        if (textCountEl) {
            textCountEl.textContent = unreadCount > 0 ? `${unreadCount} new` : '0 new';
        }

        // Browser Tab Title notification count update (e.g. "(3) Admin Dashboard")
        const baseTitle = document.title.replace(/^\(\d+\+?\)\s*/, '');
        if (unreadCount > 0) {
            document.title = `(${unreadCount}) ${baseTitle}`;
        } else {
            document.title = baseTitle;
        }

        // PWA App Badging API (for installed desktop & mobile PWAs)
        if ('setAppBadge' in navigator) {
            if (unreadCount > 0) {
                navigator.setAppBadge(unreadCount).catch(() => {});
            } else if ('clearAppBadge' in navigator) {
                navigator.clearAppBadge().catch(() => {});
            }
        }

        // Notify Service Worker if active
        if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
            navigator.serviceWorker.controller.postMessage({
                action: unreadCount > 0 ? 'SET_BADGE' : 'CLEAR_BADGE',
                count: unreadCount
            });
        }
    }

    // Load full notifications list for dropdown
    function loadNotifications() {
        const listEl = document.getElementById('adminNotificationList');
        if (!listEl) return;

        fetch('/admin/notifications?limit=15', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) {
                throw new Error('HTTP status ' + res.status);
            }
            return res.json();
        })
        .then(data => {
            if (data && data.success) {
                updateBadges(data.unread_count);
                renderNotificationList(data.notifications || []);
            } else {
                renderNotificationList([]);
            }
        })
        .catch(err => {
            console.error('Failed to load admin notifications:', err);
            listEl.innerHTML = `
                <div class="p-6 text-center text-slate-400">
                    <p class="text-xs font-semibold text-slate-600 mb-1">Could not load notifications</p>
                    <p class="text-[11px] text-slate-400 mb-3">Please check your internet connection or login session.</p>
                    <button type="button" onclick="window.AdminNotifications ? window.AdminNotifications.reload() : location.reload()" class="px-3 py-1 bg-indigo-600 text-white rounded-lg text-xs font-bold hover:bg-indigo-700">
                        Retry
                    </button>
                </div>
            `;
        });
    }

    // Render HTML inside dropdown list
    function renderNotificationList(items) {
        const listEl = document.getElementById('adminNotificationList');
        if (!listEl) return;

        if (!items || items.length === 0) {
            listEl.innerHTML = `
                <div class="p-8 text-center text-slate-400 space-y-1">
                    <i class="fa-regular fa-bell text-2xl text-slate-300 block mb-1"></i>
                    <p class="text-xs font-semibold text-slate-600">No notifications yet</p>
                    <p class="text-[11px] text-slate-400">Customer order alerts will appear here in real-time.</p>
                </div>
            `;
            return;
        }

        let html = '';
        items.forEach(item => {
            const data = item.data || {};
            const isUnread = !item.read;
            const bgClass = isUnread ? 'bg-sky-50/60 hover:bg-sky-50' : 'bg-white hover:bg-slate-50';
            const dotClass = isUnread ? 'bg-[#0077b6]' : 'bg-slate-300';
            const orderStatus = (data.order_status || 'placed').toUpperCase();

            html += `
                <div class="p-3.5 ${bgClass} transition flex items-start space-x-3 cursor-pointer group notification-row" data-id="${item.id}" data-url="${data.target_url || '/admin/orders'}">
                    <div class="w-8 h-8 rounded-xl ${isUnread ? 'bg-sky-100 text-[#0077b6]' : 'bg-slate-100 text-slate-500'} flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fa-solid fa-boxes-stacked text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-extrabold text-slate-900 text-xs truncate">
                                ${escapeHtml(data.title || 'New Order Placed')}
                            </span>
                            <span class="text-[10px] text-slate-400 shrink-0">${escapeHtml(item.created_at)}</span>
                        </div>
                        <p class="text-xs font-bold text-slate-700 mt-0.5">
                            Order #${escapeHtml(data.order_number || data.order_id)} &bull; ₹${parseFloat(data.order_amount || 0).toFixed(2)}
                        </p>
                        <p class="text-[11px] text-slate-500 mt-0.5 truncate">
                            <i class="fa-solid fa-user text-[9px] mr-1"></i>${escapeHtml(data.customer_name || 'Customer')}
                            ${data.customer_phone ? `(${escapeHtml(data.customer_phone)})` : ''}
                        </p>
                        <div class="flex items-center justify-between mt-2 pt-1 border-t border-slate-100/80">
                            <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider ${isUnread ? 'bg-sky-100 text-sky-800' : 'bg-slate-100 text-slate-600'}">
                                ${escapeHtml(orderStatus)}
                            </span>
                            <span class="text-[10px] font-bold text-[#0077b6] group-hover:underline">
                                View Order &rarr;
                            </span>
                        </div>
                    </div>
                    <span class="w-2 h-2 rounded-full ${dotClass} shrink-0 mt-1.5" title="${isUnread ? 'Unread' : 'Read'}"></span>
                </div>
            `;
        });

        listEl.innerHTML = html;

        // Attach click handlers on individual rows
        listEl.querySelectorAll('.notification-row').forEach(row => {
            row.addEventListener('click', function(e) {
                const notifId = this.dataset.id;
                const targetUrl = this.dataset.url;
                markSingleNotificationRead(notifId, targetUrl);
            });
        });
    }

    // Escape HTML to prevent XSS
    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Mark single notification read and redirect to Order Details page
    function markSingleNotificationRead(id, redirectUrl) {
        fetch(`/admin/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                updateBadges(data.unread_count);
            }
            if (redirectUrl) {
                window.location.href = redirectUrl;
            }
        })
        .catch(() => {
            if (redirectUrl) {
                window.location.href = redirectUrl;
            }
        });
    }

    // Mark all notifications as read
    function markAllAsRead() {
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch('/admin/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                updateBadges(0);
                loadNotifications();
            }
        });
    }

    // Real-Time Polling for new orders
    function checkRealtimeUpdates() {
        const url = lastCheckedTime 
            ? `/admin/notifications/unread-count?since=${encodeURIComponent(lastCheckedTime)}`
            : `/admin/notifications/unread-count`;

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const oldCount = unreadCount;
                const newCount = data.unread_count;

                updateBadges(newCount);

                // If new notifications arrived since last check
                if (newCount > oldCount && data.latest && data.latest.length > 0) {
                    playNotificationSound();

                    // Show toast / desktop system notification for the latest order
                    data.latest.forEach(item => {
                        const notifData = item.data || {};
                        showDesktopNotification(notifData);
                        showInAppToast(notifData);
                    });

                    // If dropdown is open, refresh it
                    if (isDropdownOpen) {
                        loadNotifications();
                    }
                }

                if (data.server_time) {
                    lastCheckedTime = data.server_time;
                }
            }
        })
        .catch(() => {});
    }

    // In-App Toast Alert (Top right) - Disabled per user request
    function showInAppToast(data) {
        // Notification popup disabled
        return;
    }

    // Native Desktop / System Push Notification
    function showDesktopNotification(data) {
        if (!("Notification" in window)) return;

        if (Notification.permission === "granted") {
            try {
                const title = data.title || "New Customer Order Received";
                const options = {
                    body: `Order #${data.order_number || data.order_id} placed by ${data.customer_name || 'Customer'} for ₹${parseFloat(data.order_amount || 0).toFixed(2)}`,
                    icon: '/icons/icon-192.png',
                    badge: '/icons/icon-192.png',
                    tag: 'order-' + (data.order_id || Date.now()),
                    data: {
                        url: data.target_url || '/admin/orders'
                    }
                };

                const notif = new Notification(title, options);
                notif.onclick = function() {
                    window.focus();
                    if (data.target_url) {
                        window.location.href = data.target_url;
                    }
                    this.close();
                };
            } catch (e) {
                // For mobile browsers requiring ServiceWorkerRegistration.showNotification
                if ('serviceWorker' in navigator) {
                    navigator.serviceWorker.ready.then(reg => {
                        reg.showNotification(data.title || "New Customer Order Received", {
                            body: `Order #${data.order_number || data.order_id} - ₹${parseFloat(data.order_amount || 0).toFixed(2)}`,
                            icon: '/icons/icon-192.png',
                            data: { url: data.target_url || '/admin/orders' }
                        });
                    });
                }
            }
        }
    }

    // Request Notification permission for Desktop & PWA
    function requestNotificationPermission() {
        if (!("Notification" in window)) {
            alert('Your browser does not support desktop notifications.');
            return;
        }

        Notification.requestPermission().then(permission => {
            checkPermissionBanner();
            if (permission === 'granted') {
                registerPushSubscription();
                if (window.Swal) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Notifications Enabled!',
                        text: 'You will receive instant alerts for new customer orders.',
                        timer: 2500,
                        showConfirmButton: false
                    });
                }
            }
        });
    }

    // Register Web Push subscription with Service Worker
    function registerPushSubscription() {
        if (!('serviceWorker' in navigator)) return;

        navigator.serviceWorker.ready.then(reg => {
            return reg.pushManager.getSubscription().then(sub => {
                if (sub) return sub;
                // If supported without custom VAPID or with basic push
                return reg.pushManager.subscribe({
                    userVisibleOnly: true,
                    // Generic applicationServerKey or browser default
                }).catch(() => null);
            });
        }).then(sub => {
            if (sub) {
                savePushSubscription(sub);
            }
        }).catch(() => {});
    }

    function savePushSubscription(sub) {
        const rawKey = sub.getKey ? sub.getKey('p256dh') : null;
        const rawAuth = sub.getKey ? sub.getKey('auth') : null;

        const key = rawKey ? btoa(String.fromCharCode.apply(null, new Uint8Array(rawKey))) : null;
        const auth = rawAuth ? btoa(String.fromCharCode.apply(null, new Uint8Array(rawAuth))) : null;

        fetch('/admin/notifications/push/subscribe', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                endpoint: sub.endpoint,
                keys: {
                    p256dh: key,
                    auth: auth
                }
            })
        }).catch(() => {});
    }

    // Check if permission banner should be shown
    function checkPermissionBanner() {
        const promptEl = document.getElementById('adminPushPermissionPrompt');
        if (!promptEl) return;

        if ("Notification" in window && Notification.permission === 'default') {
            promptEl.classList.remove('hidden');
        } else {
            promptEl.classList.add('hidden');
        }
    }

    // Setup Event Listeners
    document.addEventListener('DOMContentLoaded', function() {
        initAudio();

        // Register Service Worker if supported
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js').then(reg => {
                if (Notification.permission === 'granted') {
                    registerPushSubscription();
                }
            }).catch(() => {});
        }

        const bellBtn = document.getElementById('adminNotificationBellBtn');
        const dropdown = document.getElementById('adminNotificationDropdown');
        const markAllBtn = document.getElementById('adminMarkAllReadBtn');
        const enablePushBtn = document.getElementById('adminEnablePushBtn');

        if (bellBtn && dropdown) {
            bellBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                isDropdownOpen = !isDropdownOpen;
                dropdown.classList.toggle('hidden', !isDropdownOpen);
                bellBtn.setAttribute('aria-expanded', isDropdownOpen ? 'true' : 'false');

                if (isDropdownOpen) {
                    checkPermissionBanner();
                    loadNotifications();
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!document.getElementById('adminNotificationWrapper').contains(e.target)) {
                    dropdown.classList.add('hidden');
                    isDropdownOpen = false;
                    bellBtn.setAttribute('aria-expanded', 'false');
                }
            });
        }

        if (markAllBtn) {
            markAllBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                markAllAsRead();
            });
        }

        if (enablePushBtn) {
            enablePushBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                requestNotificationPermission();
            });
        }

        // Initial fetch of unread count
        checkRealtimeUpdates();

        // Start interval polling every 4 seconds for instant real-time order delivery
        pollInterval = setInterval(checkRealtimeUpdates, 4000);

        // Instant refresh when user switches tabs back to admin
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                checkRealtimeUpdates();
            }
        });
        // Expose helper on window for manual retry
        window.AdminNotifications = {
            reload: loadNotifications,
            check: checkRealtimeUpdates
        };
    });
})();
