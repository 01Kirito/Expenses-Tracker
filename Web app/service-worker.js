// service-worker.js

self.addEventListener('notificationclick', function(event) {
    event.notification.close(); // close the notification

    const url = event.notification.data.url || 'https://example.com'; // Extract the URL from notification data or use a default URL

    event.waitUntil(
        clients.openWindow(url)
    );
});