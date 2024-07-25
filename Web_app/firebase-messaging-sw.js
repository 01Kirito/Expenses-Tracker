importScripts('https://www.gstatic.com/firebasejs/9.14.0/firebase-app-compat.js')
importScripts('https://www.gstatic.com/firebasejs/9.14.0/firebase-messaging-compat.js')

const firebaseConfig = {
    apiKey: "AIzaSyCJzTJaJZ5nx_Nq7M9exRx_nZ6QsQL8UA8",
    authDomain: "budget-manager-8a82d.firebaseapp.com",
    projectId: "budget-manager-8a82d",
    storageBucket: "budget-manager-8a82d.appspot.com",
    messagingSenderId: "327106485108",
    appId: "1:327106485108:web:6c7bd1333b009eef432a21"
  };

const app = firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();