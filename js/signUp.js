document.getElementById('signUpForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const username = document.getElementById('newUsername').value;
    const password = document.getElementById('newPassword').value;

    function showPopup(message, callback) {
        const overlay = document.createElement('div');
        overlay.className = 'overlay';
        document.body.appendChild(overlay);

        const popup = document.createElement('div');
        popup.className = 'popup';
        popup.innerHTML = `
            <div class="popup-message">${message}</div>
            <div class="popup-buttons">
                <button id="closePopup"> OK </button>
            </div>
        `;
        document.body.appendChild(popup);

        popup.style.position = 'fixed';
        popup.style.left = '50%';
        popup.style.top = '50%';
        popup.style.transform = 'translate(-50%, -50%)';

        document.getElementById('closePopup').addEventListener('click', function() {
            document.body.removeChild(overlay);
            document.body.removeChild(popup);
            usernameInput.value = '';
            passwordInput.value = '';
            if (callback) callback();
        });
    }

    if (username === '' || password === '') {
        showPopup('Username or password cannot be blank.');
        return;
    }

    if (sessionStorage.getItem(username)) {
        showPopup('Username already exists');
        return;
    } else {
        sessionStorage.setItem(username, password);
        showPopup('Account created successfully.', function() {
            window.location.href = 'login.html';
        });
    }
});
