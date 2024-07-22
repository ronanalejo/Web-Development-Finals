document.addEventListener('DOMContentLoaded', function() {
    renderOrderDetails();
    renderOrderItems();
    
});

function renderOrderDetails() {
    
    let orderDetails = JSON.parse(sessionStorage.getItem('orderDetails'));
    
    
    if (!orderDetails) {
        const urlParams = new URLSearchParams(window.location.search);
        orderDetails = {
            firstName: urlParams.get('firstName') || 'N/A',
            lastName: urlParams.get('lastName') || '',
            shippingAddress: urlParams.get('shippingAddress') || 'N/A',
            contactNumber: urlParams.get('contactNumber') || 'N/A'
        };
    }
    
    document.getElementById('recipientName').textContent = `${orderDetails.firstName} ${orderDetails.lastName}`;
    document.getElementById('shippingAddress').textContent = orderDetails.shippingAddress;
    document.getElementById('contactNumber').textContent = orderDetails.contactNumber;
}

function renderOrderItems() {
    const cartItems = JSON.parse(sessionStorage.getItem('cartItems')) || [];
    const orderItemsContainer = document.getElementById('orderItems');
    let totalItems = 0;
    let totalPrice = 0;

    cartItems.forEach(item => {
        const itemElement = document.createElement('div');
        itemElement.className = 'order-item';
        itemElement.innerHTML = `<p>${item.name} - $${item.price} x ${item.quantity}</p>`;
        orderItemsContainer.appendChild(itemElement);

        totalItems += item.quantity;
        totalPrice += item.price * item.quantity;
    });

    document.getElementById('totalItems').textContent = totalItems;
    document.getElementById('totalPrice').textContent = `$${totalPrice.toFixed(2)}`;
}

document.addEventListener('DOMContentLoaded', function() {
    renderCartItems();
    document.getElementById('deleteSelected').addEventListener('click', function(event) {
        event.preventDefault(); 
        showDeleteConfirmation();
    });
    document.getElementById('selectAll').addEventListener('change', function() {
        const isChecked = this.checked;
        document.querySelectorAll('.cart-item-select').forEach(checkbox => {
            checkbox.checked = isChecked;
        });
    });
    
    updateCartCount();

    
    document.getElementById('orderForm').addEventListener('submit', function(event) {
        event.preventDefault(); 

        const orderDetails = {
            firstName: document.getElementById('firstName').value,
            lastName: document.getElementById('lastName').value,
            shippingAddress: document.getElementById('shippingAddress').value,
            contactNumber: document.getElementById('contactNumber').value,
            
            cartItems: JSON.parse(sessionStorage.getItem('cartItems')) || []
        };
    
        sessionStorage.setItem('orderDetails', JSON.stringify(orderDetails));
    
        window.location.href = 'orderConfirmation.html';
    });
});

function renderCartItems() {
    const cartItems = JSON.parse(sessionStorage.getItem('cartItems')) || [];
    const cartItemsContainer = document.getElementById('cartItems');
    cartItemsContainer.innerHTML = ''; 
    let cartTotal = 0;
    let totalItems = 0; 

    cartItems.forEach((item, index) => {
        const itemElement = document.createElement('div');
        itemElement.className = 'cart-item';
        itemElement.innerHTML = `
        <div class="cartItems">
        <img style="width: 100px; margin-left: 35px;" src="${item.img}" alt="${item.name}" class="cart-item-img">
            <h4 class="cart-item-name" style="margin-top: 15px;">${item.name}</h4>
            <p style="margin-left: 150px;>Size: ${item.size}</p>
            <p style="margin-left: 150px; margin-top: -15px;">Price: $<span class="item-price">${item.price}</span></p>
            <p style="margin-left: 150px; margin-top: -15px;"> Quantity: ${item.quantity} </p>
            <p style="margin-left: 150px; margin-top: -15px;"> Subtotal: <b> $<span class="item-subtotal">${(item.price * item.quantity).toFixed(2)}</span> </b> </p>
        </div>
        `;
        cartItemsContainer.appendChild(itemElement);
        cartTotal += item.price * item.quantity;
        totalItems += item.quantity; 
    });

    
    let totalItemsDisplay = document.getElementById('totalItemsDisplay');
    if (!totalItemsDisplay) {
        totalItemsDisplay = document.createElement('p');
        totalItemsDisplay.id = 'totalItemsDisplay';
        
        const totalPriceElement = document.getElementById('cartTotal').parentElement;
        totalPriceElement.insertAdjacentElement('beforebegin', totalItemsDisplay);
    }
    

    document.getElementById('cartTotal').innerText = `$${cartTotal.toFixed(2)}`;
    attachQuantityChangeListeners();
    attachDeleteItemListeners();
    
    updateCartCount();
}

function removeItems(){
    
    sessionStorage.removeItem('cartItems');
    sessionStorage.removeItem('orderDetails');
    localStorage.removeItem('cartItems');
    localStorage.removeItem('orderDetails');
    window.location.href = 'index.html'
}

function updateCartCount() { 
    const cartItems = JSON.parse(sessionStorage.getItem('cartItems')) || [];
    document.getElementById('cartCount').innerText = cartItems.reduce((acc, item) => acc + item.quantity, 0);
	}
    
