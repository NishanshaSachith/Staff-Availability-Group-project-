document.addEventListener('DOMContentLoaded', () => {
    // Sample notifications (consider fetching these from the database)
    const notifications = [
        'Appointment with Dr. Smith confirmed for 2024-10-10',
        'Your appointment request for Prof. Johnson is pending'
    ];

    // Populate notifications list
    const notificationList = document.getElementById('notification-list');
    notifications.forEach(notification => {
        const listItem = `<li>${notification}</li>`;
        notificationList.innerHTML += listItem;
    });

    // Example of button functionality
    const confirmButtons = document.querySelectorAll('button.confirm');
    confirmButtons.forEach(button => {
        button.addEventListener('click', () => {
            alert('Appointment confirmed!');
            // Add logic to update the database and refresh the appointment requests
        });
    });

    const rescheduleButtons = document.querySelectorAll('button.reschedule');
    rescheduleButtons.forEach(button => {
        button.addEventListener('click', () => {
            alert('Reschedule functionality goes here.');
            // Add logic for rescheduling appointments
        });
    });
});
