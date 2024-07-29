document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function(event) {
        if (event.target.closest('.product-card')) {
            const card = event.target.closest('.product-card');
            const productId = card.getAttribute('data-product-id');
            window.location.href = 'productDetail.php?id=' + productId;
        }
    });
});

function addToCart(productId) {
    fetch('addToCart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ productId })
    }).then(response => {
        if (response.ok) {
            updateCartCount();
            showAddToCartPopup();
        } else {
            alert('Failed to add product to cart.');
        }
    });
}

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
        }, 500);
    }, 1500);
}

function updateCartCount() {
    const cartItems = JSON.parse(sessionStorage.getItem('cartItems')) || [];
    const cartCountElement = document.getElementById('cartCount');
    cartCountElement.textContent = cartItems.reduce((acc, item) => acc + item.quantity, 0);
}
