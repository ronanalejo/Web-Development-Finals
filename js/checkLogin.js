document.addEventListener('DOMContentLoaded', function() {
    fetch('checkSession.php')
        .then(response => response.json())
        .then(data => {
            if (!data.loggedIn) {
                window.location.href = 'login.php';
            } else {
                document.getElementById('userDisplay').textContent = data.username;
            }
        });
});