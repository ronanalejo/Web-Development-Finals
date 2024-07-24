document.addEventListener('DOMContentLoaded', function() {
    fetch('getCartItems.php')
        .then(response => response.json())
        .then(cartItems => {
            renderCartItems(cartItems);
            updateCartCount(cartItems);
        })
        .catch(error => console.error('Error fetching cart items:', error));
});

function renderCartItems(cartItems) {
    const cartItemsContainer = document.getElementById('cartItems');
    if (!cartItemsContainer) {
        console.warn('Cart items container not found');
        return;
    }
    cartItemsContainer.innerHTML = ''; 
    let cartTotal = 0;
    let totalItems = 0;

    cartItems.forEach((item, index) => {
        const itemElement = document.createElement('div');
        itemElement.innerHTML = `
        <div class="cartItems">
            <img src="${item.img}" alt="${item.name}" class="cart-item-img">
            <h4 class="cart-item-name">${item.name}</h4>
            <p>Size: ${item.size}</p>
            <p>Price: $<span class="item-price">${item.price}</span></p>
            <div class="quantity-controls">
                <button class="quantity-decrease" data-index="${index}">-</button>
                <input type="text" class="cart-item-quantity" value="${item.quantity}" readonly data-index="${index}">
                <button class="quantity-increase" data-index="${index}">+</button>
            </div>
            <button class="delete-item-btn" data-index="${index}">Delete</button>
            <p>Subtotal: $<span class="item-subtotal">${(item.price * item.quantity).toFixed(2)}</span></p>
        </div>
        `;
        cartItemsContainer.appendChild(itemElement);
        cartTotal += item.price * item.quantity;
        totalItems += item.quantity;
    });

    const cartTotalElement = document.getElementById('cartTotal');
    if (cartTotalElement) {
        if (cartTotal > 0) {
            cartTotalElement.innerText = `$${cartTotal.toFixed(2)}`;
        } else {
            cartTotalElement.innerText = '';
        }
    } else {
        console.warn('Cart total element not found');
    }

    updateCartCount(cartItems);
}

function updateCartCount(cartItems) { 
    const cartCountElement = document.getElementById('cartCount');
    if (cartCountElement) {
        cartCountElement.innerText = cartItems.reduce((acc, item) => acc + item.quantity, 0);
    } else {
        console.warn('Cart count element not found');
    }
}
