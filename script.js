// Theme Toggle
document.getElementById('theme-toggle').addEventListener('change', function() {
    document.body.classList.toggle('dark-theme');
});

// Modal Handling
const modals = {
    register: document.getElementById('register-modal'),
    login: document.getElementById('login-modal'),
    profile: document.getElementById('profile-modal'),
    details: document.getElementById('details-modal'),
    postAd: document.getElementById('post-ad-modal')
};

const buttons = {
    register: document.getElementById('register-btn'),
    login: document.getElementById('login-btn'),
    profile: document.getElementById('profile-btn'),
    logout: document.getElementById('logout-btn'),
    postAd: document.getElementById('post-ad-btn'),
    reset: document.getElementById('reset-btn')
};

const closeButtons = document.querySelectorAll('.close-modal');

// Show modal
function showModal(modal) {
    modals[modal].style.display = 'block';
}

// Hide modal
function hideModal(modal) {
    modals[modal].style.display = 'none';
}

// Handle close button click
closeButtons.forEach(button => {
    button.addEventListener('click', function() {
        hideModal('register');
        hideModal('login');
        hideModal('profile');
        hideModal('details');
        hideModal('postAd');
    });
});

// Show appropriate modals
buttons.register.addEventListener('click', () => showModal('register'));
buttons.login.addEventListener('click', () => showModal('login'));
buttons.profile.addEventListener('click', () => showModal('profile'));
buttons.postAd.addEventListener('click', () => {
    if (currentUser) {
        showModal('postAd');
    } else {
        alert('Please sign in to post an ad.');
    }
});
buttons.logout.addEventListener('click', () => {
    currentUser = null;
    updateUI();
});
buttons.reset.addEventListener('click', () => {
    users = [];
    currentUser = null;
    updateUI();
    renderListings();
});

// User Data Storage
let users = [];
let currentUser = null;

function updateUI() {
    if (currentUser) {
        document.getElementById('login-btn').style.display = 'none';
        document.getElementById('register-btn').style.display = 'none';
        document.getElementById('profile-btn').style.display = 'inline-block';
        document.getElementById('logout-btn').style.display = 'inline-block';
        document.getElementById('post-ad-btn').style.display = 'inline-block';
    } else {
        document.getElementById('login-btn').style.display = 'inline-block';
        document.getElementById('register-btn').style.display = 'inline-block';
        document.getElementById('profile-btn').style.display = 'none';
        document.getElementById('logout-btn').style.display = 'none';
        document.getElementById('post-ad-btn').style.display = 'none';
    }
}

// Handle Register Form
document.getElementById('register-form').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const username = document.getElementById('register-username').value;
    const password = document.getElementById('register-password').value;
    const confirmPassword = document.getElementById('register-confirm-password').value;
    
    if (password !== confirmPassword) {
        alert('Passwords do not match');
        return;
    }
    
    if (users.some(user => user.username === username)) {
        alert('User already exists');
        return;
    }
    
    users.push({ username, password, profile: {} });
    alert('Registration successful');
    hideModal('register');
});

// Handle Login Form
document.getElementById('login-form').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const username = document.getElementById('login-username').value;
    const password = document.getElementById('login-password').value;
    
    const user = users.find(user => user.username === username && user.password === password);
    
    if (user) {
        currentUser = user;
        updateUI();
        hideModal('login');
        renderListings();
    } else {
        alert('Invalid username or password');
    }
});

// Handle Profile Form
document.getElementById('profile-form').addEventListener('submit', function(event) {
    event.preventDefault();
    
    if (!currentUser) {
        alert('You must be logged in');
        return;
    }
    
    currentUser.profile.fullName = document.getElementById('profile-fullname').value;
    currentUser.profile.email = document.getElementById('profile-email').value;
    currentUser.profile.phone = document.getElementById('profile-phone').value;
    currentUser.profile.city = document.getElementById('profile-city').value;
    
    alert('Profile updated');
    hideModal('profile');
});

// Handle Post Ad Form
document.getElementById('post-ad-form').addEventListener('submit', function(event) {
    event.preventDefault();
    
    if (!currentUser) {
        alert('You must be logged in to post an ad');
        return;
    }
    
    const title = document.getElementById('ad-title').value;
    const price = document.getElementById('ad-price').value;
    const category = document.getElementById('ad-category').value;
    
    if (!title || !price || !category) {
        alert('Please fill all fields');
        return;
    }
    
    const ad = { title, price, category, user: currentUser.username };
    ads.push(ad);
    alert('Ad posted successfully');
    hideModal('postAd');
    renderListings();
});

// Data and Render Listings
let ads = [];

function renderListings() {
    const listingGrid = document.querySelector('.listing-grid');
    listingGrid.innerHTML = '';
    
    ads.forEach(ad => {
        const item = document.createElement('div');
        item.className = 'listing-item';
        item.innerHTML = `
            <h3>${ad.title}</h3>
            <p>Price: $${ad.price}</p>
            <p>Category: ${ad.category}</p>
            <button class="view-details-btn" data-title="${ad.title}">View Details</button>
        `;
        listingGrid.appendChild(item);
    });
}

// Handle View Details Button
document.querySelector('.listing-grid').addEventListener('click', function(event) {
    if (event.target.classList.contains('view-details-btn')) {
        const adTitle = event.target.getAttribute('data-title');
        const ad = ads.find(ad => ad.title === adTitle);
        if (ad) {
            const detailsModal = document.getElementById('details-modal');
            detailsModal.querySelector('.modal-content').innerHTML = `
                <span class="close-modal">&times;</span>
                <h2>${ad.title}</h2>
                <p>Price: $${ad.price}</p>
                <p>Category: ${ad.category}</p>
                <p>Posted by: ${ad.user}</p>
                <button class="delete-ad-btn" data-title="${ad.title}">Delete Ad</button>
            `;
            detailsModal.style.display = 'block';
        }
    }
});

// Handle Delete Ad Button
document.getElementById('details-modal').addEventListener('click', function(event) {
    if (event.target.classList.contains('delete-ad-btn')) {
        const adTitle = event.target.getAttribute('data-title');
        if (!currentUser) {
            alert('You must be logged in to delete an ad');
            return;
        }
        
        const adIndex = ads.findIndex(ad => ad.title === adTitle && ad.user === currentUser.username);
        if (adIndex !== -1) {
            ads.splice(adIndex, 1);
            alert('Ad deleted successfully');
            renderListings();
            hideModal('details');
        } else {
            alert('You can only delete your own ads');
        }
    }
});

// Handling clicks outside of modals
window.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal')) {
        hideModal('register');
        hideModal('login');
        hideModal('profile');
        hideModal('details');
        hideModal('postAd');
    }
});
