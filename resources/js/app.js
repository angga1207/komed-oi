import './bootstrap';

// import { messaging } from "./firebase";
// import { getToken } from "firebase/messaging";

// const requestNotificationPermission = async () => {
//     try {
//         const permission = await Notification.requestPermission();
//         if (permission === "granted") {
//             const token = await getToken(messaging, {
//                 vapidKey: "BJePlygL0zdt8wF8K01lrByWH-kSODlpFaWlB1vI78yATY0ljLGSU6hmHgUmlkGjboNI3dNcA7_rQ3lY45KD6dA",
//             });
//             console.log("FCM Token:", token);
//             // Send this token to your backend for further use
//         } else {
//             console.log("Notification permission denied");
//         }
//     } catch (error) {
//         console.error("Error getting notification permission", error);
//     }
// };

// requestNotificationPermission();
