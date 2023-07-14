// Get the notifications dropdown menu element
const notificationsDropdown = document.getElementById('notification-dropdown');
const userId = notificationsDropdown.getAttribute('data-user-id');

// Get the pusher key and cluster from the Blade template
const PUSHER_APP_KEY = pusherAppKey;
const PUSHER_APP_CLUSTER = pusherAppCluster;

// Enable pusher logging - don't include this in production
Pusher.logToConsole = true;

// Listen for the Pusher event
const pusher = new Pusher(PUSHER_APP_KEY, {
    cluster: PUSHER_APP_CLUSTER,
    encrypted: true
});

const channel = pusher.subscribe('department-head-' + userId);
channel.bind('pusher:subscription_succeeded', function () {
    console.log('Subscription succeeded');
});

// Bind to the 'ticket-created' event
channel.bind('ticket-created', function (data) {
    console.log(data); // Log the received data to the console

    // Create a new notification item
    const notificationItem = document.createElement('li');
    notificationItem.innerHTML = `
        <a class="dropdown-item" href="#">
            // ${data.ticket.subject}
            A new ticket has been created
        </a>
    `;

    // Add the new notification item to the dropdown menu
    notificationsDropdown.appendChild(notificationItem);
    console.log('New notification added');

});
