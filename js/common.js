function updateCartCount() {
    const cartItems = JSON.parse(sessionStorage.getItem('cartItems')) || [];
    const totalCount = cartItems.reduce((acc, item) => acc + item.quantity, 0);
    document.getElementById('cartCount').textContent = totalCount;

}

document.addEventListener('DOMContentLoaded', updateCartCount);