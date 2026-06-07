/* 🎨 Modern E-Shopping - Enhanced JavaScript */

document.addEventListener("DOMContentLoaded", () => {
    // 1. ✨ Enhanced Navbar Scroll Effect with smooth transitions
    const navbar = document.querySelector(".navbar-premium");
    if (navbar) {
        let lastScroll = 0;
        window.addEventListener("scroll", () => {
            const currentScroll = window.scrollY;
            
            if (currentScroll > 80) {
                navbar.classList.add("scrolled");
                
                // Hide navbar on scroll down, show on scroll up
                if (currentScroll > lastScroll && currentScroll > 200) {
                    navbar.style.transform = 'translateY(-100%)';
                } else {
                    navbar.style.transform = 'translateY(0)';
                }
            } else {
                navbar.classList.remove("scrolled");
                navbar.style.transform = 'translateY(0)';
            }
            
            lastScroll = currentScroll;
        });
    }

    // 2. 🔝 Back to Top Button
    const createBackToTop = () => {
        const btn = document.createElement('button');
        btn.className = 'back-to-top';
        btn.innerHTML = '<i class="bi bi-arrow-up"></i>';
        btn.setAttribute('aria-label', 'Back to top');
        document.body.appendChild(btn);

        window.addEventListener('scroll', () => {
            if (window.scrollY > 500) {
                btn.classList.add('show');
            } else {
                btn.classList.remove('show');
            }
        });

        btn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    };
    createBackToTop();

    // 3. ⭐ Enhanced Rating Star Interactive Picker
    const starPicker = document.querySelectorAll(".star-picker i");
    if (starPicker.length > 0) {
        const ratingInput = document.getElementById("rating-input");
        
        starPicker.forEach((star, index) => {
            // Hover effect
            star.addEventListener("mouseenter", () => {
                starPicker.forEach((s, i) => {
                    if (i <= index) {
                        s.classList.remove("bi-star", "star-rating-empty");
                        s.classList.add("bi-star-fill", "star-rating");
                    } else {
                        s.classList.remove("bi-star-fill", "star-rating");
                        s.classList.add("bi-star", "star-rating-empty");
                    }
                });
            });

            // Click to set rating
            star.addEventListener("click", () => {
                const rating = parseInt(star.getAttribute("data-rating"));
                ratingInput.value = rating;
                
                // Add success animation
                star.style.transform = 'scale(1.3)';
                setTimeout(() => {
                    star.style.transform = 'scale(1)';
                }, 200);
            });
        });

        // Reset on mouse leave
        const starContainer = document.querySelector(".star-picker");
        if (starContainer) {
            starContainer.addEventListener("mouseleave", () => {
                const currentRating = parseInt(ratingInput.value) || 0;
                starPicker.forEach((s, i) => {
                    if (i < currentRating) {
                        s.classList.remove("bi-star", "star-rating-empty");
                        s.classList.add("bi-star-fill", "star-rating");
                    } else {
                        s.classList.remove("bi-star-fill", "star-rating");
                        s.classList.add("bi-star", "star-rating-empty");
                    }
                });
            });
        }
    }

    // 4. 🎉 Enhanced Toast Notification System
    window.showToast = (message, type = 'success') => {
        let toastContainer = document.getElementById('toast-container');
        
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.style.cssText = `
                position: fixed;
                bottom: 30px;
                right: 30px;
                z-index: 9999;
                display: flex;
                flex-direction: column;
                gap: 15px;
                max-width: 400px;
            `;
            document.body.appendChild(toastContainer);
        }

        const toast = document.createElement('div');
        toast.className = 'animate-fade-in';
        
        const bgColor = {
            success: 'linear-gradient(135deg, rgba(11, 163, 96, 0.15), rgba(60, 186, 146, 0.1))',
            error: 'linear-gradient(135deg, rgba(255, 0, 110, 0.15), rgba(245, 87, 108, 0.1))',
            warning: 'linear-gradient(135deg, rgba(255, 214, 10, 0.15), rgba(255, 214, 10, 0.1))',
            info: 'linear-gradient(135deg, rgba(0, 217, 255, 0.15), rgba(79, 172, 254, 0.1))'
        }[type] || bgColor.success;

        const borderColor = {
            success: '#00f5a0',
            error: '#ff006e',
            warning: '#ffd60a',
            info: '#00d9ff'
        }[type] || '#00f5a0';

        const iconClass = {
            success: 'bi-check-circle-fill',
            error: 'bi-x-circle-fill',
            warning: 'bi-exclamation-triangle-fill',
            info: 'bi-info-circle-fill'
        }[type] || 'bi-check-circle-fill';

        toast.style.cssText = `
            background: ${bgColor};
            backdrop-filter: blur(15px);
            border: 2px solid ${borderColor};
            border-radius: 12px;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            min-width: 300px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            animation: slideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        `;

        toast.innerHTML = `
            <i class="bi ${iconClass}" style="font-size: 1.5rem; color: ${borderColor};"></i>
            <span style="color: #fff; flex: 1; font-weight: 500;">${message}</span>
            <button onclick="this.parentElement.remove()" style="background: none; border: none; color: #fff; font-size: 1.2rem; cursor: pointer; opacity: 0.7; padding: 0; margin: 0; line-height: 1;">
                <i class="bi bi-x"></i>
            </button>
        `;

        toastContainer.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(400px)';
            toast.style.transition = 'all 0.4s ease';
            setTimeout(() => toast.remove(), 400);
        }, 5000);
    };

    // Add slide in animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(400px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    `;
    document.head.appendChild(style);

    // 5. 🔔 Check for session flash messages
    const flashElement = document.getElementById('session-flash-data');
    if (flashElement) {
        const message = flashElement.getAttribute('data-message');
        const type = flashElement.getAttribute('data-type');
        if (message) {
            window.showToast(message, type);
        }
    }

    // 6. 🎬 Animate elements on scroll (Intersection Observer)
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe all cards and sections
    document.querySelectorAll('.card-glass, .stats-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });

    // 7. 🖱️ Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#' && document.querySelector(href)) {
                e.preventDefault();
                document.querySelector(href).scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // 8. 💫 Add loading state to forms
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.disabled) {
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...';
                
                // Re-enable after 10 seconds as fallback
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }, 10000);
            }
        });
    });

    // 9. 🎨 Dynamic hover effects for cards
    document.querySelectorAll('.card-glass').forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const rotateX = (y - centerY) / 20;
            const rotateY = (centerX - x) / 20;
            
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-12px) scale(1.02)`;
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = '';
        });
    });

    // 10. 📱 Mobile menu enhancement
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    if (navbarToggler && navbarCollapse) {
        navbarToggler.addEventListener('click', () => {
            navbarCollapse.style.transition = 'all 0.3s ease';
        });
    }

    // 11. 🔍 Image lazy loading fallback
    if ('loading' in HTMLImageElement.prototype) {
        const images = document.querySelectorAll('img[loading="lazy"]');
        images.forEach(img => {
            img.src = img.dataset.src || img.src;
        });
    }

    // 12. ⚡ Initialize tooltips if Bootstrap is loaded
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(el => new bootstrap.Tooltip(el));
    }

    // 13. 🎯 Add active state to current page in navigation
    const currentPath = window.location.pathname;
    document.querySelectorAll('.nav-link-premium').forEach(link => {
        if (link.getAttribute('href') === currentPath || 
            (currentPath.includes(link.getAttribute('href')) && link.getAttribute('href') !== '/')) {
            link.classList.add('active');
        }
    });

    console.log('🎨 Modern E-Shopping Platform initialized successfully!');
});
