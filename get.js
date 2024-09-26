document.getElementById('contactForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    // Get form values
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const message = document.getElementById('message').value;

    // Simple validation
    if (name === '' || email === '' || message === '') {
        document.getElementById('message').innerText = 'All fields are required!';
        document.getElementById('message').style.color = '#dc3545'; // Red color for error
        return;
    }

    // Normally here you would send the data to the server
    // For demonstration purposes, we'll just show a success message
    document.getElementById('message').innerText = 'Form submitted successfully!';
    document.getElementById('message').style.color = '#28a745'; // Green color for success

    // Clear form
    document.getElementById('contactForm').reset();
});