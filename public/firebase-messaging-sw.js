importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js');

// Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyCIjxnhXyvF7uQsgEyU8jX99_7p_tqa1x0",
    authDomain: "komed-oi.firebaseapp.com",
    projectId: "komed-oi",
    storageBucket: "komed-oi.firebasestorage.app",
    messagingSenderId: "67197346584",
    appId: "1:67197346584:web:06d5065a8e2709938db46d",
    measurementId: "G-GM74W7Z9XY"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

// Initialize Firebase Cloud Messaging
const messaging = firebase.messaging();

// Background message handler
messaging.onBackgroundMessage((payload) => {
    const notificationTitle = payload.notification?.title || 'Default Title';
    const notificationOptions = {
        body: payload.notification?.body || 'Default body',
        icon: '/firebase-logo.png', // Add your custom notification icon
    };
    self.registration.showNotification(notificationTitle, notificationOptions);
    // console.log('Received background message:', payload);
});

// Handle notification clicks
self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
            if (clientList.length > 0) {
                clientList[0].focus();
            } else {
                clients.openWindow('/');
            }
        })
    );
});
