// Example products array (this could be dynamically populated from a backend)
const products = [
    { img: 'src/images/torrontoZoo.png', title: 'Zoo', desc: 'Explore the wonderful world of animals!', link: 'https://zoo.com' },
    { img: 'src/images/torrontoZoo.png', title: 'Aquarium', desc: 'Dive into the marine life experience!', link: 'https://aquarium.com' },
    { img: 'src/images/torrontoZoo.png', title: 'Safari', desc: 'Take a thrilling journey into the wild!', link: 'https://safari.com' },
    { img: 'src/images/torrontoZoo.png', title: 'Bird Sanctuary', desc: 'Discover rare and beautiful birds.', link: 'https://birdsanctuary.com' },
    { img: 'src/images/torrontoZoo.png', title: 'Bird Sanctuary', desc: 'Discover rare and beautiful birds.', link: 'https://birdsanctuary.com' },
    { img: 'src/images/torrontoZoo.png', title: 'Bird Sanctuary', desc: 'Discover rare and beautiful birds.', link: 'https://birdsanctuary.com' },

    // Add more products as needed
];

// Function to render products using the template
function renderProducts() {
    const store = document.getElementById('product-list');
    const template = document.getElementById('product-template');
    
    products.forEach(product => {
        const clone = template.content.cloneNode(true);
        // Set the image, title, and description
        clone.querySelector('img').src = product.img;
        clone.querySelector('h2').textContent = product.title;
        clone.querySelector('p').textContent = product.desc;

        // Set the link URL and ensure it opens in a new tab
        const linkElement = clone.querySelector('a');
        linkElement.href = product.link;
        linkElement.target = '_blank'; // Open in a new tab

        store.appendChild(clone);
    });

    checkOverflow();
}

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

// Render the products on page load
document.addEventListener('DOMContentLoaded', () => {
    renderProducts();
    window.addEventListener('resize', checkOverflow); // Recheck overflow on window resize
});

