document.addEventListener('DOMContentLoaded', function() {
    // Gallery filtering
    const filterButtons = document.querySelectorAll('.filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            const category = this.getAttribute('data-category');
            
            // Filter gallery items
            galleryItems.forEach(item => {
                if (category === 'all' || item.getAttribute('data-category') === category) {
                    item.style.display = 'block';
                    
                    // Add fade-in animation
                    item.style.opacity = '0';
                    setTimeout(() => {
                        item.style.transition = 'opacity 0.5s ease';
                        item.style.opacity = '1';
                    }, 10);
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
    
    // Lightbox functionality
    const lightbox = document.querySelector('.lightbox');
    const lightboxImage = document.querySelector('.lightbox-image');
    const lightboxTitle = document.querySelector('.lightbox-title');
    const lightboxDescription = document.querySelector('.lightbox-description');
    const lightboxClose = document.querySelector('.lightbox-close');
    const lightboxPrev = document.querySelector('.lightbox-prev');
    const lightboxNext = document.querySelector('.lightbox-next');
    const viewButtons = document.querySelectorAll('.view-full');
    
    let currentImageIndex = 0;
    const galleryData = [
        {
            id: 1,
            title: 'Luxury Suite',
            image_url: 'https://images.pexels.com/photos/271624/pexels-photo-271624.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            description: 'Spacious suite with modern amenities and elegant design.'
        },
        {
            id: 2,
            title: 'Presidential Suite',
            image_url: 'https://images.pexels.com/photos/1579253/pexels-photo-1579253.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            description: 'Our most luxurious accommodation with breathtaking city views.'
        },
        {
            id: 3,
            title: 'Deluxe Twin Room',
            image_url: 'https://images.pexels.com/photos/271618/pexels-photo-271618.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            description: 'Comfortable twin beds in a beautifully decorated room.'
        },
        {
            id: 4,
            title: 'Executive Bathroom',
            image_url: 'https://images.pexels.com/photos/1457847/pexels-photo-1457847.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            description: 'Marble bathroom with premium fixtures and amenities.'
        },
        {
            id: 5,
            title: 'Fine Dining Restaurant',
            image_url: 'https://images.pexels.com/photos/941861/pexels-photo-941861.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            description: 'Elegant restaurant offering gourmet cuisine and fine wines.'
        },
        {
            id: 6,
            title: 'Signature Dish',
            image_url: 'https://images.pexels.com/photos/299347/pexels-photo-299347.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            description: 'Chef\'s specialty prepared with the finest ingredients.'
        },
        {
            id: 7,
            title: 'Cocktail Bar',
            image_url: 'https://images.pexels.com/photos/3201920/pexels-photo-3201920.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            description: 'Stylish bar serving innovative cocktails and premium spirits.'
        },
        {
            id: 8,
            title: 'Breakfast Buffet',
            image_url: 'https://images.pexels.com/photos/5175515/pexels-photo-5175515.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            description: 'Start your day with our extensive breakfast selection.'
        },
        {
            id: 9,
            title: 'Spa Treatment Room',
            image_url: 'https://images.pexels.com/photos/3757942/pexels-photo-3757942.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            description: 'Tranquil space for rejuvenating spa treatments.'
        },
        {
            id: 10,
            title: 'Indoor Pool',
            image_url: 'https://images.pexels.com/photos/261327/pexels-photo-261327.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            description: 'Temperature-controlled pool for year-round swimming.'
        },
        {
            id: 11,
            title: 'Fitness Center',
            image_url: 'https://images.pexels.com/photos/3076509/pexels-photo-3076509.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            description: 'State-of-the-art equipment for maintaining your fitness routine.'
        },
        {
            id: 12,
            title: 'Wedding Reception',
            image_url: 'https://images.pexels.com/photos/3585798/pexels-photo-3585798.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            description: 'Elegant venue for your special day.'
        },
        {
            id: 13,
            title: 'Conference Room',
            image_url: 'https://images.pexels.com/photos/416320/pexels-photo-416320.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            description: 'Professional setting for successful business meetings.'
        },
        {
            id: 14,
            title: 'Hotel Exterior',
            image_url: 'https://images.pexels.com/photos/258154/pexels-photo-258154.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            description: 'Impressive architecture and welcoming entrance.'
        },
        {
            id: 15,
            title: 'Garden Terrace',
            image_url: 'https://images.pexels.com/photos/631477/pexels-photo-631477.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            description: 'Beautifully landscaped outdoor space for relaxation.'
        },
        {
            id: 16,
            title: 'Rooftop View',
            image_url: 'https://images.pexels.com/photos/2034335/pexels-photo-2034335.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            description: 'Panoramic views of the city skyline.'
        }
    ];
    
    // Open lightbox
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const imageId = parseInt(this.getAttribute('data-id'));
            const imageData = galleryData.find(item => item.id === imageId);
            
            if (imageData) {
                currentImageIndex = galleryData.findIndex(item => item.id === imageId);
                
                lightboxImage.src = imageData.image_url;
                lightboxTitle.textContent = imageData.title;
                lightboxDescription.textContent = imageData.description;
                
                lightbox.classList.add('open');
                document.body.style.overflow = 'hidden';
            }
        });
    });
    
    // Close lightbox
    lightboxClose.addEventListener('click', function() {
        lightbox.classList.remove('open');
        document.body.style.overflow = '';
    });
    
    // Close lightbox when clicking outside image
    lightbox.addEventListener('click', function(event) {
        if (event.target === lightbox) {
            lightbox.classList.remove('open');
            document.body.style.overflow = '';
        }
    });
    
    // Navigate to previous image
    lightboxPrev.addEventListener('click', function() {
        currentImageIndex = (currentImageIndex - 1 + galleryData.length) % galleryData.length;
        updateLightbox();
    });
    
    // Navigate to next image
    lightboxNext.addEventListener('click', function() {
        currentImageIndex = (currentImageIndex + 1) % galleryData.length;
        updateLightbox();
    });
    
    // Update lightbox with current image
    function updateLightbox() {
        const imageData = galleryData[currentImageIndex];
        
        // Add fade-out effect
        lightboxImage.style.opacity = '0';
        
        setTimeout(() => {
            lightboxImage.src = imageData.image_url;
            lightboxTitle.textContent = imageData.title;
            lightboxDescription.textContent = imageData.description;
            
            // Add fade-in effect after image has loaded
            lightboxImage.onload = function() {
                lightboxImage.style.opacity = '1';
            };
        }, 300);
    }
    
    // Keyboard navigation
    document.addEventListener('keydown', function(event) {
        if (lightbox.classList.contains('open')) {
            if (event.key === 'Escape') {
                lightbox.classList.remove('open');
                document.body.style.overflow = '';
            } else if (event.key === 'ArrowLeft') {
                currentImageIndex = (currentImageIndex - 1 + galleryData.length) % galleryData.length;
                updateLightbox();
            } else if (event.key === 'ArrowRight') {
                currentImageIndex = (currentImageIndex + 1) % galleryData.length;
                updateLightbox();
            }
        }
    });
    
    // Video gallery and virtual tour interactions
    const videoThumbnails = document.querySelectorAll('.video-thumbnail, .tour-image');
    
    videoThumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            alert('Video playback would start here in a real implementation');
        });
    });
    
    // Instagram grid interactions
    const instagramItems = document.querySelectorAll('.instagram-item');
    
    instagramItems.forEach(item => {
        item.addEventListener('click', function() {
            window.open('https://www.instagram.com', '_blank');
        });
    });
});