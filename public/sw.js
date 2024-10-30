self.addEventListener("push", (event) => {
    notification = event.data.json();

    event.waitUntil(
        self.registration.showNotification(notification.title, {
            body: notification.body,
            icon: "icon.png",
            data: {
                notifURL: notification.url,
            },
        })
    );
});

self.addEventListener("notificationClick", (event) => {
    event.waitUntil(clients.openWindow(event.notification.data.notifURL));
});
