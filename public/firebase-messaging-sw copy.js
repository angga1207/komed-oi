// importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
// importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');

// import { onBackgroundMessage } from "firebase/messaging/sw";

// firebase.initializeApp({
//     apiKey: "AIzaSyCIjxnhXyvF7uQsgEyU8jX99_7p_tqa1x0",
//     authDomain: "komed-oi.firebaseapp.com",
//     projectId: "komed-oi",
//     storageBucket: "komed-oi.firebasestorage.app",
//     messagingSenderId: "67197346584",
//     appId: "1:67197346584:web:06d5065a8e2709938db46d",
//     measurementId: "G-GM74W7Z9XY"
// });

// const messaging = firebase.messaging();
// messaging.setBackgroundMessageHandler(function ({ data: { title, body, icon } }) {
//     return self.registration.showNotification(title, { body, icon });
// });

// messaging.onBackgroundMessage((payload) => {
//     console.log(
//         '[firebase-messaging-sw.js] Received background message ',
//         payload
//     );
//     // Customize notification here
//     const notificationTitle = 'Background Message Title';
//     const notificationOptions = {
//         body: 'Background Message body.',
//         icon: '/firebase-logo.png'
//     };

//     self.registration.showNotification(notificationTitle, notificationOptions);
// });

importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');
// importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging-sw.js');

firebase.initializeApp({
    apiKey: "AIzaSyCIjxnhXyvF7uQsgEyU8jX99_7p_tqa1x0",
    authDomain: "komed-oi.firebaseapp.com",
    projectId: "komed-oi",
    storageBucket: "komed-oi.firebasestorage.app",
    messagingSenderId: "67197346584",
    appId: "1:67197346584:web:06d5065a8e2709938db46d",
    measurementId: "G-GM74W7Z9XY"
});

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function ({ data: { title, body, icon } }) {
    return self.registration.showNotification(title, { body, icon });
});

onBackgroundMessage(messaging, (payload) => {
    console.log(
        '[firebase-messaging-sw.js] Received background message ',
        payload
    );
    // Customize notification here
    const notificationTitle = 'Background Message Title';
    const notificationOptions = {
        body: 'Background Message body.',
        icon: '/firebase-logo.png'
    };

    self.registration.showNotification(notificationTitle, notificationOptions);
});
