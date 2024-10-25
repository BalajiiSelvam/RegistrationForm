document.getElementById('form-container').addEventListener('submit', event => {
    event.preventDefault();

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const role = document.getElementById('role').value;

    // Client-side validation
    if (password.length < 8) {
        alert('Password length should not be less than Eight characters.');
        return false;
    } else if (!/\d/.test(password)) {
        alert('Password should contain at least one digit.');
        return false;
    } else if (!/[A-Z]/.test(password)) {
        alert('Password should contain at least one uppercase letter.');
        return false;
    } else if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
        alert('Password should contain at least one special character.');
        return false;
    }

    // If validation passes, submit the form
    const data = { username, password, role };

    fetch('register.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data),
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('Registration Successful.');
            console.log('Success:', result.success);
            console.log('Message:', result.message); 

        } else {
            alert('Error: ' + result.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred during registration');
    });
});
