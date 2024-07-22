document.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    const productName = params.get('name') ? decodeURIComponent(params.get('name')) : null;

    const products = JSON.parse(localStorage.getItem('productItems')) || [];
    const product = products.find(p => p.name === productName);

    if (!product) {
        console.error(`Product "${productName}" not found.`);
        return;
    }

    displayProductDetail(product);
});

console.log(product);

function displayProductDetail(product) {
    const productMain = document.getElementById('productMain');
    if (!productMain) {
        console.error('Product main container not found.');
        return;
    }

    const productHTML = `
    <div class="product-image-section">
        <p style="text-align: left;"><a href="index.html">Home</a> / <a>${product.name}</a></p>
        <img src="${product.img}" alt="${product.name}" > 
    </div>
    <div class="product-info">
        <h1>${product.name}</h1>
        <p class="price">$${product.price}</p>
        <div class="sizes">${product.sizes.map(size => `<button class="size-btn" data-size="${size}">${size}</button>`).join('')}</div>
        <button class="add-to-cart-btn">Add to Cart</button>
        <div class="dropdown-container">
            <button class="dropdown-button" onclick="toggleDropdown('description')">Description</button>
            <div id="description" class="dropdown-content" style="display:none;">
                <p>${product.description}</p>
            </div>
        </div>
    </div>
`;

productMain.innerHTML = productHTML;

    setupEventListeners(product); 
}

function setupSizeButtonListeners() {
    document.querySelectorAll('.size-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            selectedSize = this.dataset.size; 
        });
    });
}
function selectSize(size) {
    selectedSize = size;
    document.querySelectorAll('.size-btn').forEach(btn => {
        btn.classList.remove('active'); 
        if(btn.getAttribute('data-size') === size) {
            btn.classList.add('active'); 
        }
    });
}

function toggleDropdown(sectionId) {
    const section = document.getElementById(sectionId);
    if (section) {
        section.style.display = section.style.display === 'none' ? 'block' : 'none';
    }
}

function setupEventListeners(product) {
    
    document.querySelectorAll('.size-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            selectedSize = this.getAttribute('data-size'); 
            document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active')); 
            this.classList.add('active'); 
        });
    });

    
    document.querySelector('.add-to-cart-btn').addEventListener('click', function() {
        if (selectedSize) {
            addToCart(product.name, selectedSize);
        } else {
            alert('Please select a size before adding to cart.');
        }
    });
}

function addToCart(productName, size) {
    if (!selectedSize) {
        alert("Please select a size before adding to cart.");
        return;
    }
    const productItems = JSON.parse(localStorage.getItem('productItems')) || [];
    const productToAdd = productItems.find(item => item.name === productName);
    if (!productToAdd) return; 
    
    const cartItems = JSON.parse(sessionStorage.getItem('cartItems')) || [];
    let item = cartItems.find(item => item.name === productName && item.size === selectedSize);
    
    if (item) {
        item.quantity += 1;
    } else {
        cartItems.push({ 
            name: productName, 
            size: selectedSize, 
            quantity: 1,
            img: productToAdd.img, 
            price: productToAdd.price 
        });
    }

    sessionStorage.setItem('cartItems', JSON.stringify(cartItems));
    updateCartCount();
    
    selectedSize = null;
    document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
  
    updateCartModal();
    window.dispatchEvent(new CustomEvent('cartUpdated'));
}

function updateCartModal() {
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
            <p style="margin-left: 150px; margin-top: -15px;">Price: $<span class="item-price">${item.price}</span></p>
                <div class="quantity-controls" style="margin-left: 30px;">
                    <button class="quantity-decrease" data-index="${index}">-</button>
                    <input type="text" class="cart-item-quantity" value="${item.quantity}" readonly data-index="${index}">
                    <button class="quantity-increase" data-index="${index}">+</button>
                </div>
            <button class="delete-item-btn" style="margin-left: 30px; margin-top: 0px;" data-index="${index}">Delete</button>
            <p style="margin-left: 30px; margin-top: -10px;">Subtotal: <b> $<span class="item-subtotal">${(item.price * item.quantity).toFixed(2)}</span> </b> </p>
        </div>
        `;
        cartItemsContainer.appendChild(itemElement);
        cartTotal += item.price * item.quantity;
        totalItems += item.quantity;
    });

    document.getElementById('cartTotal').innerText = `$${cartTotal.toFixed(2)}`;
    document.getElementById('cartCount').textContent = totalItems; 
    attachQuantityChangeListeners();
    attachDeleteItemListeners();
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
        updateCartCount()
	}
    
function deleteItem(index) { 
    let cartItems = JSON.parse(sessionStorage.getItem('cartItems'));
    cartItems.splice(index, 1);
    sessionStorage.setItem('cartItems', JSON.stringify(cartItems));
    renderCartItems();

    window.addEventListener('cartUpdated', renderCartItems);
    updateCartCount();
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
    updateCartCount();
    }
	}

function selectAllItems() { 
    const isChecked = document.getElementById('selectAll').checked;
    document.querySelectorAll('.cart-item-select').forEach(checkbox => {
        checkbox.checked = isChecked;
    });
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
    }); 
    updateCartCount();
}

