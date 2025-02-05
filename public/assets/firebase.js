// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/11.2.0/firebase-app.js";
import { getAnalytics } from "https://www.gstatic.com/firebasejs/11.2.0/firebase-analytics.js";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
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
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
