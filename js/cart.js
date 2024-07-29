document.addEventListener('DOMContentLoaded', function() {
    const addToCartButtons = document.querySelectorAll('.add-to-cart');

    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            const productName = this.getAttribute('data-name');
            const productPrice = parseFloat(this.getAttribute('data-price'));
            const productImg = this.getAttribute('data-img');
            const productSize = document.querySelector('input[name="size"]:checked').value;
            const productQuantity = parseInt(document.getElementById('quantity').value);

            let cartItems = JSON.parse(sessionStorage.getItem('cartItems')) || [];

            const existingProductIndex = cartItems.findIndex(item => item.id === productId && item.size === productSize);
            if (existingProductIndex >= 0) {
                cartItems[existingProductIndex].quantity += productQuantity;
            } else {
                cartItems.push({ id: productId, name: productName, price: productPrice, img: productImg, size: productSize, quantity: productQuantity });
            }

            sessionStorage.setItem('cartItems', JSON.stringify(cartItems));
            updateCartModal();
            updateCartCount();
            showAddToCartPopup();
        });
    });

    function showAddToCartPopup() {
        const overlay = document.createElement('div');
        overlay.classList.add('add-to-cart-overlay');

        const popup = document.createElement('div');
        popup.classList.add('add-to-cart-popup');
        popup.textContent = 'Added to cart successfully!';

        overlay.appendChild(popup);
        document.body.appendChild(overlay);

        setTimeout(() => {
            overlay.classList.add('fade-out');
            setTimeout(() => {
                document.body.removeChild(overlay);
            }, 80);
        }, 800);
    }

    function updateCartModal() {
        const cartItems = JSON.parse(sessionStorage.getItem('cartItems')) || [];
        const cartItemsContainer = document.getElementById('cartItems');
        cartItemsContainer.innerHTML = '';
        let total = 0;

        cartItems.forEach((item, index) => {
            const itemElement = document.createElement('div');
            itemElement.classList.add('cart-item');
            itemElement.dataset.index = index;
            itemElement.innerHTML = `
                <div class="cart-item">
                    <input type="checkbox" class="cart-item-checkbox" data-index="${index}">
                    <img src="${item.img}" alt="${item.name}" style="width: 50px; height: auto;">
                    <span>${item.name}</span> - $<span>${item.price.toFixed(2)}</span>
                    <div>
                        Size: 
                        <select class="size-select" data-index="${index}">
                            <option value="7" ${item.size === '7' ? 'selected' : ''}>7</option>
                            <option value="8" ${item.size === '8' ? 'selected' : ''}>8</option>
                            <option value="9" ${item.size === '9' ? 'selected' : ''}>9</option>
                            <option value="10" ${item.size === '10' ? 'selected' : ''}>10</option>
                            <option value="11" ${item.size === '11' ? 'selected' : ''}>11</option>
                        </select>
                    </div>
                    <div>
                        Quantity: 
                        <button class="quantity-decrease" data-index="${index}">-</button>
                        <input type="text" class="cart-item-quantity" value="${item.quantity}" readonly data-index="${index}">
                        <button class="quantity-increase" data-index="${index}">+</button>
                    </div>
                    <span>Total: $<span class="item-total">${(item.price * item.quantity).toFixed(2)}</span></span>
                    <button class="delete-item-btn" data-index="${index}">Delete</button>
                </div>
            `;
            cartItemsContainer.appendChild(itemElement);
            total += item.price * item.quantity;
        });

        updateCartCount(); // Update cart count to get the latest total items

        const cartCountElement = document.getElementById('cartCount');
        const cartTotalElement = document.getElementById('totalPrice');
        const totalItemsElement = document.getElementById('totalItems');

        if (cartCountElement) {
            totalItemsElement.textContent = cartCountElement.textContent;
        }

        if (cartTotalElement) {
            cartTotalElement.textContent = total.toFixed(2);
        }
    }

    document.body.addEventListener('click', function(event) {
        const cartItems = JSON.parse(sessionStorage.getItem('cartItems')) || [];
        const target = event.target;

        if (target.classList.contains('quantity-decrease')) {
            const index = parseInt(target.dataset.index);
            if (cartItems[index].quantity > 1) {
                cartItems[index].quantity--;
                sessionStorage.setItem('cartItems', JSON.stringify(cartItems));
                updateCartModal();
                updateCartCount();
            } else {
                cartItems.splice(index, 1);
                sessionStorage.setItem('cartItems', JSON.stringify(cartItems));
                updateCartModal();
                updateCartCount();
            }
        }

        if (target.classList.contains('quantity-increase')) {
            const index = parseInt(target.dataset.index);
            cartItems[index].quantity++;
            sessionStorage.setItem('cartItems', JSON.stringify(cartItems));
            updateCartModal();
            updateCartCount();
        }

        if (target.classList.contains('delete-item-btn')) {
            const index = parseInt(target.dataset.index);
            cartItems.splice(index, 1);
            sessionStorage.setItem('cartItems', JSON.stringify(cartItems));
            updateCartModal();
            updateCartCount();
        }

        if (target.classList.contains('size-select')) {
            const index = parseInt(target.dataset.index);
            const newSize = target.value;
            cartItems[index].size = newSize;
            sessionStorage.setItem('cartItems', JSON.stringify(cartItems));
            updateCartModal();
            updateCartCount();
        }

        if (target.id === 'deleteSelected') {
            event.preventDefault(); // Prevent form submission or page reload
            const selectedIndexes = [...document.querySelectorAll('.cart-item-checkbox:checked')].map(checkbox => parseInt(checkbox.getAttribute('data-index')));
            selectedIndexes.sort((a, b) => b - a); // Sort in descending order to avoid indexing issues during deletion

            selectedIndexes.forEach(index => {
                cartItems.splice(index, 1);
            });

            sessionStorage.setItem('cartItems', JSON.stringify(cartItems));
            updateCartModal();
            updateCartCount();
        }

        if (target.id === 'selectAll') {
            const checkboxes = document.querySelectorAll('.cart-item-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = target.checked);
        }
    });

    function updateCartCount() {
        const cartItems = JSON.parse(sessionStorage.getItem('cartItems')) || [];
        const cartCountElement = document.getElementById('cartCount');
        cartCountElement.textContent = cartItems.reduce((acc, item) => acc + item.quantity, 0);
    }

    const cartButton = document.getElementById('cartCount');
    cartButton.addEventListener('click', function() {
        const cartModalOverlay = document.querySelector('.cart-modal-overlay');
        cartModalOverlay.style.display = 'block';
        updateCartModal();
    });

    updateCartModal();
    updateCartCount();
});
