import { initializeApp } from "firebase/app";
import { getMessaging, onMessage, getToken } from "firebase/messaging";

const firebaseConfig = {
    // apiKey: "YOUR_API_KEY",
    // authDomain: "YOUR_AUTH_DOMAIN",
    // projectId: "YOUR_PROJECT_ID",
    // storageBucket: "YOUR_STORAGE_BUCKET",
    // messagingSenderId: "YOUR_MESSAGING_SENDER_ID",
    // appId: "YOUR_APP_ID"

    apiKey: "AIzaSyCIjxnhXyvF7uQsgEyU8jX99_7p_tqa1x0",
    authDomain: "komed-oi.firebaseapp.com",
    projectId: "komed-oi",
    storageBucket: "komed-oi.firebasestorage.app",
    messagingSenderId: "67197346584",
    appId: "1:67197346584:web:06d5065a8e2709938db46d",
    // measurementId: "G-GM74W7Z9XY"
};

const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

export { messaging };
