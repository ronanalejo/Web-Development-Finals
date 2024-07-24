document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function(event) {
        // Handle product card click
        if (event.target.closest('.product-card')) {
            const card = event.target.closest('.product-card');
            const productId = card.getAttribute('data-product-id');
            console.log('Redirecting to product ID:', productId); // Debug log
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
        } else {
            alert('Failed to add product to cart.');
        }
    });
}

function updateCartCount() {
    fetch('getCartCount.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('cartCount').innerText = data.count;
        });
}

function searchProducts() {
    const searchQuery = document.getElementById('searchBar').value;
    window.location.href = 'productPage.php?search=' + searchQuery;
}