document.getElementById('bookingForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const staff = document.getElementById('staffSelect').value;
    const date = document.getElementById('dateSelect').value;
    const time = document.getElementById('timeSelect').value;

    // Create a FormData object to send the data
    const formData = new FormData();
    formData.append('staff_member', staff);
    formData.append('appointment_date', date);
    formData.append('appointment_time', time);

    // Send the data using fetch
    fetch('book_appointment.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const bookingMessage = document.getElementById('bookingMessage');
        if (data.error) {
            bookingMessage.textContent = `Error: ${data.error}`;
            bookingMessage.style.color = 'red';
        } else {
            bookingMessage.textContent = data.message;
            bookingMessage.style.color = 'green';
        }
    })
    .catch(error => {
        const bookingMessage = document.getElementById('bookingMessage');
        bookingMessage.textContent = `Error: ${error.message}`;
        bookingMessage.style.color = 'red';
    });

    // Reset form
    this.reset();
});
