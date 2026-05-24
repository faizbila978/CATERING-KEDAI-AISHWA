// ========== SCROLL REVEAL ANIMATION ==========
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Intersection Observer untuk scroll reveal animation
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.animation = `${entry.target.classList.contains('scroll-reveal') ? 'scrollReveal' : 'fadeUp'} 0.8s ease-out forwards`;
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe semua elemen dengan class scroll-reveal
    document.querySelectorAll('.scroll-reveal').forEach(el => {
        el.style.opacity = '0';
        observer.observe(el);
    });

    // Smooth scroll untuk internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#' && document.querySelector(href)) {
                e.preventDefault();
                const target = document.querySelector(href);
                const offsetTop = target.offsetTop - 100;
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });
});

// ========== FEEDBACK TAB SWITCHING ==========
document.addEventListener('DOMContentLoaded', function() {
    const feedbackTabs = document.querySelectorAll('.feedback-tab');
    
    feedbackTabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            
            const tabName = this.getAttribute('data-tab');
            const tabContent = document.getElementById(tabName + '-tab');
            
            if (!tabContent) return;
            
            // Remove active class from all tabs and contents
            document.querySelectorAll('.feedback-tab').forEach(t => {
                t.classList.remove('active');
            });
            document.querySelectorAll('.feedback-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Add active class to clicked tab and corresponding content
            this.classList.add('active');
            tabContent.classList.add('active');
        });
    });
});

// ========== NAVBAR SCROLL EFFECT ==========
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('.navbar-premium');
    let lastScrollTop = 0;
    
    window.addEventListener('scroll', function() {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > 50) {
            navbar.style.background = 'rgba(255, 255, 255, 0.85)';
            navbar.style.boxShadow = '0 4px 30px rgba(0, 0, 0, 0.08)';
        } else {
            navbar.style.background = 'rgba(255, 255, 255, 0.7)';
            navbar.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.02)';
        }
        
        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    });
});

// ========== ACTIVE NAV LINK HIGHLIGHTING ==========
document.addEventListener('DOMContentLoaded', function() {
    const currentPage = window.location.pathname.split('/').pop() || 'index.php';
    
    document.querySelectorAll('.nav-link-premium').forEach(link => {
        const href = link.getAttribute('href');
        if (href && href.includes(currentPage)) {
            link.style.color = 'var(--primary-pink)';
        }
    });
});

// ========== FORM SUBMISSION ANIMATION ==========
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.style.pointerEvents = 'none';
                submitBtn.style.opacity = '0.7';
                submitBtn.innerHTML = '⏳ Processing...';
            }
        });
    });
});

// ========== DROPDOWN MENU ANIMATION ==========
document.addEventListener('DOMContentLoaded', function() {
    const dropdownTriggers = document.querySelectorAll('.dropdown-toggle');
    
    dropdownTriggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            const dropdownMenu = this.nextElementSibling;
            if (dropdownMenu && dropdownMenu.classList.contains('dropdown-menu')) {
                const isVisible = dropdownMenu.classList.contains('show');
                
                // Close all other dropdowns
                document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                    if (menu !== dropdownMenu) {
                        menu.classList.remove('show');
                    }
                });
                
                // Toggle current dropdown
                if (isVisible) {
                    dropdownMenu.classList.remove('show');
                } else {
                    dropdownMenu.classList.add('show');
                }
            }
        });
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown') && !e.target.closest('.dropdown-toggle')) {
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                menu.classList.remove('show');
            });
        }
    });
});

// ========== MENU CARD CLICK ANIMATION ==========
document.addEventListener('DOMContentLoaded', function() {
    const menuCards = document.querySelectorAll('.menu-card');
    
    menuCards.forEach(card => {
        card.addEventListener('click', function(e) {
            // Tambah ripple effect
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            
            // Note: Ripple effect membutuhkan CSS tambahan di stylesheet
            // Anda bisa menambahkan ini jika ingin full ripple effect
        });
    });
});

// ========== BUTTON HOVER EFFECT ==========
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.btn-hero-primary, .btn-hero-secondary, .btn-submit, .btn-explore');
    
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});

// ========== LAZY LOAD IMAGES ==========
document.addEventListener('DOMContentLoaded', function() {
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                    }
                    imageObserver.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
});

// ========== ACTIVE PAGE INDICATOR ==========
document.addEventListener('DOMContentLoaded', function() {
    const currentUrl = window.location.pathname;
    
    document.querySelectorAll('.nav-link-premium').forEach(link => {
        const href = link.getAttribute('href');
        
        if (href === '#') {
            if (currentUrl.endsWith('index.php') || currentUrl.endsWith('/')) {
                link.style.color = 'var(--primary-pink)';
                link.style.fontWeight = '700';
            }
        } else if (currentUrl.includes(href)) {
            link.style.color = 'var(--primary-pink)';
            link.style.fontWeight = '700';
        }
    });
});

// ========== FORM VALIDATION ==========
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
        
        inputs.forEach(input => {
            input.addEventListener('invalid', function(e) {
                e.preventDefault();
                this.style.borderColor = '#dc3545';
                this.style.boxShadow = '0 0 0 3px rgba(220, 53, 69, 0.1)';
            });
            
            input.addEventListener('input', function() {
                if (this.validity.valid) {
                    this.style.borderColor = '#28a745';
                    this.style.boxShadow = '0 0 0 3px rgba(40, 167, 69, 0.1)';
                }
            });
        });
    });
});

// ========== MOBILE MENU TOGGLE ==========
document.addEventListener('DOMContentLoaded', function() {
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    if (navbarToggler && navbarCollapse) {
        navbarToggler.addEventListener('click', function() {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            
            if (!isExpanded) {
                // Menu sedang dibuka
                navbarCollapse.style.animation = 'slideDown 0.3s ease-out';
            } else {
                // Menu sedang ditutup
                navbarCollapse.style.animation = 'slideUp 0.3s ease-out';
            }
        });
    }
});

// ========== PAGE LOAD ANIMATION ==========
window.addEventListener('load', function() {
    document.body.style.opacity = '1';
    document.body.style.animation = 'fadeIn 0.5s ease-out';
});

// ========== INTERSECTION OBSERVER FOR LAZY LOADING ==========
if ('IntersectionObserver' in window) {
    const lazyElements = document.querySelectorAll('.scroll-reveal, .menu-item, .testimonial-card');
    const elementObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    lazyElements.forEach(element => {
        elementObserver.observe(element);
    });
}

// ========== SMOOTH SCROLL BEHAVIOR ==========
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        
        if (href === '#') {
            e.preventDefault();
            return;
        }
        
        const target = document.querySelector(href);
        if (target) {
            e.preventDefault();
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// ========== UTILITY: Add active class to current page link ==========
function highlightCurrentPage() {
    const currentPage = window.location.pathname.split('/').pop() || '';
    
    document.querySelectorAll('.nav-link-premium').forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPage.includes(href.replace('php', '').replace('.', ''))) {
            link.classList.add('active');
        }
    });
}

document.addEventListener('DOMContentLoaded', highlightCurrentPage);