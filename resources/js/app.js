import './bootstrap';

window.Echo.channel('user.' + userId)  // Listen to the specific user's channel
    .listen('NotificationReceived', (event) => {
        console.log('Notification Received:', event);

        // You can update your notification icon or display the notification
        // Example: Update the notification count on the icon
        let notificationCount = document.getElementById('bell-count');
        notificationCount.innerText = parseInt(notificationCount.innerText) + 1;

        // Optionally, show the notification content on the frontend
        let notificationArea = document.getElementById('notification-area');
        let notification = document.createElement('div');
        notification.innerHTML = `<strong>${event.notification.title}</strong>: ${event.notification.message}`;
        notificationArea.appendChild(notification);
    });

