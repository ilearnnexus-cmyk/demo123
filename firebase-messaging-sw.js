importScripts("https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js");

firebase.initializeApp({
    apiKey: "Your firebase api key",
    authDomain: "Your firebase auth domain",
    projectId: "Your firebase project id",
    messagingSenderId: "Your firebase messaging sender id",
    appId: "Your firebase app id",
});

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function ({
    data: { title, body, icon },
}) {
    return self.registration.showNotification(title, { body, icon });
});
