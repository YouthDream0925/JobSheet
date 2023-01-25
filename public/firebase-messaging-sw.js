importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');
   
firebase.initializeApp({
    apiKey: "AIzaSyBOBWnDE5CeKh9dpLa0OTVSPqjqGI79Pac",
    projectId: "reluis",
    messagingSenderId: "216062052432",
    appId: "1:216062052432:web:70736aee4af44150b62955"
});
  
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function({data:{title,body,icon}}) {
    return self.registration.showNotification(title,{body,icon});
});