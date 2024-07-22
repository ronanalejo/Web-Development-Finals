document.addEventListener('DOMContentLoaded', function() {
    const cartButton = document.getElementById('cartCount');
    const cartModal = document.createElement('div');
    cartModal.innerHTML = `
    <input type="checkbox" class="cart-item-select" data-index="${index}">
    <img style="width: 100px;"src="${item.img}" alt="${item.name}" class="cart-item-img">
    <div class="cart-item-info">
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
        <a href="orderForm.html"><button class="checkout-btn" id="checkout">Checkout</button></a>
    </div>
    `;
    document.body.appendChild(cartModal);

    const cartModalOverlay = document.querySelector('.cart-modal-overlay');
    cartModalOverlay.style.display = 'none';

    cartButton.addEventListener('click', function() {
        updateCartModal();
    });

    document.getElementById('checkout').addEventListener('click', function(event) {
        event.preventDefault(); 
        window.location.href = 'orderForm.html'; 
    });

    function updateCartModal() {
        const cartItems = JSON.parse(sessionStorage.getItem('cartItems')) || [];
        const cartItemsContainer = document.getElementById('cartItems');
        cartItemsContainer.innerHTML = '';
        let total = 0;

        cartItems.forEach((item, index) => {
            const itemElement = document.createElement('div');
            itemElement.innerHTML = `
                <input type="checkbox" class="cart-item-checkbox" data-index="${index}">
                <img src="${item.img}" alt="${item.name}" style="width: 50px; height: auto;">
                <span>${item.name}</span> - $<span>${item.price}</span>
            `;
            cartItemsContainer.appendChild(itemElement);
            total += parseFloat(item.price);
        });

        document.getElementById('totalItems').textContent = cartItems.length;
        document.getElementById('totalPrice').textContent = total.toFixed(2);
        updateCartCount();
    }
    
    cartButton.addEventListener('click', function() {
        updateCartModal();
    });
    updateCartModal();
    updateCartCount();
});
