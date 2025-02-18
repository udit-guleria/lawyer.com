document.addEventListener('DOMContentLoaded', function() {
    // Check if user is already logged in
    if (localStorage.getItem('user') && window.location.pathname.includes('login.html')) {
        window.location.href = 'dashboard.html';
    }

    const loginForm = document.getElementById('loginForm');
    const signupForm = document.getElementById('signupForm');

    loginForm?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(loginForm);
        const data = {
            email: formData.get('email'),
            password: formData.get('password')
        };

        try {
            const response = await fetch('api/users.php?email=' + data.email);
            const users = await response.json();
            const user = users[0];

            if (user) {
                localStorage.setItem('user', JSON.stringify(user));
                window.location.href = 'dashboard.html';
            } else {
                alert('Invalid credentials');
            }
        } catch (error) {
            alert('Login failed');
        }
    });

    signupForm?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(signupForm);
        const data = {
            name: formData.get('name'),
            email: formData.get('email'),
            password: formData.get('password'),
            type: formData.get('type')
        };

        try {
            const response = await fetch('api/users.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            if (result.message) {
                alert('Account created successfully. Please login.');
                document.getElementById('login-tab').click();
            } else {
                alert('Signup failed: ' + result.error);
            }
        } catch (error) {
            alert('Signup failed');
        }
    });
}); 