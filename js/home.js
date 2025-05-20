document.addEventListener('DOMContentLoaded', function() {
    // Testimonial Slider
    const testimonials = document.querySelectorAll('.testimonial-slide');
    const prevButton = document.querySelector('.prev-testimonial');
    const nextButton = document.querySelector('.next-testimonial');
    let currentTestimonial = 0;
    
    // Hide all testimonials except the first one
    if (testimonials.length > 0) {
        testimonials.forEach((testimonial, index) => {
            if (index !== 0) {
                testimonial.style.display = 'none';
            }
        });
        
        // Function to show a specific testimonial
        function showTestimonial(index) {
            testimonials.forEach(testimonial => {
                testimonial.style.display = 'none';
            });
            
            testimonials[index].style.display = 'block';
            testimonials[index].style.opacity = '0';
            
            // Fade in animation
            setTimeout(() => {
                testimonials[index].style.transition = 'opacity 0.5s ease';
                testimonials[index].style.opacity = '1';
            }, 50);
        }
        
        // Event listeners for next and previous buttons
        if (prevButton && nextButton) {
            nextButton.addEventListener('click', function() {
                currentTestimonial = (currentTestimonial + 1) % testimonials.length;
                showTestimonial(currentTestimonial);
            });
            
            prevButton.addEventListener('click', function() {
                currentTestimonial = (currentTestimonial - 1 + testimonials.length) % testimonials.length;
                showTestimonial(currentTestimonial);
            });
            
            // Auto-advance the testimonials every 5 seconds
            setInterval(function() {
                currentTestimonial = (currentTestimonial + 1) % testimonials.length;
                showTestimonial(currentTestimonial);
            }, 5000);
        }
    }
    
    // Room hover effect enhancement
    const roomCards = document.querySelectorAll('.room-card');
    
    roomCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.querySelector('.room-image img').style.transform = 'scale(1.1)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.querySelector('.room-image img').style.transform = 'scale(1)';
        });
    });
    
    // Service card animation
    const serviceCards = document.querySelectorAll('.service-card');
    
    serviceCards.forEach((card, index) => {
        // Add staggered animation delay
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        card.style.transitionDelay = `${index * 0.1}s`;
        
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });
            
            observer.observe(card);
        } else {
            // Fallback for browsers that don't support IntersectionObserver
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }
    });
    
    // Parallax effect for CTA section
    const ctaSection = document.querySelector('.cta');
    
    if (ctaSection) {
        window.addEventListener('scroll', function() {
            const scrollPosition = window.pageYOffset;
            const ctaPosition = ctaSection.offsetTop;
            const windowHeight = window.innerHeight;
            
            if (scrollPosition + windowHeight > ctaPosition) {
                const yPos = -(scrollPosition - ctaPosition) * 0.2;
                ctaSection.style.backgroundPositionY = `${yPos}px`;
            }
        });
    }
});