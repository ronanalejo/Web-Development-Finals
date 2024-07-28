document.addEventListener('DOMContentLoaded', function() {
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
            <img src="${item.img}" alt="${item.name}" class="cart-item-img" style="margin-left: 10px;">
            <h4 class="cart-item-name">${item.name}</h4>
            <p style="margin-left: 150px;">Size: ${item.size}</p>
            <p style="margin-left: 150px;">Price: $<span class="item-price">${item.price}</span></p>
            <div class="quantity-controls" style="margin-left: 10px;">
                <button class="quantity-decrease" data-index="${index}">-</button>
                <input type="text" class="cart-item-quantity" value="${item.quantity}" readonly data-index="${index}">
                <button class="quantity-increase" data-index="${index}">+</button>
            </div>
            <button class="delete-item-btn" style="margin-left: 10px;" data-index="${index}">Delete</button>
            <p style="margin-left: 10px;">Subtotal: $<span class="item-subtotal">${(item.price * item.quantity).toFixed(2)}</span></p>
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
    addQuantityControlListeners(cartItems);
}

function updateCartCount(cartItems) { 
    const cartCountElement = document.getElementById('cartCount');
    if (cartCountElement) {
        cartCountElement.innerText = cartItems.reduce((acc, item) => acc + item.quantity, 0);
    } else {
        console.warn('Cart count element not found');
    }
}

function addQuantityControlListeners(cartItems) {
    const decreaseButtons = document.querySelectorAll('.quantity-decrease');
    const increaseButtons = document.querySelectorAll('.quantity-increase');
    const deleteButtons = document.querySelectorAll('.delete-item-btn');

    decreaseButtons.forEach(button => {
        button.addEventListener('click', function() {
            const index = parseInt(this.getAttribute('data-index'));
            if (cartItems[index].quantity > 1) {
                cartItems[index].quantity--;
                updateCart(cartItems);
            }
        });
    });

    increaseButtons.forEach(button => {
        button.addEventListener('click', function() {
            const index = parseInt(this.getAttribute('data-index'));
            cartItems[index].quantity++;
            updateCart(cartItems);
        });
    });

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const index = parseInt(this.getAttribute('data-index'));
            cartItems.splice(index, 1);
            updateCart(cartItems);
        });
    });
}

function updateCart(cartItems) {
    sessionStorage.setItem('cartItems', JSON.stringify(cartItems));
    renderCartItems(cartItems);
    updateCartCount(cartItems);
}

const cartButton = document.getElementById('cartButton');
const cartModal = document.getElementById('cartModal');
const closeModalElements = document.getElementsByClassName("close");

if (cartButton && cartModal && closeModalElements.length > 0) {
    const closeModal = closeModalElements[0];
    cartButton.onclick = function() {
        cartModal.style.display = "block";
    };

    closeModal.onclick = function() {
        cartModal.style.display = "none";
    };

    window.onclick = function(event) {
        if (event.target === cartModal) {
            cartModal.style.display = "none";
        }
    };
}
