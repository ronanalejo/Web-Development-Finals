document.addEventListener('DOMContentLoaded', function() {
    renderCartItems();

    window.addEventListener('cartUpdated', renderCartItems);
    
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
        itemElement.innerHTML = `
        <input type="checkbox" class="cart-item-select" data-index="${index}">
        <div class="cartItems" style="margin-top: -55px">
        <img style="width: 100px; margin-left: 35px;" src="${item.img}" alt="${item.name}" class="cart-item-img">
            <h4 class="cart-item-name">${item.name}</h4>
            <p style="margin-left: 150px;">Size: ${item.size}</p>
            <p style="margin-left: 150px;  margin-top: -15px;"">Price: $<span class="item-price">${item.price}</span></p>
                <div class="quantity-controls" style="margin-left: 30px;">
                    <button class="quantity-decrease" data-index="${index}">-</button>
                    <input type="text" class="cart-item-quantity" value="${item.quantity}" readonly data-index="${index}">
                    <button class="quantity-increase" data-index="${index}">+</button>
                </div>
            <button class="delete-item-btn" style="margin-left: 30px;  margin-top: 0px;" data-index="${index}">Delete</button>
            <p style="margin-left: 30px; margin-top: -10px;">Subtotal: <b> $<span class="item-subtotal">${(item.price * item.quantity).toFixed(2)}</span> </b> </p>
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
    totalItemsDisplay.innerText = `Total Items: ${totalItems}`;

    document.getElementById('cartTotal').innerText = `$${cartTotal.toFixed(2)}`;
    attachQuantityChangeListeners();
    attachDeleteItemListeners();
    
    updateCartCount();
}

function attachQuantityChangeListeners() { 
	document.querySelectorAll('.quantity-increase, .quantity-decrease').forEach(button => {
			button.addEventListener('click', function() {
				const isIncrease = button.classList.contains('quantity-increase');
				updateItemQuantity(button.dataset.index, isIncrease ? 1 : -1);
			});
		});
	}

function updateItemQuantity(index, change) { 
    let cartItems = JSON.parse(sessionStorage.getItem('cartItems'));
    if (!cartItems) return;
    let item = cartItems[index];
    if (!item) return;

    let newQuantity = item.quantity + change;
    if (newQuantity > 0) {
        cartItems[index].quantity = newQuantity;
    } else {
        cartItems.splice(index, 1); 
    }

    sessionStorage.setItem('cartItems', JSON.stringify(cartItems));
    renderCartItems();

    window.addEventListener('cartUpdated', renderCartItems);
     
	}

function attachDeleteItemListeners() {
		document.querySelectorAll('.delete-item-btn').forEach(button => {
        button.addEventListener('click', function() {
            deleteItem(button.dataset.index);
        });
    });
	}
    
function deleteItem(index) { 
    let cartItems = JSON.parse(sessionStorage.getItem('cartItems'));
    cartItems.splice(index, 1);
    sessionStorage.setItem('cartItems', JSON.stringify(cartItems));
    renderCartItems();

    window.addEventListener('cartUpdated', renderCartItems);
     
	}

function deleteSelectedItems() { 
    let cartItems = JSON.parse(sessionStorage.getItem('cartItems'));
    const checkboxes = document.querySelectorAll('.cart-item-select:checked');
    let indicesToDelete = [...checkboxes].map(checkbox => parseInt(checkbox.dataset.index));

    if (indicesToDelete.length > 0) {
        indicesToDelete.sort((a, b) => b - a).forEach(index => cartItems.splice(index, 1));
        sessionStorage.setItem('cartItems', JSON.stringify(cartItems));
        renderCartItems();

    window.addEventListener('cartUpdated', renderCartItems);
     
    }
	}

function selectAllItems() { 
    const isChecked = document.getElementById('selectAll').checked;
    document.querySelectorAll('.cart-item-select').forEach(checkbox => {
        checkbox.checked = isChecked;
    });
	}
    
function updateCartCount() { 
    const cartItems = JSON.parse(sessionStorage.getItem('cartItems')) || [];
    document.getElementById('cartCount').innerText = cartItems.reduce((acc, item) => acc + item.quantity, 0);
	}
function showDeleteConfirmation() { 
    const overlay = document.createElement('div');
    overlay.className = 'overlay';
    document.body.appendChild(overlay);

    const confirmationPopup = document.createElement('div');
    confirmationPopup.className = 'confirmation-popup';
    confirmationPopup.innerHTML = `
        <div class="confirmation-message">Are you sure you want to delete the selected items?</div>
        <div class="confirmation-buttons">
            <button id="confirmDelete">Yes</button>
            <button id="cancelDelete">No</button>
        </div>
    `;
    document.body.appendChild(confirmationPopup);

    
    confirmationPopup.style.position = 'fixed';
    confirmationPopup.style.left = '50%';
    confirmationPopup.style.top = '50%';
    confirmationPopup.style.transform = 'translate(-50%, -50%)';

    const confirmButton = document.getElementById('confirmDelete');
    const cancelButton = document.getElementById('cancelDelete');

    confirmButton.addEventListener('click', function() {
        
        deleteSelectedItems();
        document.body.removeChild(overlay);
        document.body.removeChild(confirmationPopup);

        const selectAll = document.getElementById('selectAll');
        if (selectAll.checked) {
            selectAll.checked = false;
        }
    });

    cancelButton.addEventListener('click', function() {
        
        document.body.removeChild(overlay);
        document.body.removeChild(confirmationPopup);
    }); }


    document.getElementById('orderForm').addEventListener('submit', function(event) {
    event.preventDefault(); 

    
    const firstName = document.getElementById('firstName').value;
    const lastName = document.getElementById('lastName').value;
    const shippingAddress = document.getElementById('shippingAddress').value;
    const contactNumber = document.getElementById('contactNumber').value;

    
    const orderDetails = {
        firstName,
        lastName,
        shippingAddress,
        contactNumber
    };

    
    sessionStorage.setItem('orderDetails', JSON.stringify(orderDetails));

    
    window.location.href = 'orderConfirmation.html';

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
