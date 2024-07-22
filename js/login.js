sessionStorage.setItem('CSELEC03', 'webprog');

document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const storedPassword = sessionStorage.getItem(username);
    if (password === storedPassword) {
        sessionStorage.setItem('currentUser', username);
        window.location.href = 'index.html';
    } else {
        alert('Invalid credentials.');
    }
});

sessionStorage.setItem('currentUser', username);
