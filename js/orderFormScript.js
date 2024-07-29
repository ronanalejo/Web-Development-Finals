document.addEventListener('DOMContentLoaded', function() {
    fetchCartItems();
    document.getElementById('orderForm').addEventListener('submit', function(event) {
        event.preventDefault(); 

        const orderDetails = {
            firstName: document.getElementById('firstName').value,
            lastName: document.getElementById('lastName').value,
            shippingAddress: document.getElementById('shippingAddress').value,
            contactNumber: document.getElementById('contactNumber').value
        };

        fetch('saveOrderDetails.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(orderDetails)
        }).then(response => {
            if (response.ok) {
                window.location.href = 'orderConfirmation.php';
            } else {
                alert('Failed to save order details.');
            }
        });
    });
});

function fetchCartItems() {
    const cartItems = JSON.parse(sessionStorage.getItem('cartItems')) || [];
    renderCartItems(cartItems);
    updateCartCount(cartItems);
}

function renderCartItems(cartItems) {
    const cartItemsContainer = document.getElementById('cartItems');
    cartItemsContainer.innerHTML = ''; 
    let cartTotal = 0;

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

    document.getElementById('cartTotal').innerText = cartTotal.toFixed(2);
    addCartEventListeners();
}

function updateCartCount(cartItems) {
    document.getElementById('cartCount').innerText = cartItems.reduce((acc, item) => acc + item.quantity, 0);
}

function addCartEventListeners() {
    const decreaseButtons = document.querySelectorAll('.quantity-decrease');
    const increaseButtons = document.querySelectorAll('.quantity-increase');
    const deleteButtons = document.querySelectorAll('.delete-item-btn');

    decreaseButtons.forEach(button => {
        button.addEventListener('click', function() {
            const index = parseInt(this.dataset.index);
            updateCartItemQuantity(index, -1);
        });
    });

    increaseButtons.forEach(button => {
        button.addEventListener('click', function() {
            const index = parseInt(this.dataset.index);
            updateCartItemQuantity(index, 1);
        });
    });

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const index = parseInt(this.dataset.index);
            deleteCartItem(index);
        });
    });
}

function updateCartItemQuantity(index, delta) {
    let cartItems = JSON.parse(sessionStorage.getItem('cartItems')) || [];
    if (cartItems[index]) {
        cartItems[index].quantity += delta;
        if (cartItems[index].quantity <= 0) {
            cartItems.splice(index, 1);
        }
        sessionStorage.setItem('cartItems', JSON.stringify(cartItems));
        renderCartItems(cartItems);
        updateCartCount(cartItems);
    }
}

function deleteCartItem(index) {
    let cartItems = JSON.parse(sessionStorage.getItem('cartItems')) || [];
    cartItems.splice(index, 1);
    sessionStorage.setItem('cartItems', JSON.stringify(cartItems));
    renderCartItems(cartItems);
    updateCartCount(cartItems);
}
