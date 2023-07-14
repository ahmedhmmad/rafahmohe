// Get the notifications dropdown menu element
const notificationsDropdown = document.getElementById('notification-dropdown');
const userId = notificationsDropdown.getAttribute('data-user-id');

// Listen for the Pusher event
const pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
    cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
    encrypted: true
});

const channel = pusher.subscribe('department-head-' + userId);
channel.bind('ticket-created', function (data) {
    // Create a new notification item
    const notificationItem = document.createElement('li');
    notificationItem.innerHTML = `
        <a class="dropdown-item" href="#">
            ${data.ticket.subject}
        </a>
    `;

    // Add the new notification item to the dropdown menu
    notificationsDropdown.appendChild(notificationItem);
});
