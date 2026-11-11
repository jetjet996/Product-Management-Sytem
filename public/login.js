
const API_URL = 'http://127.0.0.1:8000/api';

const loginForm = document.getElementById('loginForm');

loginForm.addEventListener('submit', async (event) => {
    event.preventDefault();

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const errorMessage = document.getElementById('errorMessage');

    errorMessage.textContent = '';

    try {
        const response = await fetch(`${API_URL}/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                username: username,
                password: password
            })
        });

        const data = await response.json();

        if (!response.ok) {
            errorMessage.textContent = data.message || 'Login failed';
            return;
        }
        localStorage.setItem('user_id', data.user.id);
        localStorage.setItem('token', data.token);

        window.location.href = '/dashboard';

    } catch (error) {
        errorMessage.textContent = 'Unable to connect to server.';
        console.error(error);
    }
});
