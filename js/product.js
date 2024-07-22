document.addEventListener('DOMContentLoaded', function() {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const productName = urlParams.get('productName');
    const productItems = JSON.parse(localStorage.getItem('productItems')) || [];
    const product = productItems.find(item => item.name === productName);
    if (product) {
        document.getElementById('productDetail').innerHTML = `
            <img src="${product.img}" alt="${product.name}">
            <h3>${product.name}</h3>
            <p class="price">$${product.price}</p>
            <button onclick="addToCart('${product.name}')">Add to Cart</button>
        `;
    } else {
        document.getElementById('productDetail').innerHTML = '<p>Product not found.</p>';
    }
});

function addToCart(productName) {
    const cartItems = JSON.parse(sessionStorage.getItem('cartItems')) || [];
    const productItems = JSON.parse(localStorage.getItem('productItems')) || [];
    const product = productItems.find(item => item.name === productName);
    if (product) {
        cartItems.push(product);
        sessionStorage.setItem('cartItems', JSON.stringify(cartItems));
        alert('Added to cart.');
    }
}
