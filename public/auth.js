 //log in start

const API_URL = '/api';

async function checkLogin() {
    const token = localStorage.getItem('token');

    if (!token) {
        window.location.href = '/login';
        return;
    }

    try {
        const response = await fetch(`${API_URL}/user`, {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        if (!response.ok) {
            localStorage.removeItem('token');
            window.location.href = '/login';
            return;
        }

        document.body.style.display = 'block';

    } catch (error) {
        console.error(error);
        window.location.href = '/login';
    }
}

checkLogin()