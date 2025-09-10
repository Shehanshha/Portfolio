// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
            
            // Close mobile menu if open
            const sidebar = document.querySelector('.sidebar');
            if (sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
        }
    });
});

// Mobile menu toggle functionality
const mobileMenu = document.getElementById('mobile-menu');
const sidebar = document.querySelector('.sidebar');

// Toggle sidebar when menu button is clicked
mobileMenu.addEventListener('click', (e) => {
    e.stopPropagation(); // Prevent this click from triggering the document click handler
    sidebar.classList.toggle('active');
});

// Close sidebar when clicking outside of it
document.addEventListener('click', (e) => {
    if (!sidebar.contains(e.target) && e.target !== mobileMenu) {
        sidebar.classList.remove('active');
    }
});

// Close sidebar when a link is clicked
document.querySelectorAll('.sidebar-links a').forEach(link => {
    link.addEventListener('click', () => {
        sidebar.classList.remove('active');
    });
});

// Form submission handling
const contactForm = document.querySelector('.contact form');
if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
        // Get form values
        const formData = new FormData(contactForm);
        const submitButton = contactForm.querySelector('button');
        const originalButtonText = submitButton.innerHTML;
        
        // Show loading state
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
        submitButton.disabled = true;

        // Send data via Fetch API
        fetch('contact.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            // Create a temporary div to parse the response
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data;
            
            // Find success/error message in response
            const responseMessage = tempDiv.querySelector('.success-message, .error-message');
            
            if (responseMessage) {
                // Insert message before the form
                contactForm.parentNode.insertBefore(responseMessage, contactForm);
                
                // Scroll to message
                responseMessage.scrollIntoView({ behavior: 'smooth' });
                
                // If success, show confirmation
                if (responseMessage.classList.contains('success-message')) {
                    submitButton.innerHTML = '<i class="fas fa-check"></i> Message Sent!';
                    submitButton.style.backgroundColor = '#4CAF50';
                    contactForm.reset();
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.innerHTML = '<h3>Error</h3><p>There was a problem sending your message. Please try again later.</p>';
            contactForm.parentNode.insertBefore(errorDiv, contactForm);
        })
        .finally(() => {
            // Reset button after 3 seconds
            setTimeout(() => {
                submitButton.innerHTML = originalButtonText;
                submitButton.style.backgroundColor = '';
                submitButton.disabled = false;
            }, 3000);
        });
    });
}

// Initialize AOS
AOS.init({
    duration: 800,
    once: true,
    easing: 'ease-in-out',
    offset: 100
});

// Set current year in footer
document.getElementById('current-year').textContent = new Date().getFullYear();

// Back to top button behavior
window.addEventListener('scroll', () => {
    const backToTop = document.querySelector('.back-to-top');
    if (window.scrollY > 300) {
        backToTop.style.opacity = '1';
        backToTop.style.visibility = 'visible';
    } else {
        backToTop.style.opacity = '0';
        backToTop.style.visibility = 'hidden';
    }
});

// Animate progress bars when they come into view
const progressBars = document.querySelectorAll('.progress');
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const width = entry.target.style.width;
            entry.target.style.width = '0';
            setTimeout(() => {
                entry.target.style.width = width;
            }, 100);
        }
    });
}, { threshold: 0.5 });

progressBars.forEach(bar => {
    observer.observe(bar);
});

// Add hover effect to timeline cards
const timelineCards = document.querySelectorAll('.timeline-card');
timelineCards.forEach(card => {
    card.addEventListener('mouseenter', () => {
        const year = card.querySelector('.timeline-year');
        year.style.color = '#fff';
        year.style.backgroundColor = '#ff6f61';
        year.style.padding = '5px 10px';
        year.style.borderRadius = '5px';
        year.style.transition = 'all 0.3s ease';
    });
    
    card.addEventListener('mouseleave', () => {
        const year = card.querySelector('.timeline-year');
        year.style.color = '';
        year.style.backgroundColor = '';
        year.style.padding = '';
        year.style.borderRadius = '';
    });
});
// Toggle category expansion
function toggleCategory(categoryId) {
    const category = document.getElementById(categoryId);
    const chevron = category.previousElementSibling.querySelector('.fa-chevron-down');
    
    category.classList.toggle('active');
    
    if (category.classList.contains('active')) {
        chevron.style.transform = 'rotate(180deg)';
    } else {
        chevron.style.transform = 'rotate(0deg)';
    }
}

// Initialize categories (optional: open first category by default)
document.addEventListener('DOMContentLoaded', function() {
    // Close all categories initially
    const categories = document.querySelectorAll('.category-content');
    categories.forEach(category => {
        category.classList.remove('active');
    });
    
    // Alternatively, open the first category by default
    // toggleCategory('data-analysis');
});

function typeWriter(element, text, i = 0) {
    const nameStart = text.indexOf("Sanyam Kudale");
    const nameEnd = nameStart + "Sanyam Kudale".length;
    
    let displayText = text.substring(0, i);
    
    if (i > nameStart) {
        const beforeName = text.substring(0, nameStart);
        const namePart = text.substring(nameStart, Math.min(i, nameEnd));
        const afterName = i > nameEnd ? text.substring(nameEnd, i) : '';
        
        element.innerHTML = `${beforeName}<span class="highlight">${namePart}</span>${afterName}`;
    } else {
        element.innerHTML = displayText;
    }
    
    if (i < text.length) {
        i++;
        setTimeout(() => typeWriter(element, text, i), 100);
    } else {
        // Ensure final state has the highlight
        const beforeName = text.substring(0, nameStart);
        const namePart = text.substring(nameStart, nameEnd);
        element.innerHTML = `${beforeName}<span class="highlight">${namePart}</span>`;
    }
}
const heroTitle = document.querySelector('.hero-content h1');
if (heroTitle) {
    const text = heroTitle.textContent;
    heroTitle.textContent = '';
    setTimeout(() => typeWriter(heroTitle, text), 500);
}
