document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded and parsed'); // Log to ensure the script is running

    fetchOrderDetails();
    fetchCartItems();

    const backHomeButton = document.getElementById('backHomeButton');
    if (backHomeButton) {
        console.log('Back Home button found'); // Log to confirm button exists
        backHomeButton.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default anchor link behavior
            console.log('Back Home button clicked'); // Log for debugging
            removeAllItems(); // Clear the cart
            window.location.href = 'productPage.php'; // Redirect to product page
        });
    } else {
        console.error('Back Home button not found'); // Log if button is missing
    }
});

function fetchOrderDetails() {
    fetch('getOrderDetails.php')
        .then(response => response.json())
        .then(orderDetails => {
            document.getElementById('fullName').innerText = orderDetails.fullName;
            document.getElementById('shippingAddress').innerText = orderDetails.shippingAddress;
            document.getElementById('contactNumber').innerText = orderDetails.contactNumber;
        });
}

function fetchCartItems() {
    const cartItems = JSON.parse(sessionStorage.getItem('cartItems')) || [];
    renderCartItems(cartItems);
}

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
            <p style="margin-left: 150px;">Size: ${item.size}</p>
            <p style="margin-left: 150px;">Price: $<span class="item-price">${item.price}</span></p>
            <p style="margin-left: 150px;">Quantity: <span class="cart-item-quantity">${item.quantity}</span></p>
            <p style="margin-left: 150px;">Subtotal: $<span class="item-subtotal">${(item.price * item.quantity).toFixed(2)}</span></p>
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


function removeAllItems() {
    console.log('Removing all items from cart and order details'); // Log for debugging

    // Clear cart items from sessionStorage and localStorage
    sessionStorage.removeItem('cartItems');
    sessionStorage.removeItem('orderDetails');
    localStorage.removeItem('cartItems');
    localStorage.removeItem('orderDetails');

    // Log the storage contents after removal for debugging
    console.log('Session Storage after removal:', sessionStorage.getItem('cartItems'), sessionStorage.getItem('orderDetails'));
    console.log('Local Storage after removal:', localStorage.getItem('cartItems'), localStorage.getItem('orderDetails'));

    // Update the UI
    const cartItemsContainer = document.getElementById('cartItems');
    if (cartItemsContainer) {
        cartItemsContainer.innerHTML = '';
    }

    const cartTotalElement = document.getElementById('cartTotal');
    if (cartTotalElement) {
        cartTotalElement.innerText = '';
    }

    updateCartCount([]);
}

function updateCartCount(cartItems) {
    const cartCountElement = document.getElementById('cartCount');
    if (cartCountElement) {
        cartCountElement.textContent = cartItems.reduce((acc, item) => acc + item.quantity, 0);
    }
}