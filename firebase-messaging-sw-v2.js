importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js');

const firebaseConfig = {
    apiKey: "AIzaSyCUpT22o_EQV4uuyOn84zawWemlWqzmsOA",
    authDomain: "a2p-realtech-29ced.firebaseapp.com",
    projectId: "a2p-realtech-29ced",
    storageBucket: "a2p-realtech-29ced.firebasestorage.app",
    messagingSenderId: "796136362818",
    appId: "1:796136362818:web:37d0a918debf7ce7a5aedb",
    measurementId: "G-DYGC70ZNSK"
};

firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

messaging.onBackgroundMessage((payload) => {
    // If the message already has a notification payload, the browser will handle it automatically
    // We only show a manual one if it's data-only.
    if (payload.notification) {
        console.log('Notification handled automatically by browser.');
        return;
    }

    const logoAbs = self.location.origin + '/assets/images/favicons/android-chrome-192x192.png';
    const notificationIcon = payload.data?.image || logoAbs;

    const title = payload.data?.title || "A2P RealTech";
    const body = payload.data?.body || "New Update Available";

    const options = {
        body: body,
        icon: notificationIcon,
        badge: notificationIcon,
        tag: 'a2p-branded-push', // Group notifications
        renotify: true,         // Allows it to pop up again even with same tag
        requireInteraction: true,
        vibrate: [200, 100, 200],
        data: {
            url: payload.data?.link || '/'
        }
    };

    // If an image was provided in data, use it
    if (payload.data?.image) {
        options.image = payload.data.image;
    }

    return self.registration.showNotification(title, options);
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();
    const urlToOpen = event.notification.data?.url || '/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true })
            .then(function (windowClients) {
                // Check if there is already a window open with this URL
                for (let i = 0; i < windowClients.length; i++) {
                    const client = windowClients[i];
                    if (client.url.includes(self.location.origin) && 'focus' in client) {
                        return client.focus();
                    }
                }
                // If no window is found, open a new one
                if (clients.openWindow) {
                    return clients.openWindow(urlToOpen);
                }
            })
    );
});
