document.addEventListener('DOMContentLoaded', function() {
    let productItems = JSON.parse(localStorage.getItem('productItems')) || [];

    const defaultProductItems = [
        {
            name: "Nike Air Force 1 Low",
            description: "The Air Force 1 model represents a modern take on the classic sneaker.",
            price: "90.00",
            img: "img/airforce1low.webp",
            sizes: ["7", "8", "9", "10", "11"]
        },
        {
            name: "Nike Air Max 270",
            description: "The Nike Air Max 270 model's uniquely large heel is meant to maximize comfort.",
            price: "160.00",
            img: "img/airmax270.webp",
            sizes: ["7", "8", "9", "10", "11"]
        },
        {
            name: "Nike Air Max 97",
            description: "Nike Air Max 97 is known for its unique water-ripple line design.",
            price: "160.00",
            img: "img/airmax97.webp",
            sizes: ["7", "8", "9", "10", "11"]
        },
        {
            name: "Nike Air VaporMax Plus",
            description: "Based off the 1998 running shoe, the Nike Air VaporMax Plus is focused on comfort and style.",
            price: "200.00",
            img: "img/vapormaxplus.webp",
            sizes: ["7", "8", "9", "10", "11"]
        },
        {
            name: "Nike Revolution 5",
            description: "This running shoe comes in a variety of styles for men, women, and children.",
            price: "65.00",
            img: "img/revolution5.webp",
            sizes: ["7", "8", "9", "10", "11"]
        },
        {
            name: "Nike Air VaporMax Flyknit 3",
            description: "The Nike Air VaporMax Flyknit 3 is known for its breathable and stretchable material and its VaporMax technology that helps cushion the entire sole.",
            price: "200.00",
            img: "img/vapormaxflyknit3.webp",
            sizes: ["7", "8", "9", "10", "11"]
        },
        {
            name: "Adidas NMD R1",
            description: "These breathable sneakers from Adidas are inspired by the trends of the '80s.",
            price: "130.00",
            img: "img/nmdr1.webp",
            sizes: ["7", "8", "9", "10", "11"]
        },
        {
            name: "Jordan 13 Retro",
            description: "The Jordan 13 \"Flint,\" pictured above, recently became the fastest-selling sneaker in the history of StockX, a leading resale marketplace. More than 40,000 pairs of the sneaker, which was recently revived in a May 2020 release, sold in June.",
            price: "190.00",
            img: "img/jordan13retro.webp",
            sizes: ["7", "8", "9", "10", "11"]
        },
        {
            name: "Jordan I High OG",
            description: "The Air Jordan 1s are considered of the most iconic silhouettes to come from the Jordan Brand. Demand for this style has recently gone up, thanks to their feature in The Last Dance, a 10-part documentary series that ended in May and followed NBA legend Michael Jordan and the Chicago Bulls during the 1997–98 season.",
            price: "170.00",
            img: "img/jordan1highog.webp",
            sizes: ["7", "8", "9", "10", "11"]
        },
        {
            name: "Nike Air Max 90",
            description: "The Nike Air Max 90 is a remake of the original silhouette known for its comfort, stitched overlays, and bright colors.",
            price: "120.00",
            img: "img/airmax90.webp",
            sizes: ["7", "8", "9", "10", "11"]
        }
    ];

    if (!productItems || productItems.length === 0) {
        productItems = defaultProductItems;
        localStorage.setItem('productItems', JSON.stringify(productItems));
    }

    console.log("Loaded from localStorage:", productItems);

    const productContainer = document.getElementById('productContainer');
    if (productContainer) {
        renderProducts(productItems);
    } else {
        console.log('Product container not found - likely not on a product listing page.');
    }

    const searchBar = document.getElementById('searchBar');

    function renderProducts(products) {
        productContainer.innerHTML = products.map(item => `
            <div class="product-card" onclick="window.location.href='productPage.html?name=${encodeURIComponent(item.name)}'">
                <img src="${item.img}" alt="${item.name}" />
                <h3>${item.name}</h3>
                <p>$${item.price}</p>
            </div>
        `).join('');
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

    searchBar.addEventListener('input', function(e) {
            const searchText = e.target.value.toLowerCase();
            const filteredProducts = productItems.filter(item =>
                item.name.toLowerCase().includes(searchText)
            );
            renderProducts(filteredProducts);
        });

});
