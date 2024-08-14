// Fetch products from the PHP backend
fetch('get_products.php')
    .then(response => response.json())
    .then(products => {
        // Get the store and product template elements
        const store = document.getElementById('product-list');
        const template = document.getElementById('product-template');

        // Loop through the products and create the HTML for each one
        products.forEach(product => {
            const clone = template.content.cloneNode(true);
            clone.querySelector('img').src = product.image_url;
            clone.querySelector('h2').textContent = product.title;
            clone.querySelector('p').textContent = product.description;
            clone.querySelector('a').href = product.link;
            store.appendChild(clone);
        });

        // After rendering, check if scrolling is needed
        checkOverflow();
    })
    .catch(error => console.error('Error fetching products:', error));

// Scroll Store by specified direction (-1 for left, 1 for right)
function scrollStore(direction) {
    const store = document.querySelector('.store');
    const scrollAmount = direction * store.offsetWidth * 0.6; // Scroll by 60% of the container width
    store.scrollBy({
        left: scrollAmount,
        behavior: 'smooth'
    });
}

// Check if the store content overflows horizontally and show/hide buttons accordingly
function checkOverflow() {
    const storeContainer = document.querySelector('.store-container');
    const store = document.querySelector('.store');
    
    if (store.scrollWidth > store.offsetWidth) {
        storeContainer.classList.add('overflow'); // Add class to show buttons
    } else {
        storeContainer.classList.remove('overflow'); // Hide buttons if not overflowing
    }
}

// Add event listener for window resize to check for overflow
window.addEventListener('resize', checkOverflow);
