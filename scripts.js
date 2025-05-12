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
    contactForm.addEventListener('submit', function (e) {
        e.preventDefault();
        
        // Get form values
        const formData = new FormData(contactForm);
        const data = Object.fromEntries(formData.entries());
        
        // Here you would typically send the data to a server
        console.log('Form submitted:', data);
        
        // Show success message
        const submitButton = contactForm.querySelector('button');
        submitButton.innerHTML = '<i class="fas fa-check"></i> Message Sent!';
        submitButton.style.backgroundColor = '#4CAF50';
        
        // Reset form after 3 seconds
        setTimeout(() => {
            contactForm.reset();
            submitButton.innerHTML = 'Send Message';
            submitButton.style.backgroundColor = '';
        }, 3000);
    });
}

// Initialize AOS (Animate On Scroll)
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
