document.addEventListener('DOMContentLoaded', function() {
    fetch('getCartItems.php')
        .then(response => response.json())
        .then(cartItems => {
            renderCartItems(cartItems);
            updateCartCount(cartItems);
        });

    document.querySelectorAll('.add-to-cart-button').forEach(button => {
        button.addEventListener('click', function() {
            const productId = button.dataset.productId;
            const quantity = document.getElementById(`quantity-${productId}`).value;
            addToCart(productId, quantity);
        });
    });
});

function addToCart(productId, quantity) {
    fetch('addToCart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ productId, quantity })
    }).then(response => response.json())
      .then(cartItems => {
          renderCartItems(cartItems);
          updateCartCount(cartItems);
      });
}

function renderCartItems(cartItems) {
    const cartItemsContainer = document.getElementById('cartItems');
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

    document.getElementById('cartTotal').innerText = `$${cartTotal.toFixed(2)}`;
    updateCartCount(cartItems);
}

function updateCartCount(cartItems) { 
    document.getElementById('cartCount').innerText = cartItems.reduce((acc, item) => acc + item.quantity, 0);
}
