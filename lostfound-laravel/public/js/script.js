/**
 * Lost&Found Hub - Main JavaScript File
 * Handles all frontend interactions and API calls
 */

// Global variables
const currentUser = window.currentUser;

let allItems = [];
//let filteredItems = [];
let currentFilter = 'all';
let itemModal, contactModal;
//const BASE_URL = "/lost-and-found-system/";
//for locating the folder 
//const BASE_URL = window.location.origin + "/lost-and-found-system/";


// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

document.addEventListener('DOMContentLoaded', () => {
    if (currentUser) {
    loadItems();
    } else {
        loadItems(); // same endpoint, backend decides visibility
    }
});
function csrf() {
    return document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute('content');
}

/**
 * Initialize the application
 */
async function initializeApp() {
    // Initialize Bootstrap modals
    const itemModalEl = document.getElementById('item-modal');
    const contactModalEl = document.getElementById('contact-modal');
    if (itemModalEl) itemModal = new bootstrap.Modal(itemModalEl);
    if (contactModalEl) contactModal = new bootstrap.Modal(contactModalEl);

    //await checkAuthStatus();
    await loadItems();
    setupEventListeners();
}

/**
 * Check user authentication status
 */
/*async function checkAuthStatus() {
    try {
        const response = await fetch('api/auth-check.php');
        if (response.ok) {
            const result = await response.json();
            if (result.success && result.user) {
                currentUser = result.user;
                updateNavigation(true);
            } else {
                updateNavigation(false);
            }
        }
    } catch (error) {
        console.log('Auth check failed:', error);
        updateNavigation(false);
    }
}*/

/**
 * Update navigation based on auth status
 */
function updateNavigation(isLoggedIn) {
    const navAuth = document.getElementById('nav-auth');
    const navUser = document.getElementById('nav-user');
    const usernameDisplay = document.getElementById('username-display');

    if (isLoggedIn && currentUser) {
        if (navAuth) navAuth.style.display = 'none';
        if (navUser) navUser.style.display = 'flex';
        if (usernameDisplay) usernameDisplay.textContent = currentUser.username;
    } else {
        if (navAuth) navAuth.style.display = 'flex';
        if (navUser) navUser.style.display = 'none';
    }
}

/**
 * Setup event listeners
 */
function setupEventListeners() {
    // Contact form submission
    const contactForm = document.getElementById('contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', handleContactSubmission);
    }

    // Search input debouncing
    let searchTimeout;
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(searchItems, 300);
        });
    }
}

/**
 * Load all items
 */
async function loadItems() {
    const itemsGrid = document.getElementById('items-grid');
    if (!itemsGrid) return;

    try {
        const response = await fetch('/items');
        if (!response.ok) throw new Error('Failed');

        const data = await response.json();

        allItems = data;          // ← THIS WAS MISSING
        filteredItems = data;

        displayItems(allItems);   // ← correct function
    } catch (err) {
        console.error(err);
        itemsGrid.innerHTML =
            '<p class="text-danger text-center">Failed to load items</p>';
    }
}

/**
 * Display items in the grid
 */
function displayItems(items) {
    const itemsGrid = document.getElementById('items-grid');
    if (!itemsGrid) return;

    if (items.length === 0) {
        itemsGrid.innerHTML = `
            <div class="col-12 text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <p class="text-muted">No items found matching your criteria</p>
            </div>
        `;
        return;
    }

    itemsGrid.innerHTML = items.map(item => createItemCard(item)).join('');
}


/**
 * Create HTML for an item card
 */
function createItemCard(item) {
    const date = new Date(item.created_at).toLocaleDateString();
    const typeClass = item.type === 'lost' ? 'danger' : 'success';
    const typeBadge = item.type === 'lost' ? 'Lost' : 'Found';
    const isOwner = currentUser && currentUser.id == item.user_id;
    const isAdmin = currentUser && currentUser.is_admin;
    const isResolved = item.status === 'resolved';
    const canManage = (isOwner || isAdmin) && !isResolved;
    // Determine icon based on category
    const categoryIcons = {
        'Electronics': 'fa-laptop',
        'Bags': 'fa-bag-shopping',
        'Jewelry': 'fa-gem',
        'Clothing': 'fa-shirt',
        'Books': 'fa-book',
        'Personal Items': 'fa-wallet',
        'Other': 'fa-box'
    };
    const icon = categoryIcons[item.category] || 'fa-box';
    
    return `
        <div class="col-md-6 col-lg-4">
            <div class="card item-card shadow-sm hover-shadow" onclick="showItemDetails(${item.id})">
                ${item.image_path ? `
                    <img src="/storage/${escapeHtml(item.image_path)}" alt="Item image"> <!--Added storage path for public side.-->
                ` : `
                    <div class="placeholder-img">
                        <i class="fas ${icon}"></i>
                    </div>
                `}
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-${typeClass}">${typeBadge}</span>
                        ${isResolved ? '<span class="badge bg-secondary">Resolved</span>' : ''}
                    </div>
                    <h5 class="card-title">${escapeHtml(item.title)}</h5>
                    <p class="card-text text-muted small">${escapeHtml(item.description).substring(0, 100)}${item.description.length > 100 ? '...' : ''}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-light text-dark">${escapeHtml(item.category)}</span>
                        <small class="text-muted">${date}</small>
                    </div>
                    ${item.location ? `<div class="mt-2"><small class="text-muted"><i class="fas fa-map-marker-alt me-1"></i>${escapeHtml(item.location)}</small></div>` : ''}
                    ${canManage && !isResolved ? `
                        <div class="mt-3 d-flex gap-2" onclick="event.stopPropagation()">
                        
                            <a href="/items/${item.id}/edit"
                            class="btn btn-sm btn-outline-primary flex-fill d-flex align-items-center justify-content-center gap-1">
                                <i class="fas fa-edit"></i>
                                Edit
                            </a>
    
                            <button class="btn btn-sm btn-outline-success flex-fill" onclick="resolveItem(${item.id})">
                                <i class="fas fa-check"></i> Resolve
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteItem(${item.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    ` : ''}
                    ${isAdmin && isResolved ? `
                <div class="mt-3 d-flex align-items-center gap-2"
                    onclick="event.stopPropagation()">

                    <span class="small text-muted" style="visibility: hidden">
                        DELETE USER'S RESOLVED POST
                    </span>

                    <button class="btn btn-sm btn-outline-danger ms-auto"
                            onclick="deleteItem(${item.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            ` : ''}
                </div>
            </div>
        </div>
    `;
}

/**
 * Show item details in modal
 */
async function showItemDetails(itemId) {
    const item = allItems.find(i => i.id == itemId);
    if (!item) return;

    const modalTitle = document.getElementById('modal-title');
    const modalBody = document.getElementById('modal-body');

    if (!modalTitle || !modalBody) return;

    modalTitle.textContent = item.title;
    
    const date = new Date(item.created_at).toLocaleDateString();
    const typeClass = item.type === 'lost' ? 'danger' : 'success';
    const canContact = currentUser && currentUser.id != item.user_id;
    const isResolved = item.status === 'resolved';
    
    // Determine icon based on category
    const categoryIcons = {
        'Electronics': 'fa-laptop',
        'Bags': 'fa-bag-shopping',
        'Jewelry': 'fa-gem',
        'Clothing': 'fa-shirt',
        'Books': 'fa-book',
        'Personal Items': 'fa-wallet',
        'Other': 'fa-box'
    };
    const icon = categoryIcons[item.category] || 'fa-box';

    modalBody.innerHTML = `
        <div class="modal-item-details">
            ${item.image_path ? `
                 <img src="/storage/${escapeHtml(item.image_path)}" alt="Item image"> <!--Added storage path for public side.-->

            ` : `
                <div class="placeholder-img rounded mb-3">
                    <i class="fas ${icon}"></i>
                </div>
            `}
            
            <div class="d-flex gap-2 mb-3">
                <span class="badge bg-${typeClass}">${item.type}</span>
                ${isResolved ? '<span class="badge bg-secondary">Resolved</span>' : '<span class="badge bg-success">Active</span>'}
            </div>
            
            <div class="mb-3">
                <h6 class="fw-bold">Description</h6>
                <p>${escapeHtml(item.description)}</p>
            </div>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <strong>Category:</strong> ${escapeHtml(item.category)}
                </div>
                ${item.location ? `<div class="col-md-6"><strong>Location:</strong> ${escapeHtml(item.location)}</div>` : ''}
                ${item.date_occurred ? `<div class="col-md-6"><strong>Date:</strong> ${new Date(item.date_occurred).toLocaleDateString()}</div>` : ''}
                <div class="col-md-6">
                    <strong>Posted by:</strong> ${escapeHtml(item.user?.full_name || 'Unknown')}
                </div>
                <div class="col-md-6">
                    <strong>Posted on:</strong> ${date}
                </div>
            </div>
            
           ${canContact && !isResolved ? `
                <div class="mt-4">
                    <button class="btn btn-primary" onclick="showContactForm(${item.id})">
                        <i class="fas fa-envelope me-2"></i>Contact Poster
                    </button>
                </div>
            ` : ''}
            
            ${!currentUser ? `
                <div class="mt-4">
                    <a href="/login" class="btn btn-primary">Login to Contact Poster</a>
                </div>
            ` : ''}
        </div>
    `;

    itemModal.show();
}

/**
 * Show contact form modal
 */
function showContactForm(itemId) {
    const contactItemId = document.getElementById('contact-item-id');
    
    if (contactItemId) {
        contactItemId.value = itemId;
        itemModal.hide();
        contactModal.show();
    }
}

/**
 * Handle contact form submission
 */
async function handleContactSubmission(e) {
    e.preventDefault();
    
    if (!currentUser) {
        alert('Please login to contact posters');
        return;
    }

    const form = e.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    const itemId = document.getElementById('contact-item-id').value;
    const message = document.getElementById('contact-message').value;
    const contactInfo = document.getElementById('contact-info').value;
    

    if (!message.trim() || !contactInfo.trim()) {
        alert('Please fill in all fields');
        return;
    }
    
    try {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';

        const response = await fetch(`/items/${itemId}/contact`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrf(),
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            message,
            contact_info: contactInfo
        })
});


        const result = await response.json();

        if (result.success) {
            alert('Message sent successfully! The poster will be notified.');
            contactModal.hide();
            form.reset();
        } else {
            alert(result.message || 'Failed to send message');
        }
    } catch (error) {
        console.error('Error sending contact:', error);
        alert('Failed to send message. Please try again.');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Send Message';
    }
}

/**
 * Edit item
 */
function editItem(itemId) {
    window.location.href = `/items/${itemId}/edit`; 
}

/**
 * Resolve item
 */
async function resolveItem(itemId) {
    if (!confirm('Mark as resolved?')) return;

    const response = await fetch(`/items/${itemId}/resolve`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': csrf(),
            'Accept': 'application/json'
        }
    });

    if (response.ok) {
        showSuccessMessage('Item resolved');
         // REMOVE item instantly from UI
        allItems = allItems.filter(item => item.id !== itemId);
        filteredItems = filteredItems.filter(item => item.id !== itemId);

        displayItems(filteredItems);
        itemModal.hide();
        //loadItems();
    }
}


/**
 * Delete item
 */
async function deleteItem(itemId) {
    if (!confirm('Are you sure?')) return;

    const response = await fetch(`/items/${itemId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrf(),
            'Accept': 'application/json'
        }
    });

    if (response.ok) {
        showSuccessMessage('Item deleted');
        loadItems();
    }
}


/**
 * Search items
 */
function searchItems() {
    const searchInput = document.getElementById('search-input');
    if (!searchInput) return;

    const query = searchInput.value.toLowerCase().trim();
    
    filteredItems = allItems.filter(item => {
        const matchesSearch = !query || 
            item.title.toLowerCase().includes(query) ||
            item.description.toLowerCase().includes(query) ||
            item.category.toLowerCase().includes(query);
        
        const matchesType = currentFilter === 'all' || item.type === currentFilter;
        
        return matchesSearch && matchesType;
    });

    applyFilters();
}

/**
 * Filter items by type
 */
function filterItems(type) {
    currentFilter = type;
    
    // Update active button
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    event.target.classList.add('active');
    
    applyFilters();
}

/**
 * Show all items
 */
function showAllItems() {
    currentFilter = 'all';
    
    // Update active button
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    event.target.classList.add('active');
    
    applyFilters();
}

/**
 * Filter by category
 */
function filterByCategory() {
    applyFilters();
}

/**
 * Apply all current filters
 */
function applyFilters() {
    const searchInput = document.getElementById('search-input');
    const categoryFilter = document.getElementById('category-filter');
    
    const searchQuery = searchInput ? searchInput.value.toLowerCase().trim() : '';
    const selectedCategory = categoryFilter ? categoryFilter.value : '';

    filteredItems = allItems.filter(item => {
        const matchesSearch = !searchQuery || 
            item.title.toLowerCase().includes(searchQuery) ||
            item.description.toLowerCase().includes(searchQuery) ||
            item.category.toLowerCase().includes(searchQuery);
        
        const matchesType = currentFilter === 'all' || item.type === currentFilter;
        const matchesCategory = !selectedCategory || item.category === selectedCategory;
        
        return matchesSearch && matchesType && matchesCategory;
    });

    displayItems(filteredItems);
}



/**
 * Show/hide loading indicator
 */
function showLoading(show) {
    const loading = document.getElementById('loading');
    if (loading) {
        loading.style.display = show ? 'block' : 'none';
    }
}

/**
 * Show error message
 */
function showError(message) {
    const itemsGrid = document.getElementById('items-grid');
    if (itemsGrid) {
        itemsGrid.innerHTML = `
            <div class="col-12 text-center py-5">
                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                <p class="text-muted">${escapeHtml(message)}</p>
            </div>
        `;
    }
}

/**
 * Escape HTML to prevent XSS
 */
function escapeHtml(unsafe) {
    if (typeof unsafe !== 'string') return '';
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

/**
 * Show success message
 */
function showSuccessMessage(message) {
    const toast = document.createElement('div');
    toast.className = 'position-fixed top-0 end-0 p-3';
    toast.style.zIndex = '9999';
    toast.innerHTML = `
        <div class="toast show" role="alert">
            <div class="toast-header bg-success text-white">
                <i class="fas fa-check-circle me-2"></i>
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                ${escapeHtml(message)}
            </div>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}