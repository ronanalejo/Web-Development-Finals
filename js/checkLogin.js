document.addEventListener('DOMContentLoaded', function() {
    if (!sessionStorage.getItem('currentUser')) {
        window.location.href = 'login.html';
    } else {
        const username = sessionStorage.getItem('currentUser');
        document.getElementById('userDisplay').textContent = `${username}`;
    }
});
