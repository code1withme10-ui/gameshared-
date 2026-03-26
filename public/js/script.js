// Tiny Tots Creche - JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Mobile Navigation Toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mainNav = document.getElementById('mainNav');
    
    if (mobileMenuBtn && mainNav) {
        mobileMenuBtn.addEventListener('click', function() {
            mainNav.classList.toggle('active');
            
            // Toggle icon
            const icon = this.querySelector('i');
            if (icon) {
                if (mainNav.classList.contains('active')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        });
    }
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        if (mobileMenuBtn && mainNav && !mainNav.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
            mainNav.classList.remove('active');
            const icon = mobileMenuBtn.querySelector('i');
            if (icon) {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        }
    });
    
    // Smooth Scrolling for Anchor Links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Scroll Animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observe elements for animation
    document.querySelectorAll('.highlight-card, .contact-card, .news-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
    
    // Form Validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(form)) {
                e.preventDefault();
            }
        });
    });
    
    // Auto-hide Flash Messages
    const flashMessages = document.querySelectorAll('.flash-message');
    flashMessages.forEach(message => {
        setTimeout(() => {
            message.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => {
                message.remove();
            }, 300);
        }, 5000);
    });
    
    // Gallery Lightbox (if gallery elements exist)
    initGallery();
    
    // Form field animations
    initFormAnimations();
    
    // Initialize tooltips
    initTooltips();
});

// Form Validation Function
function validateForm(form) {
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
        const value = field.value.trim();
        const fieldName = field.name || field.id;
        
        // Remove previous error styling
        field.classList.remove('error');
        removeError(field);
        
        if (!value) {
            showError(field, `${getFieldLabel(fieldName)} is required`);
            isValid = false;
        } else {
            // Specific validations
            switch (field.type) {
                case 'email':
                    if (!validateEmail(value)) {
                        showError(field, 'Please enter a valid email address');
                        isValid = false;
                    }
                    break;
                case 'tel':
                    if (!validatePhone(value)) {
                        showError(field, 'Please enter a valid phone number');
                        isValid = false;
                    }
                    break;
                case 'file':
                    if (field.files.length > 0) {
                        const file = field.files[0];
                        const maxSize = 5 * 1024 * 1024; // 5MB
                        const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
                        
                        if (file.size > maxSize) {
                            showError(field, 'File size must be less than 5MB');
                            isValid = false;
                        }
                        
                        if (!allowedTypes.includes(file.type)) {
                            showError(field, 'Only JPG, PNG, and PDF files are allowed');
                            isValid = false;
                        }
                    }
                    break;
            }
        }
    });
    
    return isValid;
}

// Show Error Message
function showError(field, message) {
    field.classList.add('error');
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.textContent = message;
    
    field.parentNode.appendChild(errorDiv);
}

// Remove Error Message
function removeError(field) {
    const errorDiv = field.parentNode.querySelector('.field-error');
    if (errorDiv) {
        errorDiv.remove();
    }
}

// Get Field Label
function getFieldLabel(fieldName) {
    const labels = {
        'username': 'Username',
        'password': 'Password',
        'confirm_password': 'Confirm Password',
        'name': 'Full Name',
        'email': 'Email Address',
        'phone': 'Phone Number',
        'contactNumber': 'Contact Number',
        'emailAddress': 'Email Address',
        'parentFirstName': 'Parent First Name',
        'parentSurname': 'Parent Surname',
        'childFirstName': 'Child First Name',
        'childSurname': 'Child Surname',
        'dateOfBirth': 'Date of Birth',
        'childGender': 'Child Gender',
        'gradeApplyingFor': 'Grade Applying For'
    };
    
    return labels[fieldName] || fieldName.replace(/([A-Z])/g, ' $1').replace(/^./, str => str.toUpperCase());
}

// Email Validation
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Phone Validation
function validatePhone(phone) {
    const phoneRegex = /^[0-9\s\-\+\(\)]{10,}$/;
    return phoneRegex.test(phone);
}

// Gallery Lightbox
function initGallery() {
    const galleryImages = document.querySelectorAll('.gallery-image');
    const lightbox = document.createElement('div');
    lightbox.className = 'lightbox';
    lightbox.innerHTML = `
        <div class="lightbox-content">
            <span class="lightbox-close">&times;</span>
            <img class="lightbox-image" src="" alt="">
            <div class="lightbox-nav">
                <button class="lightbox-prev">&lt;</button>
                <button class="lightbox-next">&gt;</button>
            </div>
        </div>
    `;
    
    if (galleryImages.length > 0) {
        document.body.appendChild(lightbox);
        
        let currentImageIndex = 0;
        const images = Array.from(galleryImages);
        
        galleryImages.forEach((img, index) => {
            img.addEventListener('click', function() {
                currentImageIndex = index;
                showLightbox(this.src, this.alt);
            });
        });
        
        function showLightbox(src, alt) {
            const lightboxImage = lightbox.querySelector('.lightbox-image');
            lightboxImage.src = src;
            lightboxImage.alt = alt;
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeLightbox() {
            lightbox.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        function showNextImage() {
            currentImageIndex = (currentImageIndex + 1) % images.length;
            const nextImage = images[currentImageIndex];
            showLightbox(nextImage.src, nextImage.alt);
        }
        
        function showPrevImage() {
            currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
            const prevImage = images[currentImageIndex];
            showLightbox(prevImage.src, prevImage.alt);
        }
        
        // Event listeners
        lightbox.querySelector('.lightbox-close').addEventListener('click', closeLightbox);
        lightbox.querySelector('.lightbox-next').addEventListener('click', showNextImage);
        lightbox.querySelector('.lightbox-prev').addEventListener('click', showPrevImage);
        
        lightbox.addEventListener('click', function(e) {
            if (e.target === lightbox) {
                closeLightbox();
            }
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (lightbox.classList.contains('active')) {
                switch (e.key) {
                    case 'Escape':
                        closeLightbox();
                        break;
                    case 'ArrowRight':
                        showNextImage();
                        break;
                    case 'ArrowLeft':
                        showPrevImage();
                        break;
                }
            }
        });
    }
}

// Form Field Animations
function initFormAnimations() {
    const formFields = document.querySelectorAll('input, textarea, select');
    
    formFields.forEach(field => {
        // Add focus animations
        field.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        field.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.classList.remove('focused');
            }
        });
        
        // Check if field has value on load
        if (field.value) {
            field.parentElement.classList.add('focused');
        }
    });
}

// Tooltips
function initTooltips() {
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = this.getAttribute('data-tooltip');
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
            tooltip.style.top = rect.top - tooltip.offsetHeight - 10 + 'px';
            
            this.tooltip = tooltip;
        });
        
        element.addEventListener('mouseleave', function() {
            if (this.tooltip) {
                this.tooltip.remove();
                this.tooltip = null;
            }
        });
    });
}

// AJAX Helper Functions
function ajax(url, method = 'GET', data = null) {
    return fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': window.csrfToken || ''
        },
        body: data ? JSON.stringify(data) : null
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    });
}

// Utility Functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// Add CSS for dynamic elements
const style = document.createElement('style');
style.textContent = `
    .field-error {
        color: var(--error-red);
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: block;
        animation: slideDown 0.3s ease-out;
    }
    
    input.error, textarea.error, select.error {
        border-color: var(--error-red) !important;
        box-shadow: 0 0 8px rgba(255, 107, 107, 0.3) !important;
    }
    
    .lightbox {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        display: none;
        z-index: 10000;
        animation: fadeIn 0.3s ease-out;
    }
    
    .lightbox.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .lightbox-content {
        position: relative;
        max-width: 90%;
        max-height: 90%;
    }
    
    .lightbox-image {
        max-width: 100%;
        max-height: 80vh;
        border-radius: 10px;
    }
    
    .lightbox-close {
        position: absolute;
        top: -40px;
        right: 0;
        color: white;
        font-size: 2rem;
        cursor: pointer;
        background: none;
        border: none;
    }
    
    .lightbox-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 100%;
        display: flex;
        justify-content: space-between;
        padding: 0 20px;
    }
    
    .lightbox-prev, .lightbox-next {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: none;
        padding: 15px 20px;
        font-size: 1.5rem;
        cursor: pointer;
        border-radius: 50%;
        transition: background-color 0.3s ease;
    }
    
    .lightbox-prev:hover, .lightbox-next:hover {
        background: rgba(255, 255, 255, 0.4);
    }
    
    .tooltip {
        position: absolute;
        background: var(--text-dark);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.875rem;
        z-index: 1000;
        pointer-events: none;
        animation: fadeIn 0.3s ease-out;
    }
    
    .tooltip::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        border: 5px solid transparent;
        border-top-color: var(--text-dark);
    }
    
    @keyframes slideOutRight {
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .focused label {
        color: var(--primary-color);
        transform: translateY(-25px);
        font-size: 0.875rem;
    }
`;

document.head.appendChild(style);
