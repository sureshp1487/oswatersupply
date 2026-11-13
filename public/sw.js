const CACHE_NAME = 'aquaflow-v2';
const ASSETS = [
  '/',
  '/manifest.json',
  'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css',
  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
  'https://code.jquery.com/jquery-3.7.1.min.js'
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(ASSETS).catch(() => {});
    })
  );
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) => {
      return Promise.all(
        keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key))
      );
    })
  );
  self.clients.claim();
});

self.addEventListener('fetch', (event) => {
  // Only cache GET requests for static assets, not dynamic/admin/API calls
  if (
    event.request.method !== 'GET' ||
    event.request.url.includes('/api/') ||
    event.request.url.includes('/admin/') ||
    event.request.url.includes('/payments/') ||
    event.request.url.includes('/customer/')
  ) {
    return;
  }

  event.respondWith(
    fetch(event.request).catch(() => caches.match(event.request))
  );
});

// Real-Time Web Push Notification Handling for PWA
self.addEventListener('push', (event) => {
  let data = {
    title: 'New Customer Order Received',
    body: 'A customer has placed a new drinking water order.',
    url: '/admin/orders',
    icon: '/icons/icon-192.png',
    badge: '/icons/icon-192.png',
    tag: 'admin-order-notification-' + Date.now(),
    unread_count: 1
  };

  if (event.data) {
    try {
      const parsed = event.data.json();
      data = Object.assign(data, parsed);
    } catch (e) {
      data.body = event.data.text();
    }
  }

  // Update PWA App Badge API if supported by browser/OS
  if (navigator.setAppBadge && typeof data.unread_count === 'number') {
    navigator.setAppBadge(data.unread_count).catch(() => {});
  }

  const options = {
    body: data.body,
    icon: data.icon || '/icons/icon-192.png',
    badge: data.badge || '/icons/icon-192.png',
    tag: data.tag || 'admin-order-alert',
    renotify: true,
    requireInteraction: true,
    vibrate: [200, 100, 200, 100, 300],
    data: {
      url: data.url || '/admin/orders'
    }
  };

  event.waitUntil(
    self.registration.showNotification(data.title, options)
  );
});

// Click notification event -> opens the corresponding Order Details page
self.addEventListener('notificationclick', (event) => {
  event.notification.close();

  const targetUrl = (event.notification.data && event.notification.data.url)
    ? event.notification.data.url
    : '/admin/orders';

  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true }).then((windowClients) => {
      for (let client of windowClients) {
        if ('focus' in client) {
          if (client.url.includes('/admin/')) {
            client.navigate(targetUrl);
            return client.focus();
          }
        }
      }
      if (clients.openWindow) {
        return clients.openWindow(targetUrl);
      }
    })
  );
});

// Background sync or message handling from frontend
self.addEventListener('message', (event) => {
  if (event.data && event.data.action === 'SET_BADGE') {
    const count = parseInt(event.data.count, 10);
    if (navigator.setAppBadge) {
      if (count > 0) {
        navigator.setAppBadge(count).catch(() => {});
      } else if (navigator.clearAppBadge) {
        navigator.clearAppBadge().catch(() => {});
      }
    }
  } else if (event.data && event.data.action === 'CLEAR_BADGE') {
    if (navigator.clearAppBadge) {
      navigator.clearAppBadge().catch(() => {});
    }
  }
});
